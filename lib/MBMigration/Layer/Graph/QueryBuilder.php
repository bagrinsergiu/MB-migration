<?php

namespace MBMigration\Layer\Graph;

use GraphQL\Results;
use MBMigration\Core\Logger;
use Exception;
use MBMigration\Builder\VariableCache;
use GraphQL\Client;
use GraphQL\InlineFragment;
use GraphQL\Mutation;
use GraphQL\Query;
use GraphQL\Variable;

class QueryBuilder
{

    private $client;
    private $brizy_cms_api_url;
    private $session;
    private $cache;

    public function __construct($endpoint, $token)
    {
        $this->cache = VariableCache::getInstance();
        $this->session = $token;
        $this->brizy_cms_api_url = $endpoint;
        $this->setProject();
    }

    public function setProject(): void
    {
        $token = $this->session;
        if (!$token) {
            Logger::instance()->info('Token was not found');
        }

        $this->client = $this->getClient([
            'User-Agent' => 'Brizy Cloud',
            'Authorization' => 'Bearer '.$token,
        ]);
    }

    private function getClient(array $headers = []): Client
    {
        return new Client(
            $this->brizy_cms_api_url,
            [],
            [
                'connect_timeout' => 5,
                'timeout' => 2000,
                'headers' => $headers,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function createEditor($title, $url, $hidden = false)
    {
        if (!$this->client) {
            throw new Exception('Client was not init.');
        }

        $mutation = (new Mutation('createCollectionEditor'))
            ->setOperationName('editorCreate')
            ->setVariables([new Variable('input', 'createCollectionEditorInput', true)])
            ->setArguments(['input' => '$input'])
            ->setSelectionSet(
                [
                    (new Query('collectionEditor'))->setSelectionSet(
                        [
                            'id',
                            'title',
                            'url',
                        ]
                    ),
                ]
            );

        $variables = ['input' => ['title' => $title, 'url' => $url, 'hidden' => $hidden]];

        $results = $this->runQuery($mutation, true, $variables);

        return $results->getData()['createCollectionEditor']['collectionEditor'];
    }

    public function createCollectionType(
        $editor_id,
        $title,
        $slug,
        array $fields,
        array $settings,
        $priority,
        $public = true,
        $showUI = true,
        $showInMenu = true
    ) {
        if (!$this->client) {
            throw new Exception('Client was not init.');
        }

        $mutation = (new Mutation('createCollectionType'))
            ->setOperationName('collectionTypeCreate')
            ->setVariables([new Variable('input', 'createCollectionTypeInput', true)])
            ->setArguments(['input' => '$input'])
            ->setSelectionSet(
                [
                    (new Query('collectionType'))->setSelectionSet(
                        [
                            'id',
                            'title',
                            'slug',
                            'priority',
                            'public',
                            'showUI',
                            'showInMenu',
                            (new Query('fields'))
                                ->setSelectionSet(
                                    [
                                        'id',
                                        'slug',
                                        'settings',
                                        'label',
                                        'type',
                                        'priority',
                                        'required',
                                    ]
                                ),
                            (new Query('editor'))
                                ->setSelectionSet(
                                    [
                                        'id',
                                        'title',
                                        'url',
                                    ]
                                ),
                        ]
                    ),
                ]
            );

        $variables = [
            'input' => [
                'editor' => $editor_id,
                'title' => $title,
                'slug' => $slug,
                'fields' => $fields,
                'settings' => $settings,
                'priority' => $priority,
                'public' => $public,
                'showUI' => $showUI,
                'showInMenu' => $showInMenu,
            ],
        ];

        $results = $this->runQuery($mutation, true, $variables);

        return $results->getData()['createCollectionType']['collectionType'];
    }

    public function getCollectionTypes($withFieldsSet = true)
    {
        if (!$this->client) {
            Logger::instance()->warning('Client was not init.');
        }

        static $result;

        if (!empty($result)) {
            return $result;
        }

        $query = (new Query('collectionTypes'))
            ->setOperationName('collectionTypes')
            ->setSelectionSet(
                [
                    'id',
                    'title',
                    'slug',
                    'priority',
                    'public',
                    'showUI',
                    'createdAt',
                    'showInMenu',
                    (new Query('settings'))
                        ->setSelectionSet(
                            [
                                'hidden',
                                'icon',
                                'titlePlural',
                                'titleSingular',
                            ]
                        ),
                    (new Query('editor'))
                        ->setSelectionSet(
                            [
                                'id',
                                'title',
                                'url',
                            ]
                        ),
                    (new Query('fields'))
                        ->setSelectionSet(
                            array_merge(
                                [
                                    '__typename',
                                    'id',
                                    'slug',
                                    'settings',
                                    'label',
                                    'type',
                                    'priority',
                                    'required',
                                    'hidden',
                                ],
                                $withFieldsSet ? $this->getTypeFieldSelectionSet() : []
                            )
                        ),
                ]
            );

        $results = $this->runQuery($query, true, []);

        return $result = $results->getData()['collectionTypes'];
    }

    /**
     * @throws Exception
     */
    public function getCollectionType($collection_type_id)
    {
        if (!$this->client) {
            throw new Exception('Client was not init.');
        }

        $query = (new Query('collectionType'))
            ->setOperationName('collectionType')
            ->setArguments(['id' => $collection_type_id])
            ->setSelectionSet(
                [
                    'id',
                    'title',
                    'slug',
                    'priority',
                    (new Query('settings'))
                        ->setSelectionSet(
                            [
                                'hidden',
                                'icon',
                                'titlePlural',
                                'titleSingular',
                            ]
                        ),
                    (new Query('editor'))
                        ->setSelectionSet(
                            [
                                'id',
                                'title',
                                'url',
                            ]
                        ),
                    (new Query('fields'))
                        ->setSelectionSet(
                            [
                                'id',
                                'slug',
                                'settings',
                                'label',
                                'type',
                                'priority',
                                'required',
                                'hidden',
                            ]
                        ),
                ]
            );

        $results = $this->runQuery($query, true, []);

        return $results;
    }

    /**
     * @param $collection_type_id
     * @param $slug
     * @param $title
     * @param array $fields
     * @param string $status
     * @return mixed
     * @throws Exception
     */
    public function createCollectionItem(
        $collection_type_id,
        $slug,
        $title = null,
        $seo = null,
        bool $protectedPage = false,
        $status = 'published',
        array $fields = [],
        $pageData = '{"items":[]}'
    ) {
        if (!$this->client) {
            throw new Exception('Client was not init.');
        }

        $mutation = (new Mutation('createCollectionItem'))
            ->setOperationName('createCollectionItem')
            ->setVariables([new Variable('input', 'createCollectionItemInput', true)])
            ->setArguments(['input' => '$input'])
            ->setSelectionSet(
                [
                    (new Query('collectionItem'))->setSelectionSet(
                        [
                            'id',
                            'title',
                            'slug',
                            $this->getFieldsSelectionSet(),
                            (new Query('type'))
                                ->setSelectionSet(
                                    [
                                        'slug',
                                        'priority',
                                    ]
                                ),
                        ]
                    ),
                ]
            );

        $variables = [
            'input' => [
                'type' => $collection_type_id,
                'title' => $title,
                'slug' => $slug,
                'status' => $status,
                'fields' => $fields,
                'pageData' => $pageData,
            ],
        ];

        if ($seo) {
            $seoValue = [
                'enableIndexing' => true,
                'title' => $seo,
            ];
            $variables['input']['seo'] = $seoValue;
        }

        if ($protectedPage) {
            $variables['input']['itemPassword'] = $_ENV['DEFAULT_PAGE_PASSWORD'] ?? "876543";
            $variables['input']['visibility'] = 'passwordProtected';
        }

        $results = $this->runQuery($mutation, true, $variables);

        return $results->getData()['createCollectionItem']['collectionItem'];
    }

    public function deleteCollectionItem($collectionItemUri)
    {
        if (!$this->client) {
            Logger::instance()->warning('Client was not init.');
        }

        $mutation = (new Mutation('deleteCollectionItem'))
            ->setOperationName('deleteCollectionItem')
            ->setVariables([new Variable('input', 'deleteCollectionItemInput', true)])
            ->setArguments(['input' => '$input'])
            ->setSelectionSet(
                [
                    (new Query('collectionItem'))->setSelectionSet(
                        [
                            'id',
                        ]
                    ),
                ]
            );

        $variables = [
            'input' => [
                'id' => $collectionItemUri,
            ],
        ];
        $t = (string)$mutation;

        /**
         * @var Results $results ;
         */
        $results = $this->runQuery($mutation, true, $variables);

        return $results->getData()['deleteCollectionItem']['collectionItem'];
    }

    /**
     * @param array $collection_type_ids
     * @param int $page
     * @param int $limit
     * @return array|mixed
     * @throws Exception
     */
    public function getCollectionItems(array $collection_type_ids, $page = 1, $limit = 1000, $withFields = true)
    {
        if (!$this->client) {
            Logger::instance()->warning('Client was not init.');
        }

        $selectionSet = [];
        foreach ($collection_type_ids as $slug => $collection_type_id) {
            // $slug = preg_replace('/[0-9]+/', GeneralUtils::generateRandomString(5), str_replace('-', '_', $slug)); #https://github.com/bagrinsergiu/brizy-api/issues/325
            $selectionSet[] = (new Query($slug.': collectionItems'))
                ->setArguments(['type' => $collection_type_id, 'page' => $page, 'itemsPerPage' => $limit])
                ->setSelectionSet(
                    [
                        (new Query('collection'))
                            ->setSelectionSet(
                                [
                                    'id',
                                    'title',
                                    'slug',
                                    'status',
                                    'createdAt',
                                    'authorId',
                                    'pageData',
                                    (new Query('seo'))
                                        ->setSelectionSet(
                                            [
                                                'title',
                                                'description',
                                                'enableIndexing',
                                            ]
                                        ),
                                    $this->getCustomAssetsSelectionSet(),
                                    (new Query('social'))
                                        ->setSelectionSet([
                                            'title',
                                            'description',
                                            'image',
                                        ]),
                                    (new Query('type'))
                                        ->setSelectionSet(
                                            [
                                                'id',
                                                'slug',
                                                'priority',
                                                (new Query('settings'))
                                                    ->setSelectionSet(
                                                        [
                                                            'hidden',
                                                            'icon',
                                                            'titlePlural',
                                                            'titleSingular',
                                                        ]
                                                    ),
                                                (new Query('editor'))
                                                    ->setSelectionSet(
                                                        [
                                                            'id',
                                                            'title',
                                                            'url',
                                                        ]
                                                    ),
                                            ]
                                        ),
                                    $withFields ? $this->getFieldsSelectionSet() : '',
                                ]
                            ),
                        (new Query('paginationInfo'))
                            ->setSelectionSet(
                                [
                                    'totalCount',
                                    'itemsPerPage',
                                    'lastPage',
                                ]
                            ),
                    ]
                );
        }

        $query = (new Query())
            ->setOperationName('getMultipleCollectionItems')
            ->setSelectionSet($selectionSet);

        $results = $this->runQuery($query, true, []);

        return $results->getData();
    }

    /**
     * @param string $reference
     * @param int $page
     * @param int $limit
     * @return array|mixed
     * @throws Exception
     */
    public function getCollectionItemsByReference($reference, $page = 1, $limit = 1000)
    {
        if (!$this->client) {
            throw new Exception('Client was not init.');
        }

        $query = (new Query('referencedCollectionItems'))
            ->setOperationName('referencedCollectionItems')
            ->setArguments(['referenced' => $reference, 'page' => $page, 'itemsPerPage' => $limit])
            ->setSelectionSet(
                [
                    (new Query('collection'))
                        ->setSelectionSet(
                            [
                                'id',
                                'title',
                                'slug',
                                'status',
                                'createdAt',
                                'authorId',
                                'pageData',
                                $this->getCustomAssetsSelectionSet(),
                                (new Query('social'))
                                    ->setSelectionSet([
                                        'title',
                                        'description',
                                        'image',
                                    ]),
                                (new Query('type'))
                                    ->setSelectionSet(
                                        [
                                            'id',
                                            'slug',
                                            'priority',
                                            (new Query('settings'))
                                                ->setSelectionSet(
                                                    [
                                                        'hidden',
                                                        'icon',
                                                        'titlePlural',
                                                        'titleSingular',
                                                    ]
                                                ),
                                            (new Query('editor'))
                                                ->setSelectionSet(
                                                    [
                                                        'id',
                                                        'title',
                                                        'url',
                                                    ]
                                                ),
                                        ]
                                    ),
                                $this->getFieldsSelectionSet(),
                            ]
                        ),
                    (new Query('paginationInfo'))
                        ->setSelectionSet(
                            [
                                'totalCount',
                                'itemsPerPage',
                                'lastPage',
                            ]
                        ),
                ]
            );

        $results = $this->runQuery($query, true, []);

        return $results->getData();
    }

    /**
     * @throws Exception
     */
    public function updateCollectionItem(
        $collection_item_id,
        $slug,
        $pageData = [],
        $status = 'published',
        array $fields = [],
        $title = null,
        $seo = null
    ) {
        if (!$this->client) {
            throw new Exception('Client was not init.');
        }

        $mutation = (new Mutation('updateCollectionItem'))
            ->setOperationName('updateCollectionItem')
            ->setVariables([new Variable('input', 'updateCollectionItemInput', true)])
            ->setArguments(['input' => '$input'])
            ->setSelectionSet(
                [
                    (new Query('collectionItem'))->setSelectionSet(
                        [
                            'id',
                            'title',
                            'slug',
                            $this->getFieldsSelectionSet(),
                            (new Query('type'))
                                ->setSelectionSet(
                                    [
                                        'slug',
                                        'priority',
                                    ]
                                ),
                        ]
                    ),
                ]
            );

        $variables = [
            'input' => [
                'id' => $collection_item_id,
            ],
        ];

        if ($fields) {
            $variables['input']['fields'] = $fields;
        }

        if ($title) {
            $variables['input']['title'] = $title;
        }

        if ($status) {
            $variables['input']['status'] = $status;
        }

        if ($slug) {
            $variables['input']['slug'] = $slug;
        }

        if ($pageData) {
            $variables['input']['pageData'] = $pageData;
        }

        if ($seo) {
            $variables['input']['seo'] = $seo;
        }

        $results = $this->runQuery($mutation, true, $variables);

        return $results->getData();
    }

    /**
     * @param $collection_item_id
     * @return array|mixed
     * @throws Exception
     */
    public function getCollectionItem($collection_item_id)
    {
        if (!$this->client) {
            throw new Exception('Client was not init.');
        }

        $query = (new Query('collectionItem'))
            ->setOperationName('collectionItem')
            ->setArguments(['id' => $collection_item_id])
            ->setSelectionSet(
                [
                    'id',
                    'title',
                    'slug',
                    'status',
                    'createdAt',
                    'authorId',
                    'pageData',
                    $this->getCustomAssetsSelectionSet(),
                    (new Query('type'))
                        ->setSelectionSet(
                            [
                                'id',
                                'slug',
                                'priority',
                                (new Query('settings'))
                                    ->setSelectionSet(
                                        [
                                            'hidden',
                                            'icon',
                                            'titlePlural',
                                            'titleSingular',
                                        ]
                                    ),
                                (new Query('editor'))
                                    ->setSelectionSet(
                                        [
                                            'id',
                                            'title',
                                            'url',
                                        ]
                                    ),
                            ]
                        ),
                    $this->getFieldsSelectionSet(),
                    (new Query('seo'))
                        ->setSelectionSet(
                            [
                                'title',
                                'description',
                                'enableIndexing',
                            ]
                        ),
                    (new Query('social'))
                        ->setSelectionSet([
                            'title',
                            'description',
                            'image',
                        ]),
                ]
            );

        $results = $this->runQuery($query, true, []);

        return $results->getData()['collectionItem'];
    }

    private function getCollectionBaseSelectionSet()
    {
        return [
            'id',
            'title',
            'slug',
            'status',
            'createdAt',
            'authorId',
            'pageData',
            (new Query('social'))
                ->setSelectionSet([
                    'title',
                    'description',
                    'image',
                ]),
            (new Query('type'))
                ->setSelectionSet(
                    [
                        'id',
                        'slug',
                        'priority',
                        (new Query('settings'))
                            ->setSelectionSet(
                                [
                                    'hidden',
                                    'icon',
                                    'titlePlural',
                                    'titleSingular',
                                ]
                            ),
                        (new Query('editor'))
                            ->setSelectionSet(
                                [
                                    'id',
                                    'title',
                                    'url',
                                ]
                            ),
                    ]
                ),
            $this->getFieldsSelectionSet(),
        ];
    }

    /**
     * @param $slug
     * @return array|mixed
     * @throws Exception
     */
    public function getCollectionItemBySlug($slug)
    {
        if (!$this->client) {
            throw new Exception('Client was not init.');
        }

        $query = (new Query('collectionItemBySlug'))
            ->setOperationName('collectionItemBySlug')
            ->setArguments(['slug' => $slug])
            ->setSelectionSet(
                [
                    'id',
                    'title',
                    'slug',
                    'status',
                    'createdAt',
                    'authorId',
                    'pageData',
                    $this->getCustomAssetsSelectionSet(),
                    (new Query('type'))
                        ->setSelectionSet(
                            [
                                'id',
                                'slug',
                                'priority',
                                (new Query('settings'))
                                    ->setSelectionSet(
                                        [
                                            'hidden',
                                            'icon',
                                            'titlePlural',
                                            'titleSingular',
                                        ]
                                    ),
                                (new Query('editor'))
                                    ->setSelectionSet(
                                        [
                                            'id',
                                            'title',
                                            'url',
                                        ]
                                    ),
                            ]
                        ),
                    $this->getFieldsSelectionSet(),
                    (new Query('seo'))
                        ->setSelectionSet(
                            [
                                'title',
                                'description',
                                'enableIndexing',
                            ]
                        ),
                    (new Query('social'))
                        ->setSelectionSet([
                            'title',
                            'description',
                            'image',
                        ]),
                ]
            );

        $results = $this->runQuery($query, true, []);

        return $results->getData()['collectionItemBySlug'];
    }

    public function getCollectionTypeBySlug($slug)
    {
        if (!$this->client) {
            throw new Exception('Client was not init.');
        }

        $query = (new Query('collectionTypeBySlug'))
            ->setOperationName('collectionTypeBySlug')
            ->setArguments(['slug' => $slug])
            ->setSelectionSet(
                [
                    'id',
                    'title',
                    'slug',
                ]
            );

        $results = $this->runQuery($query, true, []);

        return $results->getData()['collectionTypeBySlug'];
    }

    /**
     * @param $title
     * @param $appUrl
     * @param $token
     * @return array|mixed
     * @throws Exception
     */
    public function createCmsApplication($title, $appUrl, array $token)
    {
        $this->client = $this->getClient([
            'User-Agent' => 'Brizy Cloud',
            'Authorization' => $token['token_type'].' '.$token['access_token'],
        ]);

        $mutation = (new Mutation('createCmsPublicApplication'))
            ->setOperationName('createCmsPublicApplication')
            ->setVariables([new Variable('input', 'createCmsPublicApplicationInput', true)])
            ->setArguments(['input' => '$input'])
            ->setSelectionSet(
                [
                    (new Query('cmsPublicApplication'))->setSelectionSet(
                        [
                            'id',
                            'title',
                            'appUrl',
                            'redirectUris',
                        ]
                    ),
                ]
            );

        $variables = [
            'input' => [
                'redirectUris' => ['https://www.brizy.cloud'],
                'title' => $title,
                'appUrl' => $appUrl,
                'category' => '/cms_application_categories/1',
                'description' => '',
                'tagLine' => '',
                'appIcon' => '',
                'notificationEmail' => '',
                'appSubmissionContactEmail' => '',
                'supportEmail' => '',
                'privacyPolicyUrl' => '',
            ],
        ];

        $results = $this->runQuery($mutation, true, $variables);

        return $results->getData()['createCmsPublicApplication']['cmsPublicApplication'];
    }

    /**
     * @param $application_id
     * @param $project_id
     * @param array $token
     * @return mixed
     */
    public function createCmsApplicationInstall($application_id, $project_id, array $token)
    {
        $this->client = $this->getClient([
            'User-Agent' => 'Brizy Cloud',
            'Authorization' => $token['token_type'].' '.$token['access_token'],
        ]);

        $mutation = (new Mutation('createCmsApplicationInstall'))
            ->setOperationName('createCmsApplicationInstall')
            ->setVariables([new Variable('input', 'createCmsApplicationInstallInput', true)])
            ->setArguments(['input' => '$input'])
            ->setSelectionSet(
                [
                    (new Query('cmsApplicationInstall'))->setSelectionSet(
                        [
                            'id',
                            (new Query('application'))->setSelectionSet(
                                [
                                    'id',
                                    'title',
                                    'appUrl',
                                ]
                            ),
                        ]
                    ),
                ]
            );

        $variables = [
            'input' => [
                'project' => '/data/'.$project_id,
                'application' => '/cms_public_applications/'.$application_id,
            ],
        ];

        $results = $this->runQuery($mutation, true, $variables);

        return $results->getData()['createCmsApplicationInstall']['cmsApplicationInstall'];
    }

    /**
     * @param $cms_application_id
     * @param array $token
     * @return array|mixed
     */
    public function getCmsApplication($cms_application_id, array $token)
    {
        $this->client = $this->getClient([
            'User-Agent' => 'Brizy Cloud',
            'Authorization' => $token['token_type'].' '.$token['access_token'],
        ]);

        $query = (new Query('cmsPublicApplication'))
            ->setOperationName('cmsPublicApplication')
            ->setArguments(['id' => $cms_application_id])
            ->setSelectionSet(
                [
                    'id',
                    'redirectUris',
                    'clientIdentifier',
                    'clientSecret',
                ]
            );

        $results = $this->runQuery($query, true, []);

        return $results->getData()['cmsPublicApplication'];
    }

    public function getMetafieldByName($name)
    {
        if (!$this->client) {
            throw new Exception('Client was not init.');
        }

        $query = (new Query('metafieldByName'))
            ->setOperationName('metafieldByName')
            ->setArguments(['name' => $name])
            ->setSelectionSet(
                [
                    'id',
                    'value',
                    'name',
                    'type',
                ]
            );

        $results = $this->runQuery($query, true, []);

        return $results->getData()['metafieldByName'];
    }

    public function updateMetafield($id, $value, $project)
    {
        $this->setProject($project);

        $mutation = (new Mutation('updateMetafield'))
            ->setOperationName('updateMetafield')
            ->setVariables([new Variable('input', 'updateMetafieldInput', true)])
            ->setArguments(['input' => '$input'])
            ->setSelectionSet(
                [
                    (new Query('metafield'))->setSelectionSet(
                        [
                            'id',
                            'name',
                            'value',
                        ]
                    ),
                ]
            );

        $variables = [
            'input' => [
                'id' => $id,
                'value' => $value,
            ],
        ];

        $results = $this->runQuery($mutation, true, $variables);

        return $results->getData()['updateMetafield'];
    }

    public function getTypeFieldSelectionSet()
    {
        return [
            (new InlineFragment('CollectionTypeFieldCheck'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    'hidden',
                    'label',
                    '__typename',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('checkSettings'))
                        ->setSelectionSet([
                            (new Query('choices'))
                                ->setSelectionSet([
                                    'value',
                                    'title',
                                ]),
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldColor'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    'hidden',
                    '__typename',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                ]),
            (new InlineFragment('CollectionTypeFieldDateTime'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    'hidden',
                    '__typename',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('dateTimeSettings'))
                        ->setSelectionSet([
                            'time',
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldEmail'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    '__typename',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('emailSettings'))
                        ->setSelectionSet([
                                'placeholder',
                            ]
                        ),
                ]),
            (new InlineFragment('CollectionTypeFieldFile'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    'hidden',
                    'label',
                    '__typename',
                    'slug',
                    'description',
                    'type',
                    'placement',
                ]),
            (new InlineFragment('CollectionTypeFieldGallery'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    '__typename',
                    'required',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                ]),
            (new InlineFragment('CollectionTypeFieldImage'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    'hidden',
                    '__typename',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                ]),
            (new InlineFragment('CollectionTypeFieldLink'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    'hidden',
                    '__typename',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('linkSettings'))
                        ->setSelectionSet([
                            'placeholder',
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldMap'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    '__typename',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                ]),
            (new InlineFragment('CollectionTypeFieldMultiReference'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    'hidden',
                    '__typename',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('multiReferenceSettings'))
                        ->setSelectionSet([
                            (new Query('collectionType'))
                                ->setSelectionSet([
                                    'id',
                                    'title',
                                ]),
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldNumber'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    'hidden',
                    '__typename',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('numberSettings'))
                        ->setSelectionSet([
                            'min',
                            'max',
                            'step',
                            'placeholder',
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldPassword'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    '__typename',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('passwordSettings'))
                        ->setSelectionSet([
                            'minLength',
                            'placeholder',
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldPhone'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    '__typename',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('phoneSettings'))
                        ->setSelectionSet([
                            'placeholder',
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldReference'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    '__typename',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('referenceSettings'))
                        ->setSelectionSet([
                            (new Query('collectionType'))
                                ->setSelectionSet([
                                    'id',
                                    'title',
                                ]),
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldRichText'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    '__typename',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('richTextSettings'))
                        ->setSelectionSet([
                            'minLength',
                            'maxLength',
                            'placeholder',
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldSelect'))
                ->setSelectionSet([
                    'id',
                    'priority',
                    'required',
                    '__typename',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('selectSettings'))
                        ->setSelectionSet([
                            'placeholder',
                            (new Query('choices'))
                                ->setSelectionSet([
                                    'title',
                                    'value',
                                ]),
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldSwitch'))
                ->setSelectionSet([
                    'id',
                    '__typename',
                    'priority',
                    'required',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                ]),
            (new InlineFragment('CollectionTypeFieldText'))
                ->setSelectionSet([
                    'id',
                    '__typename',
                    'priority',
                    'required',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('textSettings'))
                        ->setSelectionSet([
                            'minLength',
                            'maxLength',
                            'placeholder',
                        ]),
                ]),
            (new InlineFragment('CollectionTypeFieldVideoLink'))
                ->setSelectionSet([
                    'id',
                    '__typename',
                    'priority',
                    'required',
                    'hidden',
                    'label',
                    'slug',
                    'description',
                    'type',
                    'placement',
                    (new Query('videoLinkSettings'))
                        ->setSelectionSet([
                            'placeholder',
                        ]),
                ]),
        ];
    }

    public function getFieldsSelectionSet()
    {
        return (new Query('fields'))->setSelectionSet(
            [
                'id',
                '__typename',
                (new Query('type'))
                    ->setSelectionSet([
                        'id',
                        'slug',
                        'settings',
                        (new Query('collectionType'))
                            ->setSelectionSet([
                                'id',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldCheck'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new query('checkValues'))
                            ->setSelectionSet([
                                'value',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldColor'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new query('colorValues'))
                            ->setSelectionSet([
                                'red',
                                'green',
                                'blue',
                                'opacity',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldDateTime'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('dateTimeValues'))
                            ->setSelectionSet([
                                'timestamp',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldEmail'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('emailValues'))
                            ->setSelectionSet([
                                'value',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldFile'))
                    ->setSelectionSet([
                        'id',
                        $this->getFieldInterfaceSelectionSet(),
                    ]),
                (new InlineFragment('CollectionItemFieldGallery'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        'id',
                    ]),
                (new InlineFragment('CollectionItemFieldImage'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('imageValues'))
                            ->setSelectionSet([
                                'id',
                                (new Query('focusPoint'))
                                    ->setSelectionSet([
                                        'x',
                                        'y',
                                    ]),
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldLink'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('linkValues'))
                            ->setSelectionSet([
                                'value',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldMap'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        'id',
                    ]),
                (new InlineFragment('CollectionItemFieldMultiReference'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('multiReferenceValues'))
                            ->setSelectionSet([
                                (new Query('collectionItems'))
                                    ->setSelectionSet([
                                        'id',
                                        'title',
                                        'slug',
                                        'status',
                                    ]),
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldNumber'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('numberValues'))
                            ->setSelectionSet([
                                'value',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldPassword'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('passwordValues'))
                            ->setSelectionSet([
                                'password',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldPhone'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('phoneValues'))
                            ->setSelectionSet([
                                'value',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldReference'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('referenceValues'))
                            ->setSelectionSet([
                                (new Query('collectionItem'))
                                    ->setSelectionSet([
                                        'id',
                                        'title',
                                        'slug',
                                        'status',
                                    ]),
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldMultiReference'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('multiReferenceValues'))
                            ->setSelectionSet([
                                (new Query('collectionItems'))
                                    ->setSelectionSet([
                                        'id',
                                        'title',
                                        'slug',
                                        'status',
                                    ]),
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldRichText'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('richTextValues'))
                            ->setSelectionSet([
                                'value',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldSelect'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('selectValues'))
                            ->setSelectionSet([
                                'value',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldSwitch'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('switchValues'))
                            ->setSelectionSet([
                                'value',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldText'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('textValues'))
                            ->setSelectionSet([
                                'value',
                            ]),
                    ]),
                (new InlineFragment('CollectionItemFieldVideoLink'))
                    ->setSelectionSet([
                        $this->getFieldInterfaceSelectionSet(),
                        (new Query('videoLinkValues'))
                            ->setSelectionSet([
                                'value',
                            ]),
                    ]),
            ]
        );
    }

    private function getCustomAssetsSelectionSet()
    {
        return (new Query('codeInjection'))
            ->setSelectionSet([
                'css',
                (new Query('js'))
                    ->setSelectionSet([
                        'header',
                        'footer',
                    ]),
            ]);
    }

    private function getFieldInterfaceSelectionSet()
    {
        return (new Query('type'))
            ->setSelectionSet([
                'type',
            ]);
    }

    public function getEditors($project)
    {
        $this->setProject($project);

        $query = (new Query('collectionEditors'))
            ->setOperationName('collectionEditors')
            ->setSelectionSet(
                [
                    'title',
                ]
            );

        $results = $this->runQuery($query, true, []);

        return $results->getData()['collectionEditors'];
    }

    /**
     * @throws Exception
     */
    private function runQuery($query, $resultsAsArray = false, $variables = [])
    {
        return $this->client->runQuery($query, $resultsAsArray, $variables);
    }

    public function cloneData($source, $target, array $token)
    {
        $this->client = $this->getClient([
            'User-Agent' => 'Brizy Cloud',
            'Authorization' => $token['token_type'].' '.$token['access_token'],
        ]);

        $mutation = (new Mutation('cloneData'))
            ->setOperationName('cloneData')
            ->setVariables([new Variable('input', 'cloneDataInput', true)])
            ->setArguments(['input' => '$input'])
            ->setSelectionSet(
                [
                    (new Query('data'))->setSelectionSet(
                        [
                            'id',
                        ]
                    ),
                ]
            );

        $variables = [
            'input' => [
                'source' => '/data/'.$source,
                'target' => '/data/'.$target,
            ],
        ];

        $results = $this->runQuery($mutation, true, $variables);

        return $results->getData();
    }

}