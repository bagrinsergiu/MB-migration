<?php

namespace MBMigration\Browser;

use HeadlessChromium\Page;
use Monolog\Logger;
use Exception;
use HeadlessChromium\BrowserFactory;
use Monolog\Handler\StreamHandler;
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

        // Try to create browser with retry logic and port rotation
        $this->browser = $this->createBrowserWithRetry($browserFactory, $logger);



//          $this->browser = $browserFactory->createBrowser([
//            'headless' => false,
//            'windowSize' => [1920, 6000],
//            //'enableImages' => true,
//            //'debugLogger' => $logger,
//            //'keepAlive' => false,
//            //'noSandbox' => true,
//            //'connectionDelay' => 0.5,
//            //'disableNotifications' => true,
//            'customFlags' => [
//                //'--single-process',
//                '--remote-debugging-port=9022',
//                '--no-zygote',
//                '--disable-setuid-sandbox',
//                '--disable-canvas-aa',
//                '--disable-3d-apis',
//                '--stub-cros-settings',
//                '--disable-dev-shm-usage',
//                '--no-sandbox',
//                '--disable-setuid-sandbox',
//                '--disable-web-security',
//
//                '--remote-debugging-port=9222',
//                '--disable-dev-shm-usage',       // avoids /dev/shm size limits in Docker
//                '--no-sandbox',                  // required in many container setups
//                '--disable-gpu',                 // skip GPU emulation (faster in Docker unless you pass GPU)
//                '--disable-software-rasterizer', // don’t emulate GPU
//                '--disable-extensions',
//                '--disable-background-networking',
//                '--disable-background-timer-throttling',
//                '--disable-backgrounding-occluded-windows',
//                '--disable-breakpad',
//                '--disable-client-side-phishing-detection',
//                '--disable-default-apps',
//                '--disable-sync',
//                '--disable-translate',
//                '--metrics-recording-only',
//                '--no-first-run',
//                '--safebrowsing-disable-auto-update',
//                '--mute-audio',
//            ],
//        ]);


        $this->scriptPath = $scriptPath;
    }

    /**
     * Create browser instance with retry logic and port rotation
     * 
     * @param BrowserFactory $browserFactory
     * @param LoggerInterface $logger
     * @return \HeadlessChromium\Browser
     * @throws Exception
     */
    private function createBrowserWithRetry(BrowserFactory $browserFactory, LoggerInterface $logger)
    {
        // Base ports to try (starting from 9002, then rotating)
        $basePorts = [9002, 9003, 9004, 9005, 9006];
        $maxRetries = count($basePorts); // Try all ports
        $lastException = null;

        for ($attempt = 0; $attempt < $maxRetries; $attempt++) {
            // Rotate through ports
            $port = $basePorts[$attempt];
            
            try {
                \MBMigration\Core\Logger::instance()->debug("Attempting to create browser", [
                    'attempt' => $attempt + 1,
                    'port' => $port
                ]);

                // Clean up any existing Chrome processes on this port before trying
                $this->cleanupPort($port);

                // Build custom flags with the current port
                $customFlags = [
                    '--no-zygote',
                    '--disable-setuid-sandbox',
                    '--disable-canvas-aa',
                    '--disable-3d-apis',
                    '--stub-cros-settings',
                    '--disable-dev-shm-usage',
                    '--no-sandbox',
                    '--disable-web-security',
                    '--remote-debugging-port=' . $port,
                    '--disable-gpu',
                    '--disable-software-rasterizer',
                    '--disable-extensions',
                    '--disable-background-networking',
                    '--disable-background-timer-throttling',
                    '--disable-backgrounding-occluded-windows',
                    '--disable-breakpad',
                    '--disable-client-side-phishing-detection',
                    '--disable-default-apps',
                    '--disable-sync',
                    '--disable-translate',
                    '--metrics-recording-only',
                    '--no-first-run',
                    '--safebrowsing-disable-auto-update',
                    '--mute-audio',
                ];

                // starts headless Chrome
                $browser = $browserFactory->createBrowser([
                    'windowSize' => [1920, 6000],
                    'customFlags' => $customFlags,
                ]);

                \MBMigration\Core\Logger::instance()->info("Browser created successfully", [
                    'port' => $port,
                    'attempt' => $attempt + 1
                ]);

                return $browser;

            } catch (Exception $e) {
                $lastException = $e;
                $errorMessage = $e->getMessage();
                
                \MBMigration\Core\Logger::instance()->warning("Browser creation failed", [
                    'attempt' => $attempt + 1,
                    'port' => $port,
                    'error' => $errorMessage,
                    'error_code' => $e->getCode()
                ]);

                // If it's a port conflict or DevTools error, try next port
                if (strpos($errorMessage, 'Devtools could not start') !== false ||
                    strpos($errorMessage, 'port') !== false ||
                    strpos($errorMessage, 'address already in use') !== false) {
                    
                    // Wait a bit before retrying
                    if ($attempt < $maxRetries - 1) {
                        sleep(2);
                        continue;
                    }
                }

                // For other errors, still retry but log more details
                if ($attempt < $maxRetries - 1) {
                    sleep(2);
                    continue;
                }
            }
        }

        // All retries failed
        \MBMigration\Core\Logger::instance()->critical("Failed to create browser after all retries", [
            'max_retries' => $maxRetries,
            'last_error' => $lastException ? $lastException->getMessage() : 'Unknown error'
        ]);

        throw new Exception(
            "Failed to start Chrome browser after {$maxRetries} attempts. " .
            ($lastException ? "Last error: " . $lastException->getMessage() : ""),
            0,
            $lastException
        );
    }

    /**
     * Clean up any processes using the specified port
     * 
     * @param int $port
     * @return void
     */
    private function cleanupPort(int $port): void
    {
        try {
            // Check if port is in use
            $connection = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.1);
            if ($connection) {
                @fclose($connection);
                
                \MBMigration\Core\Logger::instance()->debug("Port {$port} is in use, attempting cleanup");
                
                // Try to find and kill Chrome processes using this port
                // This is a best-effort cleanup
                $commands = [
                    // Find processes using the port
                    "lsof -ti:{$port} 2>/dev/null",
                    // Alternative: netstat
                    "netstat -tlnp 2>/dev/null | grep :{$port} | awk '{print \$7}' | cut -d'/' -f1",
                ];

                foreach ($commands as $cmd) {
                    $output = [];
                    $returnVar = 0;
                    @exec($cmd, $output, $returnVar);
                    
                    if (!empty($output)) {
                        $pids = array_filter(array_map('trim', $output));
                        foreach ($pids as $pid) {
                            if (is_numeric($pid) && $pid > 0) {
                                \MBMigration\Core\Logger::instance()->debug("Killing process using port {$port}", ['pid' => $pid]);
                                @exec("kill -9 {$pid} 2>/dev/null");
                                usleep(500000); // Wait 0.5 seconds
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Ignore cleanup errors, just log them
            \MBMigration\Core\Logger::instance()->debug("Port cleanup error (non-critical)", [
                'port' => $port,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function openPage($url, $theme): BrowserPageInterface
    {
        \MBMigration\Core\Logger::instance()->debug('Opening a new page');

        if (!$this->page) {
            \MBMigration\Core\Logger::instance()->debug('Creating a new browser tab.');
            $this->page = $this->browser->createPage();
        }

        \MBMigration\Core\Logger::instance()->debug('Navigate to: '.$url);
        $this->page->navigate($url)->waitForNavigation(Page::DOM_CONTENT_LOADED, 120000);


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
