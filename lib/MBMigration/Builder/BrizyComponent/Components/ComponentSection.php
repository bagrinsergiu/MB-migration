<?php

namespace MBMigration\Builder\BrizyComponent\Components;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;

class ComponentSection
{
    private BrizyComponent $brizySection;

    /**
     * @throws BadJsonProvided
     */
    public function __construct($brizyKit){
        $this->brizySection = new BrizyComponent(json_decode($brizyKit['global']['main'], true));
    }

}
