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

    public function setThemeContext(ThemeContextInterface $themeContext)
    {
        $this->themeContext = $themeContext;
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
            $this->themeContext->getBrizyMenuEntity(),
            $this->themeContext->getBrizyMenuItems(),
            $this->themeContext->getFamilies(),
            $this->themeContext->getDefaultFamily()
        );

        Logger::instance()->debug("Handling [head] page section.");
        $elementFactory->getElement('head', $browserPage)->transformToItem($elementContext);

        $elementsList = ['event'];
        $processedItems = [];

        foreach ($mbPageSections as $mbPageSection) {

            $elementName = explode("-", $mbPageSection['typeSection']);

            if (in_array($elementName[0], $elementsList)) {
                if (!in_array($elementName[0], $processedItems)) {
                    $processedItems[] = $elementName[0];
                } else {
                    continue;
                }
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

    public function beforeBuildPage(): array
    {
        return [];
    }

    private function fontHandle(BrowserPageInterface $browserPage)
    {
        $this->themeContext->getFontsController()->refreshFontInProject($browserPage);
    }
}
