<?php

namespace MBMigration\Parser\JsParse;

use MBMigration\Core\Utils;

class JSCode
{

    public static function StylesExtractor(array $DATA)
    {
        $requiredParameters = ['selector', 'blockIndex', 'styleProperties'];

        $dataCheck = self::checkParameters($DATA, $requiredParameters);

        return self::loadSampleAndReplacePlaceholderInCode('StylesExtractor', $dataCheck);
    }

    public static function RichText(array $DATA)
    {
        $requiredParameters = ['data'];

        $DATA_R = self::checkParameters($DATA, $requiredParameters);

        $DATA_R['data'] = addslashes(json_encode($DATA_R['data']));

        return self::loadSampleAndReplacePlaceholderInCode('richText', $DATA_R);
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
            Utils::MESSAGES_POOL("File dont exist: " . $path, 'ERROR');
            return false;
        }

        $jsCode = file_get_contents($path);

        foreach ($placeholderData as $placeholder => $replacement) {
            $jsCode = str_replace("{{" . $placeholder . "}}", $replacement, $jsCode);
        }

        return $jsCode;
    }
}