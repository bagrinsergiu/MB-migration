<?php

namespace MBMigration\Builder;

use Exception;

trait Checking {

    protected function pageCheck($slug): bool
    {
        $cache = VariableCache::getInstance();
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