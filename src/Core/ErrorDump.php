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
    private $cache;

    public function __construct()
    {
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
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

    private function createDump($error) {

        $dump_file = __DIR__ . '/../../log/error/error_dump_' . date('Y-m-d_H-i-s') . '.txt';

        $data = [
            'error_message' => $error->getMessage(),
            'error_code' => $error->getCode(),
            'error_file' => $error->getFile(),
            'error_line' => $error->getLine(),
            'error_trace' => $error->getTraceAsString(),
            'cache' => $this->cache,
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
    }
}