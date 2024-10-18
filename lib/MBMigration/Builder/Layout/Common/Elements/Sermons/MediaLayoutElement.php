<?php

namespace MBMigration\Builder\Layout\Common\Elements\Sermons;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Concern\SlugAble;
use MBMigration\Builder\Layout\Common\Elements\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class MediaLayoutElement extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use CssPropertyExtractorAware;
    use BrizyQueryBuilderAware;
    use SlugAble;

    const DETAILS_PAGE_NAME = 'Sermon Detail';
    const DETAILS_SLUG_NAME = 'sermon-detail';

    /**
     * @param $brizyKit
     * @param BrowserPageInterface $browserPage
     * @param QueryBuilder $queryBuilder
     */
    public function __construct($brizyKit, BrowserPageInterface $browserPage, QueryBuilder $queryBuilder)
    {
        parent::__construct($brizyKit, $browserPage);
        $this->setQueryBuilder($queryBuilder);
    }


    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['Section']['main'], true));

        $mbSection = $data->getMbSection();

        $mbContext = $data->getThemeContext();

        $slug = $mbContext->getSlug();

        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
        $this->handleSectionStyles($elementContext, $this->browserPage);

        $this->setTopPaddingOfTheFirstElement($data, $sectionItemComponent);

        $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
        $this->handleRichTextHead($elementContext, $this->browserPage);
        $this->handleRichTextItems($elementContext, $this->browserPage);

        $dataIdSelector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';

        $nodeSelector = $dataIdSelector. ' .media-grid-container';
        $mbSection['mediaGridContainer'] = $this->hasNode($nodeSelector, $this->browserPage);

        $nodeSelector = $dataIdSelector. ' .media-player';
        $mbSection['media-player'] = $this->hasNode($nodeSelector, $this->browserPage);

        if ($mbSection['media-player']) {
            $titleSelector = $dataIdSelector. ' .media-video-title';
            $mbSection['containTitle'] = $this->getNodeText($titleSelector, $this->browserPage);
        }

        $sectionSubPalette = $this->getNodeSubPalette($dataIdSelector, $this->browserPage);
        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        if(!$mbSection['mediaGridContainer']) {
            $brizySectionFeaturedVideo = new BrizyComponent(json_decode($this->brizyKit['SermonFeatured']['elementVideo'], true));
            $brizySectionFeaturedDescription = new BrizyComponent(json_decode($this->brizyKit['SermonFeatured']['elementDescription'], true));

            $textColorStyles = $this->getDomElementStyles(
                $dataIdSelector. ' .media-player-container .media-header .text-content',
                ['color'],
                $this->browserPage);

            $backgroundColorStyles = $this->getDomElementStyles(
                $dataIdSelector. ' .media-player-container .media-player',
                ['background-color'],
                $this->browserPage);

            $backgroundColorStyles = ColorConverter::convertColorRgbToHex($backgroundColorStyles['background-color']);
            $textColorStyles = ColorConverter::convertColorRgbToHex($textColorStyles['color']);

            $sectionProperties = [
                'sermonSlug' => $this->createSlug($mbSection['containTitle']),

                'titleColorHex' => $sectionPalette['link'] ?? "#1e1eb7",
                'titleColorOpacity' => 1,
                'titleColorPalette' => "",

                'hoverTitleColorHex' => $sectionPalette['link'] ?? "#1e1eb7",
                'hoverTitleColorOpacity' => 0.7,
                'hoverTitleColorPalette' => "",

                'metaLinksColorHex' => $sectionPalette['link'] ?? "#3d79ff",
                'metaLinksColorOpacity' => 1,
                'metaLinksColorPalette' => "",

                'hoverMetaLinksColorHex' => $sectionPalette['link'] ?? "#3d79ff",
                'hoverMetaLinksColorOpacity' => 0.7,
                'hoverMetaLinksColorPalette' => "",
            ];

            $sectionPropertiesSecondLevel = [
                'sermonSlug'=> $this->createSlug($mbSection['containTitle']),

                'colorHex' => $textColorStyles ?? "#ebeff2",
                'colorOpacity' => 1,
                'colorPalette' => "",

                'titleColorHex' => $textColorStyles ?? "#ebeff2",
                'titleColorOpacity' => 1,
                'titleColorPalette' => "",

                'hoverTitleColorHex' => $textColorStyles ?? "#ebeff2",
                'hoverTitleColorOpacity' => 1,
                'hoverTitleColorPalette' => "",

                'previewColorHex' => $textColorStyles ?? "#ebeff2",
                'previewColorOpacity' => 1,
                'previewColorPalette' => "",

                'parentBgColorHex' => $backgroundColorStyles ?? '#505050',
                'parentBgColorOpacity' => 1,
                'parentBgColorPalette' => "",
            ];

            foreach ($sectionProperties as $key => $value) {
                $properties = 'set_'.$key;
                $brizySectionFeaturedVideo->getItemValueWithDepth(0)
                    ->$properties($value);
            }

            foreach ($sectionPropertiesSecondLevel as $key => $value) {
                $properties = 'set_'.$key;
                $brizySectionFeaturedDescription->getItemValueWithDepth(0)
                    ->$properties($value);
            }

            $brizySection->getItemValueWithDepth(0)
                ->add('items', [$brizySectionFeaturedVideo])
                ->add('items', [$brizySectionFeaturedDescription]);
        } else {
            $brizySectionGrid = new BrizyComponent(json_decode($this->brizyKit['GridMediaLayout']['main'], true));
            $detailsSection = new BrizyComponent(json_decode($this->brizyKit['GridMediaLayout']['detail'], true));

            $resultColorStyles['text'] = $this->getDomElementStyles(
                $dataIdSelector. ' .media-player-container .media-header .text-content',
                ['color'],
                $this->browserPage);

            $resultColorStyles['pagination-normal'] = $this->getDomElementStyles(
                $dataIdSelector. ' .pagination .previous a',
                ['color'],
                $this->browserPage);

            $resultColorStyles['opacity-pagination-normal'] = $this->getDomElementStyles(
                $dataIdSelector. ' .pagination .previous a',
                ['opacity'],
                $this->browserPage);

            $resultColorStyles['pagination-active'] = $this->getDomElementStyles(
                $dataIdSelector. ' .pagination .active a',
                ['color'],
                $this->browserPage);

            $resultColorStyles['opacity-pagination-active'] = $this->getDomElementStyles(
                $dataIdSelector. ' .pagination .active a',
                ['opacity'],
                $this->browserPage);

            $resultColorStyles['bg-color'] = $this->getDomElementStyles(
                $dataIdSelector. ' .media-player-container .media-player',
                ['background-color'],
                $this->browserPage);

            $resultColorStyles['bg-opacity'] = $this->getDomElementStyles(
                $dataIdSelector. ' .media-player-container .media-player',
                ['opacity'],
                $this->browserPage);

            $resultColorStyles['color-text-description'] = $this->getDomElementStyles(
                $dataIdSelector. ' .media-player-container .media-description',
                ['color'],
                $this->browserPage);

            $resultColorStyles['color-text-header'] = $this->getDomElementStyles(
                $dataIdSelector. ' .media-player-container .media-header',
                ['color'],
                $this->browserPage);


            $colorStyles = [
                'text-color' => ColorConverter::convertColorRgbToHex($resultColorStyles['text']['color'] ?? $sectionPalette['text']),
                'bg-color' => ColorConverter::convertColorRgbToHex($resultColorStyles['bg-color']['background-color']  ?? $sectionPalette['bg']),
                'bg-opacity' => ColorConverter::convertColorRgbToHex($resultColorStyles['bg-opacity']['opacity'] ?? 1),
                'pagination-normal' => ColorConverter::convertColorRgbToHex($resultColorStyles['pagination-normal']['color']  ?? $sectionPalette['text']),
                'pagination-active' => ColorConverter::convertColorRgbToHex($resultColorStyles['pagination-active']['color']  ?? $sectionPalette['text']),
                'color-text-description' => ColorConverter::convertColorRgbToHex($resultColorStyles['color-text-description']['color']  ?? $sectionPalette['text']),
                'color-text-header' => ColorConverter::convertColorRgbToHex($resultColorStyles['color-text-header']['color']  ?? $sectionPalette['text']),
                'opacity-pagination-normal' => $resultColorStyles['opacity-pagination-normal']['opacity'] ?? 0.75,
                'opacity-pagination-active' => $resultColorStyles['opacity-pagination-active']['opacity'] ?? 1,
            ];

            $sectionPalette['item-bg'] = $colorStyles['bg-color'];

            $this->setStyleDetailPage($detailsSection, $sectionPalette);

            $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();
            $detailCollectionItem = $this->createDetailsCollectionItem(
                $collectionTypeUri,
                $detailsSection,
                self::DETAILS_SLUG_NAME,
                self::DETAILS_PAGE_NAME,
            );

            $placeholder = base64_encode('{{ brizy_dc_url_post entityId="' . $detailCollectionItem['id'] . '" }}');

            $this->getDetailsLinksComponent($brizySectionGrid)
                ->getValue()
//                ->set_defaultCategory($slug)
                ->set_parentCategory($slug)
                ->set_detailPageSource($collectionTypeUri)
                ->set_detailPage("{{placeholder content='$placeholder'}}");

            $sectionProperties = [

                'showCategoryFilter' => 'off',

                'colorHex' =>  $colorStyles['color-text-header']['color'] ?? $colorStyles['color-text-header'],
                'colorOpacity' => $colorStyles['color-text-header']['opacity'] ?? 1,
                'colorPalette' => "",

                'titleColorHex' =>  $sectionPalette['link'] ?? "#1e1eb7",
                'titleColorOpacity' =>  1,
                'titleColorPalette' =>  "",

                'hoverTitleColorHex' =>  $sectionPalette['link'] ?? "#1e1eb7",
                'hoverTitleColorOpacity' =>  0.7,
                'hoverTitleColorPalette' =>  "",

                'metaLinksColorHex' =>  $sectionPalette['link'] ?? "#3d79ff",
                'metaLinksColorOpacity' =>  1,
                'metaLinksColorPalette' =>  "",

                'hoverMetaLinksColorHex' =>  $sectionPalette['link'] ?? "#3d79ff",
                'hoverMetaLinksColorOpacity' =>  0.7,
                'hoverMetaLinksColorPalette' =>  "",

                'filterBgColorHex' => $colorStyles['bg-color'],
                'filterBgColorOpacity' => $colorStyles['bg-opacity'],
                'filterBgColorPalette' => '',

                'itemBgColorHex' => $colorStyles['bg-color'],
                'itemBgColorOpacity' => 0, // $colorStyles['bg-opacity'],
                'itemBgColorPalette' => '',

                'paginationColorHex' => $colorStyles['pagination-normal']['color'] ?? $colorStyles['pagination-normal'],
                'paginationColorOpacity' => floatval($colorStyles['pagination-normal']['opacity'] ?? $colorStyles['opacity-pagination-normal']),
                'paginationColorPalette' => '',

                'activePaginationColorHex' => $colorStyles['pagination-active']['color'] ?? $colorStyles['pagination-active'],
                'activePaginationColorOpacity' => floatval($colorStyles['pagination-active']['opacity'] ?? $colorStyles['opacity-pagination-active']),
                'activePaginationColorPalette' => '',

                'hoverPaginationColorHex' => $colorStyles['pagination-normal']['color'] ?? $colorStyles['pagination-normal'],
                'hoverPaginationColorOpacity' => $colorStyles['pagination-normal']['opacity'] ?? 0.75,
                'hoverPaginationColorPalette' => '',

                'resultsHeadingColorHex' => $colorStyles['text-color'],
                'resultsHeadingColorOpacity' => 1,
                'resultsHeadingColorPalette' => '',

                "itemPaddingType" => "ungrouped",
                "itemPadding" => 0,
                "itemPaddingSuffix" => "px",
                "itemPaddingTop" => 0,
                "itemPaddingTopSuffix" => "px",
                "itemPaddingRight" => 0,
                "itemPaddingRightSuffix" => "px",
                "itemPaddingBottom" => 0,
                "itemPaddingBottomSuffix" => "px",
                "itemPaddingLeft" => 0,
                "itemPaddingLeftSuffix" => "px",
            ];

            foreach ($sectionProperties as $key => $value) {
                $properties = 'set_'.$key;
                $brizySectionGrid->getItemValueWithDepth(0)
                    ->$properties($value);
            }

            $brizySection->getItemValueWithDepth(0)
                ->add('items', [$brizySectionGrid]);
        }

        return $brizySection;
    }

    private function setStyleDetailPage(BrizyComponent $detailsSection, array $sectionPalette)
    {

        $colorTitle = ColorConverter::hex2Rgb($sectionPalette['btn-text']);

        $richTextTitle = [
            'text' => '<p data-generated-css="brz-css-yVHHc" data-uniq-id="oe68r" class="brz-tp-lg-heading2 brz-text-lg-center"><span style="color: '.$colorTitle.';">Sermon Details</span></p>',
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
        ];

        $sectionProperties2 = [
            'showAudio' => 'off',
            'showImage' => 'off',
            'showVideo' => 'off',
            'showPreview' => 'off',
            'showTitle' => 'off',
            'showDate' => 'on',
            'showGroup' => 'on',
            'showCategory' => 'off',
            'showPreacher' => 'off',
            'showPassage' => 'off',
            'showMetaHeadings' => 'off',
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

            'hoverMetaLinksColorHex' => $sectionPalette['text'],
            'hoverMetaLinksColorOpacity' => 0.75,
            'hoverMetaLinksColorPalette' => '',

            'detailButtonBgColorHex' => $sectionPalette['btn-bg'],
            'detailButtonBgColorOpacity' => 1,
            'detailButtonBgColorPalette' => '',

            'hoverDetailButtonBgColorHex' => $sectionPalette['btn-bg'],
            'hoverDetailButtonBgColorOpacity' => 0.75,
            'hoverDetailButtonBgColorPalette' => '',

            'subscribeButtonColorHex' => $sectionPalette['btn-text'],
            'subscribeButtonColorOpacity' => 1,
            'subscribeButtonColorPalette' => '',

            'subscribeButtonBgColorHex' => $sectionPalette['btn-bg'],
            'subscribeButtonBgColorOpacity' => 1,
            'subscribeButtonBgColorPalette' => '',

            'hoverSubscribeButtonBgColorHex' => $sectionPalette['btn-bg'],
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
    }

    protected function getDetailsLinksComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 25,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
