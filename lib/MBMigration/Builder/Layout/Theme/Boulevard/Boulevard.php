<?php

namespace MBMigration\Builder\Layout\Theme\Boulevard;

use Exception;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyPage;
use MBMigration\Builder\Layout\Common\AbstractTheme;
use MBMigration\Builder\Layout\Common\ElementContext;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Core\Logger;

class Boulevard extends AbstractTheme
{

    public function transformBlocks(array $mbPageSections): BrizyPage
    {
        $brizyPage = new BrizyPage;

        $kit = $this->themeContext->getBrizyKit();

        try {
            $layout = json_decode($kit['Theme']['Boulevard']['layout'], true);
            $brizyComponent = new BrizyComponent($layout);
        } catch (Exception $e) {
            $layout = ['value' => ['items' => []]];
            $brizyComponent = new BrizyComponent($layout);
        }


        $elementFactory = $this->themeContext->getElementFactory();
        $browserPage = $this->themeContext->getBrowserPage();

        $this->fontHandle($browserPage);

        $brizyPage = $this->beforeTransformBlocks($brizyPage, $mbPageSections);

        $elementContext = ElementContext::instance(
            $this,
            $this->themeContext,
            $this->themeContext->getMbHeadSection(),
            $brizyComponent,
            $brizyComponent->getItemWithDepth(0, 0, 0),
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

        foreach ($mbPageSections as $events) {
            $elementName = explode("-", $events['typeSection']);
            if (in_array($elementName[0], $elementsList)) {
                $processedEventsSectionCount[] = $events['typeSection'];
            }
        }

        $elementContext = ElementContext::instance(
            $this,
            $this->themeContext,
            $mbPageSections,
            $brizyComponent,
            $brizyComponent->getItemWithDepth(0, 0),
            $this->themeContext->getBrizyMenuEntity(),
            $this->themeContext->getBrizyMenuItems(),
            $this->themeContext->getFamilies(),
            $this->themeContext->getDefaultFamily()
        );

        $elementFactory->getElement('section', $browserPage)->transformToItem($elementContext);

        foreach ($mbPageSections as $mbPageSection) {

            $elementName = explode("-", $mbPageSection['typeSection']);

            if (in_array($elementName[0], $elementsList)) {
                if (!in_array($elementName[0], $processedItems)) {
                    $processedItems[] = $elementName[0];
                } else {
                    continue;
                }
            }

            if (count($processedEventsSectionCount) > 1) {
                $mbPageSection['typeSection'] = 'event-calendar-layout';
            }

            $elementName = $mbPageSection['typeSection'];
            Logger::instance()->debug("Handling [$elementName] page section.");
            try {
                $element = $elementFactory->getElement($elementName, $browserPage);
                $elementContext = ElementContext::instance(
                    $this,
                    $this->themeContext,
                    $mbPageSection,
                    $brizyComponent,
                    $brizyComponent->getItemWithDepth(0, 0, 1),
                    $this->themeContext->getBrizyMenuEntity(),
                    $this->themeContext->getBrizyMenuItems(),
                    $this->themeContext->getFamilies(),
                    $this->themeContext->getDefaultFamily()
                );

                $brizySection = $element->transformToItem($elementContext);

                $brizyComponent->getItemWithDepth(0, 0, 1)->getValue()->add_items([$brizySection]);

            } catch (ElementNotFound|BrowserScriptException|BadJsonProvided $e) {
                Logger::instance()->error($e->getMessage(), $e->getTrace());
                continue;
            }
        }

        $elementFactory->getElement('footer', $browserPage)
            ->transformToItem(
                ElementContext::instance(
                    $this,
                    $this->themeContext,
                    $this->themeContext->getMbFooterSection(),
                    $brizyComponent,
                    $brizyComponent->getItemWithDepth(0, 0, 0),
                    $this->themeContext->getBrizyMenuEntity(),
                    $this->themeContext->getBrizyMenuItems(),
                    $this->themeContext->getFamilies(),
                    $this->themeContext->getDefaultFamily()
                )
            );

        $brizyPage->addItem($brizyComponent);
        Logger::instance()->debug("Handling [footer] page section.");
        $brizyPage = $this->afterTransformBlocks($brizyPage, $mbPageSections);

        return $brizyPage;
    }

    public function useHeadElementCached(): bool
    {
        return false;
    }

    public function useFooterElementCached(): bool
    {
        return false;
    }

    public function getThemeIconSelector(): string
    {
        return "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]";
    }

    public function getThemeButtonSelector(): string
    {
        return ".sites-button:not(.nav-menu-button)";
    }

}
