<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserInterface;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponentBuilder;
use MBMigration\Builder\Fonts\FontsController;
use MBMigration\Builder\Layout\Common\DTO\PageDto;

interface ThemeContextInterface
{
    public function getLayoutName(): string;

    public function getBrizyKit(): array;

    public function getElementFactory(): ThemeElementFactoryInterface;

    public function getBrowserPage(): BrowserPageInterface;

    public function getFamilies(): array;
    public function setFamilies($families): self;

    public function getDefaultFamily(): string;

    public function getMbHeadSection(): array;

    public function getMbFooterSection(): array;

    public function getBrizyCollectionTypeURI(): string;

    public function getBrizyCollectionItemURI(): string;

    public function getBrizyMenuEntity(): array;

    public function getBrizyMenuItems(): array;

    public function getSlug(): string;

    public function getUrlMap(): array;

    public function getRootPalettes(): RootPalettesInterface;

    public function getBrowser(): BrowserInterface;

    public function getListSeries(): array;

    public function getPageDTO(): PageDto;

    public function getBrizyComponentBuilder(): BrizyComponentBuilder;

    public function getProjectName(): string;

    public function getFontsController(): FontsController;
}
