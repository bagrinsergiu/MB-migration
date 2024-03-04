<?php

namespace MBMigration\Layer\DataSource\driver;

use MBMigration\Core\Logger;
use Exception;
use MBMigration\Core\Config;
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
        Logger::instance()->info('Initialization');

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
            Logger::instance()->info('Connection success');
        } catch (PDOException $e) {
            Logger::instance()->info($e->getMessage());
            throw new Exception("Database connection failed: ".$e->getMessage());
        }
        Logger::instance()->info('READY');
    }

    /**
     * @throws Exception
     */
    public function request($sql)
    {
        if (!$this->connection) {
            Logger::instance()->warning('Not connected to the database.');
            Logger::instance()->info('Not connected to the database');
            throw new Exception("Not connected to the database.");
        }
        try {
            $statement = $this->connection->query($sql);

            if (Config::getDevOptions('log_SqlQuery')) {
                Logger::instance()->info('success Request: '.json_encode($sql));
            }

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::instance()->info($e->getMessage());
            Logger::instance()->info('MySql Request: '.json_encode($sql));
            Logger::instance()->warning("Query execution : ".$e->getMessage());
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