<?php

namespace MBMigration\Builder\Layout\Theme\Voyage\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementDataInterface;

class FullText extends AbstractElement
{
    public function transformToItem(ElementDataInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySectionLine = new BrizyComponent(json_decode($this->brizyKit['line'], true));

        foreach ($mbSection['items'] as $mbSectionItem) {

            switch ($mbSectionItem['category']) {
                case 'text':
                    $this->handleTextItem($mbSectionItem, $brizySection);
                    break;
                case 'photo':
                    $this->handlePhotoItem($mbSectionItem, $brizySection);
                    break;

            }
        }

        return $brizySection;
    }

    private function handleTextItem($mbSectionItem, $brizySection)
    {
        if ($this->canShowHeader($mbSectionItem) && $mbSectionItem['item_type'] === 'title') {

            $richTextBrowserData = $this->browserPage->runScript('richText.js', [
                'selector' => '[data-id="'.$mbSectionItem['id'].'"]',
                'attributes' => json_encode([
                    "font-size",
                    "font-family",
                    "font-weight",
                    "text-align",
                    "letter-spacing",
                    "text-transform",
                ]),
                //'families' => $fontFamilies,
                'defaultFontFamily' => 'helvetica_neue_helveticaneue_helvetica_arial_sans-serif',
            ]);

        }


        if ($this->canShowBody($mbSectionItem)) {

        }
    }

    private function handlePhotoItem($mbSectionItem, $brizySection)
    {

    }


    private function getPattingStyles($sectionId): array
    {
        return [];
    }

    private function getBackgroundStyles($sectionId): array
    {
        return [];
    }

    private function getBackgroundImages($sectionId): array
    {
        return [];
    }

    private function getBackgroundVideo($sectionId): array
    {
        return [];
    }
}