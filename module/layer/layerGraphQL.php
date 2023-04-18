<?php

namespace Brizy;

use Brizy\Config;
use GraphQL\Client;
use GraphQL\Exception\QueryError;
use GraphQL\Http\Client as HttpClient;
use GraphQL\Http\HeaderAuthorization;

class layerGraphQL {
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $token;
    /**
     * @var int
     */
    private $projectID;


    /**
     * @param int $projectId
     * @param string $token
     * @return void
     */
    public function init($projectId, $token) {

        $this->projectID = $projectId;
        $this->token = $token;
        $this->url = Config::$urlGraphqlAPI;

    }
    public function setProjectID($id)
    {
        $this->projectID = $id;
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