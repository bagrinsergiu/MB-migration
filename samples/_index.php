<?php
namespace Brizy;

require_once(__DIR__ . '/lib/Core.php');
$brizyAPI   = new BrizyAPI();


//$parametrs = ['page'=>1, 'count'=>100];
$parametrs = ['name'=>'CreateScript'];

$result = $brizyAPI->httpClient('POST', 'https://beta1.brizy.cloud/api/2.0/workspaces', $parametrs );
var_dump($result);

exit;

$parametrs = ['name'=>'CreateScript'];

$parametrs = ['page'=>1, 'count'=>100];

$result = httpClient('GET', 'https://beta1.brizy.cloud/api/2.0/workspaces', $parametrs );
var_dump($result);
exit;

//$params = array(
//    'name' => 'CreateScript'
//);
//
//var_dump(Helper::curlExec('https://beta1.brizy.cloud/api/2.0/workspaces',$params, 'POST'));
//$param = ['slug' => '/token', 'getToken' => 'client_id=3onlcdgeeh0k8s4s4wkccwo8kwwo4g0g&client_secret=4ock4cos8wsowskw4c8cs4wkcskwkow0&grant_type=user_client_credentials&scope=user'];

//$url = "https://icanhazip.com/";

//$url = Config::$urlAPI;
//
//$resultquery = $helper->curlExec($url, $param);

//$token = $brizyAPI->getUserToken();


//$graph = graphQlInit();



//var_dump($token['access_token']);



// if (isset($_GET['function'])) {
//     $functionName = $_GET['function'];
//     if (function_exists($functionName)) {
//         $functionName();
//     } else {
//         echo 'Function not found.';
//     }
// }



exit;

$requestSiteId = $db->request("SELECT * from `sites` WHERE id = 299");
while($siteId = mysqli_fetch_array($requestSiteId))
{
    $itemsArrSite   = array();
    $pageArray      = array();

    $designsName = $db->requestArray("SELECT `name` from `designs` WHERE `uuid` = '" . $siteId['design_uuid'] . "'");

    $itemsArrSite[] = array(
        "templat"   => $designsName['name'],
        "favicon"   => $siteId['favicon'],
        "settings"  => array(json_decode($siteId['settings'])
        )
    );

    $requestPageSite = $db->request("SELECT id, slug FROM pages WHERE site_id = " . $siteId['id'] . " AND parent_id IS NULL AND slug = 'about-us' ORDER BY parent_id ASC, `position`");
    while($pageSiteArray = mysqli_fetch_array($requestPageSite))
    {
        $itemsArray = array();

        $tool->log('Get Slug: ' . $pageSiteArray['slug'], 0, 'main');

        $cons = new Constructor($designsName[0], $pageSiteArray['slug']);

        $templatDataArray = $cons->getJsonObject();

        if(!$templatDataArray)
        {
            continue;
        }

        $requestPage = $db->request("SELECT id, position FROM pages WHERE site_id = " . $siteId['id'] . " and parent_id = " . $pageSiteArray['id'] . " ORDER BY `position` asc");
        while($requestPageArray = mysqli_fetch_array($requestPage))
        {

            $sectionArray = [];

            $tool->log('Page Id: '.$requestPageArray['id'] . ' Orde BY ' . $requestPageArray['position'],0,'main');

            $requestSections = $db->request("SELECT id, section_layout_uuid, category, position FROM sections WHERE page_id  = " . $requestPageArray['id']);
            while($requestSectionsArray = mysqli_fetch_array($requestSections))
            {
                $partArray              = [];
                $itemsArray             = [];
                $itemsConstructorArray  = [];

                $tool->log('Section Id: '.$requestSectionsArray['id'], 0, 'main');

                if($requestSectionsArray['category']=='text')
                {
                    $tool->log('Category: ' . $requestSectionsArray['category'], 0, 'main');

                    $sectionLayouts = $db->requestArray("SELECT * FROM section_layouts WHERE uuid = '" . $requestSectionsArray['section_layout_uuid'] . "'");

                    $requestItems = $db->request("SELECT * FROM items WHERE section_id  = " . $requestSectionsArray['id']);
                    while($requestItemsArray = mysqli_fetch_array($requestItems))
                    {
                        if ($requestItemsArray['category'] == 'text')
                        {
                            $itemsArray[] = $requestItemsArray['content'];
                        }
                        elseif($requestItemsArray['category'] == 'photo' and $requestItemsArray['content'] != NULL)
                        {
                            $itemsArray[] = array(
                                "image" => $requestItemsArray['content'],
                                "settings" => $requestItemsArray['settings']
                            );
                        }
                    }

                    foreach($cons->pathReplace[$cons->getPageName($pageSiteArray['slug'])][$requestPageArray['position']-1] as $key => $value)
                    {
                        $templatDataArray = $cons->getJsonObject();

                        $tool->log('EditArray | Path: ' . $value . ' Value: ' . $itemsArray[$key],0,'main');
                        $cons->setJsonObject($cons->editArray($templatDataArray,$value,$itemsArray[$key]));
                    }

                    $partArray[] = $itemsArray;

                }
                elseif($requestSectionsArray['category']=='list')
                {
                    $tool->log('Category: ' . $requestSectionsArray['category'] . ' ID: ' . $requestSectionsArray['id'], 0, 'main');

                    $parentItems = $db->request("SELECT * FROM items WHERE section_id  = " . $requestSectionsArray['id'] . " AND category = 'list' and parent_id is NULL ORDER BY order_by asc");
                    while($parentListItems = mysqli_fetch_array($parentItems))
                    {
                        $ConstructorArray = array();

                        $requestListItems = $db->request("SELECT * FROM items WHERE parent_id  = " . $parentListItems['id'] . " ORDER BY order_by asc");
                        while($itemsListArray = mysqli_fetch_array($requestListItems))
                        {
                            if ($itemsListArray['category'] == 'text')
                            {
                                $ConstructorArray[] = array($itemsListArray['item_type'] => $itemsListArray['content']);

                            }
                            elseif($itemsListArray['category'] == 'photo')
                            {
                                $ConstructorArray[] = array(
                                    "image" => $itemsListArray['content'],
                                    "settings" => $itemsListArray['settings']
                                );
                            }

                        }
                        $collectorArray[] = $ConstructorArray;
                    }
                    $partArray['list'] = $collectorArray;
                }
                //var_dump($cons->getJsonObject());
                //var_dump($templatDataArray);
                //var_dump($partArray);





                // foreach ($itemsArray as $key=>$value)
                // {
                //     echo $key;
                // }







                //var_dump($itemsArray);

                //$partArray[] = array($itemsArray);



                // //$pageArray[] = array($requestPageArray['slug'] => $partArray);
                // $sectionArray[] = $partArray;
                //break;
                //var_dump($itemsArray);
            }

            //$datajsonDecodeData['items'][1]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = $tool->cleanJson($pageArray[1]['about-us'][1]['body']);
            //$datajsonDecodeData['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $pageArray[1]['about-us'][0]['title'];

            //var_dump($sectionArray); //title
            //var_dump($cons->getJsonObject());
        }

        // while($sectionsSite = mysqli_fetch_array($requestSectionsSite))
        // {

        //     if($sectionsSite['position'] >= 0 && $sectionsSite['parent_id'] == NULL)
        //     {
        //         if($sectionsSite['slug'] == 'home')
        //         {
        //             $sectionsSite['slug'] = '';
        //         }
        //         $itemsArrayMainMenu = array(
        //             $sectionsSite['name'],
        //             '/'.$sectionsSite['slug']
        //         );

        //         $editBlocMenu = strReplace($_WorkClassTemplate::$menuBloc, $_WorkClassTemplate::$menuBlocArrayReplace, $itemsArrayMainMenu);

        //         $itemsArray[] = json_decode($editBlocMenu,true);
        //     }

        // }



        //$pageArray[] = array($pageSiteArray['slug'] => $partArray);

        //var_dump($sectionArray); //title

        //var_dump($cons->getJsonObject());
    }

    //var_dump($pageArray[1]['about-us'][1]['body']);
    //var_dump($pageArray[1]['about-us'][0]['title']);

    //var_dump($datajsonDecodeData['items'][1]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text']);
    //echo $_WorkClassTemplate::$menuBloc;
    //getPageName

    //echo $cons->getPageName();

    //var_dump($datajsonDecodeData);


    //var_dump(createJsonData());
    var_dump($cons->getJsonObject());
}


//==========================



// while($sectionsSite = mysqli_fetch_array($requestSectionsSite))
// {

//     if($sectionsSite['position'] >= 0 && $sectionsSite['parent_id'] == NULL)
//     {
//         if($sectionsSite['slug'] == 'home')
//         {
//             $sectionsSite['slug'] = '';
//         }
//         $itemsArrayMainMenu = array(
//             $sectionsSite['name'],
//             '/'.$sectionsSite['slug']
//         );

//         $editBlocMenu = strReplace($_WorkClassTemplate::$menuBloc, $_WorkClassTemplate::$menuBlocArrayReplace, $itemsArrayMainMenu);

//         $itemsArray[] = json_decode($editBlocMenu,true);
//     }

// }

// $editBlocMenu = strReplace($_WorkClassTemplate::$menuBloc, $_WorkClassTemplate::$menuBlocArrayReplace, $itemsArrayMainMenu);

// $datajsonDecode['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'] = $itemsArray;

// $sectionBlocArray[] = json_decode($_WorkClassTemplate::$sectionBloc,true);



// $datajsonDecode['items'][2]['value']['items'][0]['value']['items'][0]['value']['items'][0] = $sectionBlocArray;
// $datajsonollect .=
// $datajsonDecode = array( "data" => json_encode($datajsonDecode));


//==========================

// $itemsArr = array();

// if($sectionsSite['page_id'] != 0)
// {
//     $query3 = $db->reqest("SELECT `name`, `slug` from `pages` WHERE `id` = '".$sectionsSite['page_id']."'");
//     $pageSite = mysqli_fetch_array($query3);
//     $sectionsSiteName = $pageSite['slug'];
// }
// else
// {
//     $sectionsSiteName = $sectionsSite['category'];
// }

// $query2 = $db->reqest("SELECT * from `items` WHERE `section_id` = '".$sectionsSite['id']."'");
// while($itemsSite = mysqli_fetch_array($query2))
// {
//     if ($itemsSite['item_type']=='title')
//     {
//         $itemsArr[] = array(
//             "title" => $itemsSite['content'],
//             "settings"  => json_decode($itemsSite['settings'])
//         );
//     }
//     elseif($itemsSite['item_type']=='body')
//     {
//         $itemsArr[] = array(
//             "body" => $itemsSite['content'],
//             "settings"  => json_decode($itemsSite['settings'])
//         );
//     }
//     elseif($itemsSite['item_type']==NULL and $itemsSite['category']=='photo')
//     {
//         if(!empty($itemsSite['content']))
//         {
//             $itemsArr[] = array(
//                 "photo" => $itemsSite['content'],
//                 "settings"  => json_decode($itemsSite['settings'])

//             );
//         }
//     }
// }
// $itemsArrSite[] = array( $sectionsSiteName => $itemsArr);
// $itemsArr = array();

//==========================



// $jsonDataArr = jsonDataLoad($designsName[0]);
// $getDat = 'data';

// json_decode($jsonDataArr->$getDat);





// $jsonString = file_get_contents($jsonDataLayout);
// $jsonData = json_decode($jsonString);

// $jsonDataArr = json_decode($jsonData->media);
// var_dump($jsonDataArr);

// $dataArr = json_decode($jsonData->data);

// print_r($dataArr);




// $jsonDataLayout = str_replace("{theme}", $themes['August'], $pathLayoutData);
// $jsonDataArr = json_decode($jsonData->meta);
// var_dump($jsonDataArr);

//$jsonData = json_decode($jsonData->data);

//var_dump($jsonData);

// $query = $db->reqest("SELECT * from items WHERE site_id = '148'");

// while($row = mysqli_fetch_row($query))
// {

//     if($row[1]=='photo')
//         {

//         $size = json_decode($row[9]);

//             $image = array(
//                 "bgImageSrc"    => $row[8],
//                 "bgImageWidth"  => $size['width'],
//                 "bgImageHeight" => $size['height']
//             );
//         }
//     print_r($row);
// }




// use vendor\xlsx;
// $jsonFile = '/opt/lampp/htdocs/parser/v1/Layout/8026827f75b5aded9743733df21d436c/data.json';
// $xlsxFile = '/opt/lampp/htdocs/parser/v1/xlsx/';

// $jsonString = file_get_contents($jsonFile);

// $jsonData = json_decode($jsonString, true);

// var_dump($jsonData['data']);

// $reader = ReaderEntityFactory::createXLSXReader();
// $reader->open($xlsxFile);

// foreach ($reader->getSheetIterator() as $sheet) {
//     foreach ($sheet->getRowIterator() as $row) {
//         foreach ($row->getCells() as $cell) {
//             var_dump($cell->getValue());
//         }
//     }
// }
// $reader->close();



