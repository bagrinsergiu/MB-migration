<?php

namespace MBMigration\Contracts;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Интерфейс для работы с данными проектов Ministry Brands
 * 
 * Этот интерфейс определяет контракт для всех операций получения данных из базы данных MB:
 * - Получение данных сайта, страниц, секций
 * - Работа со шрифтами
 * - Получение элементов секций
 * - Работа с доменами
 * 
 * Примечание: Статические методы не включены в интерфейс, так как PHP 7.4 не поддерживает
 * статические методы в интерфейсах. Статические методы можно использовать напрямую через класс.
 * 
 * @package MBMigration\Contracts
 * @see \MBMigration\Layer\MB\MBProjectDataCollector
 */
interface MBProjectDataCollectorInterface
{
    /**
     * Установить ID проекта вручную
     * 
     * Полезно при использовании класса независимо от процесса миграции
     *
     * @param mixed $projectId ID проекта для установки
     * @return $this Для цепочки вызовов
     */
    public function setProjectId($projectId);

    /**
     * Получить имя дизайна сайта из базы данных
     * 
     * @return string|false Имя дизайна сайта или false, если проект не найден
     * @throws Exception
     */
    public function getDesignSite();

    /**
     * Получить полные данные сайта из базы данных
     * 
     * Возвращает данные сайта: id, uuid, name, title, domain, design, settings,
     * favicon, fonts и т.д.
     * 
     * @return array|false Данные сайта или false, если проект не найден
     * @throws Exception
     * @throws GuzzleException
     */
    public function getSite();

    /**
     * Получить шрифты из настроек или темы шрифтов
     * 
     * Если в настройках есть шрифты, возвращает их. Иначе получает шрифты
     * из темы шрифтов по UUID.
     * 
     * @param mixed $settings Настройки сайта
     * @param mixed $fontThemeUUID UUID темы шрифтов
     * @param string $migrationDefaultFonts Шрифт по умолчанию для миграции (по умолчанию 'poppins')
     * @return array Массив шрифтов
     * @throws Exception
     * @throws GuzzleException
     */
    public function getFonts($settings, $fontThemeUUID, $migrationDefaultFonts = 'poppins'): array;

    /**
     * Получить шрифты по умолчанию из темы шрифтов
     * 
     * Получает шрифты из базы данных по UUID темы шрифтов и обрабатывает их
     * для использования в миграции.
     * 
     * @param mixed $fontThemeUUID UUID темы шрифтов
     * @param mixed $migrationDefaultFonts Шрифт по умолчанию для миграции
     * @return array Массив шрифтов по умолчанию
     * @throws GuzzleException
     * @throws Exception
     */
    public function getDefaultFont($fontThemeUUID, $migrationDefaultFonts);

    /**
     * Получить главные секции сайта
     * 
     * Возвращает секции сайта, которые не привязаны к конкретной странице
     * (page_id is null or 0). Включает элементы секций.
     * 
     * @return array Массив главных секций сайта, сгруппированных по категориям
     * @throws Exception
     */
    public function getMainSection(): array;

    /**
     * Получить все страницы сайта с иерархией
     * 
     * Возвращает все страницы сайта с иерархией родитель-потомок.
     * Исключает удаленные страницы (trashed_at is null).
     * 
     * @return array Массив страниц с иерархией (родитель-потомок)
     */
    public function getPages(): array;

    /**
     * Получить родительские страницы
     * 
     * @deprecated Очень медленная реализация. Используйте getPages().
     * 
     * @return array Массив родительских страниц с дочерними страницами
     * @throws Exception
     */
    public function getParentPages(): array;

    /**
     * Получить дочерние страницы по ID родительской страницы
     * 
     * @param mixed $parenId ID родительской страницы
     * @return array Массив дочерних страниц (id, position)
     * @throws Exception
     */
    public function getChildFromPages($parenId): array;

    /**
     * Получить все секции страницы
     * 
     * Возвращает все секции страницы с их настройками и layout.
     * Секции упорядочены по позиции.
     * 
     * @param mixed $id ID страницы
     * @return array Массив секций страницы с настройками и layout
     * @throws Exception
     */
    public function getSectionsPage($id): array;

    /**
     * Получить все ID проектов с определенными критериями
     * 
     * Возвращает проекты с критериями:
     * - setup_step = 'final'
     * - site_type = 'user'
     * - directory_name IS NOT NULL
     * - archived_at IS NULL
     * - design_uuid = '8405e015-b796-4e14-896f-7991da379e77'
     * 
     * @return array Массив проектов (id, uuid)
     * @throws Exception
     */
    public function getAllProjectsID();

    /**
     * Получить элементы из секции
     * 
     * Возвращает элементы (items) из секции. Использует кэш для оптимизации.
     * Если параметр $assembly = true, элементы группируются по parent_id.
     * 
     * @param mixed|array $sectionId ID секции или массив с данными секции
     * @param bool $assembly Собрать элементы в группы (по умолчанию false)
     * @return array Массив элементов секции
     * @throws GuzzleException
     * @throws Exception
     */
    public function getItemsFromSection($sectionId, $assembly = false);

    /**
     * Транслитерировать название семейства шрифтов
     * 
     * Убирает кавычки, пробелы, запятые из названия семейства шрифтов
     * и приводит к нижнему регистру.
     * 
     * @param mixed $family Название семейства шрифтов
     * @return string Транслитерированное название шрифта
     */
    public function transLiterationFontFamily($family): string;
}
