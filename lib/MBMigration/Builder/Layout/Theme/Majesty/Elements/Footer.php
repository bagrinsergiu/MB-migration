<?php

namespace MBMigration\Builder\Layout\Theme\Majesty\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyComponentValue;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\Element\AbstractElement;
use MBMigration\Builder\Layout\Common\ElementContext;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class Footer extends AbstractElement
{
    const CACHE_KEY = 'footer';
    use Cacheable;
    use RichTextAble;
    use SectionStylesAble;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        return $this->getCache(self::CACHE_KEY, function () use ($data): BrizyComponent {

            $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
            $brizySection->getItemValueWithDepth(0, 0)->set_items([]);

            $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));

            $this->handleRichTextItems($elementContext, $this->browserPage);
            $this->handleSectionStyles($elementContext, $this->browserPage);

            return $brizySection;
        });
    }

//
//    protected function applySectionStyles($mbSectionItem, BrizyComponent $brizySection): BrizyComponent
//    {
//        $sectionStyles = $this->browserPage->evaluateScript(
//            'StyleExtractor.js',
//            [
//                'selector' => '[data-id="'.$mbSectionItem['sectionId'].'"]',
//                'STYLE_PROPERTIES' => [
//                    'background-color',
//                    'opacity',
//                    'border-bottom-color',
//                    'padding-top',
//                    'padding-bottom',
//                    'margin-top',
//                    'margin-bottom',
//                ],
//                'FAMILIES' => [],
//                'DEFAULT_FAMILY' => 'lato',
//            ]
//        );
//
//        $sectionWrapperStyles = $this->browserPage->evaluateScript('StyleExtractor.js', [
//            'selector' => '[data-id="'.$mbSectionItem['sectionId'].'"]>.content-wrapper',
//            'STYLE_PROPERTIES' => [
//                'padding-top',
//                'padding-bottom',
//                'margin-top',
//                'margin-bottom',
//            ],
//            'FAMILIES' => [],
//            'DEFAULT_FAMILY' => 'helvetica_neue_helveticaneue_helvetica_arial_sans-serif',
//        ]);
//
//        $sectionStyles = $sectionStyles['data'];
//        $sectionWrapperStyles = $sectionWrapperStyles['data'];
//
//        $backgroundColorHex = ColorConverter::rgba2hex($sectionStyles['background-color']);
//
//        $brizySection->getItemValueWithDepth(0)
//            ->set_bgColorHex($backgroundColorHex)
//            ->set_bgColorOpacity($sectionStyles['opacity'])
//            ->set_bgColorType('none')
//            ->set_paddingTop((int)$sectionStyles['padding-top'] + (int)$sectionWrapperStyles['padding-top'])
//            ->set_paddingBottom((int)$sectionStyles['padding-bottom'] + (int)$sectionWrapperStyles['padding-bottom'])
//            ->set_marginTop((int)$sectionWrapperStyles['margin-top'])
//            ->set_marginBottom((int)$sectionWrapperStyles['margin-bottom'])
//            ->set_bgColorPalette('');
//
//
//        return $brizySection;
//    }
}