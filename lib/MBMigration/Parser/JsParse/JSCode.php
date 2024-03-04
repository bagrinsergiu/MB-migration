<?php

namespace MBMigration\Parser\JsParse;

use MBMigration\Core\Utils;

class JSCode
{

    public static function StylesExtractor(array $DATA)
    {
        $requiredParameters = ['selector', 'styleProperties'];

        $dataCheck = self::checkParameters($DATA, $requiredParameters);

        return self::loadSampleAndReplacePlaceholderInCode('StylesExtractor', $dataCheck);
    }
    public static function ImageStyles(array $DATA)
    {
        $requiredParameters = ['selector'];

        $dataCheck = self::checkParameters($DATA, $requiredParameters);

        return self::loadSampleAndReplacePlaceholderInCode('getImageStyle', $dataCheck);
    }

    public static function RichText(array $DATA)
    {
        $requiredParameters = ['data'];

        $DATA_R = self::checkParameters($DATA, $requiredParameters);

        $DATA_R['data'] = addslashes(json_encode($DATA_R['data']));

        return self::loadSampleAndReplacePlaceholderInCode('getText', $DATA_R);
    }

    public static function ExtractStyleFromMenu(array $DATA)
    {
        $requiredParameters = ['selector', 'families'];

        $dataCheck = self::checkParameters($DATA, $requiredParameters);

        return self::loadSampleAndReplacePlaceholderInCode('getMenuStyle', $dataCheck);
    }

    private static function checkParameters(array $confConnection, array $requiredParameters)
    {
        foreach ($requiredParameters as $field) {
            if (!array_key_exists($field, $confConnection)) {
                return false;
            }
        }
        return $confConnection;
    }

    private static function loadSampleAndReplacePlaceholderInCode($fileName, $placeholderData) {

        $path = __DIR__ . '/js/' . $fileName . '.js';

        if (!file_exists($path)) {
            \MBMigration\Core\Logger::instance()->info("File dont exist: " . $path);
            return false;
        }

        $jsCode = file_get_contents($path);

        foreach ($placeholderData as $placeholder => $replacement) {
            $jsCode = str_replace("{{" . $placeholder . "}}", $replacement, $jsCode);
        }

        return $jsCode;
    }
}