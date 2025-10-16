<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Elements\Text\PhotoTextElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class
LeftMediaDiamond extends PhotoTextElement
{
    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0, 0,0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 1);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));

        $showHeader = $mbSection['settings']['sections']['text']['show_header'] ?? true;
        $showSecondaryHeader = $mbSection['settings']['sections']['text']['show_secondary_header'] ?? true;
        $showBody = $mbSection['settings']['sections']['text']['show_body'] ?? true;

        foreach ((array) $mbSection['typeSection'] as $typeSection) {
            $brizySectionElem = (array) $mbSection['items'];

            if ($typeSection == 'left-gallery') {
                $brizySectionElem = (array) $mbSection['gallery']['items'];
            }

            foreach ($brizySectionElem as $mbSectionItem) {
                switch ($mbSectionItem['category']) {
                    case 'photo':
                        // add the photo items on the right side of the block
                        $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                            $mbSectionItem,
                            $imageTarget = $this->getImageComponent($brizySection)
                        );
                        $this->handleRichTextItem(
                            $elementContext,
                            $this->browserPage,
                            null,
                            [],
                            [
                                'customCSS' => $this->getCustomCss(),
                                'maskShape' => 'custom'
                            ]
                        );

                        $imageStyles = $this->obtainImageStyles($elementContext, $this->browserPage);

                        $this->targetImageSize($imageTarget, (int) $imageStyles['width'], (int) $imageStyles['height']);

                        break;
                }
            }
        }

        foreach ((array) $mbSection['items'] as $mbSectionItem) {
            switch ($mbSectionItem['category']) {
                case 'text':

                    if ($mbSectionItem['item_type'] == 'title' && !$showHeader) {
                        continue 2;
                    }
                    if ($mbSectionItem['item_type'] == 'secondary_title' && !$showSecondaryHeader) {
                        continue 2;
                    }
                    if ($mbSectionItem['item_type'] == 'body' && !$showBody) {
                        continue 2;
                    }

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

            "containerSize" => 150,
            "containerSizeSuffix" => "%",
            "containerType"=> "boxed"
        ];
    }

    private function getCustomCss(): string
    {
        return "element:has(.brz-ed-image__wrapper) .brz-ed-image__wrapper{
  mask-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMDAgMjAwIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0ibTEwMCAxMCA5MCA5MC05MCA5MC05MC05MHoiLz48L3N2Zz4=') !important;
}

element:not(:has(.brz-ed-image__wrapper)) picture{
  mask-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMDAgMjAwIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCI+PHBhdGggZD0ibTEwMCAxMCA5MCA5MC05MCA5MC05MC05MHoiLz48L3N2Zz4=') !important;
}";
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 75;
    }

}
