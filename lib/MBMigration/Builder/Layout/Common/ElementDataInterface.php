<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;

interface ElementDataInterface
{
    /**
     * Returns and Brizy fully build section ready to be inserted in page data.
     *
     * @return array
     */
    public function getMbSection(): array;

    public function getMenu(): array;
}