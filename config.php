<?php

namespace Brizy;

class Config{

    public static $debugMode  = FALSE;
    
    public static $dbLocal  = 'localhost';
    public static $dbName   = 'test';
    public static $dbUser   = 'root';
    public static $dbPass   = '';

    public static $pathLayoutData    = __DIR__ . '/layout/{theme}/{page}/data.json';
    public static $pathLayoutResurce = __DIR__ . '/../layout/{theme}/file';

    public static $pathLogFile = __DIR__ . '/../LOG/1.log';

    public static $themes = array(
        "Anthem"    => "Anthem",
        "August"    => "August",
        "Aurora"    => "Aurora",
        "Bloom"     => "Bloom",
        "Boulevard" => "Boulevard",
        "Dusk"      => "Dusk",
        "Ember"     => "Ember",
        "Hope"      => "Hope",
        "Majesty"   => "Majesty",
        "Serene"    => "Serene",
        "Solstice"  => "Solstice",
        "Tradition" => "Tradition",
        "Voyage"    => "Voyage",
        "Zion"      => "Zion"
    );

    public static $idThemes = array(
        "Anthem"    => 569,
        "August"    => 563,
        "Aurora"    => 570,
        "Bloom"     => 562,
        "Boulevard" => 565,
        "Dusk"      => 568,
        "Ember"     => 567,
        "Hope"      => 559,
        "Majesty"   => 561,
        "Serene"    => 576,
        "Solstice"  => 564,
        "Tradition" => 566,
        "Voyage"    => 560,
        "Zion"      => 571
    );
    
}