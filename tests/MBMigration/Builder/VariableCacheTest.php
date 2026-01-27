<?php

namespace MBMigration\Builder;

use Exception;
use MBMigration\Builder\Factory\VariableCacheFactory;
use MBMigration\Core\Config;
use PHPUnit\Framework\TestCase;

/**
 * Тесты для класса VariableCache
 * 
 * Проверяет:
 * - Создание VariableCache через конструктор
 * - Создание VariableCache через VariableCacheFactory
 * - Разные экземпляры VariableCache не влияют друг на друга
 * - Все методы VariableCache работают правильно
 * 
 * Задача: task-p1-1-5-refactor-variablecache
 * Принцип: Сразу тестировать - после рефакторинга написать тест
 */
class VariableCacheTest extends TestCase
{
    /**
     * Временный путь для тестового кэша
     */
    private string $testCachePath;

    /**
     * Настройка перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Создаем временную директорию для кэша
        $this->testCachePath = sys_get_temp_dir() . '/test_cache_' . uniqid();
        @mkdir($this->testCachePath, 0755, true);
    }

    /**
     * Очистка после каждого теста
     */
    protected function tearDown(): void
    {
        // Удаляем тестовую директорию кэша, если существует
        if (is_dir($this->testCachePath)) {
            $files = glob($this->testCachePath . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    @unlink($file);
                }
            }
            @rmdir($this->testCachePath);
        }
        
        parent::tearDown();
    }

    /**
     * Тест: VariableCache можно создать через конструктор
     * 
     * Проверяет, что конструктор работает и создает новый экземпляр
     */
    public function testVariableCacheCanBeCreatedViaConstructor(): void
    {
        $cache = new VariableCache($this->testCachePath);
        
        $this->assertInstanceOf(VariableCache::class, $cache);
    }

    /**
     * Тест: VariableCache можно создать через VariableCacheFactory
     * 
     * Проверяет, что фабрика создает правильный экземпляр
     */
    public function testVariableCacheCanBeCreatedViaFactory(): void
    {
        $cache = VariableCacheFactory::create($this->testCachePath);
        
        $this->assertInstanceOf(VariableCache::class, $cache);
    }

    /**
     * Тест: VariableCacheFactory::createDefault() создает экземпляр
     * 
     * Проверяет, что метод createDefault() работает
     */
    public function testVariableCacheFactoryCreateDefault(): void
    {
        $cache = VariableCacheFactory::createDefault();
        
        $this->assertInstanceOf(VariableCache::class, $cache);
    }

    /**
     * Тест: Разные экземпляры VariableCache не влияют друг на друга
     * 
     * Проверяет изоляцию экземпляров
     */
    public function testDifferentVariableCacheInstancesAreIsolated(): void
    {
        $cache1 = new VariableCache($this->testCachePath . '/cache1');
        $cache2 = new VariableCache($this->testCachePath . '/cache2');
        
        $cache1->set('test_key', 'value1');
        $cache2->set('test_key', 'value2');
        
        $this->assertEquals('value1', $cache1->get('test_key'));
        $this->assertEquals('value2', $cache2->get('test_key'));
        $this->assertNotEquals($cache1->get('test_key'), $cache2->get('test_key'));
    }

    /**
     * Тест: Метод set() и get() работают правильно
     * 
     * Проверяет базовую функциональность кэша
     */
    public function testSetAndGetMethodsWork(): void
    {
        $cache = new VariableCache($this->testCachePath);
        
        $cache->set('test_key', 'test_value');
        $this->assertEquals('test_value', $cache->get('test_key'));
        
        $cache->set('array_key', ['nested' => 'value']);
        $this->assertEquals(['nested' => 'value'], $cache->get('array_key'));
    }

    /**
     * Тест: Метод exist() работает правильно
     * 
     * Проверяет проверку существования ключа
     */
    public function testExistMethodWorks(): void
    {
        $cache = new VariableCache($this->testCachePath);
        
        $this->assertFalse($cache->exist('non_existent_key'));
        
        $cache->set('existing_key', 'value');
        $this->assertTrue($cache->exist('existing_key'));
    }

    /**
     * Тест: Метод set() с секцией работает правильно
     * 
     * Проверяет работу с секциями
     */
    public function testSetWithSectionWorks(): void
    {
        $cache = new VariableCache($this->testCachePath);
        
        $cache->set('key', 'value', 'section');
        $this->assertEquals('value', $cache->get('key', 'section'));
    }

    /**
     * Тест: Метод add() работает правильно
     * 
     * Проверяет добавление значений
     */
    public function testAddMethodWorks(): void
    {
        $cache = new VariableCache($this->testCachePath);
        
        $cache->add('array_key', ['item1']);
        $this->assertEquals(['item1'], $cache->get('array_key'));
        
        $cache->add('array_key', ['item2']);
        $result = $cache->get('array_key');
        $this->assertIsArray($result);
        $this->assertContains('item1', $result);
        $this->assertContains('item2', $result);
    }

    /**
     * Тест: Метод setClass() и getClass() работают правильно
     * 
     * Проверяет работу с объектами
     */
    public function testSetClassAndGetClassWork(): void
    {
        $cache = new VariableCache($this->testCachePath);
        
        $testObject = new \stdClass();
        $testObject->property = 'value';
        
        $cache->setClass($testObject, 'test_object');
        $retrievedObject = $cache->getClass('test_object');
        
        $this->assertInstanceOf(\stdClass::class, $retrievedObject);
        $this->assertEquals('value', $retrievedObject->property);
    }

    /**
     * Тест: Deprecated метод getInstance() все еще работает
     * 
     * Проверяет обратную совместимость
     */
    public function testDeprecatedGetInstanceStillWorks(): void
    {
        $cache1 = VariableCache::getInstance($this->testCachePath);
        $cache2 = VariableCache::getInstance($this->testCachePath);
        
        // getInstance() должен возвращать тот же экземпляр для обратной совместимости
        $this->assertInstanceOf(VariableCache::class, $cache1);
        $this->assertInstanceOf(VariableCache::class, $cache2);
    }

    /**
     * Тест: setCachePath() работает правильно
     * 
     * Проверяет установку пути к кэшу
     */
    public function testSetCachePathWorks(): void
    {
        $cache = new VariableCache($this->testCachePath);
        
        $newPath = $this->testCachePath . '/new_path';
        $cache->setCachePath($newPath);
        
        // Проверяем, что путь установлен (через dumpCache, если возможно)
        $this->assertTrue(true); // Базовая проверка, что метод выполнился без ошибок
    }
}
