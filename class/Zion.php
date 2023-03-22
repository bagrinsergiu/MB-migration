<?php

namespace Brizy;

class Zion
{

    public static $namePages = array(
        "Home" => "home",
        "home" => "home",

        "About Us" => "about",
        "about-us" => "about",
        "about" => "about",

        "Connect" => "contact",
        "connect" => "contact",

        "Ministries" => "services",
        "ministries" => "services",

        "Donate" => "donate",
        "donate" => "donate"
    );

    //=========== about =======

    public static $sectionHeadAbout = array(
        'items/1/value/items/0/value/items/0/value/items/0/value/text', //title
        'items/1/value/items/0/value/items/2/value/items/0/value/text', //body
    );

    //=========================

    public static $sectionBloc = '{"type":"RichText","value":{"_styles":["richText"],"_id":"myvsbdrubdvggfenyvxwamnqenquphavebgy","text":"<h1 data-generated-css=\"brz-css-dihaf\"data-uniq-id=\"aaeap\" class=\"brz-text-lg-center brz-tp-lg-heading1\"><span class=\"brz-cp-color8\">myadd<\/span><\/h1>"}}';
    public static $sectionBlocArrayReplace = array("{TITLE}", "{URL}");

    public $mainMenuArray;
    public $sectionWelcome;

    public static function getNamePage()
    {
        return self::$namePages;
    }

}