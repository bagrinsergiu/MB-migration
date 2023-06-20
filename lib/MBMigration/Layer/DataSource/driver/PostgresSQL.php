<?php

namespace MBMigration\Layer\DataSource\driver;

use Exception;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;
use PDO;
use PDOException;

//use phpseclib\Net\SSH2;


class PostgresSQL
{
    private $connection;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        Utils::log('Initialization', 4, 'PostgresSQL');

        $config = Config::$configPostgreSQL;

        $dbHost = $config['dbHost'];
        $dbPort = $config['dbPort'];
        $dbName = $config['dbName'];
        $dbUser = $config['dbUser'];
        $dbPass = $config['dbPass'];

        try {
            $this->connection = new PDO(
                "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName",
                $dbUser,
                $dbPass
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            Utils::log('Connection success', 4, 'PostgresSQL');
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
        Utils::log('READY', 4, 'PostgresSQL Module');
    }

    /**
     * @throws Exception
     */
    public function request($sql)
    {
        if (!$this->connection) {
            Utils::log('Not connected to the database.', 2, 'PostgresSQL');
            throw new Exception("Not connected to the database.");
        }
        try {
            $statement = $this->connection->query($sql);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Utils::log("Query execution failed: " . $e->getMessage(), 2, 'PostgresSQL');
            throw new Exception("Query execution failed: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function requestArray($sql)
    {
       return $this->request($sql);
    }

}