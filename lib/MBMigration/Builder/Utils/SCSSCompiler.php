<?php

namespace MBMigration\Builder\Utils;

use Leafo\ScssPhp\Compiler;

class SCSSCompiler
{

    private $scss;
    private $variables = [];

    public function __construct() {
        $this->scss = new Compiler();
    }

    public function setVariables($variables) {
        $this->variables = $variables;
        $this->scss->setVariables($variables);
    }

    public function compile($scssCode): string
    {
        return $this->scss->compile($scssCode);
    }

}