# Dashboard API - Быстрый старт

## Доступ к дашборду

- **HTML страница**: http://localhost:8080/dashboard
- **API**: http://localhost:8080/dashboard/api

## Основные команды

### Проверка работоспособности
```bash
curl http://localhost:8080/dashboard/api/health
```

### Получить список миграций
```bash
curl http://localhost:8080/dashboard/api/migrations
```

### Получить успешные миграции
```bash
curl "http://localhost:8080/dashboard/api/migrations?status=success"
```

### Получить детали миграции
```bash
curl http://localhost:8080/dashboard/api/migrations/22926177
```

### Запустить миграцию
```bash
curl -X POST http://localhost:8080/dashboard/api/migrations/run \
  -H "Content-Type: application/json" \
  -d '{
    "mb_project_uuid": "3c56530e-ca31-4a7c-964f-e69be01f382a",
    "brz_project_id": 23131991,
    "mb_site_id": 31383,
    "mb_secret": "b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q"
  }'
```

### Получить последние логи
```bash
curl http://localhost:8080/dashboard/api/logs/recent
```

## Важные файлы

- `dashboard/IMPORTANT.md` - Критически важная информация
- `dashboard/CONTEXT.md` - Контекст проекта
- `dashboard/API.md` - Полная документация API
- `dashboard/TEST_RESULTS.md` - Результаты тестирования

## Структура ответов

### Успешный ответ
```json
{
  "success": true,
  "data": {...}
}
```

### Ошибка
```json
{
  "success": false,
  "error": "Описание ошибки"
}
```

## Статусы миграций

- `pending` - Ожидает запуска
- `in_progress` - Выполняется
- `success` - Успешно завершена
- `error` - Завершилась с ошибкой
