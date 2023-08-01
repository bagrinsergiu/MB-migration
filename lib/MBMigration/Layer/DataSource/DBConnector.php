<?php

namespace MBMigration\Layer\DataSource;

use Exception;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;
use MBMigration\Layer\DataSource\driver\PostgresSQL;

class DBConnector
{
    private $connection;

    /**
     * @throws Exception
     */
    public function __construct() {
        Utils::log('Initialization', 4, 'DBConnector');

        $this->connection = new PostgresSQL();

        Utils::log('READY', 4, 'DBConnector Module');
    }

    /**
     * @throws Exception
     */
    public function request($query)
    {
        return $this->connection->request($query);
    }

    /**
     * @throws Exception
     */
    public function requestArray($query)
    {
        return $this->connection->requestArray($query);
    }
}