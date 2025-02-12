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

    /**
     * @return mixed
     */
    public function triggerEvent($eventNameMethod, $elementSelector, $params = []): bool
    {
        try {
            switch ($eventNameMethod) {
                case 'hover':
                    $this->page->screenshot()->saveToFile('/project/var/cache/page.jpg');
//                    $this->page->mouse()->move(1, 1);
                    $pos = $this->page->mouse()->find($elementSelector, 0)->getPosition();
                    $this->page->mouse()->move($pos['x'], $pos['y']);
                    usleep(1000);
                    $this->page->screenshot()->saveToFile('/project/var/cache/page_af.jpg');
                    break;
                case 'click':
                    $this->page->mouse()->find($elementSelector)->click();
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
