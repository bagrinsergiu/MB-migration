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

        // output
        $hex = sprintf(
            "#%02X%02X%02X",
            $matches[1][2], // blue
            $matches[1][1], // green
            $matches[1][0] // red
        );

        return $hex;
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

        return sprintf("%.2f",(float)$matches[1][3]);
    }
}