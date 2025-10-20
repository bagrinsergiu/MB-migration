<?php

namespace MBMigration\Layer\DataSource;

use MBMigration\Core\Logger;
use Exception;
use MBMigration\Layer\DataSource\driver\PostgresSQL;

class DBConnector
{
    static $instance = null;

    private $connection;

    /**
     * @throws Exception
     */
    private function __construct() {
        $this->connection = new PostgresSQL();
    }

    public static function getInstance(): DBConnector
    {
        if (self::$instance === null) {
            self::$instance = new DBConnector();
        }

        return self::$instance;
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
