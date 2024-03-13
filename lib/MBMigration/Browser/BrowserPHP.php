<?php

namespace MBMigration\Browser;

use HeadlessChromium\Page;
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
            $logger = new Logger('browser');
            $logger->pushHandler(new StreamHandler($_ENV['CHROME_LOG_FILE_PATH'], $_ENV['CHROME_LOG_LEVEL']));
        }

        $browserFactory = new BrowserFactory($chromeExecutable);

        // starts headless Chrome
        $this->browser = $browserFactory->createBrowser([
            'windowSize' => [1920, 2000],
            //'enableImages' => true,
            'debugLogger' => $logger,
            'keepAlive' => false,
            'noSandbox' => true,
            'connectionDelay' => 1,
            'disableNotifications' => true,
            'customFlags' => [
                //'--single-process',
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
            'excludedSwitches' => ['--disable-background-networking'],
        ]);

        $this->scriptPath = $scriptPath;
    }

    public function openPage($url, $theme): BrowserPageInterface
    {
        \MBMigration\Core\Logger::instance()->info('Opening: '.$url);

        if (!$this->page) {
            $this->page = $this->browser->createPage();
        }
        $imageName = str_replace(["/",":"],"",$url);
        $this->page->screenshot([
            'format' => 'jpeg',  // default to 'png' - possible values: 'png', 'jpeg', 'webp'
            'quality' => 80,      // only when format is 'jpeg' or 'webp' - default 100
            'optimizeForSpeed' => true,
             'captureBeyondViewport' => true,// default to 'false' - Optimize image encoding for speed, not for resulting size
        ])->saveToFile('/project/var/cache/before-'.$imageName.".png");

        try {
            $this->page->navigate($url)->waitForNavigation(Page::NETWORK_IDLE);
        } catch (Exception $e) {
            \MBMigration\Core\Logger::instance()->critical($e->getMessage(), $e->getTrace());
        }

        $this->page->screenshot([
            'format' => 'jpeg',  // default to 'png' - possible values: 'png', 'jpeg', 'webp'
            'quality' => 80,      // only when format is 'jpeg' or 'webp' - default 100
            'optimizeForSpeed' => true,
             'captureBeyondViewport' => true, // default to 'false' - Optimize image encoding for speed, not for resulting size
        ])->saveToFile('/project/var/cache/after-'.$imageName.".png");

        return new BrowserPagePHP($this->page, $this->scriptPath."/Theme/".$theme."/Assets/dist");
    }

    public function closePage(): void
    {
        try {
            // $this->page->close();
        } catch (Exception $e) {
            \MBMigration\Core\Logger::instance()->critical($e->getMessage(), $e->getTrace());
        }
    }

    public function closeBrowser()
    {
        try {
            $this->browser->close();
        } catch (Exception $e) {
            \MBMigration\Core\Logger::instance()->critical($e->getMessage(), $e->getTrace());
        }
    }
}