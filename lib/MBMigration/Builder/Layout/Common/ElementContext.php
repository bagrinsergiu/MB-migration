<?php

namespace MBMigration\Builder\Layout\Common;

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
     * @var array
     */
    private $selector;

    /**
     * @var string
     */
    private $defaultFontFamily;
    /**
     * @var BrizyComponent
     */
    private $brizyComponent;

    /**
     * @var ThemeContextInterface
     */
    private $themeContext;
    private array $brizyMenuEntity;
    private array $brizyMenuItems;
    private ThemeInterface $themeInstance;

    /**
     * @param $mbSection
     * @param $browserData
     * @param $menu
     *
     * @return self
     */
    static public function instance(
        ThemeInterface $themeInstance,
        ThemeContextInterface $themeContext,
        array $mbSection,
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

    public function getThemeInstance(): ThemeInterface
    {
        return $this->themeInstance;
    }
}
