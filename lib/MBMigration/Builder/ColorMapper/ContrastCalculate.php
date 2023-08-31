<?php

namespace MBMigration\Builder\ColorMapper;

class ContrastCalculate
{

    public function getContrastingColor($color, $threshold = 50, $lightColor = '#ffffff', $darkColor = '#2a2a2a') {
        $color = $this->hexToRgb($color);

        $hsl = $this->rgbToHsl($color[0], $color[1], $color[2]);

        if ($hsl[2] > $threshold / 100) {
            return $darkColor;
        } else {
            return $lightColor;
        }
    }

    protected function lumLight($color): float
    {
        list($hue, $saturation, $lightness) = $this->hexToHSL($color);

        return $lightness;
    }
    private function hexToHSL($hex): array
    {
        $hex = ltrim($hex, '#');

        list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");

        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $lightness = ($max + $min) / 2;

        if ($max == $min) {
            $saturation = 0;
        } else {
            $delta = $max - $min;
            $saturation = $delta / (1 - abs(2 * $lightness - 1));
        }

        if ($max == $r) {
            $hue = 60 * fmod((($g - $b) / $delta), 6);
        } elseif ($max == $g) {
            $hue = 60 * ((($b - $r) / $delta) + 2);
        } elseif ($max == $b) {
            $hue = 60 * ((($r - $g) / $delta) + 4);
        }

        if ($hue < 0) {
            $hue += 360;
        }

        $hue = round($hue);
        $saturation = round($saturation * 100);
        $lightness = round($lightness * 100);

        return array($hue, $saturation, $lightness);
    }


    protected function hexToRgb($hex): ?array
    {
        $hex = preg_replace('/[^0-9A-Fa-f]/', '', $hex);

        if (strlen($hex) !== 6) {
            return [0, 0, 0];
        }

        $redValue = hexdec(substr($hex, 0, 2));
        $greenValue = hexdec(substr($hex, 2, 2));
        $blueValue = hexdec(substr($hex, 4, 2));

        return [$redValue, $greenValue, $blueValue];
    }

    protected function rgbToHsl($r, $g, $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $h = 0;
        $s = 0;
        $l = ($max + $min) / 2;

        if ($max !== $min) {
            $diff = $max - $min;
            $s = $diff / (1 - abs(2 * $l - 1));

            if ($max === $r) {
                $h = ($g - $b) / $diff + ($g < $b ? 6 : 0);
            } elseif ($max === $g) {
                $h = ($b - $r) / $diff + 2;
            } else {
                $h = ($r - $g) / $diff + 4;
            }

            $h /= 6;
        }

        return [$h, $s, $l];
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
        $rgb = $this->hexToRgb($color);

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

    function lightness($hexColor): float
    {
        $r = hexdec(substr($hexColor, 1, 2));
        $g = hexdec(substr($hexColor, 3, 2));
        $b = hexdec(substr($hexColor, 5, 2));

        $r /= 255.0;
        $g /= 255.0;
        $b /= 255.0;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $lightness = ($max + $min) / 2;

        return round($lightness * 100, 2);
    }

}