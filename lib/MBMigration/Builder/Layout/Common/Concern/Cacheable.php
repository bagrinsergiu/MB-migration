<?php

namespace MBMigration\Builder\Layout\Common\Concern;

trait Cacheable
{
    static private $cache = [];

    /**
     * @param string $key
     * @param callable $callback
     * @return mixed
     */
    public function getCache(string $key, callable $callback)
    {
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        return $callback();
    }

    public function invalidateCache($key): void
    {
        if (isset(self::$cache[$key])) {
            unset(self::$cache[$key]);
        }
    }
}