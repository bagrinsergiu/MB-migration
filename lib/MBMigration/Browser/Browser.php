<?php

namespace MBMigration\Browser;

use MBMigration\Core\Utils;
use Nesk\Puphpeteer\Puppeteer;
use Psr\Log\LoggerInterface;

class Browser implements BrowserInterface
{
    /**
     * @var Puppeteer
     */
    private $browser;
    private $scriptPath;

    static public function instance($scriptPath, LoggerInterface $logger = null)
    {
        static $instance = null;

        if ($instance) {
            return $instance;
        }

        return $instance = new self($scriptPath, $logger);
    }

    private function __construct($scriptPath, LoggerInterface $logger = null)
    {
        $puppeteer = new Puppeteer(
            ['log_browser_console' => true, 'log_node_console' => true, 'logger' => $logger, 'debug' => true]
        );
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

    public function openPage($url, $theme): BrowserPageInterface
    {
        $page = $this->browser->newPage();
        $page->goto($url, ['timeout' => 60000]);

        return new BrowserPage($page, $this->scriptPath."/".$theme."/Assets/dist");
    }

//    function __destruct()
//    {
//        $this->browser->close();
//    }
}