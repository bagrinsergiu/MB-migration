<?php
namespace Layer;

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

    public function getProject($workspacesID, $filtre)
    {
        $param = [
            'page' => 1,
            'count' => 100,
            'workspace' => $workspacesID
        ];

        $result = $this->httpClient('GET', $this->createUrlAPI('projects'), $param);

        if (!isset($filtre)){
            return  $result;
        }

        $result = json_decode($result['body'], true);

        if(!is_array($result))
        {
            return false;
        }

        foreach($result as $value)
        {
            if($value['name'] === $filtre)
            {
                return $value['id'];
            }
        }
        return false;

    }

    public function getGraphToken($projectid){
        $result = $this->httpClient('GET',  $this->createUrlApiProject($projectid));

        $result = json_decode($result['body'], true);

        if(!is_array($result))
        {
            return false;
        }

        return $result['access_token'];

    }

    public function getUserToken($userId)
    {
        $result = $this->httpClient('GET', $this->createUrlAPI('users'), ['id'=>$userId]);

        $result = json_decode($result['body'], true);

        if(!is_array($result))
        {
            return false;
        }

        return $result['token'];
    }

    private function setProjectToken($newToken)
    {
        $this->projectToken = $newToken;
    }

    public function createUser(array $value)
    {
        $result = $this->httpClient('POST', $this->createUrlAPI('users'), $value);

        $result = json_decode($result['body'], true);

        if(!is_array($result))
        {
            return false;
        }

        return $result['token'];

    }

    public function createProject($projectName,$workspacesId, $filter = null)
    {
        $result = $this->httpClient('POST', $this->createUrlAPI('projects'), [
            'name' => $projectName,
            'workspace' => $workspacesId
        ]);

        if (!isset($filter)){
            return  $result;
        }

        $result = json_decode($result['body'], true);

        if(!is_array($result))
        {
            return false;
        }

        return $result[$filter];

    }

    public function createdWorkspaces()
    {
        return $this->httpClient('POST', $this->createUrlAPI('projects'), ['name' => Config::$nameMigration]);
    }

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