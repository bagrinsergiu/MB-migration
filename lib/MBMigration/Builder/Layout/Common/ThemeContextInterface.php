<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;

interface ThemeContextInterface
{
    public function getLayoutName(): string;

    public function getBrizyKit(): array;

    public function getElementFactory(): ThemeElementFactoryInterface;

    public function getBrowserPage(): BrowserPageInterface;

    public function getFamilies(): array;

    public function getDefaultFamily(): string;

    public function getMbHeadSection(): array;

    public function getMbFooterSection(): array;

    public function getBrizyCollectionTypeURI(): string;

    public function getBrizyCollectionItemURI(): string;

    public function getMbMenu(): array;

    public function getSlug(): string;
}