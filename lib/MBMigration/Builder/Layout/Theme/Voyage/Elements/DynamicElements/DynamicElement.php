<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements\DynamicElements;

use MBMigration\Builder\Layout\Theme\Voyage\Elements\Element;
use MBMigration\Builder\VariableCache;

abstract class DynamicElement extends Element implements DynamicElementInterface
{

    /**
     * @var VariableCache
     */
    protected $cache;
    protected $jsonDecode;
    /**
     * @var array
     */
    protected $elementOptions;

    /**
     * @throws \Exception
     */
    public function __construct(array $ElementOptions = [])
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $this->loadKit();
        $this->elementOptions = $ElementOptions;
    }

}