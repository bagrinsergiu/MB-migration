<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;

abstract class  AbstractThemeElementFactory implements ThemeElementFactoryInterface
{
    /**
     * @var array
     */
    protected $blockKit;
    /**
     * @var BrowserPageInterface
     */
    protected $browserPage;


    public function __construct($blockKit, BrowserPageInterface $browserPage)
    {
        $this->blockKit = $blockKit;
        $this->browserPage = $browserPage;
    }
}