<?php

namespace MBMigration\Browser;

use MBMigration\Core\Utils;
use Monolog\Handler\StreamHandler;
use Nesk\Puphpeteer\Puppeteer;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class Browser implements BrowserInterface
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
        if(is_null($logger)) {
            $logger = new \Monolog\Logger('my_logger');
            $logger->pushHandler(new StreamHandler('php://stdout', LogLevel::DEBUG));
        }

        $puppeteer = new Puppeteer(
            [
                'log_browser_console' => true,
                'log_node_console' => true,
                'logger' => $logger,
                'protocolTimeout' => 900,
                'read_timeout' => 900,
                'idle_timeout' => null,
                'debug' => true,
            ]
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
        echo "\nOpen page: {$url}\n";

        if (!isset($this->page)) {
            $this->page = $this->browser->newPage();
            $this->page->setViewport(['width' => 1920, 'height' => 1480]);
        }

        try {
            $this->page->goto($url, ['timeout' => 120000, 'waitUntil' => 'networkidle0']);
        } catch (\Exception $e) {
            Utils::MESSAGES_POOL($e->getMessage(), 'error');
        }

        return new BrowserPage($this->page, $this->scriptPath."/Theme/".$theme."/Assets/dist");
    }

    public function closePage(): void
    {
        try {
            $this->page->close();
            sleep(2);
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