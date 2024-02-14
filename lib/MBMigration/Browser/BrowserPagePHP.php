<?php

namespace MBMigration\Browser;

use Exception;
use HeadlessChromium\Page;
use MBMigration\Core\Utils;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;
use Nesk\Rialto\Exceptions\Node\FatalException;

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
        ])->waitForResponse();

    }

    public function evaluateScript($jsScript, $params): array
    {
        try {
            $result = $this->page->callFunction($jsScript, [(object)$params])->getReturnValue(1000);

            if (!$result) {
                $result = [];
            }

            return $result;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()]; // element not found
        }
    }

    private function executeScriptWithoutResult($jsScript): void
    {
        $jsFunction = JsFunction::createWithBody($this->getScriptBody($jsScript));
        $this->page->tryCatch->evaluate($jsFunction);
    }

    private function getScriptBody($jsScript): string
    {
        if (!file_exists($this->scriptPath."/".$jsScript)) {
            throw new \Exception($this->scriptPath."/".$jsScript." not found.");
        }

        $code = file_get_contents($this->scriptPath."/".$jsScript);

        return $code;
    }

    /**
     * @return mixed
     */
    public function triggerEvent($eventNameMethod, $elementSelector,$params=[]): bool
    {
        try {
            switch ($eventNameMethod) {
                case 'mouse.hover':
                    $this->page->mouse()->find($elementSelector);
                    break;
            }

        } catch (Exception $e) {
            return false; // element not found
        }

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
        )->waitForResponse(500);
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
        )->waitForResponse(500);
    }

}