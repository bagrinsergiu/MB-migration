<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Layer\Graph\QueryBuilder;

class LayoutElementFactory implements LayoutElementFactoryInterface
{
    /**
     * @var array
     */
    private $blockKit;
    /**
     * @var BrowserPageInterface
     */
    private $browserPage;

    static $instances = [];
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    public function __construct($blockKit, BrowserPageInterface $browserPage, QueryBuilder $queryBuilder)
    {
        $this->blockKit = $blockKit;
        $this->browserPage = $browserPage;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @throws ElementNotFound
     */
    public function getFactory($design): ThemeElementFactoryInterface
    {
        if ($instance = $this->loadFromCache($design)) {
            return $instance;
        }

        switch ($design) {
            case 'Voyage':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Voyage\ElementFactory(
                    $this->blockKit, $this->browserPage,$this->queryBuilder
                );
            case 'Aurora':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Aurora\ElementFactory(
                    $this->blockKit, $this->browserPage, $this->queryBuilder
                );
            case 'Solstice':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Solstice\ElementFactory(
                    $this->blockKit, $this->browserPage, $this->queryBuilder
                );
            case 'Majesty':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Majesty\ElementFactory(
                    $this->blockKit, $this->browserPage, $this->queryBuilder
                );
            default:
                throw new ElementNotFound("The Element [{$design}] was not found.");
        }
    }

    private function loadFromCache($design)
    {
        if (isset(self::$instances[$design])) {
            return self::$instances[$design];
        }

        return null;
    }
}

































