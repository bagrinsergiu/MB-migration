<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;

final class ElementContext implements ElementContextInterface
{
    /**
     * @var array
     */
    private $mbSection;
    /**
     * @var array
     */
    private $menu;

    /**
     * @var array
     */
    private $fontFamilies;

    /**
     * @var string
     */
    private $defaultFontFamily;
    /**
     * @var BrizyComponent
     */
    private $brizyComponent;

    private $brizyCollectionType;
    private $brizyCollectionItem;
    /**
     * @var ThemeContextInterface
     */
    private $themeContext;

    /**
     * @param $mbSection
     * @param $browserData
     * @param $menu
     *
     * @return self
     */
    static public function instance(
        ThemeContextInterface $themeContext,
        array $mbSection,
        BrizyComponent $brizyComponent = null,
        array $menu = [],
        array $fontFamilies = [],
        string $defaultFontFamily = ''
    ): self {
        return new self(
            $themeContext,
            $mbSection,
            $brizyComponent,
            $menu,
            $fontFamilies,
            $defaultFontFamily
        );
    }

    public function instanceWithBrizyComponent(BrizyComponent $brizyComponent): ElementContextInterface
    {
        return new self(
            $this->themeContext,
            $this->getMbSection(),
            $brizyComponent,
            $this->getMenu(),
            $this->getFontFamilies(),
            $this->getDefaultFontFamily()
        );
    }

    public function instanceWithMBSection($mbSection): ElementContextInterface
    {
        return new self(
            $this->themeContext,
            $mbSection,
            $this->getBrizySection(),
            $this->getMenu(),
            $this->getFontFamilies(),
            $this->getDefaultFontFamily()
        );
    }

    public function instanceWithBrizyComponentAndMBSection(
        $mbSection,
        BrizyComponent $brizyComponent
    ): ElementContextInterface {
        return new self(
            $this->themeContext,
            $mbSection,
            $brizyComponent,
            $this->getMenu(),
            $this->getFontFamilies(),
            $this->getDefaultFontFamily()
        );
    }

    public function __construct(
        ThemeContextInterface $themeContext,
        array $section,
        BrizyComponent $brizyComponent = null,
        array $menu = [],
        array $fontFamily = [],
        string $defaultFontFamilies = ''
    ) {
        $this->mbSection = $section;
        $this->brizyComponent = $brizyComponent;
        $this->menu = $menu;
        $this->fontFamilies = $fontFamily;
        $this->defaultFontFamily = $defaultFontFamilies;
        $this->themeContext = $themeContext;
    }

    public function getMbSection(): array
    {
        return $this->mbSection;
    }

    public function getMenu(): array
    {
        return $this->menu;
    }

    /**
     * @return array
     */
    public function getFontFamilies(): array
    {
        return $this->fontFamilies;
    }

    /**
     * @return array
     */
    public function getDefaultFontFamily(): string
    {
        return $this->defaultFontFamily;
    }

    /**
     * @return BrizyComponent
     */
    public function getBrizySection(): BrizyComponent
    {
        return $this->brizyComponent;
    }

    public function getThemeContext(): ThemeContextInterface
    {
        return $this->themeContext;
    }
}