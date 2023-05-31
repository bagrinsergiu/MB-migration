<?php

namespace Builder;

interface QueryBuilder
{
    public function section(): QueryBuilder;

    public function item(int $id): QueryBuilder;

    public function settings(string $key, string $value): QueryBuilder;

    public function get();

}