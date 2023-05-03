<?php
$token = null;


$tresult = $token ? 'sett': 'default';

echo $tresult;


exit;

//$jsonFile = __DIR__. '/Layout/Zion/data.json';
// $jsonFile = __DIR__. '/Layout/Zion/about-us/data.json';

// $jsonString = file_get_contents($jsonFile);

// $jsonData = json_decode($jsonString);
// $jsonData = json_decode($jsonData->data, true);


$result = '{
    "data": [
      {
        "id": "497f6eca-6276-4993-bfeb-53cbbbba6f08",
        "name": "Erich Hook",
        "avatar_url": "https://cdn.ministryone.com/assets/users/497f6eca-6276-4993-bfeb-53cbbbba6f08/avatar.jpg",
        "email": "user@example.com",
        "phone": "+1 (555) 555-5555",
        "organization_owner": false,
        "universal_admin": true,
        "status": {
          "current": "active",
          "updated_at": "2019-08-25T08:04:43Z",
          "history": [
            {
              "status": "invited",
              "updated_at": "2019-08-24T14:15:22Z"
            },
            {
              "status": "active",
              "updated_at": "2019-08-25T08:04:43Z"
            }
          ]
        },
        "created_at": "2019-08-24T14:15:22Z",
        "updated_at": "2019-08-25T08:04:43Z"
      }
    ],
    "links": {
      "first": "http://example.com?page=1",
      "last": "http://example.com?page=3",
      "prev": null,
      "next": "http://example.com?page=2"
    },
    "meta": {
      "current_page": 2,
      "from": 11,
      "last_page": 3,
      "links": [
        {
          "url": "http://example.com?page=1",
          "label": "Previous",
          "active": false
        },
        {
          "url": "http://example.com?page=1",
          "label": "1",
          "active": false
        },
        {
          "url": "http://example.com?page=2",
          "label": "2",
          "active": true
        },
        {
          "url": "http://example.com?page=3",
          "label": "3",
          "active": false
        },
        {
          "url": "http://example.com?page=3",
          "label": "Next",
          "active": false
        }
      ],
      "path": "http://example.com",
      "per_page": 10,
      "to": 20,
      "total": 30
    }
  }';

echo json_encode($result);

//$jsonData = json_decode($json);

//$jsonData = $jsonData['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items']; //image
//$jsonData = $jsonData['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['items']; //menu

// $jsonData = $jsonData['items'][1]; //Section welcome (image)

//$jsonData['items'][2]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text'] = "<p class='finaldraft_placeholder'>Section Title</p>"; //Section 2 (CREATED FOR HIM)

//$jsonDataE = $jsonData['items'][1]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['text']; //Section 2 (CREATED FOR HIM)


//$add = "<p class='finaldraft_placeholder'>Section Title</p>"; 


//$jsonDataE = addTextInTeg($jsonDataE, $add);

//$ff = preg_match("/>(.*?)</",$add,$matches);

//$jsonDataE = preg_replace('|(>).*(</span><)|Uis', '$1'.$matches[1].'$2', $jsonDataE);


//var_dump(json_encode($jsonData));
//var_dump($jsonDataE);


function jsonClear($jsonStr)
{
    $arrDel = array('\n',' ');
    $jsonClearData = str_replace($arrDel, '', $jsonStr);  

    return $jsonClearData;
}

function printValues($arr) {
    global $count;
    global $values;
    
    if(!is_array($arr)){
        die("ОШИБКА: Это не массив");
    }
    
     foreach($arr as $key=>$value){
        if(is_array($value)){
            printValues($value);
        } else{
            $values[] = $value;
            $count++;
        }
    }

    return array('total' => $count, 'values' => $values);
}
 
function jsonEdit($directory_path, $word) {
    $files = glob("$directory_path/*");

    foreach ($files as $file) {
        if (is_file($file)) {

            $file_contents = file_get_contents($file);
            if (strpos($file_contents, $word) !== false) {
                echo "Слово '$word' найдено в файле $file<br>";
            }
        } elseif (is_dir($file)) {
            
            jsonEdit($file, $word);
        }
    }
}

function editNestedArrayValue(&$array, $keys, $value) {
    $current_key = array_shift($keys);
    if (count($keys) == 0) {
        $array[$current_key] = $value;
    } else {
        editNestedArrayValue($array[$current_key], $keys, $value);
    }
}

function recursiveSearchAndEdit(&$array, $searchKey, $newValue) {
    foreach ($array as $key => &$value) {
        if ($key === $searchKey) {
            $value = $newValue;
        } elseif (is_array($value)) {
            recursiveSearchAndEdit($value, $searchKey, $newValue);
        }
    }
}

function addTextInTeg($in, $from)
{

    $ff = preg_match("/>(.*?)</",$from,$matches);

    $jsonDataE = preg_replace('|(">).*(</)|Uis', '$1'.$matches[1].'$2',$in);

    return $jsonDataE;

}




$json= '{
    "type": "MenuItem",
    "value": {
        "id": "74133a89db79c8b4cff11bd0be506cc3",
        "itemId": "/collection_items/1986323",
        "title": "HELLO",
        "url": "/",
        "target": "",
        "items": [],
        "current": true,
        "_id": "oimnfydaljdcxfifmutwlkekkviukituqmjz",
        "megaMenuItems": [
            {
                "type": "SectionMegaMenu",
                "value": {
                    "items": [],
                    "_id": "pbewefqwmmyskrtqfkuwxjpmaqnfmobqyhvg"
                }
            }
        ]
    }
}';