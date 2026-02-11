<?php

namespace MBMigration\Analysis;

use Exception;
use MBMigration\Core\Logger;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * AIComparisonService
 *
 * Сервис для сравнения страниц с использованием OpenAI GPT-4 Vision API или Azure OpenAI
 */
class AIComparisonService
{
    /**
     * @var Client
     */
    private $httpClient;
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $model;
    /**
     * @var string
     */
    private $apiUrl;
    /**
     * @var bool
     */
    private $isAzure;
    /**
     * @var string
     */
    private $apiVersion;
    /**
     * @var PromptBuilder
     */
    private $promptBuilder;

    public function __construct(?string $apiKey = null, ?string $model = null)
    {
        // Проверяем наличие Azure OpenAI параметров
        $azureEndpoint = $_ENV['AZURE_API_ENDPOINT'] ?? '';
        $azureKey = $_ENV['AZURE_API_KEY'] ?? '';
        $azureVersion = $_ENV['AZURE_API_VERSION'] ?? '2024-12-01-preview';
        $azureModel = $_ENV['AZURE_API_MODEL_NAME'] ?? '';

        // Проверяем наличие стандартного OpenAI
        $openAiKey = $_ENV['OPENAI_API_KEY'] ?? '';
        $openAiModel = $_ENV['OPENAI_MODEL'] ?? 'gpt-4o';

        // Определяем какой API использовать (приоритет у Azure если настроен)
        if (!empty($azureEndpoint) && !empty($azureKey) && !empty($azureModel)) {
            $this->isAzure = true;
            $this->apiKey = $apiKey ?? $azureKey;
            $this->model = $model ?? $azureModel;
            $this->apiVersion = $azureVersion;

            // Формируем URL для Azure OpenAI
            // Формат: {endpoint}/openai/deployments/{deployment}/chat/completions?api-version={api-version}
            $endpoint = rtrim($azureEndpoint, '/');
            $this->apiUrl = $endpoint . '/openai/deployments/' . urlencode($this->model) . '/chat/completions?api-version=' . urlencode($this->apiVersion);

            Logger::instance()->info("[Quality Analysis] Using Azure OpenAI", [
                'endpoint' => $endpoint,
                'model' => $this->model,
                'api_version' => $this->apiVersion
            ]);
        } elseif (!empty($openAiKey)) {
            $this->isAzure = false;
            $this->apiKey = $apiKey ?? $openAiKey;
            $this->model = $model ?? $openAiModel;
            $this->apiUrl = 'https://api.openai.com/v1/chat/completions';

            Logger::instance()->info("[Quality Analysis] Using OpenAI", [
                'model' => $this->model
            ]);
        } else {
            // Нет настроенных API ключей - используем мок
            Logger::instance()->warning("[Quality Analysis] ⚠️ USING MOCK AI SERVICE - No API keys configured!");
            Logger::instance()->warning("[Quality Analysis] ⚠️ Configure either AZURE_API_* or OPENAI_API_KEY in .env file");
            $this->apiKey = 'MOCK_FOR_TESTING';
            $this->isAzure = false;
            return;
        }

        // Создаем HTTP клиент
        $headers = [
            'Content-Type' => 'application/json',
        ];

        if ($this->isAzure) {
            // Azure OpenAI использует заголовок api-key
            $headers['api-key'] = $this->apiKey;
        } else {
            // Стандартный OpenAI использует Authorization Bearer
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
        }

        $this->httpClient = new Client([
            'timeout' => 120, // Увеличенный таймаут для анализа
            'headers' => $headers
        ]);

        // Инициализируем PromptBuilder для загрузки тематических промптов
        $this->promptBuilder = new PromptBuilder();
    }

    /**
     * Сравнить две страницы используя AI
     *
     * @param array $sourceData Данные исходной страницы (screenshot_path, html, url)
     * @param array $migratedData Данные мигрированной страницы (screenshot_path, html, url)
     * @param string $themeName Название темы (designName) для использования тематического промпта
     * @return array Результат анализа с оценкой качества и найденными проблемами
     * @throws Exception
     */
    public function comparePages(array $sourceData, array $migratedData, string $themeName = 'default'): array
    {
        Logger::instance()->info("[Quality Analysis] Starting AI comparison", [
            'source_url' => $sourceData['url'],
            'migrated_url' => $migratedData['url'],
            'model' => $this->model,
            'theme' => $themeName
        ]);

        // Проверяем использование мока
        if ($this->apiKey === 'MOCK_FOR_TESTING' || empty($this->apiKey) || !isset($this->httpClient)) {
            Logger::instance()->warning("[Quality Analysis] ⚠️ USING MOCK AI RESPONSE - Returning test data");
            return $this->getMockAnalysisResult($sourceData, $migratedData);
        }

        try {
            $startTime = microtime(true);

            // Read screenshots and encode to base64 (with correct MIME type for API)
            Logger::instance()->debug("[Quality Analysis] Encoding source screenshot to base64", [
                'screenshot_path' => $sourceData['screenshot_path']
            ]);
            $sourceEncoded = $this->encodeImage($sourceData['screenshot_path']);
            $sourceScreenshot = $sourceEncoded['base64'];
            $sourceMime = $sourceEncoded['mime'];
            Logger::instance()->debug("[Quality Analysis] Source screenshot encoded", [
                'base64_size_bytes' => strlen($sourceScreenshot),
                'mime' => $sourceMime
            ]);

            Logger::instance()->debug("[Quality Analysis] Encoding migrated screenshot to base64", [
                'screenshot_path' => $migratedData['screenshot_path']
            ]);
            $migratedEncoded = $this->encodeImage($migratedData['screenshot_path']);
            $migratedScreenshot = $migratedEncoded['base64'];
            $migratedMime = $migratedEncoded['mime'];
            Logger::instance()->debug("[Quality Analysis] Migrated screenshot encoded", [
                'base64_size_bytes' => strlen($migratedScreenshot),
                'mime' => $migratedMime
            ]);

            // Подготавливаем промпт для анализа используя PromptBuilder
            Logger::instance()->debug("[Quality Analysis] Building analysis prompt", [
                'theme' => $themeName
            ]);
            $prompt = $this->promptBuilder->buildPrompt($sourceData, $migratedData, $themeName);
            $promptLength = strlen($prompt);
            Logger::instance()->info("[Quality Analysis] Analysis prompt prepared", [
                'prompt_length' => $promptLength,
                'source_elements' => $this->extractKeyInfo($sourceData['html'] ?? []),
                'migrated_elements' => $this->extractKeyInfo($migratedData['html'] ?? [])
            ]);

            // Отправляем запрос в API
            $apiName = $this->isAzure ? 'Azure OpenAI' : 'OpenAI';

            // Подсчитываем приблизительное количество токенов в промпте
            // Примерная оценка: 1 токен ≈ 4 символа для английского текста, для русского может быть больше
            $estimatedPromptTokens = (int)ceil(strlen($prompt) / 3); // Консервативная оценка
            $estimatedImageTokens = 170; // Примерно для изображения с detail='high' (85 токенов на изображение * 2)
            $estimatedTotalInputTokens = $estimatedPromptTokens + $estimatedImageTokens;

            Logger::instance()->info("[Quality Analysis] Sending request to {$apiName} API", [
                'model' => $this->model,
                'api_url' => $this->apiUrl,
                'is_azure' => $this->isAzure,
                'estimated_prompt_tokens' => $estimatedPromptTokens,
                'estimated_image_tokens' => $estimatedImageTokens,
                'estimated_total_input_tokens' => $estimatedTotalInputTokens,
                'max_tokens_requested' => 2000
            ]);

            $response = $this->sendAnalysisRequest($sourceScreenshot, $migratedScreenshot, $prompt, $sourceMime, $migratedMime);

            // Извлекаем информацию о токенах из ответа
            $usage = $response['usage'] ?? null;
            $promptTokens = $usage['prompt_tokens'] ?? null;
            $completionTokens = $usage['completion_tokens'] ?? null;
            $totalTokens = $usage['total_tokens'] ?? null;

            Logger::instance()->info("[Quality Analysis] Received response from {$apiName} API", [
                'response_has_choices' => isset($response['choices']),
                'response_choices_count' => count($response['choices'] ?? []),
                'tokens_usage' => [
                    'prompt_tokens' => $promptTokens,
                    'completion_tokens' => $completionTokens,
                    'total_tokens' => $totalTokens
                ],
                'estimated_vs_actual' => $promptTokens ? [
                    'estimated_input' => $estimatedTotalInputTokens,
                    'actual_input' => $promptTokens,
                    'difference' => $promptTokens - $estimatedTotalInputTokens
                ] : null
            ]);

            // Парсим ответ
            Logger::instance()->debug("[Quality Analysis] Parsing AI response");
            $analysisResult = $this->parseResponse($response);
            $duration = round(microtime(true) - $startTime, 2);

            // Добавляем информацию о токенах в результат анализа
            if ($usage) {
                $estimatedCost = $this->estimateCost($promptTokens, $completionTokens);
                $analysisResult['token_usage'] = [
                    'prompt_tokens' => $promptTokens,
                    'completion_tokens' => $completionTokens,
                    'total_tokens' => $totalTokens,
                    'estimated_prompt_tokens' => $estimatedTotalInputTokens,
                    'estimation_accuracy_percent' => $promptTokens ? round((1 - abs($promptTokens - $estimatedTotalInputTokens) / $promptTokens) * 100, 1) : null,
                    'cost_estimate_usd' => $estimatedCost,
                    'model' => $this->model
                ];
            }

            Logger::instance()->info("[Quality Analysis] AI comparison completed successfully", [
                'duration_seconds' => $duration,
                'quality_score' => $analysisResult['quality_score'] ?? null,
                'severity_level' => $analysisResult['severity_level'] ?? null,
                'issues_count' => count($analysisResult['issues'] ?? []),
                'has_summary' => !empty($analysisResult['summary'] ?? ''),
                'token_usage' => $usage ? [
                    'prompt_tokens' => $promptTokens,
                    'completion_tokens' => $completionTokens,
                    'total_tokens' => $totalTokens,
                    'cost_estimate_usd' => $this->estimateCost($promptTokens, $completionTokens)
                ] : null
            ]);

            return $analysisResult;

        } catch (GuzzleException $e) {
            $apiName = $this->isAzure ? 'Azure OpenAI' : 'OpenAI';
            Logger::instance()->error("{$apiName} API request failed", [
                'error' => $e->getMessage(),
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);
            throw new Exception("AI analysis failed: " . $e->getMessage());
        } catch (Exception $e) {
            Logger::instance()->error("Error during AI comparison", [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Encode image to base64 and detect MIME type for correct API upload.
     * Browser screenshots are usually JPEG; files may have .png or .jpg extension.
     *
     * @param string $imagePath Path to screenshot file
     * @return array{base64: string, mime: string}
     */
    private function encodeImage(string $imagePath): array
    {
        if (!file_exists($imagePath)) {
            throw new Exception("Screenshot file not found: {$imagePath}");
        }

        $mime = $this->getImageMimeType($imagePath);
        $imageData = file_get_contents($imagePath);

        return [
            'base64' => base64_encode($imageData),
            'mime' => $mime
        ];
    }

    /**
     * Detect image MIME type from file content (so API receives correct format).
     * Browser screenshot() often returns JPEG even when path has .png.
     *
     * @param string $imagePath Path to image file
     * @return string MIME type, e.g. image/jpeg or image/png
     */
    private function getImageMimeType(string $imagePath): string
    {
        if (!file_exists($imagePath) || !is_readable($imagePath)) {
            return 'image/png';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo !== false) {
            $mime = finfo_file($finfo, $imagePath);
            finfo_close($finfo);
            if ($mime && in_array($mime, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'], true)) {
                return $mime === 'image/jpg' ? 'image/jpeg' : $mime;
            }
        }

        $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        return $ext === 'jpg' || $ext === 'jpeg' ? 'image/jpeg' : 'image/png';
    }

    /**
     * Построить промпт для анализа
     */
    private function buildAnalysisPrompt(array $sourceData, array $migratedData): string
    {
        $sourceElements = $this->extractKeyInfo($sourceData['html'] ?? '');
        $migratedElements = $this->extractKeyInfo($migratedData['html'] ?? '');

        return <<<PROMPT
Ты — эксперт по анализу качества миграции веб-страниц.
Твоя задача — провести строгую, воспроизводимую оценку качества миграции без догадок и предположений.
Используй ТОЛЬКО предоставленные данные. Не делай выводы при недостатке информации.

Проанализируй две страницы:
1. Исходная страница (MB): {$sourceData['url']}
2. Мигрированная страница (Brizy): {$migratedData['url']}

---

## Структурные данные

Исходная страница:
- Заголовков: {$sourceElements['headings_count']}
- Изображений: {$sourceElements['images_count']}
- Ссылок: {$sourceElements['links_count']}
- Форм: {$sourceElements['forms_count']}

Мигрированная страница:
- Заголовков: {$migratedElements['headings_count']}
- Изображений: {$migratedElements['images_count']}
- Ссылок: {$migratedElements['links_count']}
- Форм: {$migratedElements['forms_count']}

---

## Критерии анализа

### 1. Функциональность (приоритет №1)
Проверь:
- Работоспособность форм
- Корректность ссылок и CTA
- Интерактивность элементов

Любая потеря функциональности считается "critical".

---

### 2. Контент
Проверь:
- Отсутствие ключевого текстового контента
- Потерю или искажение смысла
- Отсутствие контентно-значимых изображений

Потеря ключевого контента считается минимум "high".

---

### 3. Элементы интерфейса / CTA
Проверь:
- Наличие кнопок, CTA, навигации
- Сохранение структуры и порядка блоков

Отсутствие основного CTA считается минимум "high".

---

### 4. Типографика (Typography)
Проверь:
- Соответствие шрифтов (font-family)
- Размеры шрифтов для заголовков, текста и CTA
- Начертания (font-weight, italic)
- Межстрочные интервалы (line-height)
- Использование брендовых шрифтов

Правила:
- Замена брендового шрифта → минимум "medium"
- Отличия в шрифтах заголовков или CTA → "medium" или "high"
- Потеря читаемости → минимум "high"

---

### 5. Визуальные различия
Проверь:
- Отступы, выравнивание, размеры блоков
- Цвета и фон

Визуальные различия без влияния на UX считаются "low".

---

## Правила оценки качества (Scoring Rules)

- Начальный балл: 100
- Баллы вычитаются за найденные проблемы
- Минимальный балл: 0

### Максимальные штрафы:
- Функциональность: −40
- Контент: −25
- Элементы UI / CTA: −15
- Типографика: −10
- Визуальные отличия: −10

### Калибровка:
- При наличии "critical" итоговый score ≤ 49
- При severity "high" итоговый score ≤ 69
- При severity "medium" итоговый score ≤ 89
- При severity "low" итоговый score ≤ 95

---

## Уровень критичности

- critical — сломана функциональность или потерян ключевой контент
- high — отсутствуют важные элементы или искажен смысл
- medium — заметные, но не критичные отличия
- low — только визуальные или типографические отличия
- none — различия минимальны или отсутствуют

---

## Формат ответа (СТРОГО JSON, без пояснений)

{
  "quality_score": number,
  "severity_level": "critical" | "high" | "medium" | "low" | "none",
  "summary": "Краткое резюме основных проблем",
  "issues": [
    {
      "type": "missing_content" | "changed_content" | "missing_element" | "visual_difference" | "typography" | "functionality",
      "severity": "critical" | "high" | "medium" | "low",
      "description": "Краткое описание проблемы",
      "details": "Подробности и влияние на пользователя"
    }
  ],
  "missing_elements": [],
  "changed_elements": [],
  "recommendations": []
}


PROMPT;
    }

    /**
     * Извлечь ключевую информацию из HTML
     */
    private function extractKeyInfo(string $html): array
    {
        if (empty($html)) {
            return [
                'headings_count' => 0,
                'images_count' => 0,
                'links_count' => 0,
                'forms_count' => 0
            ];
        }

        $dom = new \DOMDocument();
        // Suppress HTML5 tag warnings (nav, section, header, footer, etc. are valid HTML5 tags)
        $libxmlPreviousState = libxml_use_internal_errors(true);
        @$dom->loadHTML($html);
        libxml_clear_errors();
        libxml_use_internal_errors($libxmlPreviousState);

        return [
            'headings_count' => $dom->getElementsByTagName('h1')->length +
                               $dom->getElementsByTagName('h2')->length +
                               $dom->getElementsByTagName('h3')->length,
            'images_count' => $dom->getElementsByTagName('img')->length,
            'links_count' => $dom->getElementsByTagName('a')->length,
            'forms_count' => $dom->getElementsByTagName('form')->length
        ];
    }

    /**
     * Send request to OpenAI API with screenshots (correct MIME type for each image).
     *
     * @param string $sourceImage Base64-encoded source screenshot
     * @param string $migratedImage Base64-encoded migrated screenshot
     * @param string $prompt Analysis prompt text
     * @param string $sourceMime MIME type of source image (e.g. image/jpeg, image/png)
     * @param string $migratedMime MIME type of migrated image
     */
    private function sendAnalysisRequest(string $sourceImage, string $migratedImage, string $prompt, string $sourceMime = 'image/png', string $migratedMime = 'image/png'): array
    {
        $payload = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $prompt
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'data:' . $sourceMime . ';base64,' . $sourceImage,
                                'detail' => 'high'
                            ]
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'data:' . $migratedMime . ';base64,' . $migratedImage,
                                'detail' => 'high'
                            ]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 2000,
            'temperature' => 0.3
        ];

        $response = $this->httpClient->post($this->apiUrl, [
            'json' => $payload
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Парсить ответ от API
     */
    private function parseResponse(array $response): array
    {
        if (!isset($response['choices'][0]['message']['content'])) {
            $apiName = $this->isAzure ? 'Azure OpenAI' : 'OpenAI';
            throw new Exception("Invalid response from {$apiName} API");
        }

        $content = $response['choices'][0]['message']['content'];

        // Пытаемся извлечь JSON из ответа
        $jsonMatch = [];
        if (preg_match('/\{[\s\S]*\}/', $content, $jsonMatch)) {
            $parsed = json_decode($jsonMatch[0], true);

            if (json_last_error() === JSON_ERROR_NONE && $parsed) {
                return $parsed;
            }
        }

        // If JSON parsing failed, return base structure
        Logger::instance()->warning("Failed to parse JSON from AI response, using fallback");

        return [
            'quality_score' => 50,
            'severity_level' => 'medium',
            'summary' => 'Could not automatically analyze the pages',
            'issues' => [],
            'raw_response' => $content
        ];
    }

    /**
     * TODO: TEMPORARY MOCK - REMOVE THIS METHOD AFTER SETTING UP REAL OpenAI API KEY
     * Временная заглушка для тестирования без реального API ключа
     *
     * Возвращает тестовые данные анализа вместо реального запроса к OpenAI
     *
     * @param array $sourceData Данные исходной страницы
     * @param array $migratedData Данные мигрированной страницы
     * @return array Тестовый результат анализа
     */
    private function getMockAnalysisResult(array $sourceData, array $migratedData): array
    {
        Logger::instance()->info("[Quality Analysis] Generating mock analysis result", [
            'source_url' => $sourceData['url'] ?? null,
            'migrated_url' => $migratedData['url'] ?? null
        ]);

        // Извлекаем информацию для более реалистичного мока
        $sourceElements = $this->extractKeyInfo($sourceData['html'] ?? '');
        $migratedElements = $this->extractKeyInfo($migratedData['html'] ?? '');

        // Простая логика для определения качества на основе количества элементов
        $headingsDiff = abs(($sourceElements['headings_count'] ?? 0) - ($migratedElements['headings_count'] ?? 0));
        $imagesDiff = abs(($sourceElements['images_count'] ?? 0) - ($migratedElements['images_count'] ?? 0));
        $linksDiff = abs(($sourceElements['links_count'] ?? 0) - ($migratedElements['links_count'] ?? 0));
        $formsDiff = abs(($sourceElements['forms_count'] ?? 0) - ($migratedElements['forms_count'] ?? 0));

        $totalDiff = $headingsDiff + $imagesDiff + $linksDiff + $formsDiff;

        // Определяем качество на основе различий
        if ($totalDiff === 0) {
            $qualityScore = 95;
            $severityLevel = 'none';
        } elseif ($totalDiff <= 2) {
            $qualityScore = 85;
            $severityLevel = 'low';
        } elseif ($totalDiff <= 5) {
            $qualityScore = 70;
            $severityLevel = 'medium';
        } elseif ($totalDiff <= 10) {
            $qualityScore = 50;
            $severityLevel = 'high';
        } else {
            $qualityScore = 30;
            $severityLevel = 'critical';
        }

        $mockResult = [
            'quality_score' => $qualityScore,
            'severity_level' => $severityLevel,
            'summary' => '[MOCK] Test analysis completed. Differences detected: ' . $totalDiff . ' elements. ' .
                        'Source: ' . ($sourceElements['headings_count'] ?? 0) . ' headings, ' .
                        ($sourceElements['images_count'] ?? 0) . ' images. ' .
                        'Migrated: ' . ($migratedElements['headings_count'] ?? 0) . ' headings, ' .
                        ($migratedElements['images_count'] ?? 0) . ' images.',
            'issues' => [
                [
                    'type' => 'visual_difference',
                    'severity' => $severityLevel,
                    'description' => '[MOCK] Test issue detected',
                    'details' => 'This is a mock analysis result. Set OPENAI_API_KEY to get real AI analysis.'
                ]
            ],
            'missing_elements' => $totalDiff > 0 ? ['[MOCK] Some elements may be missing'] : [],
            'changed_elements' => $totalDiff > 0 ? ['[MOCK] Some elements may have changed'] : [],
            'recommendations' => [
                '[MOCK] This is a test result. Configure OPENAI_API_KEY for real AI analysis.',
                'Review page manually to verify migration quality.'
            ],
            'mock_data' => true, // Маркер что это тестовые данные
            'source_elements' => $sourceElements,
            'migrated_elements' => $migratedElements,
            'differences' => [
                'headings' => $headingsDiff,
                'images' => $imagesDiff,
                'links' => $linksDiff,
                'forms' => $formsDiff,
                'total' => $totalDiff
            ]
        ];

        Logger::instance()->info("[Quality Analysis] Mock analysis result generated", [
            'quality_score' => $qualityScore,
            'severity_level' => $severityLevel,
            'total_differences' => $totalDiff
        ]);

        return $mockResult;
    }

    /**
     * Оценить стоимость запроса в USD на основе использования токенов
     *
     * @param int|null $promptTokens Количество входных токенов
     * @param int|null $completionTokens Количество выходных токенов
     * @return float|null Оценка стоимости в USD
     */
    private function estimateCost(?int $promptTokens, ?int $completionTokens): ?float
    {
        if ($promptTokens === null || $completionTokens === null) {
            return null;
        }

        // Позволяем явно переопределить тарифы через .env без изменений кода.
        // Значения ожидаются в USD за 1M токенов.
        $envInputPer1M = $_ENV['OPENAI_COST_INPUT_PER_1M'] ?? null;
        $envOutputPer1M = $_ENV['OPENAI_COST_OUTPUT_PER_1M'] ?? null;

        if (is_numeric($envInputPer1M) && is_numeric($envOutputPer1M)) {
            $inputPricePerToken = ((float)$envInputPer1M) / 1000000;
            $outputPricePerToken = ((float)$envOutputPer1M) / 1000000;

            $cost = ($promptTokens * $inputPricePerToken) + ($completionTokens * $outputPricePerToken);
            return round($cost, 6);
        }

        // Примерные цены для разных моделей (на момент написания кода)
        // Цены могут отличаться, обновите при необходимости
        $pricing = [
            'gpt-4o' => ['input' => 0.0025 / 1000, 'output' => 0.01 / 1000], // $2.50/$10 per 1M tokens
            'gpt-4-turbo' => ['input' => 0.01 / 1000, 'output' => 0.03 / 1000], // $10/$30 per 1M tokens
            'gpt-4-vision-preview' => ['input' => 0.01 / 1000, 'output' => 0.03 / 1000],
            'gpt-4.1' => ['input' => 0.01 / 1000, 'output' => 0.03 / 1000], // Azure OpenAI может иметь другие цены
            // Эмпирическая оценка на основе фактических логов проекта (~$0.418 за 1M суммарных токенов).
            // Для устойчивой оценки считаем одинаковую цену на input/output.
            'gpt-5.3-codex' => ['input' => 0.418 / 1000000, 'output' => 0.418 / 1000000],
        ];

        $modelKey = strtolower($this->model);
        $prices = $pricing[$modelKey] ?? $pricing['gpt-4o']; // По умолчанию используем gpt-4o

        $cost = ($promptTokens * $prices['input']) + ($completionTokens * $prices['output']);

        return round($cost, 6); // Округляем до 6 знаков после запятой
    }
}
