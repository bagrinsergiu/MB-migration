<?php
namespace Brizy;

class Helper{

    public static function resourcesInitialization ($directory_path)
    {
        $files = glob("$directory_path/*");

        foreach ($files as $file) {
            if (is_file($file)) {

                require_once $file;

            } elseif (is_dir($file)) {
                self::resourcesInitialization($file);
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
      

    public static function strReplace($blok,$replace,$toteplace)
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

    public static function curlExec($url, array $value = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url.$value['slug']);
        curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        if(array_key_exists('getToken', $value))
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $value['getToken']);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        }

        if(array_key_exists('postParam', $value))
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $value['post_param']);
            var_dump($value);
        }
        
        $response = curl_exec($ch);

        if (curl_errno($ch)) {

            print "Error: " . curl_error($ch);

        }
        curl_close($ch);
    
        return $response;
    
    }


    public static function log($mesage,$type = 1, $nameFunction = '')
    {   
        //if(Config::$debugMode == TRUE and $type == 0)
        $param = array(
                        "mesage" => $mesage, 
                        "type" => $type,
                        "callFunction" => $nameFunction
                    );

        self::writeLogToFile($param);
        
    }

    private static function writeLogToFile($param)
    {
        $typeMesageArray = array("DEBUG","INFO","WARNING","CRITICAL");

        $strlog = "[" . date('Y-m-d H:i:s') . "] [" . $typeMesageArray[$param['type']] . "]: [" . $param['callFunction'] . "] ".$param['mesage']."\n";  
        
        file_put_contents(Config::$pathLogFile, $strlog, FILE_APPEND);
    } 

}