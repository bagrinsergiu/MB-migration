<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
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


    public function __construct($blockKit, BrowserPageInterface $browserPage, QueryBuilder $queryBuilder, BrizyAPI $brizyApiClient)
    {
        $this->blockKit = $blockKit;
        $this->browserPage = $browserPage;
        $this->brizyApiClient = $brizyApiClient;


        $this->setQueryBuilder($queryBuilder);
    }
}