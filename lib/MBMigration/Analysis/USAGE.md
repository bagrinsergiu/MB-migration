# Использование параметра quality_analysis

## Описание

Параметр `quality_analysis` позволяет включать/выключать автоматический AI-анализ качества миграции страниц.

## Использование в GET запросе

### Включить анализ качества

```
GET /?mb_project_uuid=xxx&brz_project_id=123&quality_analysis=true
```

или

```
GET /?mb_project_uuid=xxx&brz_project_id=123&quality_analysis=1
```

### Выключить анализ качества

```
GET /?mb_project_uuid=xxx&brz_project_id=123&quality_analysis=false
```

или просто не указывать параметр (по умолчанию выключен):

```
GET /?mb_project_uuid=xxx&brz_project_id=123
```

## Логика работы

1. Если параметр `quality_analysis` передан и равен `true` или `1` - анализ включен
2. Если параметр не передан или равен `false` - проверяется переменная окружения `QUALITY_ANALYSIS_ENABLED`
3. Если переменная окружения тоже не установлена - анализ выключен

## Примеры

### Полный запрос с включенным анализом

```
GET /?mb_project_uuid=3c56530e-ca31-4a7c-964f-e69be01f382a&brz_project_id=23131991&brz_workspaces_id=22925473&mb_site_id=31383&mb_secret=xxx&quality_analysis=true
```

### Запрос без анализа (по умолчанию)

```
GET /?mb_project_uuid=3c56530e-ca31-4a7c-964f-e69be01f382a&brz_project_id=23131991&brz_workspaces_id=22925473&mb_site_id=31383&mb_secret=xxx
```

## Примечания

- Анализ выполняется асинхронно и не блокирует процесс миграции
- Если анализ завершится с ошибкой, это не повлияет на результат миграции
- Результаты анализа сохраняются в таблице `page_quality_analysis`
