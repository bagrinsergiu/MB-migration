<?php

namespace MBMigration\Core;

use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class Logger extends \Monolog\Logger
{
    static private LoggerInterface $instance;

    static public function initialize($level, $path): LoggerInterface
    {
        self::$instance = new self('app');
        self::$instance->pushHandler(new StreamHandler($path, $level));

        return self::$instance;
    }

    static public function instance(): LoggerInterface
    {
        if (!self::$instance) {
            throw new \Exception('Please initialize logger first.');
        }

        return self::$instance;
    }
}