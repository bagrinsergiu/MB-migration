<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyComponentValue;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;

trait ImageStylesAble
{
    protected function obtainImageStyles(ElementContextInterface $data, BrowserPage $browserPage): array
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