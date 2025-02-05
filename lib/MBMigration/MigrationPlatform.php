<?php

namespace MBMigration;

use MBMigration\Builder\Media\MediaController;
use MBMigration\Builder\Menu\MenuHandler;
use MBMigration\Builder\Utils\ArrayManipulator;
use MBMigration\Builder\Utils\FoldersUtility;
use MBMigration\Builder\Utils\TimeUtility;
use MBMigration\Core\Logger;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Builder\Checking;
use MBMigration\Builder\DebugBackTrace;
use MBMigration\Builder\Layout\Common\MenuBuilderFactory;
use MBMigration\Builder\PageController;
use MBMigration\Builder\Utils\ExecutionTimer;
use MBMigration\Builder\Utils\TextTools;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;
use MBMigration\Core\ErrorDump;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\Graph\QueryBuilder;
use MBMigration\Layer\MB\MBProjectDataCollector;
use MBMigration\Layer\MB\MonkcmsAPI;
use MBMigration\Parser\JS;
use Psr\Log\LoggerInterface;

class MigrationPlatform
{
    protected VariableCache $cache;
    protected string $projectId;
    private MBProjectDataCollector $parser;
    private QueryBuilder $QueryBuilder;
    private BrizyApi $brizyApi;
    private $projectID_Brizy;
    private $startTime;
    private $graphApiBrizy;
    private string $migrationID;
    private ErrorDump $errorDump;
    private array $finalSuccess;
    private string $buildPage;
    private PageController $pageController;
    private LoggerInterface $logger;
    private array $pageMapping;
    private Config $config;

    use checking;
    use DebugBackTrace;

    public function __construct(Config $config, LoggerInterface $logger, $buildPage = '')
    {
        $this->cache = VariableCache::getInstance(Config::$cachePath);
        $this->logger = $logger;

        $this->errorDump = new ErrorDump($this->cache);
        set_error_handler([$this->errorDump, 'handleError']);
        register_shutdown_function([$this->errorDump, 'handleFatalError']);

        $this->finalSuccess['status'] = 'start';

        $this->buildPage = $buildPage;

        $this->config = $config;
    }

    public function start(string $projectID_MB, int $projectID_Brizy = 0): bool
    {
        try {
            $this->cache->loadDump($projectID_MB, $projectID_Brizy);
            $this->run($projectID_MB, $projectID_Brizy);
            $this->cache->dumpCache($projectID_MB, $projectID_Brizy);
        } catch (GuzzleException $e) {
            Logger::instance()->critical($e->getMessage(), $e->getTrace());

            throw $e;
        } catch (Exception $e) {
            Logger::instance()->critical($e->getMessage(), $e->getTrace());

            throw $e;
        }

        return true;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    private function init(string $projectID_MB, int $projectID_Brizy): void
    {
        Logger::instance()->info('Starting the migration for '.$projectID_MB.' to '.$projectID_Brizy);

        Utils::init($this->cache);

        $this->startTime = microtime(true);

        $this->cache->set('migrationID', $this->migrationID);
        Logger::instance()->info('Migration ID: '.$this->migrationID);

        $this->graphApiBrizy = Utils::strReplace(Config::$urlGraphqlAPI, '{ProjectId}', $projectID_Brizy);

        $this->cache->set('projectId_MB', $projectID_MB);
        $this->cache->set('projectId_Brizy', $projectID_Brizy);
        $this->cache->set('Status', ['Total' => 0, 'Success' => 0]);
        $this->cache->set('headBlockCreated', false);
        $this->cache->set('footerBlockCreated', false);

        $this->parser = new MBProjectDataCollector();
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    private function run(string $projectUUID_MB, int $projectID_Brizy = 0): void
    {
        $this->brizyApi = new BrizyAPI();

        $this->cache->setClass($this->brizyApi, 'brizyApi');

        if (!($projectID_MB = $this->cache->get('projectId_MB'))) {
            $projectID_MB = MBProjectDataCollector::getIdByUUID($projectUUID_MB);
        }

        if ($projectID_Brizy == 0) {
            $this->projectID_Brizy = $this->brizyApi->createProject('Project_id:'.$projectID_MB, 4352671, 'id');
//            $this->projectID_Brizy = $this->brizyApi->createProject('Project_id:'.$projectID_MB, 4423676, 'id');
        } else {
            $this->projectID_Brizy = $projectID_Brizy;
        }

        $this->projectId = $projectUUID_MB.'_'.$this->projectID_Brizy.'_';
        $this->migrationID = $this->brizyApi->getNameHash($this->projectId, 10);
        $this->projectId .= $this->migrationID;

        if (!$this->cache->get('container')) {
            $this->cache->set('container', $this->brizyApi->getProjectContainer($this->projectID_Brizy));
        }

        $this->init($projectID_MB, $this->projectID_Brizy);

        if (!$designName = $this->cache->get('designName')) {
            $designName = $this->parser->getDesignSite();
            $this->cache->set('designName', $designName);
        }

//        if (Config::$devMode) {
//            $this->brizyApi->clearAllFontsInProject();
//        }

        $this->checkDesign($designName);

        FoldersUtility::createProjectFolders($this->projectId);

        $this->cache->set('GraphApi_Brizy', $this->graphApiBrizy);

        if (!($graphToken = $this->cache->get('graphToken'))) {
            $graphToken = $this->brizyApi->getGraphToken($this->projectID_Brizy);
            $this->cache->set('graphToken', $graphToken);
        }

        $this->QueryBuilder = new QueryBuilder(
            $this->graphApiBrizy,
            $graphToken
        );

        $this->pageController = new PageController(
            $this->parser,
            $this->brizyApi,
            $this->QueryBuilder,
            $this->logger,
            $this->projectID_Brizy,
            $designName
        );

        $this->cache->setClass($this->QueryBuilder, 'QueryBuilder');

        if (!$this->cache->get('ListPages')) {
            $this->pageController->getAllPage();
        }

        if (!$this->cache->get('settings')) {
            $settings = $this->emptyCheck($this->parser->getSite(), self::trace(0).' Message: Site not found');
            $this->cache->set('settings', $settings);
        }

        $this->brizyApi->setMetaDate();

        $received = $this->brizyApi->getMetadata();

        $configM = [
            'siteId' => $received['site_id'],
            'siteSecret' => $received['secret'],
        ];

        $mCms = new MonkcmsAPI($configM);

        $this->cache->set('series', $mCms->getSeriesGroupBySlug());

        $parentPages = $this->parser->getPages();

        if (empty($parentPages)) {
            Logger::instance()->info(
                'MB project not found, migration did not start, process completed without errors!'
            );
            $this->logFinalProcess($this->startTime, false);

            throw new Exception('MB project not found, migration did not start, process completed without errors!');
        }

        MediaController::setFavicon($settings['favicon'] ?? null, $this->projectId, $this->brizyApi, $this->QueryBuilder);

        if (!$this->cache->get('mainSection')) {
            $mainSection = $this->parser->getMainSection();
            Logger::instance()->debug('Upload section pictures');
            $mainSection = MediaController::uploadPicturesFromSections($mainSection, $this->projectId, $this->brizyApi);
            $this->cache->set('mainSection', $mainSection);
        }

        if (!$this->cache->get('menuList')) {
            Logger::instance()->info('Start create blank pages');
            $existingBrizyPages = $this->brizyApi->getAllProjectPages();
//            if (!$this->buildPage) {
                $existingBrizyPages['listPages'] = $this->pageController->deleteAllPages(
                    $existingBrizyPages['listPages']
                );
//            }

            $this->pageController->createBlankPages(
                $parentPages,
                $existingBrizyPages
            );

            $this->cache->set('menuList', [
                'id' => null,
                'uid' => null,
                'name' => null,
                'create' => false,
                'list' => $parentPages,
                'data' => "",
            ]);

            MenuHandler::createMenuList();
        } else {
            $parentPages = $this->cache->get('menuList')['list'];
        }

        if (Config::$devMode) {
            echo $this->cache->get('design', 'settings')."\n";
        }

        // lets dump the cache there.
        $this->cache->dumpCache($projectID_MB, $projectID_Brizy);

        $this->pageMapping = $this->pageController->getPageMapping($parentPages, $this->projectID_Brizy, $this->brizyApi);

        $this->launch($parentPages);

        $this->brizyApi->clearCompileds($projectID_Brizy);

        Logger::instance()->info('Project migration completed successfully!');

        $this->logFinalProcess($this->startTime);
    }

    /**
     * @throws Exception
     */
    private function launch($parentPages): void
    {
        foreach ($parentPages as $i => $page) {
//            if($page['hidden']){ continue; }

            if (!empty($page['parentSettings'])) {
                $settings = json_decode($page['parentSettings'], true);
//                if (array_key_exists('external_url', $settings)) {
//                    continue;
//                }

//                // there a pages that have only one section only and de same slug as home page..
//                if ( array_key_exists('category', $settings) && $settings['category']=='text' ) {
//                    continue;
//                }
            }

            if (!empty($page['child'])) {
                $this->launch($page['child']);
            }
            if (Config::$devMode && $this->buildPage !== '') {
                if ($page['slug'] !== $this->buildPage) {
                    continue;
                }
            }
            if ($page['landing'] !== true) {
                continue;
            }
            $this->collector($page);
        }
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    private function collector($page): void
    {
        $this->cache->set('tookPage', $page);
        ExecutionTimer::start();

         if (!($preparedSectionOfThePage = $this->cache->get('preparedSectionOfThePage_'.$page['id']))) {
            $preparedSectionOfThePage = $this->pageController->getSectionsFromPage($page);
            if (!$preparedSectionOfThePage) {
                return;
            }
            $preparedSectionOfThePage = MediaController::uploadPicturesFromSections($preparedSectionOfThePage, $this->projectId, $this->brizyApi);
            $preparedSectionOfThePage = ArrayManipulator::sortArrayByPosition($preparedSectionOfThePage);
            $this->cache->set('preparedSectionOfThePage_'.$page['id'], $preparedSectionOfThePage);
        }

        $collectionItem = $page['collection'];

        if ($collectionItem) {
            $this->pageController->setCurrentPageOnWork($collectionItem);
            Logger::instance()->info('Run Page Builder for page', ['slug' => $page['slug'], 'name' => $page['name']]);
            $this->pageController->run($preparedSectionOfThePage, $this->pageMapping);
        } else {
            Logger::instance()->info(
                'Failed to run collector for page: '.$page['slug'].'. The collection item was not found.'
            );
        }
    }

    public function getStatus()
    {
        return json_encode($this->cache->get('Status'));
    }

    /**
     * @throws Exception
     */
    private function checkDesign($designName)
    {
        $designInDevelop = Config::$designInDevelop;
        $devMode = Config::$devMode;
        if (in_array($designName, $designInDevelop) && !$devMode) {
            throw new Exception('This design is not ready for migration, but is in development');
        }
    }

    public function getLogs()
    {
        if ($this->finalSuccess['status'] === 'success') {
            Logger::instance()->debug(json_encode($this->errorDump->getDetailsMessage()));

            return $this->finalSuccess;
        }

        return $this->errorDump->getAllErrors();
    }

    private function logFinalProcess(float $startTime, bool $successWorkCompletion = true): void
    {
        $endTime = microtime(true);

        $projectSettings = $this->cache->get('settings');
        $executionTime = ($endTime - $startTime);

        $this->finalSuccess['UMID'] = $this->migrationID;

        $this->finalSuccess['uuid'] = $projectSettings['uuid'] ?? null;
        $this->finalSuccess['site_id'] = $projectSettings['id'] ?? null;
        $this->finalSuccess['product_name'] = $projectSettings['name'] ?? null;
        $this->finalSuccess['design'] = $projectSettings['design'] ?? null;

        if ($successWorkCompletion) {
            $this->finalSuccess['status'] = 'success';
        }
        $this->finalSuccess['progress'] = $this->cache->get('Status');
        $this->finalSuccess['processTime'] = round($executionTime, 1);

        Logger::instance()->info('Work time: '.TimeUtility::Time($executionTime).' (seconds: '.round($executionTime, 1).')');
    }

}
