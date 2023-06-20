<?php
namespace Brizy\layer\Brizy;

use Brizy\core\Config;
use Brizy\core\Utils;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;


class BrizyAPI{

    private $projectId;
    private $projectToken;
    private $nameFolder;

    function __construct()
    {
        Utils::log('Initialization', 4, 'BrizyAPI');
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

    public function getProject($workspacesID, $filtre = null)
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

    public function getProjectPrivateApi($projectID)
    {
        $param = [ 'project' => $projectID ];

        $url = $this->createPrivatUrlAPI('projects') . '/' . $projectID;

        $result = $this->httpClient('GET',  $url, $param);

        $result = json_decode($result['body'], true);

        if(!is_array($result))
        {
            return false;
        }

        return $result;

    }

    public function getGraphToken($projectid)
    {
        $nameFunction = __FUNCTION__;

        Utils::log('get Token', 1, $nameFunction);

        $result = $this->httpClient('GET',  $this->createUrlApiProject($projectid));
        if($result['status'] > 200)
        {
            Utils::log('Response: '. json_encode($result), 2, $nameFunction);
            exit('Error');
        }
        $resultDecode = json_decode($result['body'], true);

        if(!is_array($resultDecode))
        {
            Utils::log('Bad Response', 2, $nameFunction);
        }
        if(array_key_exists('code', $result)) {
            if ($resultDecode['code'] == 500) {
                Utils::log('Error getting token', 5, $nameFunction);
                exit('Error getting token');
            }
        }

        return $resultDecode['access_token'];
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

    public function setProjectToken($newToken)
    {
        $this->projectToken = $newToken;
    }

    public function createMedia($pathOrUrlToFileName, $nameFolder = ''): bool|array
    {
        if($nameFolder != ''){
            $this->nameFolder = $nameFolder;
        }
        $pathToFileName = $this->isUrlOrFile($pathOrUrlToFileName);
        $mime_type = mime_content_type($pathToFileName);
        if($this->getFileExtension($mime_type)) {
            $file_contents = file_get_contents($pathToFileName);
            if(!$file_contents){
                Utils::log('Failed get contents image!!! path: ' . $pathToFileName, 2, 'createMedia');
            }
            $base64_content = base64_encode($file_contents);

            return $this->httpClient('POST', $this->createPrivatUrlAPI('media'), [
                'filename' => $this->getFileName($pathToFileName),
                'name' => $this->getNameHash($base64_content) . '.' . $this->getFileExtension($mime_type),
                'attachment' => $base64_content
            ]);
        }
        return false;
    }

    public function createFonts($fontsName, $projectID, $pathToFonts): bool|array
    {
        return $this->httpClient('POST', $this->createPrivatUrlAPI('fonts'), [
            'family' => $fontsName,
            'uid' => $this->generateUID(),
            'files' => [$this->readBinaryFile($pathToFonts)],
            'container' => $projectID
        ]);
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

    public function getPage($projectID)
    {
        $param = [
            'page' => 1,
            'count' => 100,
            'project' => $projectID
        ];

        $result = $this->httpClient('GET', $this->createPrivatUrlAPI('pages'), $param);

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

    public function createPage($projectID, $pageName, $filter = null)
    {
        $result = $this->httpClient('POST', $this->createPrivatUrlAPI('pages'), [
            'project' => $projectID,
            'dataVersion' => '2.0',
            'data' => $pageName,
            'is_index' => false,
            'status' => 'draft',
            'type' => 'page',
            'is_autosave' => true
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

    public function createMenu($data)
    {
        Utils::log('Request to create menu', 1, 'createMenu');
        $result = $this->httpClient('POST', $this->createPrivatUrlAPI('menu'), [
            'project' => $data['project'],
            'name'    => $data['name'],
            'data'    => $data['data']
        ]);
        if($result['status'] !== 201){
            Utils::log('Failed menu', 2, 'createMenu');
            return false;
        }
        Utils::log('Created menu', 1, 'createMenu');
        return json_decode($result['body'], true);
    }

    private function createUrlApiProject($projectId)
    {
        return Utils::strReplace(Config::$urlGetApiToken, '{project}', $projectId);
    }

    private function createUrlAPI($endPoint): string
    {
        return Config::$urlAPI . Config::$endPointVersion . Config::$endPointApi[$endPoint];
    }

    private function createUrlProject($projectId, $endPoint = ''): string
    {
        $urlProjectAPI = Utils::strReplace(Config::$urlProjectAPI, '{project}', $projectId);
        return $urlProjectAPI . Config::$endPointApi[$endPoint];
    }

    private function createPrivatUrlAPI($endPoint): string
    {
        return Config::$urlAPI . Config::$endPointApi[$endPoint];
    }

    public function getNameHash($data, int $length = 32): string
    {
        $to_hash = $this->generateUniqueID() . $data;
        $newHash = hash('sha256', $to_hash);
        return substr($newHash, 0, $length);
    }
    private function generateUniqueID(): string
    {
        $microtime = microtime();
        $microtime = str_replace('.', '', $microtime);
        $microtime = substr($microtime, 0, 10);
        $random_number = rand(1000, 9999);
        return $microtime . $random_number;
    }

    private function generateUID(): string
    {
        return $this->getNameHash($this->generateUniqueID());
    }

    private function getFileExtension($mime_type) {
        $extensions = array(
            'image/x-icon'  => 'ico',
            'image/jpeg'    => 'jpg',
            'image/png'     => 'png',
            'image/gif'     => 'gif',
            'image/bmp'     => 'bmp'
        );
        return $extensions[$mime_type] ?? false;
    }

    private function getFileName($string) {
        $parts = pathinfo($string);
        if (isset($parts['extension']))
        {
            return $parts['basename'];
        }
        else
        {
            return $string;
        }
    }

    private function downloadImage($url): string
    {
        Utils::log('Loading a picture', 1, 'downloadImage');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $image_data = curl_exec($ch);
        curl_close($ch);

        $file_name = basename($url);
        $path = Config::$pathTmp . $this->nameFolder . '/media/' . $file_name;
        $status = file_put_contents($path, $image_data);
        if(!$status){
            Utils::log('Failed to load image!!! path: ' . $path, 2, 'downloadImage');
        }
        return $path;
    }

    private function readBinaryFile($filename): bool|string
    {
        $handle = fopen($filename, 'rb');
        if ($handle === false) {
            return false;
        }
        $data = fread($handle, filesize($filename));
        fclose($handle);
        return $data;
    }

    private function isUrlOrFile($urlOrPath) {
        Utils::log('Check image address', 1, 'uploadPicturesFromSections');
        if (filter_var($urlOrPath, FILTER_VALIDATE_URL)) {
            return $this->downloadImage($urlOrPath);
        }
        else
        {
            if (file_exists($urlOrPath)) {
                return $urlOrPath;
            }
            else
            {
                return "unknown";
            }
        }
    }

    public function createDirectory($directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);
        }
    }

    private function httpClient($method, $url, $data = null, $token = null ): array
    {
        $nameFunction = __FUNCTION__;

        $client = new Client();

        $token = Config::$devToken;

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

                Utils::log(json_encode(['status' => $statusCode, 'body' => $body]), 3, $nameFunction);

                return ['status' => $statusCode, 'body' => $body];
            }
            else
            {
                Utils::log(json_encode(['status' => false, 'body' => 'Request timed out.']), 3, $nameFunction);

                return ['status' => false, 'body' => 'Request timed out.'];
            }
        } catch (GuzzleException $e) {

            Utils::log(json_encode(['status' => false, 'body' => $e->getMessage()]), 3, $nameFunction);

            return ['status' => false, 'body' => $e->getMessage()];
        }
    }

}