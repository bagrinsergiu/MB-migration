<?php
namespace MBMigration\Builder\Utils;


use Exception;
use MBMigration\Core\Utils;

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
            Utils::log('Download error: ' . json_encode($error), 3, $this->layoutName . "] [loadJsonFromUrl");
            throw new Exception('Download error: ' . json_encode($error) . 'url:' . $url);
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Utils::log('JSON decoding error: ' . json_encode(json_last_error_msg()), 3, $this->layoutName . "] [loadJsonFromUrl");
            throw new Exception('JSON decoding error: ' . json_encode(json_last_error_msg()) . 'url:' . $url);
        }

        Utils::log('JSON success download from: ' . json_encode($url), 1, $this->layoutName . "] [loadJsonFromUrl");
        return $data;
    }



}