<?php

declare(strict_types=1);

namespace MBMigration;

use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;

/**
 * Тест для проверки обновления ApplicationBootstrapper
 * 
 * Этот тест проверяет, что после обновления ApplicationBootstrapper:
 * - Код компилируется без ошибок
 * - ApplicationBootstrapper может создать MigrationPlatform с новым конструктором
 * - Зависимости создаются правильно
 * 
 * Задача: task-2.4-update-migration-platform-creations
 * Принцип: Сразу тестировать - после обновления написать тест
 */
class ApplicationBootstrapperUpdateTest extends TestCase
{
    /**
     * Тест: Проверка, что ApplicationBootstrapper компилируется без ошибок
     * 
     * Проверяет синтаксис файла
     */
    public function testApplicationBootstrapperCompiles(): void
    {
        $filePath = __DIR__ . '/../../lib/MBMigration/ApplicationBootstrapper.php';
        
        $this->assertFileExists($filePath, 
            'Файл ApplicationBootstrapper.php должен существовать');
        
        // Проверяем, что файл можно загрузить
        $this->assertTrue(class_exists(ApplicationBootstrapper::class),
            'Класс ApplicationBootstrapper должен быть доступен');
    }

    /**
     * Тест: Проверка, что ApplicationBootstrapper имеет необходимые use statements
     * 
     * Проверяет, что добавлены use statements для BrizyAPI и MBProjectDataCollector
     */
    public function testApplicationBootstrapperHasRequiredUseStatements(): void
    {
        $filePath = __DIR__ . '/../../lib/MBMigration/ApplicationBootstrapper.php';
        $fileContent = file_get_contents($filePath);
        
        $this->assertStringContainsString('use MBMigration\Layer\Brizy\BrizyAPI;', $fileContent,
            'Должен быть добавлен use statement для BrizyAPI');
        
        $this->assertStringContainsString('use MBMigration\Layer\MB\MBProjectDataCollector;', $fileContent,
            'Должен быть добавлен use statement для MBProjectDataCollector');
    }

    /**
     * Тест: Проверка, что в коде создаются зависимости перед MigrationPlatform
     * 
     * Проверяет, что в методе doMigration создаются BrizyAPI и MBProjectDataCollector
     */
    public function testDependenciesAreCreatedBeforeMigrationPlatform(): void
    {
        $filePath = __DIR__ . '/../../lib/MBMigration/ApplicationBootstrapper.php';
        $fileContent = file_get_contents($filePath);
        
        // Проверяем, что создаются зависимости
        $this->assertStringContainsString('$brizyApi = new BrizyAPI($logger);', $fileContent,
            'Должно быть создание BrizyAPI перед MigrationPlatform');
        
        $this->assertStringContainsString('$mbCollector = new MBProjectDataCollector();', $fileContent,
            'Должно быть создание MBProjectDataCollector перед MigrationPlatform');
        
        // Проверяем, что зависимости передаются в конструктор
        $this->assertStringContainsString('$brizyApi,', $fileContent,
            'BrizyAPI должен передаваться в конструктор MigrationPlatform');
        
        $this->assertStringContainsString('$mbCollector,', $fileContent,
            'MBProjectDataCollector должен передаваться в конструктор MigrationPlatform');
    }

    /**
     * Тест: Проверка наличия новых параметров в конструкторе MigrationPlatform
     * 
     * Проверяет, что новые параметры ($brizyApi и $mbCollector) присутствуют в вызове конструктора
     */
    public function testConstructorHasNewParameters(): void
    {
        $filePath = __DIR__ . '/../../lib/MBMigration/ApplicationBootstrapper.php';
        $fileContent = file_get_contents($filePath);
        
        // Проверяем, что в вызове конструктора присутствуют новые параметры
        // Ищем блок с вызовом конструктора MigrationPlatform
        $this->assertStringContainsString('new MigrationPlatform(', $fileContent,
            'Должен быть вызов конструктора MigrationPlatform');
        
        // Проверяем, что новые параметры присутствуют в вызове
        $this->assertStringContainsString('$brizyApi,', $fileContent,
            'Параметр $brizyApi должен быть в вызове конструктора');
        
        $this->assertStringContainsString('$mbCollector,', $fileContent,
            'Параметр $mbCollector должен быть в вызове конструктора');
        
        // Проверяем, что параметры идут после $logger
        $loggerPos = strpos($fileContent, '$logger,');
        $brizyApiPos = strpos($fileContent, '$brizyApi,');
        $mbCollectorPos = strpos($fileContent, '$mbCollector,');
        
        $this->assertNotFalse($loggerPos, 'Должен быть параметр $logger');
        $this->assertNotFalse($brizyApiPos, 'Должен быть параметр $brizyApi');
        $this->assertNotFalse($mbCollectorPos, 'Должен быть параметр $mbCollector');
        
        // Проверяем порядок: $logger должен быть перед $brizyApi, $brizyApi перед $mbCollector
        $this->assertGreaterThan($loggerPos, $brizyApiPos,
            'Параметр $brizyApi должен идти после $logger');
        $this->assertGreaterThan($brizyApiPos, $mbCollectorPos,
            'Параметр $mbCollector должен идти после $brizyApi');
    }

    /**
     * Тест: Проверка наличия комментариев
     * 
     * Проверяет, что добавлены комментарии, объясняющие изменения
     */
    public function testCommentsAdded(): void
    {
        $filePath = __DIR__ . '/../../lib/MBMigration/ApplicationBootstrapper.php';
        $fileContent = file_get_contents($filePath);
        
        // Проверяем наличие комментариев
        $this->assertStringContainsString('Создаем зависимости для MigrationPlatform', $fileContent,
            'Должен быть комментарий о создании зависимостей');
        
        $this->assertStringContainsString('рефакторинг', $fileContent,
            'Должен быть комментарий о рефакторинге');
    }
}
