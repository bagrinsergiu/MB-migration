<?php

namespace MBMigration\Core;

use Exception;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

/**
 * Logger класс для логирования событий миграции
 * 
 * Наследуется от Monolog\Logger и предоставляет функциональность логирования.
 * Поддерживает создание через конструктор (рекомендуется) или через статические методы
 * для обратной совместимости.
 * 
 * @package MBMigration\Core
 */
class Logger extends \Monolog\Logger
{
    /**
     * @var LoggerInterface|null Глобальный экземпляр для обратной совместимости (deprecated)
     * @deprecated Используйте Dependency Injection вместо глобального экземпляра
     */
    private static ?LoggerInterface $instance = null;

    /**
     * Создает новый экземпляр Logger
     * 
     * Рекомендуемый способ создания Logger - через конструктор с последующей инжекцией.
     * 
     * @param string $name Имя логгера
     * @param string|null $logLevel Уровень логирования (debug, info, warning, error, critical)
     * @param string|null $path Путь к файлу лога
     * @throws Exception Если не удалось создать handler для лога
     */
    public function __construct(string $name, ?string $logLevel = null, ?string $path = null)
    {
        parent::__construct($name);
        
        if ($path !== null && $logLevel !== null) {
            $this->pushHandler(new StreamHandler($path, $logLevel));
        }
    }

    /**
     * Инициализирует Logger (для обратной совместимости)
     * 
     * @deprecated Используйте конструктор или LoggerFactory вместо этого метода
     * Этот метод оставлен для обратной совместимости, но будет удален в будущем
     * 
     * @param string $name Имя логгера
     * @param string|null $logLevel Уровень логирования
     * @param string|null $path Путь к файлу лога
     * @return LoggerInterface Созданный экземпляр Logger
     */
    public static function initialize(string $name, ?string $logLevel = null, ?string $path = null): LoggerInterface
    {
        $logger = new self($name, $logLevel, $path);
        self::$instance = $logger;
        return $logger;
    }

    /**
     * Получить глобальный экземпляр Logger (для обратной совместимости)
     * 
     * @deprecated Используйте Dependency Injection вместо этого метода
     * Этот метод оставлен для обратной совместимости, но будет удален в будущем
     * 
     * @return LoggerInterface Глобальный экземпляр Logger
     * @throws Exception Если Logger не был инициализирован
     */
    public static function instance(): LoggerInterface
    {
        if (self::$instance === null) {
            throw new Exception('Please initialize logger first using Logger::initialize() or use Dependency Injection.');
        }

        return self::$instance;
    }

    /**
     * Проверить, инициализирован ли глобальный экземпляр Logger
     * 
     * @deprecated Этот метод больше не рекомендуется, так как Logger должен инжектироваться
     * 
     * @return bool True если Logger инициализирован, false в противном случае
     */
    public static function isInitialized(): bool
    {
        return self::$instance !== null;
    }
}
