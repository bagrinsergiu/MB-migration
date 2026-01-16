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

    public function setThemeContext(ThemeContextInterface $themeContext)
    {
        $this->themeContext = $themeContext;
        $this->projectID = $themeContext->getProjectID();
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
