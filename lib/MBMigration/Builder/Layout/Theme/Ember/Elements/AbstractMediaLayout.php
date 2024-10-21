<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyMinistryBrandsSermonLayout;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class AbstractMediaLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use BrizyQueryBuilderAware;


    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mainBrizySection = $this->getSectionItemComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($mainBrizySection->getItemWithDepth(0, 0));

        $this->handleRichTextHeadFromItems(
            $elementContext,
            $this->browserPage,
            function (ElementContextInterface $itemElementContext) use ($data, $mainBrizySection) {
                $mbSectionItem = $itemElementContext->getMbSection();

                if ($mbSectionItem['category'] == 'media') {
                    $mainBrizySection->getItemWithDepth(1, 0, 0)->getValue()->add_items(
                        [new BrizyMinistryBrandsSermonLayout($data->getThemeContext()->getBrizyCollectionItemURI())]
                    );
                } else {
                    $this->handleRichTextItem(
                        $itemElementContext,
                        $this->browserPage
                    );
                }
            }
        );

        // sections styles
        $elementContext = $data->instanceWithBrizyComponent($mainBrizySection);
        $this->handleSectionStyles($elementContext, $this->browserPage);

        return $brizySection;
    }


}
