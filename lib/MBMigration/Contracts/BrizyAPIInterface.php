<?php

namespace MBMigration\Contracts;

use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Интерфейс для работы с Brizy API
 * 
 * Этот интерфейс определяет контракт для всех операций с Brizy API:
 * - Создание и управление проектами
 * - Работа со страницами и меню
 * - Загрузка медиа файлов
 * - Управление шрифтами
 * - Работа с workspace и пользователями
 * 
 * @package MBMigration\Contracts
 * @see \MBMigration\Layer\Brizy\BrizyAPI
 */
interface BrizyAPIInterface
{
    /**
     * Получить метаданные проекта
     * 
     * @param mixed $projectId ID проекта
     * @return array|null Метаданные проекта или null, если не найдены
     * @throws Exception
     */
    public function getProjectMetadata($projectId);

    /**
     * Получить все проекты из контейнера
     * 
     * @param mixed $containerId ID контейнера
     * @param int $count Количество проектов (по умолчанию 100)
     * @return array Массив проектов
     * @throws Exception Если статус ответа > 200
     */
    public function getAllProjectFromContainer($containerId, $count = 100);

    /**
     * Получить все проекты из контейнера (версия V1 API)
     * 
     * @param mixed $containerId ID контейнера
     * @param int $count Количество проектов (по умолчанию 100)
     * @return array Массив проектов
     * @throws Exception Если статус ответа > 200
     */
    public function getAllProjectFromContainerV1($containerId, $count = 100);

    /**
     * Удалить проект
     * 
     * @param mixed $projectID ID проекта для удаления
     * @return bool true если успешно удален
     */
    public function deleteProject($projectID): bool;

    /**
     * Установить домашнюю страницу проекта
     * 
     * @param mixed $projectId ID проекта
     * @param mixed $homePageId ID домашней страницы
     * @return bool true если успешно установлена
     */
    public function getProjectHomePage($projectId, $homePageId);

    /**
     * Получить список workspace или найти workspace по имени
     * 
     * @param string|null $name Имя workspace (опционально)
     * @return array|int|false Массив workspace, ID workspace или false
     * @throws Exception
     */
    public function getWorkspaces($name = null);

    /**
     * Получить проекты из workspace или найти проект по имени
     * 
     * @param mixed $workspacesID ID workspace
     * @param mixed|null $filtre Фильтр по имени проекта (опционально)
     * @return array|int|false Массив проектов, ID проекта или false
     * @throws Exception
     */
    public function getProject($workspacesID, $filtre = null);

    /**
     * Получить данные проекта через приватный API
     * 
     * @param mixed $projectID ID проекта
     * @return array|false Данные проекта или false
     */
    public function getProjectPrivateApi($projectID);

    /**
     * Получить GraphQL токен для проекта
     * 
     * @param mixed $projectid ID проекта
     * @return string Токен доступа
     * @throws Exception Если ошибка получения токена
     */
    public function getGraphToken($projectid);

    /**
     * Получить токен пользователя
     * 
     * @param mixed $userId ID пользователя
     * @return string|false Токен пользователя или false
     * @throws Exception
     */
    public function getUserToken($userId);

    /**
     * Установить токен проекта
     * 
     * @param mixed $newToken Новый токен проекта
     * @return void
     */
    public function setProjectToken($newToken);

    /**
     * Загрузить кастомную иконку
     * 
     * @param mixed $projectId ID проекта
     * @param mixed $fileName Имя файла
     * @param mixed $attachment Содержимое файла (base64)
     * @return array|false Результат загрузки или false
     */
    public function uploadCustomIcon($projectId, $fileName, $attachment);

    /**
     * Создать медиа файл (изображение) в проекте
     * 
     * @param mixed $pathOrUrlToFileName Путь к файлу или URL
     * @param string $nameFolder Имя папки (опционально)
     * @return array|false Результат загрузки или false
     * @throws Exception
     */
    public function createMedia($pathOrUrlToFileName, $nameFolder = '');

    /**
     * Создать глобальный блок
     * 
     * @param mixed $data Данные блока
     * @param mixed $position Позиция блока
     * @param mixed $rules Правила блока
     * @return bool Всегда возвращает false
     * @throws Exception
     * @throws GuzzleException
     */
    public function createGlobalBlock($data, $position, $rules);

    /**
     * Удалить страницу (не реализовано полностью)
     * 
     * @param mixed $url URL страницы
     * @return void
     */
    public function deletePage($url);

    /**
     * Удалить все глобальные блоки проекта
     * 
     * @return void
     */
    public function deleteAllGlobalBlocks();

    /**
     * Открыть файл по URL
     * 
     * @param string $url URL файла
     * @return resource|false Файловый дескриптор или false
     */
    public function fopenFromURL($url);

    /**
     * Создать шрифты в проекте
     * 
     * @param mixed $fontsName Имя шрифта
     * @param mixed $projectID ID проекта
     * @param array $KitFonts Массив шрифтов с весами
     * @param mixed $displayName Отображаемое имя
     * @return array Результат создания шрифта
     * @throws Exception
     * @throws GuzzleException
     */
    public function createFonts($fontsName, $projectID, array $KitFonts, $displayName);

    /**
     * Добавить шрифт и обновить проект
     * 
     * @param array $data Данные шрифта
     * @param string $configFonts Тип шрифта ('upload', 'google', 'config'), по умолчанию 'upload'
     * @return string ID шрифта
     * @throws GuzzleException
     * @throws Exception
     */
    public function addFontAndUpdateProject(array $data, string $configFonts = 'upload'): string;

    /**
     * Проверить, был ли шрифт успешно добавлен в проект
     * 
     * @param array $projectDataResponse Ответ с данными проекта
     * @param mixed $brzFontId ID шрифта в Brizy
     * @param mixed|null $fontNmae Имя шрифта (опционально)
     * @return void
     */
    public function checkUpdateFonts(array $projectDataResponse, $brzFontId, $fontNmae = null);

    /**
     * Очистить все шрифты в проекте
     * 
     * @return void
     * @throws GuzzleException
     */
    public function clearAllFontsInProject();

    /**
     * Установить метку ручной миграции
     * 
     * @param bool $value Значение метки
     * @param mixed|null $projectID ID проекта (опционально)
     * @return void
     */
    public function setLabelManualMigration(bool $value, $projectID = null);

    /**
     * Установить ссылку клонирования проекта
     * 
     * @param bool $value Значение
     * @param mixed|null $projectID ID проекта (опционально)
     * @return void
     */
    public function setCloningLink(bool $value, $projectID = null);

    /**
     * Обновить данные проекта
     * 
     * @param array $projectFullData Полные данные проекта
     * @return array Обновленные данные проекта
     */
    public function updateProject(array $projectFullData): array;

    /**
     * Получить метаданные проекта
     * 
     * @return array Метаданные проекта
     */
    public function getMetadata(): array;

    /**
     * Установить метаданные проекта
     * 
     * @return void
     * @throws GuzzleException
     */
    public function setMetaDate();

    /**
     * Получить данные контейнера проекта
     * 
     * @param int $containerID ID контейнера
     * @param bool $fullDataProject Получить полные данные проекта (по умолчанию false)
     * @return mixed Данные контейнера или проекта
     * @throws Exception
     */
    public function getProjectContainer(int $containerID, $fullDataProject = false);

    /**
     * Создать пользователя
     * 
     * @param array $value Данные пользователя
     * @return string|false Токен пользователя или false
     * @throws Exception
     */
    public function createUser(array $value);

    /**
     * Создать новый проект
     * 
     * @param mixed $projectName Имя проекта
     * @param mixed $workspacesId ID workspace
     * @param mixed|null $filter Фильтр для результата (опционально)
     * @return array|mixed|false Результат создания проекта
     * @throws Exception
     */
    public function createProject($projectName, $workspacesId, $filter = null);

    /**
     * Очистить скомпилированные файлы проекта
     * 
     * @param mixed $projectId ID проекта
     * @return array|mixed|false Результат очистки
     */
    public function clearCompileds($projectId);

    /**
     * Создать новый workspace
     * 
     * @param string|null $name Имя workspace. Если не указано, используется Config::$nameMigration
     * @return array Результат создания workspace
     * @throws Exception
     */
    public function createdWorkspaces(?string $name = null): array;

    /**
     * Получить страницы проекта
     * 
     * @param mixed $projectID ID проекта
     * @return array|int|false Страницы проекта, ID страницы или false
     * @throws Exception
     */
    public function getPage($projectID);

    /**
     * Получить домен проекта
     * 
     * @param mixed $projectID ID проекта
     * @return string|false Домен проекта или false
     */
    public function getDomain($projectID);

    /**
     * Проверить, помечен ли проект как ручная миграция
     * 
     * @param mixed $projectID ID проекта
     * @return bool true если проект помечен как ручная миграция
     */
    public function checkProjectManualMigration($projectID): bool;

    /**
     * Получить версию данных проекта
     * 
     * @param mixed $projectID ID проекта
     * @return int|false Версия данных проекта или false
     */
    public function getProjectsDataVersion($projectID);

    /**
     * Получить данные проекта
     * 
     * @param mixed $projectID ID проекта
     * @return array|false Данные проекта или false
     */
    public function getProjectsData($projectID);

    /**
     * Создать новую страницу в проекте
     * 
     * @param mixed $projectID ID проекта
     * @param mixed $pageName Имя страницы
     * @param mixed|null $filter Фильтр для результата (опционально)
     * @return array|mixed|false Результат создания страницы
     * @throws Exception
     */
    public function createPage($projectID, $pageName, $filter = null);

    /**
     * Получить все страницы проекта через GraphQL
     * 
     * @return array Массив страниц проекта
     */
    public function getAllProjectPages(): array;

    /**
     * Создать меню в проекте
     * 
     * @param array $data Данные меню (project, name, data)
     * @return array|false Результат создания меню или false
     * @throws Exception
     */
    public function createMenu($data);

    /**
     * Клонировать проект
     * 
     * @param mixed $projectId ID проекта для клонирования
     * @param mixed $workspaceId ID workspace для нового проекта
     * @return array|false Результат клонирования или false
     */
    public function cloneProject($projectId, $workspaceId);

    /**
     * Обновить проект
     * 
     * @param mixed $projectId ID проекта
     * @return array|false Результат обновления или false
     */
    public function upgradeProject($projectId);

    /**
     * Установить имя папки для медиа файлов
     * 
     * @param string $nameFolder Имя папки для медиа
     * @return void
     */
    public function setMediaFolder(string $nameFolder);

    /**
     * Создать директорию, если она не существует
     * 
     * @param mixed $directoryPath Путь к директории
     * @return void
     */
    public function createDirectory($directoryPath): void;
}
