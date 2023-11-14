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
            return $input;
        }
    }

}