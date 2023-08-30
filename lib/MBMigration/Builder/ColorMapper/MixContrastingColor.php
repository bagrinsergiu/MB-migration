<?php

namespace MBMigration\Builder\ColorMapper;

class MixContrastingColor extends ContrastCalculate
{

    public function result($color1, $threshold = 50): string
    {
        $color1 = $this->hexToRgb($color1);
        $color2 = $this->hexToRgb($this->getContrastingColor($color1));

        $blendedColor = [
            ($color1[0] * $threshold/100) + ($color2[0] * (1-$threshold/100)),
            ($color1[1] * $threshold/100) + ($color2[1] * (1-$threshold/100)),
            ($color1[2] * $threshold/100) + ($color2[2] * (1-$threshold/100))
        ];

        $blendedColor = array_map(function($value) {
            return max(0, min(255, $value));
        }, $blendedColor);

        return sprintf("#%02x%02x%02x", $blendedColor[0], $blendedColor[1], $blendedColor[2]);
    }
}