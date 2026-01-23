# Настройка AI анализа качества миграции

## Шаг 1: Настройка OpenAI API

1. Получите API ключ от OpenAI: https://platform.openai.com/api-keys
2. Добавьте в файл `.env`:

```env
OPENAI_API_KEY=sk-your-api-key-here
OPENAI_MODEL=gpt-4o
QUALITY_ANALYSIS_ENABLED=true
```

## Шаг 2: Создание таблицы в БД

Выполните миграцию Phinx:

```bash
vendor/bin/phinx migrate
```

Это создаст таблицу `page_quality_analysis` в базе данных.

## Шаг 3: Проверка работы

После настройки анализ будет запускаться автоматически после каждой успешной миграции страницы.

Для проверки можно посмотреть логи:
```bash
tail -f var/log/migration.log | grep "quality analysis"
```

## Отключение анализа

Если нужно временно отключить анализ, установите в `.env`:
```env
QUALITY_ANALYSIS_ENABLED=false
```

## Просмотр результатов

Результаты сохраняются в таблице `page_quality_analysis`. Можно запросить их через:

```php
use MBMigration\Analysis\PageQualityAnalyzer;

$analyzer = new PageQualityAnalyzer();
$reports = $analyzer->getReports($brizyProjectId);
$stats = $analyzer->getStatistics($brizyProjectId);
```

## Структура результатов

Каждая запись содержит:
- `quality_score` (0-100) - оценка качества
- `severity_level` - уровень критичности (critical/high/medium/low/none)
- `issues_summary` - краткое описание проблем (JSON)
- `detailed_report` - полный отчет от AI (JSON)
- `screenshots_path` - пути к скриншотам (JSON)

## Стоимость

Использование OpenAI GPT-4 Vision API платное. Примерная стоимость:
- GPT-4o: ~$0.01-0.03 за анализ одной страницы (зависит от размера скриншотов)
- GPT-4 Turbo: ~$0.02-0.05 за анализ одной страницы

Рекомендуется начать с небольшого количества страниц для оценки стоимости.
