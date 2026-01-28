<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Gallery;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use Throwable;

class GalleryLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Gallery\GalleryLayoutElement
{
    protected function getSlideImageComponent(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem->getItemWithDepth(0,0);
        //return $brizySectionItem;
    }

    protected function getSlideVideoComponent(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem->getItemWithDepth(0,0);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "paddingType"=> "ungrouped",
            "padding" => 0,
            "paddingSuffix" => "px",
            "paddingTop" => 0,
            "paddingTopSuffix" => "px",
            "paddingRight" => 0,
            "paddingRightSuffix" => "px",
            "paddingBottom" => 0,
            "paddingBottomSuffix" => "px",
            "paddingLeft" => 0,
            "paddingLeftSuffix" => "px",

            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 0,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 0,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }

    /**
     * Возвращает тип высоты секции для GalleryLayoutElement темы Aurora
     * Возможные значения: 'auto', 'custom', 'full'
     *
     * @return string
     */
    protected function getSectionHeightType(): string
    {
        // По умолчанию используем 'auto', можно переопределить в дочерних классах
        return 'auto';
    }

    protected function getHeightSlideStyl(): string
    {
        return "custom";
    }

    /**
     * Возвращает тип высоты секции для мобильной версии GalleryLayoutElement темы Aurora
     * Возможные значения: 'auto', 'custom', 'full'
     *
     * @return string
     */
    protected function getMobileSectionHeightType(): string
    {
        // По умолчанию используем 'custom', можно переопределить в дочерних классах
        return 'auto';
    }


    protected function sectionIndentations(BrizyComponent $section)
    {
    }

    /**
     * Переопределение handleSectionStyles для применения параметров getPropertiesMainSection
     * с приоритетом над значениями из DOM
     */
    protected function handleSectionStyles(
        ElementContextInterface $data,
        BrowserPageInterface $browserPage,
        $additionalOptions = []
    ): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        $sectionStyles = $this->getSectionListStyle($data, $browserPage);

        if (!empty($additionalOptions['bg-gradient'])) {
            $sectionStyles['bg-gradient'] = $additionalOptions['bg-gradient'];
            unset($additionalOptions['bg-gradient']);
        }

        if (!empty($additionalOptions['bg-color'])) {
            $sectionStyles['background-color'] = $additionalOptions['bg-color']['bgColor'];
            $sectionStyles['background-opacity'] = $additionalOptions['bg-color']['bgOpacity'];
            $sectionStyles['opacity'] = $additionalOptions['bg-color']['bgOpacity'];
            unset($additionalOptions['bg-color']);
        }

        $options = [
            'heightType' => $this->getSectionHeightType(),
            'mobileHeightType' => $this->getMobileSectionHeightType()
        ];

        $this->handleSectionBackground($brizySection, $mbSectionItem, $sectionStyles, $options);

        $this->handleSectionTexture($brizySection, $mbSectionItem, $sectionStyles, $options);

        // set the background color paddings and margins
        // Приоритет: сначала $additionalOptions (включая getPropertiesMainSection), затем $sectionStyles как fallback
        $brizySection->getValue()
            ->set_paddingType($additionalOptions['paddingType'] ?? 'ungrouped')
            ->set_marginType('ungrouped')
            ->set_paddingTop((int)($additionalOptions['paddingTop'] ?? $sectionStyles['padding-top'] ?? 0))
            ->set_paddingBottom((int)($additionalOptions['paddingBottom'] ?? $sectionStyles['padding-bottom'] ?? 0))
            ->set_paddingRight((int)($additionalOptions['paddingRight'] ?? $sectionStyles['padding-right'] ?? 0))
            ->set_paddingLeft((int)($additionalOptions['paddingLeft'] ?? $sectionStyles['padding-left'] ?? 0))
            ->set_paddingSuffix($additionalOptions['paddingSuffix'] ?? 'px')
            ->set_paddingTopSuffix($additionalOptions['paddingTopSuffix'] ?? 'px')
            ->set_paddingRightSuffix($additionalOptions['paddingRightSuffix'] ?? 'px')
            ->set_paddingBottomSuffix($additionalOptions['paddingBottomSuffix'] ?? 'px')
            ->set_paddingLeftSuffix($additionalOptions['paddingLeftSuffix'] ?? 'px')
            ->set_marginLeft((int)($additionalOptions['marginLeft'] ?? $sectionStyles['margin-left'] ?? 0))
            ->set_marginRight((int)($additionalOptions['marginRight'] ?? $sectionStyles['margin-right'] ?? 0))
            ->set_marginTop((int)($additionalOptions['marginTop'] ?? $sectionStyles['margin-top'] ?? 0))
            ->set_marginBottom((int)($additionalOptions['marginBottom'] ?? $sectionStyles['margin-bottom'] ?? 0))
            ->set_fullHeight('custom')
            ->set_sectionHeight((int)($sectionStyles['height'] ?? 0))
            ->set_mobileBgSize('cover')
            ->set_mobileBgSizeType('original')
            ->set_mobileBgRepeat('off')
            ->set_mobilePaddingType($additionalOptions['mobilePaddingType'] ?? 'ungrouped')
            ->set_mobilePadding((int)($additionalOptions['mobilePadding'] ?? $sectionStyles['padding-top'] ?? 0))
            ->set_mobilePaddingSuffix($additionalOptions['mobilePaddingSuffix'] ?? 'px')
            ->set_mobilePaddingTop((int)($additionalOptions['mobilePaddingTop'] ?? $sectionStyles['padding-top'] ?? 0))
            ->set_mobilePaddingTopSuffix($additionalOptions['mobilePaddingTopSuffix'] ?? 'px')
            ->set_mobilePaddingRight((int)($additionalOptions['mobilePaddingRight'] ?? $sectionStyles['padding-right'] ?? 0))
            ->set_mobilePaddingRightSuffix($additionalOptions['mobilePaddingRightSuffix'] ?? 'px')
            ->set_mobilePaddingBottom((int)($additionalOptions['mobilePaddingBottom'] ?? $sectionStyles['padding-bottom'] ?? 0))
            ->set_mobilePaddingBottomSuffix($additionalOptions['mobilePaddingBottomSuffix'] ?? 'px')
            ->set_mobilePaddingLeft((int)($additionalOptions['mobilePaddingLeft'] ?? $sectionStyles['padding-left'] ?? 0))
            ->set_mobilePaddingLeftSuffix($additionalOptions['mobilePaddingLeftSuffix'] ?? 'px');

        try {
//            $brizySection->getParent()->getValue()
//                ->set_fullHeight('custom')
//                ->set_sectionHeightSuffix('px')
//                ->set_sectionHeight((int)$sectionStyles['height']);
        } catch (\Exception|Throwable $e) {

        }

        // Список параметров, которые уже были установлены явно выше и не должны перезаписываться
        $alreadySetProperties = [
            'paddingType', 'paddingTop', 'paddingBottom', 'paddingRight', 'paddingLeft',
            'paddingSuffix', 'paddingTopSuffix', 'paddingRightSuffix', 'paddingBottomSuffix', 'paddingLeftSuffix',
            'marginType', 'marginLeft', 'marginRight', 'marginTop', 'marginBottom',
            'mobilePaddingType', 'mobilePadding', 'mobilePaddingTop', 'mobilePaddingRight',
            'mobilePaddingBottom', 'mobilePaddingLeft',
            'mobilePaddingSuffix', 'mobilePaddingTopSuffix', 'mobilePaddingRightSuffix',
            'mobilePaddingBottomSuffix', 'mobilePaddingLeftSuffix',
            'fullHeight', 'sectionHeight', 'mobileBgSize', 'mobileBgSizeType', 'mobileBgRepeat'
        ];

        // Применяем только те параметры из $additionalOptions, которые еще не были установлены
        foreach ($additionalOptions as $key => $value) {
            if (is_array($value) || in_array($key, $alreadySetProperties)) {
                continue;
            }
            $method = 'set_' . $key;
            if (method_exists($brizySection->getValue(), $method)) {
                $brizySection->getValue()->$method($value);
            }
        }

        return $brizySection;
    }

    /**
     * Переопределение handleSectionBackground для обработки мобильной высоты
     */
    protected function handleSectionBackground(BrizyComponent $brizySection, $mbSectionItem, $sectionStyles, $options = ['heightType' => 'custom'])
    {
        // Вызываем родительский метод для базовой обработки
        parent::handleSectionBackground($brizySection, $mbSectionItem, $sectionStyles, $options);

        // Обрабатываем мобильную высоту, если она указана в options
        if (isset($options['mobileHeightType'])) {
            $mobileHeightType = $options['mobileHeightType'];

            // Определяем, на какой компонент устанавливать мобильную высоту
            // Для Gallery секции используем сам $brizySection, для других - getParent()
            $targetComponent = $brizySection;
            if ($brizySection->getType() != 'Section' && $brizySection->getParent() !== null) {
                $targetComponent = $brizySection->getParent();
            }

            if ($mobileHeightType == 'auto') {
                $targetComponent
                    ->getValue()
                    ->set_mobileHeightStyle('auto');
            } else if ($mobileHeightType == 'custom') {
                // Используем значение из getPropertiesMainSection или дефолтное
                $mobileHeight = isset($sectionStyles['height'])
                    ? (int)str_replace('px', '', $sectionStyles['height'])
                    : 300;
                $targetComponent
                    ->getValue()
                    ->set_mobileHeight($mobileHeight)
                    ->set_mobileHeightStyle('custom');
            } else if ($mobileHeightType == 'full') {
                $targetComponent
                    ->getValue()
                    ->set_mobileHeightStyle('full');
            }
        }
    }

}
