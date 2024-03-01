<?php

namespace MBMigration\Browser;

use Exception;
use MBMigration\Core\Utils;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;
use Nesk\Rialto\Exceptions\Node\FatalException;

class BrowserPage implements BrowserPageInterface
{
    private $page;
    private $scriptPath;

    public function __construct($page, $scriptPath)
    {
        $this->page = $page;
        $this->scriptPath = $scriptPath;
    }

    public function evaluateScript($jsScript, $params): array
    {
        try {
            $jsFunction = JsFunction::createWithScope($params)
                ->parameters(array_keys($params))
                ->body($this->getScriptBody($jsScript));

            $result = $this->page->tryCatch->evaluate($jsFunction);

            // for the case when the script does not return anything
            if(!$result) $result = [];

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

        $code = file_get_contents($this->scriptPath."/".$jsScript)." return output.default; ";

        return $code;
    }

    /**
     * @return mixed
     */
    public function triggerEvent($eventNameMethod, $elementSelector): bool
    {
        try {
//            $result = $this->page->tryCatch->waitForSelector($elementSelector, ['timeout' => 60000]);
//            $this->page->tryCatch->waitForTimeout(60000);
            $this->page->tryCatch->$eventNameMethod($elementSelector);
            usleep(200000);
        } catch (Exception $e) {
            return false; // element not found
        }

        return true; // element found
    }

    public function ExtractHover($selector): void
    {
        if ($this->triggerEvent('hover', $selector)) {
            $this->globalsEval();
        }
    }

    public function ExtractHoverMenu($selector): void
    {
        if ($this->triggerEvent('hover', $selector)) {
            $this->globalMenuEval();
        }
    }

    public function globalsEval(): void
    {
        $this->executeScriptWithoutResult('Globals.js');
        $this->triggerEvent('hover', 'html');
    }

    public function globalMenuEval(): void
    {
        $this->executeScriptWithoutResult('GlobalMenu.js');
        $this->triggerEvent('hover', 'html');
    }

    public function setNodeStyles($selector, array $attributes)
    {
        $this->evaluateScript("function (selector, attributes) {
            var element = document.querySelector(selector);
            if (element) {
                for (var key in attributes) {
                        element.style[key] = attributes[key];
                }
            }
        }
        ",
            [
                'selector' => $selector,
                'attributes' => $attributes,
            ]
        );

        $this->page->waitForSelector($selector);

        $this->page->screenshot(['path' =>  __DIR__ . 'testImage.png']);
    }

    public function setNodeAttribute($selector, array $attributes)
    {
        $this->evaluateScript("function (selector, attributes) {
            var element = document.querySelector(selector);
            if (element) {
                for (var key in attributes) {
                    element.setAttribute(key,attributes[key]);
                }
            }
        }",
            [
                'selector' => $selector,
                'attributes' => $attributes,
            ]
        );

        $this->page->waitForSelector($selector);

        $this->page->screenshot(['path' =>  __DIR__ . 'testImageAttr.png']);
    }
}