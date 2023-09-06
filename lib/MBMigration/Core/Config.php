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
    /**
     * @var mixed
     */
    public static $MBMediaStaging;

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
    public function __construct(string $cloud_host, string $path, string $token, array $settings)
    {
        $path = $this->checkPath($path);

        $this->setSettings($settings);

        $this->checkRequiredKeys($settings);

        $DBConnection = $this->checkDBConnection($settings['db']);

        self::$defaultSettings  = [
            'devMode'       => false,
            'debugMode'     => true,
            'urlJsonKit'    => false,
            'graphqlToken'  => false
        ];

        self::$debugMode        = (bool) $this->checkSettings('debugMode');
        self::$devMode          = (bool) $this->checkSettings('devMode');

        self::$urlJsonKits      = $this->checkAssets('CloudUrlJsonKit');
        self::$MBMediaStaging   = $this->checkAssets('MBMediaStaging');

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
            'Boulevard', 'Dusk', 'Aurora', 'Solstice', 'Tradition', 'Hope', 'August', 'Voyage', 'Zion', 'Boulevard', 'Ember', 'Bloom', 'Majesty', 'Serene'
        ];

    }

    /**
     * @throws Exception
     */
    private function checkPath($path): string
    {
        $pathWrite = is_dir($path) ? $path : sys_get_temp_dir();
        $this->checkAndDeleteFile($pathWrite);
        return $pathWrite;
    }

    /**
     * @throws Exception
     */
    private function checkDBConnection(array $confConnection): array
    {
        $requiredFields = ['dbHost', 'dbPort', 'dbName', 'dbUser', 'dbPass'];

        foreach ($requiredFields as $field) {
            if (empty($confConnection[$field])) {
                throw new Exception($field . " value is not set");
            }
        }
        return $confConnection;
    }

    /**
     * @throws Exception
     */
    private function checkRequiredKeys($confConnection): void
    {
        $requiredKeys = ['db', 'assets'];

        foreach ($requiredKeys as $field) {
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

    /**
     * @throws Exception
     */
    private function setSettings(array $settings)
    {
        if (empty($settings)) {
            throw new Exception('Settings not set');
        }
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

    /**
     * @throws Exception
     */
    private function checkAssets(string $flag)
    {
        $assets = self::$settings['assets'];

        if(array_key_exists($flag, $assets)){
            if (empty($assets[$flag])) {
                throw new Exception('Assets is empty');
            }
            return $assets[$flag];
        }
        return false;
    }

    /**
     * @throws Exception
     */
    function checkAndDeleteFile($path) {

        $testFile = $path . '/test_file.log';
        $handle = @fopen($testFile, 'w');

        if ($handle === false) {
            throw new Exception('Unable to create or write a file at the specified path.');
        }

        fwrite($handle, "test");
        fclose($handle);

        if (!unlink($testFile)) {
            throw new Exception('The file was successfully verified and created, but failed to delete the file.');
        }
    }
}