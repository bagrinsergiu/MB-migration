<?php

namespace MBMigration\Builder;
trait Checking {

    protected function pageCheck($slug): bool
    {
        $ListPages = $this->cache->get('ListPages');
        foreach ($ListPages as $listSlug => $collectionItems) {
            if ($listSlug == $slug) {
                return false;
            }
        }
        return true;
    }

}