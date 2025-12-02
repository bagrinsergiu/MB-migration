<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Layer\Brizy\BrizyAPI;

final class ElementContext implements ElementContextInterface
{
    private array $mbSection;
    private array $fontFamilies;
    private string $defaultFontFamily;
    private array $brizyMenuEntity;
    private array $brizyMenuItems;

    private BrizyComponent $sectionLayout ;
    private ThemeInterface $themeInstance;
    private BrizyComponent $brizyComponent;
    private ThemeContextInterface $themeContext;

    static public function instance(
        ThemeInterface $themeInstance,
        ThemeContextInterface $themeContext,
        array $mbSection,
        BrizyComponent $sectionLayout,
        BrizyComponent $brizyComponent = null,
        array $brizyMenuEntity = [],
        array $brizyMenuItems = [],
        array $fontFamilies = [],
        string $defaultFontFamily = ''
    ): self {
        return new self(
            $themeInstance,
            $themeContext,
            $mbSection,
            $sectionLayout,
            $brizyComponent,
            $brizyMenuEntity,
            $brizyMenuItems,
            $fontFamilies,
            $defaultFontFamily
        );
    }

    public function instanceWithBrizyComponent(BrizyComponent $brizyComponent): ElementContextInterface
    {
        return new self(
            $this->themeInstance,
            $this->themeContext,
            $this->getMbSection(),
            $this->sectionLayout,
            $brizyComponent,
            $this->getBrizyMenuEntity(),
            $this->getBrizyMenuItems(),
            $this->getFontFamilies(),
            $this->getDefaultFontFamily()
        );
    }

    public function instanceWithMBSection($mbSection): ElementContextInterface
    {
        return new self(
            $this->themeInstance,
            $this->themeContext,
            $mbSection,
            $this->sectionLayout,
            $this->getBrizySection(),
            $this->getBrizyMenuEntity(),
            $this->getBrizyMenuItems(),
            $this->getFontFamilies(),
            $this->getDefaultFontFamily()
        );
    }

    public function instanceWithBrizyComponentAndMBSection(
        $mbSection,
        BrizyComponent $brizyComponent
    ): ElementContextInterface {
        return new self(
            $this->themeInstance,
            $this->themeContext,
            $mbSection,
            $this->sectionLayout,
            $brizyComponent,
            $this->getBrizyMenuEntity(),
            $this->getBrizyMenuItems(),
            $this->getFontFamilies(),
            $this->getDefaultFontFamily()
        );
    }

    public function __construct(
        ThemeInterface $themeInstance,
        ThemeContextInterface $themeContext,
        array $section,
        BrizyComponent $sectionLayout,
        BrizyComponent $brizyComponent = null,
        array $brizyMenuEntity = [],
        array $brizyMenuItems = [],
        array $fontFamily = [],
        string $defaultFontFamilies = ''
    ) {
        $this->mbSection = $section;
        $this->brizyComponent = $brizyComponent;
        $this->fontFamilies = $fontFamily;
        $this->defaultFontFamily = $defaultFontFamilies;
        $this->themeContext = $themeContext;
        $this->brizyMenuEntity = $brizyMenuEntity;
        $this->brizyMenuItems = $brizyMenuItems;
        $this->themeInstance = $themeInstance;
        $this->sectionLayout = $sectionLayout;
    }

    public function setPageLayout(BrizyComponent $layout): void
    {
        $this->sectionLayout = $layout;
    }

    public function getPageLayout(): BrizyComponent
    {
        return $this->sectionLayout;
    }

    public function getMbSection(): array
    {
        return $this->mbSection;
    }

    public function getBrizyMenuEntity(): array
    {
        return $this->brizyMenuEntity;
    }

    public function getBrizyMenuItems(): array
    {
        return $this->brizyMenuItems;
    }

    public function getFontFamilies(): array
    {
        return $this->fontFamilies;
    }

    public function getDefaultFontFamily(): string
    {
        return $this->defaultFontFamily;
    }

    public function getBrizySection(): BrizyComponent
    {
        return $this->brizyComponent;
    }

    public function getThemeContext(): ThemeContextInterface
    {
        return $this->themeContext;
    }

    public function getThemeInstance(): ThemeInterface
    {
        return $this->themeInstance;
    }

    public function getBrizyAPI(): BrizyAPI
    {
        return $this->themeContext->getBrizyAPI();
    }
}
