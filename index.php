<?php
namespace Brizy;

require_once(__DIR__. '/module/autoloader.php');

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

        $requestPage = $db->request("SELECT id FROM pages WHERE site_id = " . $siteId['id'] . " and parent_id = " . $pageSiteArray['id'] . " ORDER BY `position` asc");
        while($requestPageArray = mysqli_fetch_array($requestPage))
        {

            $sectionArray = array();

            $tool->log('Page Id: '.$requestPageArray['id'] ,0,'main');

            $requestSections = $db->request("SELECT id, section_layout_uuid, category FROM sections WHERE page_id  = " . $requestPageArray['id']);
            while($requestSectionsArray = mysqli_fetch_array($requestSections))
            {
                $partArray              = array();
                $itemsArray             = array();
                $itemsConstructorArray  = array();

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
                
                var_dump($partArray);
                
            }
        }
    }
}