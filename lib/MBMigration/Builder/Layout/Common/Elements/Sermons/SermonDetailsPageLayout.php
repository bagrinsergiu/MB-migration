<?php

namespace MBMigration\Builder\Layout\Common\Elements\Sermons;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Utils\ColorConverter;

class SermonDetailsPageLayout
{
    private BrizyComponent $detailsSection;

    private static BrizyComponent $cache;
    private int $topPaddingOfTheFirstElement;

    private int $mobileTopPaddingOfTheFirstElement;
    public function __construct($detailsSection, int $topPaddingOfTheFirstElement, int $mobileTopPaddingOfTheFirstElement)
    {
        $this->detailsSection = new BrizyComponent(json_decode($detailsSection, true));
        $this->topPaddingOfTheFirstElement = $topPaddingOfTheFirstElement;
        $this->mobileTopPaddingOfTheFirstElement = $mobileTopPaddingOfTheFirstElement;
    }

    public function setStyleDetailPage(array $sectionPalette): BrizyComponent
    {
        if(!empty(self::$cache))
        {
            return self::$cache;
        }
        $detailsSection = $this->detailsSection;
        $colorTitle = ColorConverter::hex2Rgb($sectionPalette['btn-text']);

        $colorTitle = ColorConverter::hex2Rgb($sectionPalette['btn-text'] ?? $sectionPalette['text']);

        $richTextTitle = [
            'text' => '<h5 class="brz-text-lg-center brz-tp-lg-empty brz-ff-lato brz-ft-google brz-fs-lg-20 brz-fss-lg-px brz-fw-lg-400 brz-ls-lg-0 brz-lh-lg-1_6 brz-vfw-lg-400 brz-fwdth-lg-100 brz-fsft-lg-0" data-uniq-id="xdAq1" data-generated-css="brz-css-duw4v"><span style="color: '.$colorTitle.';">Sermon Details</span></h5>',
            'typographyFontStyle' => 'heading5'
        ];

        $wrapperItemTitle = [
            'bgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 1,
        ];

        $sectionStyle = [
            'paddingTop' => $this->getTopPaddingOfTheFirstElement ?? 0,
            'bgColorHex' => $sectionPalette['bg'],
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
            'showGroup' => 'on',
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

            'detailButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' => $sectionPalette['btn-text']  ?? $sectionPalette['text'],
            'hoverDetailButtonColorOpacity' => 0.75,
            'hoverDetailButtonColorPalette' => '',

            'detailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'detailButtonBgColorOpacity' => 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverDetailButtonBgColorOpacity' => 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'subscribeButtonColorHex' => $sectionPalette['btn-text']  ?? $sectionPalette['text'],
            'subscribeButtonColorOpacity' => 1,
            'subscribeButtonColorPalette' => '',

            'subscribeButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'subscribeButtonBgColorOpacity' => 1,
            'subscribeButtonBgColorPalette' => '',

            'hoverSubscribeButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverSubscribeButtonBgColorOpacity' => 0.75,
            'hoverSubscribeButtonBgColorPalette' => '',

            'subscribeEventButtonColorHex' => $sectionPalette['btn-text']  ?? $sectionPalette['text'],
            'subscribeEventButtonColorOpacity' => 1,
            'subscribeEventButtonColorPalette' => '',

            'subscribeEventButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'subscribeEventButtonBgColorOpacity' => 1,
            'subscribeEventButtonBgColorPalette' => '',

            'hoverSubscribeEventButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverSubscribeEventButtonBgColorOpacity' => 0.75,
            'hoverSubscribeEventButtonBgColorPalette' => '',

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
            'showGroup' => 'off',
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

            'detailButtonColorHex' => $sectionPalette['btn-text'] ?? $sectionPalette['text'],
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' => $sectionPalette['btn-text']  ?? $sectionPalette['text'],
            'hoverDetailButtonColorOpacity' => 0.75,
            'hoverDetailButtonColorPalette' => '',

            'detailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'detailButtonBgColorOpacity' => 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'hoverDetailButtonBgColorOpacity' => 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'subscribeButtonColorHex' => $sectionPalette['btn-text']  ?? $sectionPalette['text'],
            'subscribeButtonColorOpacity' => 1,
            'subscribeButtonColorPalette' => '',

            'subscribeButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
            'subscribeButtonBgColorOpacity' => 1,
            'subscribeButtonBgColorPalette' => '',

            'hoverSubscribeButtonBgColorHex' => $sectionPalette['btn-bg'] ?? $sectionPalette['item-bg'],
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
            ->subscribeEventButtonTypography();

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

        $detailsSection
            ->getItemWithDepth(0,1,1,1,0)
            ->dataTypography()
            ->typography();

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


        foreach ($richTextTitle as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 0, 0, 0, 0)
                ->$properties($value);
        }

        $this->sectionPadding($detailsSection);

        self::$cache = $detailsSection;

        return $detailsSection;
    }

    private function sectionPadding(BrizyComponent $detailsSection){
        if($this->topPaddingOfTheFirstElement !== 0) {
            $options['paddingTop'] = $this->topPaddingOfTheFirstElement;
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
