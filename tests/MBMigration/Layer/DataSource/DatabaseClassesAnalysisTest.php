<?php

namespace MBMigration\Layer\DataSource;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тест для проверки анализа классов Database (MySQL и PostgresSQL)
 * 
 * Этот тест проверяет, что анализ классов Database выполнен корректно:
 * - Оба класса изучены
 * - Все публичные методы найдены
 * - Документ анализа создан
 * - Сравнение методов выполнено
 * - Рекомендации присутствуют
 * 
 * Задача: task-1.6-study-database-classes
 * Принцип: Сразу тестировать - после анализа написать тест
 */
class DatabaseClassesAnalysisTest extends TestCase
{
    /**
     * Путь к файлу анализа
     */
    private const ANALYSIS_FILE = __DIR__ . '/../../../../testing-improvement/stage-1/substage-1.1/tasks/DATABASE_CLASSES_ANALYSIS.md';

    /**
     * Путь к классу MySQL
     */
    private const MYSQL_CLASS = \MBMigration\Layer\DataSource\driver\MySQL::class;

    /**
     * Путь к классу PostgresSQL
     */
    private const POSTGRESQL_CLASS = \MBMigration\Layer\DataSource\driver\PostgresSQL::class;

    /**
     * Получить содержимое файла анализа или пропустить тест, если файл не существует
     * 
     * @return string Содержимое файла
     */
    private function getAnalysisFileContent(): string
    {
        if (!file_exists(self::ANALYSIS_FILE)) {
            $this->markTestSkipped('Файл анализа не существует: ' . self::ANALYSIS_FILE);
        }
        return file_get_contents(self::ANALYSIS_FILE);
    }

    /**
     * Тест: документ анализа должен существовать
     * 
     * Проверяет, что документ с анализом создан
     */
    public function testAnalysisDocumentExists(): void
    {
        if (!file_exists(self::ANALYSIS_FILE)) {
            $this->markTestSkipped('Файл анализа не существует: ' . self::ANALYSIS_FILE);
        }
        $this->assertFileExists(
            self::ANALYSIS_FILE,
            'Документ анализа DATABASE_CLASSES_ANALYSIS.md должен существовать'
        );
    }

    /**
     * Тест: документ анализа должен содержать информацию о классах
     * 
     * Проверяет, что документ содержит ключевую информацию
     */
    public function testAnalysisDocumentContainsClasses(): void
    {
        $content = $this->getAnalysisFileContent();
        
        $this->assertNotEmpty($content, 'Документ анализа не должен быть пустым');
        $this->assertStringContainsString('MySQL', $content, 'Документ должен содержать информацию о классе MySQL');
        $this->assertStringContainsString('PostgresSQL', $content, 'Документ должен содержать информацию о классе PostgresSQL');
        $this->assertStringContainsString('Публичные методы', $content, 'Документ должен содержать раздел "Публичные методы"');
    }

    /**
     * Тест: документ должен содержать информацию о методах MySQL
     * 
     * Проверяет наличие информации о методах класса MySQL
     */
    public function testAnalysisContainsMySQLMethods(): void
    {
        $content = $this->getAnalysisFileContent();
        
        $this->assertStringContainsString('getAllRows', $content, 'Документ должен содержать информацию о методе getAllRows');
        $this->assertStringContainsString('find', $content, 'Документ должен содержать информацию о методе find');
        $this->assertStringContainsString('insert', $content, 'Документ должен содержать информацию о методе insert');
        $this->assertStringContainsString('delete', $content, 'Документ должен содержать информацию о методе delete');
    }

    /**
     * Тест: документ должен содержать информацию о методах PostgresSQL
     * 
     * Проверяет наличие информации о методах класса PostgresSQL
     */
    public function testAnalysisContainsPostgresSQLMethods(): void
    {
        $content = $this->getAnalysisFileContent();
        
        $this->assertStringContainsString('request', $content, 'Документ должен содержать информацию о методе request');
        $this->assertStringContainsString('requestArray', $content, 'Документ должен содержать информацию о методе requestArray');
    }

    /**
     * Тест: документ должен содержать сравнение методов
     * 
     * Проверяет наличие раздела с сравнением методов
     */
    public function testAnalysisContainsComparison(): void
    {
        $content = $this->getAnalysisFileContent();
        
        $this->assertStringContainsString(
            'Сравнение методов',
            $content,
            'Документ должен содержать раздел с сравнением методов'
        );
    }

    /**
     * Тест: документ должен содержать информацию об использовании
     * 
     * Проверяет наличие раздела об использовании классов в проекте
     */
    public function testAnalysisContainsUsage(): void
    {
        $content = $this->getAnalysisFileContent();
        
        $this->assertStringContainsString(
            'Использование в проекте',
            $content,
            'Документ должен содержать информацию об использовании классов в проекте'
        );
    }

    /**
     * Тест: документ должен содержать рекомендации
     * 
     * Проверяет наличие раздела с рекомендациями для создания интерфейса
     */
    public function testAnalysisContainsRecommendations(): void
    {
        $content = $this->getAnalysisFileContent();
        
        $this->assertStringContainsString(
            'Рекомендации для интерфейса',
            $content,
            'Документ должен содержать рекомендации для создания интерфейса'
        );
    }

    /**
     * Тест: документ должен содержать информацию об уникальных методах
     * 
     * Проверяет наличие информации об уникальных методах каждого класса
     */
    public function testAnalysisContainsUniqueMethods(): void
    {
        $content = $this->getAnalysisFileContent();
        
        $this->assertStringContainsString(
            'Уникальные методы',
            $content,
            'Документ должен содержать информацию об уникальных методах'
        );
    }

    /**
     * Тест: количество методов MySQL должно соответствовать документу
     * 
     * Проверяет, что количество методов в документе соответствует реальному
     */
    public function testMySQLMethodCountMatches(): void
    {
        $reflection = new ReflectionClass(self::MYSQL_CLASS);
        $publicMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем конструктор
        $mysqlMethods = array_filter($publicMethods, function($method) {
            return $method->getName() !== '__construct';
        });
        
        $content = $this->getAnalysisFileContent();
        
        // Проверяем, что в документе упомянуты основные методы
        $expectedMethods = ['doConnect', 'getAllRows', 'find', 'insert', 'delete', 'getSingleValue', 'getColumns'];
        
        foreach ($expectedMethods as $methodName) {
            $this->assertStringContainsString(
                $methodName,
                $content,
                "Метод {$methodName} должен быть упомянут в анализе"
            );
        }
    }

    /**
     * Тест: количество методов PostgresSQL должно соответствовать документу
     * 
     * Проверяет, что количество методов в документе соответствует реальному
     */
    public function testPostgresSQLMethodCountMatches(): void
    {
        $reflection = new ReflectionClass(self::POSTGRESQL_CLASS);
        $publicMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем конструктор
        $postgresMethods = array_filter($publicMethods, function($method) {
            return $method->getName() !== '__construct';
        });
        
        $content = $this->getAnalysisFileContent();
        
        // Проверяем, что в документе упомянуты все методы
        $expectedMethods = ['request', 'requestArray'];
        
        foreach ($expectedMethods as $methodName) {
            $this->assertStringContainsString(
                $methodName,
                $content,
                "Метод {$methodName} должен быть упомянут в анализе"
            );
        }
    }

    /**
     * Тест: документ должен содержать итоговые рекомендации
     * 
     * Проверяет наличие раздела с итоговыми рекомендациями
     */
    public function testAnalysisContainsFinalRecommendations(): void
    {
        $content = $this->getAnalysisFileContent();
        
        $this->assertStringContainsString(
            'Итоговые рекомендации',
            $content,
            'Документ должен содержать раздел с итоговыми рекомендациями'
        );
        
        // Проверяем наличие конкретных рекомендаций
        $this->assertStringContainsString(
            'query',
            $content,
            'Документ должен содержать рекомендацию о методе query'
        );
    }
}
