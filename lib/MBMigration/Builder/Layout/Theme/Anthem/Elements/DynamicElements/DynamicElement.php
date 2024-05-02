<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements\DynamicElements;

use Exception;
use MBMigration\Builder\Layout\Theme\Anthem\Elements\Element;
use MBMigration\Builder\VariableCache;

abstract class DynamicElement extends Element implements DynamicElementInterface
{
    protected $cache;
    protected $jsonDecode;
    protected array $elementOptions;
    /**
     * @var mixed
     */
    protected $jsonDecodeAnthem;

    /**
     * @throws Exception
     */
    public function __construct(array $ElementOptions = [])
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $this->loadKit();
        $this->jsonDecodeAnthem = $this->loadKit('Anthem');
        $this->elementOptions = $ElementOptions;
    }

}