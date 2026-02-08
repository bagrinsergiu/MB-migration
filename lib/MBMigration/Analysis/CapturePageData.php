<?php

namespace MBMigration\Analysis;

use Exception;
use MBMigration\Browser\BrowserPHP;
use MBMigration\Core\Logger;

/**
 * CapturePageData
 * 
 * Класс для захвата данных страниц (скриншоты и HTML)
 * для последующего анализа качества миграции
 */
class CapturePageData
{
    /**
     * @var string
     */
    private $baseScreenshotsPath;
    /**
     * @var BrowserPHP
     */
    private $browser;
    /**
     * @var int|null
     */
    private $projectId;

    public function __construct(?string $screenshotsPath = null, ?int $projectId = null)
    {
        $this->projectId = $projectId;
        
        // По умолчанию используем var/tmp от корня проекта
        if ($screenshotsPath === null) {
            // Определяем корень проекта (3 уровня выше от lib/MBMigration/Analysis/)
            $projectRoot = dirname(__DIR__, 3);
            $basePath = $projectRoot . '/var/tmp/';
        } else {
            $basePath = $screenshotsPath;
        }
        
        // Убеждаемся что путь заканчивается на /
        if (substr($basePath, -1) !== '/') {
            $basePath .= '/';
        }
        
        // Если указан projectId, создаем поддиректорию для проекта
        if ($this->projectId !== null) {
            $this->baseScreenshotsPath = $basePath . 'project_' . $this->projectId . '/';
        } else {
            $this->baseScreenshotsPath = $basePath;
        }
        
        // Создаем директорию для скриншотов если её нет
        if (!is_dir($this->baseScreenshotsPath)) {
            mkdir($this->baseScreenshotsPath, 0755, true);
        }
        
        Logger::instance()->debug("[Quality Analysis] Screenshots directory initialized", [
            'base_screenshots_path' => $this->baseScreenshotsPath,
            'project_id' => $this->projectId,
            'directory_exists' => is_dir($this->baseScreenshotsPath),
            'is_writable' => is_writable($this->baseScreenshotsPath)
        ]);
    }

    /**
     * Получить путь к директории скриншотов для текущего проекта
     * 
     * @return string
     */
    public function getScreenshotsPath(): string
    {
        return $this->baseScreenshotsPath;
    }

    /**
     * Захватить данные исходной страницы (MB)
     * 
     * @param string $url URL исходной страницы
     * @param string $pageSlug Slug страницы для именования файлов
     * @return array Массив с путями к файлам и HTML
     * @throws Exception
     */
    public function captureSourcePage(string $url, string $pageSlug): array
    {
            Logger::instance()->info("[Quality Analysis] Opening browser for source page", [
            'url' => $url,
            'slug' => $pageSlug,
            'screenshots_path' => $this->baseScreenshotsPath,
            'project_id' => $this->projectId
        ]);
        
        try {
            $startTime = microtime(true);
            $this->browser = BrowserPHP::instance(dirname(__DIR__) . '/Builder/Layout');
            Logger::instance()->debug("[Quality Analysis] Browser instance created", ['url' => $url]);
            
            $browserPage = $this->browser->openPage($url, 'Solstice');
            Logger::instance()->debug("[Quality Analysis] Page opened, waiting for load", ['url' => $url]);
            
            // Ждем загрузки страницы и появления контента
            sleep(2);
            
            $html = '';
            $htmlLength = 0;
            $textLength = 0;
            $maxAttempts = 5;
            
            for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
                Logger::instance()->debug("[Quality Analysis] Capturing HTML content (source, attempt {$attempt})", ['url' => $url]);
                $html = $this->captureHTML($browserPage);
                $htmlLength = strlen($html);
                $textLength = strlen(trim(strip_tags($html)));
                
                Logger::instance()->info("[Quality Analysis] HTML content captured (source)", [
                    'attempt' => $attempt,
                    'html_length' => $htmlLength,
                    'text_length' => $textLength,
                    'url' => $url
                ]);
                
                // Если видимый текст достаточно большой, считаем что страница отрендерилась
                if ($textLength > 50) {
                    break;
                }
                
                // Даем странице ещё немного времени
                if ($attempt < $maxAttempts) {
                    sleep(2);
                }
            }
            
            // Получаем скриншот после того как HTML стал доступен
            $screenshotPath = $this->baseScreenshotsPath . 'source_' . md5($pageSlug) . '.jpg';
            Logger::instance()->debug("[Quality Analysis] Capturing screenshot", [
                'screenshot_path' => $screenshotPath,
                'url' => $url
            ]);
            $this->captureScreenshot($browserPage, $screenshotPath);
            $screenshotSize = file_exists($screenshotPath) ? filesize($screenshotPath) : 0;
            Logger::instance()->info("[Quality Analysis] Screenshot captured", [
                'screenshot_path' => $screenshotPath,
                'screenshot_size_bytes' => $screenshotSize,
                'url' => $url
            ]);
            
            $this->browser->closePage();
            $this->browser->closeBrowser();
            $duration = round(microtime(true) - $startTime, 2);
            Logger::instance()->info("[Quality Analysis] Browser closed, source page capture completed", [
                'url' => $url,
                'duration_seconds' => $duration,
                'screenshot_size_bytes' => $screenshotSize,
                'html_length' => $htmlLength
            ]);
            
            return [
                'screenshot_path' => $screenshotPath,
                'html' => $html,
                'url' => $url
            ];
        } catch (Exception $e) {
            Logger::instance()->error("Error capturing source page", [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            
            if (isset($this->browser)) {
                try {
                    $this->browser->closePage();
                    $this->browser->closeBrowser();
                } catch (Exception $closeEx) {
                    // Игнорируем ошибки закрытия
                }
            }
            
            throw $e;
        }
    }

    /**
     * Захватить данные мигрированной страницы (Brizy)
     * 
     * @param string $url URL мигрированной страницы
     * @param string $pageSlug Slug страницы для именования файлов
     * @return array Массив с путями к файлам и HTML
     * @throws Exception
     */
    public function captureMigratedPage(string $url, string $pageSlug): array
    {
        Logger::instance()->info("[Quality Analysis] Opening browser for migrated page", [
            'url' => $url,
            'slug' => $pageSlug,
            'screenshots_path' => $this->baseScreenshotsPath,
            'project_id' => $this->projectId
        ]);
        
        try {
            $startTime = microtime(true);
            $this->browser = BrowserPHP::instance(dirname(__DIR__) . '/Builder/Layout');
            Logger::instance()->debug("[Quality Analysis] Browser instance created", ['url' => $url]);
            
            $browserPage = $this->browser->openPage($url, 'Solstice');
            Logger::instance()->debug("[Quality Analysis] Page opened, waiting for load", ['url' => $url]);
            
            // Ждем загрузки страницы и генерации HTML Brizy
            sleep(2);
            
            $html = '';
            $htmlLength = 0;
            $textLength = 0;
            $maxAttempts = 7;
            
            for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
                Logger::instance()->debug("[Quality Analysis] Capturing HTML content (migrated, attempt {$attempt})", ['url' => $url]);
                $html = $this->captureHTML($browserPage);
                $htmlLength = strlen($html);
                $textLength = strlen(trim(strip_tags($html)));
                
                Logger::instance()->info("[Quality Analysis] HTML content captured (migrated)", [
                    'attempt' => $attempt,
                    'html_length' => $htmlLength,
                    'text_length' => $textLength,
                    'url' => $url
                ]);
                
                // Для мигрированной страницы даем больше времени: ждем пока появится видимый контент
                if ($textLength > 50) {
                    break;
                }
                
                if ($attempt < $maxAttempts) {
                    sleep(2);
                }
            }
            
            // Получаем скриншот после того как HTML стал доступен (или после попыток)
            $screenshotPath = $this->baseScreenshotsPath . 'migrated_' . md5($pageSlug) . '.jpg';
            Logger::instance()->debug("[Quality Analysis] Capturing screenshot", [
                'screenshot_path' => $screenshotPath,
                'url' => $url
            ]);
            $this->captureScreenshot($browserPage, $screenshotPath);
            $screenshotSize = file_exists($screenshotPath) ? filesize($screenshotPath) : 0;
            Logger::instance()->info("[Quality Analysis] Screenshot captured", [
                'screenshot_path' => $screenshotPath,
                'screenshot_size_bytes' => $screenshotSize,
                'url' => $url
            ]);
            
            $this->browser->closePage();
            $this->browser->closeBrowser();
            $duration = round(microtime(true) - $startTime, 2);
            Logger::instance()->info("[Quality Analysis] Browser closed, migrated page capture completed", [
                'url' => $url,
                'duration_seconds' => $duration,
                'screenshot_size_bytes' => $screenshotSize,
                'html_length' => $htmlLength
            ]);
            
            return [
                'screenshot_path' => $screenshotPath,
                'html' => $html,
                'url' => $url
            ];
        } catch (Exception $e) {
            Logger::instance()->error("Error capturing migrated page", [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            
            if (isset($this->browser)) {
                try {
                    $this->browser->closePage();
                    $this->browser->closeBrowser();
                } catch (Exception $closeEx) {
                    // Игнорируем ошибки закрытия
                }
            }
            
            throw $e;
        }
    }

    /**
     * Захватить скриншот страницы
     * 
     * @param mixed $browserPage Объект страницы браузера (BrowserPagePHP)
     * @param string $outputPath Путь для сохранения скриншота
     * @return void
     * @throws Exception
     */
    private function captureScreenshot($browserPage, string $outputPath): void
    {
        try {
            // Используем метод screenshot из BrowserPagePHP
            $browserPage->screenshot($outputPath);
            
            Logger::instance()->debug("Screenshot saved", ['path' => $outputPath]);
        } catch (Exception $e) {
            Logger::instance()->error("Error capturing screenshot", [
                'path' => $outputPath,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Захватить HTML содержимое страницы
     * 
     * @param mixed $browserPage Объект страницы браузера (BrowserPagePHP)
     * @return string HTML содержимое
     * @throws Exception
     */
    private function captureHTML($browserPage): string
    {
        try {
            // Используем callFunctionWithRetry через рефлексию для прямого вызова JavaScript
            // Это позволяет получить строку напрямую, а не массив
            $reflection = new \ReflectionClass($browserPage);
            $method = $reflection->getMethod('callFunctionWithRetry');
            $method->setAccessible(true);
            
            // Вызываем JavaScript функцию напрямую для получения HTML
            // callFunctionWithRetry принимает: function string, args array, timeout, retryTimeout
            $html = $method->invoke(
                $browserPage,
                "function() { return document.documentElement.outerHTML; }",
                [],
                10000,
                5000
            );
            
            Logger::instance()->debug("[Quality Analysis] HTML retrieved via callFunctionWithRetry", [
                'html_type' => gettype($html),
                'html_length' => is_string($html) ? strlen($html) : (is_array($html) ? count($html) : 0)
            ]);
            
            // Результат может быть строкой (прямой возврат) или массивом
            if (is_string($html)) {
                return $html;
            } elseif (is_array($html)) {
                // Если вернулся массив, пытаемся извлечь значение
                // Ищем строку в массиве (обычно это первый элемент или элемент с ключом 'value')
                foreach ($html as $key => $value) {
                    if (is_string($value) && strlen($value) > 100) {
                        Logger::instance()->debug("[Quality Analysis] Found HTML string in array", ['key' => $key]);
                        return $value;
                    }
                }
                // Если не нашли строку, берем первое значение и конвертируем в строку
                if (count($html) > 0) {
                    $firstValue = reset($html);
                    if (is_string($firstValue)) {
                        return $firstValue;
                    }
                    return (string)$firstValue;
                }
                return '';
            }
            
            // Если что-то другое, конвертируем в строку
            return (string)$html;
        } catch (\ReflectionException $e) {
            Logger::instance()->warning("[Quality Analysis] Reflection failed, trying evaluateScript method", [
                'error' => $e->getMessage()
            ]);
            
            // Альтернативный способ через evaluateScript (возвращает массив)
            try {
                $result = $browserPage->evaluateScript(
                    "function() { return document.documentElement.outerHTML; }",
                    []
                );
                
                Logger::instance()->debug("[Quality Analysis] HTML retrieved via evaluateScript", [
                    'result_type' => gettype($result),
                    'is_array' => is_array($result),
                    'array_keys' => is_array($result) ? array_keys($result) : []
                ]);
                
                // evaluateScript возвращает массив
                if (is_array($result)) {
                    // Пытаемся найти строку в массиве (HTML обычно длинный)
                    foreach ($result as $key => $value) {
                        if (is_string($value) && strlen($value) > 100) {
                            Logger::instance()->debug("[Quality Analysis] Found HTML string in evaluateScript result", ['key' => $key]);
                            return $value;
                        }
                    }
                    // Если не нашли строку, берем первое значение
                    if (count($result) > 0) {
                        $firstValue = reset($result);
                        if (is_string($firstValue)) {
                            return $firstValue;
                        }
                        return (string)$firstValue;
                    }
                } elseif (is_string($result)) {
                    // Если evaluateScript вернул строку напрямую (не должно быть, но на всякий случай)
                    return $result;
                }
                
                return '';
            } catch (Exception $e2) {
                Logger::instance()->error("[Quality Analysis] Error capturing HTML via evaluateScript", [
                    'error' => $e2->getMessage(),
                    'error_code' => $e2->getCode()
                ]);
                return '';
            }
        } catch (Exception $e) {
            Logger::instance()->error("[Quality Analysis] Error capturing HTML", [
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine()
            ]);
            // Возвращаем пустую строку вместо исключения, чтобы не прерывать процесс
            return '';
        }
    }

    /**
     * Извлечь ключевые элементы из HTML
     * 
     * @param string $html HTML содержимое
     * @return array Массив с извлеченными элементами
     */
    public function extractKeyElements(string $html): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        
        $xpath = new \DOMXPath($dom);
        
        return [
            'headings' => $this->extractHeadings($xpath),
            'images' => $this->extractImages($xpath),
            'links' => $this->extractLinks($xpath),
            'forms' => $this->extractForms($xpath),
            'text_content' => $this->extractTextContent($xpath)
        ];
    }

    /**
     * Извлечь заголовки
     */
    private function extractHeadings(\DOMXPath $xpath): array
    {
        $headings = [];
        for ($i = 1; $i <= 6; $i++) {
            $nodes = $xpath->query("//h{$i}");
            foreach ($nodes as $node) {
                $headings[] = [
                    'level' => $i,
                    'text' => trim($node->textContent)
                ];
            }
        }
        return $headings;
    }

    /**
     * Извлечь изображения
     */
    private function extractImages(\DOMXPath $xpath): array
    {
        $images = [];
        $nodes = $xpath->query("//img");
        foreach ($nodes as $node) {
            $images[] = [
                'src' => $node->getAttribute('src'),
                'alt' => $node->getAttribute('alt')
            ];
        }
        return $images;
    }

    /**
     * Извлечь ссылки
     */
    private function extractLinks(\DOMXPath $xpath): array
    {
        $links = [];
        $nodes = $xpath->query("//a[@href]");
        foreach ($nodes as $node) {
            $links[] = [
                'href' => $node->getAttribute('href'),
                'text' => trim($node->textContent)
            ];
        }
        return $links;
    }

    /**
     * Извлечь формы
     */
    private function extractForms(\DOMXPath $xpath): array
    {
        $forms = [];
        $nodes = $xpath->query("//form");
        foreach ($nodes as $node) {
            $forms[] = [
                'action' => $node->getAttribute('action'),
                'method' => $node->getAttribute('method')
            ];
        }
        return $forms;
    }

    /**
     * Извлечь текстовый контент
     */
    private function extractTextContent(\DOMXPath $xpath): string
    {
        // Удаляем script и style элементы
        $xpath->registerNamespace("php", "http://php.net/xpath");
        $nodes = $xpath->query("//text()[normalize-space()]");
        
        $texts = [];
        foreach ($nodes as $node) {
            $text = trim($node->textContent);
            if (strlen($text) > 10) { // Игнорируем очень короткие тексты
                $texts[] = $text;
            }
        }
        
        return implode(' ', $texts);
    }
}
