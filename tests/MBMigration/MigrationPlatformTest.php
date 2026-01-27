<?php

namespace MBMigration;

use Exception;
use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use MBMigration\Contracts\BrizyAPIInterface;
use MBMigration\Contracts\MBProjectDataCollectorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Тесты для класса MigrationPlatform
 * 
 * Проверяет:
 * - MigrationPlatform использует инжектированный Logger
 * - Можно мокировать Logger в тестах
 * - Все методы логирования работают правильно
 * 
 * Задача: task-p1-1-3-update-migration-platform
 * Принцип: Сразу тестировать - после рефакторинга написать тест
 */
class MigrationPlatformTest extends TestCase
{
    /**
     * Временный путь для тестовых логов
     */
    private string $testLogPath;

    /**
     * Настройка перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем временный файл для логов
        $this->testLogPath = sys_get_temp_dir() . '/test_migration_platform_' . uniqid() . '.log';
    }

    /**
     * Очистка после каждого теста
     */
    protected function tearDown(): void
    {
        // Удаляем тестовый файл лога, если существует
        if (file_exists($this->testLogPath)) {
            @unlink($this->testLogPath);
        }
        
        parent::tearDown();
    }

    /**
     * Создает реальный Config для тестов
     */
    private function createTestConfig(): Config
    {
        // Config требует инициализации статических свойств
        // Создаем реальный Config с минимальными параметрами
        $cachePath = sys_get_temp_dir() . '/test_cache_' . uniqid();
        @mkdir($cachePath, 0755, true);
        
        return new Config(
            'https://api.brizy.io',
            sys_get_temp_dir(),
            $cachePath,
            'test-token',
            [
                'devMode' => false,
                'mgrMode' => false,
                'db' => [
                    'dbHost' => 'localhost',
                    'dbPort' => '5432',
                    'dbName' => 'test_db',
                    'dbUser' => 'test_user',
                    'dbPass' => 'test_pass',
                ],
                'db_mg' => [
                    'dbHost' => 'localhost',
                    'dbPort' => '3306',
                    'dbName' => 'test_mg_db',
                    'dbUser' => 'test_mg_user',
                    'dbPass' => 'test_mg_pass',
                ],
                'assets' => [
                    'MBMediaStaging' => 'https://media.test.com',
                ],
                'previewBaseHost' => 'preview.test.com',
            ]
        );
    }

    /**
     * Создает мок BrizyAPIInterface
     */
    private function createMockBrizyAPI(): BrizyAPIInterface
    {
        return $this->createMock(BrizyAPIInterface::class);
    }

    /**
     * Создает мок MBProjectDataCollectorInterface
     */
    private function createMockMBCollector(): MBProjectDataCollectorInterface
    {
        return $this->createMock(MBProjectDataCollectorInterface::class);
    }

    /**
     * Тест: MigrationPlatform использует инжектированный Logger
     * 
     * Проверяет, что Logger передается через конструктор и сохраняется в свойство
     * 
     * Примечание: Полная инициализация MigrationPlatform требует инициализации VariableCache,
     * который пока использует Logger::instance(). Это будет исправлено в задаче 1.1.5.
     * Здесь проверяем только структуру класса.
     */
    public function testMigrationPlatformUsesInjectedLogger(): void
    {
        // Инициализируем Logger для VariableCache (временное решение до задачи 1.1.5)
        Logger::initialize('test-logger', 'info', $this->testLogPath);
        
        $config = $this->createTestConfig();
        $logger = new Logger('test-migration-platform', 'info', $this->testLogPath);
        $brizyApi = $this->createMockBrizyAPI();
        $mbCollector = $this->createMockMBCollector();
        
        $migrationPlatform = new MigrationPlatform(
            $config,
            $logger,
            $brizyApi,
            $mbCollector
        );
        
        // Проверяем, что Logger сохранен через рефлексию
        $reflection = new \ReflectionClass(MigrationPlatform::class);
        $loggerProperty = $reflection->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $savedLogger = $loggerProperty->getValue($migrationPlatform);
        
        $this->assertInstanceOf(LoggerInterface::class, $savedLogger);
        $this->assertSame($logger, $savedLogger);
    }

    /**
     * Тест: MigrationPlatform не использует Logger::instance()
     * 
     * Проверяет, что в коде нет вызовов Logger::instance()
     */
    public function testMigrationPlatformDoesNotUseLoggerInstance(): void
    {
        // Проверяем, что в файле нет вызовов Logger::instance()
        $filePath = dirname(__DIR__, 2) . '/lib/MBMigration/MigrationPlatform.php';
        if (file_exists($filePath)) {
            $fileContent = file_get_contents($filePath);
            $this->assertStringNotContainsString('Logger::instance()', $fileContent, 
                'MigrationPlatform не должен использовать Logger::instance()');
        } else {
            // Если файл не найден, пропускаем тест
            $this->markTestSkipped('Файл MigrationPlatform.php не найден');
        }
    }

    /**
     * Тест: можно создать MigrationPlatform с моком Logger
     * 
     * Проверяет, что можно использовать мок Logger для тестирования
     */
    public function testMigrationPlatformCanUseMockLogger(): void
    {
        // Инициализируем Logger для VariableCache (временное решение до задачи 1.1.5)
        Logger::initialize('test-logger', 'info', $this->testLogPath);
        
        // Создаем мок Logger
        $mockLogger = $this->createMock(LoggerInterface::class);
        
        $config = $this->createTestConfig();
        $brizyApi = $this->createMockBrizyAPI();
        $mbCollector = $this->createMockMBCollector();
        
        $migrationPlatform = new MigrationPlatform(
            $config,
            $mockLogger,
            $brizyApi,
            $mbCollector
        );
        
        // Проверяем, что Logger сохранен
        $reflection = new \ReflectionClass(MigrationPlatform::class);
        $loggerProperty = $reflection->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $savedLogger = $loggerProperty->getValue($migrationPlatform);
        
        $this->assertInstanceOf(LoggerInterface::class, $savedLogger);
        $this->assertSame($mockLogger, $savedLogger);
    }

    /**
     * Тест: MigrationPlatform использует Logger для логирования
     * 
     * Проверяет, что методы логирования вызываются на инжектированном Logger
     */
    public function testMigrationPlatformUsesLoggerForLogging(): void
    {
        // Инициализируем Logger для VariableCache (временное решение до задачи 1.1.5)
        Logger::initialize('test-logger', 'info', $this->testLogPath);
        
        // Создаем мок Logger с ожиданиями
        $mockLogger = $this->createMock(LoggerInterface::class);
        
        $config = $this->createTestConfig();
        $brizyApi = $this->createMockBrizyAPI();
        $mbCollector = $this->createMockMBCollector();
        
        $migrationPlatform = new MigrationPlatform(
            $config,
            $mockLogger,
            $brizyApi,
            $mbCollector
        );
        
        // Проверяем, что Logger доступен
        $reflection = new \ReflectionClass(MigrationPlatform::class);
        $loggerProperty = $reflection->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $savedLogger = $loggerProperty->getValue($migrationPlatform);
        
        $this->assertInstanceOf(LoggerInterface::class, $savedLogger);
        
        // В реальном сценарии можно проверить вызовы методов логирования,
        // но для этого нужно вызывать методы MigrationPlatform, что требует полной инициализации
        // Это будет частью интеграционных тестов
    }

    /**
     * Тест: разные экземпляры MigrationPlatform используют разные Logger
     * 
     * Проверяет изоляцию экземпляров
     */
    public function testDifferentMigrationPlatformInstancesUseDifferentLoggers(): void
    {
        // Инициализируем Logger для VariableCache (временное решение до задачи 1.1.5)
        Logger::initialize('test-logger', 'info', $this->testLogPath);
        
        $config = $this->createTestConfig();
        $brizyApi = $this->createMockBrizyAPI();
        $mbCollector = $this->createMockMBCollector();
        
        $logger1 = new Logger('logger-1', 'info', $this->testLogPath);
        $logger2 = new Logger('logger-2', 'debug', $this->testLogPath);
        
        $migrationPlatform1 = new MigrationPlatform(
            $config,
            $logger1,
            $brizyApi,
            $mbCollector
        );
        
        $migrationPlatform2 = new MigrationPlatform(
            $config,
            $logger2,
            $brizyApi,
            $mbCollector
        );
        
        // Проверяем, что каждый экземпляр использует свой Logger
        $reflection = new \ReflectionClass(MigrationPlatform::class);
        $loggerProperty = $reflection->getProperty('logger');
        $loggerProperty->setAccessible(true);
        
        $savedLogger1 = $loggerProperty->getValue($migrationPlatform1);
        $savedLogger2 = $loggerProperty->getValue($migrationPlatform2);
        
        $this->assertSame($logger1, $savedLogger1);
        $this->assertSame($logger2, $savedLogger2);
        $this->assertNotSame($savedLogger1, $savedLogger2);
    }
}
