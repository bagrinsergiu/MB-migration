<?php

namespace MBMigration\Browser;

use MBMigration\Core\Utils;
use Nesk\Puphpeteer\Puppeteer;

class Browser
{
    private Puppeteer $puppeteer;

    static public function instance($pageUrl)
    {
        static $instance = null;

        if ($instance) {
            return $instance;
        }

        return $instance = new self($pageUrl);
    }

    private function __construct()
    {
        try {
            $this->puppeteer = new Puppeteer();
            $this->puppeteer->launch([
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
        } catch (\Exception $e) {
            Utils::MESSAGES_POOL($e->getMessage(), 'browser', 'JS:RUN');

            return '';
        }
    }

    public function runScripts($pageUrl, $jsScript, $params)
    {
        $this->puppeteer->newPage();
        $this->puppeteer->goto($pageUrl);
    }

    public function __destruct()
    {
        $this->puppeteer->close();
    }
}