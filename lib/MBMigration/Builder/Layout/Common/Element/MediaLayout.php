<?php

namespace MBMigration\Builder\Layout\Common\Element;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\BrizyQueryBuilderAware;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Concern\SlugAble;
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


            $colorStyles = [
                'pagination-normal' => ColorConverter::convertColorRgbToHex($resultColorStyles['pagination-normal']['color']),
                'pagination-active' => ColorConverter::convertColorRgbToHex($resultColorStyles['pagination-active']['color']),
                'opacity-pagination-normal' => $resultColorStyles['opacity-pagination-normal']['opacity'],
                'opacity-pagination-active' => $resultColorStyles['opacity-pagination-active']['opacity'],
                ];

            $collectionTypeUri = $data->getThemeContext()->getBrizyCollectionTypeURI();
            $detailCollectionItem = $this->createDetailsCollectionItem(
                $data->getThemeContext()->getBrizyCollectionTypeURI(),
                [
                    $detailsSection,
                ],
                'media-detail',
                'Media Detail'
            );

            $placeholder = base64_encode('{{ brizy_dc_url_post entityId="' . $detailCollectionItem . '" }}"');

            $this->getDetailsLinksComponent($detailsSection)
                ->getValue()
                ->set_source($collectionTypeUri)
                ->set_detailPage("{{placeholder content='$placeholder'}}");

            $sectionProperties = [
                'sermonSlug', $this->createSlug($mbSection['settings']['containTitle']),

                'defaultCategory', $this->createSlug($mbSection['settings']['containTitle']),
                'parentCategory', $this->createSlug($mbSection['settings']['containTitle']),
                'showCategoryFilter', $this->createSlug($mbSection['settings']['containTitle']),

                'titleColorHex', $sectionPalette['link'] ?? "#1e1eb7",
                'titleColorOpacity', 1,
                'titleColorPalette', "",

                'hoverTitleColorHex', $sectionPalette['link'] ?? "#1e1eb7",
                'hoverTitleColorOpacity', 0.7,
                'hoverTitleColorPalette', "",

                'metaLinksColorHex', $sectionPalette['link'] ?? "#3d79ff",
                'metaLinksColorOpacity', 1,
                'metaLinksColorPalette', "",

                'hoverMetaLinksColorHex', $sectionPalette['link'] ?? "#3d79ff",
                'hoverMetaLinksColorOpacity', 0.7,
                'hoverMetaLinksColorPalette', "",
            ];

            if($mbSection['settings']['mediaGridContainer'] === false){
                $sectionProperties = array_merge($sectionProperties, ['searchValue' => $mbSection['settings']['containTitle']]);
            }

            $sectionPropertieSecondLevel = [
                'sermonSlug', $this->createSlug($mbSection['settings']['containTitle']),

                'colorHex', $sectionPalette['text'] ?? "#ebeff2",
                'colorOpacity', 1,
                'colorPalette', "",

                'titleColorHex', $sectionPalette['text'] ?? "#ebeff2",
                'titleColorOpacity', 1,
                'titleColorPalette', "",

                'hoverTitleColorHex', $mbSection['style']['sermon']['text'] ?? "#ebeff2",
                'hoverTitleColorOpacity', 1,
                'hoverTitleColorPalette', "",

                'previewColorHex', $mbSection['style']['sermon']['text'] ?? "#ebeff2",
                'previewColorOpacity', 1,
                'previewColorPalette', "",

                'parentBgColorHex', $mbSection['style']['sermon']['bg'] ?? '#505050',
                'parentBgColorOpacity', 1,
                'parentBgColorPalette', "",
            ];

            foreach ($sectionProperties as $key => $value) {
                $brizySection->getItemValueWithDepth(0, 1, 0, 0, 0)
                    ->set_source($key, $value);
            }

            foreach ($sectionPropertieSecondLevel as $key => $value) {
                $brizySection->getItemValueWithDepth(0, 2, 0, 0, 0)
                    ->set_source($key, $value);
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
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }
}
