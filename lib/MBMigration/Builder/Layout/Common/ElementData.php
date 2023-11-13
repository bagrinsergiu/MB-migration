<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;

final class ElementData implements ElementDataInterface
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

    /**
     * @param $mbSection
     * @param $browserData
     * @param $menu
     *
     * @return self
     */
    static public function instance(
        array $mbSection,
        BrizyComponent $brizyComponent = null,
        array $menu = [],
        array $fontFamilies = [],
        string $defaultFontFamily = ''
    ): self {
        return new self($mbSection, $brizyComponent, $menu, $fontFamilies, $defaultFontFamily);
    }

    public function instanceWithBrizyComponent(BrizyComponent $brizyComponent): ElementDataInterface
    {
        return new self(
            $this->getMbSection(),
            $brizyComponent,
            $this->getMenu(),
            $this->getFontFamilies(),
            $this->getDefaultFontFamily()
        );
    }

    public function instanceWithMBSection($mbSection): ElementDataInterface
    {
        return new self(
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
    ): ElementDataInterface {
        return new self(
            $mbSection,
            $brizyComponent,
            $this->getMenu(),
            $this->getFontFamilies(),
            $this->getDefaultFontFamily()
        );
    }

    public function __construct(
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
}