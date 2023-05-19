<?php
namespace Brizy\Layer\DataSource\driver;

use Brizy\core\Config;
use Brizy\core\Utils;

class MySql
{
    private $mySqlConnect;

    function __construct()
    {
        Utils::log('Initialization', 4, 'MySql');
        $this->connect(Config::configMySQL());
    }

    public function request($query): \mysqli_result|bool
    {
        if($result = mysqli_query($this->mySqlConnect, $query))
        {
            return $result;
        }
        else
        {
            echo "DB_error:request";
            return FALSE;
        }
    }

    public function requestArray($query): bool|array|null
    {
        if($resultArray = mysqli_query($this->mySqlConnect, $query))
        {
            return mysqli_fetch_array($resultArray);
        }
        else
        {
            echo "DB_error:requestArray";
            return FALSE;
        }
    }

    private function connect($config): void
    {
        $this->mySqlConnect = mysqli_connect($config['dbLocal'], $config['dbUser'], $config['dbPass'], $config['dbName']);
        if(!$this->mySqlConnect)
        {
            die();
        }
    }
}