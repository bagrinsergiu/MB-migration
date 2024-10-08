<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait ImageStylesAble
{
    protected function obtainImageStyles(ElementContextInterface $data, BrowserPageInterface $browserPage): array
    {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();
        $this->browserPage = $browserPage;

        $selector = '[data-id="'.($mbSectionItem['sectionId'] ?? $mbSectionItem['id']).'"] .photo-container img';

        $styleProperties = [
            'width',
            'height',
        ];

        return $this->getObtainStyles($selector, $styleProperties, $families, $defaultFont);
    }

    protected function obtainItemImageStyles(int $itemId, BrowserPageInterface $browserPage): array
    {
        $this->browserPage = $browserPage;

        $selector = '[data-id="'.$itemId.'"] .photo-container img';

        $styleProperties = [
            'width',
            'height',
        ];

        return $this->getObtainStyles($selector, $styleProperties);
    }

    private function getObtainStyles($selector, $styleProperties = [], $families = [], $defaultFont=[]): array
    {
        $browserPage = $this->browserPage;

        $imageStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector,
                'styleProperties' => $styleProperties,
                'families' => $families,
                'defaultFamily' => $defaultFont,
            ]
        );

        if (isset($imageStyles['error'])) {
            throw new BrowserScriptException($imageStyles['error']);
        }

        if (empty($imageStyles)) {
            throw new BrowserScriptException(
                "The element with selector {$selector} was not found in page."
            );
        }


        return $imageStyles['data'];
    }
}
