<?php

namespace MBMigration\Builder\Layout\Common\Elements;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\TextsExtractorAware;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class AbstractElement implements ElementInterface
{
    use CssPropertyExtractorAware;
    use MbSectionUtils;
    use TextsExtractorAware;

    protected array $brizyKit = [];

    protected array $headParams = [];

    protected array $basicHeadParams = [];

    protected BrowserPageInterface $browserPage;

    private QueryBuilder $queryBuilder;

    public function __construct($brizyKit, BrowserPageInterface $browserPage)
    {
        $this->brizyKit = $brizyKit;
        $this->browserPage = $browserPage;
    }

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $this->beforeTransformToItem($data);
        $component = $this->internalTransformToItem($data);
        $this->afterTransformToItem($component);

        return $component;
    }

    public function getBasicHeadParams(): array
    {
        return $this->basicHeadParams;
    }

    /**
     * Returns and Brizy fully build section ready to be inserted in page data.
     *
     * @return array
     */
    abstract protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent;

    protected function getSectionItemComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0);
    }

    protected function canShowHeader($mbSectionData): bool
    {
        $sectionCategory = $mbSectionData['category'];

        if (isset($mbSectionData['settings']['sections'][$sectionCategory]['show_header'])) {
            return $mbSectionData['settings']['sections'][$sectionCategory]['show_header'];
        }

        return true;
    }

    protected function canShowBody($sectionData): bool
    {
        $sectionCategory = $sectionData['category'];
        if (isset($sectionData['settings']['sections'][$sectionCategory]['show_body'])) {
            return $sectionData['settings']['sections'][$sectionCategory]['show_body'];
        }

        return true;
    }


    protected function beforeTransformToItem(ElementContextInterface $data): void
    {

    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "margin-left" => 0,
            "margin-right" => 0,
        ];
    }
}
