<?php

namespace MBMigration\Builder\Layout\Common\Elements;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\Component\Button;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\Concern\MbSectionUtils;
use MBMigration\Builder\Layout\Common\Concern\TextsExtractorAware;
use MBMigration\Builder\Layout\Common\DTO\PageDto;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\ElementInterface;
use MBMigration\Builder\Layout\Common\ThemeContextInterface;
use MBMigration\Core\Logger;
use MBMigration\Layer\Graph\QueryBuilder;

abstract class AbstractElement implements ElementInterface
{
    use CssPropertyExtractorAware;
    use MbSectionUtils;
    use TextsExtractorAware;
    use Button;

    public PageDto $pageTDO;
    public ThemeContextInterface $themeContext;
    public array $globalBrizyKit;
    protected array $brizyKit = [];
    protected array $headParams = [];
    protected array $basicHeadParams = [];
    protected BrowserPageInterface $browserPage;
    private QueryBuilder $queryBuilder;
    private array $buttonStyleNormal;
    private array $buttonStyleHover;


    public function __construct($brizyKit, BrowserPageInterface $browserPage)
    {
        $this->brizyKit = $brizyKit;
        $this->browserPage = $browserPage;
    }

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        try {
            $this->pageTDO = $data->getThemeContext()->getPageDTO();
            $this->themeContext = $data->getThemeContext();
            $this->globalBrizyKit = $data->getThemeContext()->getBrizyKit()['global'];

            $this->initialBehavior($data);

            $this->beforeTransformToItem($data);
            $component = $this->internalTransformToItem($data);
            $this->globalTransformSection($component);
            $this->afterTransformToItem($component);

            $this->generalSectionBehavior($data, $component);

        } catch (\Exception $e) {
            Logger::instance()->error($e->getMessage(), ['AbstractElement', 'transformToItem']);
            $component = $this->internalTransformToItem($data);
        }

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

    protected function emptyBackgroundContentSection(array $sectionItem): bool
    {
        if(empty($sectionItem['settings']['sections']['background']['photo']))
        {
            return true;
        }
        return false;
    }

    protected function emptyContentSectionItem(array $sectionItem): bool
    {
        if (empty($sectionItem)) {
            return true;
        }

        switch ($sectionItem['category']) {
            case 'list':
            case 'media':
                return false;
        }

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
    }

    protected function getHeightTypeHandleSectionStyles(): string
    {
        // default option custom
        // auto/custom/full
        return 'custom';
    }


    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 50;
    }

    protected function getMobileTopMarginOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getAdditionalTopPaddingOfTheFirstElement(): int
    {
        return 0;
    }

    protected function getAdditionalTopPaddingOfDetailPage(): int
    {
        return 0;
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

    protected function getDonationsButtonOptions(): array
    {
        return [
            'mobilePaddingTop' => 0,
            'mobilePaddingRight' => 0,
            'mobilePaddingBottom' => 0,
            'mobilePaddingLeft' => 0,
        ];
    }

    private function initialBehavior(ElementContextInterface $data): void
    {
        $this->getBasicStyleForButton($data);

        $this->buttonStyleNormal = $this->pageTDO->getButtonStyle()->getNormal();
        $this->buttonStyleHover = $this->pageTDO->getButtonStyle()->getHover();
    }

    private function generalSectionBehavior(ElementContextInterface $data, BrizyComponent $section): void
    {
        $mbSection = $data->getMbSection();

        $this->behaviorForAddingIndentsInSection($mbSection, $section);

    }

    private function getBasicStyleForButton(ElementContextInterface $data)
    {
        if(!$this->pageTDO->getButtonStyle()->hasData()){
            $buttonStyle = $this->getButtonStyle($data);
            $this->pageTDO->getButtonStyle()->setHover($buttonStyle['hover'])->setNormal($buttonStyle['normal']);
        }
    }

    private function behaviorForAddingIndentsInSection($mbSection, BrizyComponent $section){
        switch ($mbSection['category']){
            case 'list':
                if(empty($mbSection['items'])) {
                    $this->sectionIndentations($section);
                }
                break;
            case 'media':
            case 'accordion':
                break;
            default:

                if(!$this->emptyBackgroundContentSection($mbSection)){
                    $this->pageTDO->getPageStyle()->setPreviousSectionEmpty();
                    break;
                }

                if($section->getItemWithDepth(0) !== null){
                    if(!$this->canShowBody($mbSection) && !$this->canShowHeader($mbSection))
                    {
                        $this->sectionIndentations($section);
                    } else {
                        if($this->emptyContentSectionItem($mbSection['items'][0] ?? [])
                            && $this->emptyContentSectionItem($mbSection['items'][1] ?? []))
                        {
                            $this->sectionIndentations($section);
                        } else {
                            $this->pageTDO->getPageStyle()->setPreviousSectionEmpty();
                        }
                    }
                }
                break;
        }
    }

    private function sectionIndentations(BrizyComponent $section){
        $section
            ->getItemWithDepth(0)
            ->addPadding($this->pageTDO->getHeadStyle()->getHeight() ?? 50, 0, 50, 0)
            ->addGroupedMargin()
            ->addMobilePadding()
            ->addMobileMargin()
            ->addTabletPadding()
            ->addTabletMargin();

        $this->pageTDO->getPageStyle()->setPreviousSectionEmpty(true);
    }

    protected function getThemeMenuHeaderStyle($headStyles, $section): BrizyComponent
    {
        return $section;
    }
}
