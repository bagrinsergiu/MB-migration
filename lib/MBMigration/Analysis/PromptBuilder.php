<?php

namespace MBMigration\Analysis;

use Exception;
use MBMigration\Core\Logger;

/**
 * PromptBuilder
 * 
 * Класс для загрузки и построения тематических промптов из Markdown файлов
 */
class PromptBuilder
{
    /**
     * @var string
     */
    private $promptsDir;

    public function __construct()
    {
        // Путь к директории с промптами относительно этого файла
        $this->promptsDir = dirname(__FILE__) . '/Prompts';
    }

    /**
     * Построить промпт для анализа страницы
     * 
     * @param array $sourceData Данные исходной страницы
     * @param array $migratedData Данные мигрированной страницы
     * @param string $themeName Название темы (designName)
     * @return string Готовый промпт для отправки в AI
     * @throws Exception
     */
    public function buildPrompt(array $sourceData, array $migratedData, string $themeName = 'default'): string
    {
        // Загружаем промпт из MD файла
        $promptTemplate = $this->loadPrompt($themeName);
        
        // Извлекаем ключевую информацию из HTML
        $sourceElements = $this->extractKeyInfo($sourceData['html'] ?? '');
        $migratedElements = $this->extractKeyInfo($migratedData['html'] ?? '');
        
        // Подставляем данные в промпт
        $replacements = [
            '{source_url}' => $sourceData['url'] ?? '',
            '{migrated_url}' => $migratedData['url'] ?? '',
            '{source_headings_count}' => $sourceElements['headings_count'],
            '{source_images_count}' => $sourceElements['images_count'],
            '{source_links_count}' => $sourceElements['links_count'],
            '{source_forms_count}' => $sourceElements['forms_count'],
            '{migrated_headings_count}' => $migratedElements['headings_count'],
            '{migrated_images_count}' => $migratedElements['images_count'],
            '{migrated_links_count}' => $migratedElements['links_count'],
            '{migrated_forms_count}' => $migratedElements['forms_count'],
        ];
        
        $prompt = str_replace(array_keys($replacements), array_values($replacements), $promptTemplate);
        
        Logger::instance()->info("[PromptBuilder] Prompt built successfully", [
            'theme' => $themeName,
            'prompt_length' => strlen($prompt),
            'source_url' => $sourceData['url'] ?? null,
            'migrated_url' => $migratedData['url'] ?? null
        ]);
        
        return $prompt;
    }

    /**
     * Загрузить промпт из MD файла
     * 
     * @param string $themeName Название темы
     * @return string Содержимое промпта
     * @throws Exception
     */
    public function loadPrompt(string $themeName): string
    {
        // Нормализуем название темы (убираем пробелы, специальные символы)
        $normalizedTheme = $this->normalizeThemeName($themeName);
        
        // Путь к файлу промпта
        $promptPath = $this->getPromptPath($normalizedTheme);
        
        // Если файл темы не найден, используем default
        if (!file_exists($promptPath)) {
            Logger::instance()->info("[PromptBuilder] Theme prompt not found, using default", [
                'requested_theme' => $themeName,
                'normalized_theme' => $normalizedTheme,
                'prompt_path' => $promptPath
            ]);
            
            // Пробуем загрузить default
            $defaultPath = $this->getPromptPath('default');
            if (!file_exists($defaultPath)) {
                throw new Exception("Default prompt file not found: {$defaultPath}");
            }
            $promptPath = $defaultPath;
        }
        
        $prompt = file_get_contents($promptPath);
        if ($prompt === false) {
            throw new Exception("Failed to read prompt file: {$promptPath}");
        }
        
        // Удаляем заголовок Markdown (если есть) - первая строка с #
        $lines = explode("\n", $prompt);
        if (!empty($lines) && strpos(trim($lines[0]), '#') === 0) {
            // Пропускаем заголовок и описание (если есть)
            $startIndex = 0;
            foreach ($lines as $index => $line) {
                $trimmed = trim($line);
                // Пропускаем заголовки и пустые строки в начале
                if (empty($trimmed) || strpos($trimmed, '#') === 0) {
                    continue;
                }
                // Начинаем с первого непустого не-заголовка
                $startIndex = $index;
                break;
            }
            $prompt = implode("\n", array_slice($lines, $startIndex));
        }
        
        Logger::instance()->info("[PromptBuilder] Prompt loaded", [
            'theme' => $themeName,
            'normalized_theme' => $normalizedTheme,
            'prompt_path' => $promptPath,
            'prompt_length' => strlen($prompt)
        ]);
        
        return trim($prompt);
    }

    /**
     * Получить путь к файлу промпта
     * 
     * @param string $themeName Название темы
     * @return string Полный путь к файлу
     */
    public function getPromptPath(string $themeName): string
    {
        $normalizedTheme = $this->normalizeThemeName($themeName);
        return $this->promptsDir . '/' . $normalizedTheme . '.md';
    }

    /**
     * Нормализовать название темы для использования в имени файла
     * 
     * @param string $themeName Исходное название темы
     * @return string Нормализованное название
     */
    private function normalizeThemeName(string $themeName): string
    {
        // Убираем пробелы и специальные символы, оставляем только буквы, цифры и подчеркивания
        $normalized = preg_replace('/[^a-zA-Z0-9_]/', '', $themeName);
        
        // Если после нормализации пусто, используем 'default'
        if (empty($normalized)) {
            return 'default';
        }
        
        return $normalized;
    }

    /**
     * Извлечь ключевую информацию из HTML
     * 
     * @param string $html HTML содержимое страницы
     * @return array Массив с количеством элементов
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

        // Используем простой подсчет через регулярные выражения
        // Более точный подсчет можно сделать через DOMDocument, но это медленнее
        $headingsCount = preg_match_all('/<h[1-6][^>]*>/i', $html);
        $imagesCount = preg_match_all('/<img[^>]*>/i', $html);
        $linksCount = preg_match_all('/<a[^>]*href[^>]*>/i', $html);
        $formsCount = preg_match_all('/<form[^>]*>/i', $html);

        return [
            'headings_count' => $headingsCount ?: 0,
            'images_count' => $imagesCount ?: 0,
            'links_count' => $linksCount ?: 0,
            'forms_count' => $formsCount ?: 0
        ];
    }
}
