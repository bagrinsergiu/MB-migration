<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
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


    public function __construct($blockKit, BrowserPageInterface $browserPage, QueryBuilder $queryBuilder)
    {
        $this->blockKit = $blockKit;
        $this->browserPage = $browserPage;

        $this->setQueryBuilder($queryBuilder);
    }
}