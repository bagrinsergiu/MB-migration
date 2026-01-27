# Места создания PageController

## 🔍 Результаты поиска

**Команда поиска**: `grep -rn "new PageController" lib/ dashboard/ public/`

**Найдено 1 место**:

### 1. `lib/MBMigration/MigrationPlatform.php:317`

- **Метод**: `run()`
- **Контекст**: Создание `PageController` для обработки страниц миграции. Происходит после инициализации QueryBuilder и перед использованием PageController.
- **Текущие параметры**:
  ```php
  $this->parser,
  $this->brizyApi,
  $this->QueryBuilder,
  $this->logger,
  $this->projectID_Brizy,
  $designName,
  $this->qualityAnalysisEnabled,
  $this->mb_element_name,
  $this->skip_media_upload,
  $this->skip_cache
  ```
- **Зависимости, которые нужно создать**:
  - `BrowserInterface` (реализуется через `BrowserPHP::instance($layoutBasePath)`)
    - Требует: `$layoutBasePath` - путь к директории Layout (вычисляется как `dirname(__FILE__)."/Layout"` относительно PageController.php, т.е. `lib/MBMigration/Builder/Layout`)
  - `FontsController` (реализуется через `new FontsController($brizyContainerId)`)
    - Требует: `$brizyContainerId` - ID контейнера Brizy (доступен через `$this->cache->get('container')`)

#### План обновления

**До рефакторинга**:
```php
$this->pageController = new PageController(
    $this->parser,
    $this->brizyApi,
    $this->QueryBuilder,
    $this->logger,
    $this->projectID_Brizy,
    $designName,
    $this->qualityAnalysisEnabled,
    $this->mb_element_name,
    $this->skip_media_upload,
    $this->skip_cache
);
```

**После рефакторинга**:
```php
// Создаем зависимости для PageController (рефакторинг для тестируемости)
// Эти зависимости теперь инжектируются через конструктор вместо создания внутри класса
$brizyContainerId = $this->cache->get('container');
$layoutBasePath = dirname(__DIR__) . '/Builder/Layout'; // Путь к lib/MBMigration/Builder/Layout

$browser = \MBMigration\Browser\BrowserPHP::instance($layoutBasePath);
$fontsController = new \MBMigration\Builder\Fonts\FontsController($brizyContainerId);

$this->pageController = new PageController(
    $this->parser,
    $this->brizyApi,
    $this->QueryBuilder,
    $this->logger,
    $browser,              // НОВЫЙ параметр: BrowserInterface
    $fontsController,      // НОВЫЙ параметр: FontsController
    $this->projectID_Brizy,
    $designName,
    $this->qualityAnalysisEnabled,
    $this->mb_element_name,
    $this->skip_media_upload,
    $this->skip_cache
);
```

#### Необходимые use statements
```php
use MBMigration\Browser\BrowserPHP;
use MBMigration\Builder\Fonts\FontsController;
```

#### Примечания
- `$brizyContainerId` уже установлен в кэш на строке 281-282 метода `run()`
- `$layoutBasePath` вычисляется относительно файла PageController.php, который находится в `lib/MBMigration/Builder/`
- Из MigrationPlatform (который находится в `lib/MBMigration/`) путь к Layout будет: `dirname(__DIR__) . '/Builder/Layout'` или можно использовать `__DIR__ . '/Builder/Layout'`
