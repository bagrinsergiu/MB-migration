<?php

namespace MBMigration\Core;

class Loger
{
    /**
     * @var string
     */
    private static $logFileName;
    /**
     * @var string
     */
    private static $logDirectory;

    public static function init($logDirectory) {
        self::$logDirectory = rtrim($logDirectory, '/');
        self::createLogFile();
    }

    private static function createLogFile() {
        $currentDate = date('Y-m-d');
        self::$logFileName = self::$logDirectory . '/' . $currentDate . '.log';
    }

    public static function add($message) {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] $message\n";
        file_put_contents(self::$logFileName, $logMessage, FILE_APPEND);
    }
}