<?php

namespace MBMigration\Builder\BrizyComponent;

use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;

class BrizyColumComponent extends BrizyComponent
{
    /**
     * @throws BadJsonProvided
     */
    public function __construct(array $data = null, ?BrizyComponent $parent = null)
    {
        if ($data === null) {
            $data = [
                "type" => "Column",
                "value" => [
                    "_styles" => [
                        "column"
                    ],
                    "linkSource" => "/collection_types/5167637",
                    "linkType" => "page",
                    "items" => [],
                    "_id" => 'a' . bin2hex(random_bytes(16)),
                ]
            ];
        }

        parent::__construct($data, $parent);
    }
}
