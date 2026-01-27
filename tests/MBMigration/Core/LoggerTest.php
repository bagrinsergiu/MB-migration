<?php

namespace MBMigration\Core;

use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Тесты для класса Logger
 * 
 * Проверяет:
 * - Создание Logger через конструктор
 * - Создание Logger через LoggerFactory
 * - Работу deprecated методов (обратная совместимость)
 * - Изоляцию экземпляров Logger
 * 
 * Задача: task-p1-1-1-refactor-logger-class
 * Принцип: Сразу тестировать - после рефакторинга написать тест
 */
class LoggerTest extends TestCase
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
        $this->testLogPath = sys_get_temp_dir() . '/test_logger_' . uniqid() . '.log';
        
        // Очищаем статическое состояние перед каждым тестом
        $this->resetLoggerInstance();
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
        
        // Очищаем статическое состояние
        $this->resetLoggerInstance();
        
        parent::tearDown();
    }

    /**
     * Сброс статического экземпляра Logger через рефлексию
     */
    private function resetLoggerInstance(): void
    {
        $reflection = new \ReflectionClass(Logger::class);
        $property = $reflection->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);
    }

    /**
     * Тест: создание Logger через конструктор
     * 
     * Проверяет, что Logger можно создать через конструктор
     */
    public function testCreateLoggerViaConstructor(): void
    {
        $logger = new Logger('test-logger', 'info', $this->testLogPath);
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertEquals('test-logger', $logger->getName());
    }

    /**
     * Тест: создание Logger через конструктор без handler
     * 
     * Проверяет, что Logger можно создать без path и logLevel
     */
    public function testCreateLoggerViaConstructorWithoutHandler(): void
    {
        $logger = new Logger('test-logger');
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertEquals('test-logger', $logger->getName());
    }

    /**
     * Тест: создание Logger через LoggerFactory
     * 
     * Проверяет, что Logger можно создать через фабрику
     */
    public function testCreateLoggerViaFactory(): void
    {
        $logger = \MBMigration\Core\Factory\LoggerFactory::create(
            'test-logger',
            'info',
            $this->testLogPath
        );
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertEquals('test-logger', $logger->getName());
    }

    /**
     * Тест: создание Logger через LoggerFactory::createDefault
     * 
     * Проверяет, что Logger можно создать через фабрику с настройками по умолчанию
     */
    public function testCreateLoggerViaFactoryDefault(): void
    {
        $defaultLogPath = sys_get_temp_dir() . '/test_default_' . uniqid() . '.log';
        
        $logger = \MBMigration\Core\Factory\LoggerFactory::createDefault(
            'test-logger',
            'info',
            $defaultLogPath
        );
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertEquals('test-logger', $logger->getName());
        
        // Очистка
        if (file_exists($defaultLogPath)) {
            @unlink($defaultLogPath);
        }
    }

    /**
     * Тест: deprecated метод initialize() все еще работает
     * 
     * Проверяет обратную совместимость - старый метод должен работать
     */
    public function testDeprecatedInitializeMethodStillWorks(): void
    {
        $logger = Logger::initialize('test-logger', 'info', $this->testLogPath);
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertEquals('test-logger', $logger->getName());
    }

    /**
     * Тест: deprecated метод instance() работает после initialize
     * 
     * Проверяет обратную совместимость - старый метод должен работать
     */
    public function testDeprecatedInstanceMethodWorksAfterInitialize(): void
    {
        Logger::initialize('test-logger', 'info', $this->testLogPath);
        
        $instance = Logger::instance();
        
        $this->assertInstanceOf(LoggerInterface::class, $instance);
        $this->assertEquals('test-logger', $instance->getName());
    }

    /**
     * Тест: deprecated метод instance() выбрасывает исключение без initialize
     * 
     * Проверяет, что instance() выбрасывает исключение, если Logger не был инициализирован
     */
    public function testDeprecatedInstanceMethodThrowsExceptionWithoutInitialize(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Please initialize logger first');
        
        Logger::instance();
    }

    /**
     * Тест: deprecated метод isInitialized() работает
     * 
     * Проверяет обратную совместимость
     */
    public function testDeprecatedIsInitializedMethodWorks(): void
    {
        // До инициализации
        $this->assertFalse(Logger::isInitialized());
        
        // После инициализации
        Logger::initialize('test-logger', 'info', $this->testLogPath);
        $this->assertTrue(Logger::isInitialized());
    }

    /**
     * Тест: разные экземпляры Logger не влияют друг на друга
     * 
     * Проверяет изоляцию экземпляров
     */
    public function testDifferentLoggerInstancesAreIsolated(): void
    {
        $logger1 = new Logger('logger-1', 'info', $this->testLogPath);
        $logger2 = new Logger('logger-2', 'debug', $this->testLogPath);
        
        $this->assertEquals('logger-1', $logger1->getName());
        $this->assertEquals('logger-2', $logger2->getName());
        $this->assertNotSame($logger1, $logger2);
    }

    /**
     * Тест: Logger может логировать сообщения
     * 
     * Проверяет базовую функциональность логирования
     */
    public function testLoggerCanLogMessages(): void
    {
        $logger = new Logger('test-logger', 'info', $this->testLogPath);
        
        $logger->info('Test message');
        
        $this->assertFileExists($this->testLogPath);
        $logContent = file_get_contents($this->testLogPath);
        $this->assertStringContainsString('Test message', $logContent);
    }

    /**
     * Тест: Logger с разными уровнями логирования
     * 
     * Проверяет создание Logger с разными уровнями
     */
    public function testLoggerWithDifferentLogLevels(): void
    {
        $levels = ['debug', 'info', 'warning', 'error', 'critical'];
        
        foreach ($levels as $level) {
            $logPath = sys_get_temp_dir() . '/test_' . $level . '_' . uniqid() . '.log';
            $logger = new Logger('test-logger', $level, $logPath);
            
            $this->assertInstanceOf(LoggerInterface::class, $logger);
            
            // Очистка
            if (file_exists($logPath)) {
                @unlink($logPath);
            }
        }
    }
}
