<?php

namespace MBMigration\Builder\Utils;

class FontUtils
{
    public static function convertFontFamily($fontName): string
    {
        $inputString = explode(',',  $fontName);

        $inputString = str_replace(["\"", "'", ' '], ['', '', '_'], $inputString[0]);

        return strtolower($inputString);
    }

    public static function transliterateFontFamily($fontName): string
    {
        $inputString = str_replace(["\"", "'", ' '], ['', '', '_'], $fontName);

        $inputString = str_replace(',', '', $inputString);

        return strtolower($inputString);
    }
}
