<?php
namespace Brizy;

use Brizy\Config;
use Brizy\Helper;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;


class BrizyAPI{

    /**
     * @var Client
     */
    private $httpClient;
    private $projectId;
    private $projectToken;

    function __construct()
    {
        $this->projectToken = Config::$devToken;
    }
    public function getWorkspaces($name = null)
    {
        $result = $this->httpClient('GET', $this->createUrlAPI('workspaces'),['page'=>1,'count'=>100]);

        if (!isset($name)){
            return  $result;
        }

        $result = json_decode($result['body'], true);

        if(!is_array($result))
        {
            return false;
        }

        foreach($result as $value)
        {
            if($value['name'] === $name)
            {
                return $value['id'];
            }
        }
        return false;
    }
    public function getProject($workspacesID)
    {
        $param = [
            'page' => 1,
            'count' => 100,
            'workspace' => $workspacesID
        ];
        return $this->httpClient('GET', $this->createUrlAPI('projects'), $param);

    }

    public function getGraphToken($projectid){

        return $this->httpClient('GET',  $this->createUrlApiProject($projectid));

    }

    public function getUserToken()
    {
        $brizyCreateClient = $this->createUser();

        $authenticateParametr = Helper::strReplace(Config::$authenticateParametr, ['{client_id}','{client_secret}'], $brizyCreateClient);

        $param = ['slug' => '/token', 'getToken' => $authenticateParametr];

        $resultquery = Helper::curlExec(Config::$urlAPI, $param);

        return json_decode($resultquery, true);
    }

    private function getApiProjectToken($projectID)
    {
        return $this->httpClient('GET', $this->createUrlApiProject($projectID) );
    }

    private function setProjectToken($newToken){
        $this->projectToken = $newToken;
    }


    public function createUser()
    {
        /**
         * this is where the user creation magic happens Brizy
         * 
         */
        return [Config::$brizyClientId, Config::$brizyClientSecret];
    }

    public function createProject()
    {
        /**
         * this is where the user creation magic happens Brizy
         *
         */
        return [Config::$brizyClientId, Config::$brizyClientSecret];
    }


    public function createdWorkspaces()
    {
        return $this->httpClient('POST', $this->createUrlAPI('projects'), ['name' => Config::$nameMigration]);
    }

    /**
     * @throws GuzzleException
     */

    private function createUrlApiProject($projectId)
    {
        return Helper::strReplace(Config::$urlGetApiToken, '{project}', $projectId);
    }

    private function createUrlAPI($endPoint): string
    {
        return Config::$urlAPI . Config::$endPointVersion . Config::$endPointApi[$endPoint];
    }

    public function httpClient($method, $url, $data = null, $token = null ): array
    {
        $client = new Client();

        $token = $token ? $this->projectToken : Config::$devToken;

        try {
            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'x-auth-user-token' => $token
            ];
            $options = [
                'headers' => $headers,
                'timeout' => 10,
                'connect_timeout' => 5
            ];
            if ($method === 'POST' && isset($data))
            {
                    $options['form_params'] = $data;
            }

            if($method === 'GET'  && isset($data))
            {
                $data = http_build_query($data);
                $url  = sprintf("%s?%s", $url, $data);
            }

            $response = $client->request($method, $url, $options);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            return ['status' => $statusCode, 'body' => $body];

        } catch (RequestException $e) {
            if ($e->hasResponse())
            {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();

                return ['status' => $statusCode, 'body' => $body];
            }
            else
            {
                return ['status' => false, 'body' => 'Request timed out.'];
            }
        } catch (GuzzleException $e) {
            return ['status' => false, 'body' => $e->getMessage()];
        }
    }

}