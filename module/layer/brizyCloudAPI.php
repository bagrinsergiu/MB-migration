<?php
namespace Brizy;

use Brizy\Config;

class cloudAPI
{
    private static $config; 

    function __construct()
    {
        $this->config = Config::$idThemes;
    }

}