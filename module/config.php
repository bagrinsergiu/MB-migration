<?php

namespace Brizy;

class Config{

    public static $debugMode  = FALSE;
    
    public static $dbLocal  = '127.0.0.1';
    public static $dbName   = 'test';
    public static $dbUser   = 'root';
    public static $dbPass   = '';

    public static $brizyClientId      = '3onlcdgeeh0k8s4s4wkccwo8kwwo4g0g';
    public static $brizyClientSecret  = '4ock4cos8wsowskw4c8cs4wkcskwkow0';



    public static $nameMigration = "Migration"; // this is the name with which Workspaces is created, the same will be written by the same name for migration and work with projects
    public static $urlAPI        = "https://beta1.brizy.cloud/api";
    public static $devToken      = 'ZDRmZjIxMzc4M2Y0YmMzZjg5ZmE5YmE4OTUyOTVjMzNkZmFhNmRlZTMwNjliNzIwODhlM2I0MmEwNTlkNGIwMA';

    public static $urlGetApiToken = 'https://beta1.brizy.cloud/api/projects/{project}/token';
    public static $urlGraphqlAPI = 'https://beta1.brizy.cloud/graphql/{Project.id}/brizy-api';

    public static $endPointVersion = '/2.0';
    public static $endPointApi = [
        'workspaces'    => '/workspaces',
        'projects'      => '/projects',
        'team_members'  => '/team_members',
        'users'         => '/users'
    ];

    public static $authenticateParametr = "client_id={client_id}&client_secret={client_secret}&grant_type=user_client_credentials&scope=user";



    public static $pathLayoutData    = __DIR__ . '/../layout/{theme}/{page}/data.json';
    public static $pathLayoutResurce = __DIR__ . '/../layout/{theme}/file';

    public static $pathLogFile = __DIR__ . '/../log/1.log';

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