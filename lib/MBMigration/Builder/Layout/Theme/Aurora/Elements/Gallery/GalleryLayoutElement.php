<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Gallery;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use Throwable;

class GalleryLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Gallery\GalleryLayoutElement
{
    private const CONTAINER_WIDTH = 1170;
    private const DEFAULT_SLIDE_HEIGHT = 650;

    /**
     * Slide template is now SectionItem → Row → Column → Wrapper → Image.
     * Depth 0,0,0,0 navigates to the Image widget so imageSrc is set correctly.
     */
    protected function getSlideImageComponent(BrizyComponent $brizySectionItem): BrizyComponent
    {
        return $brizySectionItem->getItemWithDepth(0, 0, 0, 0);
    }

    /**
     * Return the Section itself so that slide SectionItems become its direct children,
     * which is the structure Brizy's slider component requires.
     */
    protected function getSlideLocation(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    /**
     * Aurora uses an Image widget (not a section background) so the image is
     * constrained by the page container width instead of stretching full-screen.
     * Height is computed proportionally for a ~1170 px container.
     */
    protected function setSlideImage(BrizyComponent $brizySectionItem, $mbItem, $properties = []): BrizyComponent
    {
        $brizyComponentValue = $brizySectionItem->getValue();

        $brizyComponentValue
            ->set_marginTop(0)
            ->set_marginBottom(0)
            ->set_imageSrc($mbItem['content'] ?? $mbItem['photo'] ?? '')
            ->set_imageFileName($mbItem['imageFileName'] ?? $mbItem['filename'] ?? '')
            ->set_sizeType('original')
            ->set_width(100)
            ->set_widthSuffix('%')
            ->set_tabletWidth(100)
            ->set_tabletWidthSuffix('%')
            ->set_mobileWidth(100)
            ->set_mobileWidthSuffix('%');

        if (isset($mbItem['settings']['slide']['extension'])) {
            $brizyComponentValue->set_imageExtension($mbItem['settings']['slide']['extension']);
        }

        // Compute proportional height for a ~1170px container
        $imageWidth = $mbItem['settings']['slide']['slide_width']
            ?? $mbItem['settings']['width']
            ?? $mbItem['imageWidth']
            ?? null;
        $imageHeight = $mbItem['settings']['slide']['slide_height']
            ?? $mbItem['settings']['height']
            ?? $mbItem['imageHeight']
            ?? null;

        if ($imageWidth && $imageHeight && (int)$imageWidth > 0) {
            $computedHeight = (int)round((int)$imageHeight * self::CONTAINER_WIDTH / (int)$imageWidth);
        } else {
            $computedHeight = self::DEFAULT_SLIDE_HEIGHT;
        }

        $brizyComponentValue
            ->set_heightStyle('custom')
            ->set_height($computedHeight)
            ->set_heightSuffix('px')
            ->set_tabletHeightStyle('custom')
            ->set_tabletHeight(max(300, (int)round($computedHeight * 0.7)))
            ->set_tabletHeightSuffix('px')
            ->set_mobileHeightStyle('custom')
            ->set_mobileHeight(250)
            ->set_mobileHeightSuffix('px');

        // Reset Wrapper (parent of Image) margins to override Brizy's default 10px top/bottom
        $wrapper = $brizySectionItem->getParent();
        if ($wrapper !== null) {
            $wrapper->getValue()
                ->set_marginTop(0)
                ->set_marginBottom(0)
                ->set_tabletMarginTop(0)
                ->set_tabletMarginBottom(0)
                ->set_mobileMarginTop(0)
                ->set_mobileMarginBottom(0);

            // Column (parent of Wrapper): mobile margin 0 on all sides for mobile (no gap between slides)
            $column = $wrapper->getParent();
            if ($column !== null) {
                $column->addMobileMargin([0, 0, 0, 0]);
            }
        }

        return $brizySectionItem;
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

            "marginTop" => 0,
            "marginBottom" => 0,

            "tabletPaddingTop" => 0,
            "tabletPaddingTopSuffix" => "px",
            "tabletPaddingBottom" => 0,
            "tabletPaddingBottomSuffix" => "px",
            "tabletMarginTop" => 0,
            "tabletMarginBottom" => 0,

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

            "mobileMarginType" => "ungrouped",
            "mobileMarginTop" => 0,
            "mobileMarginBottom" => 0,
            "mobileMarginTopSuffix" => "px",
            "mobileMarginBottomSuffix" => "px",
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

        // Gallery sections use Image widgets for slides; handleSectionBackground is intentionally skipped.
        // Texture is applied to the parent Section so it is not lost when set_items() replaces slide children.
        $brizyParentSection = $brizySection->getParent();
        $textureTarget = $brizyParentSection !== null ? $brizyParentSection : $brizySection;
        $this->handleSectionTexture($textureTarget, $mbSectionItem, $sectionStyles, $options);

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
            ->set_tabletPaddingTop((int)($additionalOptions['tabletPaddingTop'] ?? 0))
            ->set_tabletPaddingTopSuffix($additionalOptions['tabletPaddingTopSuffix'] ?? 'px')
            ->set_tabletPaddingBottom((int)($additionalOptions['tabletPaddingBottom'] ?? 0))
            ->set_tabletPaddingBottomSuffix($additionalOptions['tabletPaddingBottomSuffix'] ?? 'px')
            ->set_tabletMarginTop((int)($additionalOptions['tabletMarginTop'] ?? 0))
            ->set_tabletMarginBottom((int)($additionalOptions['tabletMarginBottom'] ?? 0))
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

        // fullHeight/sectionHeight are Section-level properties; set them on the parent Section.
        if ($brizyParentSection !== null) {
            $brizyParentSection->getValue()
                ->set_fullHeight('auto');
        }

        // Список параметров, которые уже были установлены явно выше и не должны перезаписываться
        $alreadySetProperties = [
            'paddingType', 'paddingTop', 'paddingBottom', 'paddingRight', 'paddingLeft',
            'paddingSuffix', 'paddingTopSuffix', 'paddingRightSuffix', 'paddingBottomSuffix', 'paddingLeftSuffix',
            'marginType', 'marginLeft', 'marginRight', 'marginTop', 'marginBottom',
            'tabletPaddingTop', 'tabletPaddingTopSuffix', 'tabletPaddingBottom', 'tabletPaddingBottomSuffix',
            'tabletMarginTop', 'tabletMarginBottom',
            'mobilePaddingType', 'mobilePadding', 'mobilePaddingTop', 'mobilePaddingRight',
            'mobilePaddingBottom', 'mobilePaddingLeft',
            'mobilePaddingSuffix', 'mobilePaddingTopSuffix', 'mobilePaddingRightSuffix',
            'mobilePaddingBottomSuffix', 'mobilePaddingLeftSuffix',
            'mobileBgSize', 'mobileBgSizeType', 'mobileBgRepeat'
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
     * Aurora gallery uses Image widgets as slides; section-level background is not needed
     * because slides visually cover the full section area. The parent's type guard would also
     * block application when the parent Section is passed, so this is intentionally a no-op.
     */
    protected function handleSectionBackground(BrizyComponent $brizySection, $mbSectionItem, $sectionStyles, $options = ['heightType' => 'custom'])
    {
    }

    /**
     * Mobile: negative top/bottom margins on Section to reduce spacing;
     * first slide gets padding 20 L/R, other slides get 0.
     */
    protected function customizationSection(BrizyComponent $brizySection): BrizyComponent
    {
        $brizySection->getValue()
            ->set_mobileMarginType('ungrouped')
            ->set_mobileMarginTop(-20)
            ->set_mobileMarginTopSuffix('px')
            ->set_mobileMarginBottom(-21)
            ->set_mobileMarginBottomSuffix('px');

        $items = $brizySection->getValue()->get('items') ?? [];
        foreach ($items as $index => $sectionItem) {
            if (!$sectionItem instanceof BrizyComponent) {
                continue;
            }
            $value = $sectionItem->getValue();
            if ($index === 0) {
                $sectionItem->addMobilePadding([0, 20, 0, 20]);
            } else {
                $sectionItem->addMobilePadding([0, 0, 0, 0]);
            }
        }

        return $brizySection;
    }

}
