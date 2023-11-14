<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;

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

    public function __construct($blockKit, BrowserPageInterface $browserPage)
    {
        $this->blockKit = $blockKit;
        $this->browserPage = $browserPage;
    }

    public function getFactory($design): ThemeElementFactoryInterface
    {
        if ($instance = $this->loadFromCache($design)) {
            return $instance;
        }

        switch ($design) {
            case 'Voyage':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Voyage\ElementFactory(
                    $this->blockKit, $this->browserPage
                );
            case 'Aurora':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Aurora\ElementFactory(
                    $this->blockKit, $this->browserPage
                );
            case 'Solstice':
                return self::$instances[$design] = new \MBMigration\Builder\Layout\Theme\Solstice\ElementFactory(
                    $this->blockKit, $this->browserPage
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

































