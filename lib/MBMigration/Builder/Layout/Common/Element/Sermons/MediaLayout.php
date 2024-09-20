<?php

namespace MBMigration\Builder\Layout\Common\Element\Sermons;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Concern\SlugAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class MediaLayout extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use CssPropertyExtractorAware;
    use BrizyQueryBuilderAware;
    use SlugAble;

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
        $mbSection = $data->getMbSection();

        $mbContext = $data->getThemeContext();

        $slug = $mbContext->getSlug();

        $dataIdSelector = '[data-id="'.($mbSection['sectionId'] ?? $mbSection['id']).'"]';

        $nodeSelector = $dataIdSelector. ' .media-grid-container';
        $mbSection['mediaGridContainer'] = $this->hasNode($nodeSelector, $this->browserPage);

        if (!$mbSection['mediaGridContainer']) {
            $titleSelector = $dataIdSelector. ' .media-video-title';
            $mbSection['containTitle'] = $this->getNodeText($titleSelector, $this->browserPage);
        }

        $sectionSubPalette = $this->getNodeSubPalette($dataIdSelector, $this->browserPage);
        $sectionPalette = $data->getThemeContext()->getRootPalettes()->getSubPaletteByName($sectionSubPalette);

        if($mbSection['mediaGridContainer']) {
            $brizySection = new BrizyComponent(json_decode($this->brizyKit['GridMediaLayout']['main'], true));
            $brizySectionHead = new BrizyComponent(json_decode($this->brizyKit['GridMediaLayout']['head'], true));
            $detailsSection = new BrizyComponent(json_decode($this->brizyKit['GridMediaLayout']['detail'], true));

            $sectionItemComponent = $this->getSectionItemComponent($brizySection);
            $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
            $this->handleSectionStyles($elementContext, $this->browserPage);

            $sectionItemComponent = $this->getSectionItemComponent($brizySectionHead);
            $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
            $this->handleRichTextHead($elementContext, $this->browserPage);
            $this->handleRichTextItems($elementContext, $this->browserPage);

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


            $colorStyles = [
                'text-color' => ColorConverter::convertColorRgbToHex($resultColorStyles['text']['color']),
                'bg-color' => ColorConverter::convertColorRgbToHex($resultColorStyles['bg-color']['background-color']),
                'bg-opacity' => ColorConverter::convertColorRgbToHex($resultColorStyles['bg-opacity']['opacity']),
                'pagination-normal' => ColorConverter::convertColorRgbToHex($resultColorStyles['pagination-normal']['color']),
                'pagination-active' => ColorConverter::convertColorRgbToHex($resultColorStyles['pagination-active']['color']),
                'color-text-description' => ColorConverter::convertColorRgbToHex($resultColorStyles['color-text-description']['color']),
                'opacity-pagination-normal' => $resultColorStyles['opacity-pagination-normal']['opacity'],
                'opacity-pagination-active' => $resultColorStyles['opacity-pagination-active']['opacity'],
                ];

            $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();
            $detailCollectionItem = $this->createDetailsCollectionItem(
                $collectionTypeUri,
                $detailsSection,
                'media-detail',
                'Media Detail'
            );

            $placeholder = base64_encode('{{ brizy_dc_url_post entityId="' . $detailCollectionItem['id'] . '" }}');

            $this->getDetailsLinksComponent($brizySection)
                ->getValue()
                ->set_detailPageSource($collectionTypeUri)
                ->set_detailPage("{{placeholder content='$placeholder'}}");

            $sectionProperties = [

                'showCategoryFilter' => 'off',

                'colorHex' =>  $colorStyles['color-text-description']['color'] ?? "#ebeff2",
                'colorOpacity' => $colorStyles['color-text-description']['opacity'] ?? 1,
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

                'paginationColorHex' => $colorStyles['pagination-normal'],
                'paginationColorOpacity' => floatval($colorStyles['opacity-pagination-normal']),
                'paginationColorPalette' => '',

                'activePaginationColorHex' => $colorStyles['pagination-active'],
                'activePaginationColorOpacity' => floatval($colorStyles['opacity-pagination-active']),
                'activePaginationColorPalette' => '',

                'hoverPaginationColorHex' => $colorStyles['pagination-normal'],
                'hoverPaginationColorOpacity' => 0.75,
                'hoverPaginationColorPalette' => '',

                'resultsHeadingColorHex' => $colorStyles['text-color'],
                'resultsHeadingColorOpacity' => 1,
                'resultsHeadingColorPalette' => '',
            ];

            foreach ($sectionProperties as $key => $value) {
                $properties = 'set_'.$key;
                $brizySection->getItemValueWithDepth(0, 0, 0)
                    ->$properties($value);
            }

        } else {
            $brizySection = new BrizyComponent(json_decode($this->brizyKit['SermonFeatured']['main'], true));
            $brizySectionHead = new BrizyComponent(json_decode($this->brizyKit['SermonFeatured']['head'], true));

            $sectionItemComponent = $this->getSectionItemComponent($brizySection);
            $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
            $this->handleSectionStyles($elementContext, $this->browserPage);

            $sectionItemComponent = $this->getSectionItemComponent($brizySectionHead);
            $elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);
            $this->handleRichTextHead($elementContext, $this->browserPage);
            $this->handleRichTextItems($elementContext, $this->browserPage);

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
                $brizySection->getItemValueWithDepth(0, 0, 0)
                    ->$properties($value);
            }

            foreach ($sectionPropertiesSecondLevel as $key => $value) {
                $properties = 'set_'.$key;
                $brizySection->getItemValueWithDepth(0, 1, 0)
                    ->$properties($value);
            }
        }

        $brizySection->getItemValueWithDepth(0)
            ->add('items', [$brizySectionHead], 0);

        return $brizySection;
    }

    protected function getDetailsLinksComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }
}
