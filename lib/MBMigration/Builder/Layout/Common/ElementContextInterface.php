<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

interface ElementContextInterface
{
    /**
     * Returns and Brizy fully build section ready to be inserted in page data.
     *
     * @return array
     */
    public function getMbSection(): array;

    public function getBrizySection(): BrizyComponent;

    public function getThemeContext(): ThemeContextInterface;

    public function getThemeInstance(): ThemeInterface;

    public function getBrizyMenuEntity(): array;
    public function getBrizyMenuItems(): array;

    public function getFontFamilies(): array;

    public function getDefaultFontFamily(): string;

    public function instanceWithBrizyComponent(BrizyComponent $brizyComponent): ElementContextInterface;
    public function instanceWithMBSection($mbSection): ElementContextInterface;
    public function instanceWithBrizyComponentAndMBSection($mbSection, BrizyComponent $brizyComponent): ElementContextInterface;
}