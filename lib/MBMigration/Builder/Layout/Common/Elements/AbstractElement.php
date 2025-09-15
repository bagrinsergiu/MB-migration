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
        Logger::instance()->info('AbstractElement constructor called', [
            'element_class' => static::class,
            'section_name' => $this->getSectionName(),
            'brizy_kit_keys' => is_array($brizyKit) ? array_keys($brizyKit) : 'not_array',
            'browser_page_class' => get_class($browserPage)
        ]);

        $this->brizyKit = $brizyKit;
        $this->browserPage = $browserPage;

        Logger::instance()->info('AbstractElement initialized successfully', [
            'element_class' => static::class,
            'section_name' => $this->getSectionName(),
            'brizy_kit_count' => is_array($brizyKit) ? count($brizyKit) : 0
        ]);
    }

    protected function getSectionName(): string
    {
        return "main";
    }

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $startTime = microtime(true);
        Logger::instance()->info('AbstractElement::transformToItem called', [
            'element_class' => static::class,
            'section_name' => $this->getSectionName(),
            'data_class' => get_class($data),
            'start_time' => $startTime
        ]);

        try {
            Logger::instance()->info('Setting up transformation context', [
                'element_class' => static::class
            ]);

            $this->pageTDO = $data->getThemeContext()->getPageDTO();
            $this->themeContext = $data->getThemeContext();
            $this->globalBrizyKit = $data->getThemeContext()->getBrizyKit()['global'];

            Logger::instance()->info('Context setup completed, starting transformation process', [
                'element_class' => static::class,
                'page_id' => $this->pageTDO ? $this->pageTDO->getId() : null,
                'global_brizy_kit_keys' => array_keys($this->globalBrizyKit)
            ]);

            $this->initialBehavior($data);
            Logger::instance()->info('Initial behavior completed', ['element_class' => static::class]);

            $this->beforeTransformToItem($data);
            Logger::instance()->info('Before transform hook completed', ['element_class' => static::class]);

            $component = $this->internalTransformToItem($data);
            Logger::instance()->info('Internal transformation completed', [
                'element_class' => static::class,
                'component_type' => $component->getType()
            ]);

            $this->globalTransformSection($component);
            Logger::instance()->info('Global section transformation completed', ['element_class' => static::class]);

            $this->afterTransformToItem($component);
            Logger::instance()->info('After transform hook completed', ['element_class' => static::class]);

            $this->generalSectionBehavior($data, $component);
            Logger::instance()->info('General section behavior completed', ['element_class' => static::class]);

            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;

            Logger::instance()->info('Transform to item completed successfully', [
                'element_class' => static::class,
                'execution_time_ms' => round($executionTime, 2),
                'component_type' => $component->getType()
            ]);

        } catch (\Exception $e) {
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;

            Logger::instance()->error('Exception during transformation, falling back to internal transform', [
                'element_class' => static::class,
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e),
                'execution_time_ms' => round($executionTime, 2),
                'stack_trace' => $e->getTraceAsString()
            ]);
            $component = $this->internalTransformToItem($data);

            Logger::instance()->info('Fallback transformation completed', [
                'element_class' => static::class,
                'component_type' => $component->getType()
            ]);
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
        Logger::instance()->info('AbstractElement::canShowHeader called', [
            'element_class' => static::class,
            'section_category' => $mbSectionData['category'] ?? null,
            'has_settings' => isset($mbSectionData['settings'])
        ]);

        $sectionCategory = $mbSectionData['category'];
        $result = true;

        if (isset($mbSectionData['settings']['sections'][$sectionCategory]['show_header'])) {
            $result = $mbSectionData['settings']['sections'][$sectionCategory]['show_header'];
            Logger::instance()->info('Header visibility determined from settings', [
                'element_class' => static::class,
                'section_category' => $sectionCategory,
                'show_header' => $result
            ]);
        } else {
            Logger::instance()->info('Header visibility defaulted to true (no settings)', [
                'element_class' => static::class,
                'section_category' => $sectionCategory
            ]);
        }

        return $result;
    }

    protected function canShowBody($sectionData): bool
    {
        Logger::instance()->info('AbstractElement::canShowBody called', [
            'element_class' => static::class,
            'section_category' => $sectionData['category'] ?? null,
            'has_settings' => isset($sectionData['settings'])
        ]);

        $sectionCategory = $sectionData['category'];
        $result = true;

        if (isset($sectionData['settings']['sections'][$sectionCategory]['show_body'])) {
            $result = $sectionData['settings']['sections'][$sectionCategory]['show_body'];
            Logger::instance()->info('Body visibility determined from settings', [
                'element_class' => static::class,
                'section_category' => $sectionCategory,
                'show_body' => $result
            ]);
        } else {
            Logger::instance()->info('Body visibility defaulted to true (no settings)', [
                'element_class' => static::class,
                'section_category' => $sectionCategory
            ]);
        }

        return $result;
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
        Logger::instance()->info('AbstractElement::canShowButton called', [
            'element_class' => static::class,
            'section_category' => $sectionData['category'] ?? null,
            'has_settings' => isset($sectionData['settings'])
        ]);

        $sectionCategory = $sectionData['category'];
        $result = true;

        if (isset($sectionData['settings']['sections'][$sectionCategory]['show_buttons'])) {
            $result = $sectionData['settings']['sections'][$sectionCategory]['show_buttons'];
            Logger::instance()->info('Button visibility determined from settings', [
                'element_class' => static::class,
                'section_category' => $sectionCategory,
                'show_buttons' => $result
            ]);
        } else {
            Logger::instance()->info('Button visibility defaulted to true (no settings)', [
                'element_class' => static::class,
                'section_category' => $sectionCategory
            ]);
        }

        return $result;
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

    protected function handleItemPhotoAfter(ElementContextInterface $component)
    {
    }

    protected function getPropertiesItemPhoto(): array
    {
        return [];
    }

    protected function getHeightTypeHandleSectionStyles(): string
    {
        // default option custom
        // auto/custom/full
        return 'custom';
    }

    public function isBgHoverItemMenu(): bool
    {
        return false;
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
                if(empty($mbSection['items']) && empty($mbSection['head'])) {
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

    protected function sectionIndentations(BrizyComponent $section){
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
