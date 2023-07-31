<?php

use MBMigration\Core\Config;
use MBMigration\Layer\DataSource\DBConnector;

require_once(__DIR__ . '/../../Core/Utils.php');
require_once(__DIR__ . '/../../Core/Config.php');
require_once(__DIR__ . '/../../Layer/DataSource/DBConnector.php');
require_once(__DIR__ . '/../../Layer/DataSource/driver/PostgresSQL.php');
require_once(__DIR__ . '/FontDownloader.php');
require_once(__DIR__ . '/DowenLoad_v2.php');
require_once(__DIR__ . '/font_d_v3.php');


$db = [
    'dbType' => "postgresql",
    'dbHost' => "localhost",
    'dbPort' => 50000,
    'dbName' => 'api_production',
    'dbUser' => 'brizy_contractor',
    'dbPass' => 'Lg$8AON5^Dk9JLBR2023iUu'
];

$settings = [

    'devMode' => false
];

try {
    $config = new Config(
        'https://beta1.brizy.cloud',
        __DIR__,
        'ZDRmZjIxMzc4M2Y0YmMzZjg5ZmE5YmE4OTUyOTVjMzNkZmFhNmRlZTMwNjliNzIwODhlM2I0MmEwNTlkNGIwMA',
        $db,
        $settings);
} catch (Exception $e) {
    echo "Message: " . $e->getMessage();
}

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
    //$fd = new FontDownloader($src, $font['name']);
    $fd = new FontDownloader_v3($src);
    //$fd = new FontDownloader_v2($src);
    //$fonts[$font['name']]['fonts'] = $fd->downloadFonts($font['name']);
    //$fonts[$font['name']]['fonts'] = $fd->downloadFonts();
    $fonts[$font['name']]['fonts'] = $fd->downloadFonts($font['name']);
    //$fonts[$font['name']]['fonts'] = downloadFontsFromCSS($src);
}

$filename = __DIR__ . '/fonts_v3.1.json';
$json = json_encode($fonts);
file_put_contents($filename, $json);
echo "Success Download fonts" . PHP_EOL;


function downloadFont($url, $outputDir)
{
    $fileName = basename($url);
    $outputPath = $outputDir . '/' . $fileName;

    if (file_put_contents($outputPath, file_get_contents($url))) {
        echo "Downloaded font: $fileName\n";
    } else {
        echo "Failed to download font: $fileName\n";
    }
}

function parseCSS($css): array
{
    $fonts = array();

    preg_match_all('/@font-face\s*{([^}]+)}/', $css, $matches);
    foreach ($matches[1] as $match) {
        $font = array();

        preg_match('/font-family:\s*\'(.*?)\';/', $match, $familyMatch);
        if (isset($familyMatch[1])) {
            $font['family'] = $familyMatch[1];
        }

        preg_match('/src:\s*url\(\'(.*?)\'\);/', $match, $srcMatch);
        if (isset($srcMatch[1])) {
            $font['src'] = $srcMatch[1];
        }

        preg_match('/font-weight:\s*(\d+);/', $match, $weightMatch);
        if (isset($weightMatch[1])) {
            $font['weight'] = $weightMatch[1];
        }

        preg_match('/font-style:\s*(\w+);/', $match, $styleMatch);
        if (isset($styleMatch[1])) {
            $font['style'] = $styleMatch[1];
        }

        if (!empty($font)) {
            $fonts[] = $font;
        }
    }

    return $fonts;
}

function downloadFontsFromCSS($cssUrl)
{
    $cssContent = file_get_contents($cssUrl);

    if ($cssContent === false) {
        echo "Failed to fetch CSS file: $cssUrl\n";
        return;
    }

    $fonts = parseCSS($cssContent);

    if (empty($fonts)) {
        echo "No fonts found in the CSS file: $cssUrl\n";
        return;
    }

    foreach ($fonts as $font) {
        $familyDir = __DIR__ . '/fonts_D/' . $font['family'];
        $fontDir = $familyDir . '/' . $font['weight'] . '/' . $font['style'];

        if (!is_dir($fontDir) && !mkdir($fontDir, 0777, true)) {
            echo "Failed to create font directory: $fontDir\n";
            continue;
        }
        downloadFont($font['src'], $fontDir);
    }
}

