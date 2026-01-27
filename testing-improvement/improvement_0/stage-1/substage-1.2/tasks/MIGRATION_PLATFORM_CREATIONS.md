# Места создания MigrationPlatform

**Дата анализа**: 2025-01-27  
**Задача**: task-2.3-find-migration-platform-creations.md

## 📋 Обзор

После рефакторинга конструктора `MigrationPlatform` (задача 2.2) конструктор теперь требует два дополнительных параметра:
- `BrizyAPIInterface $brizyApi`
- `MBProjectDataCollectorInterface $mbCollector`

Этот документ содержит полный список всех мест в проекте, где создается объект `MigrationPlatform`, и информацию о том, как обновить каждое место.

---

## 🔍 Результаты поиска

**Команда поиска**: `grep -rn "new MigrationPlatform" lib/ dashboard/ public/`

**Найдено мест создания**: 1

---

## 📝 Места создания MigrationPlatform

### 1. lib/MBMigration/ApplicationBootstrapper.php:303

#### Метод
`doMigration()` - публичный метод класса `ApplicationBootstrapper`

#### Контекст
- Класс: `ApplicationBootstrapper`
- Метод: `doMigration()`
- Строка: 303
- Контекст создания: Создание объекта `MigrationPlatform` для выполнения миграции проекта

#### Текущие параметры конструктора (из кода)
```php
$migrationPlatform = new MigrationPlatform(
    $this->config,           // Config $config
    $logger,                  // LoggerInterface $logger
    $mb_page_slug,           // $buildPage = ''
    $brz_workspaces_id,      // $workspacesId = 0
    $mMgrIgnore,             // bool $mMgrIgnore = true
    $mrgManual,              // $mgr_manual = false
    $qualityAnalysis,        // bool $qualityAnalysis = false
    $mb_element_name,        // string $mb_element_name = ''
    $skip_media_upload,      // bool $skip_media_upload = false
    $skip_cache              // bool $skip_cache = false
);
```

#### Доступные зависимости в методе
- `$this->config` - объект `Config` (уже есть)
- `$logger` - объект `LoggerInterface` (создается в методе через `Logger::initialize()`)
- Другие переменные из параметров метода

#### Зависимости, которые нужно создать

**1. BrizyAPI (BrizyAPIInterface)**
- **Класс**: `MBMigration\Layer\Brizy\BrizyAPI`
- **Реализует**: `MBMigration\Contracts\BrizyAPIInterface`
- **Конструктор**: `BrizyAPI::__construct()` - не принимает параметров
- **Как создать**:
  ```php
  $brizyApi = new \MBMigration\Layer\Brizy\BrizyAPI();
  ```
- **Примечание**: Конструктор `BrizyAPI` не требует параметров, использует `Config` через статические методы

**2. MBProjectDataCollector (MBProjectDataCollectorInterface)**
- **Класс**: `MBMigration\Layer\MB\MBProjectDataCollector`
- **Реализует**: `MBMigration\Contracts\MBProjectDataCollectorInterface`
- **Конструктор**: `MBProjectDataCollector::__construct($projectId = null)` - принимает опциональный `$projectId`
- **Как создать**:
  ```php
  $mbCollector = new \MBMigration\Layer\MB\MBProjectDataCollector();
  ```
  или с projectId (если нужно):
  ```php
  $mbCollector = new \MBMigration\Layer\MB\MBProjectDataCollector($projectId);
  ```
- **Примечание**: В данном контексте можно создать без параметров, так как projectId будет установлен позже через методы класса

#### План обновления

**До рефакторинга**:
```php
$migrationPlatform = new MigrationPlatform(
    $this->config, 
    $logger, 
    $mb_page_slug, 
    $brz_workspaces_id, 
    $mMgrIgnore, 
    $mrgManual, 
    $qualityAnalysis,
    $mb_element_name,
    $skip_media_upload,
    $skip_cache
);
```

**После рефакторинга**:
```php
// Создаем зависимости перед созданием MigrationPlatform
$brizyApi = new \MBMigration\Layer\Brizy\BrizyAPI();
$mbCollector = new \MBMigration\Layer\MB\MBProjectDataCollector();

$migrationPlatform = new MigrationPlatform(
    $this->config, 
    $logger,
    $brizyApi,              // НОВЫЙ параметр: BrizyAPIInterface
    $mbCollector,           // НОВЫЙ параметр: MBProjectDataCollectorInterface
    $mb_page_slug, 
    $brz_workspaces_id, 
    $mMgrIgnore, 
    $mrgManual, 
    $qualityAnalysis,
    $mb_element_name,
    $skip_media_upload,
    $skip_cache
);
```

#### Необходимые use statements

Убедиться, что в начале файла есть:
```php
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Layer\MB\MBProjectDataCollector;
```

#### Примечания
- Зависимости создаются непосредственно перед созданием `MigrationPlatform`
- Оба класса реализуют соответствующие интерфейсы, поэтому их можно передавать в конструктор
- Конструкторы зависимостей не требуют сложных параметров, что упрощает их создание

---

## ✅ Проверка полноты списка

### Дополнительные проверки

1. **Проверка через grep в других директориях**:
   ```bash
   grep -rn "new MigrationPlatform" . --exclude-dir=vendor --exclude-dir=node_modules
   ```
   Результат: Найдено только одно место (уже задокументировано)

2. **Проверка через поиск в Bridge.php**:
   - Bridge использует `ApplicationBootstrapper`, но не создает `MigrationPlatform` напрямую
   - Создание происходит через `ApplicationBootstrapper::doMigration()`

3. **Проверка в public/ и dashboard/**:
   - В `public/` и `dashboard/` не найдено созданий `MigrationPlatform`
   - Все создания происходят через `ApplicationBootstrapper`

### Вывод
**Все места создания MigrationPlatform найдены и задокументированы.**

---

## 📊 Статистика

- **Всего мест создания**: 1
- **Файлов для обновления**: 1
  - `lib/MBMigration/ApplicationBootstrapper.php`

---

## 🎯 Следующие шаги

1. Обновить `ApplicationBootstrapper.php` согласно плану обновления
2. Добавить необходимые use statements
3. Протестировать обновленный код
4. Убедиться, что миграция работает корректно

---

**Анализ выполнен**: 2025-01-27  
**Следующий шаг**: Обновление мест создания MigrationPlatform (задача 2.4)
