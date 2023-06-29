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
     * @throws Exception
     */
    public function errorHandler($errno, $errstr, $errfile, $errline) {
        $this->errorStatus = true;
        $this->errorDetails = new ErrorException($errstr, 0, $errno, $errfile, $errline);
        if(Config::$devMode){ $this->createDump();}
        throw new Exception('error');
    }

    /**
     * @throws Exception
     */
    public function exceptionHandler($exception) {
        $this->errorStatus = true;
        $this->errorDetails = $exception;
        if(Config::$devMode){ $this->createDump();}
        throw new Exception('error');
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

    public function getDump(bool $inJson = true): array
    {
        if($this->errorStatus) {
            if ($inJson) {
                $dump = [
                    'error_message' => $this->errorDetails->getMessage(),
                    'error_code' => $this->errorDetails->getCode(),
                    'error_file' => $this->errorDetails->getFile(),
                    'error_line' => $this->errorDetails->getLine(),
                    'error_trace' => $this->errorDetails->getTrace(),
                    'details_message' => Utils::$ERROR_MESSAGE,
                    'cache' => $this->cache->getCache()
                ];
            } else {
                $dump = [
                    'error_message' => $this->errorDetails->getMessage(),
                    'error_code' => $this->errorDetails->getCode(),
                    'error_file' => $this->errorDetails->getFile(),
                    'error_line' => $this->errorDetails->getLine(),
                    'error_trace' => $this->errorDetails->getTraceAsString(),
                    'details_message' => Utils::$ERROR_MESSAGE,
                    'cache' => $this->cache->getCache()
                ];
            }
        } else {
            $dump = [
                'details_message' => Utils::$ERROR_MESSAGE,
                'cache' => $this->cache->getCache()
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
        Utils::log('FATAL ' . $this->projectId, 7, 'createDump');
        Utils::log('Details: ' . $dump_file, 7, 'createDump');
        Utils::log('', 7, 'END] -= PROCESS =- [END');
    }
}