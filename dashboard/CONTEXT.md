# Контекст Dashboard API

## Обзор проекта

Dashboard API - это веб-панель управления для системы миграции проектов из Ministry Brands в Brizy. API предоставляет RESTful endpoints для управления миграциями, просмотра логов и мониторинга статусов.

## Архитектура

### Backend (PHP)
- **Фреймворк**: Symfony HTTP Foundation
- **База данных**: MySQL через PDO
- **Автозагрузка**: PSR-0 + custom autoloader для Dashboard namespace
- **Маршрутизация**: Custom routing в `public/index.php`

### Ключевые компоненты

#### DatabaseService
- Управление подключением к БД
- **КРИТИЧЕСКАЯ ФУНКЦИЯ**: Проверка хоста перед записью
- Методы для работы с `migrations_mapping` и `migration_result_list`

#### ApiProxyService
- Проксирование запросов к существующему API миграций
- Обработка ответов в формате `{"value": {...}}`
- Сохранение результатов в БД

#### MigrationService
- Бизнес-логика для работы с миграциями
- Объединение данных из разных таблиц
- Определение статусов миграций

#### MigrationController
- REST API endpoints для миграций
- Валидация входных данных
- Обработка ошибок

#### LogController
- Просмотр логов миграций
- Получение последних логов

## База данных

### Подключение
- Использует переменные окружения `MG_DB_*`
- Хост для записи жестко проверяется: `mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com`
- При неверном хосте выбрасывается исключение

### Таблицы

#### migrations_mapping
- `brz_project_id` (int) - ID проекта в Brizy
- `mb_project_uuid` (string) - UUID проекта в Ministry Brands
- `changes_json` (text) - JSON с метаданными
- `created_at`, `updated_at` - временные метки

#### migration_result_list
- `migration_uuid` (string) - уникальный ID миграции
- `brz_project_id` (int) - ID проекта Brizy
- `brizy_project_domain` (string) - домен проекта
- `mb_project_uuid` (string) - UUID проекта MB
- `result_json` (text) - полный результат миграции в JSON

## Формат данных

### Запуск миграции (POST /migrations/run)
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

### Ответ миграции
```json
{
  "value": {
    "status": "success",
    "brizy_project_id": 23131991,
    "mb_uuid": "3c56530e-ca31-4a7c-964f-e69be01f382a",
    "progress": {
      "Total": 18,
      "Success": 16,
      "processTime": 592.4
    }
  }
}
```

### Статусы миграций
- `pending` - создана, но не запущена
- `in_progress` - выполняется
- `success` - успешно завершена
- `error` - завершилась с ошибкой

## Безопасность

1. **Проверка хоста БД**: Строгая валидация перед записью
2. **Валидация входных данных**: Все параметры проверяются
3. **Prepared statements**: Используются для всех SQL запросов
4. **CORS**: Настроен для фронтенд приложений

## Интеграция с существующим кодом

- Использует классы из `MBMigration\` namespace
- Проксирует запросы к существующему API в `public/index.php`
- Сохраняет результаты в те же таблицы БД

## Текущий статус

✅ **Работает:**
- Health check
- Список миграций с фильтрами
- Детали миграции
- Статус миграции
- Последние логи
- Подключение к БД через .env

⏳ **В разработке:**
- React фронтенд
- Аутентификация
- WebSocket для real-time

## Файлы для редактирования

### При изменении API endpoints:
- `dashboard/api/index.php` - маршрутизация
- `dashboard/api/controllers/*.php` - контроллеры

### При изменении логики:
- `dashboard/api/services/*.php` - бизнес-логика

### При изменении БД:
- `dashboard/api/services/DatabaseService.php` - методы работы с БД

## Тестирование

### Проверка работы:
```bash
# Health check
curl http://localhost:8080/dashboard/api/health

# Список миграций
curl http://localhost:8080/dashboard/api/migrations

# С фильтром
curl "http://localhost:8080/dashboard/api/migrations?status=success"

# Детали миграции
curl http://localhost:8080/dashboard/api/migrations/22926177
```

## Важные файлы

- `dashboard/api/index.php` - точка входа API
- `dashboard/api/services/DatabaseService.php` - работа с БД
- `public/index.php` - маршрутизация (строки 20-23)
- `dashboard/API.md` - документация API
- `dashboard/IMPORTANT.md` - критически важная информация
