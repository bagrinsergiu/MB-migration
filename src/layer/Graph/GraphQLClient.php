<?php

use Brizy\core\Config;
use Brizy\core\Helper;
use Brizy\layer\Graph\GQLT;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;


class GraphQLClient {
    private $endpoint;
    private $token;
    /**
     * @var GuzzleClient
     */
    private $client;

    public function __construct($endpoint, $token) {
        $this->client = new GuzzleClient();
        $this->endpoint = $endpoint;
        $this->token = $token;
    }

    public function send($query, $variables = null ) {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $data = [
            'query' => $query,
        ];

        if ($variables) {
            $data['variables'] = $variables;
        }

        $response = $this->client->post($this->endpoint, [
            RequestOptions::HEADERS => $headers,
            RequestOptions::JSON => $data,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}











class D_GraphQLClient {
    private $client;
    /**
     * @var GQLT
     */
    private $GT;

    public function init($projectId, $token = '') {

        $this->GT = new GQLT();
        $headers = ['Content-Type' => 'application/json'];
        if (!empty($token)) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        $apiURL = Helper::strReplace(Config::$urlGraphqlAPI, '{ProjectId}', $projectId);

        $this->client = new Client(
            $apiURL,
            [],
            [
                'headers' => $headers,
                'connect_timeout' => 3,
                'timeout' => 20
            ]
        );
    }

    public function createCollectionItem($collection_type_id, $slug, $title, $status, $pageData = null)
    {

        $query = '"query": "mutation CreateCollectionItem($input: createCollectionItemInput!) {\n  createCollectionItem(input: $input) {\n    collectionItem {\n      ...CollectionItemFragment\n      __typename\n    }\n    __typename\n  }\n}\n\nfragment CollectionItemFragment on CollectionItem {\n  id\n  title\n  slug\n  seo {\n    title\n    description\n    enableIndexing\n    __typename\n  }\n  social {\n    image\n    __typename\n  }\n  status\n  visibility\n  itemPassword\n  pageData\n  createdAt\n  type {\n    id\n    slug\n    title\n    settings {\n      titleSingular\n      titlePlural\n      icon\n      __typename\n    }\n    ...CollectionTypeFieldsFragment\n    __typename\n  }\n  ...CollectionItemFieldsFragment\n  __typename\n}\n\nfragment CollectionTypeFieldsFragment on CollectionType {\n  fields {\n    id\n    slug\n    label\n    type\n    hidden\n    priority\n    required\n    description\n    placement\n    ... on CollectionTypeFieldCheck {\n      checkSettings {\n        choices {\n          title\n          value\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionTypeFieldColor {\n      settings\n      __typename\n    }\n    ... on CollectionTypeFieldDateTime {\n      settings\n      __typename\n    }\n    ... on CollectionTypeFieldEmail {\n      emailSettings {\n        placeholder\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionTypeFieldFile {\n      settings\n      __typename\n    }\n    ... on CollectionTypeFieldGallery {\n      settings\n      __typename\n    }\n    ... on CollectionTypeFieldImage {\n      settings\n      __typename\n    }\n    ... on CollectionTypeFieldMap {\n      settings\n      __typename\n    }\n    ... on CollectionTypeFieldMultiReference {\n      multiReferenceSettings {\n        collectionType {\n          id\n          title\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionTypeFieldNumber {\n      numberSettings {\n        min\n        max\n        step\n        placeholder\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionTypeFieldMultiReference {\n      multiReferenceSettings {\n        collectionType {\n          id\n          title\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionTypeFieldReference {\n      referenceSettings {\n        collectionType {\n          id\n          title\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionTypeFieldRichText {\n      richTextSettings {\n        minLength\n        maxLength\n        placeholder\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionTypeFieldSelect {\n      selectSettings {\n        choices {\n          title\n          value\n          __typename\n        }\n        placeholder\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionTypeFieldSwitch {\n      settings\n      __typename\n    }\n    ... on CollectionTypeFieldLink {\n      linkSettings {\n        placeholder\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionTypeFieldText {\n      textSettings {\n        minLength\n        maxLength\n        placeholder\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n  __typename\n}\n\nfragment CollectionItemFieldsFragment on CollectionItem {\n  fields {\n    id\n    type {\n      id\n      slug\n      type\n      __typename\n    }\n    ... on CollectionItemFieldCheck {\n      checkValues {\n        value\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionItemFieldEmail {\n      emailValues {\n        value\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionItemFieldImage {\n      imageValues {\n        id\n        focusPoint {\n          x\n          y\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionItemFieldMultiReference {\n      multiReferenceValues {\n        collectionItems {\n          id\n          title\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionItemFieldNumber {\n      numberValues {\n        value\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionItemFieldReference {\n      referenceValues {\n        collectionItem {\n          id\n          title\n          __typename\n        }\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionItemFieldRichText {\n      richTextValues {\n        value\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionItemFieldSelect {\n      selectValues {\n        value\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionItemFieldSwitch {\n      switchValues {\n        value\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionItemFieldLink {\n      linkValues {\n        value\n        __typename\n      }\n      __typename\n    }\n    ... on CollectionItemFieldText {\n      textValues {\n        value\n        __typename\n      }\n      __typename\n    }\n    __typename\n  }\n  __typename\n}\n"';



        $variables = [
            'input' => [
            'type' => $collection_type_id,
            'title' => $title,
            'slug' => $slug,
            'status' => $status,
            'fields' =>[],
            'visibility' => 'public'
        ]
        ];

        if ($pageData) {
            $variables['input']['pageData'] = $pageData;
        }

        //var_dump($variables);
        echo json_encode($variables);

        return $this->runQuery($this->GT->createPage_ql(), false, $variables);

    }


    private function runQuery($query, $resultsAsArray = false, $variables = [])
    {
        try {
            return $this->client->runQuery($query,$resultsAsArray, $variables);
        } catch (Exception $e) {
           throw new Exception($e->getMessage());
        }
    }

    function jsonClear($jsonStr)
    {
        $arrDel = array('\n');
        $jsonClearData = str_replace($arrDel, '', $jsonStr);

        return $jsonClearData;
    }

    public function executeQuery($query, $variables = []) {
        $data = [
            'query' => $query,
            "variables" => $variables
        ];

        echo $this->jsonClear(json_encode($data));
        try {
            var_dump(json_encode($data));
            $response = $this->client->post('/', [
                json_encode($data)
            ]);

            $body = (string) $response->getBody();
            return $body;
        } catch (GuzzleException $e) {
            return ['errors' => [$e->getMessage()]];
        }
    }



}


class B_layerGraphQL {
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
        $this->url =$this->graphQeryUrlProject($projectId);

    }
    public function setProjectID($id)
    {
        $this->projectID = $id;
    }
    public function sendRequest($query, $variables = null) {

        $client = new Client($this->url, new HttpClient([
            'headers' => [
                new HeaderAuthorization('Bearer ' . $this->token)
            ]
        ]));

        try {

            $result = $client->query($query, $variables);


            return $result->getData();
        } catch (QueryError $e) {

            return 'Error: ' . $e->getMessage();
        }
    }
    private function graphQeryUrlProject($projectId)
    {
        return Helper::strReplace(Config::$urlGraphqlAPI, '{ProjectId}', $projectId);
    }

}