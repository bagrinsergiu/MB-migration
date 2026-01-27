<?php

namespace MBMigration\Layer\DataSource\driver;

use MBMigration\Core\Logger;
use Exception;
use MBMigration\Core\Config;
use MBMigration\Contracts\DatabaseInterface;
use PDO;
use PDOException;

class PostgresSQL implements DatabaseInterface
{
    private $connection;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        Logger::instance()->debug('PostgresSQL Initialization');

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
            Logger::instance()->debug('Connection success');
        } catch (PDOException $e) {
            Logger::instance()->error($e->getMessage());
            throw new Exception("Database connection failed: ".$e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function request($sql)
    {
        if (!$this->connection) {
            Logger::instance()->error('Not connected to the database.');
            throw new Exception("Not connected to the database.");
        }
        try {
            $statement = $this->connection->query($sql);

            if (Config::getDevOptions('log_SqlQuery')) {
                Logger::instance()->debug('success Request: '.json_encode($sql));
            }

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::instance()->error($e->getMessage(),[$sql]);
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

    /**
     * Выполнить SQL запрос и вернуть все строки результата
     * 
     * Реализация метода интерфейса DatabaseInterface.
     * Использует существующий метод request().
     * 
     * Примечание: Параметры $params игнорируются, так как PostgresSQL::request()
     * не поддерживает подготовленные запросы. Для использования параметров
     * рекомендуется использовать метод request() напрямую или обновить реализацию.
     * 
     * @param string $sql SQL запрос для выполнения
     * @param array $params Параметры для подготовленного запроса (игнорируются)
     * @return array Массив всех строк результата
     * @throws Exception
     */
    public function query(string $sql, array $params = []): array
    {
        // Игнорируем параметры, так как request() не поддерживает подготовленные запросы
        // В будущем можно добавить поддержку параметров через PDO::prepare()
        return $this->request($sql);
    }

    /**
     * Выполнить SQL запрос и вернуть одну строку результата
     * 
     * Реализация метода интерфейса DatabaseInterface.
     * Использует существующий метод request() и возвращает первую строку.
     * 
     * Примечание: Параметры $params игнорируются, так как PostgresSQL::request()
     * не поддерживает подготовленные запросы.
     * 
     * @param string $sql SQL запрос для выполнения
     * @param array $params Параметры для подготовленного запроса (игнорируются)
     * @return array|null Первая строка результата или null
     * @throws Exception
     */
    public function queryOne(string $sql, array $params = []): ?array
    {
        $result = $this->request($sql);
        return empty($result) ? null : $result[0];
    }

}