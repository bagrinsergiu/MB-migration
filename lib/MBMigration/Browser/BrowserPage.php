<?php

namespace MBMigration\Browser;

use MBMigration\Core\Utils;
use Nesk\Puphpeteer\Puppeteer;

class BrowserPage implements BrowserPageInterface
{
    private $page;
    private $scriptPath;

    public function __construct($page, $scriptPath)
    {
        $this->page = $page;
        $this->scriptPath = $scriptPath;
    }

    public function runScript($jsScript, $params): array
    {
        $prepareScript = $this->prepareScript($jsScript, $params);
        $result = $this->page->evaluateHandle($prepareScript);
        $json = $result->jsonValue();
        return json_decode($json, true);
    }

    private function prepareScript($jsScript, $params)
    {
        $code = file_get_contents($this->scriptPath."/".$jsScript);

        $search = array_map(function ($key) {
            return "{{".$key."}}";
        }, array_keys($params));

        $replace = array_values($params);

        $code = str_replace($search, $replace, $code);

        return $code;
    }

}