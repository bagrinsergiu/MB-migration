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
            ],
        ];
        parent::__construct($wrapper);
    }
}
