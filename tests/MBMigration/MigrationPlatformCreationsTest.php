<?php

declare(strict_types=1);

namespace MBMigration;

use PHPUnit\Framework\TestCase;

/**
 * Тест для проверки полноты списка мест создания MigrationPlatform
 * 
 * Этот тест проверяет, что:
 * - Все места создания MigrationPlatform найдены
 * - Документ с результатами создан
 * - Список мест соответствует реальному коду
 * 
 * Задача: task-2.3-find-migration-platform-creations
 * Принцип: Сразу тестировать - после поиска написать тест
 */
class MigrationPlatformCreationsTest extends TestCase
{
    /**
     * Тест: Проверка, что документ с результатами поиска существует
     * 
     * Проверяет, что документ MIGRATION_PLATFORM_CREATIONS.md создан
     */
    public function testDocumentExists(): void
    {
        // Путь относительно корня проекта (в Docker контейнере это /project)
        $projectRoot = getcwd();
        if (strpos($projectRoot, '/project') === false) {
            // Если не в контейнере, используем относительный путь
            $projectRoot = dirname(__DIR__, 3);
        }
        $documentPath = $projectRoot . '/testing-improvement/stage-1/substage-1.2/tasks/MIGRATION_PLATFORM_CREATIONS.md';
        
        $this->assertFileExists($documentPath, 
            'Документ MIGRATION_PLATFORM_CREATIONS.md должен быть создан по пути: ' . $documentPath);
    }

    /**
     * Тест: Проверка, что все места создания найдены через grep
     * 
     * Проверяет, что количество мест создания в документе соответствует
     * реальному количеству в коде
     */
    public function testAllCreationsFound(): void
    {
        // Находим все места создания через grep
        $projectRoot = getcwd();
        if (strpos($projectRoot, '/project') === false) {
            $projectRoot = dirname(__DIR__, 3);
        }
        $grepCommand = "grep -rn 'new MigrationPlatform' " . escapeshellarg($projectRoot . '/lib') . " " . 
                       escapeshellarg($projectRoot . '/dashboard') . " " . 
                       escapeshellarg($projectRoot . '/public') . " 2>/dev/null";
        
        $grepOutput = shell_exec($grepCommand);
        $foundLines = $grepOutput ? explode("\n", trim($grepOutput)) : [];
        $foundLines = array_filter($foundLines, function($line) {
            return !empty(trim($line));
        });
        
        $actualCount = count($foundLines);
        
        // Читаем документ и проверяем количество мест
        $projectRoot = getcwd();
        if (strpos($projectRoot, '/project') === false) {
            $projectRoot = dirname(__DIR__, 3);
        }
        $documentPath = $projectRoot . '/testing-improvement/stage-1/substage-1.2/tasks/MIGRATION_PLATFORM_CREATIONS.md';
        $documentContent = file_get_contents($documentPath);
        
        // Ищем в документе количество мест (из раздела "Найдено мест создания")
        // Формат: "**Найдено мест создания**: 1"
        preg_match('/\*\*Найдено мест создания\*\*[:\s]+(\d+)/', $documentContent, $matches);
        if (!isset($matches[1])) {
            // Альтернативный формат без markdown
            preg_match('/Найдено мест создания[:\s]+(\d+)/', $documentContent, $matches);
        }
        $documentedCount = isset($matches[1]) ? (int)$matches[1] : 0;
        
        $this->assertEquals($actualCount, $documentedCount,
            "Количество мест создания в документе ({$documentedCount}) должно соответствовать реальному количеству ({$actualCount})");
    }

    /**
     * Тест: Проверка, что каждое найденное место задокументировано
     * 
     * Проверяет, что для каждого места создания в коде есть запись в документе
     */
    public function testEachCreationDocumented(): void
    {
        // Находим все места создания через grep
        $projectRoot = getcwd();
        if (strpos($projectRoot, '/project') === false) {
            $projectRoot = dirname(__DIR__, 3);
        }
        $grepCommand = "grep -rn 'new MigrationPlatform' " . escapeshellarg($projectRoot . '/lib') . " " . 
                       escapeshellarg($projectRoot . '/dashboard') . " " . 
                       escapeshellarg($projectRoot . '/public') . " 2>/dev/null";
        
        $grepOutput = shell_exec($grepCommand);
        $foundLines = $grepOutput ? explode("\n", trim($grepOutput)) : [];
        $foundLines = array_filter($foundLines, function($line) {
            return !empty(trim($line));
        });
        
        // Читаем документ
        $projectRoot = getcwd();
        if (strpos($projectRoot, '/project') === false) {
            $projectRoot = dirname(__DIR__, 3);
        }
        $documentPath = $projectRoot . '/testing-improvement/stage-1/substage-1.2/tasks/MIGRATION_PLATFORM_CREATIONS.md';
        $documentContent = file_get_contents($documentPath);
        
        // Проверяем каждое найденное место
        foreach ($foundLines as $line) {
            // Извлекаем путь к файлу и номер строки из вывода grep
            // Формат: path/to/file:line:code
            if (preg_match('/^([^:]+):(\d+):/', $line, $matches)) {
                $filePath = $matches[1];
                $lineNumber = $matches[2];
                
                // Проверяем, что этот файл и строка упомянуты в документе
                $projectRoot = getcwd();
                if (strpos($projectRoot, '/project') === false) {
                    $projectRoot = dirname(__DIR__, 3);
                }
                $relativePath = str_replace($projectRoot . '/', '', $filePath);
                
                $this->assertStringContainsString($relativePath, $documentContent,
                    "Файл {$relativePath} должен быть упомянут в документе");
                $this->assertStringContainsString(":{$lineNumber}", $documentContent,
                    "Строка {$lineNumber} файла {$relativePath} должна быть упомянута в документе");
            }
        }
    }

    /**
     * Тест: Проверка структуры документа
     * 
     * Проверяет, что документ содержит все необходимые разделы
     */
    public function testDocumentStructure(): void
    {
        $projectRoot = getcwd();
        if (strpos($projectRoot, '/project') === false) {
            $projectRoot = dirname(__DIR__, 3);
        }
        $documentPath = $projectRoot . '/testing-improvement/stage-1/substage-1.2/tasks/MIGRATION_PLATFORM_CREATIONS.md';
        $documentContent = file_get_contents($documentPath);
        
        // Проверяем наличие ключевых разделов
        $this->assertStringContainsString('Места создания MigrationPlatform', $documentContent,
            'Документ должен содержать заголовок "Места создания MigrationPlatform"');
        
        $this->assertStringContainsString('Результаты поиска', $documentContent,
            'Документ должен содержать раздел "Результаты поиска"');
        
        $this->assertStringContainsString('Места создания MigrationPlatform', $documentContent,
            'Документ должен содержать раздел с местами создания');
        
        $this->assertStringContainsString('План обновления', $documentContent,
            'Документ должен содержать план обновления для каждого места');
    }
}
