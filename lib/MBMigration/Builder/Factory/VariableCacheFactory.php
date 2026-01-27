<?php

namespace MBMigration\Builder\Factory;

use MBMigration\Builder\VariableCache;

/**
 * Фабрика для создания экземпляров VariableCache
 * 
 * Предоставляет централизованный способ создания VariableCache
 * вместо использования статического метода getInstance().
 * 
 * Использование:
 * ```php
 * $cache = VariableCacheFactory::create('/path/to/cache');
 * ```
 */
class VariableCacheFactory
{
    /**
     * Создать новый экземпляр VariableCache
     * 
     * @param string|null $cachePath Путь к директории кэша. Если не указан, используется Config::$cachePath
     * @return VariableCache Новый экземпляр VariableCache
     */
    public static function create(?string $cachePath = null): VariableCache
    {
        return new VariableCache($cachePath);
    }

    /**
     * Создать VariableCache с путем по умолчанию
     * 
     * Использует Config::$cachePath, если он установлен.
     * 
     * @return VariableCache Новый экземпляр VariableCache
     */
    public static function createDefault(): VariableCache
    {
        return new VariableCache();
    }
}
