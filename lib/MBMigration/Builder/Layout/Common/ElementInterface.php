<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

interface ElementInterface
{
    public function transformToItem(ElementContextInterface $data): BrizyComponent;
}