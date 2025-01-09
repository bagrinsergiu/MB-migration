<?php

namespace MBMigration\Builder\Utils;

class UrlBuilder {
    private $domain;
    private $path;

    public function __construct($domain) {
        $this->domain = 'http://' . rtrim($domain, '/');
        $this->path = '';
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setPath($path): UrlBuilder
    {
        $this->path = ltrim($path, '/');
        return $this;
    }

    public function build(): string
    {
        return $this->domain . '/' . $this->path;
    }

    public function build_path(): string
    {
        return $this->domain . '/' . $this->path;
    }
}
