<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class  AbstractThemeElementFactory implements ThemeElementFactoryInterface
{
    use BrizyQueryBuilderAware;

    /**
     * @var array
     */
    protected $blockKit;
    /**
     * @var BrowserPageInterface
     */
    protected $browserPage;


    /**
     * @var BrizyAPI
     */
    protected $brizyApiClient;
    protected FontsController $fontsController;


    public function __construct($blockKit, BrowserPageInterface $browserPage, QueryBuilder $queryBuilder, BrizyAPI $brizyApiClient, FontsController $fontsController)
    {
        $this->blockKit = $blockKit;
        $this->browserPage = $browserPage;
        $this->brizyApiClient = $brizyApiClient;
        $this->fontsController = $fontsController;

        $this->setQueryBuilder($queryBuilder);
    }
}