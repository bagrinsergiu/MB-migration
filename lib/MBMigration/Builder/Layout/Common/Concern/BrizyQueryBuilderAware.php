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

    protected function createDetailsCollectionItem($collectionTypeUri, $pageData)
    {
        $slug = 'event-grid';
        $title = 'Event grid';

        $collectionItem = $this->getQueryBuilder()->getCollectionItemBySlug($slug);

        if (!$collectionItem) {
            $pageData = json_encode($pageData);
            $collectionItem = $this->getQueryBuilder()->createCollectionItem(
                $collectionTypeUri,
                $slug,
                $title,
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
}