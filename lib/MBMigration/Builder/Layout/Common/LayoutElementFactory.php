<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Theme\Bloom\ElementFactory;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Layer\Brizy\BrizyAPI;
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
    private BrowserPageInterface $browserPage;

    static $instances = [];
    /**
     * @var QueryBuilder
     */
    private QueryBuilder $queryBuilder;

    private BrizyAPI $brizyAPIClient;
    private FontsController $fontsController;

    public function __construct(
        $blockKit,
        BrowserPageInterface $browserPage,
        QueryBuilder $queryBuilder,
        BrizyAPI $brizyAPIClient,
        FontsController $fontsController
    ) {
        $this->blockKit = $blockKit;
        $this->browserPage = $browserPage;
        $this->queryBuilder = $queryBuilder;
        $this->brizyAPIClient = $brizyAPIClient;
        $this->fontsController = $fontsController;
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
            case 'Bloom':
                return self::$instances[$design] = new ElementFactory(
                    $this->blockKit, $this->queryBuilder, $this->brizyAPIClient, $this->fontsController
                );
            case 'Voyage':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Voyage\ElementFactory(
                    $this->blockKit, $this->queryBuilder, $this->brizyAPIClient, $this->fontsController
                );
            case 'Aurora':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Aurora\ElementFactory(
                    $this->blockKit, $this->queryBuilder, $this->brizyAPIClient, $this->fontsController
                );
//            case 'Solstice':
//                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Solstice\ElementFactory(
//                    $this->blockKit,  $this->queryBuilder, $this->brizyAPIClient, $this->fontsController
//                );
            case 'Majesty':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Majesty\ElementFactory(
                    $this->blockKit, $this->queryBuilder, $this->brizyAPIClient, $this->fontsController
                );
            case 'Ember':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Ember\ElementFactory(
                    $this->blockKit, $this->queryBuilder, $this->brizyAPIClient, $this->fontsController
                );
            default:
                throw new ElementNotFound("The Element Factory [{$design}] was not found.");
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

































