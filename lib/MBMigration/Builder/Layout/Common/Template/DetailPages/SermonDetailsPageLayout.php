<?php

namespace MBMigration\Builder\Layout\Common\Template\DetailPages;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\DTO\PageDto;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Utils\ColorConverter;

class SermonDetailsPageLayout extends DetailsPage
{
    public function setStyleDetailPage(array $sectionPalette): BrizyComponent
    {
        if(!empty(self::$cacheSermons))
        {
            return self::$cacheSermons;
        }

        $detailsSection = $this->detailsSection;

        $basicButtonStyleNormal = $this->pageTDO->getButtonStyle()->getNormal();
        $basicButtonStyleHover = $this->pageTDO->getButtonStyle()->getHover();

        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleNormal);
        ColorConverter::rewriteColorIfSetOpacity($basicButtonStyleHover);

        $sectionPalette['text'] =  ColorConverter::rgba2hex($sectionPalette['text']);
        $sectionPalette['btn-text'] =  ColorConverter::rgba2hex($sectionPalette['btn-text']);

        $hexColorTitle = $sectionPalette['text'] ?? $sectionPalette['btn-text'];

        $colorTitle = ColorConverter::hex2Rgb($sectionPalette['btn-text'] ?? $basicButtonStyleNormal['color']);

        if($basicButtonStyleNormal['background-color-opacity'] == 0 ){
            $basicButtonStyleNormal['background-color-opacity'] = 0.5;
        }

        $richTextTitle = [
            'text' => '<p class="brz-text-lg-center brz-fss-lg-px brz-fw-lg-700 brz-ls-lg-0 brz-lh-lg-1_6 brz-vfw-lg-400 brz-fwdth-lg-100 brz-fsft-lg-0 brz-tp-lg-empty brz-ff-lato brz-ft-google brz-fs-lg-21" data-generated-css="brz-css-bLAMY" data-uniq-id="qQD1W"><span style="color: '.$colorTitle.';">Sermon Details</span></p>',
            'typographyFontStyle' => ''
        ];

        $wrapperItemTitle = [
            'bgColorHex' => $this->colorPalettes['subpalette1']['bg-accent'] ?? $sectionPalette['bg-accent'] ?? $sectionPalette['btn-bg'] ?? $basicButtonStyleNormal['background-color'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 1,
        ];

        $sectionStyle = [
            'paddingTop' => $this->getTopPaddingOfTheFirstElement ?? 0,
            'bgColorHex' => $this->colorPalettes[$this->subpalette]['bg'] ?? $sectionPalette['bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 1,
        ];

        $sectionDescriptionStyle = [
            'bgColorHex' => $sectionPalette['item-bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 0,
        ];

        $sectionDescriptionItemsStyle = [
            "mobilePaddingType" => "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 5,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 5,
            "mobilePaddingLeftSuffix" => "px",
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
            'showAudio' => 'off',
            'showImage' => 'off',
            'showVideo' => 'off',
            'showPreview' => 'on',
            'showTitle' => 'on',
            'showGroup' => 'off',
            'showDate' => 'off',
            'showCategory' => 'off',
            'showPreacher' => 'off',
            'showPassage' => 'off',
            'showMetaHeadings' => 'off',
            'showPreviousPage' => 'off',
            'showMediaLinksVideo' =>'off',
            'showMediaLinksAudio' => 'off',
            'showMediaLinksDownload' => 'off',
            'showMediaLinksNotes' => 'off',

            'colorHex' => $sectionPalette['text'],
            'colorOpacity' => 1,
            'colorPalette' => '',

            'titleColorHex' => $sectionPalette['text'],
            'titleTypographyFontStyle' => 'heading3',
            'titleColorOpacity' => 3,
            'titleColorPalette' => '',

            'previewColorHex' => $sectionPalette['text'],
            'previewColorOpacity' => 1,
            'previewColorPalette' => '',

            'metaLinksColorHex' => $sectionPalette['link'],
            'metaLinksColorOpacity' => 1,
            'metaLinksColorPalette' => '',

            'hoverMetaLinksColorHex' => $sectionPalette['link'],
            'hoverMetaLinksColorOpacity' => 0.75,
            'hoverMetaLinksColorPalette' => '',

            'detailButtonColorHex' => $basicButtonStyleNormal['color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' =>  $basicButtonStyleHover['background-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'hoverDetailButtonColorOpacity' => 0.75,
            'hoverDetailButtonColorPalette' => '',

            'detailButtonBgColorHex' => $basicButtonStyleNormal['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'detailButtonBgColorOpacity' => 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $basicButtonStyleHover['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverDetailButtonBgColorOpacity' => 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'subscribeButtonColorHex' => $basicButtonStyleNormal['color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'subscribeButtonColorOpacity' => 1,
            'subscribeButtonColorPalette' => '',

            'subscribeButtonBorderStyle' => 'solid',
            'subscribeButtonBorderColorHex' => $basicButtonStyleNormal['border-top-color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'subscribeButtonBorderColorOpacity' => 1,
            'subscribeButtonBorderColorPalette' => '',

            "subscribeButtonBorderWidthType" => "grouped",
            "subscribeButtonBorderWidth" => 1,
            "subscribeButtonBorderTopWidth" => 1,
            "subscribeButtonBorderRightWidth" => 1,
            "subscribeButtonBorderBottomWidth" => 1,
            "subscribeButtonBorderLeftWidth" => 1,

            'subscribeButtonBgColorHex' => $basicButtonStyleNormal['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'subscribeButtonBgColorOpacity' =>  1,
            'subscribeButtonBgColorPalette' => '',

            'hoverSubscribeButtonBgColorHex' => $basicButtonStyleHover['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverSubscribeButtonBgColorOpacity' => 0.75,
            'hoverSubscribeButtonBgColorPalette' => '',

            'subscribeEventButtonColorHex' => $basicButtonStyleNormal['color'] ?? $sectionPalette['text']  ?? $sectionPalette['btn-text'],
            'subscribeEventButtonColorOpacity' => 1,
            'subscribeEventButtonColorPalette' => '',

            'subscribeEventButtonBgColorHex' => $basicButtonStyleNormal['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'subscribeEventButtonBgColorOpacity' =>  1,
            'subscribeEventButtonBgColorPalette' => '',

            'hoverSubscribeEventButtonBgColorHex' =>  $basicButtonStyleHover['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverSubscribeEventButtonBgColorOpacity' => 0.75,
            'hoverSubscribeEventButtonBgColorPalette' => '',

            'subscribeEventButtonBorderStyle' => 'solid',
            'subscribeEventButtonBorderColorHex' => $basicButtonStyleNormal['border-top-color'] ?? $sectionPalette['text'] ?? $sectionPalette['btn-text'],
            'subscribeEventButtonBorderColorOpacity' => 1,
            'subscribeEventButtonBorderColorPalette' => '',

            "subscribeEventButtonBorderWidthType" => "grouped",
            "subscribeEventButtonBorderWidth" => 1,
            "subscribeEventButtonBorderTopWidth" => 1,
            "subscribeEventButtonBorderRightWidth" => 1,
            "subscribeEventButtonBorderBottomWidth" => 1,
            "subscribeEventButtonBorderLeftWidth" => 1,

        ];

        $sectionProperties2Margin = [
            "marginType" => "ungrouped",
            "margin" => 0,
            "marginSuffix" => "px",
            "marginTop" => 10,
            "marginTopSuffix" => "px",
            "marginRight" => 0,
            "marginRightSuffix" => "px",
            "marginBottom" => 0,
            "marginBottomSuffix" => "px",
            "marginLeft" => 30,
            "marginLeftSuffix" => "px",
        ];

        $sectionProperties2 = [
            'showAudio' => 'off',
            'showImage' => 'off',
            'showVideo' => 'off',
            'showPreview' => 'off',
            'showTitle' => 'off',
            'showDate' => 'on',
            'showGroup' => 'on',
            'showCategory' => 'on',
            'showPreacher' => 'on',
            'showPassage' => 'on',
            'showMetaHeadings' => 'on',
            'showPreviousPage' => 'on',
            'showMediaLinksVideo' =>'off',
            'showMediaLinksAudio' => 'off',
            'showMediaLinksDownload' => 'on',
            'showMediaLinksNotes' => 'on',

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

            'detailButtonColorHex' => $basicButtonStyleNormal['color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonColorOpacity' =>  1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' => $basicButtonStyleHover['color'] ?? $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'hoverDetailButtonColorOpacity' => 0.75,
            'hoverDetailButtonColorPalette' => '',

            'detailButtonBgColorHex' => $basicButtonStyleHover['background-color'] ??  $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'detailButtonBgColorOpacity' => 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $basicButtonStyleHover['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverDetailButtonBgColorOpacity' => 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'subscribeButtonColorHex' => $basicButtonStyleNormal['color'] ?? $sectionPalette['btn-text'] ??  $sectionPalette['text'],
            'subscribeButtonColorOpacity' => 1,
            'subscribeButtonColorPalette' => '',

            'subscribeButtonBgColorHex' => $basicButtonStyleNormal['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'subscribeButtonBgColorOpacity' => 1,
            'subscribeButtonBgColorPalette' => '',

            'hoverSubscribeButtonBgColorHex' => $basicButtonStyleHover['background-color'] ?? $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverSubscribeButtonBgColorOpacity' => 0.75,
            'hoverSubscribeButtonBgColorPalette' => '',

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

        foreach ($sectionDescriptionItemsStyle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 0)
                ->$properties($value);
        }
        foreach ($sectionDescriptionItemsStyle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1)
                ->$properties($value);
        }

        foreach ($sectionProperties1 as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 0, 0, 0)
                ->$properties($value);
        }

        $detailsSection
            ->getItemWithDepth(0, 1, 0, 0, 0)
            ->titleTypography()
            ->previewTypography()
            ->subscribeEventButtonTypography()
            ->detailButtonTypography();

        foreach ($sectionDescriptionStyle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1)
                ->$properties($value);
        }

        foreach ($sectionProperties2 as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 1, 0)
                ->$properties($value);
        }

        $detailsSection->getItemWithDepth(0, 1, 1, 1, 0)
            ->dataTypography()
            ->typography(['lineHeight' => 1.7])
            ->dataTypography(['lineHeight' => 1.7])
            ->previewTypography(['lineHeight' => 1.7])
            ->subscribeEventButtonTypography()
            ->detailButtonTypography();

        $detailsSection->getItemWithDepth(0, 1, 1)->addPadding(10,15,5,15);

        foreach ($sectionProperties2Margin as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 1)
                ->$properties($value);
        }

        foreach ($wrapperItemTitle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 0, 0)
                ->$properties($value);
        }

        $detailsSection->getItemWithDepth(0, 1, 1, 0)->addPadding(0,10,10,10);

        foreach ($richTextTitle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 0, 0, 0, 0)
                ->$properties($value);
        }

        $detailsSection
            ->getItemWithDepth(0, 1)
            ->addPadding(15,0,0,0);

        $detailsSection
            ->getItemWithDepth(0, 1, 1)
            ->addPadding(0,0,0,0);

        $this->sectionPadding($detailsSection);

        self::$cacheSermons = $detailsSection;

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
