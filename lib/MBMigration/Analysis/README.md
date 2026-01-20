# Quality Analysis Module

Модуль для автоматического анализа качества миграции страниц с использованием AI.

## Структура

- `PageQualityAnalyzer.php` - главный класс, оркестрирует процесс анализа
- `CapturePageData.php` - захват скриншотов и HTML исходной и мигрированной страниц
- `AIComparisonService.php` - интеграция с OpenAI GPT-4 Vision API для анализа различий
- `QualityReport.php` - сохранение и получение отчетов о качестве миграции

## Использование

### Автоматический анализ

Анализ запускается автоматически после успешной миграции каждой страницы в `PageController::run()`.

### Ручной запуск

```php
use MBMigration\Analysis\PageQualityAnalyzer;

$analyzer = new PageQualityAnalyzer();
$analyzer->analyzePage(
    $sourceUrl,        // URL исходной страницы (MB)
    $migratedUrl,      // URL мигрированной страницы (Brizy)
    $pageSlug,         // Slug страницы
    $mbProjectUuid,    // UUID проекта MB
    $brizyProjectId    // ID проекта Brizy
);
```

### Получение отчетов

```php
$analyzer = new PageQualityAnalyzer();

// Получить все отчеты по миграции
$reports = $analyzer->getReports($brizyProjectId);

// Получить статистику
$stats = $analyzer->getStatistics($brizyProjectId);
```

## Конфигурация

Добавьте в `.env`:

```env
OPENAI_API_KEY=your_api_key_here
OPENAI_MODEL=gpt-4o
QUALITY_ANALYSIS_ENABLED=true
```

## База данных

Таблица `page_quality_analysis` создается через миграцию Phinx:

```bash
vendor/bin/phinx migrate
```

## Результаты анализа

Каждый анализ сохраняет:
- `quality_score` (0-100) - общая оценка качества
- `severity_level` (critical/high/medium/low/none) - уровень критичности проблем
- `issues_summary` - краткое описание проблем
- `detailed_report` - полный отчет от AI с деталями различий
- `screenshots_path` - пути к скриншотам исходной и мигрированной страниц

## Классификация проблем

- **critical**: отсутствует критический контент, сломанные элементы
- **high**: значительные различия в контенте, отсутствуют важные элементы
- **medium**: небольшие различия, но функциональность сохранена
- **low**: только визуальные различия (отступы, стили)
- **none**: различия минимальны или отсутствуют
