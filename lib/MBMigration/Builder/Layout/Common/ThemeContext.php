<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserInterface;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Browser\BrowserPHP;
use MBMigration\Builder\BrizyComponent\BrizyComponentBuilder;
use MBMigration\Builder\Layout\Common\DTO\PageDto;

final class ThemeContext implements ThemeContextInterface
{
    /**
     * @var string
     */
    private $layoutName;

    /**
     * @var array
     */
    private $brizyKit;

    /**
     * @var ThemeElementFactoryInterface
     */
    private $elementFactory;

    /**
     * @var BrowserPageInterface
     */
    private $browserPage;

    /**
     * @var BrowserInterface
     */
    private $browser;

    /**
     * @var RootPalettes
     */
    private $rootPalettes;

    /**
     * @var array
     */
    private $families;

    /**
     * @var string
     */
    private $defaultFamily;

    /**
     * @var array
     */
    private $mbHeadSection;

    /**
     * @var array
     */
    private $mbFooterSection;

    /**
     * @var string
     */
    private $brizyCollectionTypeURI;

    /**
     * @var string
     */
    private $brizyCollectionItemURI;
    /**
     * @var string
     */
    private $slug;
    private array $brizyMenuEntity;
    private array $brizyMenuItems;
    private array $urlMap = [];
    private array $listSeries;
    private PageDto $pageDTO;

    public function __construct(
        string $layoutName,
        BrowserPageInterface $browserPage,
        array $brizyKit,
        array $brizyMenuEntity,
        array $brizyMenuItems,
        array $mbHeadSection,
        array $mbFooterSection,
        array $families,
        string $defaultFamily,
        ThemeElementFactoryInterface $elementFactory,
        string $brizyCollectionTypeURI,
        string $brizyCollectionItemURI,
        string $slug,
        array $urlMap,
        RootPalettesInterface $RootPalettes,
        BrowserInterface $browser,
        array $listSeries,
        PageDto $pageDTO
    ) {
        $this->layoutName = $layoutName;
        $this->brizyKit = $brizyKit;
        $this->elementFactory = $elementFactory;
        $this->mbHeadSection = $mbHeadSection;
        $this->mbFooterSection = $mbFooterSection;
        $this->families = $families;
        $this->defaultFamily = $defaultFamily;
        $this->browserPage = $browserPage;
        $this->brizyCollectionTypeURI = $brizyCollectionTypeURI;
        $this->brizyCollectionItemURI = $brizyCollectionItemURI;
        $this->slug = $slug;
        $this->brizyMenuEntity = $brizyMenuEntity;
        $this->brizyMenuItems = $brizyMenuItems;
        $this->urlMap = $urlMap;
        $this->rootPalettes = $RootPalettes;
        $this->browser = $browser;
        $this->listSeries = $listSeries;
        $this->pageDTO = $pageDTO;
    }

    public function getLayoutName(): string
    {
        return $this->layoutName;
    }

    public function getBrizyKit(): array
    {
        return $this->brizyKit;
    }

    public function getElementFactory(): ThemeElementFactoryInterface
    {
        return $this->elementFactory;
    }

    public function getBrowserPage(): BrowserPageInterface
    {
        return $this->browserPage;
    }

    public function getFamilies(): array
    {
        return $this->families;
    }

    public function setFamilies($families): self
    {
        $this->families = $families;

        return $this;
    }

    public function getDefaultFamily(): string
    {
        return $this->defaultFamily;
    }

    public function getMbHeadSection(): array
    {
        return $this->mbHeadSection;
    }

    public function getMbFooterSection(): array
    {
        return $this->mbFooterSection;
    }

    public function getBrizyCollectionTypeURI(): string
    {
        return $this->brizyCollectionTypeURI;
    }

    public function getBrizyCollectionItemURI(): string
    {
        return $this->brizyCollectionItemURI;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getBrizyMenuEntity(): array
    {
        return $this->brizyMenuEntity;
    }

    public function getBrizyMenuItems(): array
    {
        return $this->brizyMenuItems;
    }

    public function getUrlMap(): array
    {
        return $this->urlMap;
    }

    public function getRootPalettes(): RootPalettesInterface
    {
        return $this->rootPalettes;
    }

    public function getBrowser(): BrowserInterface
    {
        return $this->browser;
    }

    public function getListSeries(): array
    {
        return $this->listSeries;
    }

    public function getPageDTO(): PageDto
    {
        return $this->pageDTO;
    }

    public function getBrizyComponentBuilder(): BrizyComponentBuilder
    {
        return new BrizyComponentBuilder($this->getBrizyKit());
    }
}
