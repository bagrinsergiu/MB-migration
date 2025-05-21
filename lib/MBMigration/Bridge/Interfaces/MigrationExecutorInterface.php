<?php

namespace MBMigration\Bridge\Interfaces;

/**
 * Interface for migration executor
 */
interface MigrationExecutorInterface
{
    /**
     * Execute a migration
     *
     * @param string $mbProjectUuid The Ministry Brands project UUID
     * @param int $brzProjectId The Brizy project ID
     * @param int $brzWorkspacesId The Brizy workspace ID
     * @param string $mbPageSlug The Ministry Brands page slug
     * @param bool $isManual Whether the migration is manual
     * @return ResponseHandlerInterface The response handler
     */
    public function executeMigration(
        string $mbProjectUuid,
        int $brzProjectId,
        int $brzWorkspacesId = 0,
        string $mbPageSlug = '',
        bool $isManual = false
    ): ResponseHandlerInterface;

    /**
     * Run a migration wave
     *
     * @param int $waveId The wave ID
     * @return ResponseHandlerInterface The response handler
     */
    public function runMigrationWave(int $waveId): ResponseHandlerInterface;

    /**
     * Clear a workspace
     *
     * @param int $workspaceId The workspace ID
     * @return ResponseHandlerInterface The response handler
     */
    public function clearWorkspace(int $workspaceId): ResponseHandlerInterface;
}
