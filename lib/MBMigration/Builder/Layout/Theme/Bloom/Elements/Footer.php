<?php

namespace MBMigration\Builder\Layout\Theme\Bloom\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyComponentValue;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContext;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class Footer extends AbstractElement
{
    const CACHE_KEY = 'footer';
    use Cacheable;
    use RichTextAble;
    use SectionStylesAble;

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        return $this->getCache(self::CACHE_KEY, function () use ($data): BrizyComponent {

            $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
            $brizySection->getItemValueWithDepth(0, 0)->set_items([]);

            $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));

            $this->handleRichTextItems($elementContext, $this->browserPage);
            $this->handleSectionStyles($elementContext, $this->browserPage);

            return $brizySection;
        });
    }

}