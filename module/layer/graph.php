<?php

namespace Brizy;

use Brizy\Config;
use GraphQL\Client;
use GraphQL\Exception\QueryError;
use GraphQL\Http\Client as HttpClient;
use GraphQL\Http\HeaderAuthorization;

class layerGraphQL {


    private $url;
    private $token;

    public function __construct() {
        
    }

    public function sendRequest($query, $variables = null) {
        // создаем клиент GraphQL API
        $client = new Client($this->url, new HttpClient([
            'headers' => [
                new HeaderAuthorization('Bearer ' . $this->token)
            ]
        ]));

        try {
            // отправляем запрос и получаем результат
            $result = $client->query($query, $variables);

            // возвращаем данные из ответа
            return $result->getData();
        } catch (QueryError $e) {
            // если произошла ошибка, выводим сообщение
            echo 'Error: ' . $e->getMessage();
        }
    }

}