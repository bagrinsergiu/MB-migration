<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyComponentValue;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;

trait SectionStylesAble
{
    protected function obtainSectionStyles(ElementContextInterface $data, BrowserPage $browserPage): array
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

    protected function handleSectionStyles(ElementContextInterface $data, BrowserPage $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();
        $brizySection = $data->getBrizySection();
        $pagePosition = $mbSectionItem['settings']['pagePosition'] ?? null;

        $selectorSectionStyles = '[data-id="'.$mbSectionItem['sectionId'].'"]';
        $sectionStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => $selectorSectionStyles,
                'STYLE_PROPERTIES' => [
                    'color',
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
        $selectorSectionWrapperStyles = '[data-id="'.$mbSectionItem['sectionId'].'"]>.content-wrapper';
        $sectionWrapperStyles = $browserPage->evaluateScript(
            'StyleExtractor.js',
            [
                'SELECTOR' => $selectorSectionWrapperStyles,
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

//        if (isset($mbSectionItem['settings']['sections']['background'])) {
//            if (isset($mbSectionItem['settings']['sections']['background']['opacity'])) {
//                $sectionStyles['data']['opacity'] = $mbSectionItem['settings']['sections']['background']['opacity'];
//            } else {
//                $resultingSectionStyles = $browserPage->evaluateScript(
//                    'StyleExtractor.js',
//                    [
//                        'SELECTOR' => '[data-id="'.$mbSectionItem['sectionId'].'"] .bg-opacity',
//                        'STYLE_PROPERTIES' => [
//                            'opacity',
//                        ],
//                        'FAMILIES' => $families,
//                        'DEFAULT_FAMILY' => $defaultFont,
//                    ]
//                );
//                $sectionStyles['data']['opacity'] = $resultingSectionStyles['data']['opacity'];
//            }
//        }

        if (isset($sectionStyles['error'])) {
            throw new BrowserScriptException($sectionStyles['error']);
        }

        if (isset($sectionWrapperStyles['error'])) {
            throw new BrowserScriptException($sectionWrapperStyles['error']);
        }

        if (empty($sectionStyles)) {
            throw new BrowserScriptException(
                "The element with selector {$selectorSectionStyles} was not found in page."
            );
        }
        if (empty($sectionWrapperStyles)) {
            throw new BrowserScriptException(
                "The element with selector {$selectorSectionWrapperStyles} was not found in page."
            );
        }

        $sectionStyles = $sectionStyles['data'];
        $sectionWrapperStyles = $sectionWrapperStyles['data'];

        $this->handleSectionBackground($brizySection, $mbSectionItem, $sectionStyles);

        // reset padding top for first section as in brizy there is no need for that padding.
        if (!is_null($pagePosition) && $pagePosition == 0) {
            //$sectionStyles['padding-top'] = 0;
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
            ->set_marginBottom((int)$sectionStyles['margin-bottom'] + (int)$sectionWrapperStyles['margin-bottom']);

        return $brizySection;
    }

    private function hasImageBackground($mbSectionItem)
    {
        return isset($mbSectionItem['settings']['sections']['background']['photo']) && $mbSectionItem['settings']['sections']['background']['photo'] != '';
    }

    private function hasVideoBackground($mbSectionItem)
    {
        return isset($mbSectionItem['settings']['sections']['background']['video']) && $mbSectionItem['settings']['sections']['background']['video'] != '';
    }

    private function handleSectionBackground(BrizyComponent $brizySection, $mbSectionItem, $sectionStyles)
    {
        if ($brizySection->getType() == 'Section') {
            return;
        }

        $backgroundColorHex = ColorConverter::rgba2hex($sectionStyles['background-color']);
        $brizySection->getValue()
            ->set_bgColorHex($backgroundColorHex)
            ->set_bgColorPalette('')
            ->set_bgColorType('solid')
            ->set_bgColorOpacity(NumberProcessor::convertToNumeric($sectionStyles['opacity']));


        // try to set the image background
        if ($this->hasImageBackground($mbSectionItem)) {
            $background = $mbSectionItem['settings']['sections']['background'];
            if (isset($background['filename']) && isset($background['photo'])) {
                $brizySection->getValue()
                    ->set_bgImageFileName($background['filename'])
                    ->set_bgImageSrc($background['photo'])
                    ->set_bgSize('auto')
                    ->set_bgColorOpacity(0)
                    ->set_bgColorHex($backgroundColorHex);
            }
        }

        // try to set the video background
        if ($this->hasVideoBackground($mbSectionItem)) {
            $background = $mbSectionItem['settings']['sections']['background'];
            $brizySection->getValue()
                ->set_media('video')
                ->set_bgVideoType('url')
                ->set_bgVideo($background['video']);
        }

        if ($this->hasImageBackground($mbSectionItem) || $this->hasVideoBackground($mbSectionItem)) {
            $background = $mbSectionItem['settings']['sections']['background'];
            if (isset($background['photoOption'])) {
                switch ($background['photoOption']) {
                    case 'parallax-scroll':
                        $brizySection->getValue()->set_bgAttachment('animated');
                        break;
                    case 'parallax-fixed':
                        $brizySection->getValue()->set_bgAttachment('fixed');
                        break;
                    case 'fill':

                        break;
                }
            }
        }
    }
}