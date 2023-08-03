<?php

namespace MBMigration\Builder\ColorMapper;

class ColorController
{
    /**
     * @throws \Exception
     */
    public static function result($methodName, $colorData, $options = null)
    {
        switch ($methodName) {
            case "chooseLargerContrast":
                return new ChooseLargerContrast($colorData);
            case "chooseLesserContrast":
                return new ChooseLesserContrast($colorData);
            case "desaturateByPercent":
                return new DesaturateByPercent($colorData, $options);
            default:
                throw new \Exception("Unknown Element");
        }
    }

}