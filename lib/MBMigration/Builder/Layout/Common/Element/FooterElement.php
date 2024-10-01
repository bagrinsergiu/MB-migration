<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Layer\Brizy\BrizyAPI;

abstract class FooterElement extends AbstractElement
{
    const CACHE_KEY = 'footer';
    use Cacheable;
    use RichTextAble;
    use SectionStylesAble;

    protected BrizyAPI $brizyAPIClient;

    public function __construct($brizyKit, BrowserPageInterface $browserPage, BrizyAPI $brizyAPI)
    {
        parent::__construct($brizyKit, $browserPage);

        $this->brizyAPIClient = $brizyAPI;
    }

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        return $this->getCache(self::CACHE_KEY, function () use ($data): BrizyComponent {
            $this->beforeTransformToItem($data);
            $component = $this->internalTransformToItem($data);
            $this->afterTransformToItem($component);

            $position = '{"align":"bottom","top":1,"bottom":1}';
            $rules = '[{"type":1,"appliedFor":null,"entityType":"","entityValues":[]}]';

            $this->brizyAPIClient->createGlobalBlock(json_encode($component), $position, $rules);

            return $component;
        });
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);

        $this->handleRichTextItems($elementContext, $this->browserPage);
        $this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

        return $brizySection;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "margin-left" => 0,
            "margin-right" => 0,
        ];
    }

}
