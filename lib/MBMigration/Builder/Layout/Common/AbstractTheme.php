<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyPage;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;

abstract class AbstractTheme implements ThemeInterface
{
    /**
     * @var ThemeContextInterface
     */
    protected $themeContext;


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

        $brizyPage = $this->beforeTransformBlocks($brizyPage, $mbPageSections);

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
                printf("\nException: %s", $e->getMessage());
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

        $brizyPage = $this->afterTransformBlocks($brizyPage, $mbPageSections);

        return $brizyPage;
    }

    public function beforeTransformBlocks(BrizyPage $page, array $mbPageSections): BrizyPage
    {
        $browserPage = $this->themeContext->getBrowserPage();

        $selectorIcon = $this->getThemeIconSelector(); //"[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]";
        if ($browserPage->triggerEvent('hover', $selectorIcon)) {
            $browserPage->evaluateScript('Globals.js', []);
            $browserPage->triggerEvent('hover', 'html');
        }

        $selectorButton = $this->getThemeButtonSelector(); //".sites-button:not(.nav-menu-button)";
        if ($browserPage->triggerEvent('hover', $selectorButton)) {
            $browserPage->evaluateScript('Globals.js', []);
            $browserPage->triggerEvent('hover', 'html');
        }

        $selector = $this->getThemeMenuItemSelector(); //"#main-navigation li:not(.selected) a";
        if ($browserPage->triggerEvent('hover', $selector)) {
            $browserPage->evaluateScript('GlobalMenu.js', []);
            $browserPage->triggerEvent('hover', 'html');
        }

        $browserPage->setNodeStyles($this->getThemeSubMenuItemSelector(), ['display' => 'block','visibility'=>'visible']);
        if ($browserPage->triggerEvent('hover', $this->getThemeSubMenuItemSelector())) {
            $browserPage->evaluateScript('GlobalMenu.js', []);
            $browserPage->triggerEvent('hover', 'html');
        }

        return $page;
    }

    public function afterTransformBlocks(BrizyPage $page, array $mbPageSections): BrizyPage
    {
        return $page;
    }
}