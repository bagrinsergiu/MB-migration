<?php

namespace MBMigration\Builder\Layout\Common\Template\DetailPages;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class EventDetailsPageLayout extends DetailsPage
{
    protected function filterDetailPageStyles($sectionProperties,$sectionPalette): array {
        return $sectionProperties;
    }
    protected function filterDetailPageStyles2($sectionProperties,$sectionPalette): array {
        return $sectionProperties;
    }

    public function setStyleDetailPage(array $sectionPalette): BrizyComponent
    {
        if(!empty(self::$cacheEvent))
        {
            return self::$cacheEvent;
        }

        $basicButtonStyleNormal = $this->pageTDO->getButtonStyle()->getNormal();
        $basicButtonStyleHover = $this->pageTDO->getButtonStyle()->getHover();

        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleNormal);
        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleHover);

        $detailsSection = $this->detailsSection;
        $colorTitle = ColorConverter::hex2Rgb($sectionPalette['btn-text'] ?? $basicButtonStyleNormal['color']);

        $richTextTitle = [
            'text' => '<p class="brz-text-lg-center brz-fss-lg-px brz-fw-lg-700 brz-ls-lg-0 brz-lh-lg-1_6 brz-vfw-lg-400 brz-fwdth-lg-100 brz-fsft-lg-0 brz-tp-lg-empty brz-ff-lato brz-ft-google brz-fs-lg-21" data-generated-css="brz-css-bLAMY" data-uniq-id="qQD1W"><span style="color: '.$colorTitle.';">Event Details</span></p>',
            'typographyFontStyle' => ''
        ];

        $wrapperItemTitle = [
            'bgColorHex' => $this->colorPalettes['subpalette1']['bg-accent'] ?? $sectionPalette['bg-accent'] ?? $sectionPalette['btn-bg'] ?? $basicButtonStyleNormal['background-color'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 1,
        ];

        $sectionStyle = [
            'bgColorHex' => $this->colorPalettes[$this->subpalette]['bg'] ?? $sectionPalette['bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 1,
        ];

        $sectionDescriptionStyle = [
            'bgColorHex' => $sectionPalette['bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 0,
        ];

        $sectionPlayerStyle = [
            "marginType" => "ungrouped",
            "margin" => 0,
            "marginSuffix" => "px",
            "marginTop" => 10,
            "marginTopSuffix" => "px",
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginBottom" => 0,
            "marginBottomSuffix" => "px",
            "marginLeft" => 0,
            "marginLeftSuffix" => "px",

            "mobileMarginType" => "ungrouped",
            "mobileMargin" => 0,
            "mobileMarginSuffix" => "px",
            "mobileMarginTop" => 10,
            "mobileMarginTopSuffix" => "px",
            "mobileMarginRight" => 0,
            "mobileMarginRightSuffix" => "px",
            "mobileMarginBottom" => 0,
            "mobileMarginBottomSuffix" => "px",
            "mobileMarginLeft" => 0,
            "mobileMarginLeftSuffix" => "px",
        ];

        $sectionProperties1 = [
            'showImage' =>'off',
            'showTitle' => 'on',
            'showDescription' => 'on',
            'showSubscribeToEvent' => 'on',
            'showPreviousPage' => 'off',
            'showCoordinatorPhone' => 'off',
            'showMetaIcons' => 'off',
            'showGroup' => 'off',
            'showDate' => 'off',
            'showCategory' => 'off',
            'showMetaHeadings' => 'off',
            'showLocation' => 'off',
            'showRoom' => 'off',
            'showCoordinator' => 'off',
            'showCoordinatorEmail' => 'off',
            'showCost' => 'off',
            'showWebsite' => 'off',
            'showRegistration' => 'off',

            'colorHex' => $sectionPalette['text'],
            'colorOpacity' => 1,
            'colorPalette' => '',

            'titleColorHex' => $sectionPalette['text'],
            'titleColorOpacity' => 3,
            'titleColorPalette' => '',

            'previewColorHex' => $sectionPalette['text'],
            'previewColorOpacity' => 1,
            'previewColorPalette' => '',

            'detailButtonColorHex' => $sectionPalette['btn-text'] ?? $basicButtonStyleNormal['color'],
            'detailButtonColorOpacity' => $basicButtonStyleNormal['color-opacity'] ?? 1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' => $sectionPalette['btn-text'] ?? $basicButtonStyleHover['background-color'],
            'hoverDetailButtonColorOpacity' => $basicButtonStyleHover['background-color-opacity'] ??  0.75,
            'hoverDetailButtonColorPalette' => '',

            'detailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $basicButtonStyleNormal['background-color'],
            'detailButtonBgColorOpacity' => $basicButtonStyleNormal['background-color-opacity'] ?? 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $basicButtonStyleHover['background-color'],
            'hoverDetailButtonBgColorOpacity' => $basicButtonStyleHover['background-color-opacity'] ?? 1,
            'hoverDetailButtonBgColorPalette' => '',

            'metaLinksColorHex' => $sectionPalette['link'],
            'metaLinksColorOpacity' => 1,
            'metaLinksColorPalette' => '',

            'hoverMetaLinksColorHex' => $sectionPalette['link'],
            'hoverMetaLinksColorOpacity' => 0.75,
            'hoverMetaLinksColorPalette' => '',

            'subscribeEventButtonColorHex' => $sectionPalette['btn-text'] ?? $basicButtonStyleNormal['color'] ,
            'subscribeEventButtonColorOpacity' => $basicButtonStyleNormal['color-opacity'] ?? 1,
            'subscribeEventButtonColorPalette' => '',

            'hoverSubscribeEventButtonColorHex' => $sectionPalette['btn-text'] ?? $basicButtonStyleHover['color'],
            'hoverSubscribeEventButtonColorOpacity' => $basicButtonStyleHover['color-opacity'] ?? 0.75,
            'hoverSubscribeEventButtonColorPalette' => '',

//            'subscribeEventButtonBorderStyle' => 'solid',
//            'subscribeEventButtonBorderColorHex' => $basicButtonStyleNormal['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
//            'subscribeEventButtonBorderColorOpacity' => $basicButtonStyleNormal['border-top-color-opacity'] ?? 1,
//            'subscribeEventButtonBorderColorPalette' => '',
//
//            "subscribeEventButtonBorderWidthType" => "grouped",
//            "subscribeEventButtonBorderWidth" => 1,
//            "subscribeEventButtonBorderTopWidth" => 1,
//            "subscribeEventButtonBorderRightWidth" => 1,
//            "subscribeEventButtonBorderBottomWidth" => 1,
//            "subscribeEventButtonBorderLeftWidth" => 1,

            'subscribeEventButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $basicButtonStyleNormal['background-color'] ,
            'subscribeEventButtonBgColorOpacity' => $basicButtonStyleNormal['background-color-opacity'] ?? 1,
            'subscribeEventButtonBgColorPalette' => '',

            'hoverSubscribeEventButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $basicButtonStyleHover['background-color'] ,
            'hoverSubscribeEventButtonBgColorOpacity' => $basicButtonStyleHover['background-color-opacity'] ?? 0.75,
            'hoverSubscribeEventButtonBgColorPalette' => '',
        ];

        $sectionProperties2Margin = [
            "marginType" => "ungrouped",
            "margin" => 0,
            "marginSuffix" => "px",
            "marginTop" => 10,
            "marginTopSuffix" => "px",
            "marginRight" => 20,
            "marginRightSuffix" => "px",
            "marginBottom" => 0,
            "marginBottomSuffix" => "px",
            "marginLeft" => 20,
            "marginLeftSuffix" => "px",
        ];

        $sectionProperties2 = [
            'showImage' =>'off',
            'showTitle' => 'off',
            'showDescription' => 'off',
            'showSubscribeToEvent' => 'off',
            'showPreviousPage' => 'on',
            'showCoordinatorPhone' => 'on',
            'showMetaIcons' => 'off',
            'showGroup' => 'on',
            'showDate' => 'on',
            'showCategory' => 'on',
            'showMetaHeadings' => 'on',
            'showLocation' => 'on',
            'showRoom' => 'on',
            'showCoordinator' => 'on',
            'showCoordinatorEmail' => 'on',
            'showCost' => 'on',
            'showWebsite' => 'on',
            'showRegistration' => 'on',

            'colorHex' => $sectionPalette['text'],
            'colorOpacity' => 1,
            'colorPalette' => '',

            'dateColorHex' => $sectionPalette['text'],
            'dateColorOpacity' => 1,
            'dateColorPalette' => '',

            'metaLinksColorHex' => $sectionPalette['link'],
            'metaLinksColorOpacity' => 1,
            'metaLinksColorPalette' => '',

            'hoverMetaLinksColorHex' => $sectionPalette['link'],
            'hoverMetaLinksColorOpacity' => 0.75,
            'hoverMetaLinksColorPalette' => '',

            'detailButtonColorHex' => $sectionPalette['btn-text'] ?? $basicButtonStyleNormal['color'],
            'detailButtonColorOpacity' => $basicButtonStyleNormal['color-opacity'] ?? 1,
            'detailButtonColorPalette' => '',

            'detailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $basicButtonStyleNormal['background-color'] ,
            'detailButtonBgColorOpacity' => $basicButtonStyleNormal['background-color-opacity'] ?? 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $basicButtonStyleHover['background-color'] ,
            'hoverDetailButtonBgColorOpacity' => $basicButtonStyleHover['background-color-opacity'] ?? 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'hoverDetailButtonColorHex' => $sectionPalette['btn-text'] ?? $basicButtonStyleHover['color'],
            'hoverDetailButtonColorOpacity' => $basicButtonStyleHover['color-opacity'] ?? 0.75,
            'hoverDetailButtonColorPalette' => '',

//            'detailButtonBorderStyle' => 'solid',
//            'detailButtonBorderColorHex' => $basicButtonStyleHover['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
//            'detailButtonBorderColorOpacity' => $basicButtonStyleHover['border-top-color-opacity'] ?? 1,
//            'detailButtonBorderColorPalette' => '',
//
//            "detailButtonBorderWidthType" => "grouped",
//            "detailButtonBorderWidth" => 1,
//            "detailButtonBorderTopWidth" => 1,
//            "detailButtonBorderRightWidth" => 1,
//            "detailButtonBorderBottomWidth" => 1,
//            "detailButtonBorderLeftWidth" => 1,

            'subscribeEventButtonColorHex' => $sectionPalette['btn-text'] ?? $basicButtonStyleNormal['color'] ,
            'subscribeEventButtonColorOpacity' => $basicButtonStyleNormal['color-opacity'] ?? 1,
            'subscribeEventButtonColorPalette' => '',

            'subscribeEventButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $basicButtonStyleNormal['background-color'],
            'subscribeEventButtonBgColorOpacity' => $basicButtonStyleNormal['background-color-opacity'] ?? 1,
            'subscribeEventButtonBgColorPalette' => '',

            'hoverSubscribeEventButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $basicButtonStyleHover['background-color'],
            'hoverSubscribeEventButtonBgColorOpacity' => $basicButtonStyleHover['background-color-opacity'] ?? 0.75,
            'hoverSubscribeEventButtonBgColorPalette' => '',

//            'subscribeEventButtonBorderStyle' => 'solid',
//            'subscribeEventButtonBorderColorHex' => $basicButtonStyleNormal['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
//            'subscribeEventButtonBorderColorOpacity' => $basicButtonStyleNormal['border-top-color-opacity'] ?? 1,
//            'subscribeEventButtonBorderColorPalette' => '',
//
//            "subscribeEventButtonBorderWidthType" => "grouped",
//            "subscribeEventButtonBorderWidth" => 1,
//            "subscribeEventButtonBorderTopWidth" => 1,
//            "subscribeEventButtonBorderRightWidth" => 1,
//            "subscribeEventButtonBorderBottomWidth" => 1,
//            "subscribeEventButtonBorderLeftWidth" => 1,

            "typographyBold" => false,
            "typographyItalic" => false,
            "typographyUnderline" => false,
            "typographyStrike" => false,
            "typographyUppercase" => false,
            "typographyLowercase" => false
        ];




        foreach ($sectionStyle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0)
                ->$properties($value);
        }

        foreach ($sectionPlayerStyle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 0)
                ->$properties($value);
        }
        $sectionProperties1 = $this->filterDetailPageStyles($sectionProperties1,$sectionPalette);
        foreach ($sectionProperties1 as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 0, 0, 0)
                ->$properties($value);
        }

        $detailsSection->getItemWithDepth(0, 1, 0, 0, 0)
            ->titleTypography()
            ->typography()
            ->dataTypography()
            ->previewTypography()
            ->subscribeEventButtonTypography();

        $detailsSection
            ->getItemWithDepth(0, 1)
            ->addPadding(15,0,0,0);

        foreach ($sectionProperties2Margin as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 1)
                ->$properties($value);
        }

        $sectionProperties2 = $this->filterDetailPageStyles2($sectionProperties2,$sectionPalette);
        foreach ($sectionProperties2 as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 1, 0)
                ->$properties($value);
        }

        $detailsSection->getItemWithDepth(0, 1, 1, 1, 0)
            ->dataTypography()
            ->typography(['lineHeight' => 1.7])
            ->dataTypography(['lineHeight' => 1.7])
            ->previewTypography(['lineHeight' => 1.7]);

        $detailsSection->getItemWithDepth(0, 1, 1)->addPadding(10,15,5,15);

        foreach ($wrapperItemTitle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 0, 0)
                ->$properties($value);
        }

        foreach ($richTextTitle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 0, 0, 0, 0)
                ->$properties($value);
        }

        foreach ($sectionDescriptionStyle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1)
                ->$properties($value);
        }

        $this->sectionPadding($detailsSection);

        self::$cacheEvent = $detailsSection;

        return $detailsSection;
    }

    private function sectionPadding(BrizyComponent $detailsSection){
        if($this->topPaddingOfTheFirstElement !== 0) {
            $options['paddingTop'] = $this->topPaddingOfTheFirstElement + 40;
        }

        if ($this->mobileTopPaddingOfTheFirstElement !== 0) {
            $options['mobilePaddingTop'] = $this->mobileTopPaddingOfTheFirstElement;
        }

        foreach ($options as $key => $value) {
            $method = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0)
                ->$method($value);
        }
    }
}
