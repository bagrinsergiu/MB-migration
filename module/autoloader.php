<?php

namespace Brizy;

require_once(__DIR__. '/../config.php');

require_once(__DIR__. '/db.php');

require_once(__DIR__. '/class.php');

require_once(__DIR__. '/constructor.php');

require_once(__DIR__. '/tool.php');

require_once(__DIR__. '/../class/Zion.php');

$tool   = new Tool();
$config = new Config();
$db     = new DBConnect();