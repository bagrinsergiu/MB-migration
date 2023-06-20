<?php

namespace MBMigration\Layer\DataSource;

use Exception;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;
use MBMigration\Layer\DataSource\driver\MySql;
use MBMigration\Layer\DataSource\driver\PostgresSQL;

class DBConnector
{
    private $connection;

    /**
     * @throws Exception
     */
    public function __construct() {
        Utils::log('Initialization', 4, 'DBConnector');
        $selectedDatabase = Config::$DBConnection;

        if ($selectedDatabase === 'mysql') {
            $this->connectToMySQL();
        } elseif ($selectedDatabase === 'postgresql') {
            $this->connectToPostgresSQL();
        } else {
            throw new Exception('Unsupported database');
        }
       Utils::log('READY', 4, 'DBConnector Module');
    }

    private function connectToMySQL(): void
    {
        $this->connection = new MySql();
    }

    private function connectToPostgresSQL(): void
    {
        $this->connection = new PostgresSQL();
    }

    public function request($query)
    {
        return $this->connection->request($query);
    }

    public function requestArray($query)
    {
        return $this->connection->requestArray($query);
    }
}