<?php

namespace Brizy;

require_once(__DIR__. '/helper.php');

/**
 *  include composer
 */
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/layer/brizyAPI.php';
Helper::resourcesInitialization(__DIR__);

$config     = new Config();
$db         = new DBConnect();
$helper     = new Helper();
$brizyAPI   = new brizyAPI();
$graphLayer = new layerGraphQL();