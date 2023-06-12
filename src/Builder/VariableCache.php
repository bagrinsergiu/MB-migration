<?php

namespace Brizy\Builder;

use Brizy\core\Utils;

/**
 *
 */
class VariableCache {

    private array $cache;

    public function __construct() {
        $this->cache = array();
        Utils::log('Initialization', 4, 'Cache');
    }

    public function get($key, $section = ''){
        if ($section !== '') {
            return $this->getKeyRecursive($key, $section, $this->cache);
        } else {
            return $this->searchKeyRecursive($key, $this->cache);
        }
    }

    public function update($key, $value, $section = ''): void
    {
        if ($section !== '') {
            $this->updateKeyRecursive($section, $key, $value, $this->cache);
        }
        if (array_key_exists($key, $this->cache)) {
            $this->cache[$key] = $value;
        }
    }

    public function exist($key): bool
    {
        if (array_key_exists($key, $this->cache)) {
            return true;
        }
        return false;
    }

    public function set($key, $value, $section = '', $expiration = 0): void
    {
        if ($section !== '') {
            $this->setKeyRecursive($section, $key, $value, $this->cache);
        } else {
            $this->cache[$key] = $value;
            if ($expiration > 0) {
                $expiration_time = time() + $expiration;
                $this->cache[$key . '_expiration'] = $expiration_time;
            }
        }
    }

    public function add($key, $value, $expiration = 0): void
    {
        $this->cache[$key] = array_merge($this->cache[$key], $value);

        if ($expiration > 0) {
            $expiration_time = time() + $expiration;
            $this->cache[$key . '_expiration'] = $expiration_time;
        }
    }

    public function delete($key): void
    {
        if (array_key_exists($key, $this->cache)) {
            unset($this->cache[$key]);
            unset($this->cache[$key . '_expiration']);
        }
    }

    public function clear(): void
    {
        $this->cache = array();
    }

    public function isExpired($key): bool
    {
        if (array_key_exists($key . '_expiration', $this->cache)) {
            return time() > $this->cache[$key . '_expiration'];
        }
        return false;
    }

    private function setKeyRecursive($section, $key, $value, &$array): void
    {
        foreach ($array as $k => &$v) {
            if ($k === $section && is_array($v)) {
                $v[$key] = $value;
                return;
            }
            if (is_array($v)) {
                $this->setKeyRecursive($section, $key, $value, $v);
            }
        }
    }

    private function getKeyRecursive($key, $section, $array) {
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

    private function searchKeyRecursive($key, $array) {
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
                    $v[$key] = $value;
                    return;
                }
            }
            if (is_array($v)) {
                $this->updateKeyRecursive($section, $key, $value, $v);
            }
        }
    }

}
