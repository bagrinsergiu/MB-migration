<?php

namespace MBMigration\Browser;

use Monolog\Logger;
use Exception;
use HeadlessChromium\BrowserFactory;
use MBMigration\Core\Config;
use Monolog\Handler\StreamHandler;
use Nesk\Puphpeteer\Puppeteer;
use Psr\Log\LoggerInterface;

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

        return $instance = new self($scriptPath, '/usr/bin/google-chrome', $logger);
    }

    private function __construct(
        $scriptPath,
        $chromeExecutable = '/usr/bin/google-chrome',
        LoggerInterface $logger = null
    ) {
        if (is_null($logger)) {
            $logger = new Logger('my_logger');
            $logger->pushHandler(new StreamHandler('php://stdout', $_ENV['CHROME_LOG_LEVEL']));
        }

        $browserFactory = new BrowserFactory($chromeExecutable);

        // starts headless Chrome
        $this->browser = $browserFactory->createBrowser([
            'windowSize' => [1920, 2000],
            //'enableImages' => true,
            'debugLogger' => $logger,
            'keepAlive' => true,
            'noSandbox' => true,
            'customFlags' => [
                '--single-process',
                '--no-zygote',
                '--disable-setuid-sandbox',
                '--disable-canvas-aa',
                '--disable-3d-apis',
                '--stub-cros-settings',
                '--disable-dev-shm-usage',
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-web-security',
            ],
            //'excludedSwitches'=>['--disable-background-networking']
        ]);

        $this->scriptPath = $scriptPath;
    }

    public function openPage($url, $theme): BrowserPageInterface
    {
        if (!isset($this->page)) {
            $this->page = $this->browser->createPage();
        }

        try {
            $this->page->navigate($url)->waitForNavigation();
        } catch (Exception $e) {
            \MBMigration\Core\Logger::instance()->critical($e->getMessage());
        }

        return new BrowserPagePHP($this->page, $this->scriptPath."/Theme/".$theme."/Assets/dist");
    }

    public function closePage(): void
    {
        try {
            //$this->page->close();
        } catch (Exception $e) {
            \MBMigration\Core\Logger::instance()->critical($e->getMessage());
        }
    }

    public function closeBrowser()
    {
        try {
            $this->browser->close();
        } catch (Exception $e) {
            \MBMigration\Core\Logger::instance()->critical($e->getMessage());
        }
    }
}