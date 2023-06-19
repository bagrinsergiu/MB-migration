<?php
namespace Builder;

use Brizy\Builder\VariableCache;
use Brizy\core\Config;
use Brizy\core\Utils;
use Brizy\layer\Graph\QueryBuilder;

class ItemsBuilder
{
    private $namePagesArray;
    private $pageName;
    private $designsName;
    private $arrayObject;
    private VariableCache $cache;
    private QueryBuilder $QueryBuilder;

    /**
     * @throws \Exception
     */
    public function __construct($preparedSectionOfThePage, VariableCache $cache, $defaultPage = false)
    {
        $this->cache = $cache;
        $this->QueryBuilder = new QueryBuilder($cache);

        $itemsID = $this->cache->get('currentPageOnWork');
        $design = $this->cache->get('settings')['design'];
        $slug = $this->cache->get('tookPage')['slug'];

        $workClass = 'Brizy\\' . __NAMESPACE__ . '\\Layout\\' . $design . '\\' . $design;

        $_WorkClassTemplate = new $workClass($cache);

        if(!$defaultPage)
        {
            $itemsData = [];
            $menuBlock = json_decode($cache->get('menuBlock'),true);
            $itemsData['items'][] = $menuBlock;
            Utils::log('Current Page: ' . $itemsID . ' | Slug: ' . $slug, 1, 'ItemsBuilder');
            $this->cache->update('createdFirstSection',false, 'flags');
            $this->cache->update('Current', '++', 'Status');
            foreach ($preparedSectionOfThePage as $section)
            {
                $blockData = $_WorkClassTemplate->callMethod($section['typeSection'], $section);

                if (!empty($blockData) && $blockData !== "null") {
                    $decodeBlock = json_decode($blockData, true);
                    $itemsData['items'][] = $decodeBlock;
                } else {
                    Utils::log('null' . $slug, 2, 'ItemsBuilder');
                }
            }
            $this->sendStatus();

            $itemsData['items'][] = json_decode($cache->get('footerBlock'),true);

            $pageData = json_encode($itemsData);

            Utils::log('Request to send content to the page: ' . $itemsID . ' | Slug: ' . $slug, 1, 'ItemsBuilder');

            $this->saveLayoutJson($pageData, $slug);

            $this->QueryBuilder->updateCollectionItem($itemsID, $slug, $pageData);

            Utils::log('Content added to the page successfully: ' . $itemsID . ' | Slug: ' . $slug, 1, 'ItemsBuilder');
            return true;
        }
        else
        {
            Utils::log('Build default Page: ' . $itemsID . ' | Slug: ' . $slug, 1, 'ItemsBuilder');
            $_WorkClassTemplate->callMethod('create-Default-Page');
            return true;
        }
        return false;
    }

    public function getJsonObject($object = 'data') 
    {   
        return $this->arrayObject[$object];
    }

    public function load_json_file($designProject, $assoc = true)
    {
        $file = file_get_contents(__DIR__ . '\\Layout\\' . $designProject . '\\blocksKit.json');
        return json_decode($file, $assoc);
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
            Utils::log('Bad Array', 2, $nameFunction);
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

    private function objectData(): bool|array
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
            Utils::log('Bad Array', 2, $nameFunction);
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
            Utils::log('Page does not exist in array. key: '. $name, 2, $nameFunction);
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

        $jsonDataLayout = Utils::strReplace(Config::$pathLayoutData, ["{theme}", "{page}"], [Config::$themes[$nameTemplate], $namePage] );

        Utils::log('Import: '.$jsonDataLayout,0,$nameFunction);

        if(file_exists($jsonDataLayout))
        {
            $jsonString = file_get_contents($jsonDataLayout);

            $jsonData = json_decode($jsonString);

            return $jsonData;
        }
        else
        {
            Utils::log('File does not exist. PATH: '. $jsonDataLayout, 2, $nameFunction);
            return FALSE;
        }
    }

     public function createJsonData($oarr = 'all')
     {
         global $datajsonDecodeClass, $datajsonDecodeData, $datajsonDecodeMedia, $datajsonDecodeMeta, $datajsonDecodeEditorVersion, $datajsonDecodeFiles, $datajsonDecodeHasPro;

         $dataJsonEncode = array(
                                 "class"         => $datajsonDecodeClass,
                                 "media"         => json_encode($datajsonDecodeMedia),
                                 "data"          => json_encode($datajsonDecodeData),
                                 "meta"          => json_encode($datajsonDecodeMeta),
                                 "editorVersion" => $datajsonDecodeEditorVersion,
                                 "files"         => $datajsonDecodeFiles,
                                 "hasPro"        => $datajsonDecodeHasPro
         );
        
         if($oarr == 'all')
         {
             return json_encode($dataJsonEncode);
         }
         else
         {
             if (array_key_exists($oarr, $dataJsonEncode))
             {
                 return $dataJsonEncode[$oarr];
             }
             else
             {
                 return FALSE;
             }
         }
     }

    private function saveLayoutJson(string $pageData, string $pageName): void
    {
        $mainFolder = $this->cache->get('page','ProjectFolders');
        if(!is_dir($mainFolder)) {
            mkdir($mainFolder, 0777, true);
        }
        $json = json_encode($pageData);
        $fileFolder =  $mainFolder . '/' . $pageName . '.json';
        file_put_contents($fileFolder, $json);
        Utils::log('Created json dump, page: '. $pageName, 1, 'saveLayoutJson' );
    }

    private function sendStatus(): void
    {
        echo json_encode($this->cache->get('Status'));
    }

}