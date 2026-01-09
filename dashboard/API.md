# Dashboard API Documentation

## Базовый URL
`http://localhost:8080/dashboard/api`

## Endpoints

### Health Check
**GET** `/health` или `/`

Проверка работоспособности API.

**Response:**
```json
{
  "status": "success",
  "message": "Dashboard API is running",
  "version": "1.0.0",
  "endpoints": {...}
}
```

### Миграции

#### Получить список миграций
**GET** `/migrations`

**Query параметры:**
- `status` (optional) - фильтр по статусу: `pending`, `success`, `error`, `in_progress`
- `mb_project_uuid` (optional) - поиск по MB UUID
- `brz_project_id` (optional) - фильтр по Brizy Project ID

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 23131991,
      "mb_project_uuid": "3c56530e-ca31-4a7c-964f-e69be01f382a",
      "brz_project_id": 23131991,
      "created_at": "2025-01-15 10:00:00",
      "updated_at": "2025-01-15 10:05:00",
      "status": "success",
      "changes_json": {...},
      "result": {...}
    }
  ],
  "count": 1
}
```

#### Получить детали миграции
**GET** `/migrations/:id`

**Response:**
```json
{
  "success": true,
  "data": {
    "mapping": {...},
    "result": {...},
    "status": "success"
  }
}
```

#### Запустить миграцию
**POST** `/migrations/run`

**Body (JSON):**
```json
{
  "mb_project_uuid": "3c56530e-ca31-4a7c-964f-e69be01f382a",
  "brz_project_id": 23131991,
  "mb_site_id": 31383,
  "mb_secret": "b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q",
  "brz_workspaces_id": 22925473,
  "mb_page_slug": "home",
  "mgr_manual": 0
}
```

**Обязательные поля:**
- `mb_project_uuid`
- `brz_project_id`
- `mb_site_id`
- `mb_secret`

**Опциональные поля:**
- `brz_workspaces_id`
- `mb_page_slug` - для миграции одной страницы
- `mgr_manual` - флаг ручной миграции (0 или 1)

**Response:**
```json
{
  "success": true,
  "data": {...},
  "http_code": 200
}
```

#### Перезапустить миграцию
**POST** `/migrations/:id/restart`

**Body (JSON):**
```json
{
  "mb_site_id": 31383,
  "mb_secret": "b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q",
  "brz_workspaces_id": 22925473,
  "mb_page_slug": "home",
  "mgr_manual": 0
}
```

**Примечание:** `mb_project_uuid` и `brz_project_id` берутся из существующей миграции, если не указаны в запросе.

#### Получить статус миграции
**GET** `/migrations/:id/status`

**Response:**
```json
{
  "success": true,
  "data": {
    "status": "success",
    "mapping": {...},
    "result": {...}
  }
}
```

### Логи

#### Получить логи миграции
**GET** `/logs/:brz_project_id`

**Response:**
```json
{
  "success": true,
  "data": {
    "migration_id": 23131991,
    "logs": [...]
  }
}
```

#### Получить последние логи
**GET** `/logs/recent`

**Query параметры:**
- `limit` (optional, default: 10) - количество записей

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "mb_project_uuid": "...",
      "brz_project_id": 23131991,
      "migration_uuid": "...",
      "status": "success",
      "created_at": "..."
    }
  ],
  "count": 10
}
```

## Статусы миграций

- `pending` - миграция создана, но еще не запущена
- `in_progress` - миграция выполняется
- `success` - миграция успешно завершена
- `error` - миграция завершилась с ошибкой

## Важные замечания

1. **База данных для записи**: Все операции записи выполняются только в базу `mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com`
2. **Таймаут**: Запросы на запуск миграции имеют таймаут 1 час
3. **CORS**: API поддерживает CORS для фронтенд приложений
