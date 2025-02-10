<?php

namespace MBMigration\Builder\Utils;

use MBMigration\Core\Logger;

final class ColorConverter
{
    /**
     * @param $rgba
     * @return string
     * @example pass: rgba(123,100,23,.5)
     */
    static public function rgba2hex($rgba): string
    {
        $defaultColor = "#000000";

        if (!is_string($rgba)) {
            Logger::instance()->info("Input must be a string. Given: " . var_export($rgba, true));
            return $defaultColor;
        }

        // Already in HEX format
        if (preg_match("/^#([a-fA-F0-9]{6})$/", $rgba)) {
            return $rgba;
        }

        // Normalize short HEX format (#abc -> #aabbcc)
        if (preg_match("/^#([a-fA-F0-9]{3})$/", $rgba, $matches)) {
            $hex = $matches[1];
            $normalizedHex = "#" . $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            Logger::instance()->info("Normalized short HEX $rgba to $normalizedHex.");
            return $normalizedHex;
        }

        // Match RGBA or RGB values
        if (preg_match("/rgba?\\((\\d+),\\s*(\\d+),\\s*(\\d+)(?:,\\s*(\\d*(?:\\.\\d+)?))?\\)/", $rgba, $matches)) {
            $r = (int)$matches[1];
            $g = (int)$matches[2];
            $b = (int)$matches[3];
            $a = isset($matches[4]) ? (float)$matches[4] : null;

            // Log presence of alpha channel
            if ($a !== null) {
                Logger::instance()->info("Alpha channel detected (ignored): $a.");
            }

            // Validate RGB ranges
            if ($r < 0 || $r > 255 || $g < 0 || $g > 255 || $b < 0 || $b > 255) {
                Logger::instance()->info("RGB values must be in the range 0–255. Given: R=$r, G=$g, B=$b.");
                return $defaultColor;
            }

            return sprintf("#%02x%02x%02x", $r, $g, $b);
        }

        Logger::instance()->info("Input does not match any supported format. Given: $rgba.");
        return $defaultColor;
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
    public static function rgba2opacity($rgba)
    {
        if (is_numeric($rgba)) {
            $value = (float)$rgba;

            if ($value < 0) {
                return 0;
            } elseif ($value > 1) {
                return 1;
            }

            return ($value === 0.0 || $value === 1.0) ? (int)$value : $value;
        }

        if (preg_match("/rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*([\d.]+)\s*)?\)/", $rgba, $matches)) {
            $alpha = isset($matches[4]) ? (float)$matches[4] : 1;

            if ($alpha < 0) {
                return 0;
            } elseif ($alpha > 1) {
                return 1;
            }

            return ($alpha === 0.0 || $alpha === 1.0) ? (int)$alpha : $alpha;
        }

        return 1;
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

            if ($a == 1 && $color === "#000000") {
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
        if ($baseOpacity < 0) {
            $baseOpacity = 0;
        } elseif ($baseOpacity > 1) {
            $baseOpacity = 1;
        }

        if ($baseOpacity >= 0.8) {
            $hoverOpacity = $baseOpacity - 0.2;
            if ($hoverOpacity < 0) {
                $hoverOpacity = 0;
            }
        } else {
            $hoverOpacity = $baseOpacity + 0.2;
            if ($hoverOpacity > 1) {
                $hoverOpacity = 1;
            }
        }

        return $hoverOpacity;
    }

    public static function rewriteColorIfSetOpacity(array &$colors): void
    {
        foreach ($colors as $key => $color) {
            if (is_array($color) && isset($color['color'], $color['opacity'])) {
                $colors[$key] = $color['color'];
                $colors[$key . '-opacity'] = $color['opacity'];
            }
        }
    }

    public static function normalizeOpacity($opacity)
    {
        if (is_string($opacity)) {
            $opacity = trim($opacity);
        }

        if (!is_numeric($opacity)) {
            return 1;
        }

        $opacity = (float)$opacity;

        if ($opacity < 0) {
            return 0;
        }
        if ($opacity > 1) {
            return 1;
        }

        return $opacity;
    }

}
