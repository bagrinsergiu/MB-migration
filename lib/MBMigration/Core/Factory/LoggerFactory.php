<?php

namespace MBMigration\Core\Factory;

use MBMigration\Core\Logger;
use Psr\Log\LoggerInterface;

/**
 * Фабрика для создания экземпляров Logger
 * 
 * Предоставляет удобный способ создания Logger с правильной конфигурацией.
 * Используется для централизованного создания Logger вместо прямого использования конструктора.
 * 
 * @package MBMigration\Core\Factory
 */
class LoggerFactory
{
    /**
     * Создает новый экземпляр Logger
     * 
     * @param string $name Имя логгера (например, "ApplicationBootstrapper", "MigrationPlatform")
     * @param string $logLevel Уровень логирования (debug, info, warning, error, critical)
     * @param string $path Путь к файлу лога
     * @return LoggerInterface Созданный экземпляр Logger
     * @throws \Exception Если не удалось создать handler для лога
     */
    public static function create(string $name, string $logLevel, string $path): LoggerInterface
    {
        return new Logger($name, $logLevel, $path);
    }

    /**
     * Создает Logger с настройками по умолчанию
     * 
     * @param string $name Имя логгера
     * @param string|null $logLevel Уровень логирования (по умолчанию 'info')
     * @param string|null $path Путь к файлу лога (по умолчанию './app.log')
     * @return LoggerInterface Созданный экземпляр Logger
     */
    public static function createDefault(string $name, ?string $logLevel = 'info', ?string $path = './app.log'): LoggerInterface
    {
        return new Logger($name, $logLevel ?? 'info', $path ?? './app.log');
    }
}
