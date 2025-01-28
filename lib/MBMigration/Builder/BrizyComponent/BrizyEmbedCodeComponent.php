<?php

namespace MBMigration\Builder\BrizyComponent;

class BrizyEmbedCodeComponent extends BrizyComponent
{
    public function __construct($code)
    {
        $wrapper = [
            "type" => "Wrapper",
            "value" => [
                "_styles" => [
                    "wrapper",
                    "wrapper--embedCode",
                ],
                "items" => [
                    [
                        "type" => "EmbedCode",
                        "value" => [
                            "_styles" => [
                                "embedCode",
                            ],
                            "_id" => "mzbcldsnfwwcmxobyvrldvfnusmtqwwffrdt",
                            "code" => $code,
                        ],
                    ],
                ],

                "_id" => "mosctamxmupqlwdouqwgghkuumljkedtwdjj",
            ],
        ];;
        parent::__construct($wrapper);
    }
}
