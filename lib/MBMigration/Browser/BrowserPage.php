<?php

namespace MBMigration\Browser;

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
        $jsFunction = JsFunction::createWithScope($params)
            ->parameters(array_keys($params))
            ->body($this->getScriptBody($jsScript));

        $result = $this->page->tryCatch->evaluate($jsFunction);

        return $result;
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
    public function triggerEvent($eventNameMethod, $elementSelector): void
    {
        $method = '$';
        $this->page->tryCatch->$method($elementSelector)->$eventNameMethod();
    }
}

