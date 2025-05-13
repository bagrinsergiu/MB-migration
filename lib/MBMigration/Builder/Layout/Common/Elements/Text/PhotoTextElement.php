<?php

namespace MBMigration\Builder\Layout\Common\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\ImageStylesAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

abstract class PhotoTextElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;
    use ImageStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
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

                    $imageStyles = $this->obtainImageStyles($elementContext, $this->browserPage);

                    $this->targetImageSize($imageTarget, (int)$imageStyles['width'], (int)$imageStyles['height']);

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
        $this->handleDonationsButton($elementContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $styleList = $this->getSectionListStyle($elementContext, $this->browserPage);

        $this->transformItem($elementContext, $sectionItemComponent, $styleList);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        return $brizySection;
    }


    public function targetImageSize(BrizyComponent $imageTarget, int $width, int $height){
        $imageTarget
            ->getValue()
            ->set_width($width)
            ->set_height($height)
            ->set_heightSuffix((strpos($height,'%')===true)?'%':'px')
            ->set_widthSuffix((strpos($width,'%')===true)?'%':'px');
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


    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",

            "paddingType" => "ungrouped",
            "paddingTop" => 20,
            "paddingTopSuffix" => "px",
            "paddingBottom" => 20,
            "paddingBottomSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",
        ];
    }

}
