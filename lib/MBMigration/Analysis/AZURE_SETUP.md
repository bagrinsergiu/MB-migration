# Настройка Azure OpenAI для анализа качества миграций

## Параметры Azure OpenAI

Добавьте следующие параметры в файл `.env` в корне проекта:

```env
# Azure OpenAI Configuration
AZURE_API_ENDPOINT=https://a-evd-res.openai.azure.com/
AZURE_API_KEY=your-azure-api-key-here
AZURE_API_VERSION=2024-12-01-preview
AZURE_API_MODEL_NAME=gpt-4.1
```

## Приоритет конфигурации

Система проверяет конфигурацию в следующем порядке:

1. **Azure OpenAI** (если все параметры `AZURE_API_*` настроены) - используется по умолчанию
2. **OpenAI** (если настроен `OPENAI_API_KEY`) - используется как fallback
3. **Mock режим** - если ни один API не настроен

## Формат URL для Azure OpenAI

URL формируется автоматически в формате:
```
{endpoint}/openai/deployments/{deployment}/chat/completions?api-version={api-version}
```

Например:
```
https://a-evd-res.openai.azure.com/openai/deployments/gpt-4.1/chat/completions?api-version=2024-12-01-preview
```

## Заголовки запросов

Azure OpenAI использует заголовок `api-key` вместо `Authorization: Bearer`:
```
api-key: your-azure-api-key-here
Content-Type: application/json
```

## Проверка настройки

После добавления параметров в `.env`, проверьте логи при запуске миграции с `quality_analysis=true`:

```
[Quality Analysis] Using Azure OpenAI
endpoint: https://a-evd-res.openai.azure.com/
model: gpt-4.1
api_version: 2024-12-01-preview
```

## Альтернатива: OpenAI

Если хотите использовать стандартный OpenAI вместо Azure, используйте:

```env
OPENAI_API_KEY=sk-your-openai-key-here
OPENAI_MODEL=gpt-4o
```

## Устранение проблем

### Ошибка "No API keys configured"
- Убедитесь что все параметры `AZURE_API_*` добавлены в `.env`
- Проверьте что `.env` файл находится в корне проекта
- Перезапустите PHP-FPM после изменения `.env`

### Ошибка "Invalid response from Azure OpenAI API"
- Проверьте правильность `AZURE_API_ENDPOINT` (должен заканчиваться на `/`)
- Проверьте что `AZURE_API_MODEL_NAME` соответствует имени deployment в Azure
- Проверьте что `AZURE_API_KEY` действителен
- Проверьте что `AZURE_API_VERSION` поддерживается вашим Azure OpenAI ресурсом

### Ошибка "AI analysis failed"
- Проверьте логи для детальной информации об ошибке
- Убедитесь что модель поддерживает vision (GPT-4 Vision или GPT-4.1)
- Проверьте квоты и лимиты в Azure Portal
