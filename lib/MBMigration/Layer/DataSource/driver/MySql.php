<?php
namespace MBMigration\Layer\DataSource\driver;

use MBMigration\Core\Config;
use MBMigration\Core\Utils;

class MySql
{
    private $mySqlConnect;

    function __construct()
    {
        Utils::log('Initialization', 4, 'MySql');
        $this->connect(Config::$configMySQL);
    }

    public function request($query)
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

    public function requestArray($query)
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

    private function connect($config)
    {
        $this->mySqlConnect = mysqli_connect($config['dbHost'], $config['dbUser'], $config['dbPass'], $config['dbName']);
        if(!$this->mySqlConnect)
        {
            die();
        }
    }
}