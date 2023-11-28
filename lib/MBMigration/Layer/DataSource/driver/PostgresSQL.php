<?php

namespace MBMigration\Layer\DataSource\driver;

use Exception;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;
use PDO;
use PDOException;

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
                "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName;user=$dbUser;password=$dbPass;keepalives_idle=1"
            );

            $this->connection->setAttribute(PDO::ATTR_TIMEOUT, 20);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            Utils::log('Connection success', 4, 'PostgresSQL');
        } catch (PDOException $e) {
            Utils::MESSAGES_POOL($e->getMessage(), 'error', 'PostgresSQL');
            throw new Exception("Database connection failed: ".$e->getMessage());
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
            Utils::MESSAGES_POOL('Not connected to the database', 'error', 'PostgresSQL');
            throw new Exception("Not connected to the database.");
        }
        try {
            $statement = $this->connection->query($sql);

            if (Config::getDevOptions('log_SqlQuery')) {
                Utils::MESSAGES_POOL('success Request: '.json_encode($sql), 'Request', 'PostgresSQL');
            }

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Utils::MESSAGES_POOL($e->getMessage(), 'error', 'PostgresSQL');
            Utils::MESSAGES_POOL('MySql Request: '.json_encode($sql), 'Failed Request', 'PostgresSQL');
            Utils::log("Query execution : ".$e->getMessage(), 2, 'PostgresSQL');
            throw new Exception("Query execution failed: ".$e->getMessage().' Request: '.json_encode($sql));
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