<?php

namespace MBMigration\Builder\ColorMapper;

class ChooseLargerContrast extends ContrastCalculate
{
    public function result(array $colorData)
    {
        $baseLumLight    = $this->lumLight($colorData[0]);
        $option1LumLight = $this->lumLight($colorData[1]);
        $option2LumLight = $this->lumLight($colorData[2]);

        $option1Contrast = abs($baseLumLight - $option1LumLight);
        $option2Contrast = abs($baseLumLight - $option2LumLight);

        if ($option1Contrast > $option2Contrast) {
            return $colorData[1];
        } else {
            return $colorData[2];
        }
    }
}