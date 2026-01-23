# Руководство по просмотру скриншотов

## Где сохраняются скриншоты

По умолчанию скриншоты сохраняются в директории:
```
var/tmp/project_{project_id}/
```
(от корня проекта, где `{project_id}` - это ID проекта Brizy)

Полный путь будет примерно:
```
/home/sg/projects/MB-migration/var/tmp/project_12345/
```

Каждый проект имеет свою отдельную директорию для скриншотов, что упрощает их организацию и поиск.

Имена файлов:
- `source_[MD5_HASH_PAGE_SLUG].png` - скриншот исходной страницы (MB)
- `migrated_[MD5_HASH_PAGE_SLUG].png` - скриншот мигрированной страницы (Brizy)

## Просмотр скриншотов через скрипт

### Просмотр всех отчетов по миграции:
```bash
php lib/MBMigration/Analysis/view_screenshots.php [migration_id]
```

Пример:
```bash
php lib/MBMigration/Analysis/view_screenshots.php 12345
```

### Просмотр конкретного отчета по slug страницы:
```bash
php lib/MBMigration/Analysis/view_screenshots.php [migration_id] [page_slug]
```

Пример:
```bash
php lib/MBMigration/Analysis/view_screenshots.php 12345 home-page
```

## Просмотр скриншотов через код

```php
use MBMigration\Analysis\QualityReport;

$reportService = new QualityReport();

// Получить все отчеты по миграции
$reports = $reportService->getReportsByMigration(12345);

foreach ($reports as $report) {
    $screenshots = $report['screenshots_path'];
    
    if (!empty($screenshots)) {
        $sourcePath = $screenshots['source'] ?? null;
        $migratedPath = $screenshots['migrated'] ?? null;
        
        if ($sourcePath && file_exists($sourcePath)) {
            echo "Source screenshot: {$sourcePath}\n";
        }
        
        if ($migratedPath && file_exists($migratedPath)) {
            echo "Migrated screenshot: {$migratedPath}\n";
        }
    }
}

// Получить конкретный отчет
$report = $reportService->getReportBySlug(12345, 'home-page');
if ($report && !empty($report['screenshots_path'])) {
    $sourcePath = $report['screenshots_path']['source'];
    $migratedPath = $report['screenshots_path']['migrated'];
    
    echo "Source: {$sourcePath}\n";
    echo "Migrated: {$migratedPath}\n";
}
```

## Просмотр в логах

При успешном завершении анализа в логах будет запись:
```
[Quality Analysis] ===== Quality analysis completed successfully =====
```

В этой записи будет секция `screenshots` с информацией:
- `source_path` - путь к скриншоту исходной страницы
- `source_exists` - существует ли файл
- `source_size_bytes` - размер файла в байтах
- `migrated_path` - путь к скриншоту мигрированной страницы
- `migrated_exists` - существует ли файл
- `migrated_size_bytes` - размер файла в байтах

## Проблемы с просмотром скриншотов

### Скриншоты не найдены

1. **Проверьте путь в логах** - найдите запись с `screenshots` и проверьте `source_path` и `migrated_path`
2. **Проверьте права доступа** - убедитесь что файлы не были удалены
3. **Проверьте директорию** - по умолчанию `var/tmp/` от корня проекта (например `/home/sg/projects/MB-migration/var/tmp/`)

### Скриншоты не создаются

1. **Проверьте логи** - найдите ошибки при захвате скриншотов
2. **Проверьте BREAKPOINT 6 и 8** - должны быть `screenshot_exists: true`
3. **Проверьте права на запись** - директория должна быть доступна для записи

## Изменение пути сохранения скриншотов

Чтобы изменить путь сохранения скриншотов, передайте путь в конструктор `CapturePageData`:

```php
$customPath = '/path/to/screenshots/';
$captureService = new CapturePageData($customPath);
```

Или установите переменную окружения (если поддерживается):
```bash
export QUALITY_ANALYSIS_SCREENSHOTS_PATH=/path/to/screenshots/
```
