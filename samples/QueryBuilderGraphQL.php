<?php
namespace Brizy;

use Brizy\layer\Graph\QueryBider;

require_once(__DIR__ . '/../src/Core/core.php');

$qb = new QueryBider();

$qb->getCollectionTypes();

