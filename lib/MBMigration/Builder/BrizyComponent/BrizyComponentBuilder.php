<?php

namespace MBMigration\Builder\BrizyComponent;

use MBMigration\Builder\BrizyComponent\Components\ComponentSection;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;

class BrizyComponentBuilder
{
    private BrizyComponent $brizySectionGrid;
    private BrizyComponent $brizyRow;
    private BrizyComponent $brizyColumn;
    private BrizyComponent $brizySection;
    private BrizyComponent $brizyImage;

    /**
     * @throws BadJsonProvided
     */
    public function __construct($brizyKit){

        $this->brizyKit = $brizyKit;

    }

    /**
     * @throws BadJsonProvided
     */
    public function createSection() {
        return new ComponentSection($this->brizyKit );
    }



}
