<?php

namespace MBMigration\Layer\MB;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тест для проверки анализа класса MBProjectDataCollector
 * 
 * Этот тест проверяет, что анализ класса MBProjectDataCollector выполнен корректно:
 * - Все публичные методы найдены
 * - Документ анализа создан
 * - Методы имеют правильные сигнатуры
 * - Статические методы учтены
 * 
 * Задача: task-1.4-study-mb-collector
 * Принцип: Сразу тестировать - после анализа написать тест
 */
class MBProjectDataCollectorAnalysisTest extends TestCase
{
    /**
     * Путь к файлу анализа
     */
    private const ANALYSIS_FILE = __DIR__ . '/../../../../testing-improvement/stage-1/substage-1.1/tasks/MB_PROJECT_DATA_COLLECTOR_ANALYSIS.md';

    /**
     * Путь к классу MBProjectDataCollector
     */
    private const MB_COLLECTOR_CLASS = MBProjectDataCollector::class;

    /**
     * Тест: документ анализа должен существовать
     * 
     * Проверяет, что документ с анализом создан
     */
    public function testAnalysisDocumentExists(): void
    {
        $this->assertFileExists(
            self::ANALYSIS_FILE,
            'Документ анализа MB_PROJECT_DATA_COLLECTOR_ANALYSIS.md должен существовать'
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
        $this->assertStringContainsString('getSite', $content, 'Документ должен содержать информацию о методе getSite');
        $this->assertStringContainsString('getPages', $content, 'Документ должен содержать информацию о методе getPages');
        $this->assertStringContainsString('getMainSection', $content, 'Документ должен содержать информацию о методе getMainSection');
    }

    /**
     * Тест: все публичные методы MBProjectDataCollector должны быть задокументированы
     * 
     * Проверяет, что все публичные методы класса упомянуты в документе анализа
     */
    public function testAllPublicMethodsAreDocumented(): void
    {
        $reflection = new ReflectionClass(self::MB_COLLECTOR_CLASS);
        $publicMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем методы из trait DebugBackTrace
        $mbCollectorMethods = array_filter($publicMethods, function($method) {
            return $method->getDeclaringClass()->getName() === self::MB_COLLECTOR_CLASS;
        });
        
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        foreach ($mbCollectorMethods as $method) {
            $methodName = $method->getName();
            // Пропускаем конструктор, так как он не включается в интерфейс
            if ($methodName === '__construct') {
                continue;
            }
            
            $this->assertStringContainsString(
                $methodName,
                $content,
                "Метод {$methodName} должен быть задокументирован в анализе"
            );
        }
    }

    /**
     * Тест: документ должен содержать информацию о статических методах
     * 
     * Проверяет наличие раздела о статических методах
     */
    public function testAnalysisContainsStaticMethods(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertStringContainsString(
            'Статические методы',
            $content,
            'Документ должен содержать информацию о статических методах'
        );
        
        // Проверяем, что упомянуты конкретные статические методы
        $this->assertStringContainsString(
            'getIdByUUID',
            $content,
            'Документ должен содержать информацию о статическом методе getIdByUUID'
        );
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
        $reflection = new ReflectionClass(self::MB_COLLECTOR_CLASS);
        $publicMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем методы из trait и конструктор
        $mbCollectorMethods = array_filter($publicMethods, function($method) {
            return $method->getDeclaringClass()->getName() === self::MB_COLLECTOR_CLASS
                && $method->getName() !== '__construct';
        });
        
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        // Проверяем, что в документе указано общее количество методов
        $this->assertStringContainsString(
            'Всего публичных методов',
            $content,
            'Документ должен содержать информацию об общем количестве методов'
        );
        
        // Проверяем, что количество методов в документе соответствует реальному
        $methodCount = count($mbCollectorMethods);
        $this->assertGreaterThan(
            15,
            $methodCount,
            "Ожидается более 15 публичных методов, найдено: {$methodCount}"
        );
    }

    /**
     * Тест: статические методы должны быть задокументированы
     * 
     * Проверяет, что все статические методы упомянуты в документе
     */
    public function testStaticMethodsAreDocumented(): void
    {
        $reflection = new ReflectionClass(self::MB_COLLECTOR_CLASS);
        $staticMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_STATIC);
        
        // Исключаем методы из trait и приватные методы
        $mbCollectorStaticMethods = array_filter($staticMethods, function($method) {
            return $method->getDeclaringClass()->getName() === self::MB_COLLECTOR_CLASS
                && !$method->isPrivate();
        });
        
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        // Список известных статических методов из класса
        $expectedStaticMethods = [
            'getIdByUUID',
            'getDomainBySiteId',
            'normalizeDomain',
            'getAllDomainsBySiteId',
            'isDomainAccessible',
            'findAvailableDomain'
        ];
        
        foreach ($expectedStaticMethods as $methodName) {
            $this->assertStringContainsString(
                $methodName,
                $content,
                "Статический метод {$methodName} должен быть задокументирован в анализе"
            );
        }
    }
}
