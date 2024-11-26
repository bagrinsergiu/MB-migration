<?php

namespace MBMigration\Builder\Layout\Theme\Zion\Elements\Text;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

class TabsLayoutElement extends \MBMigration\Builder\Layout\Common\Elements\Text\TabsLayoutElement
{
    /**
     * @param BrizyComponent $brizySection
     * @return mixed|null
     */
    protected function getTopTextComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 0, 0);
    }

    /**
     * @param BrizyComponent $brizySection
     * @return mixed
     */
    protected function getTabContainerComponent(BrizyComponent $brizySection): BrizyComponent
    {
        return $brizySection->getItemWithDepth(0, 1, 0, 0, 0);
    }

}
