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
    public static $mainToken;

    /**
     * @var string
     */
    public static $graphqlToken;

    public static $DBConnection;
    public static $configPostgreSQL;
    public static $configMySQL;

    public static $nameMigration; // this is the name with which Workspaces is created, the same will be written by the same name for migration and work with projects

    private static $cloud_host;

    /**
     * @var bool
     */
    public static $devMode;
    /**
     * @var mixed
     */
    public static $urlJsonKits;

    public static $path;
    /**
     * @var array|string[]
     */
    public static $designInDevelop;
    /**
     * @var array
     */
    private static $settings;
    /**
     * @var array
     */
    private static $defaultSettings;

    /**
     * @throws Exception
     */
    public function __construct(string $cloud_host, string $path, string $token, array $DBConnection, array $settings = [])
    {
        $path = $this->checkPath($path);

        $this->checkDBConnection($DBConnection);

        $this->setSettings($settings);

        self::$defaultSettings  = [
            'devMode'       => false,
            'debugMode'     => true,
            'urlJsonKit'    => false,
            'graphqlToken'  => false
        ];

        self::$debugMode        = (bool) $this->checkSettings('debugMode');
        self::$devMode          = (bool) $this->checkSettings('devMode');

        self::$urlJsonKits      = $this->checkSettings('urlJsonKit');

        self::$nameMigration    = 'Migration';
        self::$endPointVersion  = '/2.0';

        self::$cloud_host       = $this->checkURL($cloud_host);

        self::$mainToken        = $this->checkToken($token);
        self::$graphqlToken     = $this->checkSettings('graphqlToken');

        self::$urlAPI           = self::$cloud_host . '/api';
        self::$urlProjectAPI    = self::$cloud_host . '/projects/{project}';
        self::$urlGetApiToken   = self::$cloud_host . '/api/projects/{project}/token';
        self::$urlGraphqlAPI    = self::$cloud_host . '/graphql/{ProjectId}';

        self::$path             = $path;
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
        
        self::$designInDevelop  = [
            'Boulevard'
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
        $requiredFields = ['dbHost', 'dbPort', 'dbName', 'dbUser', 'dbPass'];

        foreach ($requiredFields as $field) {
            if (empty($confConnection[$field])) {
                throw new Exception($field . " value is not set");
            }
        }
    }

    /**
     * @throws Exception
     */
    private function checkURL($url) {
        if (!empty($url)) {
            return $url;
        } else {
            throw new Exception("Url is wrong or not set");
        }
    }

    /**
     * @throws Exception
     */
    private function checkToken($token)
    {
        if (empty($token)) {
            throw new Exception("Token not set");
        }
        return $token;
    }

    private function setSettings(array $settings)
    {
        self::$settings = $settings;
    }

    private function checkSettings(string $flag)
    {
        if(array_key_exists($flag, self::$settings)){
            return self::$settings[$flag];
        } else {
            if(array_key_exists($flag, self::$defaultSettings)){
                return self::$defaultSettings[$flag];
            }
        }
        return false;
    }
}