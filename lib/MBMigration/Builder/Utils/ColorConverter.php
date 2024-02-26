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

    private function removePx($inputString)
    {
        $pos = strpos($inputString, "px");

        if ($pos !== false) {
            $inputString = substr_replace($inputString, "", $pos, 2);
        }

        return $inputString;
    }
}