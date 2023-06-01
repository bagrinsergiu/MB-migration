<?php

namespace Brizy\Builder;

interface QueryBuilder
{
    public function section(): QueryBuilder;

    public function item(int $id): QueryBuilder;

    public function setting(string $key, string $value): QueryBuilder;

    public function get();

}