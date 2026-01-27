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
 * Тест для проверки рефакторинга MigrationPlatform
 * 
 * Этот тест проверяет, что после рефакторинга:
 * - MigrationPlatform можно создать с моками интерфейсов
 * - Зависимости правильно сохраняются
 * - Класс работает с переданными зависимостями
 * 
 * Задача: task-2.2-refactor-migration-platform-constructor
 * Принцип: Сразу тестировать - после рефакторинга написать тест
 */
class MigrationPlatformRefactoringTest extends TestCase
{
    /**
     * Тест: MigrationPlatform можно создать с моками интерфейсов
     * 
     * Проверяет, что конструктор принимает интерфейсы и класс можно создать
     */
    public function testCanCreateMigrationPlatformWithMocks(): void
    {
        // Arrange: Подготовка моков
        // Инициализируем Logger перед созданием MigrationPlatform
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
     * Тест: Зависимости правильно сохраняются в свойства класса
     * 
     * Проверяет через рефлексию, что переданные зависимости сохраняются в свойства
     */
    public function testDependenciesAreStoredInProperties(): void
    {
        // Arrange: Подготовка моков
        // Инициализируем Logger перед созданием MigrationPlatform
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

        // Assert: Проверка через рефлексию, что зависимости сохранены
        $reflection = new \ReflectionClass($platform);
        
        // Проверяем, что свойство $brizyApi содержит переданный мок
        $brizyApiProperty = $reflection->getProperty('brizyApi');
        $brizyApiProperty->setAccessible(true);
        $this->assertSame($brizyApiMock, $brizyApiProperty->getValue($platform),
            'Свойство $brizyApi должно содержать переданный объект BrizyAPIInterface');

        // Проверяем, что свойство $parser содержит переданный мок
        $parserProperty = $reflection->getProperty('parser');
        $parserProperty->setAccessible(true);
        $this->assertSame($mbCollectorMock, $parserProperty->getValue($platform),
            'Свойство $parser должно содержать переданный объект MBProjectDataCollectorInterface');
    }

    /**
     * Тест: Конструктор принимает все опциональные параметры вместе с интерфейсами
     * 
     * Проверяет, что можно создать MigrationPlatform со всеми параметрами
     */
    public function testConstructorAcceptsAllParametersWithInterfaces(): void
    {
        // Arrange: Подготовка моков и параметров
        // Инициализируем Logger перед созданием MigrationPlatform
        Logger::initialize('test', null, 'php://memory');
        $config = $this->createTestConfig();
        $logger = new NullLogger();
        $brizyApiMock = $this->createMock(BrizyAPIInterface::class);
        $mbCollectorMock = $this->createMock(MBProjectDataCollectorInterface::class);
        $buildPage = 'test-page';
        $workspacesId = 123;
        $mMgrIgnore = false;
        $mgr_manual = true;
        $qualityAnalysis = true;
        $mb_element_name = 'test-element';
        $skip_media_upload = true;
        $skip_cache = true;

        // Act: Создание объекта MigrationPlatform со всеми параметрами
        $platform = new MigrationPlatform(
            $config,
            $logger,
            $brizyApiMock,
            $mbCollectorMock,
            $buildPage,
            $workspacesId,
            $mMgrIgnore,
            $mgr_manual,
            $qualityAnalysis,
            $mb_element_name,
            $skip_media_upload,
            $skip_cache
        );

        // Assert: Проверка, что объект создан
        $this->assertInstanceOf(MigrationPlatform::class, $platform);
    }

    /**
     * Тест: Типы свойств соответствуют интерфейсам
     * 
     * Проверяет, что свойства имеют правильные типы (интерфейсы)
     */
    public function testPropertiesHaveInterfaceTypes(): void
    {
        // Arrange: Подготовка данных
        $reflection = new \ReflectionClass(MigrationPlatform::class);

        // Assert: Проверка типов свойств
        $parserProperty = $reflection->getProperty('parser');
        $parserType = $parserProperty->getType();
        $this->assertNotNull($parserType, 'Свойство $parser должно иметь тип');
        // Тип возвращается с полным namespace
        $this->assertStringEndsWith('MBProjectDataCollectorInterface', $parserType->getName(),
            'Свойство $parser должно иметь тип MBProjectDataCollectorInterface');

        $brizyApiProperty = $reflection->getProperty('brizyApi');
        $brizyApiType = $brizyApiProperty->getType();
        $this->assertNotNull($brizyApiType, 'Свойство $brizyApi должно иметь тип');
        // Тип возвращается с полным namespace
        $this->assertStringEndsWith('BrizyAPIInterface', $brizyApiType->getName(),
            'Свойство $brizyApi должно иметь тип BrizyAPIInterface');
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
