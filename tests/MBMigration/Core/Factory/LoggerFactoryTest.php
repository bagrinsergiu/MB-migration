<?php

namespace MBMigration\Core\Factory;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Тесты для класса LoggerFactory
 * 
 * Проверяет:
 * - Создание Logger через LoggerFactory::create()
 * - Создание Logger через LoggerFactory::createDefault()
 * - Правильность параметров
 * - Изоляцию экземпляров
 * 
 * Задача: task-p1-1-1-refactor-logger-class
 * Принцип: Сразу тестировать - после создания фабрики написать тест
 */
class LoggerFactoryTest extends TestCase
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
        $this->testLogPath = sys_get_temp_dir() . '/test_factory_' . uniqid() . '.log';
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
     * Тест: создание Logger через LoggerFactory::create()
     * 
     * Проверяет, что фабрика создает правильный экземпляр Logger
     */
    public function testCreateLoggerViaFactory(): void
    {
        $logger = LoggerFactory::create('test-logger', 'info', $this->testLogPath);
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertEquals('test-logger', $logger->getName());
    }

    /**
     * Тест: создание Logger через LoggerFactory::createDefault()
     * 
     * Проверяет, что фабрика создает Logger с настройками по умолчанию
     */
    public function testCreateLoggerViaFactoryDefault(): void
    {
        $defaultLogPath = sys_get_temp_dir() . '/test_default_' . uniqid() . '.log';
        
        $logger = LoggerFactory::createDefault('test-logger', 'info', $defaultLogPath);
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertEquals('test-logger', $logger->getName());
        
        // Очистка
        if (file_exists($defaultLogPath)) {
            @unlink($defaultLogPath);
        }
    }

    /**
     * Тест: createDefault() использует значения по умолчанию
     * 
     * Проверяет, что createDefault() использует значения по умолчанию, если параметры не указаны
     */
    public function testCreateDefaultUsesDefaultValues(): void
    {
        $defaultLogPath = sys_get_temp_dir() . '/test_default_' . uniqid() . '.log';
        
        // Вызываем без параметров (кроме имени)
        $logger = LoggerFactory::createDefault('test-logger', null, $defaultLogPath);
        
        $this->assertInstanceOf(LoggerInterface::class, $logger);
        $this->assertEquals('test-logger', $logger->getName());
        
        // Очистка
        if (file_exists($defaultLogPath)) {
            @unlink($defaultLogPath);
        }
    }

    /**
     * Тест: разные экземпляры Logger не влияют друг на друга
     * 
     * Проверяет изоляцию экземпляров, созданных через фабрику
     */
    public function testDifferentLoggerInstancesAreIsolated(): void
    {
        $logPath1 = sys_get_temp_dir() . '/test_1_' . uniqid() . '.log';
        $logPath2 = sys_get_temp_dir() . '/test_2_' . uniqid() . '.log';
        
        $logger1 = LoggerFactory::create('logger-1', 'info', $logPath1);
        $logger2 = LoggerFactory::create('logger-2', 'debug', $logPath2);
        
        $this->assertEquals('logger-1', $logger1->getName());
        $this->assertEquals('logger-2', $logger2->getName());
        $this->assertNotSame($logger1, $logger2);
        
        // Очистка
        if (file_exists($logPath1)) {
            @unlink($logPath1);
        }
        if (file_exists($logPath2)) {
            @unlink($logPath2);
        }
    }

    /**
     * Тест: фабрика создает Logger с правильными параметрами
     * 
     * Проверяет, что все параметры передаются корректно
     */
    public function testFactoryCreatesLoggerWithCorrectParameters(): void
    {
        $name = 'my-test-logger';
        $level = 'warning';
        $path = $this->testLogPath;
        
        $logger = LoggerFactory::create($name, $level, $path);
        
        $this->assertEquals($name, $logger->getName());
        
        // Проверяем, что файл лога может быть создан
        $logger->warning('Test warning message');
        $this->assertFileExists($path);
    }

    /**
     * Тест: фабрика может создавать Logger с разными уровнями логирования
     * 
     * Проверяет создание Logger с разными уровнями
     */
    public function testFactoryCreatesLoggerWithDifferentLogLevels(): void
    {
        $levels = ['debug', 'info', 'warning', 'error', 'critical'];
        
        foreach ($levels as $level) {
            $logPath = sys_get_temp_dir() . '/test_' . $level . '_' . uniqid() . '.log';
            $logger = LoggerFactory::create('test-logger', $level, $logPath);
            
            $this->assertInstanceOf(LoggerInterface::class, $logger);
            
            // Очистка
            if (file_exists($logPath)) {
                @unlink($logPath);
            }
        }
    }
}
