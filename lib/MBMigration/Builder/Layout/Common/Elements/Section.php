<?php

namespace MBMigration\Builder\Layout\Common\Elements;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\DonationsAble;
use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
use MBMigration\Builder\Layout\Common\ElementContextInterface;

class Section extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DonationsAble;
    protected function internalTransformToItem(ElementContextInterface $data): BrizyComponent
    {
       return $data->getBrizySection();
    }
}
