<?php

namespace MBMigration\Builder\Layout\Common\Elements\Events;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Utils\ColorConverter;

class EventDetailsPageLayout
{
    private BrizyComponent $detailsSection;

    private static BrizyComponent $cache;
    private int $topPaddingOfTheFirstElement;

    private int $mobileTopPaddingOfTheFirstElement;

    /**
     * @throws BadJsonProvided
     */
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

        $richTextTitle = [
            'text' => '<p class="brz-text-lg-center brz-fss-lg-px brz-fw-lg-700 brz-ls-lg-0 brz-lh-lg-1_6 brz-vfw-lg-400 brz-fwdth-lg-100 brz-fsft-lg-0 brz-tp-lg-empty brz-ff-lato brz-ft-google brz-fs-lg-21" data-generated-css="brz-css-bLAMY" data-uniq-id="qQD1W"><span style="color: '.$colorTitle.';">Event Details</span></p>',
            'typographyFontStyle' => 'heading5'
        ];

        $wrapperItemTitle = [
            'bgColorHex' => $sectionPalette['btn-bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 1,
        ];

        $sectionStyle = [
            'bgColorHex' => $sectionPalette['bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 1,
        ];

        $sectionDescriptionStyle = [
            'bgColorHex' => $sectionPalette['bg'],
            'bgColorPalette' => '',
            'bgColorOpacity' => 0,
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

            'detailButtonColorHex' => $sectionPalette['btn-text'],
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'hoverDetailButtonColorHex' => $sectionPalette['btn-text'],
            'hoverDetailButtonColorOpacity' => 0.75,
            'hoverDetailButtonColorPalette' => '',

            'detailButtonBgColorHex' => $sectionPalette['btn-bg'],
            'detailButtonBgColorOpacity' => 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $sectionPalette['btn-bg'],
            'hoverDetailButtonBgColorOpacity' => 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'metaLinksColorHex' => $sectionPalette['link'],
            'metaLinksColorOpacity' => 1,
            'metaLinksColorPalette' => '',

            'hoverMetaLinksColorHex' => $sectionPalette['link'],
            'hoverMetaLinksColorOpacity' => 0.75,
            'hoverMetaLinksColorPalette' => '',

            'subscribeEventButtonColorHex' => $sectionPalette['btn-text'],
            'subscribeEventButtonColorOpacity' => 1,
            'subscribeEventButtonColorPalette' => '',

            'subscribeEventButtonBgColorHex' => $sectionPalette['btn-bg'],
            'subscribeEventButtonBgColorOpacity' => 1,
            'subscribeEventButtonBgColorPalette' => '',

            'hoverSubscribeEventButtonBgColorHex' => $sectionPalette['btn-bg'],
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

            'detailButtonColorHex' => $sectionPalette['btn-text'],
            'detailButtonColorOpacity' => 1,
            'detailButtonColorPalette' => '',

            'detailButtonBgColorHex' => $sectionPalette['btn-bg'],
            'detailButtonBgColorOpacity' => 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $sectionPalette['btn-bg'],
            'hoverDetailButtonBgColorOpacity' => 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'subscribeEventButtonColorHex' => $sectionPalette['btn-text'],
            'subscribeEventButtonColorOpacity' => 1,
            'subscribeEventButtonColorPalette' => '',

            'subscribeEventButtonBgColorHex' => $sectionPalette['btn-bg'],
            'subscribeEventButtonBgColorOpacity' => 1,
            'subscribeEventButtonBgColorPalette' => '',

            'hoverSubscribeEventButtonBgColorHex' => $sectionPalette['btn-bg'],
            'hoverSubscribeEventButtonBgColorOpacity' => 0.75,
            'hoverSubscribeEventButtonBgColorPalette' => '',

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

        foreach ($sectionProperties2Margin as $key => $value) {
            $properties = 'set_'.$key;
            $detailsSection->getItemValueWithDepth(0, 1, 1, 1)
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
            ->previewTypography(['lineHeight' => 1.7]);

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
