<?php

namespace Brizy\Core;

class Config
{
    public static bool $debugMode;
    public static array $endPointApi;
    public static string $endPointVersion;
    public static string $urlAPI;
    public static string $urlProjectAPI;
    public static string $urlGetApiToken;
    public static string $urlGraphqlAPI;
    public static string $pathTmp;
    public static string $pathLogFile;
    public static string $devToken;
    public static string $DBConnection;
    public static string $nameMigration; // this is the name with which Workspaces is created, the same will be written by the same name for migration and work with projects

    private static string $cloud_host;
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

        self::$pathTmp          = $path . '/../../tmp/';
        self::$pathLogFile      = $path . "/../../log/{{PREFIX}}.log";

        self::$endPointApi      = [
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

    }

    public static function configPostgreSQL(): array
    {
        return [
            'dbHost' => "",
            'dbPort' => 50000,
            'dbName' => '',
            'dbUser' => '',
            'dbPass' => ""
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