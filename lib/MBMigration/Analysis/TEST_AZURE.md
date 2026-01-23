# Тестирование Azure OpenAI

## Быстрый тест

### Вариант 1: Через реальную миграцию (рекомендуется)

1. Убедитесь что параметры добавлены в `.env`:
```env
AZURE_API_ENDPOINT=https://a-evd-res.openai.azure.com/
AZURE_API_KEY=your-key
AZURE_API_VERSION=2024-12-01-preview
AZURE_API_MODEL_NAME=gpt-4.1
```

2. Запустите миграцию с анализом качества:
```bash
curl -X POST http://localhost:8080/dashboard/api/migrations/run \
  -H "Content-Type: application/json" \
  -d '{
    "mb_project_uuid": "your-uuid",
    "brz_project_id": 12345,
    "mb_site_id": 31383,
    "mb_secret": "your-secret",
    "quality_analysis": true
  }'
```

3. Проверьте логи:
```bash
tail -f var/log/*.log | grep "Quality Analysis"
```

Должны увидеть:
- `[Quality Analysis] Using Azure OpenAI` - подключение работает
- `[Quality Analysis] Sending request to Azure OpenAI API` - запрос отправлен
- `[Quality Analysis] Received response from Azure OpenAI API` - ответ получен

### Вариант 2: Через тестовый скрипт

Запустите тестовый скрипт (требует PHP 7.4+):
```bash
php lib/MBMigration/Analysis/test_azure_openai.php
```

## Проверка поддержки Vision API

**⚠️ ВАЖНО:** Модель `gpt-4.1` может **НЕ поддерживать Vision API**!

Если при тестировании вы получите ошибку типа:
```
The model 'gpt-4.1' does not support the 'image_url' content type
```

Это означает, что модель не может работать с изображениями и **не подходит** для анализа качества миграции.

## Что делать если модель не поддерживает Vision API?

### Вариант 1: Использовать другую модель Azure OpenAI

В `.env` измените:
```env
AZURE_API_MODEL_NAME=gpt-4-vision-preview
# или
AZURE_API_MODEL_NAME=gpt-4o
# или
AZURE_API_MODEL_NAME=gpt-4-turbo
```

### Вариант 2: Использовать стандартный OpenAI

В `.env` закомментируйте Azure параметры и используйте:
```env
# AZURE_API_ENDPOINT=...
# AZURE_API_KEY=...
# AZURE_API_VERSION=...
# AZURE_API_MODEL_NAME=...

OPENAI_API_KEY=sk-your-openai-key
OPENAI_MODEL=gpt-4o
```

## Проверка доступных моделей в Azure

1. Откройте [Azure Portal](https://portal.azure.com)
2. Перейдите в ваш Azure OpenAI ресурс
3. Откройте раздел **"Deployments"** или **"Models"**
4. Найдите модели с пометкой **"Vision"** или проверьте документацию

## Подходит ли gpt-4.1 для задачи?

**Для анализа качества миграции страниц нужна модель с Vision API**, так как анализ включает:
- ✅ Сравнение скриншотов исходной и мигрированной страниц
- ✅ Визуальный анализ различий
- ✅ Определение отсутствующих элементов

**gpt-4.1 подходит ТОЛЬКО если она поддерживает Vision API.**

### Как проверить:

1. Запустите тест (см. выше)
2. Если тест с изображением проходит успешно - модель подходит ✅
3. Если получаете ошибку о неподдержке `image_url` - модель НЕ подходит ❌

## Рекомендуемые модели

Для анализа качества миграции лучше всего подходят:

1. **gpt-4o** - лучший выбор, оптимизирована для Vision
2. **gpt-4-vision-preview** - специально для работы с изображениями
3. **gpt-4-turbo** - хороший баланс качества и скорости

## Устранение проблем

### Ошибка "No API keys configured"
- Проверьте что все параметры `AZURE_API_*` добавлены в `.env`
- Убедитесь что `.env` находится в корне проекта
- Перезапустите PHP-FPM после изменения `.env`

### Ошибка "Invalid response from Azure OpenAI API"
- Проверьте правильность `AZURE_API_ENDPOINT` (должен заканчиваться на `/`)
- Проверьте что `AZURE_API_MODEL_NAME` соответствует имени deployment в Azure
- Проверьте что `AZURE_API_KEY` действителен
- Проверьте что модель развернута в вашем Azure ресурсе

### Ошибка "The model does not support the 'image_url' content type"
- Модель не поддерживает Vision API
- Используйте модель с поддержкой Vision (см. рекомендации выше)
