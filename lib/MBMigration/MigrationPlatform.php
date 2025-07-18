<?php

namespace MBMigration;

use GuzzleHttp\Exception\RequestException;
use MBMigration\Builder\Cms\SiteSEO;
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
    /**
     * @var false|mixed
     */
    private $brizyProjectDomain;
    /**
     * @var mixed
     */
    private $mb_projectDomain;
    /**
     * @var int|mixed
     */
    private $workspacesId;
    private bool $manualMigrate;
    private array $projectPagesList;
    private string $projectUUID_MB;
    private bool $mMgrIgnore;
    private bool $mgr_manual;

    use checking;
    use DebugBackTrace;

    public function __construct(
        Config $config,
        LoggerInterface $logger,
        $buildPage = '',
        $workspacesId = 0,
        bool $mMgrIgnore = true,
        $mgr_manual = false
    )
    {
        $this->cache = VariableCache::getInstance(Config::$cachePath);
        $this->logger = $logger;
        $this->mMgrIgnore = $mMgrIgnore;
        $this->mgr_manual = $mgr_manual;

        $this->errorDump = new ErrorDump($this->cache);
        set_error_handler([$this->errorDump, 'handleError']);
        register_shutdown_function([$this->errorDump, 'handleFatalError']);
        set_exception_handler([$this->errorDump, 'handleUncaughtExceptions']);

        $this->finalSuccess['status'] = 'start';
        $this->projectPagesList = [];

        $this->buildPage = $buildPage;
        $this->workspacesId = $workspacesId;

        $this->config = $config;
    }

    public function start(string $projectID_MB, int $projectID_Brizy = 0)
    {
        try {
            //$this->cache->loadDump($projectID_MB, $projectID_Brizy);
            $this->run($projectID_MB, $projectID_Brizy);
            //$this->cache->dumpCache($projectID_MB, $projectID_Brizy);
        } catch (GuzzleException $e) {
            Logger::instance()->critical($e->getMessage(), $e->getTrace());

            throw $e;
        } catch (Exception $e) {
            Logger::instance()->critical($e->getMessage(), $e->getTrace());

            throw $e;
        }
        unset($this->cache);
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

        $this->manualMigrate = false;

        $this->parser = new MBProjectDataCollector();
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    private function run(string $projectUUID_MB, int $projectID_Brizy = 0): bool
    {
        $this->brizyApi = new BrizyAPI();

        $this->cache->setClass($this->brizyApi, 'brizyApi');

        if (!($projectID_MB = $this->cache->get('projectId_MB'))) {
            $projectID_MB = MBProjectDataCollector::getIdByUUID($projectUUID_MB);
            $this->mb_projectDomain = MBProjectDataCollector::getDomainBySiteId($projectID_MB);
        }

        if ($projectID_Brizy == 0 && $this->workspacesId !== 0) {
            $this->projectID_Brizy = $this->brizyApi->createProject($this->mb_projectDomain ?? 'Project_id:'.$projectID_MB, $this->workspacesId, 'id');
//            $this->projectID_Brizy = $this->brizyApi->createProject('Project_id:'.$projectID_MB, 4423676, 'id');

            \MBMigration\Core\Logger::initialize("brizy-$this->projectID_Brizy");
        } else {
            $this->projectID_Brizy = $projectID_Brizy;
        }

        $this->brizyProjectDomain = $this->brizyApi->getDomain($this->projectID_Brizy);
        $this->projectUUID_MB = $projectUUID_MB;
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

        if (Config::$devMode) {
            $this->brizyApi->clearAllFontsInProject();
        }

//        $this->brizyApi->setLabelManualMigration(false);

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

        if(!$this->mMgrIgnore) {
            $this->manualMigrate = $this->brizyApi->checkProjectManualMigration($this->projectID_Brizy);
            if ($this->manualMigrate) {
                $this->projectPagesList = $this->parser->getPages();

                return true;
            }
        }

        $this->cache->setClass($this->QueryBuilder, 'QueryBuilder');

        if (!$this->cache->get('ListPages')) {
            $this->pageController->getAllPage();
        }

        if (!$this->cache->get('settings')) {
            $settings = $this->emptyCheck($this->parser->getSite(), self::trace(0).' Message: Site not found');
            $this->cache->set('settings', $settings);
        } else {
            $settings = $this->cache->get('settings');
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

        SiteSEO::setSiteTitle($this->projectId, $this->QueryBuilder, $this->cache);

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

        // lets dump the cache there.
        $this->cache->dumpCache($projectID_MB, $this->projectID_Brizy);

        $this->pageMapping = $this->pageController->getPageMapping($parentPages, $this->projectID_Brizy, $this->brizyApi);

        $this->launch($parentPages, false);
        $this->launch($parentPages, true);

        $this->brizyApi->clearCompileds($this->projectID_Brizy);



        Logger::instance()->info('Project migration completed successfully!');

        $this->logFinalProcess($this->startTime);

        if($this->mgr_manual){
            $this->brizyApi->setLabelManualMigration(true);
        }

        return true;
    }

    /**
     * @throws Exception
     */
    private function launch($parentPages, $hiddenPage): void
    {
        foreach ($parentPages as $i => $page) {
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
                $this->launch($page['child'], $hiddenPage);
            }
            if (Config::$devMode && $this->buildPage !== '') {
                if ($page['slug'] !== $this->buildPage) {
                    continue;
                }
            }
            if ($page['landing'] !== true) {
                continue;
            }

            if($page['hidden'] !== $hiddenPage) { continue; }
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

    /**
     * @throws Exception
     */
    public function getLogs(): array
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

        if (Config::$devMode) {
            $this->finalSuccess['DEV_MODE'] = true;
        }

        $this->finalSuccess['theme'] = $projectSettings['design'] ?? null;

        $this->finalSuccess['migration_id'] = $this->migrationID;
        $this->finalSuccess['date'] = date('Y-m-d');

        $this->finalSuccess['mb_uuid'] = $projectSettings['uuid'] ?? null;
        $this->finalSuccess['mb_site_id'] = $projectSettings['id'] ?? null;
        $this->finalSuccess['mb_product_name'] = $projectSettings['name'] ?? null;
        $this->finalSuccess['mb_project_domain'] = $this->mb_projectDomain ?? null;

        $this->finalSuccess['brizy_project_id'] = $this->projectID_Brizy ?? null;
        $this->finalSuccess['brizy_project_domain'] = $this->brizyProjectDomain ?? null;

        if ($successWorkCompletion) {
            $this->finalSuccess['status'] = 'success';
        }
        $this->finalSuccess['progress'] = $this->cache->get('Status');
        $this->finalSuccess['progress']['processTime'] = round($executionTime, 1);
        $this->finalSuccess['message']['warning'] = ErrorDump::$warningMessage ?? [];

        Logger::instance()->info('Work time: '.TimeUtility::Time($executionTime).' (seconds: '.round($executionTime, 1).')');
    }

    public function getProjectPagesList(): array
    {
        return $this->projectPagesList;
    }


    public function getStatusManualMigration(): bool
    {
        return $this->manualMigrate;
    }

    public function getProjectUUID(): string
    {
        return $this->projectUUID_MB;
    }

}
