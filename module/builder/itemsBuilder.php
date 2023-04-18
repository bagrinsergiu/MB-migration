<?php
namespace Builder;

use Brizy\Helper;

class ItemsBuilder
{
    public $datajsonDecodeClass;
    private $namePagesArray;
    private $pageName;
    private $designsName;

    private $arrayObject;

    function __construct($name, $page)
    {
        
        $_WorkClassTemplate = '\\' . __NAMESPACE__ . '\\' . $name;

        $this->pageName = $page;

        $this->designsName = $name;

        $this->namePagesArray = $_WorkClassTemplate::$namePages;

        $this->arrayObject = $this->objectData();
        
    }

    public function getJsonObject($object = 'data') 
    {   
        return $this->arrayObject[$object];
    }

    public function getClass()
    {   
        return new self::$designsName();
    }

    public function editArray($array, $path, $newValue) 
    {
        $keys = explode('/', $path);

        $currentArray = &$array;

        foreach ($keys as $key) 
        {
            $currentArray = &$currentArray[$key];
        }

        $currentArray = $newValue;

        return $array;
    }

    function getValueFromArrayByPath($array, $path) 
    {
        $nameFunction = __FUNCTION__;

        if(!is_array($array))
        {
            Helper::log('Bad Array', 2, $nameFunction);
            return FALSE;
        }

        $keys = explode('/', $path);
        foreach ($keys as $key) 
        {
          if (isset($array[$key])) 
          {
            $array = $array[$key];
          } 
          else 
          {
            return null;
          }
        }

        return $array;
    }

    public function verificationArray($array, $nameFunction)
    {
        if(!is_array($array))
        {
            Helper::log('Bad Array', 2, $nameFunction);
            return FALSE;
        }
    }

    private function objectData()
    {
        $nameFunction = __FUNCTION__;

        $templatDataArray = $this->jsonDataLoad($this->designsName, $this->getPageName($this->pageName));

        if($templatDataArray != FALSE)
        {
            $templatArray = array(
                "class"     => $templatDataArray->class,
                "data"      => json_decode($templatDataArray->data, true),
                "media"     => json_decode($templatDataArray->media, true),
                "meta"      => json_decode($templatDataArray->meta, true),
                "version"   => $templatDataArray->editorVersion,
                "files"     => $templatDataArray->files,
                "hasPro"    => $templatDataArray->hasPro,
            );
            return $templatArray;
        }
        else
        {
            Helper::log('Bad Array', 2, $nameFunction);
            return FALSE;
        }
    
    }

    public function getPageName($name = 'home')
    {
        $nameFunction = __FUNCTION__;
        
        if(isset($this->namePagesArray[$name]))
        {
            return $this->namePagesArray[$name];
        }
        else
        { 
            Helper::log('Page does not exist in array. key: '. $name, 2, $nameFunction);
            return FALSE;
        }
    }

    public function jsonDataLoad($nameTemplate, $namePage = 'home', $path = 'default')
    {
        $nameFunction = __FUNCTION__;
        
        global $pathLayoutData;

        if($path != 'default')
        {   
            $pathLayoutData = $path;
        }

        $jsonDataLayout = Helper::strReplace(Config::$pathLayoutData, array("{theme}", "{page}"), array(Config::$themes[$nameTemplate], $namePage) );

        Helper::log('Import: '.$jsonDataLayout,0,$nameFunction);

        if(file_exists($jsonDataLayout))
        {
            $jsonString = file_get_contents($jsonDataLayout);

            $jsonData = json_decode($jsonString);

            return $jsonData;
        }
        else
        {   
            Helper::log('File does not exist. PATH: '. $jsonDataLayout, 2, $nameFunction);
            return FALSE;
        }
    }

    // public function createJsonData($oarr = 'all')
    // {
    //     global $datajsonDecodeClass, $datajsonDecodeData, $datajsonDecodeMedia, $datajsonDecodeMeta, $datajsonDecodeEditorVersion, $datajsonDecodeFiles, $datajsonDecodeHasPro;          

    //     $dataJsonEncode = array(
    //                             "class"         => $datajsonDecodeClass,
    //                             "media"         => json_encode($datajsonDecodeMedia),
    //                             "data"          => json_encode($datajsonDecodeData),
    //                             "meta"          => json_encode($datajsonDecodeMeta),
    //                             "editorVersion" => $datajsonDecodeEditorVersion,
    //                             "files"         => $datajsonDecodeFiles,
    //                             "hasPro"        => $datajsonDecodeHasPro
    //     );
        
    //     if($oarr == 'all')
    //     {
    //         return json_encode($dataJsonEncode);
    //     }
    //     else
    //     {
    //         if (array_key_exists($oarr, $dataJsonEncode))
    //         {
    //             return $dataJsonEncode[$oarr];
    //         }
    //         else 
    //         {
    //             return FALSE;
    //         }
    //     }    
    // }

}