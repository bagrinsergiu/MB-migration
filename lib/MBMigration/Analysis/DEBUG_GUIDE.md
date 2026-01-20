# Руководство по отладке Quality Analysis

## Breakpoints для отладки

В коде добавлены breakpoints с префиксом `BREAKPOINT` для удобной отладки. Каждый breakpoint логирует детальную информацию о состоянии данных.

## Список Breakpoints

### BREAKPOINT 1: Проверка включения анализа
**Файл:** `PageController.php::runQualityAnalysis()`
**Что проверяет:**
- Включен ли анализ через параметр
- Значение переменной окружения
- Доступные ключи в кэше

**Что смотреть в логах:**
```json
{
  "quality_analysis_enabled_param": true/false,
  "env_quality_analysis_enabled": "true"/"false"/"not_set"
}
```

### BREAKPOINT 2: Данные из кэша
**Файл:** `PageController.php::runQualityAnalysis()`
**Что проверяет:**
- URL исходной страницы
- Домен Brizy проекта
- UUID проекта MB
- Наличие всех необходимых данных

**Что смотреть в логах:**
```json
{
  "source_url": "http://...",
  "brizy_project_domain": "https://...",
  "mb_project_uuid": "...",
  "has_source_url": true,
  "has_brizy_domain": true
}
```

### BREAKPOINT 3: Формирование URL мигрированной страницы
**Файл:** `PageController.php::runQualityAnalysis()`
**Что проверяет:**
- Правильность формирования URL мигрированной страницы
- Все параметры перед запуском анализа

**Что смотреть в логах:**
```json
{
  "source_url": "http://...",
  "migrated_url": "https://...",
  "page_slug": "faq"
}
```

### BREAKPOINT 4: Результат анализа
**Файл:** `PageController.php::runQualityAnalysis()`
**Что проверяет:**
- Успешно ли завершился анализ
- ID созданного отчета

### BREAKPOINT 5: Начало захвата исходной страницы
**Файл:** `PageQualityAnalyzer.php::analyzePage()`
**Что проверяет:**
- Валидность URL исходной страницы
- Готовность к захвату

### BREAKPOINT 6: Данные исходной страницы захвачены
**Файл:** `PageQualityAnalyzer.php::analyzePage()`
**Что проверяет:**
- Существование скриншота
- Размер скриншота
- Наличие HTML
- Длина HTML
- Превью HTML (первые 200 символов)

**Что смотреть в логах:**
```json
{
  "screenshot_exists": true,
  "screenshot_size": 245678,
  "html_length": 15234,
  "has_screenshot": true,
  "has_html": true
}
```

### BREAKPOINT 7: Начало захвата мигрированной страницы
**Файл:** `PageQualityAnalyzer.php::analyzePage()`
**Что проверяет:**
- Валидность URL мигрированной страницы

### BREAKPOINT 8: Данные мигрированной страницы захвачены
**Файл:** `PageQualityAnalyzer.php::analyzePage()`
**Что проверяет:**
- Аналогично BREAKPOINT 6, но для мигрированной страницы

### BREAKPOINT 9: ВАЖНО! Валидация данных перед отправкой в AI
**Файл:** `PageQualityAnalyzer.php::analyzePage()`
**Что проверяет:**
- Все данные на месте перед отправкой в OpenAI
- Существование обоих скриншотов
- Наличие HTML обеих страниц
- Размеры файлов

**Что смотреть в логах:**
```json
{
  "data_ready_for_ai": true,
  "source_data": {
    "screenshot_exists": true,
    "screenshot_size": 245678,
    "has_html": true
  },
  "migrated_data": {
    "screenshot_exists": true,
    "screenshot_size": 238901,
    "has_html": true
  }
}
```

**Если `data_ready_for_ai: false` - НЕ БУДЕТ отправки в AI!**

### BREAKPOINT 10: Результат AI анализа
**Файл:** `PageQualityAnalyzer.php::analyzePage()`
**Что проверяет:**
- Оценка качества
- Уровень критичности
- Количество проблем
- Превью summary

### BREAKPOINT 11: Подготовка данных для БД
**Файл:** `PageQualityAnalyzer.php::analyzePage()`
**Что проверяет:**
- Данные перед сохранением в БД

## Как использовать для отладки

### 1. Запустить миграцию с анализом:
```bash
curl "http://localhost:8080/?mb_project_uuid=498c9ed2-a793-4fc0-b6f7-4f7fb349e04f&brz_project_id=23356258&mb_page_slug=faq&mb_site_id=31383&mb_secret=b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q&quality_analysis=true"
```

### 2. Смотреть логи в реальном времени:
```bash
tail -f var/log/migration_*.log | grep -E "(BREAKPOINT|Quality Analysis)"
```

### 3. Проверить конкретный breakpoint:
```bash
# BREAKPOINT 9 - валидация перед AI
grep "BREAKPOINT 9" var/log/migration_*.log

# BREAKPOINT 6 - данные исходной страницы
grep "BREAKPOINT 6" var/log/migration_*.log
```

### 4. Проверить что данные готовы для AI:
```bash
grep "data_ready_for_ai" var/log/migration_*.log
```

Если видите `"data_ready_for_ai": true` - все данные на месте и можно отправлять в AI.

## Проверка перед отправкой в AI

Перед отправкой в AI проверьте в логах BREAKPOINT 9:

✅ **Все должно быть true:**
- `source_data.screenshot_exists: true`
- `source_data.has_html: true`
- `migrated_data.screenshot_exists: true`
- `migrated_data.has_html: true`
- `data_ready_for_ai: true`

❌ **Если что-то false - анализ остановится с ошибкой**

## Временное отключение AI для тестирования

Чтобы протестировать только захват данных без отправки в AI, можно временно закомментировать вызов AI в `PageQualityAnalyzer.php`:

```php
// Временно отключаем AI для тестирования
// $analysisResult = $this->aiService->comparePages($sourceData, $migratedData);

// Используем тестовые данные
$analysisResult = [
    'quality_score' => 85,
    'severity_level' => 'low',
    'summary' => 'Test analysis - AI disabled',
    'issues' => [],
    'missing_elements' => [],
    'changed_elements' => []
];
```

## Поиск проблем

### Проблема: Анализ не запускается
1. Проверьте BREAKPOINT 1 - включен ли анализ
2. Проверьте BREAKPOINT 2 - есть ли данные в кэше

### Проблема: Нет скриншотов
1. Проверьте BREAKPOINT 6 и 8 - существуют ли файлы
2. Проверьте права на запись в директорию скриншотов

### Проблема: Ошибка перед отправкой в AI
1. Проверьте BREAKPOINT 9 - все ли данные на месте
2. Проверьте `data_ready_for_ai: true`

### Проблема: Ошибка AI анализа
1. Проверьте BREAKPOINT 10 - что вернул AI
2. Проверьте наличие API ключа OpenAI
