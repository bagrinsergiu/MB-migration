<?php

namespace MBMigration\Core;

use ErrorException;
use Exception;
use MBMigration\Builder\VariableCache;

class ErrorDump
{
    private $log_file = 'error_log.txt';
    private $cache;
    private $projectID;
    /**
     * @var ErrorException
     */
    private $errorDetails;

    public function __construct($projectID = null)
    {
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
        $this->projectID = $projectID;
    }

    public function setDate(VariableCache $cache): void
    {
        $this->cache = $cache;
    }

    /**
     * @throws Exception
     */
    public function errorHandler($errno, $errstr, $errfile, $errline) {
        $this->errorDetails = new ErrorException($errstr, 0, $errno, $errfile, $errline);
        $this->createDump();
    }

    /**
     * @throws Exception
     */
    public function exceptionHandler($exception) {
        $this->errorDetails = $exception;
        $this->createDump();
    }

    private function createProjectFolders(): void
    {
        $folds = [
            '/log/dump/'
        ];
        foreach ($folds as $fold) {
            $path = Config::$pathTmp . $this->projectID . $fold;
            $this->createDirectory($path);
        }
    }

    private function createDirectory($directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            Utils::log('Create Directory: ' . $directoryPath, 7, 'createDirectory');
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
        if($this->projectID !== null ){
            $dump_file = Config::$pathTmp;
            $dump_file .= $this->projectID;
            $dump_file .= '/log/dump/';
            $dump_file .= date('Y-m-d_H-i-s');
            $dump_file .= '.log';
        }
        $data = $this->getDump(false);
        $this->writeDump($dump_file, $data);
    }

    public function getDump(bool $inJson = true): array
    {
        $cache = $this->cache ?? [];
        if ($inJson) {
            $dump = [
                'error_message' => $this->errorDetails->getMessage(),
                'error_code' => $this->errorDetails->getCode(),
                'error_file' => $this->errorDetails->getFile(),
                'error_line' => $this->errorDetails->getLine(),
                'error_trace' => $this->errorDetails->getTrace(),
                'cache' => $cache->getCache()
            ];
        } else {
            $dump = [
                'error_message' => $this->errorDetails->getMessage(),
                'error_code' => $this->errorDetails->getCode(),
                'error_file' => $this->errorDetails->getFile(),
                'error_line' => $this->errorDetails->getLine(),
                'error_trace' => $this->errorDetails->getTraceAsString(),
                'cache' => $cache,
                'request_headers' => getallheaders()
            ];
        }
        return $dump;
    }

    private function writeDump($dump_file, $data): void
    {
        $dump_handle = fopen($dump_file, 'w');
        fwrite($dump_handle, print_r($data, true));
        fclose($dump_handle);
        $log_entry = date('Y-m-d H:i:s') . ": Error occurred, dump created in file $dump_file\n";
        error_log($log_entry, 3, $this->log_file);
        Utils::keepItClean();
        Utils::log('FATAL ' . $this->projectID, 7, 'createDump');
        Utils::log('Details: ' . $dump_file, 7, 'createDump');
        Utils::log('', 7, 'END] -= PROCESS =- [END');
        throw new Exception('End process, Dump created ');

    }

}