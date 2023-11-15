<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class AbstractElement implements ElementInterface
{
    /**
     * @var array
     */
    protected $brizyKit = [];
    /**
     * @var BrowserPageInterface
     */
    protected $browserPage;
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;


    public function __construct($brizyKit, BrowserPageInterface $browserPage)
    {
        $this->brizyKit = $brizyKit;
        $this->browserPage = $browserPage;
    }

    protected function canShowHeader($mbSectionData): bool
    {
        $sectionCategory = $mbSectionData['category'];

        if (isset($mbSectionData['settings']['sections'][$sectionCategory]['show_header'])) {
            return $mbSectionData['settings']['sections'][$sectionCategory]['show_header'];
        }

        return true;
    }

    protected function canShowBody($sectionData): bool
    {
        $sectionCategory = $sectionData['category'];
        if (isset($mbSectionData['settings']['sections'][$sectionCategory]['show_body'])) {
            return $sectionData['settings']['sections'][$sectionCategory]['show_body'];
        }

        return true;
    }

    protected function sortItems($items)
    {
        $groupColum = array_column($items, 'group');
        $orderByColumn = array_column($items, 'order_by');

        if (count($groupColum) == 0 || count($orderByColumn) == 0) {
            return $items;
        }

        array_multisort(
            $groupColum,
            SORT_ASC,
            array_column($items, 'order_by'),
            SORT_ASC,
            $items
        );

        return $items;
    }

}