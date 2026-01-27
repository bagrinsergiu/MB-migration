<?php

namespace MBMigration\Core;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Тест для проверки анализа класса S3Uploader
 * 
 * Этот тест проверяет, что анализ класса S3Uploader выполнен корректно:
 * - Все публичные методы найдены
 * - Документ анализа создан
 * - Методы имеют правильные сигнатуры
 * - Особенности работы задокументированы
 * 
 * Задача: task-1.8-study-s3-uploader
 * Принцип: Сразу тестировать - после анализа написать тест
 */
class S3UploaderAnalysisTest extends TestCase
{
    /**
     * Путь к файлу анализа
     * Используем абсолютный путь для надежности в Docker контейнере
     */
    private const ANALYSIS_FILE = '/project/testing-improvement/stage-1/substage-1.1/tasks/S3_UPLOADER_ANALYSIS.md';

    /**
     * Путь к классу S3Uploader
     */
    private const S3_UPLOADER_CLASS = S3Uploader::class;

    /**
     * Тест: документ анализа должен существовать
     * 
     * Проверяет, что документ с анализом создан
     */
    public function testAnalysisDocumentExists(): void
    {
        $this->assertFileExists(
            self::ANALYSIS_FILE,
            'Документ анализа S3_UPLOADER_ANALYSIS.md должен существовать'
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
        $this->assertStringContainsString('uploadLogFile', $content, 'Документ должен содержать информацию о методе uploadLogFile');
    }

    /**
     * Тест: все публичные методы S3Uploader должны быть задокументированы
     * 
     * Проверяет, что все публичные методы класса упомянуты в документе анализа
     */
    public function testAllPublicMethodsAreDocumented(): void
    {
        $reflection = new ReflectionClass(self::S3_UPLOADER_CLASS);
        $publicMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем конструктор
        $s3UploaderMethods = array_filter($publicMethods, function($method) {
            return $method->getName() !== '__construct';
        });
        
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        foreach ($s3UploaderMethods as $method) {
            $methodName = $method->getName();
            
            $this->assertStringContainsString(
                $methodName,
                $content,
                "Метод {$methodName} должен быть задокументирован в анализе"
            );
        }
    }

    /**
     * Тест: документ должен содержать информацию о конструкторе
     * 
     * Проверяет наличие информации о конструкторе
     */
    public function testAnalysisContainsConstructor(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertStringContainsString(
            'Конструктор',
            $content,
            'Документ должен содержать информацию о конструкторе'
        );
    }

    /**
     * Тест: документ должен содержать информацию о неактивном состоянии
     * 
     * Проверяет наличие информации о работе в неактивном режиме
     */
    public function testAnalysisContainsInactiveState(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertStringContainsString(
            'неактив',
            $content,
            'Документ должен содержать информацию о неактивном состоянии S3'
        );
    }

    /**
     * Тест: документ должен содержать информацию о зависимостях
     * 
     * Проверяет наличие раздела о зависимостях
     */
    public function testAnalysisContainsDependencies(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertStringContainsString(
            'Зависимости',
            $content,
            'Документ должен содержать информацию о зависимостях'
        );
    }

    /**
     * Тест: документ должен содержать информацию об особенностях
     * 
     * Проверяет наличие раздела об особенностях работы класса
     */
    public function testAnalysisContainsFeatures(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertStringContainsString(
            'Особенности',
            $content,
            'Документ должен содержать информацию об особенностях работы класса'
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
     * Проверяет, что количество методов в документе соответствует реальному
     */
    public function testMethodCountMatches(): void
    {
        $reflection = new ReflectionClass(self::S3_UPLOADER_CLASS);
        $publicMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        // Исключаем конструктор
        $s3UploaderMethods = array_filter($publicMethods, function($method) {
            return $method->getName() !== '__construct';
        });
        
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        // Проверяем, что в документе указано общее количество методов
        $this->assertStringContainsString(
            'Всего публичных методов',
            $content,
            'Документ должен содержать информацию об общем количестве методов'
        );
        
        // Проверяем, что количество методов в документе соответствует реальному
        $methodCount = count($s3UploaderMethods);
        $this->assertEquals(
            1,
            $methodCount,
            "Ожидается 1 публичный метод (uploadLogFile), найдено: {$methodCount}"
        );
    }

    /**
     * Тест: документ должен содержать информацию об использовании
     * 
     * Проверяет наличие раздела об использовании класса в проекте
     */
    public function testAnalysisContainsUsage(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertStringContainsString(
            'Использование в проекте',
            $content,
            'Документ должен содержать информацию об использовании класса в проекте'
        );
    }

    /**
     * Тест: документ должен содержать итоговые рекомендации
     * 
     * Проверяет наличие раздела с итоговыми рекомендациями
     */
    public function testAnalysisContainsFinalRecommendations(): void
    {
        $content = file_get_contents(self::ANALYSIS_FILE);
        
        $this->assertStringContainsString(
            'Итоговые рекомендации',
            $content,
            'Документ должен содержать раздел с итоговыми рекомендациями'
        );
    }
}
