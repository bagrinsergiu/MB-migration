<?php

namespace Brizy\core;

class Config
{


    public static bool $debugMode = true;
    public static string $DBconnection = 'postgresql';   //  mysql|postgresql

    public static string $brizyClientId = '3onlcdgeeh0k8s4s4wkccwo8kwwo4g0g';
    public static string $brizyClientSecret = '4ock4cos8wsowskw4c8cs4wkcskwkow0';


    public static string $nameMigration = "Migration"; // this is the name with which Workspaces is created, the same will be written by the same name for migration and work with projects
    public static string $urlAPI = "https://beta1.brizy.cloud/api";

    public static string $urlProjectAPI = "https://beta1.brizy.cloud/projects/{project}";
    public static string $devToken = 'ZDRmZjIxMzc4M2Y0YmMzZjg5ZmE5YmE4OTUyOTVjMzNkZmFhNmRlZTMwNjliNzIwODhlM2I0MmEwNTlkNGIwMA';

    public static string $urlGetApiToken = 'https://beta1.brizy.cloud/api/projects/{project}/token';

    public static string $urlGraphqlAPI = 'https://beta1.brizy.cloud/graphql/{ProjectId}';

    public static string $endPointVersion = '/2.0';
    public static array $endPointApi = [
        'workspaces' => '/workspaces',
        'projects' => '/projects',
        'team_members' => '/team_members',
        'users' => '/users',
        'pages' => '/pages',
        'menus/create' => '/menus/create',
        'media' => '/media',
        'menu' => '/menus',
        'fonts' => '/fonts'
    ];

    public static string $authenticateParametr = "client_id={client_id}&client_secret={client_secret}&grant_type=user_client_credentials&scope=user";


    public static string $pathLayoutData = __DIR__ . '/../../Layout/{theme}/{page}/data.json';
    public static string $pathLayoutResurce = __DIR__ . '/../Layout/{theme}/file';

    public static string $pathLogFile = __DIR__ . "/../../log/{{PREFIX}}.log";
    public static string $pathMedia = __DIR__ . "/../../tmp/media/";
    public static string $pathTmp = __DIR__ . "/../../tmp/";

    public static $themes = array(
        "Anthem" => "Anthem",
        "August" => "August",
        "Aurora" => "Aurora",
        "Bloom" => "Bloom",
        "Boulevard" => "Boulevard",
        "Dusk" => "Dusk",
        "Ember" => "Ember",
        "Hope" => "Hope",
        "Majesty" => "Majesty",
        "Serene" => "Serene",
        "Solstice" => "Solstice",
        "Tradition" => "Tradition",
        "Voyage" => "Voyage",
        "Zion" => "Zion"
    );

    public static $idThemes = array(
        "Anthem" => 569,
        "August" => 563,
        "Aurora" => 570,
        "Bloom" => 562,
        "Boulevard" => 565,
        "Dusk" => 568,
        "Ember" => 567,
        "Hope" => 559,
        "Majesty" => 561,
        "Serene" => 576,
        "Solstice" => 564,
        "Tradition" => 566,
        "Voyage" => 560,
        "Zion" => 571
    );

    public static function configPostgreSQL(): array
    {
        return [
            'sshHost' => '54.149.121.133',
            'sshPort' => '22',
            'sshUser' => 'brizy_contractor',
            'sshPrivateKeyPath' => __DIR__ . '\ssh\private_key.pem',
            'dbHost' => "localhost",
            'dbPort' => 50000,
            'dbName' => 'api_production',
            'dbUser' => 'brizy_contractor',
            'dbPassword' => "Lg$8AON5^Dk9JLBR2023iUu"
        ];
    }
    public static function configMySQL(): array
    {
        return [
            'dbLocal' => '127.0.0.1',
            'dbName' => 'test',
            'dbUser' => 'root',
            'dbPass' => ''
        ];
    }
}