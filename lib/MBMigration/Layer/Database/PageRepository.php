<?php

namespace MBMigration\Layer\Database;

use MBMigration\Core\Config;
use MBMigration\Core\Logger;
use MBMigration\Layer\DataSource\driver\MySQL;
use Exception;

class PageRepository
{
    private MySQL $db;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->db = new MySQL(
            Config::$mgConfigMySQL['dbUser'],
            Config::$mgConfigMySQL['dbPass'],
            Config::$mgConfigMySQL['dbName'],
            Config::$mgConfigMySQL['dbHost'],
        );
        $this->db->doConnect();
    }

    /**
     * Сохранить или обновить страницу в базе данных
     *
     * @param int|null $migrationId ID миграции (опционально, будет найден автоматически если null)
     * @param int $brzProjectId ID проекта Brizy
     * @param string $mbProjectUuid UUID проекта MB
     * @param string $slug Slug страницы
     * @param int $collectionItemsId ID collection item страницы
     * @param string|null $title Название страницы
     * @param bool $isHomepage Флаг главной страницы
     * @param bool $isProtected Флаг защищенной страницы
     * @return bool|int ID созданной/обновленной записи или false в случае ошибки
     * @throws Exception
     */
    public function savePage(
        ?int $migrationId,
        int $brzProjectId,
        string $mbProjectUuid,
        string $slug,
        int $collectionItemsId,
        ?string $title = null,
        bool $isHomepage = false,
        bool $isProtected = false
    ) {
        try {
            // Если migration_id не передан, пытаемся найти его
            if ($migrationId === null) {
                $migrationId = $this->getOrCreateMigrationId($mbProjectUuid, $brzProjectId);
                if ($migrationId === null) {
                    Logger::instance()->warning('Cannot save page: migration_id not found', [
                        'slug' => $slug,
                        'brz_project_id' => $brzProjectId,
                        'mb_project_uuid' => $mbProjectUuid
                    ]);
                    // Продолжаем без migration_id, но это не критично для сохранения страницы
                }
            }

            // Проверяем, существует ли уже страница с таким brz_project_id и slug
            $existingPage = $this->getPageBySlug($brzProjectId, $slug);

            if ($existingPage) {
                // Обновляем существующую запись
                $data = [
                    'collection_items_id' => $collectionItemsId,
                    'title' => $title,
                    'is_homepage' => $isHomepage ? 1 : 0,
                    'is_protected' => $isProtected ? 1 : 0,
                ];

                // Обновляем migration_id только если он передан
                if ($migrationId !== null) {
                    $data['migration_id'] = $migrationId;
                }

                $where = [
                    'brz_project_id' => $brzProjectId,
                    'slug' => $slug,
                ];

                $this->db->update('migration_pages', $data, $where);
                Logger::instance()->info('Page updated in database', [
                    'slug' => $slug,
                    'brz_project_id' => $brzProjectId,
                    'collection_items_id' => $collectionItemsId
                ]);

                return $existingPage['id'];
            } else {
                // Создаем новую запись
                $data = [
                    'brz_project_id' => $brzProjectId,
                    'mb_project_uuid' => $mbProjectUuid,
                    'slug' => $slug,
                    'collection_items_id' => $collectionItemsId,
                    'title' => $title,
                    'is_homepage' => $isHomepage ? 1 : 0,
                    'is_protected' => $isProtected ? 1 : 0,
                ];

                // Добавляем migration_id только если он есть
                if ($migrationId !== null) {
                    $data['migration_id'] = $migrationId;
                }

                $pageId = $this->db->insert('migration_pages', $data);

                Logger::instance()->info('Page saved to database', [
                    'id' => $pageId,
                    'slug' => $slug,
                    'brz_project_id' => $brzProjectId,
                    'collection_items_id' => $collectionItemsId
                ]);

                return $pageId;
            }
        } catch (Exception $e) {
            Logger::instance()->error('Error saving page to database: ' . $e->getMessage(), [
                'slug' => $slug,
                'brz_project_id' => $brzProjectId,
                'collection_items_id' => $collectionItemsId
            ]);
            return false;
        }
    }

    /**
     * Получить страницу по slug и project_id
     *
     * @param int $brzProjectId ID проекта Brizy
     * @param string $slug Slug страницы
     * @return array|false Массив с данными страницы или false, если не найдена
     * @throws Exception
     */
    public function getPageBySlug(int $brzProjectId, string $slug)
    {
        try {
            $sql = "SELECT * FROM migration_pages 
                    WHERE brz_project_id = :brz_project_id AND slug = :slug 
                    LIMIT 1";

            $params = [
                ':brz_project_id' => $brzProjectId,
                ':slug' => $slug,
            ];

            $result = $this->db->getAllRows($sql, $params);

            return !empty($result) ? $result[0] : false;
        } catch (Exception $e) {
            Logger::instance()->error('Error getting page by slug: ' . $e->getMessage(), [
                'slug' => $slug,
                'brz_project_id' => $brzProjectId
            ]);
            return false;
        }
    }

    /**
     * Получить все страницы миграции
     *
     * @param int $migrationId ID миграции
     * @return array Массив страниц
     * @throws Exception
     */
    public function getPagesByMigration(int $migrationId): array
    {
        try {
            $sql = "SELECT * FROM migration_pages 
                    WHERE migration_id = :migration_id 
                    ORDER BY created_at ASC";

            $params = [
                ':migration_id' => $migrationId,
            ];

            return $this->db->getAllRows($sql, $params);
        } catch (Exception $e) {
            Logger::instance()->error('Error getting pages by migration: ' . $e->getMessage(), [
                'migration_id' => $migrationId
            ]);
            return [];
        }
    }

    /**
     * Получить все страницы проекта
     *
     * @param int $brzProjectId ID проекта Brizy
     * @return array Массив страниц
     * @throws Exception
     */
    public function getPagesByProject(int $brzProjectId): array
    {
        try {
            $sql = "SELECT * FROM migration_pages 
                    WHERE brz_project_id = :brz_project_id 
                    ORDER BY created_at ASC";

            $params = [
                ':brz_project_id' => $brzProjectId,
            ];

            return $this->db->getAllRows($sql, $params);
        } catch (Exception $e) {
            Logger::instance()->error('Error getting pages by project: ' . $e->getMessage(), [
                'brz_project_id' => $brzProjectId
            ]);
            return [];
        }
    }

    /**
     * Получить или создать migration_id для проекта
     *
     * @param string $mbProjectUuid UUID проекта MB
     * @param int $brzProjectId ID проекта Brizy
     * @return int|null ID миграции или null, если не найдено
     * @throws Exception
     */
    public function getOrCreateMigrationId(string $mbProjectUuid, int $brzProjectId): ?int
    {
        try {
            // Ищем существующую запись миграции
            $sql = "SELECT id FROM migrations 
                    WHERE mb_project_uuid = :mb_project_uuid AND brz_project_id = :brz_project_id 
                    ORDER BY created_at DESC LIMIT 1";

            $params = [
                ':mb_project_uuid' => $mbProjectUuid,
                ':brz_project_id' => $brzProjectId,
            ];

            $result = $this->db->getAllRows($sql, $params);

            if (!empty($result) && isset($result[0]['id'])) {
                return (int)$result[0]['id'];
            }

            // Если не найдено, возвращаем null
            // Запись в migrations должна быть создана до начала миграции
            Logger::instance()->warning('Migration record not found in migrations table', [
                'mb_project_uuid' => $mbProjectUuid,
                'brz_project_id' => $brzProjectId
            ]);

            return null;
        } catch (Exception $e) {
            Logger::instance()->error('Error getting migration ID: ' . $e->getMessage(), [
                'mb_project_uuid' => $mbProjectUuid,
                'brz_project_id' => $brzProjectId
            ]);
            return null;
        }
    }
}
