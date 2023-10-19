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
     * @throws Exception
     */
    public function __construct(
        string $mbPageUrl,
        array $brizyKit,
        array $mbMenu,
        array $mbHeadSection,
        array $mbFooterSection,
        MBElementFactoryInterface $elementFactory,
        BrowserInterface $browser,
        array $browserPageData = []
    ) {
        $this->layoutName = 'Aurora';
        $this->brizyKit = $brizyKit;
        $this->mbMenu = $mbMenu;
        $this->elementFactory = $elementFactory;
        $this->browserPageData = $browserPageData;
        $this->browser = $browser;
        $this->mbPageUrl = $mbPageUrl;
        $this->mbHeadSection = $mbHeadSection;
        $this->mbFooterSection = $mbFooterSection;

        $this->browserPage = $this->browser->openPage($this->mbPageUrl);
    }

    /**
     * Pass all MB sections here.
     *
     * This method should return brizy sections
     *
     * @return void
     */
    public function transformBlocks(array $mbPageSections): array
    {
        $brizyBlocks = ['items' => []];
        //$brizyBlocks['items'][] = $this->elementFactory->getElement('head')->transformToItem(ElementData::instance($this->mbHeadSection,  $this->mbMenu));

        foreach ($mbPageSections as $mbPageSection) {
            $elementName = $mbPageSection['typeSection'];
            try {
                $elementContext = ElementData::instance($mbPageSection);
                $element = $this->elementFactory->getElement($elementName);
                $brizyBlocks['items'][] = $element->transformToItem($elementContext);
            } catch (ElementNotFound $e) {
                continue;
            }
        }

//        $brizyBlocks['items'][] = $this->elementFactory->getElement('footer')
//            ->transformToItem(ElementData::instance($this->mbFooterSection, $browserBlockData['footer'], $this->mbMenu));

        return $brizyBlocks;
    }
}