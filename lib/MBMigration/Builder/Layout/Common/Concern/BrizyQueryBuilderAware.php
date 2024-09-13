<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Layer\Graph\QueryBuilder;

trait BrizyQueryBuilderAware
{
    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @return mixed
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    protected function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;

        return $this;
    }

    protected function createDetailsCollectionItem($collectionTypeUri, $pageData, $slug = 'event-detail', $title = 'Event Detail')
    {
        $collectionItem = $this->getQueryBuilder()->getCollectionItemBySlug($slug);

        if (!$collectionItem) {
            $collectionItem = $this->getQueryBuilder()->createCollectionItem(
                $collectionTypeUri,
                $slug,
                $title,
                false,
                false,
                false,
                'published',
                [],
                json_encode($pageData)
            );
        } else {
            $this->getQueryBuilder()->updateCollectionItem(
                $collectionItem['id'],
                $slug,
                json_encode($pageData)
            );
        }

        return $collectionItem;
    }

    public static function uriToId($uri)
    {
        $match = [];
        preg_match("/^\/.+?\/(?<id>\d+)\/?$/", $uri, $match);

        if (isset($match['id'])) {
            return $match['id'];
        }

        return null;
    }
}
