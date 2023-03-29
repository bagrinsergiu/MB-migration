<?php
namespace Brizy;

use Brizy\Config;

class DBConnect
{
    public $mySqlConnect;
    public $connecttest;
    
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
            $result = mysqli_fetch_array($resultArray);
            return $result;
        }
        else
        {
            echo "DB_error:requestArray";
            return FALSE;
        }
    }

    private function checkConnect()
    {
        $conn = TRUE;
        if(!is_object($this->mySqlConnect))
        {
            $conn = $this->connect();
        }
    }

    public function connect()
    {

        $this->mySqlConnect = mysqli_connect(Config::$dbLocal, Config::$dbUser, Config::$dbPass, Config::$dbName);
        if(!$this->mySqlConnect)
        {
            die();
        }
    }
}