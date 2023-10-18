<?php

namespace MBMigration\Builder\Layout\Theme\Voyage;

use Exception;
use MBMigration\Browser\Browser;
use MBMigration\Browser\BrowserInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementData;
use MBMigration\Builder\Layout\Common\ElementDataInterface;
use MBMigration\Builder\Layout\Common\Exception\ElementNotFound;
use MBMigration\Builder\Layout\Common\MBElementFactoryInterface;
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
     * @var MBElementFactoryInterface
     */
    private $elementFactory;

    private $browserPageData;
    /**
     * @var BrowserInterface
     */
    private $browser;
    /**
     * @var string
     */
    private $mbPageUrl;
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
        string $mbPageUrl,
        array $brizyKit,
        array $mbMenu,
        array $mbHeadSection,
        array $mbFooterSection,
        array $families,
        string $defaultFamily,
        MBElementFactoryInterface $elementFactory,
        BrowserInterface $browser
    ) {
        $this->layoutName = 'Voyage';
        $this->brizyKit = $brizyKit;
        $this->mbMenu = $mbMenu;
        $this->elementFactory = $elementFactory;
        $this->browser = $browser;
        $this->mbPageUrl = $mbPageUrl;
        $this->mbHeadSection = $mbHeadSection;
        $this->mbFooterSection = $mbFooterSection;

        $this->browserPage = $this->browser->openPage($this->mbPageUrl, $this->layoutName);
        $this->families = $families;
        $this->defaultFamily = $defaultFamily;
    }

    /**
     * Pass all MB sections here.
     *
     * This method should return brizy sections
     *
     * @return void
     */
    public function transformBlocks(array $mbPageSections): BrizyComponent
    {
        $brizyPage = new BrizyComponent(['value' => ['items' => []]]);

        $elementContext = ElementData::instance($this->mbHeadSection, $brizyPage, $this->mbMenu, $this->families, $this->defaultFamily);
        $brizyPage->getValue()->add_items([
                $this->elementFactory->getElement('head')->transformToItem($elementContext),
            ]
        );


        foreach ($mbPageSections as $mbPageSection) {
            $elementName = $mbPageSection['typeSection'];
            try {
                $element = $this->elementFactory->getElement($elementName);
                $elementContext = ElementData::instance($mbPageSection, $brizyPage, [], $this->families, $this->defaultFamily);
                $brizyComponent = $element->transformToItem($elementContext);
                $brizyPage->getValue()->add_items([$brizyComponent]);

            } catch (ElementNotFound $e) {
                continue;
            }
        }

        $brizyPage->getValue()->add_items(
            [
                $this->elementFactory->getElement('footer')
                    ->transformToItem(ElementData::instance($this->mbFooterSection, $brizyPage,[], $this->families, $this->defaultFamily)),
            ]
        );

        return $brizyPage;
    }
}