<?php

declare(strict_types=1);

namespace MBMigration;

use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Тест для проверки обновления создания PageController в MigrationPlatform
 * 
 * Этот тест проверяет, что после рефакторинга PageController:
 * - MigrationPlatform компилируется без ошибок
 * - Наличие необходимых use statements для BrowserPHP и FontsController
 * - Создание зависимостей перед PageController
 * - Наличие новых параметров в конструкторе PageController
 * - Наличие комментариев, объясняющих изменения
 * 
 * Задача: task-2.8-update-page-controller-creations
 * Принцип: Сразу тестировать - после обновления написать тест
 */
class MigrationPlatformPageControllerUpdateTest extends TestCase
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
     * Тест: MigrationPlatform компилируется без ошибок
     * 
     * Проверяет, что файл MigrationPlatform.php имеет правильный синтаксис
     */
    public function testMigrationPlatformCompilesWithoutErrors(): void
    {
        // Arrange: Путь к файлу
        $filePath = __DIR__ . '/../../lib/MBMigration/MigrationPlatform.php';
        
        // Act: Проверка синтаксиса через exec
        $output = [];
        $returnVar = 0;
        exec("php -l $filePath 2>&1", $output, $returnVar);
        $outputString = implode("\n", $output);
        
        // Assert: Файл должен компилироваться без ошибок
        $this->assertEquals(0, $returnVar, "MigrationPlatform.php should compile without errors. Output: $outputString");
        $this->assertStringContainsString('No syntax errors', $outputString, 'Should have no syntax errors');
    }

    /**
     * Тест: Наличие необходимых use statements
     * 
     * Проверяет, что в MigrationPlatform.php есть use statements для BrowserPHP и FontsController
     */
    public function testHasRequiredUseStatements(): void
    {
        // Arrange: Чтение файла
        $filePath = __DIR__ . '/../../lib/MBMigration/MigrationPlatform.php';
        $content = file_get_contents($filePath);
        
        // Assert: Проверка наличия use statements
        $this->assertStringContainsString(
            'use MBMigration\Browser\BrowserPHP;',
            $content,
            'Should have use statement for BrowserPHP'
        );
        $this->assertStringContainsString(
            'use MBMigration\Builder\Fonts\FontsController;',
            $content,
            'Should have use statement for FontsController'
        );
    }

    /**
     * Тест: Создание зависимостей перед PageController
     * 
     * Проверяет, что перед созданием PageController создаются зависимости
     */
    public function testDependenciesCreatedBeforePageController(): void
    {
        // Arrange: Чтение файла
        $filePath = __DIR__ . '/../../lib/MBMigration/MigrationPlatform.php';
        $content = file_get_contents($filePath);
        
        // Assert: Проверка создания зависимостей
        $this->assertStringContainsString(
            'BrowserPHP::instance',
            $content,
            'Should create BrowserPHP before PageController'
        );
        $this->assertStringContainsString(
            'new FontsController',
            $content,
            'Should create FontsController before PageController'
        );
        
        // Проверяем, что создание зависимостей происходит перед созданием PageController
        $pageControllerPos = strpos($content, 'new PageController');
        $browserPos = strpos($content, 'BrowserPHP::instance');
        $fontsControllerPos = strpos($content, 'new FontsController');
        
        $this->assertNotFalse($pageControllerPos, 'Should have PageController creation');
        $this->assertNotFalse($browserPos, 'Should have BrowserPHP creation');
        $this->assertNotFalse($fontsControllerPos, 'Should have FontsController creation');
        
        // BrowserPHP и FontsController должны создаваться перед PageController
        $this->assertLessThan($pageControllerPos, $browserPos, 'BrowserPHP should be created before PageController');
        $this->assertLessThan($pageControllerPos, $fontsControllerPos, 'FontsController should be created before PageController');
    }

    /**
     * Тест: Наличие новых параметров в конструкторе PageController
     * 
     * Проверяет, что в вызове конструктора PageController присутствуют новые параметры
     */
    public function testConstructorParametersOrder(): void
    {
        // Arrange: Чтение файла
        $filePath = __DIR__ . '/../../lib/MBMigration/MigrationPlatform.php';
        $content = file_get_contents($filePath);
        
        // Assert: Проверка наличия параметров в конструкторе
        // Ищем вызов конструктора PageController
        $this->assertStringContainsString(
            '$browser',
            $content,
            'Should have $browser parameter in PageController constructor'
        );
        $this->assertStringContainsString(
            '$fontsController',
            $content,
            'Should have $fontsController parameter in PageController constructor'
        );
        
        // Проверяем, что параметры идут в правильном порядке (после $logger, перед $projectID_Brizy)
        $pageControllerCall = $this->extractPageControllerCall($content);
        $this->assertNotNull($pageControllerCall, 'Should find PageController constructor call');
        
        // Проверяем порядок параметров: logger, browser, fontsController, projectID_Brizy
        $loggerPos = strpos($pageControllerCall, '$this->logger');
        $browserPos = strpos($pageControllerCall, '$browser');
        $fontsControllerPos = strpos($pageControllerCall, '$fontsController');
        $projectIDPos = strpos($pageControllerCall, '$this->projectID_Brizy');
        
        $this->assertNotFalse($loggerPos, 'Should have $this->logger parameter');
        $this->assertNotFalse($browserPos, 'Should have $browser parameter');
        $this->assertNotFalse($fontsControllerPos, 'Should have $fontsController parameter');
        $this->assertNotFalse($projectIDPos, 'Should have $this->projectID_Brizy parameter');
        
        // Проверяем порядок: logger < browser < fontsController < projectID_Brizy
        $this->assertLessThan($browserPos, $loggerPos, '$browser should come after $this->logger');
        $this->assertLessThan($fontsControllerPos, $browserPos, '$fontsController should come after $browser');
        $this->assertLessThan($projectIDPos, $fontsControllerPos, '$this->projectID_Brizy should come after $fontsController');
    }

    /**
     * Тест: Наличие комментариев, объясняющих изменения
     * 
     * Проверяет, что есть комментарии, объясняющие рефакторинг
     */
    public function testHasCommentsExplainingChanges(): void
    {
        // Arrange: Чтение файла
        $filePath = __DIR__ . '/../../lib/MBMigration/MigrationPlatform.php';
        $content = file_get_contents($filePath);
        
        // Assert: Проверка наличия комментариев
        $this->assertStringContainsString(
            'Создаем зависимости для PageController',
            $content,
            'Should have comment explaining dependency creation'
        );
        $this->assertStringContainsString(
            'рефакторинг для тестируемости',
            $content,
            'Should have comment mentioning refactoring for testability'
        );
    }

    /**
     * Извлечь вызов конструктора PageController из содержимого файла
     * 
     * @param string $content Содержимое файла
     * @return string|null Вызов конструктора или null
     */
    private function extractPageControllerCall(string $content): ?string
    {
        // Ищем начало вызова конструктора
        $startPos = strpos($content, 'new PageController(');
        if ($startPos === false) {
            return null;
        }
        
        // Находим конец вызова (закрывающая скобка)
        $pos = $startPos;
        $depth = 0;
        $start = false;
        
        while ($pos < strlen($content)) {
            $char = $content[$pos];
            
            if ($char === '(') {
                $depth++;
                $start = true;
            } elseif ($char === ')') {
                $depth--;
                if ($start && $depth === 0) {
                    return substr($content, $startPos, $pos - $startPos + 1);
                }
            }
            
            $pos++;
        }
        
        return null;
    }
}
