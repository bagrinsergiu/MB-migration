<?php
namespace Brizy\core;

use Brizy\builder\VariableCache;

class Utils{

    /**
     * @var mixed|null
     */
    private static $projectID;
    /**
     * @var VariableCache|null
     */
    private static $cache;


    public function __construct(VariableCache $cache = null)
    {
        self::$projectID = $cache->get('projectId_Brizy');
        var_dump(self::$projectID);
    }

    public static function resourcesInitialization ($directory_path)
    {
        $files = glob("$directory_path/*");

        foreach ($files as $file) {
            if (is_file($file)) {
                $file_info = pathinfo($file);

                if ($file_info['extension'] === 'php' && filetype($file) === 'file') {
                    require_once $file;
                }

            } elseif (is_dir($file)) {
                self::resourcesInitialization($file);
            }
        }
    }

    public static function init(VariableCache $cache = null){
        self::$cache = $cache;
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

    public static function strReplace($blok, $replace, $toteplace)
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

    public static function curlExec($url, $params, $method = 'GET')
    {

        $ch = curl_init();

        if ($method == 'GET') {
            $url = $url . '?' . http_build_query($params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        // Общие настройки
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Выполнение запроса
        $response = curl_exec($ch);

        if (curl_errno($ch)) {

           print "Error: " . curl_error($ch);
        }

        curl_close($ch);

        return $response;


//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url.$value['slug']);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        //curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//        if(array_key_exists('getToken', $value))
//        {
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $value['getToken']);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
//        }
//
//        if(array_key_exists('postParam', $value))
//        {
//            curl_setopt($ch, CURLOPT_POST, true);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $value['post_param']);
//            var_dump($value);
//        }
//
//        $response = curl_exec($ch);
//
//        if (curl_errno($ch)) {
//
//            print "Error: " . curl_error($ch);
//
//        }
//        curl_close($ch);
//
//        return $response;

    }

    public static function debug($value): void
    {
        Utils::log('', 0, "Print data");
        if(is_array($value)){
            var_dump($value);
        }
        print_r($value."\n");
    }

    public static function log($messageText, $type = 1, $nameFunction = ''): void
    {
        $param = [
            "message" => $messageText,
            "type" => $type,
            "callFunction" => $nameFunction
        ];

        if(isset(self::$projectID))
        {
            $param['project_id'] = self::$projectID;
        }

        if(Config::$debugMode)
        {
            self::writeLogToFile($param);
        }

        if(Config::$debugMode == false && $type > 1)
        {
            self::writeLogToFile($param);
        }

    }

    private static function writeLogToFile(array $param): void
    {
        $typeMessageArray = array("DEBUG","INFO","WARNING","CRITICAL","PROCESS","ERROR", "SUCCESSFULLY");
        $project_id = '';
        $line = '';

        if(isset($param['project_id']))
        {
            $project_id = '['.$param['project_id'].']';
        }

        if(is_array($param['message']))
        {
            $message = json_encode($param['message']);
        } else {
            $message = $param['message'];
        }
        if($param['type']== 0 or $param['type']== 2 or $param['type'] == 3 or $param['type'] == 5)
        {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
            $caller = $backtrace[1];
            $line = ' ('.basename($caller['file']) .':'. $caller['line'].') ';
        }
        $strlog = "[" . date('Y-m-d H:i:s') . "] " . $project_id . "[" . $typeMessageArray[$param['type']] . "]" . $line . ": [" . $param['callFunction'] . "] " . $message . "\n";

        $prefix = date("Y-m-d");

        $dirToLog = Utils::strReplace( Config::$pathLogFile, '{{PREFIX}}', $prefix);

        file_put_contents($dirToLog, $strlog, FILE_APPEND);
    }
}