<?php

namespace MBMigration\Browser;

use HeadlessChromium\BrowserFactory;
use MBMigration\Core\Utils;
use Monolog\Handler\StreamHandler;
use Nesk\Puphpeteer\Puppeteer;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class BrowserPHP implements BrowserInterface
{
    /**
     * @var Puppeteer
     */
    private $browser;
    private $scriptPath;
    private $page = null;

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
        if (is_null($logger)) {
            $logger = new \Monolog\Logger('my_logger');
            $logger->pushHandler(new StreamHandler('php://stdout', LogLevel::DEBUG));
        }

        $browserFactory = new BrowserFactory('/usr/bin/google-chrome');

        // starts headless Chrome
        $this->browser = $browserFactory->createBrowser([
            'windowSize' => [1920, 2000],
            //'enableImages' => true,
            'debugLogger' => $logger,
            'keepAlive' => true,
            'noSandbox' => true,
            'customFlags'=>['--single-process','--no-zygote','--disable-setuid-sandbox','--disable-canvas-aa','--disable-3d-apis','--stub-cros-settings','--disable-dev-shm-usage','--no-sandbox'],
            'userDataDir'=>JSON_PATH."/chrome_data",
            //'excludedSwitches'=>['--disable-background-networking']
        ]);

        $this->scriptPath = $scriptPath;
    }

    public function openPage($url, $theme): BrowserPageInterface
    {
        echo "\nOpen page: {$url}\n";

        if (!isset($this->page)) {
            $this->page = $this->browser->createPage();
        }

        try {
            $this->page->navigate($url)->waitForNavigation();
        } catch (\Exception $e) {
            Utils::MESSAGES_POOL($e->getMessage(), 'error');
        }

        return new BrowserPagePHP($this->page, $this->scriptPath."/Theme/".$theme."/Assets/dist");
    }

    public function closePage(): void
    {
        try {
            //$this->page->close();
        } catch (\Exception $e) {
            Utils::MESSAGES_POOL($e->getMessage(), 'error');
        }
    }

    public function closeBrowser()
    {
        try {
            $this->browser->close();
        } catch (\Exception $e) {
            Utils::MESSAGES_POOL($e->getMessage(), 'error');
        }
    }
}