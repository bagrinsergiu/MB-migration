<?php

namespace MBMigration\Builder\Media;

use MBMigration\Builder\VariableCache;
use MBMigration\Core\Config;

class MediaController
{
    public static function getURLDoc($fileName): string
    {
        $cache = VariableCache::getInstance();
        $uuid = $cache->get('settings')['uuid'];
        $prefix = substr($uuid, 0, 2);

        return Config::$MBMediaStaging."/".$prefix.'/'.$uuid.'/documents/'.$fileName;
    }

    public static function is_doc($file): bool
    {
        if(!filter_var($file, FILTER_VALIDATE_URL)){
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if ($extension === 'pdf' || $extension === 'doc' || $extension === 'docx') {
                return true;
            }
        }

        return false;
    }


}