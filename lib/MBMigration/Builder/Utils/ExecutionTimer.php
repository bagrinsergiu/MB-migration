<?php

namespace MBMigration\Builder\Utils;

class ExecutionTimer
{
    private static $startTime;

    public static function start() {
        self::$startTime = microtime(true);
    }

    public static function stop() {
        $endTime = microtime(true);
        return $endTime - self::$startTime;
    }
}