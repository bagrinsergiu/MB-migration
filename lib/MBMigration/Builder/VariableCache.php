<?php

namespace MBMigration\Builder;

use MBMigration\Core\Logger;
use MBMigration\Core\Factory\LoggerFactory;
use InvalidArgumentException;

/**
 * Класс VariableCache для хранения временных данных в памяти
 * 
 * Используется для кэширования данных во время миграции.
 * Поддерживает работу с секциями и объектами.
 * 
 * @deprecated Статический метод getInstance() помечен как deprecated.
 * Используйте конструктор или VariableCacheFactory для создания экземпляров.
 */
class VariableCache
{
    private $cachePath;
    /**
     * @var array Статическое свойство для обратной совместимости с getInstance()
     * @deprecated Используется только для обратной совместимости
     */
    private static $instance = null;
    private $cache;

    /**
     * Конструктор VariableCache
     * 
     * Создает новый экземпляр VariableCache с указанным путем к кэшу.
     * 
     * @param string|null $cachePath Путь к директории кэша. Если не указан, используется Config::$cachePath
     * @param \Psr\Log\LoggerInterface|null $logger Логгер для записи событий. Если не передан, будет использован LoggerFactory
     */
    public function __construct($cachePath = null, ?\Psr\Log\LoggerInterface $logger = null)
    {
        // Если Logger не передан, создаем через LoggerFactory для обратной совместимости
        if ($logger === null) {
            $logger = LoggerFactory::createDefault('VariableCache');
        }
        
        $logger->debug('VariableCache initialization');
        
        // Если cachePath не указан, используем Config::$cachePath
        if ($cachePath === null) {
            $cachePath = \MBMigration\Core\Config::$cachePath ?? './var/cache';
        }
        
        $this->cachePath = rtrim($cachePath, '/');
        $this->cache = ['OBJECTS' => []];
    }

    /**
     * Получить экземпляр VariableCache (Singleton)
     * 
     * @deprecated Используйте конструктор или VariableCacheFactory::create() вместо этого метода
     * Этот метод сохранен для обратной совместимости, но не рекомендуется для использования в новом коде.
     * 
     * @param string|null $cachePath Путь к директории кэша
     * @return VariableCache Экземпляр VariableCache
     */
    public static function getInstance($cachePath = null)
    {
        $subclass = static::class;
        if (!isset(self::$instance[$subclass])) {
            self::$instance[$subclass] = new static($cachePath);
        }

        return self::$instance[$subclass];
    }

    /**
     * Установить путь к кэшу
     * 
     * @param string $cachePath Путь к директории кэша
     * @return void
     */
    public function setCachePath($cachePath)
    {
        // Используем LoggerFactory для создания Logger, так как Logger::instance() deprecated
        $logger = LoggerFactory::createDefault('VariableCache');
        $logger->debug('VariableCache initialization');
        $this->cachePath = rtrim($cachePath, '/');
        $this->cache = ['OBJECTS' => []];
    }

    /**
     * @return array
     */
    public function getCache(): array
    {
        return $this->cache;
    }

    public function get($key, $section = '')
    {
        if ($section !== '') {
            return $this->getKeyRecursive($key, $section, $this->cache);
        } else {
            return $this->searchKeyRecursive($key, $this->cache);
        }
    }

    public function update($key, $value, $section = null): void
    {
        if ($section != null) {
            $this->updateKeyRecursive($section, $key, $value, $this->cache);
        }
        if (array_key_exists($key, $this->cache)) {
            if ($value === "++") {
                if (isset($this->cache[$key])) {
                    $this->cache[$key] += 1;
                }
            } else {
                $this->cache[$key] = $value;
            }
        }
    }

    public function exist($key): bool
    {
        if (array_key_exists($key, $this->cache)) {
            return true;
        }

        return false;
    }

    public function set($key, $value, $section = null): void
    {
        if ($section !== null) {
            $this->setKeyRecursive($section, $key, $value, $this->cache);
        } else {
            $this->cache[$key] = $value;
        }
    }

    public function add($key, $value, $expiration = 0): void
    {
        if (array_key_exists($key, $this->cache)) {
            $this->cache[$key] = array_merge($this->cache[$key], $value);
        } else {
            $this->cache[$key] = $value;
        }
        if ($expiration > 0) {
            $expiration_time = time() + $expiration;
            $this->cache[$key.'_expiration'] = $expiration_time;
        }
    }

    public function setClass(object $class, $name): void
    {
        $this->set($name, $class, 'OBJECTS');
    }

    public function getClass(string $name): object
    {
        return $this->get($name, 'OBJECTS');
    }

    private function setKeyRecursive($section, $key, $value, &$array): void
    {
        if (is_array($section)) {
            $currentSection = array_shift($section);
        } else {
            $currentSection = $section;
        }
        if (!isset($array[$currentSection])) {
            $array[$currentSection] = [];
        }
        if (is_array($section)) {
            if (count($section) === 0) {
                $array[$currentSection][$key] = $value;

                return;
            }
        } else {
            $array[$currentSection][$key] = $value;

            return;
        }
        $this->setKeyRecursive($section, $key, $value, $array[$currentSection]);
    }

    private function getKeyRecursive($key, $section, $array)
    {
        foreach ($array as $k => $value) {
            if ($k === $section && is_array($value)) {
                if (array_key_exists($key, $value)) {
                    return $value[$key];
                }
            }
            if (is_array($value)) {
                $result = $this->getKeyRecursive($key, $section, $value);
                if ($result !== null) {
                    return $result;
                }
            }
        }

        return null;
    }

    private function searchKeyRecursive($key, $array)
    {
        foreach ($array as $k => $value) {
            if ($k === $key) {
                return $value;
            }
            if (is_array($value)) {
                $result = $this->searchKeyRecursive($key, $value);
                if ($result !== null) {
                    return $result;
                }
            }
        }

        return null;
    }

    private function updateKeyRecursive($section, $key, $value, &$array): void
    {
        foreach ($array as $k => &$v) {
            if ($k === $section && is_array($v)) {
                if (array_key_exists($key, $v)) {
                    if ($value === "++") {
                        if (isset($v[$key])) {
                            $v[$key] += 1;
                        }
                    } else {
                        $v[$key] = $value;
                    }

                    return;
                }
            }
            if (is_array($v)) {
                $this->updateKeyRecursive($section, $key, $value, $v);
            }
        }
    }

    public function LOAD_DATA($data)
    {
        if (is_string($data)) {
            $decodedData = json_decode($data, true);
            if ($decodedData === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidArgumentException('Invalid JSON data');
            }
            $this->cache = $decodedData;
        } elseif (is_array($data)) {
            $this->cache = $data;
        }
    }

    public function init()
    {
        $this->cache = [];

        $this->cache['OBJECTS'] = [];
    }

    public function dumpCache($projectID_MB, $projectID_Brizy)
    {
        $fileName = $this->cachePath."/".md5($projectID_MB.$projectID_Brizy)."-$projectID_Brizy.json";
        file_put_contents($fileName, json_encode($this->cache));
    }

    public function loadDump($projectID_MB, $projectID_Brizy)
    {
        $fileName = $this->cachePath."/".md5($projectID_MB.$projectID_Brizy)."-$projectID_Brizy.json";
        if (file_exists($fileName)) {
            $this->cache = json_decode(file_get_contents($fileName), true);
        }
    }
}
