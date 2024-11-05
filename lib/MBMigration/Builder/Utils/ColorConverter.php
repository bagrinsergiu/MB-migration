<?php

namespace MBMigration\Builder\Utils;

final class ColorConverter
{
    /**
     * @param $rgba
     * @return string
     * @example pass: rgba(123,100,23,.5)
     */
    static public function rgba2hex($rgba)
    {
        // get the values
        preg_match_all("/([\\d.]+)/", $rgba, $matches);
        $fromRGB = self::fromRGB($matches[1][0], $matches[1][1], $matches[1][2]);
        return $fromRGB;
    }

    static public function hex2Rgb($hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = str_repeat($hex[0], 2) . str_repeat($hex[1], 2) . str_repeat($hex[2], 2);
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "rgb($r, $g, $b)";
    }


    static private function fromRGB($R, $G, $B)
    {

        $R = dechex($R);
        if (strlen($R) < 2) {
            $R = '0'.$R;
        }

        $G = dechex($G);
        if (strlen($G) < 2) {
            $G = '0'.$G;
        }

        $B = dechex($B);
        if (strlen($B) < 2) {
            $B = '0'.$B;
        }

        return '#'.$R.$G.$B;
    }

    /**
     * @param $rgba
     * @return string
     * @example pass: rgba(123,100,23,.5)
     */
    static public function rgba2opacity($rgba)
    {
        // get the values
        preg_match_all("/([\\d.]+)/", $rgba, $matches);

        if(isset($matches[1][3]))
        {
            return sprintf("%.2f", (float)$matches[1][3]);
        }
        else{
            return 1;
        }
    }

    public static function convertColor($color)
    {

        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            return $color;
        }

        if (preg_match('/rgba\((\d+), (\d+), (\d+), ([0-9]*\.?[0-9]+)\)/', $color, $matches)) {
            $r = $matches[1];
            $g = $matches[2];
            $b = $matches[3];
            $a = $matches[4];

            $color = sprintf("#%02X%02X%02X", $r, $g, $b);

            if ($a == 0 && $color === "#000000") {
                return '#ffffff';
            } else {
                return [
                    'color' => sprintf("#%02X%02X%02X", $r, $g, $b),
                    'opacity' => $a,
                ];
            }
        }

        if (preg_match_all("/rgb\((\d{1,3}), (\d{1,3}), (\d{1,3})\)/", $color, $matches)) {

            list($r, $g, $b) = array($matches[1][0], $matches[2][0], $matches[3][0]);

            return sprintf("#%02X%02X%02X", $r, $g, $b);
        }

        return $color;
    }

    public static function convertColorRgbToHex($color)
    {
        $style = [];
        $opacityIsSet = false;
        if(is_array($color)){
            foreach ($color as $key => $value) {
                $convertedData = self::convertColor(str_replace("px", "", $value));
                if (is_array($convertedData)) {
                    $style[$key] = $convertedData['color'];
                    $style['opacity'] = $convertedData['opacity'];
                    $opacityIsSet = true;
                } else {
                    if ($opacityIsSet && $key == 'opacity') {
                        continue;
                    } else {
                        $style[$key] = $convertedData;
                    }
                }
            }
        } else {
            $style = self::convertColor(str_replace("px", "", $color));
        }
        return $style;
    }

   public static function convertStyle($sectionStyles, &$style, $section = 'data')
    {
        if (isset($sectionStyles['data'])) {
            $opacityIsSet = false;
            foreach ($sectionStyles[$section] as $key => $value) {
                $data = (new ColorConverter)->removePx($value);
                $convertedData = ColorConverter::convertColor(str_replace("px", "", $value));
                if (is_array($convertedData)) {
                    $style[$key] = $convertedData['color'];
                    $style['opacity'] = $convertedData['opacity'];
                    $opacityIsSet = true;
                } else {
                    if ($opacityIsSet && $key == 'opacity') {
                        continue;
                    } else {
                        $style[$key] = $convertedData;
                    }
                }
            }
        }
    }

    public static function removePx($inputString)
    {
        $pos = strpos($inputString, "px");

        if ($pos !== false) {
            $inputString = substr_replace($inputString, "", $pos, 2);
        }

        return $inputString;
    }

    public static function getContrastColor($hexColor): string
    {
        $hexColor = str_replace('#', '', $hexColor);

        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return $brightness > 125 ? '#000000' : '#FFFFFF';
    }

    public static function getHoverOpacity(float $baseOpacity): float {
        // Убедимся, что значение прозрачности находится в диапазоне от 0 до 1
        if ($baseOpacity < 0) {
            $baseOpacity = 0;
        } elseif ($baseOpacity > 1) {
            $baseOpacity = 1;
        }

        // Если прозрачность больше или равна 0.8, уменьшим её
        if ($baseOpacity >= 0.8) {
            $hoverOpacity = $baseOpacity - 0.2;
            if ($hoverOpacity < 0) {
                $hoverOpacity = 0; // Убедимся, что не выходит за пределы
            }
        } else {
            // В других случаях увеличим прозрачность
            $hoverOpacity = $baseOpacity + 0.2;
            if ($hoverOpacity > 1) {
                $hoverOpacity = 1; // Убедимся, что не выходит за пределы
            }
        }

        return $hoverOpacity;
    }
}
