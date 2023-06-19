<?php

use Brizy\Layer\DataSource\DBConnector;

require_once(__DIR__ . '/../../src/Core/Utils.php');
require_once(__DIR__ . '/../../src/Core/Config.php');
require_once(__DIR__ . '/../../src/Layer/DataSource/DBConnector.php');
require_once(__DIR__ . '/../../src/Layer/DataSource/driver/PostgresSQL.php');
require_once(__DIR__ . '/FontDownloader.php');

$db = new DBConnector();

$fontsList = $db->request("SELECT * FROM fonts WHERE source notnull ORDER BY id");
$fonts = [];

foreach ($fontsList as $font)
{
    $fonts[$font['name']]['settings'] = [
        'id' => $font['id'],
        'name' => $font['name'],
        'display_name' => $font['display_name'],
        'family'=> $font['family']
    ];
    echo "download new Font name: " . $font['display_name'] . PHP_EOL;
    $src = $font['source'];
    $fd = new FontDownloader($src, $font['name']);
    $fonts[$font['name']]['fonts'] = $fd->downloadFonts();
}

$filename = __DIR__ . '/fonts.json';
$json = json_encode($fonts);
file_put_contents($filename, $json);
echo "Success Download fonts" . PHP_EOL;