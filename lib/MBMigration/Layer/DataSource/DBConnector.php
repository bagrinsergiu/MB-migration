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
        \MBMigration\Core\Logger::instance()->info('Initialization');

        $this->connection = new PostgresSQL();

        \MBMigration\Core\Logger::instance()->info('READY');
    }

    /**
     * @throws Exception
     */
    public function request($query)
    {
        return $this->connection->request($query);
    }

    public function requestOne($query)
    {
        $array = $this->connection->request($query);

        return array_pop($array);
    }

    /**
     * @throws Exception
     */
    public function requestArray($query)
    {
        return $this->connection->requestArray($query);
    }
}