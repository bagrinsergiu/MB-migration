<?php

namespace MBMigration\Builder\ColorMapper;

class MixContrastingColor extends ContrastCalculate
{

    public function result($color1, $threshold = 50): array
    {
        $color1 = $this->hexToRgb($color1);
        $color2 = $this->hexToRgb($this->getContrastingColor($color1));

        return [
            ($color1[0] * $threshold/100) + ($color2[0] * (1-$threshold/100)),
            ($color1[1] * $threshold/100) + ($color2[1] * (1-$threshold/100)),
            ($color1[2] * $threshold/100) + ($color2[2] * (1-$threshold/100))
        ];
    }
}