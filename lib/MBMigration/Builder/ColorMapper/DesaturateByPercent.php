<?php

namespace MBMigration\Builder\ColorMapper;

class DesaturateByPercent
{
    function result($hexColor, $percentage): string
    {
        // Убедимся, что процент увеличения находится в диапазоне от 0 до 100
        $percentage = max(0, min(100, $percentage));

        // Преобразуем HEX-цвет в формат RGB
        $r = hexdec(substr($hexColor, 1, 2));
        $g = hexdec(substr($hexColor, 3, 2));
        $b = hexdec(substr($hexColor, 5, 2));

        // Преобразуем RGB в значения HSL
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $delta = $max - $min;

        $l = ($max + $min) / 2;

        // Если максимальное и минимальное значения совпадают, значит, цвет серый, и светлоту менять не нужно
        if ($delta === 0) {
            return $hexColor;
        }

        // Вычисляем насыщенность (Saturation) и оттенок (Hue)
        $s = $l > 0.5 ? $delta / (2 - $max - $min) : $delta / ($max + $min);
        switch ($max) {
            case $r:
                $h = ($g - $b) / $delta + ($g < $b ? 6 : 0);
                break;
            case $g:
                $h = ($b - $r) / $delta + 2;
                break;
            default: // $max == $b
                $h = ($r - $g) / $delta + 4;
                break;
        }
        $h /= 6;

        // Изменяем светлоту (Lightness) на заданный процент
        $l += $percentage / 100;

        // Гарантируем, что светлота остается в пределах от 0 до 1
        $l = max(0, min(1, $l));

        // Преобразуем HSL обратно в RGB
        if ($s === 0) {
            $r = $g = $b = $l;
        } else {
            $t2 = $l < 0.5 ? $l * (1 + $s) : ($l + $s) - ($s * $l);
            $t1 = 2 * $l - $t2;

            $r = $this->hueToRgb($t1, $t2, $r + 1/3);
            $g = $this->hueToRgb($t1, $t2, $g);
            $b = $this->hueToRgb($t1, $t2, $b - 1/3);
        }

        // Преобразуем RGB обратно в HEX
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