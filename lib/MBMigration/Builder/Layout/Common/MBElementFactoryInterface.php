<?php

namespace MBMigration\Builder\Layout\Common;

interface MBElementFactoryInterface
{
    public function loadBrizyBlockKit($kitPath);
    public function getElement($name,$mbElementData);
}