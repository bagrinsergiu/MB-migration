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
     * @var \HeadlessChromium\Browser
     */
    private $browser;
    private $scriptPath;

    /**
     * @var Page|null
     */
    private ?Page $page = null;

    static public function instance($scriptPath, LoggerInterface $logger = null)
    {
        return new self($scriptPath, '/usr/bin/google-chrome', $logger);
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
            'connectionDelay' => 0.5,
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
        ]);

        $this->scriptPath = $scriptPath;
    }

    public function openPage($url, $theme): BrowserPageInterface
    {
        \MBMigration\Core\Logger::instance()->debug('Opening a new page');

        if (!$this->page) {
            \MBMigration\Core\Logger::instance()->debug('Creating a new browser tab.');
            $this->page = $this->browser->createPage();
        }

        \MBMigration\Core\Logger::instance()->debug('Navigate to: '.$url);
        $this->page->navigate($url)->waitForNavigation(Page::NETWORK_IDLE, 120000);


        return new BrowserPagePHP($this->page, $this->scriptPath."/Theme/".$theme."/Assets/dist");
    }

    public function closePage(): void
    {
        try {
            \MBMigration\Core\Logger::instance()->info('Closing the page');
            $this->page->close();
            sleep(2);
            $this->page = null;
        } catch (Exception $e) {
            \MBMigration\Core\Logger::instance()->critical($e->getMessage(), $e->getTrace());
        }
    }

    public function closeBrowser()
    {
        try {
            $this->browser->close();
            $this->browser = null;
        } catch (Exception $e) {
            \MBMigration\Core\Logger::instance()->critical($e->getMessage(), $e->getTrace());
        }
    }
}
