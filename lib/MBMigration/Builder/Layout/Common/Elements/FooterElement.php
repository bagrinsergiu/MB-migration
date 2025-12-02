<?php

namespace MBMigration\Builder\Layout\Common\Elements;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Cacheable;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Layer\Brizy\BrizyAPI;

abstract class FooterElement extends AbstractElement
{
    const CACHE_KEY = 'footer';
    use Cacheable;
    use RichTextAble;
    use SectionStylesAble;

    protected BrizyAPI $brizyAPIClient;
    protected BrizyComponent $pageLayout;

    public function __construct($brizyKit, BrowserPageInterface $browserPage, BrizyAPI $brizyAPI)
    {
        parent::__construct($brizyKit, $browserPage);

        $this->brizyAPIClient = $brizyAPI;
    }

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $this->pageTDO = $data->getThemeContext()->getPageDTO();
        $this->themeContext = $data->getThemeContext();

        $this->pageLayout = $data->getPageLayout();

        return $this->getCache(self::CACHE_KEY, function () use ($data): BrizyComponent {
            $this->beforeTransformToItem($data);
            $component = $this->internalTransformToItem($data);
            $this->afterTransformToItem($component);

            if ($this->makeGlobalBlock()) {
                $position = '{"align":"bottom","top":1,"bottom":1}';
                $rules = '[{"type":1,"appliedFor":null,"entityType":"","entityValues":[]}]';

                $this->brizyAPIClient->createGlobalBlock(json_encode($component), $position, $rules);
            }

            return $component;
        });
    }

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $mbSection = $data->getMbSection();
        $brizySectionItemComponent = $this->getSectionItemComponent($brizySection);

        $sortItems = $this->sortItems($mbSection['items']);
        foreach ($sortItems as $items) {
            $column = $this->getFooterColumnElement($brizySectionItemComponent, $items['group']);
            $elementContext = $data->instanceWithBrizyComponent($column);
            $this->handleItemMbSection($items, $elementContext);
        }

        $additionalOptions = array_merge($data->getThemeContext()->getPageDTO()->getPageStyleDetails(), $this->getPropertiesMainSection());

        $this->handleSectionStyles($elementContext, $this->browserPage, $additionalOptions);

        return $brizySection;
    }

    protected function getFooterColumnElement(BrizyComponent $brizySection, $index): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function makeGlobalBlock(): bool
    {
        return true;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "marginLeft" => 0,
            "marginRight" => 0,
        ];
    }

    protected function handleItemMbSection($mbSection, ElementContextInterface $elementContext)
    {
        switch ($mbSection['category']) {
            case 'text':
                $this->handleOnlyRichTextItem($elementContext, $this->browserPage);
                break;
            case 'photo':
                if (!empty($mbSection['content'])) {
                    $arrowSelector = '[data-id="' . ($mbSection['sectionId'] ?? $mbSection['id']) . '"]';

                    $imgStyles = $this->getDomElementStyles(
                        $arrowSelector,
                        ['width', 'height'],
                        $this->browserPage,
                    );

                    if (!empty($imgStyles)) {
                        $additionalParams = [
                            'sizeType' => 'custom',
                            'width' => (int)$imgStyles['width'],
                            'height' => (int)$imgStyles['height'],
                            'imageWidth' => (int)$imgStyles['width'],
                            'imageHeight' => (int)$imgStyles['height'],
                            'widthSuffix' => 'px',
                            'heightSuffix' => 'px',

                        ];
                    } else {
                        $additionalParams = [
                            'sizeType' => 'custom',
                            'width' => 360,
                            'height' => 40,
                            'widthSuffix' => 'px',
                            'heightSuffix' => 'px',
                        ];
                    }

                    $brizySectionItemComponent = $elementContext->getBrizySection();

                    $brizySectionItemComponent->addImage($mbSection, $additionalParams);
                }
                break;

        }

    }

}

