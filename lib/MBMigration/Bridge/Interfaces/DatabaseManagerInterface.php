<?php

namespace MBMigration\Bridge\Interfaces;

use MBMigration\Layer\DataSource\driver\MySQL;

/**
 * Interface for database manager
 */
interface DatabaseManagerInterface
{
    /**
     * Get the database connection
     *
     * @return MySQL The database connection
     */
    public function getConnection(): MySQL;

    /**
     * Execute an insert query
     *
     * @param string $table The table name
     * @param array $data The data to insert
     * @return int|null The ID of the inserted record or null on failure
     * @throws \Exception If the insert fails
     */
    public function insert(string $table, array $data): ?int;

    /**
     * Execute a delete query
     *
     * @param string $table The table name
     * @param string $where The where clause
     * @param array $params The parameters for the where clause
     * @return bool True if successful, false otherwise
     * @throws \Exception If the delete fails
     */
    public function delete(string $table, string $where, array $params): bool;

    /**
     * Execute a find query
     *
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return array The result row
     * @throws \Exception If the query fails
     */
    public function find(string $query, array $params): array;

    /**
     * Execute a query to get all rows
     *
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return array The result rows
     * @throws \Exception If the query fails
     */
    public function getAllRows(string $query, array $params = []): array;
}
