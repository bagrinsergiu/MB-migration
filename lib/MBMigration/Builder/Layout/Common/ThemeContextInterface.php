<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;

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

    public function getBrizyMenuEntity(): array;

    public function getBrizyMenuItems(): array;

    public function getSlug(): string;
}