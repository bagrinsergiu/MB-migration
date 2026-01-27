<?php

declare(strict_types=1);

namespace MBMigration\Builder;

use MBMigration\Browser\BrowserInterface;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\Graph\QueryBuilder;
use MBMigration\Layer\MB\MBProjectDataCollector;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Тест для проверки рефакторинга PageController
 * 
 * Этот тест проверяет, что после рефакторинга:
 * - PageController можно создать с моками интерфейсов
 * - Зависимости правильно сохраняются
 * - Класс работает с переданными зависимостями
 * 
 * Задача: task-2.7-refactor-page-controller
 * Принцип: Сразу тестировать - после рефакторинга написать тест
 */
class PageControllerRefactoringTest extends TestCase
{
    /**
     * Инициализация перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Инициализируем Logger для тестов
        Logger::initialize('test', null, 'php://memory');
        // Инициализируем Config для FontsController (он создает BrizyAPI в конструкторе)
        $this->initializeConfig();
    }

    /**
     * Создать тестовый Config
     */
    private function createTestConfig(): Config
    {
        return new Config(
            'test-cloud-host',
            'test-path',
            'test-cache-path',
            'test-token',
            [
                'db' => [
                    'dbHost' => 'localhost',
                    'dbPort' => 3306,
                    'dbName' => 'test_db',
                    'dbUser' => 'test_user',
                    'dbPass' => 'test_pass',
                ],
                'db_mg' => [
                    'dbHost' => 'localhost',
                    'dbPort' => 3306,
                    'dbName' => 'test_db_mg',
                    'dbUser' => 'test_user',
                    'dbPass' => 'test_pass',
                ],
                'assets' => ['dummy' => 'value'],
                'previewBaseHost' => 'test-preview-host',
            ]
        );
    }

    /**
     * Инициализировать Config для использования в тестах
     * 
     * Config использует статические свойства, поэтому создаем его один раз
     * для инициализации статических свойств
     */
    private function initializeConfig(): void
    {
        // Config использует статические свойства, создаем его для инициализации
        // Это нужно для FontsController, который создает BrizyAPI в конструкторе
        $this->createTestConfig();
    }

    /**
     * Тест: PageController можно создать с моками интерфейсов
     * 
     * Проверяет, что конструктор принимает BrowserInterface и FontsController и класс можно создать
     */
    public function testCanCreatePageControllerWithMocks(): void
    {
        // Arrange: Подготовка моков
        $mbCollector = $this->createMock(MBProjectDataCollector::class);
        $brizyAPI = $this->createMock(BrizyAPI::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $logger = new NullLogger();
        $browser = $this->createMock(BrowserInterface::class);
        // FontsController - конкретный класс, не интерфейс
        // Для тестирования используем реальный экземпляр с тестовым projectId
        // Примечание: FontsController создает BrizyAPI в конструкторе, который требует Config
        // Config должен быть инициализирован в setUp()
        $fontsController = new FontsController('test-project-id');
        $projectID_Brizy = 12345;

        // Act: Создание объекта PageController с моками
        $controller = new PageController(
            $mbCollector,
            $brizyAPI,
            $queryBuilder,
            $logger,
            $browser,
            $fontsController,
            $projectID_Brizy
        );

        // Assert: Проверка, что объект создан
        $this->assertInstanceOf(PageController::class, $controller);
    }

    /**
     * Тест: Зависимости правильно сохраняются в свойства класса
     * 
     * Проверяет через рефлексию, что переданные зависимости сохраняются в свойства
     */
    public function testDependenciesAreStoredInProperties(): void
    {
        // Arrange: Подготовка моков
        $mbCollector = $this->createMock(MBProjectDataCollector::class);
        $brizyAPI = $this->createMock(BrizyAPI::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $logger = new NullLogger();
        $browser = $this->createMock(BrowserInterface::class);
        // FontsController - конкретный класс, не интерфейс
        // Для тестирования используем реальный экземпляр с тестовым projectId
        // Примечание: FontsController создает BrizyAPI в конструкторе, который требует Config
        // Config должен быть инициализирован в setUp()
        $fontsController = new FontsController('test-project-id');
        $projectID_Brizy = 12345;

        // Act: Создание объекта PageController
        $controller = new PageController(
            $mbCollector,
            $brizyAPI,
            $queryBuilder,
            $logger,
            $browser,
            $fontsController,
            $projectID_Brizy
        );

        // Assert: Проверка через рефлексию, что зависимости сохранены
        $reflection = new \ReflectionClass($controller);
        
        // Проверяем, что свойство $browser содержит переданный мок
        $browserProperty = $reflection->getProperty('browser');
        $browserProperty->setAccessible(true);
        $this->assertSame($browser, $browserProperty->getValue($controller), 'Property $browser should store the injected BrowserInterface');

        // Проверяем, что свойство $fontsController содержит переданный мок
        $fontsControllerProperty = $reflection->getProperty('fontsController');
        $fontsControllerProperty->setAccessible(true);
        $this->assertSame($fontsController, $fontsControllerProperty->getValue($controller), 'Property $fontsController should store the injected FontsController');

        // Проверяем, что свойство $logger содержит переданный логгер
        $loggerProperty = $reflection->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $this->assertSame($logger, $loggerProperty->getValue($controller), 'Property $logger should store the injected LoggerInterface');
    }

    /**
     * Тест: Конструктор принимает все параметры с новыми зависимостями
     * 
     * Проверяет, что конструктор принимает BrowserInterface и FontsController
     * вместе со всеми существующими параметрами
     */
    public function testConstructorAcceptsAllParametersWithInterfaces(): void
    {
        // Arrange: Подготовка моков
        $mbCollector = $this->createMock(MBProjectDataCollector::class);
        $brizyAPI = $this->createMock(BrizyAPI::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $logger = new NullLogger();
        $browser = $this->createMock(BrowserInterface::class);
        // FontsController - конкретный класс, не интерфейс
        // Для тестирования используем реальный экземпляр с тестовым projectId
        // Примечание: FontsController создает BrizyAPI в конструкторе, который требует Config
        // Config должен быть инициализирован в setUp()
        $fontsController = new FontsController('test-project-id');
        $projectID_Brizy = 12345;
        $designName = 'test-design';
        $qualityAnalysis = true;
        $mb_element_name = 'test-element';
        $skip_media_upload = true;
        $skip_cache = true;

        // Act: Создание объекта PageController со всеми параметрами
        $controller = new PageController(
            $mbCollector,
            $brizyAPI,
            $queryBuilder,
            $logger,
            $browser,
            $fontsController,
            $projectID_Brizy,
            $designName,
            $qualityAnalysis,
            $mb_element_name,
            $skip_media_upload,
            $skip_cache
        );

        // Assert: Проверка, что объект создан
        $this->assertInstanceOf(PageController::class, $controller);
    }

    /**
     * Тест: Типы свойств соответствуют интерфейсам
     * 
     * Проверяет, что свойства имеют правильные типы после рефакторинга
     */
    public function testDependencyPropertiesHaveCorrectTypes(): void
    {
        // Arrange: Подготовка моков
        $mbCollector = $this->createMock(MBProjectDataCollector::class);
        $brizyAPI = $this->createMock(BrizyAPI::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $logger = new NullLogger();
        $browser = $this->createMock(BrowserInterface::class);
        // FontsController - конкретный класс, не интерфейс
        // Для тестирования используем реальный экземпляр с тестовым projectId
        // Примечание: FontsController создает BrizyAPI в конструкторе, который требует Config
        // Config должен быть инициализирован в setUp()
        $fontsController = new FontsController('test-project-id');
        $projectID_Brizy = 12345;

        // Act: Создание объекта PageController
        $controller = new PageController(
            $mbCollector,
            $brizyAPI,
            $queryBuilder,
            $logger,
            $browser,
            $fontsController,
            $projectID_Brizy
        );

        // Assert: Проверка типов свойств через рефлексию
        $reflection = new \ReflectionClass($controller);
        
        // Проверяем тип свойства $browser
        $browserProperty = $reflection->getProperty('browser');
        $browserProperty->setAccessible(true);
        $browserValue = $browserProperty->getValue($controller);
        $this->assertInstanceOf(BrowserInterface::class, $browserValue, 'Property $browser should be of type BrowserInterface');

        // Проверяем тип свойства $fontsController
        $fontsControllerProperty = $reflection->getProperty('fontsController');
        $fontsControllerProperty->setAccessible(true);
        $fontsControllerValue = $fontsControllerProperty->getValue($controller);
        $this->assertInstanceOf(FontsController::class, $fontsControllerValue, 'Property $fontsController should be of type FontsController');
    }
}
