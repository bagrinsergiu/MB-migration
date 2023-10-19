<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementDataInterface;
use MBMigration\Builder\Layout\Common\ElementInterface;

class Footer extends AbstractElement
{
    const CACHE_KEY = 'footer';
    use Cacheable;

    public function transformToItem(ElementDataInterface $data): array
    {
        return $this->getCache(self::CACHE_KEY, function (): array {
            $section = new ItemBuilder();
            $section->newItem($this->brizyKit['main']);

            return $section->get();
        });
    }
}