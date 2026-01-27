<?php

declare(strict_types=1);

namespace MBMigration;

use MBMigration\Contracts\BrizyAPIInterface;
use MBMigration\Contracts\MBProjectDataCollectorInterface;
use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Тест для проверки использования моков в MigrationPlatform
 * 
 * Этот тест проверяет, что после рефакторинга:
 * - Можно создать MigrationPlatform с моками интерфейсов
 * - MigrationPlatform использует переданные моки
 * - Можно настроить поведение моков для тестирования
 * 
 * Задача: task-2.5-test-migration-platform
 * Принцип: Сразу тестировать - написать тест с моками
 */
class MigrationPlatformWithMocksTest extends TestCase
{
    /**
     * Тест: MigrationPlatform можно создать с моками интерфейсов
     * 
     * Проверяет базовую возможность создания объекта с моками
     */
    public function testCanCreateWithMocks(): void
    {
        // Arrange: Подготовка моков
        Logger::initialize('test', null, 'php://memory');
        $config = $this->createTestConfig();
        $logger = new NullLogger();
        $brizyApiMock = $this->createMock(BrizyAPIInterface::class);
        $mbCollectorMock = $this->createMock(MBProjectDataCollectorInterface::class);

        // Act: Создание объекта MigrationPlatform с моками
        $platform = new MigrationPlatform(
            $config,
            $logger,
            $brizyApiMock,
            $mbCollectorMock
        );

        // Assert: Проверка, что объект создан
        $this->assertInstanceOf(MigrationPlatform::class, $platform);
    }

    /**
     * Тест: MigrationPlatform использует переданные моки
     * 
     * Проверяет, что MigrationPlatform действительно использует переданные моки,
     * а не создает свои экземпляры
     */
    public function testUsesInjectedMocks(): void
    {
        // Arrange: Подготовка моков
        Logger::initialize('test', null, 'php://memory');
        $config = $this->createTestConfig();
        $logger = new NullLogger();
        $brizyApiMock = $this->createMock(BrizyAPIInterface::class);
        $mbCollectorMock = $this->createMock(MBProjectDataCollectorInterface::class);

        // Act: Создание объекта MigrationPlatform
        $platform = new MigrationPlatform(
            $config,
            $logger,
            $brizyApiMock,
            $mbCollectorMock
        );

        // Assert: Проверка через рефлексию, что используются переданные моки
        $reflection = new \ReflectionClass($platform);
        
        $brizyApiProperty = $reflection->getProperty('brizyApi');
        $brizyApiProperty->setAccessible(true);
        $this->assertSame($brizyApiMock, $brizyApiProperty->getValue($platform),
            'MigrationPlatform должен использовать переданный мок BrizyAPIInterface');

        $parserProperty = $reflection->getProperty('parser');
        $parserProperty->setAccessible(true);
        $this->assertSame($mbCollectorMock, $parserProperty->getValue($platform),
            'MigrationPlatform должен использовать переданный мок MBProjectDataCollectorInterface');
    }

    /**
     * Тест: Можно настроить поведение моков для тестирования
     * 
     * Проверяет, что можно настроить методы моков для тестирования различных сценариев
     */
    public function testCanConfigureMockBehavior(): void
    {
        // Arrange: Подготовка моков с настроенным поведением
        Logger::initialize('test', null, 'php://memory');
        $config = $this->createTestConfig();
        $logger = new NullLogger();
        
        // Настраиваем мок BrizyAPI
        $brizyApiMock = $this->createMock(BrizyAPIInterface::class);
        $brizyApiMock->method('getDomain')
            ->willReturn('test-domain.com');
        
        // Настраиваем мок MBProjectDataCollector
        $mbCollectorMock = $this->createMock(MBProjectDataCollectorInterface::class);
        $mbCollectorMock->method('getDesignSite')
            ->willReturn('test-design');

        // Act: Создание объекта MigrationPlatform
        $platform = new MigrationPlatform(
            $config,
            $logger,
            $brizyApiMock,
            $mbCollectorMock
        );

        // Assert: Проверка, что объект создан с настроенными моками
        $this->assertInstanceOf(MigrationPlatform::class, $platform);
        
        // Проверяем, что моки действительно настроены
        $reflection = new \ReflectionClass($platform);
        $brizyApiProperty = $reflection->getProperty('brizyApi');
        $brizyApiProperty->setAccessible(true);
        $injectedBrizyApi = $brizyApiProperty->getValue($platform);
        
        // Проверяем, что метод мока работает
        $this->assertEquals('test-domain.com', $injectedBrizyApi->getDomain(123));
    }

    /**
     * Тест: Можно использовать разные реализации интерфейсов
     * 
     * Проверяет, что можно передать разные реализации интерфейсов
     */
    public function testCanUseDifferentImplementations(): void
    {
        // Arrange: Подготовка разных моков
        Logger::initialize('test', null, 'php://memory');
        $config = $this->createTestConfig();
        $logger = new NullLogger();
        
        // Создаем первый набор моков
        $brizyApiMock1 = $this->createMock(BrizyAPIInterface::class);
        $mbCollectorMock1 = $this->createMock(MBProjectDataCollectorInterface::class);
        
        $platform1 = new MigrationPlatform(
            $config,
            $logger,
            $brizyApiMock1,
            $mbCollectorMock1
        );
        
        // Создаем второй набор моков
        $brizyApiMock2 = $this->createMock(BrizyAPIInterface::class);
        $mbCollectorMock2 = $this->createMock(MBProjectDataCollectorInterface::class);
        
        $platform2 = new MigrationPlatform(
            $config,
            $logger,
            $brizyApiMock2,
            $mbCollectorMock2
        );

        // Assert: Проверка, что оба объекта созданы и используют разные моки
        $this->assertInstanceOf(MigrationPlatform::class, $platform1);
        $this->assertInstanceOf(MigrationPlatform::class, $platform2);
        $this->assertNotSame($platform1, $platform2);
        
        // Проверяем, что используются разные моки
        $reflection1 = new \ReflectionClass($platform1);
        $reflection2 = new \ReflectionClass($platform2);
        
        $brizyApiProperty1 = $reflection1->getProperty('brizyApi');
        $brizyApiProperty1->setAccessible(true);
        $brizyApiProperty2 = $reflection2->getProperty('brizyApi');
        $brizyApiProperty2->setAccessible(true);
        
        $this->assertNotSame(
            $brizyApiProperty1->getValue($platform1),
            $brizyApiProperty2->getValue($platform2),
            'Разные экземпляры MigrationPlatform должны использовать разные моки'
        );
    }

    /**
     * Создает тестовый объект Config с минимальными параметрами
     * 
     * @return Config
     */
    private function createTestConfig(): Config
    {
        return new Config(
            'https://test-cloud-host.com',  // cloud_host
            __DIR__ . '/../../',            // path
            __DIR__ . '/../../var/cache',   // cachePath
            'test-token',                   // token
            [                               // settings
                'db' => [
                    'dbHost' => 'localhost',
                    'dbPort' => 3306,
                    'dbName' => 'test_db',
                    'dbUser' => 'test_user',
                    'dbPass' => 'test_pass'
                ],
                'db_mg' => [
                    'dbHost' => 'localhost',
                    'dbPort' => 3306,
                    'dbName' => 'test_db_mg',
                    'dbUser' => 'test_user',
                    'dbPass' => 'test_pass'
                ],
                'assets' => ['test' => 'value'],  // assets array (не может быть пустым)
                'previewBaseHost' => 'test.example.com'  // preview base host
            ]
        );
    }
}
