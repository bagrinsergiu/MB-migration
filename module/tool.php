<?php
namespace Brizy;

class Tool{

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

    function strClear($jsonStr)
    {
        $arrDel = array('\n ', '\\\\');
        $jsonClearData = str_replace($arrDel, '', $jsonStr);  

        return $jsonClearData;
    }

    function cleanJson($json)
    {
        $json = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json);
        $json = preg_replace('/[[:space:]]+/', ' ', $json);
        $json = trim($json);
        
        return $json;
    }
      

    function strReplace($blok,$replace,$toteplace)
    {
        $ReplaceData = str_replace($replace, $toteplace, $blok);  

        return $ReplaceData;
    }

    function addTextInTeg($in, $from)
    {
        $ff = preg_match("/>(.*?)</", $from, $matches);

        $jsonDataE = preg_replace('|(">).*(</)|Uis', '$1' . $matches[1] . '$2', $in);

        return $jsonDataE;
    }

    function log($mesage,$type = 1, $nameFunction = '')
    {   
        //if(Config::$debugMode == TRUE and $type == 0)
        $param = array(
                        "mesage" => $mesage, 
                        "type" => $type,
                        "callFunction" => $nameFunction
                    );

        $this->writeLogToFile($param);
        
    }

    private function writeLogToFile($param)
    {
        $typeMesageArray = array("DEBUG","INFO","WARNING","CRITICAL");

        $strlog = "[" . date('Y-m-d H:i:s') . "] [" . $typeMesageArray[$param['type']] . "]: [" . $param['callFunction'] . "] ".$param['mesage']."\n";  
        
        file_put_contents(Config::$pathLogFile, $strlog, FILE_APPEND);
    } 

}