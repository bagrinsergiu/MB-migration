<?php

namespace MBMigration\Builder\Layout\Common\Concern;


use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Media\MediaController;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\NumberProcessor;
use Wrench\Exception\Exception;

trait SectionStylesAble
{
    protected function setTopPaddingOfTheFirstElement(
        ElementContextInterface $data,
        BrizyComponent $section,
        array $additionalOptions = [],
        int $additionalConstantHeight = 0,
        bool $mustBeAdded = false
    ): void {
        $mbSectionItem = $data->getMbSection();
        $headHeight = $data->getThemeContext()->getPageDTO()->getHeadStyle()->getHeight() + $additionalConstantHeight;
        $options = $additionalOptions;

        if (
            isset($headHeight) &&
            $headHeight > $this->getTopPaddingOfTheFirstElement() &&
            $this->getTopPaddingOfTheFirstElement() !== 0
        ) {
            $options['paddingTop'] = $headHeight;
        } else {
            if($this->getTopPaddingOfTheFirstElement() !== 0) {
                $options['paddingTop'] = $this->getTopPaddingOfTheFirstElement();
            }
        }

        if ($this->getMobileTopPaddingOfTheFirstElement() !== 0) {
            $options['mobilePaddingTop'] = $this->getMobileTopPaddingOfTheFirstElement();
        }

        if(
            $mustBeAdded ||
            (
                isset($mbSectionItem['position']) &&
                $mbSectionItem['position'] === 1
            )
        ) {
            foreach ($options as $key => $value) {
                $method = 'set_'.$key;
                $section->getValue()
                    ->$method($value);
            }
        }
    }

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

    protected function handleSectionStyles(
        ElementContextInterface $data,
        BrowserPageInterface $browserPage,
        $additionalOptions = []
    ): BrizyComponent {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $sectionStyles = $this->getSectionListStyle($data, $browserPage);

        if(!empty($additionalOptions['bg-gradient'])){
            $sectionStyles['bg-gradient'] = $additionalOptions['bg-gradient'];
        }

        $this->handleSectionBackground($brizySection, $mbSectionItem, $sectionStyles);

        // set the background color paddings and margins
        $brizySection->getValue()
            ->set_paddingType('ungrouped')
            ->set_marginType('ungrouped')
            ->set_paddingTop((int)$sectionStyles['padding-top'])
            ->set_paddingBottom((int)$sectionStyles['padding-bottom'])
            ->set_paddingRight((int)$sectionStyles['padding-right'])
            ->set_paddingLeft((int)$sectionStyles['padding-left'])
            ->set_marginLeft((int)$sectionStyles['margin-left'])
            ->set_marginRight((int)$sectionStyles['margin-right'])
            ->set_marginTop((int)$sectionStyles['margin-top'])
            ->set_marginBottom((int)$sectionStyles['margin-bottom'])

            ->set_mobilePaddingType('ungrouped')
            ->set_mobilePadding((int)$sectionStyles['margin-bottom'])
            ->set_mobilePaddingSuffix('px')
            ->set_mobilePaddingTop((int)$sectionStyles['margin-bottom'])
            ->set_mobilePaddingTopSuffix('px')
            ->set_mobilePaddingRight((int)$sectionStyles['margin-bottom'])
            ->set_mobilePaddingRightSuffix('px')
            ->set_mobilePaddingBottom((int)$sectionStyles['margin-bottom'])
            ->set_mobilePaddingBottomSuffix('px')
            ->set_mobilePaddingLeft((int)$sectionStyles['margin-bottom'])
            ->set_mobilePaddingLeftSuffix('px');

        foreach ($additionalOptions as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $method = 'set_'.$key;
            $brizySection->getValue()
                ->$method($value);
        }

        return $brizySection;
    }

    private function hasImageBackground($mbSectionItem): bool
    {
        return isset($mbSectionItem['settings']['sections']['background']['photo']) && $mbSectionItem['settings']['sections']['background']['photo'] != '';
    }

    private function hasVideoBackground($mbSectionItem): bool
    {
        return isset($mbSectionItem['settings']['sections']['background']['video']) && $mbSectionItem['settings']['sections']['background']['video'] != '';
    }

    private function handleSectionBackground(BrizyComponent $brizySection, $mbSectionItem, $sectionStyles)
    {
        if ($brizySection->getType() == 'Section') {
            return;
        }

        $sectionStyles['background-opacity'] = NumberProcessor::convertToNumeric(
            ColorConverter::rgba2opacity($sectionStyles['background-color'])
        );
        $sectionStyles['background-color'] = ColorConverter::rgba2hex($sectionStyles['background-color']);

        $this->handleItemBackground($brizySection, $sectionStyles);

        if ($this->hasImageBackground($mbSectionItem)) {
            $background = $mbSectionItem['settings']['sections']['background'];
            if (isset($background['filename']) && isset($background['photo'])) {
                $validatedUrl = MediaController::validateBgImag($sectionStyles['background-image']);
                $bgImg = $validatedUrl ? $validatedUrl : $background['photo'];

                //                if($background['opacity']>=0.9)
//                {
//                    $background['opacity'] = 0.8;
//                }

                $brizySection->getValue()
                    ->set_bgImageSrc($bgImg)
                    ->set_bgImageFileName($background['filename'])
                    ->set_bgSize($sectionStyles['background-size'])
                    ->set_bgColorOpacity(1 - NumberProcessor::convertToNumeric($background['opacity']))
                    ->set_bgColorHex($sectionStyles['background-color'])

                    ->set_mobileBgColorType('solid')
                    ->set_mobileBgColorHex($sectionStyles['background-color'])
                    ->set_mobileBgColorOpacity(1 - NumberProcessor::convertToNumeric($background['opacity']));

                $brizySection
                    ->getParent()
                    ->getValue()
                    ->set_sectionHeight(str_replace('px', '', $sectionStyles['height']) ?? 500)
                    ->set_fullHeight('custom');
            }
        } else if ($this->hasVideoBackground($mbSectionItem)) {
            $background = $mbSectionItem['settings']['sections']['background'];
            $brizySection->getValue()
                ->set_media('video')
                ->set_bgVideoType('url')
                ->set_bgColorOpacity(1 - NumberProcessor::convertToNumeric($background['opacity']))
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
                        $brizySection->getValue()->set_bgAttachment('none');
                        break;
                    case 'tile':
                        $brizySection->getValue()->set_bgRepeat('on');
                }
            }
        }
    }

    protected function handleItemBackground(BrizyComponent $brizySection, array $sectionStyles)
    {
        $backgroundColorHex = ColorConverter::rgba2hex($sectionStyles['background-color']);
        $opacity = ColorConverter::rgba2opacity($sectionStyles['background-opacity'] ?? $sectionStyles['background-color']);
        $opacity = NumberProcessor::convertToNumeric($opacity);

        $brizySection->getValue()
            ->set_bgColorHex($backgroundColorHex)
            ->set_bgColorPalette('')
            ->set_bgColorType('solid')
            ->set_bgColorOpacity($opacity)

            ->set_mobileBgColorType('solid')
            ->set_mobileBgColorHex($backgroundColorHex)
            ->set_mobileBgColorOpacity($opacity)
            ->set_mobileBgColorPalette('');

        $this->handleSectionGradient($brizySection, $sectionStyles);
    }

    public function handleSectionGradient(BrizyComponent $brizySection, $sectionStyles): void
    {
        if(!empty($sectionStyles['bg-gradient'])){

            $gradient = $sectionStyles['bg-gradient'];

            $brizySection->getValue()
                ->set_bgColorType('gradient')
                ->set_gradientType($gradient['type'])
                ->set_gradientLinearDegree($gradient['angleOrPosition'])
                ->set_bgColorHex($gradient['colors'][0]['color'])
                ->set_bgColorPalette('')
                ->set_bgColorOpacity(1)
                ->set_gradientStartPointer($gradient['colors'][0]['percentage'])
                ->set_gradientColorHex($gradient['colors'][1]['color'])
                ->set_gradientColorPalette('')
                ->set_gradientColorOpacity(1)
                ->set_gradientFinishPointer($gradient['colors'][1]['percentage'])

                ->set_mobileBgColorType('gradient')
                ->set_mobileGradientType($gradient['type'])
                ->set_mobileGradientLinearDegree($gradient['angleOrPosition'])
                ->set_mobileBgColorHex($gradient['colors'][0]['color'])
                ->set_mobileBgColorOpacity(1)
                ->set_mobileGradientStartPointer($gradient['colors'][0]['percentage'])
                ->set_mobileGradientColorHex($gradient['colors'][1]['color'])
                ->set_mobileGradientColorOpacity(1)
                ->set_mobileGradientFinishPointer($gradient['colors'][1]['percentage']);
        }
    }

    protected function getSectionListStyle(
        ElementContextInterface $data,
        BrowserPageInterface $browserPage
    ){
        $mbSectionItem = $data->getMbSection();
        $families = $data->getFontFamilies();
        $defaultFont = $data->getDefaultFontFamily();

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

        try {
            $sectionBgStyles = $this->getBgHelperStyles(
                $mbSectionItem['sectionId'],
                $browserPage,
                $families,
                $defaultFont
            );

            $sectionStyles = array_merge($sectionStyles, $sectionBgStyles);
        }
        catch (\Exception $e) {
        }

        return $sectionStyles;
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
            'background-image',
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


    protected function getSectionWrapperStyles(
        $sectionId,
        BrowserPageInterface $browserPage,
        array $families,
        string $defaultFont
    ) {
        $selectorSectionWrapperStyles = '[data-id="'.$sectionId.'"] .content-wrapper';
        $properties = [
        ];

        return $this->getDomElementStyles(
            $selectorSectionWrapperStyles,
            $properties,
            $browserPage,
            $families,
            $defaultFont
        );
    }

    protected function getBgHelperStyles(
        $sectionId,
        BrowserPageInterface $browserPage,
        array $families,
        string $defaultFont
    ) {
        $selectorSectionWrapperStyles = '[data-id="'.$sectionId.'"] .bg-helper>.bg-opacity';
        $properties = [
            'background-color',
            'opacity',
        ];

        return $this->getDomElementStyles(
            $selectorSectionWrapperStyles,
            $properties,
            $browserPage,
            $families,
            $defaultFont
        );
    }

}

