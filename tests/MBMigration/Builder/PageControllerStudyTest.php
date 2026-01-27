<?php

declare(strict_types=1);

namespace MBMigration\Builder;

use MBMigration\Core\Logger;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\Graph\QueryBuilder;
use MBMigration\Layer\MB\MBProjectDataCollector;
use MBMigration\Browser\BrowserInterface;
use MBMigration\Builder\Fonts\FontsController;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Тест для проверки понимания структуры класса PageController
 * 
 * Этот тест проверяет, что мы правильно поняли структуру класса PageController:
 * - Конструктор принимает определенные параметры
 * - Класс можно создать с минимальными параметрами
 * - Свойства класса доступны (через рефлексию)
 * - Зависимости создаются в конструкторе
 * 
 * @package MBMigration\Builder
 */
class PageControllerStudyTest extends TestCase
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
     * Тест: Проверка, что конструктор PageController принимает все необходимые параметры
     * 
     * Этот тест проверяет наше понимание сигнатуры конструктора.
     * Если тест проходит, значит мы правильно поняли структуру конструктора.
     */
    public function testConstructorAcceptsAllRequiredParameters(): void
    {
        // Arrange: Подготовка данных
        $mbCollector = $this->createMock(MBProjectDataCollector::class);
        $brizyAPI = $this->createMock(BrizyAPI::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $logger = new NullLogger();
        $browser = $this->createMock(BrowserInterface::class);
        $fontsController = $this->createMock(FontsController::class);
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

        // Assert: Проверка, что объект создан
        $this->assertInstanceOf(PageController::class, $controller);
    }

    /**
     * Тест: Проверка, что конструктор принимает все опциональные параметры
     * 
     * Этот тест проверяет, что мы правильно поняли все опциональные параметры конструктора.
     */
    public function testConstructorAcceptsAllOptionalParameters(): void
    {
        // Arrange: Подготовка данных
        $mbCollector = $this->createMock(MBProjectDataCollector::class);
        $brizyAPI = $this->createMock(BrizyAPI::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $logger = new NullLogger();
        $browser = $this->createMock(BrowserInterface::class);
        $fontsController = $this->createMock(FontsController::class);
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
     * Тест: Проверка, что конструктор создает зависимости
     * 
     * Этот тест проверяет, что конструктор создает зависимости:
     * - VariableCache через getInstance()
     * - ArrayManipulator
     * - PageDTO (два экземпляра)
     */
    public function testConstructorCreatesDependencies(): void
    {
        // Arrange: Подготовка данных
        $mbCollector = $this->createMock(MBProjectDataCollector::class);
        $brizyAPI = $this->createMock(BrizyAPI::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $logger = new NullLogger();
        $browser = $this->createMock(BrowserInterface::class);
        $fontsController = $this->createMock(FontsController::class);
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

        // Assert: Проверка, что зависимости созданы через рефлексию
        $reflection = new \ReflectionClass($controller);
        
        // Проверяем, что свойство $cache инициализировано
        $cacheProperty = $reflection->getProperty('cache');
        $cacheProperty->setAccessible(true);
        $cache = $cacheProperty->getValue($controller);
        $this->assertNotNull($cache, 'Property $cache should be initialized');

        // Проверяем, что свойство $ArrayManipulator инициализировано
        $arrayManipulatorProperty = $reflection->getProperty('ArrayManipulator');
        $arrayManipulatorProperty->setAccessible(true);
        $arrayManipulator = $arrayManipulatorProperty->getValue($controller);
        $this->assertNotNull($arrayManipulator, 'Property $ArrayManipulator should be initialized');
        $this->assertInstanceOf(\MBMigration\Builder\Utils\ArrayManipulator::class, $arrayManipulator);

        // Проверяем, что свойство $pageDTO инициализировано
        $pageDTOProperty = $reflection->getProperty('pageDTO');
        $pageDTOProperty->setAccessible(true);
        $pageDTO = $pageDTOProperty->getValue($controller);
        $this->assertNotNull($pageDTO, 'Property $pageDTO should be initialized');
        $this->assertInstanceOf(\MBMigration\Builder\Layout\Common\DTO\PageDto::class, $pageDTO);
    }

    /**
     * Тест: Проверка, что свойства имеют правильные типы
     * 
     * Этот тест проверяет, что свойства класса имеют правильные типы,
     * как указано в PHPDoc комментариях.
     */
    public function testDependencyPropertiesHaveCorrectTypes(): void
    {
        // Arrange: Подготовка данных
        $mbCollector = $this->createMock(MBProjectDataCollector::class);
        $brizyAPI = $this->createMock(BrizyAPI::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $logger = new NullLogger();
        $browser = $this->createMock(BrowserInterface::class);
        $fontsController = $this->createMock(FontsController::class);
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
        
        // Проверяем тип свойства $brizyAPI
        $brizyAPIProperty = $reflection->getProperty('brizyAPI');
        $brizyAPIProperty->setAccessible(true);
        $brizyAPIValue = $brizyAPIProperty->getValue($controller);
        $this->assertInstanceOf(BrizyAPI::class, $brizyAPIValue, 'Property $brizyAPI should be of type BrizyAPI');

        // Проверяем тип свойства $QueryBuilder
        $queryBuilderProperty = $reflection->getProperty('QueryBuilder');
        $queryBuilderProperty->setAccessible(true);
        $queryBuilderValue = $queryBuilderProperty->getValue($controller);
        $this->assertInstanceOf(QueryBuilder::class, $queryBuilderValue, 'Property $QueryBuilder should be of type QueryBuilder');

        // Проверяем тип свойства $parser
        $parserProperty = $reflection->getProperty('parser');
        $parserProperty->setAccessible(true);
        $parserValue = $parserProperty->getValue($controller);
        $this->assertInstanceOf(MBProjectDataCollector::class, $parserValue, 'Property $parser should be of type MBProjectDataCollector');

        // Проверяем тип свойства $logger
        $loggerProperty = $reflection->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $loggerValue = $loggerProperty->getValue($controller);
        $this->assertInstanceOf(\Psr\Log\LoggerInterface::class, $loggerValue, 'Property $logger should be of type LoggerInterface');
    }

    /**
     * Тест: Проверка, что переданные зависимости сохраняются в свойствах
     * 
     * Этот тест проверяет, что зависимости, переданные через конструктор,
     * правильно сохраняются в свойствах класса.
     */
    public function testDependenciesAreStoredInProperties(): void
    {
        // Arrange: Подготовка данных
        $mbCollector = $this->createMock(MBProjectDataCollector::class);
        $brizyAPI = $this->createMock(BrizyAPI::class);
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $logger = new NullLogger();
        $browser = $this->createMock(BrowserInterface::class);
        $fontsController = $this->createMock(FontsController::class);
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

        // Assert: Проверка, что зависимости сохранены
        $reflection = new \ReflectionClass($controller);
        
        // Проверяем, что $brizyAPI сохранен
        $brizyAPIProperty = $reflection->getProperty('brizyAPI');
        $brizyAPIProperty->setAccessible(true);
        $this->assertSame($brizyAPI, $brizyAPIProperty->getValue($controller), 'Property $brizyAPI should store the injected dependency');

        // Проверяем, что $QueryBuilder сохранен
        $queryBuilderProperty = $reflection->getProperty('QueryBuilder');
        $queryBuilderProperty->setAccessible(true);
        $this->assertSame($queryBuilder, $queryBuilderProperty->getValue($controller), 'Property $QueryBuilder should store the injected dependency');

        // Проверяем, что $parser сохранен
        $parserProperty = $reflection->getProperty('parser');
        $parserProperty->setAccessible(true);
        $this->assertSame($mbCollector, $parserProperty->getValue($controller), 'Property $parser should store the injected dependency');

        // Проверяем, что $logger сохранен
        $loggerProperty = $reflection->getProperty('logger');
        $loggerProperty->setAccessible(true);
        $this->assertSame($logger, $loggerProperty->getValue($controller), 'Property $logger should store the injected dependency');

        // Проверяем, что $projectID_Brizy сохранен
        $projectIDProperty = $reflection->getProperty('projectID_Brizy');
        $projectIDProperty->setAccessible(true);
        $this->assertSame($projectID_Brizy, $projectIDProperty->getValue($controller), 'Property $projectID_Brizy should store the injected value');
    }
}
