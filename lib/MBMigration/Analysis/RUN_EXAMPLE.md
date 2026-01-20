# Пример запуска миграции с анализом качества

## Быстрый старт

### 1. Включить анализ через GET параметр

```bash
curl "http://localhost:8080/?mb_project_uuid=YOUR_UUID&brz_project_id=YOUR_ID&quality_analysis=true&mb_site_id=31383&mb_secret=YOUR_SECRET&brz_workspaces_id=YOUR_WORKSPACE_ID"
```

### 2. Или через переменную окружения

В `.env`:
```env
QUALITY_ANALYSIS_ENABLED=true
OPENAI_API_KEY=your-api-key-here
OPENAI_MODEL=gpt-4o
```

Затем запустить миграцию как обычно:
```bash
curl "http://localhost:8080/?mb_project_uuid=YOUR_UUID&brz_project_id=YOUR_ID&mb_site_id=31383&mb_secret=YOUR_SECRET&brz_workspaces_id=YOUR_WORKSPACE_ID"
```

## Что происходит при запуске

1. **Миграция страницы** - обычный процесс миграции
2. **После успешной миграции** - автоматически запускается анализ качества
3. **В логах** - появляются записи с префиксом `[Quality Analysis]`

## Просмотр результатов в реальном времени

### Открыть терминал и следить за логами:
```bash
tail -f var/log/migration_*.log | grep -E "(Quality Analysis|Step|completed|Error)"
```

### Или все логи анализа:
```bash
tail -f var/log/migration_*.log | grep "Quality Analysis"
```

## Ожидаемый вывод в логах

```
[2025-02-15 10:30:15] INFO: [Quality Analysis] ===== Starting quality analysis =====
[2025-02-15 10:30:15] INFO: [Quality Analysis] Step 1/4: Capturing source page data
[2025-02-15 10:30:20] INFO: [Quality Analysis] Screenshot captured (screenshot_size_bytes: 245678)
[2025-02-15 10:30:22] INFO: [Quality Analysis] Step 2/4: Capturing migrated page data
[2025-02-15 10:30:27] INFO: [Quality Analysis] Step 3/4: Running AI comparison analysis
[2025-02-15 10:30:45] INFO: [Quality Analysis] AI comparison completed successfully (quality_score: 85)
[2025-02-15 10:30:46] INFO: [Quality Analysis] Step 4/4: Saving quality report to database
[2025-02-15 10:30:46] INFO: [Quality Analysis] ===== Quality analysis completed successfully =====
```

## Проверка результатов в БД

После завершения анализа результаты сохраняются в таблице `page_quality_analysis`:

```sql
SELECT 
    page_slug,
    quality_score,
    severity_level,
    analysis_status,
    created_at
FROM page_quality_analysis
WHERE migration_id = YOUR_BRIZY_PROJECT_ID
ORDER BY created_at DESC;
```

## Отключение анализа

Если нужно отключить анализ для конкретной миграции:
```bash
curl "http://localhost:8080/?mb_project_uuid=YOUR_UUID&brz_project_id=YOUR_ID&quality_analysis=false&..."
```

Или не указывать параметр вообще (по умолчанию выключен).

## Важные замечания

1. **Анализ не блокирует миграцию** - если анализ завершится с ошибкой, миграция продолжится
2. **Время выполнения** - анализ добавляет ~30-60 секунд на каждую страницу
3. **Стоимость** - каждый анализ использует OpenAI API (примерно $0.01-0.03 за страницу)
4. **Скриншоты** - сохраняются во временной директории (автоматически очищаются)

## Устранение проблем

### Анализ не запускается
- Проверьте параметр `quality_analysis=true` в запросе
- Проверьте переменную `QUALITY_ANALYSIS_ENABLED` в `.env`
- Проверьте логи на наличие ошибок

### Ошибка "OpenAI API key is not configured"
- Добавьте `OPENAI_API_KEY` в `.env`
- Или передайте ключ через переменную окружения

### Ошибка "Screenshot file not found"
- Проверьте доступность исходной и мигрированной страниц
- Проверьте права на запись в директорию для скриншотов

### Анализ работает слишком долго
- Это нормально, анализ занимает 30-60 секунд на страницу
- Можно отключить для тестовых миграций
