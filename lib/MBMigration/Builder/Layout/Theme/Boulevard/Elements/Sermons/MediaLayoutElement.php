<?php
namespace MBMigration\Builder\Layout\Theme\Boulevard\Elements\Sermons;

use Exception;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\LineAble;
use MBMigration\Builder\Layout\Common\Concern\Effects\ShadowAble;
use MBMigration\Builder\Layout\Common\ElementContext;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\FontUtils;
use MBMigration\Core\Logger;

class MediaLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Sermons\MediaLayoutElement
{

    use LineAble;
    use ShadowAble;

    /**
     * @var \MBMigration\Builder\Layout\Common\ThemeInterface|null
     */
    private $currentThemeInstance = null;

    /** @var array<string, array{layout: array, detail: array}> */
    private $cachedTypographyBySection = [];

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

    /**
     * {@inheritdoc}
     */
    protected function getAdditionalOptionsForDetailPage(
        \MBMigration\Builder\Layout\Common\ElementContextInterface $data,
        array $baseOptions
    ): array {
        try {
            $typographyData = $this->extractTypographyFromDom($data);
            $detailTypography = $typographyData['detail'] ?? [];
            if (!empty($detailTypography)) {
                $baseOptions['typography'] = $detailTypography;
            }
        } catch (\Throwable $e) {
            Logger::instance()->warning(
                'Media typography extraction failed: ' . $e->getMessage(),
                ['Boulevard MediaLayoutElement', 'getAdditionalOptionsForDetailPage']
            );
        }

        return $baseOptions;
    }

    /**
     * {@inheritdoc}
     */
    protected function applyTypographyToFeaturedDescription(
        BrizyComponent $component,
        \MBMigration\Builder\Layout\Common\ElementContextInterface $data
    ): void {
        try {
            $typographyData = $this->extractTypographyFromDom($data);
            $detailTypography = $typographyData['detail'] ?? [];
            if (!empty($detailTypography)) {
                $value = $component->getValue();
                foreach ($detailTypography as $key => $val) {
                    $value->set($key, $val);
                }
            }
        } catch (\Throwable $e) {
            Logger::instance()->warning(
                'Media featured typography failed: ' . $e->getMessage(),
                ['Boulevard MediaLayoutElement', 'applyTypographyToFeaturedDescription']
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getTypographyForGridSection(
        \MBMigration\Builder\Layout\Common\ElementContextInterface $data
    ): array {
        try {
            $typographyData = $this->extractTypographyFromDom($data);

            return $typographyData['layout'] ?? [];
        } catch (\Throwable $e) {
            Logger::instance()->warning(
                'Media grid typography failed: ' . $e->getMessage(),
                ['Boulevard MediaLayoutElement', 'getTypographyForGridSection']
            );

            return [];
        }
    }

    private function extractTypographyFromDom(ElementContextInterface $data): array
    {
        $mbSection = $data->getMbSection();
        $sectionId = $mbSection['sectionId'] ?? $mbSection['id'];

        if (isset($this->cachedTypographyBySection[$sectionId])) {
            return $this->cachedTypographyBySection[$sectionId];
        }

        $sectionSelector = '[data-id="' . $sectionId . '"]';
        $families = $data->getFontFamilies();
        $defaultFamily = $data->getDefaultFontFamily();

        $titleFont = $this->resolveFontBySelectors(
            [
                $sectionSelector . ' .text-content.title-text',
                $sectionSelector . ' .media-video-title',
                $sectionSelector . ' .item-title',
            ],
            $families,
            $defaultFamily,
            $data
        );

        $textFont = $this->resolveFontBySelectors(
            [
                $sectionSelector . ' .text-content.text-1',
                $sectionSelector . ' .media-description',
                $sectionSelector . ' .media-speaker',
            ],
            $families,
            $defaultFamily,
            $data
        );

        $buttonFont = $this->resolveFontBySelectors(
            [
                $sectionSelector . ' a.btn',
                $sectionSelector . ' .subscribe-btn',
                $sectionSelector . ' .media-item',
            ],
            $families,
            $defaultFamily,
            $data
        );

        $buttonFont = $buttonFont ?? $textFont;
        $textFont = $textFont ?? $titleFont;
        $titleFont = $titleFont ?? $textFont;

        $layoutTypography = [];
        if (!empty($titleFont)) {
            $layoutTypography['titleTypographyFontFamily'] = $titleFont['family'];
            $layoutTypography['titleTypographyFontFamilyType'] = $titleFont['type'];
            $layoutTypography['resultsHeadingTypographyFontFamily'] = $titleFont['family'];
            $layoutTypography['resultsHeadingTypographyFontFamilyType'] = $titleFont['type'];
        }
        if (!empty($textFont)) {
            $layoutTypography['typographyFontFamily'] = $textFont['family'];
            $layoutTypography['typographyFontFamilyType'] = $textFont['type'];
        }
        if (!empty($buttonFont)) {
            $layoutTypography['detailButtonTypographyFontFamily'] = $buttonFont['family'];
            $layoutTypography['detailButtonTypographyFontFamilyType'] = $buttonFont['type'];
        }

        $detailTypography = [];
        if (!empty($titleFont)) {
            $detailTypography['titleTypographyFontFamily'] = $titleFont['family'];
            $detailTypography['titleTypographyFontFamilyType'] = $titleFont['type'];
        }
        if (!empty($textFont)) {
            $detailTypography['typographyFontFamily'] = $textFont['family'];
            $detailTypography['typographyFontFamilyType'] = $textFont['type'];
            $detailTypography['previewTypographyFontFamily'] = $textFont['family'];
            $detailTypography['previewTypographyFontFamilyType'] = $textFont['type'];
            $detailTypography['dateTypographyFontFamily'] = $textFont['family'];
            $detailTypography['dateTypographyFontFamilyType'] = $textFont['type'];
        }
        if (!empty($buttonFont)) {
            $detailTypography['detailButtonTypographyFontFamily'] = $buttonFont['family'];
            $detailTypography['detailButtonTypographyFontFamilyType'] = $buttonFont['type'];
            $detailTypography['subscribeEventButtonTypographyFontFamily'] = $buttonFont['family'];
            $detailTypography['subscribeEventButtonTypographyFontFamilyType'] = $buttonFont['type'];
        }

        $result = [
            'layout' => $layoutTypography,
            'detail' => $detailTypography,
        ];
        $this->cachedTypographyBySection[$sectionId] = $result;

        return $result;
    }

    private function resolveFontBySelectors(
        array $selectors,
        array &$families,
        string $defaultFamily,
        ElementContextInterface $data
    ): ?array {
        foreach ($selectors as $selector) {
            $styles = $this->getDomElementStyles(
                $selector,
                ['font-family'],
                $this->browserPage,
                $families,
                $defaultFamily
            );

            $resolvedFont = $this->resolveDomFont($styles['font-family'] ?? null, $families, $data);
            if ($resolvedFont !== null) {
                return $resolvedFont;
            }
        }

        return null;
    }

    private function resolveDomFont(?string $domFontFamily, array &$families, ElementContextInterface $data): ?array
    {
        if (empty($domFontFamily)) {
            return null;
        }

        $keysToTry = [
            FontUtils::transliterateFontFamily($domFontFamily),
            FontUtils::normalizeFontFamilyFull($domFontFamily),
            FontUtils::normalizeFontFamilyFirst($domFontFamily),
        ];
        foreach (FontUtils::extractFontFamilyParts($domFontFamily) as $part) {
            $keysToTry[] = $part;
        }
        $keysToTry = array_filter(array_unique($keysToTry));

        $fontKey = null;
        foreach ($keysToTry as $key) {
            if (!empty($key) && isset($families[$key])) {
                $fontKey = $key;
                break;
            }
        }

        if ($fontKey === null) {
            $primaryKey = FontUtils::transliterateFontFamily($domFontFamily);
            if (!empty($primaryKey)) {
                try {
                    $data->getThemeContext()
                        ->getFontsController()
                        ->upLoadFont($domFontFamily, $primaryKey, '[Boulevard MediaLayoutElement] extractTypographyFromDom');
                    $families = FontsController::getFontsFamily()['kit'];
                    $data->getThemeContext()->setFamilies($families);
                    if (isset($families[$primaryKey])) {
                        $fontKey = $primaryKey;
                    }
                } catch (\Throwable $exception) {
                    return null;
                }
            }
        }

        if ($fontKey === null || !isset($families[$fontKey])) {
            return null;
        }

        $font = $families[$fontKey];
        $fontFamily = $font['name'] ?? $font['fontname'] ?? null;
        $fontType = $font['type'] ?? 'upload';
        if (empty($fontFamily) || empty($fontType)) {
            return null;
        }

        return [
            'family' => $fontFamily,
            'type' => $fontType,
        ];
    }
}
