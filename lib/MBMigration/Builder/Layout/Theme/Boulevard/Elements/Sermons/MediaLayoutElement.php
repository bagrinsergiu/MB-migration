<?php
namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Sermons;

use Exception;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\Concern\Effects\ShadowAble;
use MBMigration\Builder\Layout\Common\ElementContext;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class MediaLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Sermons\MediaLayoutElement
{

    use LineAble;
    use ShadowAble;

    /**
     * @var \MBMigration\Builder\Layout\Common\ThemeInterface|null
     */
    private $currentThemeInstance = null;

    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
        // Сохраняем themeInstance для использования в createDetailsCollectionItem
        $this->currentThemeInstance = $data->getThemeInstance();

        $brizySection = parent::internalTransformToItem($data);
        $mbSectionItem = $data->getMbSection();

        $brizySection->getItemWithDepth(0)->addMargin(0, 30, 0, 0, '', '%');

        $showHeader = $this->canShowHeader($mbSectionItem);

        $mbSectionItem['items'] = $this->sortItems($mbSectionItem['items']);

        if($showHeader) {
            $titleMb = $this->getItemByType($mbSectionItem, 'title');
            $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                $mbSectionItem,
                $brizySection->getItemWithDepth(0)
            );

            $this->handleLine($elementContext, $this->browserPage, $titleMb['id'], null, [], 1, null, '');
        }

        $this->handleShadow($brizySection);


        return $brizySection;
    }

    protected function getSelectorSectionCustomCSS(): string
    {
        return 'element';
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

    /**
     * Переопределяем метод для создания полной страницы деталей с head и footer
     */
    protected function createDetailsCollectionItem($collectionTypeUri, $pageData, $slug = 'sermon-detail', $title = 'Sermon Detail')
    {
        // Получаем layout для детальной страницы (используем тот же layout, что и для основной страницы)
        $kit = $this->themeContext->getBrizyKit();
        try {
            $layout = json_decode($kit['Theme']['Boulevard']['layout'], true);
            $detailPageLayout = new BrizyComponent($layout);
        } catch (Exception $e) {
            $layout = ['value' => ['items' => []]];
            $detailPageLayout = new BrizyComponent($layout);
        }

        // Применяем стили layout (как в Boulevard::handleLayoutStyle)
        $this->handleDetailPageLayoutStyle($detailPageLayout);

        // Получаем ElementFactory и BrowserPage
        $elementFactory = $this->themeContext->getElementFactory();
        $browserPage = $this->themeContext->getBrowserPage();
        $themeInstance = $this->currentThemeInstance;

        if (!$themeInstance) {
            // Fallback: создаем новый экземпляр темы, если не был сохранен
            $themeName = $this->themeContext->getThemeName();
            $themeClass = "\\MBMigration\\Builder\\Layout\\Theme\\{$themeName}\\{$themeName}";
            if (class_exists($themeClass)) {
                $themeInstance = new $themeClass();
                $themeInstance->setThemeContext($this->themeContext);
            } else {
                throw new \Exception("Cannot create theme instance for {$themeName}");
            }
        }

        // Создаем ElementContext для head
        $headContext = ElementContext::instance(
            $themeInstance,
            $this->themeContext,
            $this->themeContext->getMbHeadSection(),
            $detailPageLayout,
            $detailPageLayout->getItemWithDepth(0, 0, 0),
            $this->themeContext->getBrizyMenuEntity(),
            $this->themeContext->getBrizyMenuItems(),
            $this->themeContext->getFamilies(),
            $this->themeContext->getDefaultFamily()
        );

        // Добавляем head в layout (как в Boulevard::transformBlocks)
        $headElement = $elementFactory->getElement('head', $browserPage);
        $headElement->transformToItem($headContext);

        // Добавляем детали в layout (в секцию контента, как в Boulevard)
        // $pageData уже является BrizyComponent с деталями
        if ($pageData instanceof BrizyComponent) {
            $pageData->addPadding(0,0,0,0)
            ->addTabletPadding();
            $detailPageLayout->getItemWithDepth(0, 0, 1)->getValue()->add_items([$pageData]);
        }

        // Добавляем footer в layout (как в Boulevard::transformBlocks)
        $footerContext = ElementContext::instance(
            $themeInstance,
            $this->themeContext,
            $this->themeContext->getMbFooterSection(),
            $detailPageLayout,
            $detailPageLayout->getItemWithDepth(0, 0, 0),
            $this->themeContext->getBrizyMenuEntity(),
            $this->themeContext->getBrizyMenuItems(),
            $this->themeContext->getFamilies(),
            $this->themeContext->getDefaultFamily()
        );
        $footerElement = $elementFactory->getElement('footer', $browserPage);
        $footerElement->transformToItem($footerContext);

        // Передаем весь layout как один компонент (как в Boulevard::transformBlocks)
        return parent::createDetailsCollectionItem($collectionTypeUri, $detailPageLayout, $slug, $title);
    }

    /**
     * Применяет стили layout для детальной страницы (аналогично Boulevard::handleLayoutStyle)
     */
    protected function handleDetailPageLayoutStyle(BrizyComponent $brizyComponent): BrizyComponent
    {
        $brizyComponent->getItemWithDepth(0, 0, 0)
            ->getValue()
            ->set_borderStyle('none')
            ->set_width(15);

        $brizyComponent->getItemWithDepth(0, 0, 1)
            ->getValue()
            ->set_width(85);

        $brizyComponent->getItemWithDepth(0)
            ->addMobilePadding();

        $brizyComponent->getItemWithDepth(0, 0, 1)
            ->addMobileMargin()
            ->addMobilePadding()
            ->addTabletPadding()
            ->addPadding(0,0,0,0);

        return $brizyComponent;
    }
}
