<?php

namespace MBMigration\Builder\ColorMapper;

class ContrastCalculate
{
    protected function lumLight($color): float
    {
        $result = ($this->luminance($color) + $this->lightness($color)) / 2;
        return $this->strip_unit($result) - 2.5;
    }

    private function strip_unit($value) {
        return $value / ($value * 0 + 1);
    }

    private function luminance($color): float
    {
        return ($this->color_luminance($color) + .05) * 100;
    }

    private function color_luminance($color): float
    {
        $rgb = $this->hexToRGB($color);

        $a = [$rgb[0]/255, $rgb[1]/255, $rgb[2]/255];

        foreach($a as $k => $v) {
            if ($v <= 0.03928) {
                $a[$k] = $v/12.92;
            } else {
                $a[$k] = pow((($v+0.055)/1.055), 2.4);
            }
        }
       return 0.2126*$a[0] + 0.7152*$a[1] + 0.0722*$a[2];
    }

    private function hexToRgb($hex): string
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));

        return "rgb($red, $green, $blue)";
    }

    private function lightness($color)
    {
        // return $color;
    }

}