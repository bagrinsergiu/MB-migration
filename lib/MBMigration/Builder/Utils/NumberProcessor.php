<?php

namespace MBMigration\Builder\Utils;

class NumberProcessor
{
    public static function convertToNumeric($input) {
        if (is_numeric($input)) {
            if (strpos($input, '.') !== false) {
                return (float) $input;
            } else {
                return (int) $input;
            }
        } else {
            return 1;
        }
    }

    public static function convertToInt($input)
    {
        return preg_replace('/[^0-9]/', '', $input);
    }

}
