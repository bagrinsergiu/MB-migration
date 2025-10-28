<?php

namespace MBMigration\Builder\Layout\Common\Concern\Effects;

use MBMigration\Builder\BrizyComponent\BrizyComponent;

trait ShadowAble
{
    protected function handleShadow(BrizyComponent $brizySection)
    {
        $brizySection->getItemWithDepth(0)->addCustomCSS(
            ".brz-section{\n  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);\n}"
        );
    }

}
