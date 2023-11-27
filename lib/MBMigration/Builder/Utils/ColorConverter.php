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

        return sprintf("%.2f", (float)$matches[1][3]);
    }
}