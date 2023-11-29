<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

interface ElementInterface
{
    /**
     * Returns and Brizy fully build section ready to be inserted in page data.
     *
     * @return array
     */
    public function transformToItem(ElementContextInterface $data): BrizyComponent;
}