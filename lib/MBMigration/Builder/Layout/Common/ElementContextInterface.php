<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\DTO\PageDto;
use MBMigration\Layer\Brizy\BrizyAPI;

interface ElementContextInterface
{
    public function getMbSection(): array;

    public function getBrizySection(): BrizyComponent;

    public function getThemeContext(): ThemeContextInterface;

    public function getThemeInstance(): ThemeInterface;

    public function getBrizyMenuEntity(): array;
    public function getBrizyMenuItems(): array;

    public function getFontFamilies(): array;

    public function getDefaultFontFamily(): string;

    public function getBrizyAPI(): BrizyAPI;

    public function instanceWithBrizyComponent(BrizyComponent $brizyComponent): ElementContextInterface;
    public function instanceWithMBSection($mbSection): ElementContextInterface;
    public function instanceWithBrizyComponentAndMBSection($mbSection, BrizyComponent $brizyComponent): ElementContextInterface;
}
