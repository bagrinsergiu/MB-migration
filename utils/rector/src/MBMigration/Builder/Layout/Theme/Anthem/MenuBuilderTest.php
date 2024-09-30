<?php

namespace Utils\Rector\MBMigration\Builder\Layout\Theme\Anthem;

use MBMigration\Builder\Layout\Theme\Anthem\MenuBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class MenuBuilderTest
 * @package Utils\Rector\MBMigration\Builder\Layout\Theme\Anthem
 *
 * MenuBuilderTest tests the transformToBrizyMenu method in the MenuBuilder class.
 * The aim is to ensure that the method correctly transforms the provided menu items to the BrizyMenu format.
 */
class MenuBuilderTest extends TestCase
{
    /**
     * Test whether transformToBrizyMenu method removes hidden menu items.
     */
    public function testTransformToBrizyMenuRemovesHiddenItems(): void
    {
        $menuBuilder = new MenuBuilder();

        $menuItems = [
            ['hidden' => true],
            ['hidden' => false]
        ];

        $result = $menuBuilder->transformToBrizyMenu($menuItems);

        $this->assertCount(1, $result);
    }

    // other tests to be written here depending on the full functionality of the method
}
