<?php

namespace MBMigration\Builder;

use Exception;
use MBMigration\Builder\Factory\VariableCacheFactory;

trait Checking {

    protected function pageCheck($slug): bool
    {
        $cache = VariableCacheFactory::create();
        $ListPages = $cache->get('ListPages');
        foreach ($ListPages as $listSlug => $collectionItems) {
            if ($listSlug == $slug) {
                return false;
            }
        }
        return true;
    }

    /**
     * @throws Exception
     */
    protected function emptyCheck($value, $message = 'CRITICAL: Value cannot be empty')
    {
        if (empty($value)) {
            throw new Exception("$message");
        }
        return $value;
    }

}