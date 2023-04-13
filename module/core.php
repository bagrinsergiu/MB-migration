<?php

namespace Brizy;

use GuzzleHttp\Client;

require_once(__DIR__. '/helper.php');

/**
 *  include composer
 */
require_once __DIR__ . '/../vendor/autoload.php';

Helper::resourcesInitialization(__DIR__);

$config     = new Config();
//$db         = new DBConnect();
$helper     = new Helper();
$brizyAPI   = new brizyAPI();
$httpClient = new Client();
$graphLayer = new layerGraphQL();