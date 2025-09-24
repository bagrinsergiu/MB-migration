<?php

namespace MBMigration\Builder\BrizyComponent;

use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;

class BrizyRowComponent extends BrizyComponent
{
    /**
     * @throws BadJsonProvided
     */
    public function __construct(array $data = null, ?BrizyComponent $parent = null)
    {
        if ($data === null) {
            $data = [
                "type" => "Row",
                "value" => [
                    "_styles" => [
                        "row"
                    ],
                    "items" => [],
                    "_id" => 'a' . bin2hex(random_bytes(16)),
                ]
            ];
        }

        parent::__construct($data, $parent);
    }
}
