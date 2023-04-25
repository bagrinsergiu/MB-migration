<?php
namespace Brizy;

use Brizy\layer\Graph\QueryBider;

require_once(__DIR__ . '/../src/core/core.php');

$qb = new QueryBider();

$qb->getCollectionTypes();

