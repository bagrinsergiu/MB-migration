<?php

namespace MBMigration\Builder\BrizyComponent;

use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;

class BrizyLineComponent extends BrizyComponent
{
    /**
     * @throws BadJsonProvided
     */
    public function __construct()
    {
        $imageJson = [
            "type" => "Line",
            "value" => [
                "_styles" => [
                    "line",
                ],
                "_id" => 'a'.bin2hex(random_bytes(16)),
                "tabsState" => "normal",
                "width" => 40,
                "widthSuffix" => "%",
                "style" => "default",
                "borderWidth" => 3,
                "borderWidthSuffix" => "px",
                "borderColorHex" => "#4e3131",
                "borderColorOpacity" => 0.75,
                "borderColorPalette" => ""
            ],
        ];

        parent::__construct($imageJson);
    }
}
