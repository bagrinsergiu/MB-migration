<?php

namespace MBMigration\Browser;

use MBMigration\Core\Utils;
use Nesk\Puphpeteer\Puppeteer;

class Browser implements BrowserInterface
{
    /**
     * @var Puppeteer
     */
    private $browser;
    private $scriptPath;

    static public function instance($scriptPath)
    {
        static $instance = null;

        if ($instance) {
            return $instance;
        }

        return $instance = new self($scriptPath);
    }

    private function __construct($scriptPath)
    {
        $puppeteer = new Puppeteer();
        $this->browser = $puppeteer->launch([
            "headless" => "new",
            'args' =>
                [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--aggressive-cache-discard',
                    '--disable-cache',
                    '--disable-application-cache',
                    '--disable-offline-load-stale-cache',
                    '--disable-gpu-shader-disk-cache',
                    '--media-cache-size=0',
                    '--disk-cache-size=0',
                ],
        ]);
        $this->scriptPath = $scriptPath;
    }

    public function openPage($url): BrowserPageInterface
    {
        $page = $this->browser->newPage();
        $page->goto($url);

        return new BrowserPage($page, $this->scriptPath);
    }

    function __destruct()
    {
        $this->browser->close();
    }
}