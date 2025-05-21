<?php

namespace MBMigration\Bridge\Interfaces;

/**
 * Interface for mapping manager
 */
interface MappingManagerInterface
{
    /**
     * Insert a new mapping record
     *
     * @param int $brzProjectId The Brizy project ID
     * @param string $sourceProjectId The source project ID
     * @param string $metaData JSON encoded metadata
     * @return mixed The result of the insert operation
     */
    public function insertMapping(int $brzProjectId, string $sourceProjectId, string $metaData = '{}'): ?int;

    /**
     * Get all mapping records
     *
     * @return array The mapping list
     */
    public function getAllMappings(): array;

    /**
     * Delete a mapping record by ID
     *
     * @param int $id The mapping ID
     * @return bool True if successful, false otherwise
     */
    public function deleteMapping(int $id): bool;

    /**
     * Find a Brizy project ID by source project UUID
     *
     * @param string $sourceProjectId The source project UUID
     * @return int The Brizy project ID
     * @throws \Exception If the project is not found
     */
    public function findBrizyIdBySourceId(string $sourceProjectId): int;

    /**
     * Find a mapping record by source project UUID
     *
     * @param string $sourceProjectId The source project UUID
     * @return array The mapping record
     * @throws \Exception If the project is not found
     */
    public function findMappingBySourceId(string $sourceProjectId): array;
}
