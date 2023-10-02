<?php

namespace MBMigration\Builder\ColorMapper;

use Exception;
use MBMigration\Builder\Utils\ColorUtility;
use MBMigration\Core\Utils;
use ScssPhp\ScssPhp\Exception\SassException;

class ColorControllerSCSS
{
    /**
     * @throws \Exception
     */
    public static function result($methodName, $colorData, $options = null)
    {
        $colorUtility = new ColorUtility();
        try {
            switch ($methodName) {
            case "chooseLargerContrast":
                return $colorUtility->chooseLargerContrast($colorData[0], $colorData[1], $colorData[2]);
            case "chooseLesserContrast":
                return $colorUtility->chooseLesserContrast($colorData[0], $colorData[1], $colorData[2]);
            case "desaturateByPercent":
                return $colorUtility->desaturateByPercent($colorData, $options);
            case "mixContrastingColor":
                return $colorUtility->mixContrastingColor($colorData, $options);
            case "getContrastingColor":
                return $colorUtility->getContrastingColor($colorData);
            default:
                throw new \Exception("Unknown Element");
            }
        }
        catch (Exception|SassException $e) {
            Utils::MESSAGES_POOL($e->getMessage());
        }
    }

}