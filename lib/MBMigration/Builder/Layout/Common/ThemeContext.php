<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPage;
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
     * @var BrowserPage
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
     * @var array
     */
    private $mbMenu;
    /**
     * @var string
     */
    private $slug;

    public function __construct(
        string $layoutName,
        BrowserPageInterface $browserPage,
        array $brizyKit,
        array $mbMenu,
        array $mbHeadSection,
        array $mbFooterSection,
        array $families,
        string $defaultFamily,
        ThemeElementFactoryInterface $elementFactory,
        string $brizyCollectionTypeURI,
        string $brizyCollectionItemURI,
        string $slug
    ) {
        $this->layoutName = $layoutName;
        $this->brizyKit = $brizyKit;
        $this->mbMenu = $mbMenu;
        $this->elementFactory = $elementFactory;
        $this->mbHeadSection = $mbHeadSection;
        $this->mbFooterSection = $mbFooterSection;
        $this->families = $families;
        $this->defaultFamily = $defaultFamily;
        $this->browserPage = $browserPage;
        $this->brizyCollectionTypeURI = $brizyCollectionTypeURI;
        $this->brizyCollectionItemURI = $brizyCollectionItemURI;
        $this->slug = $slug;
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

    public function getMbMenu(): array
    {
        return $this->mbMenu;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}