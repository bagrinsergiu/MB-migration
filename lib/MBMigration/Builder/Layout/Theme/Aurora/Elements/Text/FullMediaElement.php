<?php

namespace MBMigration\Builder\Layout\Theme\Aurora\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Elements\Text\FullMediaElementElement;

class FullMediaElement extends FullMediaElementElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function getTextContainerComponent(BrizyComponent $brizySection): BrizyComponent {
        return $brizySection->getItemWithDepth(0,0,0);
    }

    protected function getImageComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0,0,0);
    }

    protected function getImageWrapperComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
    }

    protected function getInsideItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0,0,0);
    }

    protected function imageIndexPosition(): ?int
    {
        return 1;
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);
        $mbSectionItem = $data->getMbSection();

        $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);
        $brizyInsideSectionItemComponent = $this->getInsideItemComponent($brizySection);

        $elementContext = $data->instanceWithBrizyComponent($brizySectionItemComponent);
        $elementInsideContext = $data->instanceWithBrizyComponent($brizyInsideSectionItemComponent);

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        $styleList = $this->getSectionListStyle($elementInsideContext, $this->browserPage);

        $this->transformItem($elementInsideContext, $brizyInsideSectionItemComponent, $styleList);

        $this->setTopPaddingOfTheFirstElement($data, $brizySectionItemComponent);

        $this->handleOnlyRichTextItems($elementInsideContext, $this->browserPage);
        $this->handleDonationsButton($elementInsideContext, $this->browserPage, $this->brizyKit, $this->getDonationsButtonOptions());

        $brizyImageWrapperComponent = $this->getImageWrapperComponent($brizySection);
        $brizyImageComponent = $this->getImageComponent($brizySection);

        // configure the image wrapper
        $brizyImageWrapperComponent->getValue()
            ->set_marginType("ungrouped")
            ->set_margin(0)
            ->set_marginSuffix("px")
            ->set_marginTop(0)
            ->set_marginTopSuffix("px")
            ->set_marginRight(0)
            ->set_marginRightSuffix("px")
            ->set_marginBottom(0)
            ->set_marginBottomSuffix("px")
            ->set_marginLeft(0)
            ->set_marginLeftSuffix("px");

        $brizyImageComponent->getValue()
            ->set_width(100)
            ->set_mobileSize(100)
            ->set_widthSuffix('%')
            ->set_height('')
            ->set_heightSuffix('');

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);
        $images = $this->getItemsByCategory($mbSectionItem, 'photo');
        $imageMb = array_pop($images);
        $this->handlePhotoItem(
            $imageMb['id'],
            $imageMb,
            $brizyImageComponent,
            $this->browserPage,
            $this->customSettings(),
            $this->imageIndexPosition()
        );

        return $brizySection;
    }

    protected function transformItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        $this->handleItemBackground($brizySection, $params);
        return $brizySection;
    }

    protected function afterTransformItem(ElementContextInterface $data, BrizyComponent $brizySection): void
    {
        $mbSectionItem = $data->getMbSection();
        $selectId = $mbSectionItem['id'] ?? $mbSectionItem['sectionId'];

        $sectionSelector = '[data-id="' .$selectId. '"] .bg-helper>.bg-opacity';
        $styles = $this->browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $sectionSelector,
                'styleProperties' => ['background-color','opacity'],
                'families' => [],
                'defaultFamily' => '',
            ]
        );

        // Получаем градиент из дополнительных опций, если он есть
        $additionalOptions = $data->getThemeContext()->getPageDTO()->getPageStyleDetails();
        if (!empty($additionalOptions['bg-gradient'])) {
            $styles['data']['bg-gradient'] = $additionalOptions['bg-gradient'];
        }

        // Устанавливаем градиент или цвет фона на SectionItem
        $sectionItemComponent = $this->getSectionItemComponent($brizySection);
        if (!empty($styles['data']['bg-gradient'])) {
            $this->handleSectionGradient($sectionItemComponent, $styles['data']);
            
            // Добавляем параметры для градиента на SectionItem
            $sectionItemValue = $sectionItemComponent->getValue();
            $sectionItemValue->set('gradientActivePointer', 'finishPointer');
        } else {
            $this->handleItemBackground($sectionItemComponent, $styles['data']);
        }
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function sectionIndentations(BrizyComponent $section)
    {
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
}
