<?php

declare(strict_types=1);

namespace MBMigration;

use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use MBMigration\MigrationPlatform;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Тест для проверки понимания структуры класса MigrationPlatform
 * 
 * Этот тест проверяет, что мы правильно поняли структуру класса MigrationPlatform:
 * - Конструктор принимает определенные параметры
 * - Класс можно создать с минимальными параметрами
 * - Свойства класса доступны (через рефлексию)
 * 
 * @package MBMigration
 */
class MigrationPlatformStudyTest extends TestCase
{
    /**
     * Тест: Проверка, что конструктор MigrationPlatform принимает Config и LoggerInterface
     * 
     * Этот тест проверяет наше понимание сигнатуры конструктора.
     * Если тест проходит, значит мы правильно поняли структуру конструктора.
     */
    public function testConstructorAcceptsConfigAndLogger(): void
    {
        // Arrange: Подготовка данных
        // Config требует 5 параметров: cloud_host, path, cachePath, token, settings
        // Инициализируем Logger перед созданием MigrationPlatform
        Logger::initialize('test', null, 'php://memory');
        $config = $this->createTestConfig();
        $logger = new NullLogger();
        $brizyApiMock = $this->createMock(\MBMigration\Contracts\BrizyAPIInterface::class);
        $mbCollectorMock = $this->createMock(\MBMigration\Contracts\MBProjectDataCollectorInterface::class);

        // Act: Создание объекта MigrationPlatform
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
     * Тест: Проверка, что конструктор принимает все опциональные параметры
     * 
     * Этот тест проверяет, что мы правильно поняли все параметры конструктора.
     */
    public function testConstructorAcceptsAllOptionalParameters(): void
    {
        // Arrange: Подготовка данных
        Logger::initialize('test', null, 'php://memory');
        $config = $this->createTestConfig();
        $logger = new NullLogger();
        $brizyApiMock = $this->createMock(\MBMigration\Contracts\BrizyAPIInterface::class);
        $mbCollectorMock = $this->createMock(\MBMigration\Contracts\MBProjectDataCollectorInterface::class);
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
     * Тест: Проверка, что зависимости инжектируются через конструктор
     * 
     * Этот тест проверяет, что после рефакторинга зависимости инжектируются
     * через конструктор, а не создаются напрямую в методах.
     * Проверяем через рефлексию, что свойства для зависимостей инициализированы переданными объектами.
     */
    public function testDependenciesAreInjectedThroughConstructor(): void
    {
        // Arrange: Подготовка данных
        Logger::initialize('test', null, 'php://memory');
        $config = $this->createTestConfig();
        $logger = new NullLogger();
        $brizyApiMock = $this->createMock(\MBMigration\Contracts\BrizyAPIInterface::class);
        $mbCollectorMock = $this->createMock(\MBMigration\Contracts\MBProjectDataCollectorInterface::class);

        // Act: Создание объекта MigrationPlatform
        $platform = new MigrationPlatform($config, $logger, $brizyApiMock, $mbCollectorMock);

        // Assert: Проверка через рефлексию, что свойства для зависимостей инициализированы переданными объектами
        $reflection = new \ReflectionClass($platform);
        
        // Проверяем, что свойство $parser инициализировано переданным объектом
        $parserProperty = $reflection->getProperty('parser');
        $parserProperty->setAccessible(true);
        $this->assertTrue($parserProperty->isInitialized($platform), 
            'Свойство $parser должно быть инициализировано в конструкторе');
        $this->assertSame($mbCollectorMock, $parserProperty->getValue($platform),
            'Свойство $parser должно содержать переданный объект MBProjectDataCollectorInterface');

        // Проверяем, что свойство $brizyApi инициализировано переданным объектом
        $brizyApiProperty = $reflection->getProperty('brizyApi');
        $brizyApiProperty->setAccessible(true);
        $this->assertTrue($brizyApiProperty->isInitialized($platform), 
            'Свойство $brizyApi должно быть инициализировано в конструкторе');
        $this->assertSame($brizyApiMock, $brizyApiProperty->getValue($platform),
            'Свойство $brizyApi должно содержать переданный объект BrizyAPIInterface');
    }

    /**
     * Тест: Проверка наличия свойств для зависимостей
     * 
     * Этот тест проверяет, что в классе есть свойства для хранения зависимостей:
     * - $parser (MBProjectDataCollector)
     * - $brizyApi (BrizyAPI)
     * - $pageController (PageController)
     */
    public function testClassHasDependencyProperties(): void
    {
        // Arrange: Подготовка данных
        $reflection = new \ReflectionClass(MigrationPlatform::class);

        // Assert: Проверка наличия свойств
        $this->assertTrue($reflection->hasProperty('parser'), 
            'Класс должен иметь свойство $parser для MBProjectDataCollector');
        
        $this->assertTrue($reflection->hasProperty('brizyApi'), 
            'Класс должен иметь свойство $brizyApi для BrizyAPI');
        
        $this->assertTrue($reflection->hasProperty('pageController'), 
            'Класс должен иметь свойство $pageController для PageController');
    }

    /**
     * Тест: Проверка типов свойств зависимостей
     * 
     * Этот тест проверяет, что свойства имеют правильные типы.
     */
    public function testDependencyPropertiesHaveCorrectTypes(): void
    {
        // Arrange: Подготовка данных
        $reflection = new \ReflectionClass(MigrationPlatform::class);

        // Assert: Проверка типов свойств
        $parserProperty = $reflection->getProperty('parser');
        $parserType = $parserProperty->getType();
        $this->assertNotNull($parserType, 'Свойство $parser должно иметь тип');
        // После рефакторинга тип должен быть интерфейсом
        $this->assertStringEndsWith('MBProjectDataCollectorInterface', $parserType->getName(), 
            'Свойство $parser должно иметь тип MBProjectDataCollectorInterface');

        $brizyApiProperty = $reflection->getProperty('brizyApi');
        $brizyApiType = $brizyApiProperty->getType();
        $this->assertNotNull($brizyApiType, 'Свойство $brizyApi должно иметь тип');
        // После рефакторинга тип должен быть интерфейсом
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
