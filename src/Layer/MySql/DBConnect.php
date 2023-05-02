<?php
namespace Brizy\layer\MySql;

use Brizy\core\Config;

class DBConnect
{
    private $mySqlConnect;

    function __construct()
    {   
        $this->connect();  
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

    private function connect()
    {

        $this->mySqlConnect = mysqli_connect(Config::$dbLocal, Config::$dbUser, Config::$dbPass, Config::$dbName);
        if(!$this->mySqlConnect)
        {
            die();
        }
    }
}