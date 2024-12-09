<?php

namespace MBMigration\Builder\BrizyComponent;

class BrizyWrapperComponent extends BrizyComponent
{
    public function __construct($wrapperType)
    {
        $wrapper = [
            "type" => "Wrapper",
            "value" => [
                "_styles" => [
                    "wrapper",
                    $wrapperType,
                ],
                "items" => [
                ],
                "_id" => "pywftojdlbsibvtxuaylcaesgliphyfthbpr",
                "marginType" => "ungrouped",
                "margin" => 0,
                "tempMargin" => 0,
                "marginSuffix" => "px",
                "tempMarginSuffix" => "px",
                "marginTop" => 0,
                "tempMarginTop" => 0,
                "marginTopSuffix" => "px",
                "tempMarginTopSuffix" => "px",
                "marginRight" => 0,
                "tempMarginRight" => 0,
                "marginRightSuffix" => "px",
                "tempMarginRightSuffix" => "px",
                "marginBottom" => 0,
                "tempMarginBottom" => 0,
                "marginBottomSuffix" => "px",
                "tempMarginBottomSuffix" => "px",
                "marginLeft" => -100,
                "tempMarginLeft" => -100,
                "marginLeftSuffix" => "px",
                "tempMarginLeftSuffix" => "px",
            ],
        ];
        parent::__construct($wrapper);
    }
}