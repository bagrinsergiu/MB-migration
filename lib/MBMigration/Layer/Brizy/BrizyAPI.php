<?php

namespace MBMigration\Layer\Brizy;

use MBMigration\Core\Logger;
use Psr\Http\Message\ResponseInterface;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;

class BrizyAPI extends Utils
{
    private $projectToken;
    private $nameFolder;
    private $containerID;
    /**
     * @var VariableCache|mixed
     */
    protected $cacheBR;
    /**
     * @var mixed|object|null
     */
    private $QueryBuilder;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        Logger::instance()->debug('BrizyAPI Initialization');
        $this->projectToken = $this->check(Config::$mainToken, 'Config not initialized');
        $this->cacheBR = VariableCache::getInstance();
    }

    /**
     * @throws Exception
     */
    public function getProjectMetadata($projectId)
    {

        $url = $this->createUrlAPI('projects').'/'.$projectId;

        $result = $this->httpClient('GET', $url);

        $result = json_decode($result['body'], true);

        if (!is_array($result)) {
            return null;
        }
        if ($result['metadata'] === null) {
            return null;
        }

        return json_decode($result['metadata'], true);

    }

    /**
     * @throws Exception
     */
    public function getWorkspaces($name = null)
    {
        $result = $this->httpClient('GET', $this->createUrlAPI('workspaces'), ['page' => 1, 'count' => 100]);

        if (!isset($name)) {
            return $result;
        }

        $result = json_decode($result['body'], true);

        if (!is_array($result)) {
            return false;
        }

        foreach ($result as $value) {
            if ($value['name'] === $name) {
                return $value['id'];
            }
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function getProject($workspacesID, $filtre = null)
    {
        $param = [
            'page' => 1,
            'count' => 100,
            'workspace' => $workspacesID,
        ];

        $result = $this->httpClient('GET', $this->createUrlAPI('projects'), $param);

        if (!isset($filtre)) {
            return $result;
        }

        $result = json_decode($result['body'], true);

        if (!is_array($result)) {
            return false;
        }

        foreach ($result as $value) {
            if ($value['name'] === $filtre) {
                return $value['id'];
            }
        }

        return false;

    }

    public function getProjectPrivateApi($projectID)
    {
        $param = ['project' => $projectID];

        $url = $this->createPrivateUrlAPI('projects').'/'.$projectID;

        $result = $this->httpClient('GET', $url, $param);

        $result = json_decode($result['body'], true);

        if (!is_array($result)) {
            return false;
        }

        return $result;

    }

    /**
     * @throws Exception
     */
    public function getGraphToken($projectid)
    {
        $nameFunction = __FUNCTION__;

        Logger::instance()->info('get Token');

        $result = $this->httpClient('GET', $this->createUrlApiProject($projectid));
        if ($result['status'] > 200) {
            Logger::instance()->warning('Response: '.json_encode($result));
            Logger::instance()->info('Response: '.json_encode($result));
            throw new Exception('Bad Response from Brizy: '.json_encode($result));
        }
        $resultDecode = json_decode($result['body'], true);

        if (!is_array($resultDecode)) {
            Logger::instance()->warning('Bad Response');
            Logger::instance()->info('Bad Response from Brizy'.json_encode($result));
            throw new Exception('Bad Response from Brizy: '.json_encode($result));
        }
        if (array_key_exists('code', $result)) {
            if ($resultDecode['code'] == 500) {
                Logger::instance()->error('Error getting token');
                Logger::instance()->info('Getting token'.json_encode($result));
                throw new Exception('getting token');
            }
        }

        return $resultDecode['access_token'];
    }

    /**
     * @throws Exception
     */
    public function getUserToken($userId)
    {
        $result = $this->httpClient('GET', $this->createUrlAPI('users'), ['id' => $userId]);

        $result = json_decode($result['body'], true);

        if (!is_array($result)) {
            return false;
        }

        return $result['token'];
    }

    public function setProjectToken($newToken)
    {
        $this->projectToken = $newToken;
    }

    /**
     * @throws Exception
     */
    public function createMedia($pathOrUrlToFileName, $nameFolder = '')
    {
        if ($nameFolder != '') {
            $this->nameFolder = $nameFolder;
        }
        $pathToFileName = $this->isUrlOrFile($pathOrUrlToFileName);
        $mime_type = mime_content_type($pathToFileName);
        Logger::instance()->debug('Mime type image; '.$mime_type);
        if ($this->getFileExtension($mime_type)) {
            $file_contents = file_get_contents($pathToFileName);
            if (!$file_contents) {
                Logger::instance()->warning('Failed get contents image!!! path: '.$pathToFileName);
            }
            $base64_content = base64_encode($file_contents);

            return $this->httpClient('POST', $this->createPrivateUrlAPI('media'), [
                'filename' => $this->getFileName($pathToFileName),
                'name' => $this->getNameHash($base64_content).'.'.$this->getFileExtension($mime_type),
                'attachment' => $base64_content,
            ]);
        }

        return false;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function createGlobalBlock($data, $position, $rules)
    {
        Logger::instance()->debug('Create Global Block',[$position,$rules]);

        $requestData['project'] = Utils::$cache->get('projectId_Brizy');
        $requestData['status'] = 'publish';
        $requestData['position'] = $position;
        $requestData['rules'] = $rules;
        $requestData['dataVersion'] = 0;
        $requestData['data'] = $data;
        $requestData['is_autosave'] = 0;
        $requestData['uid'] = self::generateCharID(12);

        $url = $this->createPrivateUrlAPI('globalBlocks');

        $result = $this->httpClient('POST', $url, $requestData);


        return false;
    }

    public function deleteAllGlobalBlocks()
    {
        $url = $this->createPrivateUrlAPI('globalBlocks');
        $requestData['project'] = Utils::$cache->get('projectId_Brizy');
        $requestData['fields'] = ['id','uid'];
        $response = $this->httpClient('GET', $url, $requestData);
        if($response['status']==200) {
            $globalBlocks = json_decode($response['body'], true);
            foreach($globalBlocks as $block) {
                Logger::instance()->debug("Delete global block {$block['id']}");
                $response = $this->httpClient('DELETE', $url."/".$block['id']);
            }
        }
    }

    public function fopenFromURL($url)
    {
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
            ),
        ));

        $fileHandle = fopen($url, 'r', false, $context);

        if (!$fileHandle) {
            return false;
        }

        return $fileHandle;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function createFonts($fontsName, $projectID, array $KitFonts, $displayName)
    {
        $fonts = [];
        foreach ($KitFonts as $fontWeight => $pathToFonts) {
            Logger::instance()->info("Request to Upload font name: $fontsName, font weight: $fontWeight");
            foreach ($pathToFonts as $pathToFont) {
                $fileExtension = $this->getExtensionFromFileString($pathToFont);
                if (Config::$urlJsonKits && Config::$devMode === false) {
                    $pathToFont = Config::$urlJsonKits.'/fonts/'.$pathToFont;
                    $fonts[] = [
                        'name' => "files[$fontWeight][$fileExtension]",
                        'contents' => $this->fopenFromURL($pathToFont),
                    ];
                } else {
                    $pathToFont = __DIR__.'/../../Builder/Fonts/'.$pathToFont;
                    $fonts[] = [
                        'name' => "files[$fontWeight][$fileExtension]",
                        'contents' => fopen($pathToFont, 'r'),
                    ];
                }
            }
        }

        $options['multipart'] = array_merge_recursive($fonts, [
            [
                'name' => 'family',
                'contents' => $displayName,
            ],
            [
                'name' => 'uid',
                'contents' => self::generateCharID(36),
            ],
            [
                'name' => 'container',
                'contents' => $projectID,
            ],
        ]);

        $res = $this->request('POST', $this->createPrivateUrlAPI('fonts'), $options);

        return json_decode($res->getBody()->getContents(), true);
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function addFontAndUpdateProject(array $data): string
    {
        Logger::instance()->info('Add font '.$data['family'].' in project and update project');
        $containerID = Utils::$cache->get('projectId_Brizy');

        $projectFullData = $this->getProjectContainer($containerID, true);

        $projectData = json_decode($projectFullData['data'], true);

        $newData['family'] = $data['family'];
        $newData['files'] = $data['files'];
        $newData['weights'] = $data['weights'];
        $newData['type'] = $data['type'];
        $newData['id'] = $data['uid'];
        $newData['brizyId'] = self::generateCharID(36);

        $projectData['fonts']['upload']['data'][] = $newData;
        $url = $this->createPrivateUrlAPI('projects').'/'.$containerID;

        $r_projectFullData['data'] = json_encode($projectData);
        $r_projectFullData['is_autosave'] = 0;
        $r_projectFullData['dataVersion'] = $projectFullData["dataVersion"] + 1;

        $this->request('PUT', $url, ['form_params' => $r_projectFullData]);

        return $data['uid'];
    }

    public function updateProject(array $projectFullData): array
    {
        $containerID = Utils::$cache->get('projectId_Brizy');
        $url = $this->createPrivateUrlAPI('projects').'/'.$containerID;

        $r_projectFullData['is_autosave'] = 0;
        $r_projectFullData['dataVersion'] = $projectFullData["dataVersion"] + 1;
        $r_projectFullData['data'] = $projectFullData['data'];

        $result = $this->request('PUT', $url, ['form_params' => $r_projectFullData]);

        return json_decode($result->getBody(), true);
    }


    /**
     * @throws GuzzleException
     */
    public function setMetaDate()
    {
        Logger::instance()->info('Check metaDate settings');
        if (Config::$metaData) {

            Logger::instance()->info('Create links between projects');

            $projectId_MB = Utils::$cache->get('projectId_MB');
            $projectId_Brizy = Utils::$cache->get('projectId_Brizy');

            $metadata['site_id'] = $projectId_MB;
            $metadata['secret'] = Config::$metaData['secret'];
            $metadata['MBAccountID'] = Config::$metaData['MBAccountID'];
            $metadata['MBVisitorID'] = Config::$metaData['MBVisitorID'];
            $metadata['MBThemeName'] = Utils::$cache->get('design', 'settings');

            $url = $this->createUrlAPI('projects').'/'.$projectId_Brizy;

            $this->request('PATCH', $url, ['form_params' => ['metadata' => json_encode($metadata)]]);
        }
    }

    private function getExtensionFromFileString($fileString)
    {
        $parts = explode('/', $fileString);
        $filename = end($parts);

        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * @throws Exception
     */
    public function getProjectContainer(int $containerID, $fullDataProject = false)
    {
        $url = $this->createPrivateUrlAPI('projects');
        $result = $this->httpClient('GET_P', $url, $containerID);
        if (!$fullDataProject) {
            if ($result['status'] === 200) {
                $response = json_decode($result['body'], true);

                return $response['container'];
            }
        }

        return json_decode($result['body'], true);
    }

    /**
     * @throws Exception
     */
    public function createUser(array $value)
    {
        $result = $this->httpClient('POST', $this->createUrlAPI('users'), $value);

        $result = json_decode($result['body'], true);

        if (!is_array($result)) {
            return false;
        }

        return $result['token'];

    }

    /**
     * @throws Exception
     */
    public function createProject($projectName, $workspacesId, $filter = null)
    {
        $result = $this->httpClient('POST', $this->createUrlAPI('projects'), [
            'name' => $projectName,
            'workspace' => $workspacesId,
        ]);

        if (!isset($filter)) {
            return $result;
        }

        $result = json_decode($result['body'], true);

        if (!is_array($result)) {
            return false;
        }

        return $result[$filter];
    }


    /**
     * @throws Exception
     */
    public function createdWorkspaces(): array
    {
        return $this->httpClient('POST', $this->createUrlAPI('projects'), ['name' => Config::$nameMigration]);
    }

    /**
     * @throws Exception
     */
    public function getPage($projectID)
    {
        $param = [
            'page' => 1,
            'count' => 100,
            'project' => $projectID,
        ];

        $result = $this->httpClient('GET', $this->createPrivateUrlAPI('pages'), $param);

        if (!isset($filtre)) {
            return $result;
        }

        $result = json_decode($result['body'], true);

        if (!is_array($result)) {
            return false;
        }

        foreach ($result as $value) {
            if ($value['name'] === $filtre) {
                return $value['id'];
            }
        }

        return false;

    }

    /**
     * @throws Exception
     */
    public function createPage($projectID, $pageName, $filter = null)
    {
        $result = $this->httpClient('POST', $this->createPrivateUrlAPI('pages'), [
            'project' => $projectID,
            'dataVersion' => '2.0',
            'data' => $pageName,
            'is_index' => false,
            'status' => 'draft',
            'type' => 'page',
            'is_autosave' => true,
        ]);

        if (!isset($filter)) {
            return $result;
        }

        $result = json_decode($result['body'], true);

        if (!is_array($result)) {
            return false;
        }

        return $result[$filter];
    }

    public function getAllProjectPages(): array
    {
        Logger::instance()->info('Get All Pages from projects');
        static $result;

        if (!empty($result)) {
            return $result;
        }

        $this->QueryBuilder = $this->cacheBR->getClass('QueryBuilder');

        $collectionTypes = $this->QueryBuilder->getCollectionTypes();

        $foundCollectionTypes = [];
        $entities = [];

        foreach ($collectionTypes as $collectionType) {
            if ($collectionType['slug'] == 'page') {
                $foundCollectionTypes[$collectionType['slug']] = $collectionType['id'];
                $result['mainCollectionType'] = $collectionType['id'];
            }
        }

        $collectionItems = $this->QueryBuilder->getCollectionItems($foundCollectionTypes);

        foreach ($collectionItems['page']['collection'] as $entity) {
            $entities[$entity['slug']] = $entity['id'];
        }
        $result['listPages'] = $entities;

        return $result;
    }

    /**
     * @throws Exception
     */
    public function createMenu($data)
    {
        Logger::instance()->info('Request to create menu');
        $result = $this->httpClient('POST', $this->createPrivateUrlAPI('menu'), [
            'project' => $data['project'],
            'name' => $data['name'],
            'data' => $data['data'],
        ]);
        if ($result['status'] !== 201) {
            Logger::instance()->warning('Failed menu');

            return false;
        }
        Logger::instance()->info('Created menu');

        return json_decode($result['body'], true);
    }

    private function createUrlApiProject($projectId): string
    {
        return Utils::strReplace(Config::$urlGetApiToken, '{project}', $projectId);
    }

    private function createUrlAPI($endPoint): string
    {
        return Config::$urlAPI.Config::$endPointVersion.Config::$endPointApi[$endPoint];
    }

    private function createUrlProject($projectId, $endPoint = ''): string
    {
        $urlProjectAPI = Utils::strReplace(Config::$urlProjectAPI, '{project}', $projectId);

        return $urlProjectAPI.Config::$endPointApi[$endPoint];
    }

    private function createPrivateUrlAPI($endPoint): string
    {
        return Config::$urlAPI.Config::$endPointApi[$endPoint];
    }


    private function generateUniqueID(): string
    {
        $microtime = microtime();
        $microtime = str_replace('.', '', $microtime);
        $microtime = substr($microtime, 0, 10);
        $random_number = rand(1000, 9999);

        return $microtime.$random_number;
    }

    private function generateUID(): string
    {
        return $this->getNameHash($this->generateUniqueID());
    }

    private function getFileExtension($mime_type)
    {
        $extensions = array(
            'image/x-icon' => 'ico',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
        );

        return $extensions[$mime_type] ?? false;
    }

    private function getFileName($string)
    {
        $parts = pathinfo($string);
        if (isset($parts['extension'])) {
            return $parts['basename'];
        } else {
            return $string;
        }
    }

    private function downloadImage($url): string
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $image_data = curl_exec($ch);
        curl_close($ch);

        $file_name = mb_strtolower(basename($url));
        $fileName = explode(".", $file_name);
        $file_name = $fileName[0].'.'.$this->fileExtension($fileName[1]);

        $path = Config::$pathTmp.$this->nameFolder.'/media/'.$file_name;
        $status = file_put_contents($path, $image_data);
        if (!$status) {
            Logger::instance()->warning('Failed to load image!!! path: '.$path);
        }

        return $path;
    }

    private function fileExtension($expansion): string
    {
        $expansionMap = [
            "jpeg" => "jpg",
        ];

        if (array_key_exists($expansion, $expansionMap)) {
            return $expansionMap[$expansion];
        }

        return $expansion;
    }

    private function readBinaryFile($filename)
    {
        $handle = fopen($filename, 'rb');
        if ($handle === false) {
            return false;
        }
        $data = fread($handle, filesize($filename));
        fclose($handle);

        return $data;
    }

    private function isUrlOrFile($urlOrPath): string
    {
        Logger::instance()->info('Check image address');
        if (filter_var($urlOrPath, FILTER_VALIDATE_URL)) {
            return $this->downloadImage($urlOrPath);
        } else {
            if (file_exists($urlOrPath)) {
                return $urlOrPath;
            } else {
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

    /**
     * @throws GuzzleException
     */
    private function request(
        string $method,
        $uri = '',
        array $options = [],
        $contentType = false
    ): ResponseInterface {
        $client = new Client();
        $headers = [
            'x-auth-user-token' => Config::$mainToken,
        ];

        if ($method === 'PUT') {
            $headers['X-HTTP-Method-Override'] = 'PUT';
        }

        if ($method === 'POST') {
            $headers['X-HTTP-Method-Override'] = 'POST';
        }

        if ($method === 'PATCH') {
            $headers['X-HTTP-Method-Override'] = 'PATCH';
        }

        if ($contentType) {
            $headers['Content-Type'] = $contentType;
        }

        $defaultOptions = [
            'headers' => $headers,
            'timeout' => 60,
            'connect_timeout' => 60,
        ];
        $options = array_merge_recursive($defaultOptions, $options);

        return $client->request($method, $uri, $options);
    }

    /**
     * @throws Exception
     */
    private function httpClient($method, $url, $data = null, $contentType = 'application/x-www-form-urlencoded'): array
    {
        $nameFunction = __FUNCTION__;

        $statusCode = '';
        $body = '';

        $client = new Client();

        $token = Config::$mainToken;
        try {

            if ($contentType !== '') {
                $headers['Content-Type'] = $contentType;
            } else {
                $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            }
            $headers['x-auth-user-token'] = $token;

            $options = [
                'headers' => $headers,
                'timeout' => 60,
                'connect_timeout' => 50,
            ];

            if ($method === 'POST' && isset($data)) {
                $options['form_params'] = $data;
            }

            if ($method === 'GET' && isset($data)) {
                $data = http_build_query($data);
                $url = sprintf("%s?%s", $url, $data);
            }

            if ($method === 'GET_P' && isset($data)) {
                $method = 'GET';
                $url = $url.'/'.$data;
            }

            if ($method === 'PUT' && isset($data)) {
                $options['json'] = $data;
            }

            $response = $client->request($method, $url, $options);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            return ['status' => $statusCode, 'body' => $body];

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();

                Logger::instance()->critical(json_encode(['status' => $statusCode, 'body' => $body]));
                if ($statusCode > 200) {
                    Logger::instance()->info("Error: RequestException Message:".json_encode(['status' => $statusCode, 'body' => $body]));
                }
                Logger::instance()->info("Error: RequestException Message:".json_encode(['status' => $statusCode, 'body' => $body]));

                return ['status' => $statusCode, 'body' => $body];
            } else {
                Logger::instance()->info("Error: GuzzleException Message:".json_encode(['status' => false, 'body' => 'Request timed out.']));
                Logger::instance()->critical(json_encode(['status' => false, 'body' => 'Request timed out.']));

                return ['status' => false, 'body' => 'Request timed out.'];
            }
        } catch (GuzzleException $e) {
            Logger::instance()->info("Error: GuzzleException Message:".json_encode($e->getMessage()));
            Logger::instance()->info("Error: GuzzleException Message: code".$statusCode."Response: ".$body);
            Logger::instance()->critical(json_encode(['status' => false, 'body' => $e->getMessage()]));

            return ['status' => false, 'body' => $e->getMessage()];
        }
    }

}