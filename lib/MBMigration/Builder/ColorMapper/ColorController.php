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
            case "mixContrastingColor":
                return new MixContrastingColor($colorData, $options);
            case "getContrastingColor":
                $contrast = new ContrastCalculate();
                return $contrast->getContrastingColor($colorData);
            default:
                throw new \Exception("Unknown Element");
        }
    }

}