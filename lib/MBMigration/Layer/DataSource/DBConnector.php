<?php

namespace MBMigration\Layer\DataSource;

use MBMigration\Core\Logger;
use Exception;
use MBMigration\Layer\DataSource\driver\PostgresSQL;

class DBConnector
{
    private $connection;

    /**
     * @throws Exception
     */
    public function __construct() {
        Logger::instance()->info('Initialization');

        $this->connection = new PostgresSQL();

        Logger::instance()->info('READY');
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