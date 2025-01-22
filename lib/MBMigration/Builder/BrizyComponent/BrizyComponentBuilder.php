<?php

namespace MBMigration\Builder\BrizyComponent;

use MBMigration\Builder\BrizyComponent\Components\AbstractComponent;
use MBMigration\Builder\BrizyComponent\Components\ComponentSection;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;

class BrizyComponentBuilder extends AbstractComponent
{
    private BrizyComponent $brizySectionGrid;
    private BrizyComponent $brizyRow;
    private BrizyComponent $brizyColumn;
    private BrizyComponent $brizySection;
    private BrizyComponent $brizyImage;


    /**
     * @throws BadJsonProvided
     */
    public function createSection(): ComponentSection
    {
        return new ComponentSection($this->brizyKit);
    }

    /**
     * @throws BadJsonProvided
     */
    public function createRow(): BrizyComponent
    {
        return new BrizyComponent(json_decode($this->brizyKit['global']['Row'], true));
    }



}
