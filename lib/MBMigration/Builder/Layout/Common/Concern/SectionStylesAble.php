<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;

trait SectionStylesAble
{
    protected function obtainSectionStyles(ElementContextInterface $data, BrowserPageInterface $browserPage): array
    {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();
        $selector = '[data-id="'.($mbSectionItem['sectionId'] ?? $mbSectionItem['id']).'"]';
        $properties = [
            'background-color',
            'background-size',
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
        ];

        return $this->getDomElementStyles($selector, $properties, $browserPage, $families, $defaultFont);

    }

    protected function handleSectionStyles(ElementContextInterface $data, BrowserPageInterface $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();
        $brizySection = $data->getBrizySection();
        $pagePosition = $mbSectionItem['settings']['pagePosition'] ?? null;


        $sectionStyles = $this->getSectionStyles(
            $mbSectionItem['sectionId'],
            $browserPage,
            $families,
            $defaultFont
        );

        $sectionWrapperStyles = $this->getSectionWrapperStyles(
            $mbSectionItem['sectionId'],
            $browserPage,
            $families,
            $defaultFont
        );
//        $bodyStyles = $this->getBodyStyles(
//            $browserPage,
//            $families,
//            $defaultFont
//        );



//        if (isset($mbSectionItem['settings']['sections']['background'])) {
//            if (isset($mbSectionItem['settings']['sections']['background']['opacity'])) {
//                $sectionStyles['data']['opacity'] = $mbSectionItem['settings']['sections']['background']['opacity'];
//            } else {
//                $resultingSectionStyles = $browserPage->evaluateScript(
//                    'StyleExtractor.js',
//                    [
//                        'selector' => '[data-id="'.$mbSectionItem['sectionId'].'"] .bg-opacity',
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

        //$this->handleSectionBackground($brizySection, $mbSectionItem, $bodyStyles);
        $this->handleSectionBackground($brizySection, $mbSectionItem, $sectionStyles);

        // reset padding top for first section as in brizy there is no need for that padding.
        // In Voyage our fixed heared adds space
        if (!is_null($pagePosition) && $pagePosition == 0) {
            $sectionStyles['padding-top'] = 0;
        }

        // set the background color paddings and margins
        $brizySection->getValue()
            //->set_paddingType('ungrouped')
            //->set_marginType('ungrouped')
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
        $opacity = ColorConverter::rgba2opacity($sectionStyles['background-color']);
        $opacity = NumberProcessor::convertToNumeric($opacity);

        //if((float)$opacity===0.) return;

        $brizySection->getValue()
            ->set_bgColorHex($backgroundColorHex)
            ->set_bgColorPalette('')
            ->set_bgColorType('solid')
            ->set_bgColorOpacity($opacity);

        // try to set the image background
        if ($this->hasImageBackground($mbSectionItem)) {
            $background = $mbSectionItem['settings']['sections']['background'];
            if (isset($background['filename']) && isset($background['photo'])) {
                $brizySection->getValue()
                    ->set_bgImageFileName($background['filename'])
                    ->set_bgImageSrc($background['photo'])
                    ->set_bgSize($sectionStyles['background-size'])
                    ->set_bgColorOpacity(1 - NumberProcessor::convertToNumeric($background['opacity']))
                    ->set_bgColorHex($backgroundColorHex);
            }
        }

        // try to set the video background
        if ($this->hasVideoBackground($mbSectionItem)) {
            $background = $mbSectionItem['settings']['sections']['background'];
            $brizySection->getValue()
                ->set_media('video')
                ->set_bgVideoType('url')
                ->set_bgColorOpacity(1-NumberProcessor::convertToNumeric($background['opacity']))
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

    /**
     * @param $sectionId
     * @param array $properties
     * @param BrowserPageInterface $browserPage
     * @param array $families
     * @param string $defaultFont
     * @return mixed
     */
    protected function getSectionStyles(
        $sectionId,
        BrowserPageInterface $browserPage,
        array $families,
        string $defaultFont
    ) {

        $properties = [
            'color',
            'background-size',
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
            'height',
            'position',
        ];
        $selectorSectionStyles = '[data-id="'.$sectionId.'"]';

        return $this->getDomElementStyles(
            $selectorSectionStyles,
            $properties,
            $browserPage,
            $families,
            $defaultFont
        );
    }

    /**
     * @param $sectionId
     * @param array $properties
     * @param BrowserPage $browserPage
     * @param array $families
     * @param string $defaultFont
     * @return mixed
     */
    protected function getSectionWrapperStyles(
        $sectionId,
        BrowserPageInterface $browserPage,
        array $families,
        string $defaultFont
    ) {
        $selectorSectionWrapperStyles = '[data-id="'.$sectionId.'"] .content-wrapper';
        $properties = [
            'padding-top',
            'padding-bottom',
            'padding-right',
            'padding-left',
            'margin-top',
            'margin-bottom',
            'margin-left',
            'margin-right',
            'height',
        ];

        return $this->getDomElementStyles(
            $selectorSectionWrapperStyles,
            $properties,
            $browserPage,
            $families,
            $defaultFont
        );
    }

    private function getBodyStyles($browserPage, $families, $defaultFont)
    {
        static $styles = null;

        if ($styles) {
            return $styles;
        }

        $selector = 'body';
        $properties = [
            'color',
            'background-size',
            'background-color',
            'background-image',
        ];

        return $this->getDomElementStyles(
            $selector,
            $properties,
            $browserPage,
            $families,
            $defaultFont
        );
    }
}