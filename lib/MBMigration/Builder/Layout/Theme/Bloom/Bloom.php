<?php

namespace MBMigration\Builder\Layout\Theme\Bloom;

use DOMDocument;
use Exception;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyPage;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\Layout\AnthemElementsController;
use MBMigration\Builder\Layout\Common\ElementContext;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Common\ThemeContextInterface;
use MBMigration\Builder\Layout\Common\ThemeInterface;
use MBMigration\Builder\Layout\Layout;
use MBMigration\Builder\Layout\LayoutUtils;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Bloom extends LayoutUtils implements ThemeInterface
{

    /**
     * @var ThemeContextInterface
     */
    private $themeContext;

    /**
     * @throws Exception
     */
    public function __construct(ThemeContextInterface $themeContext)
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

        $elementContext = ElementContext::instance(
            $this->themeContext,
            $this->themeContext->getMbHeadSection(),
            $brizyComponent,
            $this->themeContext->getMbMenu(),
            $this->themeContext->getFamilies(),
            $this->themeContext->getDefaultFamily()
        );

        $brizyPage->addItem($elementFactory->getElement('head')->transformToItem($elementContext));

        foreach ($mbPageSections as $mbPageSection) {
            $elementName = $mbPageSection['typeSection'];
            try {
                $element = $elementFactory->getElement($elementName);
                $elementContext = ElementContext::instance(
                    $this->themeContext,
                    $mbPageSection,
                    $brizyComponent,
                    $this->themeContext->getMbMenu(),
                    $this->themeContext->getFamilies(),
                    $this->themeContext->getDefaultFamily()
                );

                $brizySection = $element->transformToItem($elementContext);
                $brizyPage->addItem($brizySection);
            } catch (ElementNotFound|BrowserScriptException $e) {
                continue;
            }
        }

        $brizyPage->addItem(
            $elementFactory->getElement('footer')
                ->transformToItem(
                    ElementContext::instance(
                        $this->themeContext,
                        $this->themeContext->getMbFooterSection(),
                        $brizyComponent,
                        $this->themeContext->getMbMenu(),
                        $this->themeContext->getFamilies(),
                        $this->themeContext->getDefaultFamily()
                    )
                )
        );

        return $brizyPage;
    }
}