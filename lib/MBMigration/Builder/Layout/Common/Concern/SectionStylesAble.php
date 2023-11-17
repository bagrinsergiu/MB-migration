<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyComponentValue;
use MBMigration\Builder\Layout\Common\ElementDataInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;

trait SectionStylesAble
{
    protected function obtainSectionStyles(ElementDataInterface $data, BrowserPage $browserPage): array
    {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();

        $sectionStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => '[data-id="'.($mbSectionItem['sectionId'] ?? $mbSectionItem['id']).'"]',
                'STYLE_PROPERTIES' => [
                    'background-color',
                    'opacity',
                    'border-bottom-color',
                    'padding-top',
                    'padding-bottom',
                    'padding-right',
                    'padding-left',
                    'margin-top',
                    'margin-bottom',
                    'margin-left',
                    'margin-right',
                ],
                'FAMILIES' => $families,
                'DEFAULT_FAMILY' => $defaultFont,
            ]
        );

        if (isset($sectionStyles['error'])) {
            throw new BrowserScriptException($sectionStyles['error']);
        }

        if (isset($sectionWrapperStyles['error'])) {
            throw new BrowserScriptException($sectionWrapperStyles['error']);
        }

        $sectionStyles = $sectionStyles['data'];

        return $sectionStyles;
    }

    protected function handleSectionStyles(ElementDataInterface $data, BrowserPage $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();
        $brizySection = $data->getBrizySection();
        $pagePosition = $mbSectionItem['settings']['pagePosition'] ?? null;

        $sectionStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => '[data-id="'.$mbSectionItem['sectionId'].'"]',
                'STYLE_PROPERTIES' => [
                    'background-color',
                    'opacity',
                    'border-bottom-color',
                    'padding-top',
                    'padding-bottom',
                    'padding-right',
                    'padding-left',
                    'margin-top',
                    'margin-bottom',
                    'margin-left',
                    'margin-right',
                ],
                'FAMILIES' => $families,
                'DEFAULT_FAMILY' => $defaultFont,
            ]
        );

        $sectionWrapperStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => '[data-id="'.$mbSectionItem['sectionId'].'"]>.content-wrapper',
                'STYLE_PROPERTIES' => [
                    'padding-top',
                    'padding-bottom',
                    'padding-right',
                    'padding-left',
                    'margin-top',
                    'margin-bottom',
                    'margin-left',
                    'margin-right',
                ],
                'FAMILIES' => $families,
                'DEFAULT_FAMILY' => $defaultFont,
            ]
        );

        if (array_key_exists('background', $mbSectionItem['settings']['sections'])) {
            $resultingSectionStyles = $browserPage->evaluateScript(
                'StyleExtractor.js',
                [
                    'SELECTOR' => '[data-id="'.$mbSectionItem['sectionId'].'"] .bg-opacity',
                    'STYLE_PROPERTIES' => [
                        'opacity',
                    ],
                    'FAMILIES' => $families,
                    'DEFAULT_FAMILY' => $defaultFont,
                ]
            );

            $sectionStyles['data']['opacity'] = $resultingSectionStyles['data']['opacity'];
        }

        if (isset($sectionStyles['error'])) {
            throw new BrowserScriptException($sectionStyles['error']);
        }

        if (isset($sectionWrapperStyles['error'])) {
            throw new BrowserScriptException($sectionWrapperStyles['error']);
        }

        $sectionStyles = $sectionStyles['data'];
        $sectionWrapperStyles = $sectionWrapperStyles['data'];

        $this->handleSectionBackground($brizySection, $mbSectionItem, $sectionStyles);

        // reset padding top for first section as in brizy there is no need for that padding.
        if (!is_null($pagePosition) && $pagePosition == 0) {
            $sectionStyles['padding-top'] = 0;
        }

        // set the background color paddings and margins
        $brizySection->getValue()
            ->set_paddingTop((int)$sectionStyles['padding-top'] + (int)$sectionWrapperStyles['padding-top'])
            ->set_paddingBottom((int)$sectionStyles['padding-bottom'] + (int)$sectionWrapperStyles['padding-bottom'])
            ->set_paddingRight((int)$sectionStyles['padding-right'] + (int)$sectionWrapperStyles['padding-right'])
            ->set_paddingLeft((int)$sectionStyles['padding-left'] + (int)$sectionWrapperStyles['padding-left'])
            ->set_marginLeft((int)$sectionStyles['margin-left'] + (int)$sectionWrapperStyles['margin-left'])
            ->set_marginRight((int)$sectionStyles['margin-right'] + (int)$sectionWrapperStyles['margin-right'])
            ->set_marginTop((int)$sectionStyles['margin-top'] + (int)$sectionWrapperStyles['margin-top'])
            ->set_marginBottom((int)$sectionStyles['margin-bottom'] + (int)$sectionWrapperStyles['margin-bottom'])
            ->set_bgColorOpacity(NumberProcessor::convertToNumeric($sectionStyles['opacity']))
            ->set_bgColorPalette('');

        return $brizySection;
    }

    private function hasImageBackground($mbSectionItem)
    {
        return isset($mbSectionItem['settings']['sections']['background']['photo']);
    }

    private function handleSectionBackground(BrizyComponent $brizySection, $mbSectionItem, $sectionStyles)
    {
        if ($brizySection->getType() == 'Section') {
            return;
        }

        $backgroundColorHex = ColorConverter::rgba2hex($sectionStyles['background-color']);
        $brizySection->getValue()
            ->set_bgColorHex($backgroundColorHex)
            ->set_bgColorType('solid')
            ->set_bgColorOpacity($sectionStyles['opacity']);

        // try to set the image background
        if ($this->hasImageBackground($mbSectionItem)) {
            $background = $mbSectionItem['settings']['sections']['background'];

            // image bg
            if (isset($background['filename']) && isset($background['photo'])) {
                $brizySection->getValue()
                    ->set_bgImageFileName($background['filename'])
                    ->set_bgImageSrc($background['photo'])
                    ->set_bgColorOpacity(0)
                    ->set_bgColorHex($backgroundColorHex);

                if (isset($background['photoOption'])) {
                    switch ($background['photoOption']) {
                        case 'parallax-scroll':
                            $brizySection->getValue(0)->set_bgAttachment('animated');
                            break;
                        case 'fill':

                            break;
                    }
                }
            }

            // video bg
            if (isset($background['video'])) {
                $videoBackground = $background['video'];
                $brizySection->getItemValueWithDepth(0)
                    ->set_media('video')
                    ->set_bgVideoType('url')
                    ->set_bgVideo($videoBackground);
            }
        }
    }
}