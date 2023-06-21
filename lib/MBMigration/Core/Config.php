<?php

namespace MBMigration\Core;

use Exception;

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

    /**
     * @throws Exception
     */
    public function __construct($cloud_host, $path, $token, $DBConnection)
    {
        $this->checkDBConnection($DBConnection);

        self::$debugMode        = true;

        self::$DBConnection     = $DBConnection['dbType']; // mysql|postgresql

        self::$nameMigration    = 'Migration';
        self::$endPointVersion  = '/2.0';

        self::$cloud_host       = $this->checkURL($cloud_host);
        self::$devToken         = $token;

        self::$urlAPI           = self::$cloud_host . '/api';
        self::$urlProjectAPI    = self::$cloud_host . '/projects/{project}';
        self::$urlGetApiToken   = self::$cloud_host . '/api/projects/{project}/token';
        self::$urlGraphqlAPI    = self::$cloud_host . '/graphql/{ProjectId}';

        self::$pathTmp          = $this->checkPath($path) . '/mb_tmp/';
        self::$pathLogFile      = $this->checkPath($path) . '/mb_log/{{PREFIX}}.log';

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
            'dbHost' => $DBConnection['dbHost'],
            'dbPort' => $DBConnection['dbPort'],
            'dbName' => $DBConnection['dbName'],
            'dbUser' => $DBConnection['dbUser'],
            'dbPass' => $DBConnection['dbPass']
        ];

        self::$configMySQL      = [
            'dbHost' => $DBConnection['dbHost'],
            'dbName' => $DBConnection['dbName'],
            'dbUser' => $DBConnection['dbUser'],
            'dbPass' => $DBConnection['dbPass']
        ];
    }

    private function checkPath($path): string
    {
        return is_dir($path) ? $path : sys_get_temp_dir();
    }

    /**
     * @throws Exception
     */
    private function checkDBConnection($confConnection): void
    {
        $requiredFields = ['dbType', 'dbHost', 'dbPort', 'dbName', 'dbUser', 'dbPass'];

        foreach ($requiredFields as $field) {
            if (empty($confConnection[$field])) {
                throw new Exception($field . " value is not set"); // Если одно из значений отсутствует или пустое, возвращаем false
            }
        }

        if ($confConnection['dbType'] !== 'mysql' && $confConnection['dbType'] !== 'postgresql') {
            throw new Exception("The '" . $confConnection['dbType'] . "' value is not correct");
            }
    }

    /**
     * @throws Exception
     */
    private function checkURL($url) {
        $headers = @get_headers($url);
        if ($headers && strpos($headers[0], '200') !== false) {
            return $url;
        } else {
            throw new Exception("Ошибка инициализации конфигурации");
        }
    }
}