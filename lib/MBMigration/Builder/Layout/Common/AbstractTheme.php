<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyPage;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Core\Logger;

abstract class AbstractTheme implements ThemeInterface
{
    protected ThemeContextInterface $themeContext;

    public int $projectID;
    
    /**
     * @var string Имя элемента для фильтрации (мигрировать только этот элемент)
     */
    private string $mb_element_name = '';
    
    /**
     * @var bool Пропустить загрузку медиа
     */
    private bool $skip_media_upload = false;
    
    /**
     * @var bool Пропустить использование кэша
     */
    private bool $skip_cache = false;

    public function setThemeContext(ThemeContextInterface $themeContext)
    {
        $this->themeContext = $themeContext;
        $this->projectID = $themeContext->getProjectID();
    }
    
    /**
     * Установить параметры миграции для быстрого тестирования
     * 
     * @param string $mb_element_name Имя элемента для фильтрации
     * @param bool $skip_media_upload Пропустить загрузку медиа
     * @param bool $skip_cache Пропустить использование кэша
     */
    public function setMigrationOptions(string $mb_element_name = '', bool $skip_media_upload = false, bool $skip_cache = false): void
    {
        $this->mb_element_name = $mb_element_name;
        $this->skip_media_upload = $skip_media_upload;
        $this->skip_cache = $skip_cache;
    }

    /**
     * Pass all MB sections here.
     *
     * This method should return brizy sections
     *
     * @return void
     */
    public function transformBlocks(array $mbPageSections): BrizyPage
    {
        $brizyPage = new BrizyPage;
        $brizyComponent = new BrizyComponent(['value' => ['items' => []]]);
        $elementFactory = $this->themeContext->getElementFactory();
        $browserPage = $this->themeContext->getBrowserPage();

        $this->fontHandle($browserPage);

        $brizyPage = $this->beforeTransformBlocks($brizyPage, $mbPageSections);

        $elementContext = ElementContext::instance(
            $this,
            $this->themeContext,
            $this->themeContext->getMbHeadSection(),
            $brizyComponent,
            $brizyComponent,
            $this->themeContext->getBrizyMenuEntity(),
            $this->themeContext->getBrizyMenuItems(),
            $this->themeContext->getFamilies(),
            $this->themeContext->getDefaultFamily()
        );

        $this->addSectionIfNeeded($mbPageSections);

        Logger::instance()->debug("Handling [head] page section.");
        $elementFactory->getElement('head', $browserPage)->transformToItem($elementContext);
        $elementsList = ['event'];
        $processedItems = [];
        $processedEventsSectionCount = [];

        foreach ($mbPageSections as $events){
            $elementName = explode("-", $events['typeSection']);
            if (in_array($elementName[0], $elementsList)) {
                $processedEventsSectionCount[] = $events['typeSection'];
            }
        }

        foreach ($mbPageSections as $mbPageSection) {

            $elementName = explode("-", $mbPageSection['typeSection']);

            if (in_array($elementName[0], $elementsList)) {
                if (!in_array($elementName[0], $processedItems)) {
                    $processedItems[] = $elementName[0];
                } else {
                    continue;
                }
            }

            if (count($processedEventsSectionCount) > 1 )
            {
                $mbPageSection['typeSection'] = 'event-calendar-layout';
            }

            $elementName = $mbPageSection['typeSection'];
            
            // Фильтрация по элементу: если указан mb_element_name, мигрируем только этот элемент
            if (!empty($this->mb_element_name)) {
                // Сравниваем имя элемента (может быть с префиксом или без)
                $elementNameBase = explode("-", $elementName)[0];
                $targetElementBase = explode("-", $this->mb_element_name)[0];
                
                // Пропускаем элементы, которые не совпадают
                if ($elementName !== $this->mb_element_name && $elementNameBase !== $targetElementBase) {
                    Logger::instance()->debug("Skipping [$elementName] - filtering by mb_element_name: [{$this->mb_element_name}]");
                    continue;
                }
                
                Logger::instance()->info("Migrating only element: [$elementName] (filtered by mb_element_name: [{$this->mb_element_name}])");
            }
            
            Logger::instance()->debug("Handling [$elementName] page section.");
            try {
                $element = $elementFactory->getElement($elementName,$browserPage);
                $elementContext = ElementContext::instance(
                    $this,
                    $this->themeContext,
                    $mbPageSection,
                    $brizyComponent,
                    $brizyComponent,
                    $this->themeContext->getBrizyMenuEntity(),
                    $this->themeContext->getBrizyMenuItems(),
                    $this->themeContext->getFamilies(),
                    $this->themeContext->getDefaultFamily()
                );

                $brizySection = $element->transformToItem($elementContext);
                $brizyPage->addItem($brizySection);
                
                // Если тестируем один элемент, сохраняем JSON результат секции
                if (!empty($this->mb_element_name)) {
                    try {
                        // Получаем JSON представление секции
                        $sectionJson = json_encode($brizySection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                        
                        // Сохраняем в кэше для последующего сохранения в БД
                        $cacheKey = 'test_migration_element_result_' . $this->mb_element_name;
                        $this->themeContext->getProjectID(); // Получаем projectID для контекста
                        
                        // Используем VariableCache для сохранения результата
                        $cache = \MBMigration\Builder\VariableCache::getInstance();
                        $cache->set($cacheKey, [
                            'element_name' => $elementName,
                            'section_json' => $sectionJson,
                            'timestamp' => time(),
                            'mb_element_name' => $this->mb_element_name
                        ]);
                        
                        Logger::instance()->info("Saved element result JSON for testing", [
                            'element_name' => $elementName,
                            'json_length' => strlen($sectionJson)
                        ]);
                    } catch (\Exception $saveEx) {
                        Logger::instance()->warning("Failed to save element result JSON: " . $saveEx->getMessage());
                    }
                }
            } catch (ElementNotFound|BrowserScriptException|BadJsonProvided $e) {
                Logger::instance()->error($e->getMessage(), $e->getTrace());
                continue;
            }
        }

        $elementFactory->getElement('footer',$browserPage)
            ->transformToItem(
                ElementContext::instance(
                    $this,
                    $this->themeContext,
                    $this->themeContext->getMbFooterSection(),
                    $brizyComponent,
                    $brizyComponent,
                    $this->themeContext->getBrizyMenuEntity(),
                    $this->themeContext->getBrizyMenuItems(),
                    $this->themeContext->getFamilies(),
                    $this->themeContext->getDefaultFamily()
                )
            );
        Logger::instance()->debug("Handling [footer] page section.");
        $brizyPage = $this->afterTransformBlocks($brizyPage, $mbPageSections);

        return $brizyPage;
    }

    public function beforeTransformBlocks(BrizyPage $page, array $mbPageSections): BrizyPage
    {
        return $page;
    }

    public function afterTransformBlocks(BrizyPage $page, array $mbPageSections): BrizyPage
    {
        return $page;
    }

    /**
     * Adds an extra section before the target section in the array
     * if the specified conditions are met.
     */
    public function addSectionIfNeeded(array &$mbPageSections)
    {
    }

    public function beforeBuildPage(): array
    {
        return [];
    }

    protected function fontHandle(BrowserPageInterface $browserPage)
    {
        $this->themeContext->getFontsController()->refreshFontInProject($browserPage);
    }

    /**
     * Determines if the head element should be cached.
     * Override this method in specific themes to disable caching if needed.
     *
     * @return bool
     */
    public function useHeadElementCached(): bool
    {
        return true;
    }

    /**
     * Determines if the footer element should be cached.
     * Override this method in specific themes to disable caching if needed.
     *
     * @return bool
     */
    public function useFooterElementCached(): bool
    {
        return true;
    }
}
