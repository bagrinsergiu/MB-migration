# Логирование модуля Quality Analysis

## Что логируется

Модуль анализа качества миграции логирует все этапы процесса с префиксом `[Quality Analysis]` для удобного поиска в логах.

## Этапы логирования

### 1. Инициализация анализа
```
[Quality Analysis] ===== Starting quality analysis =====
- page_slug
- source_url
- migrated_url
- mb_project_uuid
- brizy_project_id
```

### 2. Захват исходной страницы (Step 1/4)
```
[Quality Analysis] Step 1/4: Capturing source page data
[Quality Analysis] Opening browser for source page
[Quality Analysis] Browser instance created
[Quality Analysis] Page opened, waiting for load
[Quality Analysis] Capturing screenshot
[Quality Analysis] Screenshot captured (размер файла)
[Quality Analysis] Capturing HTML content
[Quality Analysis] HTML content captured (длина HTML)
[Quality Analysis] Browser closed, source page capture completed (время выполнения)
```

### 3. Захват мигрированной страницы (Step 2/4)
```
[Quality Analysis] Step 2/4: Capturing migrated page data
[Quality Analysis] Opening browser for migrated page
... (аналогично шагу 1)
[Quality Analysis] Browser closed, migrated page capture completed
```

### 4. AI анализ (Step 3/4)
```
[Quality Analysis] Step 3/4: Running AI comparison analysis
[Quality Analysis] Starting AI comparison
[Quality Analysis] Encoding source screenshot to base64
[Quality Analysis] Source screenshot encoded (размер)
[Quality Analysis] Encoding migrated screenshot to base64
[Quality Analysis] Migrated screenshot encoded (размер)
[Quality Analysis] Building analysis prompt
[Quality Analysis] Analysis prompt prepared (длина промпта, элементы)
[Quality Analysis] Sending request to OpenAI API
[Quality Analysis] Received response from OpenAI API
[Quality Analysis] Parsing AI response
[Quality Analysis] AI comparison completed successfully (время, оценка, проблемы)
```

### 5. Сохранение результатов (Step 4/4)
```
[Quality Analysis] Step 4/4: Saving quality report to database
[Quality Analysis] Inserting report into database
[Quality Analysis] Quality report saved to database (report_id)
[Quality Analysis] ===== Quality analysis completed successfully =====
```

## Ошибки

При ошибках логируется:
```
[Quality Analysis] ===== Error during quality analysis =====
- error_message
- error_code
- error_file
- error_line
- trace (полный стек вызовов)
```

## Просмотр логов

### В реальном времени:
```bash
tail -f var/log/migration_*.log | grep "Quality Analysis"
```

### Все логи анализа:
```bash
grep "Quality Analysis" var/log/migration_*.log
```

### Конкретный этап:
```bash
# Только этапы
grep "Step.*/" var/log/migration_*.log | grep "Quality Analysis"

# Только ошибки
grep "Error during quality analysis" var/log/migration_*.log

# Только завершенные анализы
grep "Quality analysis completed successfully" var/log/migration_*.log
```

### Статистика по логам:
```bash
# Количество запущенных анализов
grep "Starting quality analysis" var/log/migration_*.log | wc -l

# Количество успешных анализов
grep "Quality analysis completed successfully" var/log/migration_*.log | wc -l

# Количество ошибок
grep "Error during quality analysis" var/log/migration_*.log | wc -l
```

## Примеры логов

### Успешный анализ:
```
[2025-02-15 10:30:15] INFO: [Quality Analysis] ===== Starting quality analysis =====
[2025-02-15 10:30:16] INFO: [Quality Analysis] Step 1/4: Capturing source page data
[2025-02-15 10:30:20] INFO: [Quality Analysis] Screenshot captured (screenshot_size_bytes: 245678)
[2025-02-15 10:30:21] INFO: [Quality Analysis] HTML content captured (html_length: 15234)
[2025-02-15 10:30:22] INFO: [Quality Analysis] Step 2/4: Capturing migrated page data
[2025-02-15 10:30:26] INFO: [Quality Analysis] Screenshot captured (screenshot_size_bytes: 238901)
[2025-02-15 10:30:27] INFO: [Quality Analysis] Step 3/4: Running AI comparison analysis
[2025-02-15 10:30:45] INFO: [Quality Analysis] AI comparison completed successfully (quality_score: 85, severity_level: low)
[2025-02-15 10:30:46] INFO: [Quality Analysis] Step 4/4: Saving quality report to database
[2025-02-15 10:30:46] INFO: [Quality Analysis] Quality report saved to database (report_id: 123)
[2025-02-15 10:30:46] INFO: [Quality Analysis] ===== Quality analysis completed successfully =====
```

### Анализ отключен:
```
[2025-02-15 10:30:15] INFO: [Quality Analysis] Analysis is disabled, skipping page
```

### Ошибка:
```
[2025-02-15 10:30:15] ERROR: [Quality Analysis] ===== Error during quality analysis =====
[2025-02-15 10:30:15] ERROR: [Quality Analysis] error_message: Screenshot file not found
```

## Уровни логирования

- **INFO** - основные этапы процесса, успешные операции
- **DEBUG** - детальная информация о каждом шаге
- **WARNING** - предупреждения (пропуск анализа, отсутствие данных)
- **ERROR** - ошибки (не блокируют миграцию)

## Настройка уровня логирования

В `.env` или конфигурации:
```env
LOG_LEVEL=debug  # Показывает все логи включая DEBUG
LOG_LEVEL=info   # Только INFO и выше (по умолчанию)
LOG_LEVEL=warning # Только WARNING и ERROR
```
