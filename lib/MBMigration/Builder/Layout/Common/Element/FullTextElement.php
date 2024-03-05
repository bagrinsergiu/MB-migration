<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class FullTextElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DanationsAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $textContainerComponent = $this->getTextContainerComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $this->handleSectionStyles($elementContext, $this->browserPage);

        $elementContext = $data->instanceWithBrizyComponent($textContainerComponent);
        $this->handleRichTextItems($elementContext, $this->browserPage);
        $this->handleDonations($elementContext, $this->browserPage, $this->brizyKit);

        // not sure if this must be there or in a concrete theme
        // the image in the bg is not always correctly fitted
        // $mbSectionItem = $data->getMbSection();
        // if ($this->hasImageBackground($mbSectionItem)) {
        //    $background = $mbSectionItem['settings']['sections']['background'];
        //    if (isset($background['filename']) && isset($background['photo'])) {
        //        $this->getSectionItemComponent($brizySection)->getValue()
        //            ->set_bgSize('auto');
        //    }
        // }

        return $brizySection;
    }

    abstract protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent;
}