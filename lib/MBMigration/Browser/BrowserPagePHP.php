<?php

namespace MBMigration\Browser;

use Exception;
use HeadlessChromium\Page;
use MBMigration\Core\Logger;

class BrowserPagePHP implements BrowserPageInterface
{
    /**
     * @var Page
     */
    private $page;
    private $scriptPath;

    /**
     * Generic helper for safe callFunction with timeout and one quick retry.
     */
    private function callFunctionWithRetry(string $function, array $args, int $timeoutMs, int $retryTimeoutMs = 5000)
    {
        try {
            return $this->page->callFunction($function, $args)
                ->waitForResponse($timeoutMs)
                ->getReturnValue($timeoutMs);
        } catch (Exception $e) {
            try {
                return $this->page->callFunction($function, $args)
                    ->waitForResponse($retryTimeoutMs)
                    ->getReturnValue($retryTimeoutMs);
            } catch (Exception $e2) {
                throw $e2;
            }
        }
    }

    public function __construct($page, $scriptPath)
    {
        $this->page = $page;
        $this->scriptPath = $scriptPath;
        $this->page->addScriptTag([
            'content' => $this->getScriptBody("index.js"),
        ])->waitForResponse(50000);
    }

    public function evaluateScript($jsScript, $params): array
    {
        $baseTimeout = 15000;
        $fastRetryTimeout = 8000;

        try {
            $result = $this->page->callFunction($jsScript, [(object)$params])
                ->waitForResponse($baseTimeout)
                ->getReturnValue($baseTimeout);

            return $result ?: [];
        } catch (Exception $e) {
            try {
                $result = $this->page->callFunction($jsScript, [(object)$params])
                    ->waitForResponse($fastRetryTimeout)
                    ->getReturnValue($fastRetryTimeout);

                return $result ?: [];
            } catch (Exception $e2) {
                Logger::instance()->error('evaluateScript failed', [
                    'function' => is_string($jsScript) ? $jsScript : '<function>',
                    'error' => $e2->getMessage()
                ]);

                return ['error' => $e2->getMessage()];
            }
        }
    }

    private function getScriptBody($jsScript): string
    {
        if (!file_exists($this->scriptPath."/".$jsScript)) {
            throw new Exception($this->scriptPath."/".$jsScript." not found.");
        }

        $code = file_get_contents($this->scriptPath."/".$jsScript);

        return $code;
    }

    private function waitForNode(string $selector, int $timeoutMs = 10000, int $intervalMs = 150): bool
    {
        $start = microtime(true);
        do {
            try {
                $exists = $this->page->callFunction(
                    "function (selector) {
                        return !!document.querySelector(selector);
                    }",
                    [$selector]
                )->waitForResponse(2000)->getReturnValue(2000);

                if ($exists) {
                    return true;
                }
            } catch (Exception $e) {
                Logger::instance()->warning("waitForNode: ".$e->getMessage(), [$selector]);
            }
            usleep($intervalMs * 1000);
        } while ((microtime(true) - $start) * 1000 < $timeoutMs);

        return false;
    }

    private function scrollIntoViewAndGetCenter(string $selector): ?array
    {
        try {
            $rect = $this->page->callFunction(
                "function (selector) {
                    var el = document.querySelector(selector);
                    if (!el) return null;
                    try { el.scrollIntoView({block: 'center', inline: 'center'}); } catch(e) {}
                    var r = el.getBoundingClientRect();
                    var x = Math.floor(r.left + (r.width || 1) / 2);
                    var y = Math.floor(r.top + (r.height || 1) / 2);
                    return {x: x, y: y};
                }",
                [$selector]
            )->waitForResponse(5000)->getReturnValue(5000);

            if (is_array($rect) && isset($rect['x'], $rect['y'])) {
                return $rect;
            }
        } catch (Exception $e) {
            Logger::instance()->info("scrollIntoViewAndGetCenter: ".$e->getMessage(), [$selector]);
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function triggerEvent($eventNameMethod, $elementSelector, $params = []): bool
    {
        try {
            if (!$this->waitForNode($elementSelector, 15000)) {
                throw new Exception("Element not found by selector: {$elementSelector}");
            }

            $center = $this->scrollIntoViewAndGetCenter($elementSelector);

            switch ($eventNameMethod) {
                case 'hover':
                    $this->page->screenshot()->saveToFile('/project/var/cache/page.jpg');

                    if ($center) {
                        $this->page->mouse()->move($center['x'], $center['y']);
                    } else {
                        $pos = $this->page->mouse()->find($elementSelector, 0)->getPosition();
                        $this->page->mouse()->move($pos['x'], $pos['y']);
                    }

                    usleep(200 * 1000);
                    $this->page->screenshot()->saveToFile('/project/var/cache/page_af.jpg');
                    break;

                case 'click':
                    if ($center) {
                        $this->page->mouse()->move($center['x'], $center['y']);
                        usleep(50 * 1000);
                        $this->page->mouse()->click();
                    } else {
                        $this->page->mouse()->find($elementSelector)->click();
                    }
                    break;
            }
        } catch (Exception $e) {
            Logger::instance()->critical("triggerEvent: ".$e->getMessage(), [$eventNameMethod,$elementSelector, $params]);

            return false; // element not found
        }
        return true; // element found
    }

    public function getPageScreen($prefix = ''): void
    {
        $this->page->screenshot()->saveToFile('/project/var/cache/pageScreen'. $prefix .'.jpg');
    }

    public function extractHover($selector): void
    {
        if ($this->triggerEvent('hover', $selector)) {
            $this->evaluateScript('brizy.globalExtractor', []);
            $this->triggerEvent('hover', 'html');
        }
    }

    public function extractHoverMenu($selector): void
    {
        if ($this->triggerEvent('hover', $selector)) {
            $this->evaluateScript('brizy.globalMenuExtractor', []);
            $this->triggerEvent('hover', 'html');
        }
    }

    public function hasNode($selector)
    {
        try {
            $result = $this->callFunctionWithRetry(
                "function (selector) {\n           return document.querySelector(selector) !== null;\n        }\n        ",
                [
                    'selector' => $selector,
                ],
                7000,
                3000
            );
        } catch (Exception $e) {
            Logger::instance()->warning("hasNode timeout: ".$e->getMessage(), [$selector]);
            return false;
        }

        return $result;
    }

    public function getNodeText($selector)
    {
        try {
            $result = $this->callFunctionWithRetry(
                "function (selector) {\n            var element = document.querySelector(selector);\n            if (element) {\n               return element.textContent;\n            }\n            return null;\n        }\n        ",
                [
                    'selector' => $selector,
                ],
                8000,
                3000
            );
        } catch (Exception $e) {
            Logger::instance()->warning("getNodeText timeout: ".$e->getMessage(), [$selector]);
            return null;
        }

        return $result;
    }

    public function setNodeStyles($selector, array $attributes)
    {
        try {
            $this->callFunctionWithRetry(
                "function (selector, attributes) {\n            var element = document.querySelector(selector);\n            if (element) {\n                for (var key in attributes) {\n                        element.style[key] = attributes[key];\n                }\n            }\n        }\n        ",
                [
                    $selector,
                    $attributes,
                ],
                7000,
                3000
            );
        } catch (Exception $e) {
            Logger::instance()->warning("setNodeStyles timeout: ".$e->getMessage(), [$selector, $attributes]);
        }
    }

    public function setNodeAttribute($selector, array $attributes)
    {
        try {
            $this->callFunctionWithRetry(
                "function (selector, attributes) {\n            var element = document.querySelector(selector);\n            if (element) {\n                for (var key in attributes) {\n                    element.setAttribute(key,attributes[key]);\n                }\n            }\n        }",
            [
                $selector,
                $attributes,
            ],
            7000,
            3000
        );
        } catch (Exception $e) {
            Logger::instance()->warning("setNodeAttribute timeout: ".$e->getMessage(), [$selector, $attributes]);
        }
    }

    public function screenshot($path)
    {
        $screenshot = $this->page->screenshot([
            //'captureBeyondViewport' => true,
            'clip' => $this->page->getFullPageClip(),
            'format' => 'jpeg',
        ]);

        $screenshot->saveToFile($path);
    }

    public function getCurrentUrl(): string
    {
        return $this->page->getCurrentUrl();
    }

}
