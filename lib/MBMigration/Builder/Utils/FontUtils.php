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
}
