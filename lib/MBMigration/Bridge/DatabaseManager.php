<?php

namespace MBMigration\Bridge;

use Exception;
use MBMigration\Bridge\Interfaces\DatabaseManagerInterface;
use MBMigration\Core\Config;
use MBMigration\Layer\DataSource\driver\MySQL;

/**
 * Handles database connections and operations
 */
class DatabaseManager implements DatabaseManagerInterface
{
    private MySQL $db;

    /**
     * Initialize the database connection
     *
     * @param string $dbUser Database username
     * @param string $dbPass Database password
     * @param string $dbName Database name
     * @param string $dbHost Database host
     */
    public function __construct(
        string $dbUser = null,
        string $dbPass = null,
        string $dbName = null,
        string $dbHost = null
    ) {
        $this->db = $this->createConnection($dbUser, $dbPass, $dbName, $dbHost);
    }

    /**
     * Create a new database connection
     *
     * @param string $dbUser Database username
     * @param string $dbPass Database password
     * @param string $dbName Database name
     * @param string $dbHost Database host
     * @return MySQL The database connection
     */
    private function createConnection(
        ?string $dbUser = null,
        ?string $dbPass = null,
        ?string $dbName = null,
        ?string $dbHost = null
    ): MySQL {
        // Use provided values or fall back to config
        $dbUser = $dbUser ?? Config::$mgConfigMySQL['dbUser'];
        $dbPass = $dbPass ?? Config::$mgConfigMySQL['dbPass'];
        $dbName = $dbName ?? Config::$mgConfigMySQL['dbName'];
        $dbHost = $dbHost ?? Config::$mgConfigMySQL['dbHost'];

        $PDOconnection = new MySQL(
            $dbUser,
            $dbPass,
            $dbName,
            $dbHost
        );

        return $PDOconnection->doConnect();
    }

    /**
     * Get the database connection
     *
     * @return MySQL The database connection
     */
    public function getConnection(): MySQL
    {
        return $this->db;
    }

    /**
     * Execute an insert query
     *
     * @param string $table The table name
     * @param array $data The data to insert
     * @return int|null The ID of the inserted record or null on failure
     * @throws Exception If the insert fails
     */
    public function insert(string $table, array $data): ?int
    {
        try {
            return $this->db->insert($table, $data);
        } catch (\Exception $e) {
            throw new Exception("Failed to insert into {$table}: " . $e->getMessage(), 400);
        }
    }

    /**
     * Execute a delete query
     *
     * @param string $table The table name
     * @param string $where The where clause
     * @param array $params The parameters for the where clause
     * @return bool True if successful, false otherwise
     * @throws Exception If the delete fails
     */
    public function delete(string $table, string $where, array $params): bool
    {
        try {
            return $this->db->delete($table, $where, $params);
        } catch (\Exception $e) {
            throw new Exception("Failed to delete from {$table}: " . $e->getMessage(), 400);
        }
    }

    /**
     * Execute a find query
     *
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return array The result row
     * @throws Exception If the query fails
     */
    public function find(string $query, array $params): array
    {
        try {
            return $this->db->find($query, $params);
        } catch (\Exception $e) {
            throw new Exception("Query failed: " . $e->getMessage(), 400);
        }
    }

    /**
     * Execute a query to get all rows
     *
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return array The result rows
     * @throws Exception If the query fails
     */
    public function getAllRows(string $query, array $params = []): array
    {
        try {
            return $this->db->getAllRows($query, $params);
        } catch (\Exception $e) {
            throw new Exception("Query failed: " . $e->getMessage(), 400);
        }
    }
}
