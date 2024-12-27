<?php

namespace MBMigration\Builder\Layout\Common\Elements;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\TextsExtractorAware;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Core\Logger;
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
        $this->globalTransformSection($component);
        $this->afterTransformToItem($component);

        $this->generalSectionBehavior($data, $component);

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

    protected function getTabTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection;
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

    protected function emptyContentSectionItem(array $sectionItem): bool
    {
        if(array_key_exists('content', $sectionItem)) {
            if(!empty(strip_tags($sectionItem['content'])))
            {
                return false;
            }
        }

        return true;
    }

    protected function canShowButton($sectionData): bool
    {
        $sectionCategory = $sectionData['category'];
        if (isset($sectionData['settings']['sections'][$sectionCategory]['show_buttons'])) {
            return $sectionData['settings']['sections'][$sectionCategory]['show_buttons'];
        }

        return true;
    }

    protected function transformItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        return $brizySection;
    }

    protected function transformHeadItem(ElementContextInterface $data, BrizyComponent $brizySection, array $params = []): BrizyComponent
    {
        return $brizySection;
    }

    protected function beforeTransformToItem(ElementContextInterface $data): void
    {

    }

    protected function afterTransformToItem(BrizyComponent $brizySection): void
    {
    }

    protected function afterTransformTabs(BrizyComponent $brizySection): void
    {
    }

    private function globalTransformSection(BrizyComponent $component)
    {
        try {
            $section = $component->getItemWithDepth(0);
                if($section !== null){
                    $section->addCustomCSS('@media (max-width: 768px) {.brz-a.brz-btn {white-space: normal;}}');
                }
        } catch (\Exception $e) {
            Logger::instance()->error($e->getMessage());
        }

    }

    private function generalSectionBehavior(ElementContextInterface $data, BrizyComponent $section): void
    {
        $mbSection = $data->getMbSection();

        if($section !== null){

            if(!$this->canShowBody($mbSection) && !$this->canShowHeader($mbSection))
            {
                $section
                    ->getItemWithDepth(0)
                    ->addGroupedPadding()
                    ->addGroupedMargin()
                    ->addMobilePadding()
                    ->addMobileMargin()
                    ->addTabletPadding()
                    ->addTabletMargin();
            }

            if($this->emptyContentSectionItem($mbSection['items'][0] ?? [])
                && $this->emptyContentSectionItem($mbSection['items'][1] ?? []))
            {
                $section
                    ->getItemWithDepth(0)
                    ->addGroupedPadding()
                    ->addGroupedMargin()
                    ->addMobilePadding()
                    ->addMobileMargin()
                    ->addTabletPadding()
                    ->addTabletMargin();
            }
        }
    }

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function getTypeItemImageComponent(): string
    {
        return 'bg';
    }

    protected function getPropertiesMainSection(): array
    {
        return [
            "marginLeft" => 0,
            "marginRight" => 0,
        ];
    }
}
