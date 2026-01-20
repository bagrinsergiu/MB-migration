# ⚠️ ВАЖНО: Удалить временную заглушку OpenAI API

## Где находится заглушка

**Файл:** `lib/MBMigration/Analysis/AIComparisonService.php`

## Что нужно сделать

### 1. Удалить проверку мока в конструкторе

Найти и удалить этот блок (строки ~27-35):
```php
// TODO: TEMPORARY MOCK - REMOVE THIS AFTER SETTING UP REAL OpenAI API KEY
// Временная заглушка для тестирования без реального API ключа
// УДАЛИТЬ ЭТОТ БЛОК ПОСЛЕ НАСТРОЙКИ РЕАЛЬНОГО OPENAI API KEY
$useMock = empty($this->apiKey) || $this->apiKey === 'MOCK_FOR_TESTING';
if ($useMock) {
    Logger::instance()->warning("[Quality Analysis] ⚠️ USING MOCK AI SERVICE - No real OpenAI API calls will be made!");
    Logger::instance()->warning("[Quality Analysis] ⚠️ TODO: Set OPENAI_API_KEY in .env file and remove mock code in AIComparisonService.php");
    $this->apiKey = 'MOCK_FOR_TESTING'; // Устанавливаем маркер для использования мока
}
```

И вернуть оригинальную проверку:
```php
if (empty($this->apiKey)) {
    throw new Exception('OpenAI API key is not configured. Set OPENAI_API_KEY in .env file.');
}
```

### 2. Удалить проверку мока в методе comparePages

Найти и удалить этот блок (в начале метода comparePages):
```php
// TODO: TEMPORARY MOCK - REMOVE THIS AFTER SETTING UP REAL OpenAI API KEY
// Временная заглушка для тестирования без реального API ключа
if ($this->apiKey === 'MOCK_FOR_TESTING' || empty($this->apiKey)) {
    Logger::instance()->warning("[Quality Analysis] ⚠️ USING MOCK AI RESPONSE - Returning test data");
    return $this->getMockAnalysisResult($sourceData, $migratedData);
}
```

### 3. Удалить метод getMockAnalysisResult

Найти и удалить весь метод `getMockAnalysisResult()` в конце класса (строки ~280-350).

### 4. Восстановить создание HTTP клиента

Убедиться что HTTP клиент создается всегда (не только если не мок):
```php
$this->httpClient = new Client([
    'timeout' => 120,
    'headers' => [
        'Authorization' => 'Bearer ' . $this->apiKey,
        'Content-Type' => 'application/json',
    ]
]);
```

## Как найти все места

Выполните поиск в файле `AIComparisonService.php`:
```bash
grep -n "TODO.*MOCK\|MOCK_FOR_TESTING\|getMockAnalysisResult" lib/MBMigration/Analysis/AIComparisonService.php
```

## Перед удалением

1. Убедитесь что у вас есть реальный OpenAI API ключ
2. Добавьте его в `.env`: `OPENAI_API_KEY=your-real-key-here`
3. Протестируйте с реальным ключом
4. Только после этого удаляйте заглушку

## После удаления

Убедитесь что:
- ✅ Реальный API ключ работает
- ✅ Анализ отправляет запросы в OpenAI
- ✅ Получает реальные результаты анализа
- ✅ Нет ошибок в логах

## Маркеры для поиска

Ищите в коде:
- `TODO: TEMPORARY MOCK`
- `MOCK_FOR_TESTING`
- `getMockAnalysisResult`
- `⚠️ USING MOCK`

Все эти места нужно удалить или исправить!
