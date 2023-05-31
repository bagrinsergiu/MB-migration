<?php
$json = '{"type":"Column","value":{"_styles":["column"],"items":[{"type":"Wrapper","value":{"_styles":["wrapper","wrapper--image"],"items":[{"type":"Image","value":{"_styles":["image"],"_id":"mxsatwjztnlxjcclgjmykqevdgapzlwn","imageSrc":"1420cb3e3f445bdecd16e6c72e12292d.jpg","imageFileName":"98e59231-d4f7-427c-8052-129144394bd1.jpg","imageExtension":"jpg","imageWidth":696,"imageHeight":896,"width":100,"height":58.26,"widthSuffix":"%","heightSuffix":"%","mobileHeight":null,"mobileHeightSuffix":null,"mobileWidth":null,"mobileWidthSuffix":null,"tabletHeight":null,"tabletHeightSuffix":null,"tabletWidth":null,"tabletWidthSuffix":null,"sizeType":"original","size":100,"borderStyle":"solid","tempBorderStyle":"solid","borderColorHex":"#66738d","borderColorOpacity":1,"tempBorderColorOpacity":1,"borderColorPalette":"color8","tempBorderColorPalette":"","borderWidthType":"grouped","borderWidth":2,"tempBorderWidth":2,"borderTopWidth":2,"tempBorderTopWidth":4,"borderRightWidth":2,"tempBorderRightWidth":4,"borderBottomWidth":2,"tempBorderBottomWidth":4,"borderLeftWidth":2,"tempBorderLeftWidth":4,"tabsState":"normal","mobileSize":73}}],"_id":"jklpisgdfjnisanjzuqaxrmxorufyjuk","mobileHorizontalAlign":"left"}}],"_id":"iezdvjfkmzvfitlbeexizxgounjaquwb","width":29.3}}';

$php = json_decode($json, true);

$c = $php['value']['items'][10]['value']['items'][0];

///var_dump($c);
//
//$a = json_decode($json);
//$b = $a?->value?->items[10]?->value?->items[0];
//
//if (!$c) {
//    echo 'null safe operATOR';
//}
//
//var_dump($b);