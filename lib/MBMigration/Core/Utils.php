<?php

namespace MBMigration\Core;

use Exception;
use MBMigration\Builder\VariableCache;

class Utils
{
    private static $projectID;
    private static $cache;

    public function __construct(VariableCache $cache = null)
    {
        self::$projectID = $cache->get('projectId_Brizy');
    }

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

    public static function init(VariableCache $cache = null): void
    {
        self::$cache = $cache;
    }

    public static function strReplace(string $block, $replace, $toReplace): string
    {
        return str_replace($replace, $toReplace, $block);
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
        $typeMessageArray = ["DEBUG", "INFO", "WARNING", "CRITICAL", "PROCESS", "ERROR", "SUCCESSFULLY", "ErrorDump"];
        $project_id = '';
        $dirToDumpLog = null;
        if(isset(self::$cache))
        {
            $project_id = "[UMID: " . self::$cache->get('migrationID') . "] ";
            $dirToDumpLog = self::$cache->get('log', 'ProjectFolders');
        }
        $line = '';

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
        $strlog = "[" . date('Y-m-d H:i:s') . "] " . $project_id . "[" . $typeMessageArray[$param['type']] . "]" . $line . ": [" . $param['callFunction'] . "] " . $message . "\n";

        $prefix = date("Y-m-d");

        self::createDirectoryIfNeeded(Config::$pathLogFile);
        $dirToLog = Utils::strReplace(Config::$pathLogFile, '{{PREFIX}}', $prefix);

        if ($dirToDumpLog !== null){
            file_put_contents($dirToDumpLog . 'process.log', $strlog, FILE_APPEND);
        }

        file_put_contents($dirToLog, $strlog, FILE_APPEND);

        if($param['type'] == 0 or $param['type'] == 2 or $param['type'] == 3 or $param['type'] == 5) {
            $dirToLog = self::$cache->get('log') . 'error.log';
            file_put_contents($dirToLog, $strlog, FILE_APPEND);
        }
    }
}