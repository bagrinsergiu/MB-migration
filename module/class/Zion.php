<?php

namespace Brizy;

class Zion
{

    public static $namePages = array(
                                        "Home" => "home",
                                        "home" => "home",

                                        "About Us" => "about",
                                        "about-us" => "about",
                                        "about"    => "about",

                                        "Connect" => "contact",
                                        "connect" => "contact",

                                        "Ministries" => "services",
                                        "ministries" => "services",

                                        "Donate" => "donate",
                                        "donate" => "donate"
    );

    //=========== Array Section =======  $section['название страницы']['индекс секции на странице']

    public static $section = [
        'home'  => [],
        "about" =>  
        [ 
            [        //SectionHeaderItem / wrapper--menu
                'items/0/value/items/0/value/items/0/value/items/0/value/text', //title
                'items/0/value/items/0/value/items/2/value/items/0/value/text', //body
                'image' => []
            ],
            [        // Welcome block section
                'items/1/value/items/0/value/items/0/value/items/0/value/text', //title
                'items/1/value/items/0/value/items/2/value/items/0/value/text', //body
            ],
            [        //Section OUR TEAM
                'items/2/value/items/0/value/items/0/value/items/0/value/text', //title
                'list' => [  
                    'items/2/value/items/0/value/items/3' => [  '/value/items/1/value/items/0/value/items/0/value/text',
                                                                '/value/items/1/value/items/1/value/items/0/value/text',
                                                                '/value/items/1/value/items/2/value/items/0/value/text'
                                                            ], // team Row
                    'items/2/value/items/0/value/items/4' => [  '/value/items/1/value/items/0/value/items/0/value/text',
                                                                '/value/items/1/value/items/1/value/items/0/value/text',
                                                                '/value/items/1/value/items/2/value/items/0/value/text'
                                                            ], // team Row
                    'items/2/value/items/0/value/items/5' => [  '/value/items/1/value/items/0/value/items/0/value/text',
                                                                '/value/items/1/value/items/1/value/items/0/value/text',
                                                                '/value/items/1/value/items/2/value/items/0/value/text'
                                                            ], // team Row
                    'items/2/value/items/0/value/items/6' => [  '/value/items/1/value/items/0/value/items/0/value/text',
                                                                '/value/items/1/value/items/1/value/items/0/value/text',
                                                                '/value/items/1/value/items/2/value/items/0/value/text'
                                                            ], // team Row
                    'items/2/value/items/0/value/items/7' => [  '/value/items/1/value/items/0/value/items/0/value/text',
                                                                '/value/items/1/value/items/1/value/items/0/value/text',
                                                                '/value/items/1/value/items/2/value/items/0/value/text'
                                                            ]
                ]
            ],
            [        //Section CONTACT
                'items/3/value/items/0/value/items/0/value/items/0/value/imageSrc', //image
                'items/3/value/items/0/value/items/1/value/items/0/value/text',     //title
                'items/3/value/items/0/value/items/3/value/items/0/value/text',     //text
            ],
            [
                'items/4/value/items/0/value/items/0/value/items/0/value/items/0/value/text',
                'items/4/value/items/0/value/items/0/value/items/1/value/items/0/value/text'
            ]
        ]
            
    ];

    //=========================



    public static $blocHead = "{\"type\":\"Section\",\"value\":{\"_styles\":[\"section\"],\"items\":[{\"type\":\"SectionItem\",\"value\":{\"_styles\":[\"section-item\"],\"items\":[{\"type\":\"Wrapper\",\"value\":{\"_styles\":[\"wrapper\",\"wrapper--richText\"],\"items\":[{\"type\":\"RichText\",\"value\":{\"_styles\":[\"richText\"],\"_id\":\"pwcrmsmusmdifhnkyzwnwzxnvgsmrzxfofmz\",\"text\":\"<h1 class=\\\"brz-tp-lg-heading1 brz-text-lg-center\\\" data-uniq-id=\\\"xiief\\\" data-generated-css=\\\"brz-css-eeusv\\\"><span class=\\\"brz-cp-color8 brz-capitalize-on\\\">THE ZION COMMUNITY<\/span><\/h1>\"}}],\"_id\":\"zicsthhfvtlbqbtxpmholmtdycijgqvjehwp\"}},{\"type\":\"Wrapper\",\"value\":{\"_styles\":[\"wrapper\",\"wrapper--line\"],\"items\":[{\"type\":\"Line\",\"value\":{\"_styles\":[\"line\"],\"_id\":\"ptksjewnizjjasiewzjvzptrrynfzwqosoni\",\"borderStyle\":\"solid\",\"tempBorderStyle\":\"solid\",\"borderColorHex\":\"#73777f\",\"borderColorOpacity\":0.75,\"tempBorderColorOpacity\":0.75,\"borderColorPalette\":\"color3\",\"tempBorderColorPalette\":\"color7\",\"borderWidthType\":\"grouped\",\"borderWidth\":2,\"tempBorderWidth\":2,\"borderTopWidth\":2,\"tempBorderTopWidth\":2,\"borderRightWidth\":2,\"tempBorderRightWidth\":2,\"borderBottomWidth\":2,\"tempBorderBottomWidth\":2,\"borderLeftWidth\":2,\"tempBorderLeftWidth\":2,\"width\":49,\"widthSuffix\":\"%\"}}],\"_id\":\"gbijfcyvjybvmkcnvqshigdwjrxwyzepjzbi\"}},{\"type\":\"Wrapper\",\"value\":{\"_styles\":[\"wrapper\",\"wrapper--richText\"],\"items\":[{\"type\":\"RichText\",\"value\":{\"_styles\":[\"richText\"],\"_id\":\"iahxhscnnqaczzvlzdiphqnqfrqcdsztknqd\",\"text\":\"<p class=\\\"brz-text-lg-center brz-tp-lg-paragraph\\\" data-uniq-id=\\\"edfls\\\" data-generated-css=\\\"brz-css-gxhbi\\\"><span class=\\\"brz-cp-color7\\\" style=\\\"opacity: 0.8;\\\">The Zion Community is a non-denominational church in Brooklyn, striving to live out the Biblical command to love God, each other and our neighbors well. This means worshipping and following Jesus Christ not only through our words, but also through the lives we lead. <\/span><\/p><p class=\\\"brz-text-lg-center brz-tp-lg-paragraph\\\" data-uniq-id=\\\"edfls\\\" data-generated-css=\\\"brz-css-gxhbi\\\"><span class=\\\"brz-cp-color7\\\" style=\\\"opacity: 0.8;\\\"> <\/span><\/p><p class=\\\"brz-text-lg-center brz-tp-lg-paragraph\\\" data-uniq-id=\\\"edfls\\\" data-generated-css=\\\"brz-css-gxhbi\\\"><span class=\\\"brz-cp-color7\\\" style=\\\"opacity: 0.8;\\\">Zion began in 2010 when Jared Hunt came to Brooklyn, on mission with his wife Deborah and their 2 children.<\/span><\/p><p class=\\\"brz-text-lg-center brz-tp-lg-paragraph\\\" data-uniq-id=\\\"edfls\\\" data-generated-css=\\\"brz-css-gxhbi\\\"><span class=\\\"brz-cp-color7\\\" style=\\\"opacity: 0.8;\\\">Recognizing that creativity and justice were expressions of God already widely celebrated within the culture, Jared and the other founding members embraced and supported Brooklyn's burgeoning indie art scene and social justice sector, building a creative and activating experience of worship.<\/span><\/p>\"}}],\"_id\":\"arrubsavyiprwooylbdumyebtnscxtarnypu\"}}],\"_id\":\"tvhsvvlbvczoyzdesmhwurvgnvpmvkxuvqyy\",\"tabsState\":\"normal\",\"bgColorType\":\"solid\",\"tempBgColorType\":\"solid\",\"bgColorHex\":\"#393939\",\"bgColorOpacity\":1,\"tempBgColorOpacity\":1,\"bgColorPalette\":\"color2\",\"tempBgColorPalette\":\"\",\"gradientColorHex\":\"#009900\",\"gradientColorOpacity\":1,\"tempGradientColorOpacity\":1,\"gradientColorPalette\":\"\",\"tempGradientColorPalette\":\"\",\"gradientType\":\"linear\",\"gradientStartPointer\":0,\"gradientFinishPointer\":100,\"gradientActivePointer\":\"startPointer\",\"gradientLinearDegree\":90,\"gradientRadialDegree\":90,\"bgImageSrc\":\"\",\"bgImageFileName\":\"\",\"bgImageExtension\":\"\",\"bgImageWidth\":0,\"bgImageHeight\":0,\"paddingType\":\"ungrouped\",\"paddingBottom\":150,\"bgSize\":\"contain\",\"containerSize\":94,\"paddingTop\":150,\"tabletPaddingType\":\"ungrouped\",\"tabletPaddingTop\":100,\"tabletPaddingBottom\":100,\"mobilePaddingType\":\"ungrouped\",\"mobilePaddingTop\":75,\"mobilePaddingBottom\":75}}],\"_id\":\"cntwcgzcslhbjwwynrocvfehitxtgskpjhpl\",\"_thumbnailSrc\":13987049,\"_thumbnailWidth\":600,\"_thumbnailHeight\":217,\"_thumbnailTime\":1666853917516},\"blockId\":\"Kit2Blank000Light\"}";
    
    public static $sectionBloc = '{"type":"RichText","value":{"_styles":["richText"],"_id":"myvsbdrubdvggfenyvxwamnqenquphavebgy","text":"<h1 data-generated-css=\"brz-css-dihaf\"data-uniq-id=\"aaeap\" class=\"brz-text-lg-center brz-tp-lg-heading1\"><span class=\"brz-cp-color8\">myadd<\/span><\/h1>"}}';
    public static $sectionBlocArrayReplace = array("{TITLE}", "{URL}");





    public $mainMenuArray;
    public $sectionWelcome;

    public static function getNamePage()
    {
        return self::$namePages;
    }

} 