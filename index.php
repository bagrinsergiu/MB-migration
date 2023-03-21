<?php

namespace Templates;

require_once(__DIR__ . '/module/autoloader.php');

$requestSiteId = $db->request("SELECT * from `sites` WHERE id = 299");
while ($siteId = mysqli_fetch_array($requestSiteId)) {
    $itemsArrSite   = array();
    $pageArray      = array();

    $requestDesignsName = $db->request("SELECT `name` from `designs` WHERE `uuid` = '" . $siteId['design_uuid'] . "'");
    $designsName = mysqli_fetch_array($requestDesignsName);

    $_WorkClassTemplate = '\\Templates\\' . $designsName[0];

    $itemsArrSite[] = array(
        "templat"   => $designsName['name'],
        "favicon"   => $siteId['favicon'],
        "settings"  => array(
            json_decode($siteId['settings'])
        )
    );

    $requestPageSite = $db->request("SELECT id, slug FROM pages WHERE site_id = " . $siteId['id'] . " and parent_id is NULL ORDER BY parent_id asc, `position`");
    while ($pageSiteArray = mysqli_fetch_array($requestPageSite)) {

        $itemsArray                  = array();

        $templatDataArray            = jsonDataLoad($designsName[0], $pageSiteArray['slug']);

        if (!$templatDataArray) {
            break;
        }

        $datajsonDecodeClass         = $templatDataArray->class;
        $datajsonDecodeData          = json_decode($templatDataArray->data, true);
        $datajsonDecodeMedia         = json_decode($templatDataArray->media, true);
        $datajsonDecodeMeta          = json_decode($templatDataArray->meta, true);
        $datajsonDecodeEditorVersion = $templatDataArray->editorVersion;
        $datajsonDecodeFiles         = $templatDataArray->files;
        $datajsonDecodeHasPro        = $templatDataArray->hasPro;

        $requestPage = $db->request("SELECT id FROM pages WHERE site_id = " . $siteId['id'] . " and parent_id = " . $pageSiteArray['id'] . " ORDER BY `position` asc");
        while ($requestPageArray = mysqli_fetch_array($requestPage)) {

            $requestSections = $db->request("SELECT id, section_layout_uuid FROM sections WHERE page_id  = " . $requestPageArray['id']);
            while ($requestSectionsArray = mysqli_fetch_array($requestSections)) {
                $sectioArray    = array();
                $sectionLayouts = $db->requestArray("SELECT * FROM section_layouts WHERE uuid = '" . $requestSectionsArray['section_layout_uuid'] . "'");

                $requestItems = $db->request("SELECT * FROM items WHERE section_id  = " . $requestSectionsArray['id'] . "");
                while ($requestItemsArray = mysqli_fetch_array($requestItems)) {
                    if ($requestItemsArray['category'] == 'text') {
                        $itemsArray[] = array(
                            $requestItemsArray['item_type'] => $requestItemsArray['content']
                        );
                    } elseif ($requestItemsArray['category'] == 'photo' and $requestItemsArray['content'] != NULL) {
                        $itemsArray[] = array(
                            "image"     => $requestItemsArray['content'],
                            "settings"  => $requestItemsArray['settings']
                        );
                    }
                }

                $sectioArray[] = array(
                    $sectionLayouts['name'] => $itemsArray
                );
            }

            $datajsonDecodeData['items'][1]['value']['items'][0]['value']['items'][2]['value']['items'][0]['value']['text'] = cleanJson($pageArray[1]['about-us'][1]['body']);
            $datajsonDecodeData['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = $pageArray[1]['about-us'][0]['title'];
        }

        $pageArray[] = array($pageSiteArray['slug'] => $sectioArray);
    }

    var_dump(createJsonData());
}
