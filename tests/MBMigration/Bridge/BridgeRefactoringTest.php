<?php

declare(strict_types=1);

namespace MBMigration\Bridge;

use MBMigration\ApplicationBootstrapper;
use MBMigration\Contracts\BrizyAPIInterface;
use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Тест для проверки рефакторинга Bridge
 * 
 * Этот тест проверяет, что после рефакторинга:
 * - Bridge можно создать с моками интерфейсов
 * - Зависимости правильно сохраняются
 * - Класс работает с переданными зависимостями
 * 
 * Задача: task-2.10-refactor-bridge
 * Принцип: Сразу тестировать - после рефакторинга написать тест
 */
class BridgeRefactoringTest extends TestCase
{
    /**
     * Инициализация перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Инициализируем Logger для тестов
        Logger::initialize('test', null, 'php://memory');
    }

    /**
     * Тест: Bridge можно создать с моками интерфейсов
     * 
     * Проверяет, что конструктор принимает BrizyAPIInterface и класс можно создать
     */
    public function testCanCreateBridgeWithMocks(): void
    {
        // Arrange: Подготовка моков
        $app = $this->createMock(ApplicationBootstrapper::class);
        $config = $this->createTestConfig();
        $request = Request::create('/test');
        $brizyApi = $this->createMock(BrizyAPIInterface::class);
        
        // Устанавливаем статические свойства Config для doConnectionToDB()
        \MBMigration\Core\Config::$mgConfigMySQL = [
            'dbUser' => 'test_user',
            'dbPass' => 'test_pass',
            'dbName' => 'test_db',
            'dbHost' => 'localhost'
        ];

        // Act: Создание объекта Bridge с моками
        // Примечание: Это может выбросить исключение, если БД недоступна
        try {
            $bridge = new Bridge(
                $app,
                $config,
                $request,
                $brizyApi
            );
        } catch (\Exception $e) {
            // Если не удалось подключиться к БД, пропускаем тест
            $this->markTestSkipped('Database connection required for this test: ' . $e->getMessage());
            return;
        }

        // Assert: Проверка, что объект создан
        $this->assertInstanceOf(Bridge::class, $bridge);
    }

    /**
     * Тест: Зависимости правильно сохраняются в свойства класса
     * 
     * Проверяет через рефлексию, что переданные зависимости сохраняются в свойства
     */
    public function testDependenciesAreStoredInProperties(): void
    {
        // Arrange: Подготовка моков
        $app = $this->createMock(ApplicationBootstrapper::class);
        $config = $this->createTestConfig();
        $request = Request::create('/test');
        $brizyApi = $this->createMock(BrizyAPIInterface::class);
        
        // Устанавливаем статические свойства Config для doConnectionToDB()
        \MBMigration\Core\Config::$mgConfigMySQL = [
            'dbUser' => 'test_user',
            'dbPass' => 'test_pass',
            'dbName' => 'test_db',
            'dbHost' => 'localhost'
        ];

        // Act: Создание объекта Bridge
        try {
            $bridge = new Bridge(
                $app,
                $config,
                $request,
                $brizyApi
            );
        } catch (\Exception $e) {
            $this->markTestSkipped('Database connection required for this test: ' . $e->getMessage());
            return;
        }

        // Assert: Проверка через рефлексию, что зависимости сохранены
        $reflection = new \ReflectionClass($bridge);
        
        // Проверяем, что $brizyApi сохранен
        $brizyApiProperty = $reflection->getProperty('brizyApi');
        $brizyApiProperty->setAccessible(true);
        $this->assertSame($brizyApi, $brizyApiProperty->getValue($bridge), 'Property $brizyApi should store the injected dependency');
    }

    /**
     * Тест: Конструктор принимает все параметры с новым интерфейсом
     * 
     * Проверяет, что конструктор принимает BrizyAPIInterface
     * вместе со всеми существующими параметрами
     */
    public function testConstructorAcceptsAllParametersWithInterface(): void
    {
        // Arrange: Подготовка моков
        $app = $this->createMock(ApplicationBootstrapper::class);
        $config = $this->createTestConfig();
        $request = Request::create('/test');
        $brizyApi = $this->createMock(BrizyAPIInterface::class);
        
        // Устанавливаем статические свойства Config для doConnectionToDB()
        \MBMigration\Core\Config::$mgConfigMySQL = [
            'dbUser' => 'test_user',
            'dbPass' => 'test_pass',
            'dbName' => 'test_db',
            'dbHost' => 'localhost'
        ];

        // Act: Создание объекта Bridge со всеми параметрами
        try {
            $bridge = new Bridge(
                $app,
                $config,
                $request,
                $brizyApi
            );
        } catch (\Exception $e) {
            $this->markTestSkipped('Database connection required for this test: ' . $e->getMessage());
            return;
        }

        // Assert: Проверка, что объект создан
        $this->assertInstanceOf(Bridge::class, $bridge);
    }

    /**
     * Тест: Типы свойств соответствуют интерфейсам
     * 
     * Проверяет, что свойства имеют правильные типы после рефакторинга
     */
    public function testDependencyPropertiesHaveCorrectTypes(): void
    {
        // Arrange: Подготовка моков
        $app = $this->createMock(ApplicationBootstrapper::class);
        $config = $this->createTestConfig();
        $request = Request::create('/test');
        $brizyApi = $this->createMock(BrizyAPIInterface::class);
        
        // Устанавливаем статические свойства Config для doConnectionToDB()
        \MBMigration\Core\Config::$mgConfigMySQL = [
            'dbUser' => 'test_user',
            'dbPass' => 'test_pass',
            'dbName' => 'test_db',
            'dbHost' => 'localhost'
        ];

        // Act: Создание объекта Bridge
        try {
            $bridge = new Bridge(
                $app,
                $config,
                $request,
                $brizyApi
            );
        } catch (\Exception $e) {
            $this->markTestSkipped('Database connection required for this test: ' . $e->getMessage());
            return;
        }

        // Assert: Проверка типов свойств через рефлексию
        $reflection = new \ReflectionClass($bridge);
        
        // Проверяем тип свойства $brizyApi
        $brizyApiProperty = $reflection->getProperty('brizyApi');
        $brizyApiProperty->setAccessible(true);
        $brizyApiValue = $brizyApiProperty->getValue($bridge);
        $this->assertInstanceOf(BrizyAPIInterface::class, $brizyApiValue, 'Property $brizyApi should be of type BrizyAPIInterface');
    }

    /**
     * Создать тестовый Config
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
