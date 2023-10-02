<?php

namespace MBMigration\Builder\ColorMapper;

class DesaturateByPercent
{
    public function result($colorHex, $percent): string
    {
        $percent = max(0, min(100, $percent));

        $colorHex = ltrim($colorHex, '#');
        $colorRgb = sscanf($colorHex, "%02x%02x%02x");

        list($r, $g, $b) = $colorRgb;
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $h = 0;
        $s = 0;
        $l = ($max + $min) / 2;

        if ($max !== $min) {
            $d = $max - $min;
            $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

            switch ($max) {
                case $r:
                    $h = ($g - $b) / $d + ($g < $b ? 6 : 0);
                    break;
                case $g:
                    $h = ($b - $r) / $d + 2;
                    break;
                case $b:
                    $h = ($r - $g) / $d + 4;
                    break;
            }
            $h /= 6;
        }

        $s -= $s * ($percent / 100);

        $s = max(0, min(1, $s));

        if ($s === 0) {
            $r = $g = $b = $l;
        } else {

            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = $this->hueToRgb($p, $q, $h + 1 / 3);
            $g = $this->hueToRgb($p, $q, $h);
            $b = $this->hueToRgb($p, $q, $h - 1 / 3);
        }

        $r = round($r * 255);
        $g = round($g * 255);
        $b = round($b * 255);
        return sprintf("#%02x%02x%02x", $r, $g, $b);
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