# Тестирование прямо сейчас

## Ваш запрос для тестирования

```
http://localhost:8080/?mb_project_uuid=498c9ed2-a793-4fc0-b6f7-4f7fb349e04f&brz_project_id=23356258&mb_page_slug=faq&mb_site_id=31383&mb_secret=b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q&quality_analysis=true
```

## Как проверить что все работает

### 1. Откройте терминал для просмотра логов

```bash
tail -f var/log/migration_*.log | grep -E "(BREAKPOINT|Quality Analysis)"
```

### 2. Запустите ваш запрос

В другом терминале или браузере выполните ваш GET запрос.

### 3. Следите за breakpoints в логах

Вы увидите последовательность breakpoints:

#### BREAKPOINT 1: Проверка включения анализа
```
[Quality Analysis] ===== BREAKPOINT 1: Checking if quality analysis should run =====
```
**Проверьте:** `quality_analysis_enabled_param: true`

#### BREAKPOINT 2: Данные из кэша
```
[Quality Analysis] ===== BREAKPOINT 2: Retrieved data from cache =====
```
**Проверьте:**
- `source_url` - должен быть URL исходной страницы
- `brizy_project_domain` - должен быть домен Brizy
- `has_source_url: true`
- `has_brizy_domain: true`

#### BREAKPOINT 3: URL мигрированной страницы
```
[Quality Analysis] ===== BREAKPOINT 3: URLs prepared, ready to start analysis =====
```
**Проверьте:**
- `source_url` - правильный URL
- `migrated_url` - правильно сформирован (домен + slug)
- `page_slug: "faq"`

#### BREAKPOINT 5: Начало захвата исходной страницы
```
[Quality Analysis] ===== BREAKPOINT 5: Starting source page capture =====
```
**Проверьте:** `url_valid: true`

#### BREAKPOINT 6: Данные исходной страницы захвачены
```
[Quality Analysis] ===== BREAKPOINT 6: Source page data captured =====
```
**Проверьте:**
- `screenshot_exists: true`
- `screenshot_size` > 0 (например, 245678 байт)
- `has_html: true`
- `html_length` > 0

#### BREAKPOINT 7: Начало захвата мигрированной страницы
```
[Quality Analysis] ===== BREAKPOINT 7: Starting migrated page capture =====
```

#### BREAKPOINT 8: Данные мигрированной страницы захвачены
```
[Quality Analysis] ===== BREAKPOINT 8: Migrated page data captured =====
```
**Проверьте:** Аналогично BREAKPOINT 6

#### BREAKPOINT 9: ⚠️ ВАЖНО! Валидация перед отправкой в AI
```
[Quality Analysis] ===== BREAKPOINT 9: Preparing for AI analysis - DATA VALIDATION =====
```
**Проверьте:**
- `data_ready_for_ai: true` ← **ЭТО ГЛАВНОЕ!**
- `source_data.screenshot_exists: true`
- `source_data.has_html: true`
- `migrated_data.screenshot_exists: true`
- `migrated_data.has_html: true`

**Если `data_ready_for_ai: false` - анализ остановится и НЕ отправит данные в AI!**

#### BREAKPOINT 10: Результат AI анализа
```
[Quality Analysis] ===== BREAKPOINT 10: AI analysis completed =====
```
**Проверьте:**
- `quality_score` (число от 0 до 100)
- `severity_level` (critical/high/medium/low/none)

#### BREAKPOINT 11: Сохранение в БД
```
[Quality Analysis] ===== BREAKPOINT 11: Preparing report data for database =====
```

#### BREAKPOINT 4: Финальный результат
```
[Quality Analysis] ===== BREAKPOINT 4: Analysis completed =====
```
**Проверьте:** `has_report_id: true`

## Быстрая проверка всех breakpoints

```bash
# Все breakpoints за один раз
grep "BREAKPOINT" var/log/migration_*.log | tail -20

# Только валидацию перед AI (самое важное)
grep "BREAKPOINT 9" var/log/migration_*.log | tail -5

# Проверить что данные готовы для AI
grep "data_ready_for_ai" var/log/migration_*.log | tail -5
```

## Что должно быть в логах при успешном тесте

1. ✅ BREAKPOINT 1: `quality_analysis_enabled_param: true`
2. ✅ BREAKPOINT 2: `has_source_url: true`, `has_brizy_domain: true`
3. ✅ BREAKPOINT 3: Оба URL правильные
4. ✅ BREAKPOINT 6: `screenshot_exists: true`, `has_html: true`
5. ✅ BREAKPOINT 8: `screenshot_exists: true`, `has_html: true`
6. ✅ BREAKPOINT 9: `data_ready_for_ai: true` ← **КРИТИЧНО!**
7. ✅ BREAKPOINT 10: `quality_score` присутствует
8. ✅ BREAKPOINT 4: `has_report_id: true`

## Если что-то не работает

### Анализ не запускается
- Проверьте BREAKPOINT 1 - должен быть `quality_analysis_enabled_param: true`
- Проверьте что параметр `quality_analysis=true` в URL

### Нет данных в кэше
- Проверьте BREAKPOINT 2 - должны быть `has_source_url: true` и `has_brizy_domain: true`
- Если false - возможно миграция еще не дошла до этого этапа

### Скриншоты не создаются
- Проверьте BREAKPOINT 6 и 8 - должно быть `screenshot_exists: true`
- Проверьте права на запись в `/tmp/migration_analysis/`

### Данные не готовы для AI
- Проверьте BREAKPOINT 9 - должно быть `data_ready_for_ai: true`
- Если false - проверьте что все 4 условия выполнены (2 скриншота + 2 HTML)

## Временное отключение AI для тестирования

Если хотите протестировать только захват данных БЕЗ отправки в AI, временно закомментируйте в `PageQualityAnalyzer.php` строку:

```php
// $analysisResult = $this->aiService->comparePages($sourceData, $migratedData);
```

И добавьте тестовые данные вместо реального AI запроса.

## Готово к тестированию!

Запускайте ваш запрос и следите за breakpoints в логах. Все должно работать! 🚀
