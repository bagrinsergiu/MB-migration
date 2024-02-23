<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
use MBMigration\Builder\Layout\Common\Concern\ImageStylesAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class PhotoTextElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DanationsAble;
    use ImageStylesAble;

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $showHeader = $mbSection['settings']['sections']['text']['show_header'] ?? true;
        $showSecondaryHeader = $mbSection['settings']['sections']['text']['show_secondary_header'] ?? true;
        $showBody = $mbSection['settings']['sections']['text']['show_body'] ?? true;

        foreach ((array)$mbSection['items'] as $mbSectionItem) {
            switch ($mbSectionItem['category']) {
                case 'photo':
                    // add the photo items on the right side of the block
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbSectionItem,
                        $imageTarget = $this->getImageComponent($brizySection)
                    );
                    $this->handleRichTextItem(
                        $elementContext,
                        $this->browserPage
                    );

                    $imageStyles = $this->obtainImageStyles($elementContext,$this->browserPage);

                    $imageTarget
                        ->getValue()
                        ->set_width((int)$imageStyles['width'])
                        ->set_height((int)$imageStyles['height'])
                        ->set_heightSuffix((strpos($imageStyles['height'],'%')===true)?'%':'pix')
                        ->set_widthSuffix((strpos($imageStyles['width'],'%')===true)?'%':'pix');
                    break;
                case 'text':

                    if($mbSectionItem['item_type']=='title' && !$showHeader) continue 2;
                    if($mbSectionItem['item_type']=='secondary_title' && !$showSecondaryHeader) continue 2;
                    if($mbSectionItem['item_type']=='body' && !$showBody) continue 2;

                    // add the text on the left side of th bock
                    $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                        $mbSectionItem,
                        $this->getTextComponent($brizySection)
                    );
                    $this->handleRichTextItem(
                        $elementContext,
                        $this->browserPage
                    );
                    break;
            }
        }

        $elementContext = $data->instanceWithBrizyComponent($this->getTextComponent($brizySection));
        $this->handleDonations($elementContext, $this->browserPage, $this->brizyKit);

        $elementContext = $data->instanceWithBrizyComponent($this->getSectionItemComponent($brizySection));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        return $brizySection;
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    abstract protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent;

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    abstract protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent;

}