<?php

namespace Brizy\core;

use Brizy\builder\VariableCache;
use ErrorException;

class ErrorDump
{

    private $log_file = 'error_log.txt';
    /**
     * @var VariableCache
     */
    private VariableCache $cache;
    /**
     * @var mixed|null
     */
    private mixed $projectID;

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
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $error = new ErrorException($errstr, 0, $errno, $errfile, $errline);
        $this->createDump($error);
    }

    public function exceptionHandler($exception) {
        $this->createDump($exception);
    }

    private function createProjectFolders(): void
    {
        $folds = [
            '/log/dump/'
        ];
        foreach ($folds as $fold) {
            $path = __DIR__ . '/../../tmp/' . $this->projectID . $fold;
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

    private function createDump($error): void
    {
        $this->createProjectFolders();
        $dump_file = __DIR__ . '/../../log/error/error_dump_' . date('Y-m-d_H-i-s') . '.txt';
        if($this->projectID !== null ){
            $dump_file = __DIR__;
            $dump_file .= '/../../tmp/';
            $dump_file .= $this->projectID;
            $dump_file .= '/log/dump/';
            $dump_file .= date('Y-m-d_H-i-s');
            $dump_file .= '.txt';
        }

        $cache = $this->cache ?? [];

        $data = [
            'error_message' => $error->getMessage(),
            'error_code' => $error->getCode(),
            'error_file' => $error->getFile(),
            'error_line' => $error->getLine(),
            'error_trace' => $error->getTraceAsString(),
            'cache' => $cache,
            'server_data' => $_SERVER,
            'post_data' => $_POST,
            'get_data' => $_GET,
            //'session_data' => $_SESSION,
            'cookie_data' => $_COOKIE,
            'request_headers' => getallheaders()
        ];

        $dump_handle = fopen($dump_file, 'w');
        fwrite($dump_handle, print_r($data, true));
        fclose($dump_handle);
        $log_entry = date('Y-m-d H:i:s') . ": Error occurred, dump created in file $dump_file\n";
        error_log($log_entry, 3, $this->log_file);
        Utils::log('FATAL ' . $this->projectID, 7, 'createDump');
        Utils::log('Details: ' . $dump_file, 7, 'createDump');
        Utils::log('', 7, 'END] -= PROCESS =- [END');
        exit();
    }
}