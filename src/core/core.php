<?php

use Brizy\core\ErrorDump;
use Brizy\core\Utils;

require_once __DIR__ . '/Utils.php';
require_once __DIR__ . '/ErrorDump.php';
require_once __DIR__ . '/../../vendor/autoload.php';

new ErrorDump();

Utils::resourcesInitialization(__DIR__.'/../');