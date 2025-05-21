<?php

namespace MBMigration\Bridge;

use Exception;
use MBMigration\Bridge\Interfaces\MappingManagerInterface;
use MBMigration\Layer\DataSource\driver\MySQL;

/**
 * Handles project mapping operations
 */
class MappingManager implements MappingManagerInterface
{
    private MySQL $db;
    private MgResponse $mgResponse;

    public function __construct(MySQL $db, MgResponse $mgResponse)
    {
        $this->db = $db;
        $this->mgResponse = $mgResponse;
    }

    /**
     * Insert a new mapping record
     *
     * @param int $brzProjectId The Brizy project ID
     * @param string $sourceProjectId The source project ID
     * @param string $metaData JSON encoded metadata
     * @return mixed The result of the insert operation
     */
    public function insertMapping(int $brzProjectId, string $sourceProjectId, string $metaData = '{}'): ?int
    {
        try {
            return $this->db->insert('migrations_mapping',
                [
                    'brz_project_id' => $brzProjectId,
                    'mb_project_uuid' => $sourceProjectId,
                    'changes_json' => $metaData
                ]);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }

    /**
     * Get all mapping records
     *
     * @return array The mapping list
     */
    public function getAllMappings(): array
    {
        try {
            $allList = $this->db->getAllRows('SELECT * FROM migrations_mapping');
            $result = [];

            foreach ($allList as $value) {
                $result[(int)$value['brz_project_id']] = $value['mb_project_uuid'];
            }

            return $result;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }

    /**
     * Delete a mapping record by ID
     *
     * @param int $id The mapping ID
     * @return bool True if successful, false otherwise
     */
    public function deleteMapping(int $id): bool
    {
        try {
            return $this->db->delete('migrations_mapping', 'id = ?', [$id]);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }

    /**
     * Find a Brizy project ID by source project UUID
     *
     * @param string $sourceProjectId The source project UUID
     * @return int The Brizy project ID
     * @throws Exception If the project is not found
     */
    public function findBrizyIdBySourceId(string $sourceProjectId): int
    {
        try {
            $brzID = $this->db->find('SELECT brz_project_id FROM migrations_mapping WHERE mb_project_uuid = ?', [$sourceProjectId]);

            if (empty($brzID['brz_project_id'])) {
                throw new Exception('Project not found', 400);
            }

            return (int)$brzID['brz_project_id'];
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }

    /**
     * Find a mapping record by source project UUID
     *
     * @param string $sourceProjectId The source project UUID
     * @return array The mapping record
     * @throws Exception If the project is not found
     */
    public function findMappingBySourceId(string $sourceProjectId): array
    {
        try {
            $mapping = $this->db->find('SELECT * FROM migrations_mapping WHERE mb_project_uuid = ?', [$sourceProjectId]);

            if (empty($mapping['brz_project_id'])) {
                throw new Exception('Project not found', 400);
            }

            return $mapping;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }
}
