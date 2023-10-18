<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyComponentValue;
use MBMigration\Builder\Layout\Common\ElementDataInterface;
use MBMigration\Builder\Utils\ColorConverter;

trait SectionStylesAble
{
    protected function handleSectionStyles(ElementDataInterface $data, BrowserPage $browserPage): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();
        $brizySection = $data->getBrizySection();

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
                    'margin-top',
                    'margin-bottom',
                ],
                'FAMILIES' => $families,
                'DEFAULT_FAMILY' => $defaultFont,
            ]
        );

        $sectionWrapperStyles = $browserPage->evaluateScript('StyleExtractor.js', [
            'SELECTOR' => '[data-id="'.$mbSectionItem['sectionId'].'"]>.content-wrapper',
            'STYLE_PROPERTIES' => [
                'padding-top',
                'padding-bottom',
                'margin-top',
                'margin-bottom',
            ],
            'FAMILIES' => [],
            'DEFAULT_FAMILY' => 'helvetica_neue_helveticaneue_helvetica_arial_sans-serif',
        ]);


        $sectionStyles = $sectionStyles['data'];
        $sectionWrapperStyles = $sectionWrapperStyles['data'];

        $backgroundColorHex = ColorConverter::rgba2hex($sectionStyles['background-color']);

        // try to set the image background
        if (isset($mbSectionItem['settings']['sections']['background'])) {
            $background = $mbSectionItem['settings']['sections']['background'];

            // image bg
            if (isset($background['filename']) && isset($background['photo'])) {
                $brizySection->getValue(0)
                    ->set_bgImageFileName($background['filename'])
                    ->set_bgImageSrc($background['photo']);
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

        // set the background color paddings and margins
        $brizySection->getValue()
            ->set_bgColorHex($backgroundColorHex)
            ->set_bgColorOpacity($sectionStyles['opacity'])
            ->set_bgColorType('none')
            ->set_paddingTop((int)$sectionStyles['padding-top'] + (int)$sectionWrapperStyles['padding-top'])
            ->set_paddingBottom((int)$sectionStyles['padding-bottom'] + (int)$sectionWrapperStyles['padding-bottom'])
            ->set_marginTop((int)$sectionWrapperStyles['margin-top'])
            ->set_marginBottom((int)$sectionWrapperStyles['margin-bottom'])
            ->set_bgColorPalette('');


        return $brizySection;
    }

    protected function deprecated_handleSectionStyles(
        $mbSectionItem,
        BrizyComponent $brizySection,
        BrowserPage $browserPage,
        $families = [],
        $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans'
    ): BrizyComponent {
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
                    'margin-top',
                    'margin-bottom',
                ],
                'FAMILIES' => $families,
                'DEFAULT_FAMILY' => $default_fonts,
            ]
        );

        $sectionWrapperStyles = $browserPage->evaluateScript('StyleExtractor.js', [
            'SELECTOR' => '[data-id="'.$mbSectionItem['sectionId'].'"]>.content-wrapper',
            'STYLE_PROPERTIES' => [
                'padding-top',
                'padding-bottom',
                'margin-top',
                'margin-bottom',
            ],
            'FAMILIES' => [],
            'DEFAULT_FAMILY' => 'helvetica_neue_helveticaneue_helvetica_arial_sans-serif',
        ]);

        $sectionStyles = $sectionStyles['data'];
        $sectionWrapperStyles = $sectionWrapperStyles['data'];

        $backgroundColorHex = ColorConverter::rgba2hex($sectionStyles['background-color']);

        // try to set the image background
        if (isset($mbSectionItem['settings']['sections']['background'])) {
            $background = $mbSectionItem['settings']['sections']['background'];

            // image bg
            if (isset($background['filename']) && isset($background['photo'])) {
                $brizySection->getValue(0)
                    ->set_bgImageFileName($background['filename'])
                    ->set_bgImageSrc($background['photo']);
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

        // set the background color paddings and margins
        $brizySection->getValue()
            ->set_bgColorHex($backgroundColorHex)
            ->set_bgColorOpacity($sectionStyles['opacity'])
            ->set_bgColorType('none')
            ->set_paddingTop((int)$sectionStyles['padding-top'] + (int)$sectionWrapperStyles['padding-top'])
            ->set_paddingBottom((int)$sectionStyles['padding-bottom'] + (int)$sectionWrapperStyles['padding-bottom'])
            ->set_marginTop((int)$sectionWrapperStyles['margin-top'])
            ->set_marginBottom((int)$sectionWrapperStyles['margin-bottom'])
            ->set_bgColorPalette('');


        return $brizySection;
    }
}