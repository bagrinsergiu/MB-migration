# Анализ класса PageController

## Конструктор

- **Сигнатура**: 
  ```php
  public function __construct(
      MBProjectDataCollector $MBProjectDataCollector,
      BrizyAPI $brizyAPI,
      QueryBuilder $QueryBuilder,
      LoggerInterface $logger,
      $projectID_Brizy,
      $designName = null,
      bool $qualityAnalysis = false,
      string $mb_element_name = '',
      bool $skip_media_upload = false,
      bool $skip_cache = false
  )
  ```

- **Параметры**:
  1. `MBProjectDataCollector $MBProjectDataCollector` - коллектор данных проекта MB
  2. `BrizyAPI $brizyAPI` - API для работы с Brizy
  3. `QueryBuilder $QueryBuilder` - построитель запросов
  4. `LoggerInterface $logger` - логгер (PSR-3)
  5. `$projectID_Brizy` - ID проекта Brizy (без типа)
  6. `$designName = null` - имя дизайна (по умолчанию null)
  7. `bool $qualityAnalysis = false` - включить анализ качества
  8. `string $mb_element_name = ''` - имя элемента для фильтрации
  9. `bool $skip_media_upload = false` - пропустить загрузку медиа
  10. `bool $skip_cache = false` - пропустить использование кэша

- **Создает ли зависимости**: Да, создает несколько зависимостей:
  - `VariableCache::getInstance()` (строка 114)
  - `new ArrayManipulator()` (строка 115)
  - `new PageDTO()` (строка 116) - для `$pageDTO`
  - `new PageDTO()` (строка 117) - для `$projectStyleDTO` (свойство не объявлено явно)

## Места создания зависимостей

### В конструкторе (строки 114-117)

1. **Строка 114**: `$this->cache = VariableCache::getInstance();`
   - Создает экземпляр `VariableCache` через статический метод `getInstance()`
   - Сохраняется в свойство `$cache`

2. **Строка 115**: `$this->ArrayManipulator = new ArrayManipulator();`
   - Создает новый экземпляр `ArrayManipulator`
   - Сохраняется в свойство `$ArrayManipulator`

3. **Строка 116**: `$this->pageDTO = new PageDTO();`
   - Создает новый экземпляр `PageDTO`
   - Сохраняется в свойство `$pageDTO`

4. **Строка 117**: `$this->projectStyleDTO = new PageDTO();`
   - Создает новый экземпляр `PageDTO`
   - Сохраняется в свойство `$projectStyleDTO` (свойство не объявлено явно в классе)

### В методе run() (строки 134-339)

1. **Строка 142**: `$fontController = new FontsController($brizyContainerId);`
   - Создает новый экземпляр `FontsController`
   - Параметры: `$brizyContainerId` (получается из кэша)
   - Локальная переменная, не сохраняется в свойство

2. **Строка 150, 158, 183, 285, 291, 302, 303, 309, 316, 330, 331**: `Logger::instance()->...`
   - Множественные вызовы статического метода `Logger::instance()`
   - Используется для логирования различных событий
   - **Примечание**: В классе уже есть свойство `$logger` типа `LoggerInterface`, но используется статический метод `Logger::instance()` вместо него

3. **Строка 167**: `$_WorkClassTemplate = new $workClass();`
   - Динамическое создание объекта через переменную класса
   - `$workClass = __NAMESPACE__.'\\Layout\\Theme\\'.$design.'\\'.$design;` (строка 166)
   - Создает экземпляр класса темы на основе дизайна

4. **Строка 177**: `$this->browser = BrowserPHP::instance($layoutBasePath);`
   - Создает экземпляр `BrowserPHP` через статический метод `instance()`
   - Параметры: `$layoutBasePath` (путь к директории Layout)
   - Сохраняется в свойство `$browser`

5. **Строка 190**: `$this->browser = BrowserPHP::instance($layoutBasePath);`
   - Повторное создание `BrowserPHP` в блоке catch (при ошибке)
   - Те же параметры, что и в строке 177

6. **Строка 198**: `$brizyKit = (new KitLoader($layoutBasePath))->loadKit($design);`
   - Создает новый экземпляр `KitLoader`
   - Параметры: `$layoutBasePath`
   - Вызывает метод `loadKit($design)` сразу после создания
   - Локальная переменная

7. **Строка 199**: `$layoutElementFactory = new LayoutElementFactory(...);`
   - Создает новый экземпляр `LayoutElementFactory`
   - Параметры:
     - `$brizyKit` (результат из строки 198)
     - `$browserPage` (результат `$this->browser->openPage()`)
     - `$queryBuilder` (из кэша)
     - `$this->brizyAPI`
     - `$fontController` (из строки 142)
   - Локальная переменная

8. **Строка 213**: `$RootPalettesExtracted = new RootPalettesExtractor($browserPage);`
   - Создает новый экземпляр `RootPalettesExtractor`
   - Параметры: `$browserPage`
   - Локальная переменная

9. **Строка 214**: `$RootListFontFamilyExtractor = new RootListFontFamilyExtractor($browserPage);`
   - Создает новый экземпляр `RootListFontFamilyExtractor`
   - Параметры: `$browserPage`
   - Локальная переменная

10. **Строка 224**: `$themeContext = new ThemeContext(...);`
    - Создает новый экземпляр `ThemeContext`
    - Параметры: множество параметров (дизайн, browserPage, brizyKit, меню, шрифты, и т.д.)
    - Локальная переменная

11. **Строка 960**: `$analyzer = new PageQualityAnalyzer($this->qualityAnalysisEnabled);`
    - Создает новый экземпляр `PageQualityAnalyzer`
    - Параметры: `$this->qualityAnalysisEnabled`
    - Локальная переменная
    - Создается в методе `runQualityAnalysis()` (строка 881)

### В методе handleFontUploadWithCache() (строка 864)

1. **Строка 866**: `$cache = VariableCache::getInstance();`
   - Создает экземпляр `VariableCache` через статический метод `getInstance()`
   - Локальная переменная (хотя в классе уже есть свойство `$cache`)

### В других методах

1. **Строка 612, 692, 702, 714, 719, 730, 733, 808, 886, 897, 902, 916, 930, 949, 971, 979**: `Logger::instance()->...`
   - Множественные вызовы статического метода `Logger::instance()` в различных методах:
     - `creteNewPage()` (строка 612)
     - `getCollectionItem()` (строка 692)
     - `setCurrentPageOnWork()` (строка 702)
     - `deleteAllPages()` (строка 714, 719)
     - `getSectionsFromPage()` (строка 730, 733, 808)
     - `runQualityAnalysis()` (строка 886, 897, 902, 916, 930, 949, 971, 979)

## Свойства класса

- `$cache` - тип: не указан (используется `VariableCache`)
- `$browser` - тип: `BrowserPHP` (PHPDoc)
- `$brizyAPI` - тип: `BrizyAPI` (PHPDoc)
- `$logger` - тип: `LoggerInterface` (PHPDoc)
- `$domain` - тип: `string` (PHPDoc)
- `$QueryBuilder` - тип: `QueryBuilder` (PHPDoc)
- `$parser` - тип: `MBProjectDataCollector` (PHPDoc)
- `$projectID_Brizy` - тип: `int` (PHPDoc)
- `$pageDTO` - тип: `PageDto` (PHPDoc)
- `$ArrayManipulator` - тип: `ArrayManipulator` (PHPDoc)
- `$qualityAnalysisEnabled` - тип: `bool` (PHPDoc)
- `$designName` - тип: `string|null` (PHPDoc)
- `$mb_element_name` - тип: `string` (объявлен с типом)
- `$skip_media_upload` - тип: `bool` (объявлен с типом)
- `$skip_cache` - тип: `bool` (объявлен с типом)
- `$projectStyleDTO` - **НЕ ОБЪЯВЛЕНО ЯВНО** как свойство, но используется в конструкторе (строка 117)

## Порядок инициализации

1. **Конструктор** вызывается при создании объекта:
   - Создает `VariableCache` через `getInstance()`
   - Создает `ArrayManipulator`
   - Создает два экземпляра `PageDTO` (для `$pageDTO` и `$projectStyleDTO`)
   - Сохраняет переданные зависимости в свойства

2. **Метод `run()`** вызывается для обработки страницы:
   - Создает `FontsController` (строка 142)
   - Использует `Logger::instance()` для логирования (множественные вызовы)
   - Динамически создает класс темы (строка 167)
   - Создает `BrowserPHP` через `instance()` (строка 177)
   - Создает `KitLoader` и загружает kit (строка 198)
   - Создает `LayoutElementFactory` (строка 199)
   - Создает `RootPalettesExtractor` и `RootListFontFamilyExtractor` (строки 213-214)
   - Создает `ThemeContext` (строка 224)
   - При необходимости создает `PageQualityAnalyzer` в методе `runQualityAnalysis()` (строка 960)

## Использование зависимостей

### Зависимости, переданные через конструктор (используются через свойства)

- `$this->brizyAPI` (BrizyAPI) используется в:
  - `run()` - передается в `LayoutElementFactory` (строка 203), `ThemeContext` (строка 245), `clearCompileds()` (строка 314)
  - `creteNewPage()` - `getProjectHomePage()` (строка 623)

- `$this->QueryBuilder` (QueryBuilder) используется в:
  - `run()` - передается в `LayoutElementFactory` (строка 202), `updateCollectionItem()` (строка 301)
  - `creteNewPage()` - `createCollectionItem()` (строка 613)
  - `getAllPage()` - `getCollectionTypes()` (строка 652), `getCollectionItems()` (строка 671)
  - `deleteAllPages()` - `deleteCollectionItem()` (строка 717)

- `$this->parser` (MBProjectDataCollector) используется в:
  - `getSectionsFromPage()` - `getSectionsPage()` (строка 737), `getItemsFromSection()` (строка 754)

- `$this->logger` (LoggerInterface) используется в:
  - **НЕ ИСПОЛЬЗУЕТСЯ НАПРЯМУЮ** - вместо него используется `Logger::instance()` статически

### Зависимости, созданные в конструкторе

- `$this->cache` (VariableCache) используется в:
  - `run()` - множественные вызовы `get()` и `set()`
  - `getPageMapping()` - `get()` (строка 344)
  - `createPage()` - `get()` и `set()` (множественные вызовы)
  - `creteNewPage()` - `get()` и `set()` (множественные вызовы)
  - `getAllPage()` - `set()` (строки 665, 678)
  - `getCollectionItem()` - `get()` (строка 686)
  - `setCurrentPageOnWork()` - `set()` (строка 703)
  - `dumpPageDataCache()` - `get()` (строка 848)
  - `runQualityAnalysis()` - `get()` и `getCache()` (множественные вызовы)

- `$this->ArrayManipulator` (ArrayManipulator) используется в:
  - `run()` - `getComparePreviousArray()` (строка 144), `compareArrays()` (строка 146)

- `$this->pageDTO` (PageDto) используется в:
  - `run()` - `setPageStyleDetails()` (строка 254), передается в `ThemeContext` (строка 242)

- `$this->projectStyleDTO` (PageDto) используется в:
  - **НЕ НАЙДЕНО ИСПОЛЬЗОВАНИЕ** в коде (возможно, используется в других методах или через traits)

### Зависимости, созданные в методе run()

- `$fontController` (FontsController) используется в:
  - `run()` - `getFontsFromProjectData()` (строка 143), передается в `LayoutElementFactory` (строка 204), `handleFontUploadWithCache()` (строка 216), `getFontsForSnippet()` (строка 218), передается в `ThemeContext` (строка 244)

- `$this->browser` (BrowserPHP) используется в:
  - `run()` - `openPage()` (строки 180, 191), `closePage()` и `closeBrowser()` (строки 186-187, 335-336), передается в `ThemeContext` (строка 240)

- `$_WorkClassTemplate` (динамический класс темы) используется в:
  - `run()` - `setThemeContext()` (строки 253, 256), `beforeBuildPage()` (строка 254), `setMigrationOptions()` (строка 259), `transformBlocks()` (строка 265)

- `$brizyKit` используется в:
  - `run()` - передается в `LayoutElementFactory` (строка 200), `ThemeContext` (строка 227)

- `$layoutElementFactory` используется в:
  - `run()` - `getFactory()` (строка 206), передается в `ThemeContext` (строка 234)

- `$RootPalettesExtracted` используется в:
  - `run()` - `extractRootPalettes()` (строка 239), передается в `ThemeContext` (строка 239)

- `$RootListFontFamilyExtractor` используется в:
  - `run()` - передается в `handleFontUploadWithCache()` (строка 216)

- `$themeContext` используется в:
  - `run()` - передается в `$_WorkClassTemplate->setThemeContext()` (строки 253, 256)

- `$analyzer` (PageQualityAnalyzer) используется в:
  - `runQualityAnalysis()` - `analyzePage()` (строка 961)

## Выводы для рефакторинга

### Критические зависимости для инжекции через конструктор

1. **`BrowserPHP`** - создается через `BrowserPHP::instance()` в методе `run()`. Должен быть инжектирован через конструктор для тестируемости.

2. **`FontsController`** - создается через `new FontsController()` в методе `run()`. Должен быть инжектирован через конструктор или создан через фабрику.

3. **`PageQualityAnalyzer`** - создается через `new PageQualityAnalyzer()` в методе `runQualityAnalysis()`. Может быть инжектирован через конструктор.

### Зависимости, которые можно оставить как есть (но улучшить)

1. **`VariableCache`** - создается через `getInstance()`. Можно инжектировать через конструктор, но это глобальный кэш, поэтому может быть оправдано использование singleton.

2. **`ArrayManipulator`** - создается через `new ArrayManipulator()` в конструкторе. Это утилитный класс, можно оставить как есть или инжектировать.

3. **`PageDTO`** - создается два экземпляра в конструкторе. Это DTO, можно оставить как есть.

4. **`Logger::instance()`** - используется статически вместо `$this->logger`. **КРИТИЧЕСКАЯ ПРОБЛЕМА**: В классе есть свойство `$logger`, но оно не используется. Нужно заменить все `Logger::instance()` на `$this->logger`.

### Другие зависимости (локальные, создаются в методах)

- `KitLoader`, `LayoutElementFactory`, `RootPalettesExtractor`, `RootListFontFamilyExtractor`, `ThemeContext` - создаются локально в методе `run()` и используются только там. Можно оставить как есть, но их зависимости должны быть инжектированы в `PageController`.

- Динамический класс темы (`$_WorkClassTemplate`) - создается через переменную класса. Это сложная зависимость, требующая фабрики или другого подхода.

## Примечания

1. **Проблема с Logger**: В классе есть свойство `$logger` типа `LoggerInterface`, но везде используется статический метод `Logger::instance()`. Это нарушает принцип Dependency Injection и делает код менее тестируемым.

2. **Отсутствующее свойство**: `$projectStyleDTO` используется в конструкторе, но не объявлено как свойство класса. Это может быть ошибкой или свойство объявлено динамически.

3. **Множественные создания BrowserPHP**: `BrowserPHP` создается дважды в методе `run()` - один раз в начале и один раз в блоке catch. Это может быть оптимизировано.

4. **Использование VariableCache**: `VariableCache` создается дважды - в конструкторе и в методе `handleFontUploadWithCache()`. В методе `handleFontUploadWithCache()` создается локальная переменная, хотя в классе уже есть свойство `$cache`.
