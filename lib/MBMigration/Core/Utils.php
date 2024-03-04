<?php

namespace MBMigration\Core;

use Exception;
use MBMigration\Builder\VariableCache;

class Utils
{
    public static $MESSAGES_POOL = [];
    private static $projectID;

    /**
     * @var VariableCache
     */
    protected static $cache;

    /**
     * @throws Exception
     */
    public function check($value, $message)
    {
        if(empty($value)){
            throw new Exception($message);
        }
        return $value;
    }

    public static function MESSAGES_POOL($message, $key = '', $section = '' )
    {
        if (empty($section)) {
            $section = 'MAIN_MESSAGE';
        }

        if (!array_key_exists($section, Utils::$MESSAGES_POOL)) {
            Utils::$MESSAGES_POOL[$section] = [];
        }

        $messageArray = &Utils::$MESSAGES_POOL[$section];

        if (!is_array($messageArray)) {
            $messageArray = [];
        }

        if (empty($key)) {
            array_unshift($messageArray, $message);
        } else {
            if (array_key_exists($key, $messageArray) && is_array($messageArray[$key])) {
                array_unshift($messageArray[$key], $message);
            } else {
                $messageArray[$key] = [$message];
            }
        }
        Utils::$MESSAGES_POOL[$section] = $messageArray;
    }

    public static function findKeyPath($array, $searchKey, $path = '', &$result = [], &$i = 0)
    {
        foreach ($array as $key => $value) {
            $currentPath = $path . '[' . $key . ']';

            if ($key === $searchKey) {
                $result[] = [
                    'position' => $i,
                    'path' => $currentPath,
                    'value' => $value
                ];
                $i++;
            }

            if (is_array($value)) {
                Utils::findKeyPath($value, $searchKey, $currentPath, $result, $i);
            }

        }
        return $result;
    }

    public static function init(VariableCache $cache): void
    {
        self::$cache = $cache;
        self::$projectID = $cache->get('projectId_Brizy');
    }

    public static function strReplace(string $block, $replace, $toReplace): string
    {
        return str_replace($replace, $toReplace, $block);
    }

    public static function keepItClean(){
        $deleteDir = ['page','media'];
        $deleteFolders = [];

        $folders = self::$cache->get('ProjectFolders');
        if (!empty($folders)) {
            foreach ($deleteDir as $name) {
                if (array_key_exists($name, $folders)) {
                    $deleteFolders[] = $folders[$name];
                }
            }
            foreach ($deleteFolders as $directory) {
                self::removeDir($directory);
            }
            \MBMigration\Core\Logger::instance()->info('Derictories removed success');
        }
    }

    private static function removeDir($directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $files = array_diff(scandir($directory), array('.', '..'));

        foreach ($files as $file) {
            $path = $directory . '/' . $file;

            if (is_dir($path)) {
                self::removeDir($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($directory);
    }

    public static function log(string $messageText, int $type = 1, string $nameFunction = ''): void
    {
        $param = [
            'message' => $messageText,
            'type' => $type,
            'callFunction' => $nameFunction
        ];

        if (isset(self::$projectID)) {
            $param['project_id'] = self::$projectID;
        }

        if (Config::$debugMode) {
            self::writeLogToFile($param);
        }

        if (Config::$debugMode === false && $type > 1) {
            self::writeLogToFile($param);
        }

    }

    private static function createDirectoryIfNeeded($filePath) {
        $directory = dirname($filePath);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    private static function writeLogToFile(array $param): void
    {
        $line = '';
        $project_id = '';
        $dirToDumpLog = null;
        $typeMessageArray = ["DEBUG", "INFO", "WARNING", "CRITICAL", "PROCESS", "ERROR", "SUCCESSFULLY", "ErrorDump"];

        if (isset(self::$cache)) {
            $project_id = "[UMID: " . self::$cache->get('migrationID') . "] ";
            $dirToDumpLog = self::$cache->get('log', 'ProjectFolders');
        }

        if (is_array($param['message'])) {
            $message = json_encode($param['message']);
        } else {
            $message = $param['message'];
        }

        if ($param['type'] == 0 or $param['type'] == 2 or $param['type'] == 3 or $param['type'] == 5) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
            $caller = $backtrace[1];
            $line = ' (' . basename($caller['file']) . ':' . $caller['line'] . ') ';
        }

        $strLog = "[" . date('Y-m-d H:i:s') . "] " . $project_id . "[" . $typeMessageArray[$param['type']] . "]" . $line . ": [" . $param['callFunction'] . "] " . $message . "\n";

        $prefix = date("Y-m-d");

        //self::createDirectoryIfNeeded(Config::$pathLogFile);
        $dirToLog = Config::$pathLogFile;//Utils::strReplace(Config::$pathLogFile, '{{PREFIX}}', $prefix);

        if ($dirToDumpLog !== null) {
            file_put_contents($dirToDumpLog . 'process.log', $strLog, FILE_APPEND);
        }

        file_put_contents($dirToLog, $strLog, FILE_APPEND);
        if(!is_null(self::$cache)) {
            if($param['type'] == 0 or $param['type'] == 2 or $param['type'] == 3 or $param['type'] == 5) {
                $dirToLog = self::$cache->get('log') . 'error.log';
                file_put_contents($dirToLog, $strLog, FILE_APPEND);
            }
        }
    }

    public static function getNameHash($data = ''): string
    {
        $to_hash = self::generateCharID(32) . $data;
        $newHash = hash('sha256', $to_hash);
        return substr($newHash, 0, 32);
    }

    public static function generateCharID($length): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

}