<?php

namespace Brizy\Layer\DataSource;

use Brizy\core\Config;
use Brizy\Layer\DataSource\driver\MySql;
use Brizy\Layer\DataSource\driver\PostgreSQL;
use Exception;

class DBConnector
{
    private $connection;

    /**
     * @throws Exception
     */
    public function __construct() {

        $selectedDatabase = Config::$DBconnection;

        if ($selectedDatabase === 'mysql') {
            $this->connectToMySQL();
        } elseif ($selectedDatabase === 'postgresql') {
            $this->connectToPostgreSQL();
        } else {
            throw new Exception('Unsupported database');
        }
    }

    private function connectToMySQL(): void
    {
        $this->connection = new MySql();
    }

    private function connectToPostgreSQL(): void
    {
        $this->connection = new PostgreSQL();
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