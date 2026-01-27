<?php

namespace MBMigration\Builder\Utils;

use MBMigration\Builder\VariableCache;
use MBMigration\Builder\Factory\VariableCacheFactory;
use MBMigration\Core\Config;
use MBMigration\Core\Logger;

class FoldersUtility
{
    /**
     * @throws \Exception
     */
    public static function createProjectFolders($projectId): void
    {
        $cache = VariableCacheFactory::create();

        $folds = [
            'main' => '/',
            'page' => '/page/',
            'media' => '/media/',
            'log' => '/log/',
            'dump' => '/log/dump/',
        ];
        foreach ($folds as $key => $fold) {
            $path = Config::$pathTmp.$projectId.$fold;
            self::createDirectory($path);
            $paths[$key] = $path;
        }
        $cache->set('ProjectFolders', $paths);
    }

    /**
     * @throws \Exception
     */

    private static function createDirectory($directoryPath): void
    {
        if (!is_dir($directoryPath)) {
            Logger::instance()->debug('Create Directory: '.$directoryPath);

            $result = shell_exec("mkdir -p ".escapeshellarg($directoryPath));

            if ($result !== null) {
                Logger::instance()->critical('Error creating directory: '.$result);
            }

            if (!is_dir($directoryPath)) {
                mkdir($directoryPath, 0777, true);
            }
        }
    }
}