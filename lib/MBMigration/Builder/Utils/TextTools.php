<?php

namespace MBMigration\Builder\Utils;

class TextTools
{
    public static function transformText($text, $text_transform) {
        if ($text_transform === "uppercase") {
            return strtoupper($text);
        } elseif ($text_transform === "lowercase") {
            return strtolower($text);
        } else {
            return $text;
        }
    }

    public static function transformTextBool($text, $text_transform = false) {
        if ($text_transform) {
            return strtoupper($text);
        } else {
            return $text;
        }
    }

}
