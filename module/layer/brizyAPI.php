<?php
namespace Brizy;

use Brizy\Config;
use Brizy\Helper;

class brizyAPI{

    function __construct()
    {
        
    }

    public function createUser()
    {
        /**
         * this is where the user creation magic happens Brizy
         * 
         */
        return [Config::$brizyClientId, Config::$brizyClientSecret];
    }

    public function getUserToken()
    {
        $brizyCreateClient = $this->createUser();

        $authenticateParametr = Helper::strReplace(Config::$authenticateParametr, ['{client_id}','{client_secret}'], $brizyCreateClient);
        
        $param = ['slug' => '/token', 'getToken' => $authenticateParametr];

        $resultquery = Helper::curlExec(Config::$urlAPI, $param);

        return json_decode($resultquery, true);
    }

    public function workspaces(){
        
    }

    public function createUrlAPI($endPoint)
    {
        return \Brizy\Config::$urlAPI . Config::$endPointVersion . Config::$endPointApi[$endPoint];
    }

}