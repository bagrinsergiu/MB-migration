<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;

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
     * @var RootPalette
     */
    private $palette;

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
        RootPalette $palette
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
        $this->palette = $palette;
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

    public function getRootPalette(): RootPalette
    {
        return $this->palette;
    }
}
