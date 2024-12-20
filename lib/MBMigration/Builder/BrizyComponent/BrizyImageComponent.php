<?php

namespace MBMigration\Builder\BrizyComponent;

class BrizyImageComponent extends BrizyComponent
{
    public function __construct()
    {
        $imageJson = [
            "type" => "Image",
            "value" => [
                "_styles" => [
                    "image",
                ],
                "linkSource" => "page",
                "linkType" => "page",
                "_id" => "gigddbxjpastzrjijvdwoqbwsbgqykqtjpro",
                "_version" => 2,
                "imageSrc" => "",
                "imageFileName" => "",
                "imageExtension" => "",
                "imageWidth" => 100,
                "imageHeight" => 75,
                "widthSuffix" => "%",
                "heightSuffix" => "%",
                "mobileHeight" => null,
                "mobileHeightSuffix" => null,
                "mobileWidth" => null,
                "mobileWidthSuffix" => null,
                "tabletHeight" => null,
                "tabletHeightSuffix" => null,
                "tabletWidth" => null,
                "tabletWidthSuffix" => null,
            ],
        ];

        parent::__construct($imageJson);
    }


}