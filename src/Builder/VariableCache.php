<?php

namespace Brizy\Builder;

use Brizy\core\Utils;

/**
 *
 */
class VariableCache {

    private $cache;

    public function __construct() {
        $this->cache = array();
        Utils::log('Init', 1, 'Class VariableCache');
    }

    public function get($key) {
        if (array_key_exists($key, $this->cache)) {
            return $this->cache[$key];
        }
        return null;
    }
    public function exist($key): bool
    {
        if (array_key_exists($key, $this->cache)) {
            return true;
        }
        return false;
    }

    public function set($key, $value, $expiration = 0) {
        $this->cache[$key] = $value;
        if ($expiration > 0) {
            $expiration_time = time() + $expiration;
            $this->cache[$key . '_expiration'] = $expiration_time;
        }
    }

    public function delete($key) {
        if (array_key_exists($key, $this->cache)) {
            unset($this->cache[$key]);
            unset($this->cache[$key . '_expiration']);
        }
    }

    public function clear() {
        $this->cache = array();
    }

    public function isExpired($key) {
        if (array_key_exists($key . '_expiration', $this->cache)) {
            return time() > $this->cache[$key . '_expiration'];
        }
        return false;
    }
}
