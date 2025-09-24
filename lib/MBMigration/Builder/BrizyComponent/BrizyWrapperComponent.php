<?php

namespace MBMigration\Builder\BrizyComponent;

class BrizyWrapperComponent extends BrizyComponent
{
    public function __construct($wrapperType, ?BrizyComponent $parent = null)
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
                "_id" => 'a' . bin2hex(random_bytes(16)),
            ],
        ];
        parent::__construct($wrapper, $parent);
    }
}
