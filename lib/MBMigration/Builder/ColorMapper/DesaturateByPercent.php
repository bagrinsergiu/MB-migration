<?php

namespace MBMigration\Builder\ColorMapper;

class DesaturateByPercent
{
    function __construct($color, $percent) {
        $percent = max(0, min(100, $percent));

        $r = hexdec(substr($color, 1, 2));
        $g = hexdec(substr($color, 3, 2));
        $b = hexdec(substr($color, 5, 2));

        $r /= 255.0;
        $g /= 255.0;
        $b /= 255.0;
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2.0;

        if ($max == $min) {
            $s = 0;
        } else {
            $d = $max - $min;
            $s = $d / (1 - abs(2 * $l - 1));
        }

        $s -= $s * $percent / 100;
        $s = max(0, min(1, $s));

        $m2 = $l + $s - $l * $s;
        $m1 = 2 * $l - $m2;
        $r = round($this->hueToRgb($m1, $m2, $r + 1/3) * 255);
        $g = round($this->hueToRgb($m1, $m2, $g) * 255);
        $b = round($this->hueToRgb($m1, $m2, $b - 1/3) * 255);


        $r = str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
        $g = str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
        $b = str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

        return "#".$r.$g.$b;
    }

    function hueToRgb($p, $q, $t) {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
        return $p;
    }
}