<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;

interface ElementDataInterface
{
    /**
     * Returns and Brizy fully build section ready to be inserted in page data.
     *
     * @return array
     */
    public function getMbSection(): array;

    public function getBrizySection(): BrizyComponent;

    public function getMenu(): array;

    public function getFontFamilies(): array;

    public function getDefaultFontFamily(): string;

    public function instanceWithBrizyComponent(BrizyComponent $brizyComponent): ElementDataInterface;
    public function instanceWithMBSection($mbSection): ElementDataInterface;
    public function instanceWithBrizyComponentAndMBSection($mbSection, BrizyComponent $brizyComponent): ElementDataInterface;
}