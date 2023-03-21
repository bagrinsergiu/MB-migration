<?php

    function menuSet($arrayMenu)
    {   

        if(verificArray($arrayMenu))
        {
            foreach($arrayMenu as $key=>$value){
                
            }
        }
    }

    function verificArray($array)
    {
        if(!is_array($array)){
            $result = FALSE;
        }
        else
        {
            $result = TRUE;
        }

        return $result;
    }

    function jsonDataLoad($nameTemplate, $namePage = 'home')
    {
        global $themes;
        global $pathLayoutData;

        $jsonDataLayout = strReplace($pathLayoutData, array("{theme}","{page}"), array($themes[$nameTemplate], $namePage) );  // формируем путь от корня к data.json

        if(file_exists($jsonDataLayout))
        {
            $jsonString = file_get_contents($jsonDataLayout);

            $jsonData = json_decode($jsonString);

            return $jsonData;
        }
        else
        {
            return FALSE;
        }
    }

    function strClear($jsonStr)
    {

        $arrDel = array('\n ', '\\\\');
        $jsonClearData = str_replace($arrDel, '', $jsonStr);  

        return $jsonClearData;
    }

    function cleanJson($json) {
        $json = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json);  // удаляем непечатные и специальные символы
        $json = preg_replace('/[[:space:]]+/', ' ', $json);         // заменяем повторяющиеся пробелы на один
        $json = trim($json);                                        // удаляем пробелы в начале и конце
        
        return $json;
      }
      

    function strReplace($blok,$replace,$toteplace)
    {
        $ReplaceData = str_replace($replace, $toteplace, $blok);  

        return $ReplaceData;
    }

    /* 
     * 
     * 
     * 
     * 
     * 
     * 
     */

    function addTextInTeg($in, $from)
    {

        $ff = preg_match("/>(.*?)</",$from,$matches);

        $jsonDataE = preg_replace('|(">).*(</)|Uis', '$1'.$matches[1].'$2',$in);

        return $jsonDataE;

    }

    function createJsonData($oarr = 'all')
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

    // function log($log)
    // {

    //     $strlog = '['.date('Y-m-d H:i:s').']'.$log;
    //     file_put_contents('/opt/lampp/logs/php_error_log', $strlog, FILE_APPEND);

    // }

