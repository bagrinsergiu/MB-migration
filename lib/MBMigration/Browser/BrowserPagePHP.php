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
        try {
            $result = $this->page->callFunction($jsScript, [(object)$params])
                ->waitForResponse(50000)
                ->getReturnValue(50000);

            if (!$result) {
                $result = [];
            }

            return $result;
        } catch (Exception $e) {
            Logger::instance()->critical("evaluateScript: ".$e->getMessage(), [$jsScript, $params]);

            return ['error' => $e->getMessage()]; // element not found
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
            $result = $this->page->callFunction("function (selector) {
           return document.querySelector(selector) !== null;
        }
        ",
                [
                    'selector' => $selector,
                ]
            )
                ->waitForResponse(50000)
                ->getReturnValue(50000);
        } catch (Exception $e) {
            Logger::instance()->critical("evaluateScript: ".$e->getMessage());

            return ['error' => $e->getMessage()]; // element not found
        }

        return $result;
    }

    public function getNodeText($selector)
    {
        try {
            $result = $this->page->callFunction("function (selector) {
            var element = document.querySelector(selector);
            if (element) {
               return element.textContent;
            }
            return null;
        }
        ",
                [
                    'selector' => $selector,
                ]
            )->waitForResponse(50000)
                ->getReturnValue(50000);
        } catch (Exception $e) {
            Logger::instance()->critical("evaluateScript: ".$e->getMessage());

            return ['error' => $e->getMessage()]; // element not found
        }

        return $result;
    }

    public function setNodeStyles($selector, array $attributes)
    {
        $this->page->callFunction(
            "function (selector, attributes) {
            var element = document.querySelector(selector);
            if (element) {
                for (var key in attributes) {
                        element.style[key] = attributes[key];
                }
            }
        }
        ",
            [
                $selector,
                $attributes,
            ]
        )->waitForResponse(50000);
    }

    public function setNodeAttribute($selector, array $attributes)
    {
        $this->page->callFunction(
            "function (selector, attributes) {
            var element = document.querySelector(selector);
            if (element) {
                for (var key in attributes) {
                    element.setAttribute(key,attributes[key]);
                }
            }
        }",
            [
                $selector,
                $attributes,
            ]
        )->waitForResponse(50000);
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

}
