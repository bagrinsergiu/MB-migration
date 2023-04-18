<?php
namespace Brizy;

require_once(__DIR__ . '/module/core.php');
$brizyAPI   = new BrizyAPI();

//получаем полный список Workspaces  json
var_dump($brizyAPI->getWorkspaces());

// возвращает только id Workspaces по его имени или возвращет false если ничего не найдено
var_dump($brizyAPI->getWorkspaces(Config::$nameMigration));

var_dump($brizyAPI->createdWorkspaces());

$graphqlToken = $brizyAPI->getGraphToken('4303800');
$idProject = 4303800;

$graphLayer->init($idProject, $graphqlToken['access_token']);





exit;
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
    function httpClient($method, $url, $data = null, $token = null ): array
    {
        $client = new Client();

        $token = $token ? $this->projectToken : Config::$devToken;

        try {
            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'x-auth-user-token' => $token
            ];
            $options = [
                'headers' => $headers,
                'timeout' => 30,
                'connect_timeout' => 5,
                'form_params'=>[ 'name'=>'CreateScript' ]
            ];
            if ($method === 'POST' && isset($data))
            {
                $options['form_params'] = $data;
            }

            if($method === 'GET')
            {
                $data = http_build_query($data);
                $url  = sprintf("%s?%s", $url, $data);
            }

            $response = $client->request($method, $url, $options);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            return ['status' => $statusCode, 'body' => $body];

        } catch (RequestException $e) {
            if ($e->hasResponse())
            {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();

                return ['status' => $statusCode, 'body' => $body];
            }
            else
            {
                return ['status' => false, 'body' => 'Request timed out.'];
            }
        } catch (GuzzleException $e) {
            return ['status' => false, 'body' => $e->getMessage()];
        }
    }





//$params = array(
//    'name' => 'CreateScript'
//);
//
//var_dump(Helper::curlExec('https://beta1.brizy.cloud/api/2.0/workspaces',$params, 'POST'));













//curl -X 'POST' '' -H 'connection: close' -H 'content-length: 17' -H 'content-type: application/x-www-form-urlencoded' -H 'user-agent: GuzzleHttp/7' -H 'host: webhook.site' -d $'name=CreateScript'
//curl -X "POST" /api/2.0/workspaces -H "Accept:\ application/json" -H "Content-type:\ application/x-www-form-urlencoded"  -d "name=create2"
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
    $itemsArrSite   = [];
    $pageArray      = [];

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
        $itemsArray = [];

        $helper->log('Get Slug: ' . $pageSiteArray['slug'], 0, 'main');
 
        $itemsBuilder = new ItemsBuilder($designsName[0], $pageSiteArray['slug']);

        $templatDataArray = $itemsBuilder->getJsonObject();

        //var_dump($templatDataArray);

        if(!$templatDataArray)
        {
            continue;
        }     

        $requestPage = $db->request("SELECT id FROM pages WHERE site_id = " . $siteId['id'] . " and parent_id = " . $pageSiteArray['id'] . " ORDER BY `position` asc");
        while($requestPageArray = mysqli_fetch_array($requestPage))
        {

            $sectionArray = [];

            $helper->log('Page Id: '.$requestPageArray['id'] ,0,'main');

            $requestSections = $db->request("SELECT id, section_layout_uuid, category FROM sections WHERE page_id  = " . $requestPageArray['id']);
            while($requestSectionsArray = mysqli_fetch_array($requestSections))
            {
                $partArray              = [];
                $itemsArray             = [];
                $itemsConstructorArray  = [];

                $helper->log('Section Id: '.$requestSectionsArray['id'], 0, 'main');

                if($requestSectionsArray['category']=='text')
                {
                    $helper->log('Category: ' . $requestSectionsArray['category'], 0, 'main');

                    $sectionLayouts = $db->requestArray("SELECT * FROM section_layouts WHERE uuid = '" . $requestSectionsArray['section_layout_uuid'] . "'");
                    
                    $requestItems = $db->request("SELECT * FROM items WHERE section_id  = " . $requestSectionsArray['id']);
                    while($requestItemsArray = mysqli_fetch_array($requestItems))
                    {
                        if ($requestItemsArray['category'] == 'text')
                        {
                            $itemsArray[$requestItemsArray['item_type']] = $requestItemsArray['content'];
                        }
                        elseif($requestItemsArray['category'] == 'photo' and $requestItemsArray['content'] != NULL) 
                        {
                            $itemsArray['media'] = array(
                                "image" => $requestItemsArray['content'],
                                "settings" => $requestItemsArray['settings']
                            );
                        }
                    }

                    $partArray['text'] = $itemsArray;

                }
                elseif($requestSectionsArray['category']=='list')
                {
                    $helper->log('Category: ' . $requestSectionsArray['category'] . ' ID: ' . $requestSectionsArray['id'], 0, 'main');
                    
                    $parentItems = $db->request("SELECT * FROM items WHERE section_id  = " . $requestSectionsArray['id'] . " AND category = 'list' and parent_id is NULL ORDER BY order_by asc");
                    while($parentListItems = mysqli_fetch_array($parentItems))
                    {   
                        $ConstructorArray = [];

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
                
                var_dump($partArray);
                
            }
        }
    }
}