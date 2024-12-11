<?php

namespace MBMigration\Builder\Layout\Common;

interface MenuBuilderInterface
{
    public function createBrizyMenu($name, $pages): array;

    public function transformToBrizyMenu(array $menuItems): array;

}