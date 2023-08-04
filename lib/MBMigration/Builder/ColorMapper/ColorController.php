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
                $chooseLargerContrast = new ChooseLargerContrast();
                return $chooseLargerContrast->result($colorData);
            case "chooseLesserContrast":
                $chooseLesserContrast = new ChooseLesserContrast();
                return $chooseLesserContrast->result($colorData);
            case "desaturateByPercent":
                $desaturateByPercent = new DesaturateByPercent();
                return $desaturateByPercent->result($colorData, $options);
            case "mixContrastingColor":
                $mixContrastingColor = new MixContrastingColor();
                return $mixContrastingColor->result($colorData, $options);
            case "getContrastingColor":
                $contrast = new ContrastCalculate();
                return $contrast->getContrastingColor($colorData);
            default:
                throw new \Exception("Unknown Element");
        }
    }

}