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

        $selector = '[data-id="'.($mbSectionItem['sectionId'] ?? $mbSectionItem['id']).'"] .photo-container img';
        $imageStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector,
                'styleProperties' => [
                    'width',
                    'height',
                ],
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