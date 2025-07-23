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
                "imageExtension" => "png",
                "imageWidth" => 100,
                "imageHeight" => 75,
                "widthSuffix" => "%",
                "heightSuffix" => "%",
                "sizeType" => "original",
                "size" => 100,
                "imageType" => "internal",
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
