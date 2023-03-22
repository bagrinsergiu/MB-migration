<?php
namespace Brizy;

use Brizy\Config;

class DBConnect
{
    public static $mySqlConnect;
    
    function __construct()
    {   
        $this->connect();   
    }

    public function request($query)
    {
        if($result = mysqli_query(self::$mySqlConnect, $query))
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
        if($resultArray = mysqli_query(self::$mySqlConnect, $query))
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
        if(!is_object(self::$mySqlConnect))
        {
            $conn = $this->connect();
        }
    }

    public function connect()
    {
        self::$mySqlConnect = mysqli_connect(Config::$dbLocal, Config::$dbUser, Config::$dbPass, Config::$dbName);
        if(!self::$mySqlConnect)
        {
            die();
        }
    }
}