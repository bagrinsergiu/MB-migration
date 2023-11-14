<?php

namespace MBMigration\Builder\Layout\Theme\Voyage;

use Exception;
use MBMigration\Browser\Browser;
use MBMigration\Browser\BrowserInterface;
use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyPage;
use MBMigration\Builder\Layout\Common\ElementData;
use MBMigration\Builder\Layout\Common\ElementDataInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Layout\Common\ThemeElementFactoryInterface;
use MBMigration\Builder\Layout\Common\ThemeInterface;
use MBMigration\Builder\Layout\ElementsController;
use MBMigration\Builder\Layout\LayoutUtils;
use MBMigration\Builder\Utils\PathSlugExtractor;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class Voyage extends LayoutUtils implements ThemeInterface
{
    private $brizyKit;

    /**
     * @var mixed
     */
    protected $jsonDecode;

    protected $layoutName;

    /**
     * @var VariableCache
     */
    public $cache;

    /**
     * @var array
     */
    private $mbMenu;

    /**
     * @var ThemeElementFactoryInterface
     */
    private $elementFactory;

    private $browserPageData;
    /**
     * @var BrowserInterface
     */
    private $browser;
    /**
     * @var array
     */
    private $mbHeadSection;
    /**
     * @var array
     */
    private $mbFooterSection;

    /**
     * @var \MBMigration\Browser\BrowserPageInterface
     */
    private $browserPage;
    /**
     * @var array
     */
    private $families;
    /**
     * @var string
     */
    private $defaultFamily;

    /**
     * @throws Exception
     */
    public function __construct(
        BrowserPage $browserPage,
        array $brizyKit,
        array $mbMenu,
        array $mbHeadSection,
        array $mbFooterSection,
        array $families,
        string $defaultFamily,
        ThemeElementFactoryInterface $elementFactory,
        BrowserInterface $browser
    ) {
        $this->layoutName = 'Voyage';
        $this->brizyKit = $brizyKit;
        $this->mbMenu = $mbMenu;
        $this->elementFactory = $elementFactory;
        $this->browser = $browser;
        $this->mbHeadSection = $mbHeadSection;
        $this->mbFooterSection = $mbFooterSection;

        $this->families = $families;
        $this->defaultFamily = $defaultFamily;
        $this->browserPage = $browserPage;
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

        $elementContext = ElementData::instance(
            $this->mbHeadSection,
            $brizyComponent,
            $this->mbMenu,
            $this->families,
            $this->defaultFamily
        );
        $brizyPage->addItem($this->elementFactory->getElement('head')->transformToItem($elementContext));

        foreach ($mbPageSections as $mbPageSection) {
            $elementName = $mbPageSection['typeSection'];
            try {
                $element = $this->elementFactory->getElement($elementName);
                $elementContext = ElementData::instance(
                    $mbPageSection,
                    $brizyComponent,
                    [],
                    $this->families,
                    $this->defaultFamily
                );
                $brizySection = $element->transformToItem($elementContext);
                $brizyPage->addItem($brizySection);

            } catch (ElementNotFound|BrowserScriptException $e) {
                continue;
            }
        }

        $brizyPage->addItem(
            $this->elementFactory->getElement('footer')
                ->transformToItem(
                    ElementData::instance(
                        $this->mbFooterSection,
                        $brizyComponent,
                        [],
                        $this->families,
                        $this->defaultFamily
                    )
                )
        );

        return $brizyPage;
    }
}