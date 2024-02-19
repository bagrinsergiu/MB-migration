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
     * @var array
     */
    public static $metaData;

    /**
     * @var bool
     */
    public static $devMode;
    /**
     * @var mixed
     */
    public static $urlJsonKits;

    /**
     * @var string
     */
    public static $previewBaseHost;

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

        self::$defaultSettings = [
            'devMode' => false,
            'debugMode' => false,
            'urlJsonKit' => false,
            'graphqlToken' => false,
            'DMode_Option' => [
                'log_SqlQuery' => false,
            ],
        ];

        self::$previewBaseHost = $this->checkPreviewBaseHost();

        self::$debugMode = (bool)$this->checkSettings('debugMode');
        self::$devMode = (bool)$this->checkSettings('devMode');

        self::$metaData = $this->checkMetaData();

        self::$urlJsonKits = $this->checkAssets('CloudUrlJsonKit');
        self::$MBMediaStaging = $this->checkAssets('MBMediaStaging');

        self::$nameMigration = 'Migration';
        self::$endPointVersion = '/2.0';

        self::$cloud_host = $this->checkURL($cloud_host);

        self::$mainToken = $this->checkToken($token);
        self::$graphqlToken = $this->checkSettings('graphqlToken');

        self::$urlAPI = self::$cloud_host.'/api';
        self::$urlProjectAPI = self::$cloud_host.'/projects/{project}';
        self::$urlGetApiToken = self::$cloud_host.'/api/projects/{project}/token';
        self::$urlGraphqlAPI = self::$cloud_host.'/graphql/{ProjectId}';

        self::$path = $path;
        self::$pathTmp = $path.'/mb_tmp/';
        self::$pathLogFile = $path.'/mb_log/{{PREFIX}}.log';

        self::$endPointApi = [
            'globalBlocks' => '/global_blocks',
            'team_members' => '/team_members',
            'menus/create' => '/menus/create',
            'workspaces' => '/workspaces',
            'projects' => '/projects',
            'users' => '/users',
            'pages' => '/pages',
            'media' => '/media',
            'fonts' => '/fonts',
            'menu' => '/menus',
        ];

        self::$configPostgreSQL = [
            'dbHost' => $DBConnection['dbHost'],
            'dbPort' => $DBConnection['dbPort'],
            'dbName' => $DBConnection['dbName'],
            'dbUser' => $DBConnection['dbUser'],
            'dbPass' => $DBConnection['dbPass'],
        ];

        self::$configMySQL = [
            'dbHost' => $DBConnection['dbHost'],
            'dbName' => $DBConnection['dbName'],
            'dbUser' => $DBConnection['dbUser'],
            'dbPass' => $DBConnection['dbPass'],
        ];

        self::$designInDevelop = [
            'Boulevard',
            'Dusk',
            'Aurora',
            'Solstice',
            'Tradition',
            'Hope',
            'August',
            'Voyage',
            'Zion',
            'Boulevard',
            'Ember',
            'Bloom',
            'Majesty',
            'Serene',
        ];

    }

    public static function getDevOptions($optionsName = '')
    {
        if (!empty($optionsName)) {
            if (!array_key_exists($optionsName, self::$defaultSettings['DMode_Option'])) {
                return false;
            }

            return self::$defaultSettings['DMode_Option'][$optionsName];
        }

        return self::$defaultSettings['DMode_Option'];
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
                throw new Exception($field." value is not set");
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
                throw new Exception($field." value is not set");
            }
        }
    }

    /**
     * @throws Exception
     */
    private function checkURL($url)
    {
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
        if (array_key_exists($flag, self::$settings)) {
            return self::$settings[$flag];
        } else {
            if (array_key_exists($flag, self::$defaultSettings)) {
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

        if (array_key_exists($flag, $assets)) {
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
    function checkAndDeleteFile($path)
    {
        if (!is_writable($path)) {
            throw new Exception("The path [{$path}] is no writable.");
        }
    }

    /**
     * @throws Exception
     */
    private function checkMetadata()
    {
        if(isset(self::$settings['metaData'])) {
            $metaData = self::$settings['metaData'];
            if (!empty($metaData)) {
                $requiredFields = ['secret', 'MBAccountID', 'MBVisitorID'];

                foreach ($requiredFields as $field) {
                    if (empty($metaData[$field])) {
                        throw new Exception($field." value is not set");
                    }
                }

                return $metaData;
            }
        }
        return false;
    }

    /**
     * @throws Exception
     */
    private function checkPreviewBaseHost()
    {
        $flag = 'previewBaseHost';

        if (array_key_exists($flag, self::$settings)) {

            if (empty(self::$settings[$flag])) {
                throw new Exception('PreviewBaseHost is empty');
            }

            return self::$settings[$flag];
        } else {
            throw new Exception('PreviewBaseHost value is not set');
        }
    }
}