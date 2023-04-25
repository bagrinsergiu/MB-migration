<?php

use Brizy\builder\VariableCache;
use Brizy\core\ErrorDump;
use Brizy\core\Utils;
use Brizy\core\Config;
use Brizy\layer\Brizy\BrizyAPI;
use Brizy\layer\Graph\QueryBuilder;
use Brizy\Parser\Parser;

require_once(__DIR__ . '/core/core.php');
class MigrationPlatform
{
    private $parser;
    /**
     * @var QueryBuilder
     */
    private $graphQueryBuildet;
    /**
     * @var QueryBuilder
     */
    private $QueryBuilder;

    public function __construct(int $projectID_MB, int $projectID_Brizy)
    {
        Utils::log('Start', 1, 'MIGRATION');

        $cache      = new VariableCache();
        $brizyApi   = new BrizyAPI();

        $errorDump  = new ErrorDump();
        $errorDump->setDate($cache);

        $GraphApi_Brizy = Utils::strReplace(Config::$urlGraphqlAPI, '{ProjectId}', $projectID_Brizy);

        $cache->set('projectId_MB', $projectID_MB);
        $cache->set('projectId_Brizy', $projectID_Brizy);
        $cache->set('GraphApi_Brizy', $GraphApi_Brizy);
        $cache->set('graphToken', $brizyApi->getGraphToken($projectID_Brizy));

        $this->parser = new Parser($cache);
        //$this->parsPage($cache);

        $this->QueryBuilder = new QueryBuilder($cache);

        $this->getAllPage($cache);

        var_dump($cache);
    }

    private function parsPage(VariableCache $cache)
    {
        return $this->parser->getSite();
    }

    private function getAllPage(VariableCache $cache)
    {

        $collectionTypes = $this->QueryBuilder->getCollectionTypes();

        $foundCollectionTypes = [];
        $entities = [];

        foreach ($collectionTypes as $collectionType) {
            if ($collectionType['slug'] == 'page') {
                $foundCollectionTypes[$collectionType['slug']] = $collectionType['id'];
            }
        }

        $collectionItems = $this->QueryBuilder->getCollectionItems($foundCollectionTypes);

        foreach ($collectionItems as $collectionItem) {
            foreach ($collectionItem['collection'] as $entity) {
                $entities[$entity['slug']] = $entity['id'];
            }
        }

        $cache->set('ListPages', $entities);
    }

    private function getPage()
    {
    }

}