<?php

namespace MBMigration\Builder\Layout\Theme\Ember\Elements\Prayer;

class PrayerListElement extends \MBMigration\Builder\Layout\Common\Elements\Prayer\PrayerListElement
{

    protected function getTopPaddingOfTheFirstElement(): int
    {
        return 110;
    }

    protected function getMobileTopPaddingOfTheFirstElement(): int
    {
        return 25;
    }

    protected function getMobileTopMarginOfTheFirstElement(): int
    {
        $dtoPageStyle = $this->pageTDO->getPageStyleDetails();

        return (int) $dtoPageStyle['headerHeight'];
    }

}
