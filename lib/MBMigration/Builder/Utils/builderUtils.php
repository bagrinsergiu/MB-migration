<?php
namespace MBMigration\Builder\Utils;


use MBMigration\Core\Logger;
use Exception;

class builderUtils
{


    /**
     * @throws Exception
     */
    protected function loadJsonFromUrl($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($response === false) {
            Logger::instance()->critical('Download error: ' . json_encode($error));
            throw new Exception('Download error: ' . json_encode($error) . 'url:' . $url);
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Logger::instance()->critical('JSON decoding error: ' . json_encode(json_last_error_msg()));
            throw new Exception('JSON decoding error: ' . json_encode(json_last_error_msg()) . 'url:' . $url);
        }

        Logger::instance()->info('JSON success download from: ' . json_encode($url));
        return $data;
    }



}