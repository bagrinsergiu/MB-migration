<?php

namespace Brizy\Layer\DataSource\driver;

use Brizy\core\Config;
use Brizy\core\Utils;
use Exception;
use PDO;
use PDOException;
use phpseclib\Crypt\RSA;
//use phpseclib\Net\SSH2;
use phpseclib3\Net\SSH2;


class PostgreSQL
{
    private SSH2 $ssh;
    private PDO $pdo;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        Utils::log('INIT', 1, 'PostgreSQL');
        $config = Config::configPostgreSQL();

        $dbHost = $config['dbHost'];
        $dbPort = $config['dbPort'];
        $dbName = $config['dbName'];
        try {
            $this->connection = new PDO(
                "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName",
                $config['dbUser'],
                $config['dbPassword']
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            Utils::log('Connection success', 1, 'PostgreSQL');
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function request($sql): bool|array
    {
        if (!$this->connection) {
            Utils::log('Not connected to the database.', 2, 'PostgreSQL');
            throw new Exception("Not connected to the database.");
        }
        try {
            $statement = $this->connection->query($sql);
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Utils::log("Query execution failed: " . $e->getMessage(), 2, 'PostgreSQL');
            throw new Exception("Query execution failed: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function requestArray($sql): bool|array
    {
       return $this->request($sql);
    }

}