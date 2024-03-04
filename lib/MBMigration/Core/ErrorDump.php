<?php

namespace MBMigration\Core;

use ErrorException;
use Exception;
use MBMigration\Builder\VariableCache;

class ErrorDump
{
    private $log_file = 'error_log.txt';

    private $projectID;
    /**
     * @var ErrorException
     */
    private $errorDetails;
    /**
     * @var true
     */
    private $errorStatus;
    /**
     * @var mixed
     */
    private $projectId;
    /**
     * @var VariableCache
     */
    private $cache;

    /**
     * @var array
     */
    public $errors;

    public function __construct(VariableCache $cache)
    {
        $this->errorStatus = false;
        $this->cache = $cache;
        $this->errors = [];
    }

    public function handleError($severity, $message, $file, $line, $fullError) {
        if(Config::$devMode) {
            echo "Warning: " . $message . " in file " . $file . " on line " . $line . "\n";
        }
        $this->errorStatus = true;
        $this->errors[] = [
            'severity' => $severity,
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'fullError' => json_encode($fullError),
            'details_message' => Utils::$MESSAGES_POOL,
//            'cache' => $this->cache->getCache()
        ];
    }

    /**
     * @throws Exception
     */
    public function handleFatalError() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line'], $error);
        }

    }
    
    /**
     * @throws Exception
     */
    public function errorHandler($errno, $errstr, $errfile, $errline) {
        $this->errorStatus = true;
        $this->errorDetails = new ErrorException($errstr, 0, $errno, $errfile, $errline);
        if(Config::$devMode) {
            $this->createDump();
        } else {
            throw new Exception('error');
        }
    }

    /**
     * @throws Exception
     */
    public function exceptionHandler($exception) {
        $this->errorStatus = true;
        $this->errorDetails = $exception;
        if(Config::$devMode) {
            $this->createDump();
        } else {
            throw new Exception('error');
        }
    }

    private function createProjectFolders(): void
    {
        $folds = [
            '/log/dump/'
        ];
        foreach ($folds as $fold) {
            $path = Config::$pathTmp . $this->projectId . $fold;
            $this->createDirectory($path);
        }
    }

    private function createDirectory($directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            \MBMigration\Core\Logger::instance()->debug('Create Directory: ' . $directoryPath);
            mkdir($directoryPath, 0777, true);
        }
    }

    /**
     * @throws Exception
     */
    public function createDump()
    {
        $this->createProjectFolders();
        $dump_file = Config::$pathTmp . '/log/error/error_dump_' . date('Y-m-d_H-i-s') . '.log';
        if($this->projectId !== null ){
            $dump_file = Config::$pathTmp;
            $dump_file .= $this->projectId;
            $dump_file .= '/log/dump/';
            $dump_file .= date('Y-m-d_H-i-s');
            $dump_file .= '.log';
        }
        $data = $this->getDump(false);
        $this->writeDump($dump_file, $data);
        throw new Exception('error');
    }

    public function getAllErrors(): array
    {
        if(!$this->errorStatus) {
            return $this->getDump();
        }
        return $this->errors;
    }

    public function getDetailsMessage(): array
    {
        $details = [
            'details_message' => Utils::$MESSAGES_POOL,
        ];
        return $details;
    }

    public function getDump(): array
    {
        return [
            'details_message' => Utils::$MESSAGES_POOL,
//            'cache' => $this->cache->getCache()
        ];
    }

    private function writeDump($dump_file, $data): void
    {
        $dump_handle = fopen($dump_file, 'w');
        fwrite($dump_handle, print_r($data, true));
        fclose($dump_handle);
        $log_entry = date('Y-m-d H:i:s') . ": Error occurred, dump created in file $dump_file\n";
        error_log($log_entry, 3, $this->log_file);
        \MBMigration\Core\Logger::instance()->debug('FATAL ' . $this->projectId);
        \MBMigration\Core\Logger::instance()->debug('Details: ' . $dump_file);
        \MBMigration\Core\Logger::instance()->debug('');
    }
}