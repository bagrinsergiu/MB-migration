<?php

namespace MBMigration\Core;

use Exception;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class Logger extends \Monolog\Logger
{
    static private LoggerInterface $instance;
    /**
     * @var mixed|null
     */
    private static $path;
    /**
     * @var mixed
     */
    private static $logLevel;

    static public function initialize($name, $logLevel = null, $path = null): LoggerInterface
    {
        if(!empty(self::$logLevel)){
            $logLevel = self::$logLevel;
        } else {
            self::$logLevel = $logLevel;
        }

        if(!empty(self::$path)) {
            $path = self::$path;
        } else {
            self::$path = $path;
        }

        self::$instance = new self($name);
        self::$instance->pushHandler(new StreamHandler($path, $logLevel));

        return self::$instance;
    }

    static public function instance(): LoggerInterface
    {
        if (!self::$instance) {
            throw new Exception('Please initialize logger first.');
        }

        return self::$instance;
    }
}
