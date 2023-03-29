<?php
namespace Brizy;

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