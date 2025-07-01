<?php

namespace MBMigration\Builder\Utils;

class TextTools
{
    public static function transformText(
        $text,
        $text_transform
    ) {
        if ($text_transform === "uppercase") {
            return mb_strtoupper($text, 'UTF-8');
        } elseif ($text_transform === "lowercase") {
            return mb_strtolower($text, 'UTF-8');
        } else {
            return $text;
        }
    }

    public static function transformTextBool(
        $text,
        $text_transform = false
    ) {
        if ($text_transform) {
            return mb_strtoupper($text, 'UTF-8');
        } else {
            return $text;
        }
    }

}
