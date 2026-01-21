<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\Concern\Effects\ShadowAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;

class ListLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\ListLayoutElement
{
    use LineAble;
    use ShadowAble;

    protected function getSelectorSectionCustomCSS(): string
    {
        return 'element';
    }

    protected function getHeaderComponent(BrizyComponent $brizyComponent): BrizyComponent
    {
        return $brizyComponent->getItemWithDepth(0);
    }

    protected function getItemTextContainerComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition,
        $mbItem = null
    ): BrizyComponent {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 1 : 0);
    }

    protected function getItemImageComponent(
        BrizyComponent $brizyComponent,
        string $photoPosition
    ): BrizyComponent {
        return $brizyComponent->getItemWithDepth($photoPosition == 'left' ? 0 : 1, 0,0);
    }

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection
            ->getItemWithDepth(0)
            ->addMargin(0, 30, 0, 0,  '', '%');

    }

    protected function transformListItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = [], ?BrizyComponent $brizyParent = null): BrizyComponent
    {
        return $brizySection;
    }

    protected function getCalculateLineWidth($width)
    {
        return 100;
    }

    protected function transformHeadItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        $mbSectionItem = $data->getMbSection();
        $showHeader = $this->canShowHeader($mbSectionItem);

        if($showHeader) {
            $titleMb = $this->getByType($mbSectionItem['head'], 'title');
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem,
                $brizySection
            );

            $this->handleLine(
                $elementContext,
                $this->browserPage,
                $titleMb['id'],
                null,
                ['widthSuffix' => '%'],
                1,
                null
            );
        }

        $this->handleShadow($brizySection);

        return $brizySection;
    }

    protected function handleRowListItem(BrizyComponent $brizySection, $position = 'left'): void
    {
        if ($position == 'left') {
            $brizySection
                ->getItemWithDepth(1)
                ->addPadding(15,0,15,20);

            $brizySection->getItemWithDepth(0)->getValue()->set_width(30);
            $brizySection->getItemWithDepth(1)->getValue()->set_width(70);
        } else {
            $brizySection
                ->getItemWithDepth(0)
                ->addPadding(15,20,15,0);
        }
    }

    protected function handleCustomStylesSection(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->addPadding(0,0,0,0);
    }

    protected function customSettings(): array
    {
        return  [];
    }

    protected function handleMbPhotoItem(ElementContextInterface $data, $brizySectionItem, $photoPosition, $mbItem)
    {
        $elementContext = $data->instanceWithBrizyComponentAndMBSection(
            $mbItem,
            $this->getItemImageComponent($brizySectionItem, $photoPosition)
        );
        $this->handleRichTextItem($elementContext, $this->browserPage, null, [], $this->customSettings());
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "paddingType" => "ungrouped",
            "paddingTop" => 90,
            "paddingBottom" => 90,

            "mobilePaddingType"=> "ungrouped",
            "mobilePadding" => 0,
            "mobilePaddingSuffix" => "px",
            "mobilePaddingTop" => 50,
            "mobilePaddingTopSuffix" => "px",
            "mobilePaddingRight" => 20,
            "mobilePaddingRightSuffix" => "px",
            "mobilePaddingBottom" => 50,
            "mobilePaddingBottomSuffix" => "px",
            "mobilePaddingLeft" => 20,
            "mobilePaddingLeftSuffix" => "px",
        ];
    }
}
