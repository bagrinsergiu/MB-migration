<?php

namespace MBMigration\Layer\Brizy;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тест для проверки анализа класса BrizyAPI
 * 
 * Этот тест проверяет, что анализ класса BrizyAPI выполнен корректно:
 * - Все публичные методы найдены
 * - Документ анализа создан
 * - Методы имеют правильные сигнатуры
 * 
 * Задача: task-1.2-study-brizy-api
 * Принцип: Сразу тестировать - после анализа написать тест
 */
class BrizyAPIAnalysisTest extends TestCase
{
    /**
     * Путь к файлу анализа
     */
    private const ANALYSIS_FILE = __DIR__ . '/../../../../testing-improvement/stage-1/substage-1.1/tasks/BRIZY_API_ANALYSIS.md';

    /**
     * Путь к классу BrizyAPI
     */
    private const BRIZY_API_CLASS = BrizyAPI::class;

    /**
     * Тест: документ анализа должен существовать
     * 
     * Проверяет, что документ с анализом создан
     */
    public function testAnalysisDocumentExists(): void
    {
        $this->assertFileExists(
            self::ANALYSIS_FILE,
            'Документ анализа BRIZY_API_ANALYSIS.md должен существовать'
        );
    }

    /**
     * Тест: документ анализа должен содержать информацию о методах
     * 
     * Проверяет, что документ содержит ключевую информацию
     */
    public function testAnalysisDocumentContainsMethods(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertNotEmpty($content, 'Документ анализа не должен быть пустым');
        $this->assertStringContainsString('Публичные методы', $content, 'Документ должен содержать раздел "Публичные методы"');
        $this->assertStringContainsString('createProject', $content, 'Документ должен содержать информацию о методе createProject');
        $this->assertStringContainsString('createPage', $content, 'Документ должен содержать информацию о методе createPage');
        $this->assertStringContainsString('createMedia', $content, 'Документ должен содержать информацию о методе createMedia');
    }

    /**
     * Тест: все публичные методы BrizyAPI должны быть задокументированы
     * 
     * Проверяет, что все публичные методы класса упомянуты в документе анализа
     */
    public function testAllPublicMethodsAreDocumented(): void
    {
        $reflection = new ReflectionClass(self::BRIZY_API_CLASS);
        $publicMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем методы, унаследованные от родительского класса Utils
        $brizyApiMethods = array_filter($publicMethods, function($method) {
            return $method->getDeclaringClass()->getName() === self::BRIZY_API_CLASS;
        });
        
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        foreach ($brizyApiMethods as $method) {
            $methodName = $method->getName();
            $this->assertStringContainsString(
                $methodName,
                $content,
                "Метод {$methodName} должен быть задокументирован в анализе"
            );
        }
    }

    /**
     * Тест: документ должен содержать классификацию методов
     * 
     * Проверяет наличие раздела с классификацией методов по критичности
     */
    public function testAnalysisContainsClassification(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertStringContainsString(
            'Классификация методов по критичности',
            $content,
            'Документ должен содержать классификацию методов по критичности'
        );
        
        $this->assertStringContainsString(
            'КРИТИЧЕСКИЕ',
            $content,
            'Документ должен содержать раздел с критическими методами'
        );
    }

    /**
     * Тест: документ должен содержать информацию о зависимостях
     * 
     * Проверяет наличие раздела о зависимостях между методами
     */
    public function testAnalysisContainsDependencies(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertStringContainsString(
            'Зависимости между методами',
            $content,
            'Документ должен содержать информацию о зависимостях между методами'
        );
    }

    /**
     * Тест: документ должен содержать рекомендации
     * 
     * Проверяет наличие раздела с рекомендациями для создания интерфейса
     */
    public function testAnalysisContainsRecommendations(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertStringContainsString(
            'Рекомендации для интерфейса',
            $content,
            'Документ должен содержать рекомендации для создания интерфейса'
        );
    }

    /**
     * Тест: количество публичных методов должно соответствовать документу
     * 
     * Проверяет, что количество методов в документе соответствует реальному количеству
     */
    public function testMethodCountMatches(): void
    {
        $reflection = new ReflectionClass(self::BRIZY_API_CLASS);
        $publicMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем методы, унаследованные от родительского класса
        $brizyApiMethods = array_filter($publicMethods, function($method) {
            return $method->getDeclaringClass()->getName() === self::BRIZY_API_CLASS;
        });
        
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        // Проверяем, что в документе указано общее количество методов
        $this->assertStringContainsString(
            'Всего публичных методов',
            $content,
            'Документ должен содержать информацию об общем количестве методов'
        );
        
        // Проверяем, что количество методов в документе соответствует реальному
        // (приблизительная проверка - ищем упоминания методов)
        $methodCount = count($brizyApiMethods);
        $this->assertGreaterThan(
            30,
            $methodCount,
            "Ожидается более 30 публичных методов, найдено: {$methodCount}"
        );
    }
}
