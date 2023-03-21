<?php

namespace Templates;

class Anthem 
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;

    public static $sectionBloc;
    public static $sectionBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {

        $jsonData = jsonDataLoad('Anthem');

        $jsonFile = __DIR__.  '/../layout/Anthem/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);



    }


}

class August 
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;

    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/August/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}


class Aurora 
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Aurora/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Bloom
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Bloom/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Boulevard
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Boulevard/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Dusk
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Dusk/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Ember
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Ember/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Hope
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Hope/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Majesty
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Majesty/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Serene
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Serene/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Solstice
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Solstice/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Tradition
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Tradition/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Voyage
{

    public static $menuBloc;
    public static $menuBlocArrayReplace;


    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile = __DIR__.  '/../layout/Voyage/{page}/data.json';
        $jsonString = file_get_contents($jsonFile);
        $jsonData = json_decode($jsonString);

    }


}

class Zion
{
    public $namePages = array(
                                "Home" => "home",
                                "home" => "home",
                                
                                "About Us" => "about",
                                "about-us" => "about",

                                "Connect" => "contact",
                                "connect" => "contact",

                                "Ministries" => "services",
                                "ministries" => "services",

                                "Donate" => "donate",
                                "donate" => "donate"
                             );


    public static $menuBloc = '{"type":"MenuItem","value":{"id":"74133a89db79c8b4cff11bd0be506cc3","itemId":"\/collection_items\/1986323","title":"{TITLE}","url":"\{URL}","target":"","items":[],"current":true,"_id":"oimnfydaljdcxfifmutwlkekkviukituqmjz","megaMenuItems":[{"type":"SectionMegaMenu","value":{"items":[],"_id":"pbewefqwmmyskrtqfkuwxjpmaqnfmobqyhvg"}}]}}';
    public static $menuBlocArrayReplace = array("{TITLE}", "{URL}");


    public static $sectionBloc = '{"type":"RichText","value":{"_styles":["richText"],"_id":"myvsbdrubdvggfenyvxwamnqenquphavebgy","text":"<h1 data-generated-css=\"brz-css-dihaf\"data-uniq-id=\"aaeap\" class=\"brz-text-lg-center brz-tp-lg-heading1\"><span class=\"brz-cp-color8\">myadd<\/span><\/h1>"}}';
    public static $sectionBlocArrayReplace = array("{TITLE}", "{URL}");

    public $mainMenuArray;
    public $sectionWelcome;


    function __construct($page)
    {
        $jsonFile           = __DIR__.  '/../layout/Zion/{page}/data.json';
        $jsonString         = file_get_contents($jsonFile);
        $templatDataArray   = json_decode($jsonString);

        $datajsonDecode     = json_decode($templatDataArray->data, true);

    }

}