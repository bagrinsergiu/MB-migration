<?php

namespace MBMigration\Builder\ColorMapper;

class ChooseLargerContrast extends ContrastCalculate
{
    public function result(array $colorData)
    {
        $baseLumLight    = $this->lumLight('#e1d7d2');
        $option1LumLight = $this->lumLight('#f2ece4');
        $option2LumLight = $this->lumLight('#dadbdf');

        $option1Contrast = abs($baseLumLight - $option1LumLight);
        $option2Contrast = abs($baseLumLight - $option2LumLight);

        if ($option1Contrast > $option2Contrast) {
            return $colorData[1];
        } else {
            return $colorData[2];
        }
    }
}