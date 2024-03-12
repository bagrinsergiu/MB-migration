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
                    $pos = $this->page->mouse()->find($elementSelector)->getPosition();
                    $this->page->mouse()->move($pos['x'], $pos['y']);
                    break;
                case 'click':
                    $this->page->mouse()->find($elementSelector)->click();
                    break;
            }
        } catch (Exception $e) {
            Logger::instance()->critical("triggerEvent: ".$e->getMessage(), [$eventNameMethod,$elementSelector, $params]);

            return false; // element not found
        }
        usleep(500000);
        return true; // element found
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