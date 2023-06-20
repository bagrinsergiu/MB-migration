<?php

namespace MBMigration\Core;

class Config
{
    public static $debugMode;

    public static $endPointApi;
    public static $endPointVersion;

    public static $urlAPI;
    public static $urlProjectAPI;
    public static $urlGetApiToken;
    public static $urlGraphqlAPI;

    public static $pathTmp;
    public static $pathLogFile;
    public static $devToken;

    public static $DBConnection;
    public static $configPostgreSQL;
    public static $configMySQL;

    public static $nameMigration; // this is the name with which Workspaces is created, the same will be written by the same name for migration and work with projects

    private static $cloud_host;

    public function __construct($cloud_host, $path, $token)
    {
        self::$debugMode        = true;

        self::$DBConnection     = 'postgresql'; // mysql|postgresql

        self::$nameMigration    = 'Migration';
        self::$endPointVersion  = '/2.0';

        self::$cloud_host       = $cloud_host;
        self::$devToken         = $token;

        self::$urlAPI           = self::$cloud_host . '/api';
        self::$urlProjectAPI    = self::$cloud_host . '/projects/{project}';
        self::$urlGetApiToken   = self::$cloud_host . '/api/projects/{project}/token';
        self::$urlGraphqlAPI    = self::$cloud_host . '/graphql/{ProjectId}';

        self::$pathTmp          = $path . '/mb_tmp/';
        self::$pathLogFile      = $path . '/mb_log/{{PREFIX}}.log';

        self::$endPointApi      = [
            'team_members'  => '/team_members',
            'menus/create'  => '/menus/create',
            'workspaces'    => '/workspaces',
            'projects'      => '/projects',
            'users'         => '/users',
            'pages'         => '/pages',
            'media'         => '/media',
            'fonts'         => '/fonts',
            'menu'          => '/menus'
        ];

        self::$configPostgreSQL = [
            'dbHost' => "localhost",
            'dbPort' => 50000,
            'dbName' => 'api_production',
            'dbUser' => 'brizy_contractor',
            'dbPass' => 'Lg$8AON5^Dk9JLBR2023iUu'
        ];

        self::$configMySQL      = [
            'dbLocal' => '127.0.0.1',
            'dbName' => 'test',
            'dbUser' => 'root',
            'dbPass' => ''
        ];
    }

    public static function configPostgreSQL(): array
    {
        return [
            'dbHost' => 'localhost',
            'dbPort' => 50000,
            'dbName' => 'api_production',
            'dbUser' => 'brizy_contractor',
            'dbPass' => 'Lg$8AON5^Dk9JLBR2023iUu'
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