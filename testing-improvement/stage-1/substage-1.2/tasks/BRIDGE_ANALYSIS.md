# Анализ класса Bridge

## Конструктор

- **Сигнатура**: 
  ```php
  public function __construct(
      ApplicationBootstrapper $app,
      Config                  $config,
      Request                 $request
  )
  ```

- **Параметры**:
  1. `ApplicationBootstrapper $app` - загрузчик приложения
  2. `Config $config` - конфигурация приложения
  3. `Request $request` - HTTP запрос (Symfony)

- **Создает ли зависимости**: Да, создает несколько зависимостей:
  - `new RequestHandlerGET($request)` (строка 48)
  - `new RequestHandlerPOST($request)` (строка 49)
  - `new RequestHandlerDELETE($request)` (строка 50)
  - `new MgResponse()` (строка 52)
  - Вызывает `$this->doConnectionToDB()`, который создает `new MySQL(...)` (строка 154)

## Места создания зависимостей

### В конструкторе (строки 37-55)

1. **Строка 48**: `$this->GET = new RequestHandlerGET($request);`
   - Создает новый экземпляр `RequestHandlerGET`
   - Параметры: `$request` (переданный в конструктор)
   - Сохраняется в свойство `$GET`

2. **Строка 49**: `$this->POST = new RequestHandlerPOST($request);`
   - Создает новый экземпляр `RequestHandlerPOST`
   - Параметры: `$request` (переданный в конструктор)
   - Сохраняется в свойство `$POST`

3. **Строка 50**: `$this->DELETE = new RequestHandlerDELETE($request);`
   - Создает новый экземпляр `RequestHandlerDELETE`
   - Параметры: `$request` (переданный в конструктор)
   - Сохраняется в свойство `$DELETE`

4. **Строка 52**: `$this->mgResponse = new MgResponse();`
   - Создает новый экземпляр `MgResponse`
   - Без параметров
   - Сохраняется в свойство `$mgResponse`

5. **Строка 54**: `$this->db = $this->doConnectionToDB();`
   - Вызывает метод `doConnectionToDB()`, который создает `new MySQL(...)` (строка 154)
   - Параметры MySQL: `Config::$mgConfigMySQL['dbUser']`, `Config::$mgConfigMySQL['dbPass']`, `Config::$mgConfigMySQL['dbName']`, `Config::$mgConfigMySQL['dbHost']`
   - Сохраняется в свойство `$db`

### В методе doConnectionToDB() (строки 152-162)

1. **Строка 154**: `$PDOconnection = new MySQL(...);`
   - Создает новый экземпляр `MySQL`
   - Параметры:
     - `Config::$mgConfigMySQL['dbUser']`
     - `Config::$mgConfigMySQL['dbPass']`
     - `Config::$mgConfigMySQL['dbName']`
     - `Config::$mgConfigMySQL['dbHost']`
   - Локальная переменная, затем вызывается `doConnect()` и возвращается результат

### В методе waveMigration() (строка 169)

1. **Строка 174**: `$waveProc = new WaveProc($projectUuids, $this->db, $batchSize, $muuid);`
   - Создает новый экземпляр `WaveProc`
   - Параметры: `$projectUuids`, `$this->db`, `$batchSize`, `$muuid`
   - Локальная переменная

### В методе compareDate() (строки 306-325)

1. **Строка 309**: `$projectDate = new DateTime($projectDate);`
   - Создает новый экземпляр `DateTime`
   - Параметры: `$projectDate` (строка даты)
   - Локальная переменная

2. **Строка 310**: `$snapShotDate = new DateTime($snapShotDate);`
   - Создает новый экземпляр `DateTime`
   - Параметры: `$snapShotDate` (строка даты)
   - Локальная переменная

### В методе runMigration() (строка 430)

1. **Строка 506**: `$brizyAPI = new BrizyAPI();`
   - Создает новый экземпляр `BrizyAPI`
   - Без параметров
   - Локальная переменная
   - Используется для проверки ручной миграции проекта

2. **Строка 520**: `$mbProjectDataCollector = new MBProjectDataCollector($projectId);`
   - Создает новый экземпляр `MBProjectDataCollector`
   - Параметры: `$projectId` (получен через `MBProjectDataCollector::getIdByUUID($mb_project_uuid)`)
   - Локальная переменная
   - Используется для получения данных проекта

### В методе clearWorkspace() (строка 690)

1. **Строка 692**: `$brizyApi = new BrizyAPI();`
   - Создает новый экземпляр `BrizyAPI`
   - Без параметров
   - Локальная переменная
   - Используется для очистки workspace

### В методе addTagManualMigrationFromDB() (строка 707)

1. **Строка 709**: `$brizyApi = new BrizyAPI();`
   - Создает новый экземпляр `BrizyAPI`
   - Без параметров
   - Локальная переменная
   - Используется для установки тегов ручной миграции

### В методе delTagManualMigration() (строка 730)

1. **Строка 732**: `$brizyApi = new BrizyAPI();`
   - Создает новый экземпляр `BrizyAPI`
   - Без параметров
   - Локальная переменная
   - Используется для удаления тега ручной миграции

### В методе addTagManualMigration() (строка 753)

1. **Строка 755**: `$brizyApi = new BrizyAPI();`
   - Создает новый экземпляр `BrizyAPI`
   - Без параметров
   - Локальная переменная
   - Используется для добавления тега ручной миграции

### В методе setCloningLincMigration() (строка 776)

1. **Строка 778**: `$brizyApi = new BrizyAPI();`
   - Создает новый экземпляр `BrizyAPI`
   - Без параметров
   - Локальная переменная
   - Используется для установки ссылки клонирования

### В методе mApp_2() (строка 799)

1. **Строка 802**: `$brizyApi = new BrizyAPI();`
   - Создает новый экземпляр `BrizyAPI`
   - Без параметров
   - Локальная переменная
   - Используется для установки ссылок клонирования из файла

### В методе mApp() (строка 836)

1. **Строка 840**: `$this->DB = (new MySQL(...))->doConnect();`
   - Создает новый экземпляр `MySQL`
   - Параметры: `'admin'`, `'Vuhodanasos2'`, `'MG_prepare_mapping'`, `'mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com'`
   - Сохраняется в свойство `$DB`
   - **Примечание**: Хардкодные значения для подключения к БД

2. **Строка 847**: `$this->brizyApi = new BrizyAPI();`
   - Создает новый экземпляр `BrizyAPI`
   - Без параметров
   - Сохраняется в свойство `$brizyApi`
   - Используется для установки тегов и ссылок клонирования

3. **Строка 886, 909, 913**: `Logger::instance()->...`
   - Множественные вызовы статического метода `Logger::instance()`
   - Используется для логирования

### В методе doCloningProjects() (строка 923)

1. **Строка 926**: `$brizyApi = new BrizyAPI();`
   - Создает новый экземпляр `BrizyAPI`
   - Без параметров
   - Локальная переменная
   - Используется для клонирования проекта

### В методе checkAllProjectChanges() (строка 941)

1. **Строка 952**: `$mbProjectDataCollector = new MBProjectDataCollector($projectId);`
   - Создает новый экземпляр `MBProjectDataCollector`
   - Параметры: `$projectId` (получен через `MBProjectDataCollector::getIdByUUID($migrations['mb_project_uuid'])`)
   - Локальная переменная
   - Используется для получения страниц проекта

### В методе makeAllProjectsPRO() (строка 988)

1. **Строка 999**: `$api = new BrizyAPI();`
   - Создает новый экземпляр `BrizyAPI`
   - Без параметров
   - Локальная переменная
   - Используется для обновления проектов до PRO версии

## Свойства класса

- `$config` - тип: `Config` (объявлен с типом)
- `$mgResponse` - тип: `MgResponse` (объявлен с типом)
- `$sourceProject` - тип: `string` (объявлен с типом)
- `$request` - тип: `Request` (объявлен с типом)
- `$allList` - тип: `array` (объявлен с типом)
- `$preparedProject` - тип: `int` (объявлен с типом)
- `$db` - тип: `MySQL` (объявлен с типом)
- `$GET` - тип: `RequestHandlerGET` (объявлен с типом)
- `$POST` - тип: `RequestHandlerPOST` (объявлен с типом)
- `$DELETE` - тип: `RequestHandlerDELETE` (объявлен с типом)
- `$listReport` - тип: `array` (объявлен с типом)
- `$app` - тип: `ApplicationBootstrapper` (объявлен с типом)
- `$DB` - тип: `MySQL` (объявлен с типом) - используется только в методе `mApp()`
- `$brizyApi` - тип: `BrizyAPI` (объявлен с типом) - используется только в методе `mApp()`

## Порядок инициализации

1. **Конструктор** вызывается при создании объекта:
   - Инициализирует `$listReport` как пустой массив
   - Сохраняет переданные зависимости (`$app`, `$config`, `$request`)
   - Создает обработчики запросов (`RequestHandlerGET`, `RequestHandlerPOST`, `RequestHandlerDELETE`)
   - Создает `MgResponse`
   - Создает подключение к БД через `doConnectionToDB()`

2. **Методы** вызываются по запросу:
   - `runMigration()` - создает `BrizyAPI` и `MBProjectDataCollector` локально
   - `clearWorkspace()`, `addTagManualMigrationFromDB()`, `delTagManualMigration()`, `addTagManualMigration()`, `setCloningLincMigration()`, `mApp_2()`, `doCloningProjects()`, `makeAllProjectsPRO()` - создают `BrizyAPI` локально
   - `mApp()` - создает `$this->DB` и `$this->brizyApi` как свойства класса
   - `checkAllProjectChanges()` - создает `MBProjectDataCollector` локально

## Использование зависимостей

### Зависимости, переданные через конструктор (используются через свойства)

- `$this->app` (ApplicationBootstrapper) используется в:
  - `runMigration()` - `migrationFlow()` (строки 570, 595)

- `$this->config` (Config) используется в:
  - **НЕ НАЙДЕНО ПРЯМОЕ ИСПОЛЬЗОВАНИЕ** - используется через статические свойства `Config::$mgConfigMySQL` в `doConnectionToDB()`

- `$this->request` (Request) используется в:
  - Множественные методы для получения параметров запроса и определения метода

### Зависимости, созданные в конструкторе

- `$this->GET` (RequestHandlerGET) используется в:
  - `checkPreparedProject()` - `checkInputProperties()` (строка 60)

- `$this->POST` (RequestHandlerPOST) используется в:
  - `addPreparedProject()` - `checkInputProperties()` (строка 78)
  - `addALLPreparedProject()` - `checkInputProperties()` (строка 99)
  - `runMigrationWave()` - `checkInputProperties()` (строка 671)
  - `delTagManualMigration()` - `checkInputProperties()` (строка 735)
  - `addTagManualMigration()` - `checkInputProperties()` (строка 758)
  - `setCloningLincMigration()` - `checkInputProperties()` (строка 781)
  - `doCloningProjects()` - `checkInputProperties()` (строка 928)
  - `makeAllProjectsPRO()` - `checkInputProperties()` (строка 1001)

- `$this->DELETE` (RequestHandlerDELETE) используется в:
  - `delPreparedProject()` - `checkInputProperties()` (строка 398)

- `$this->mgResponse` (MgResponse) используется в:
  - Множественные методы для формирования ответа

- `$this->db` (MySQL) используется в:
  - `getPreparedMappingList()` - `getAllRows()` (строка 192)
  - `insertMigrationMapping()` - `insert()` (строка 248)
  - `searchByUUID()` - `find()` (строка 333)
  - `searchMappingByUUID()` - `find()` (строка 348)
  - `delPreparedProject()` - `delete()` (строка 412)
  - `addTagManualMigrationFromDB()` - `getAllRows()` (строка 711)
  - `mApp()` - `find()` (строка 895)
  - `checkAllProjectChanges()` - `getAllRows()` (строка 944)

### Зависимости, созданные в методах

- `$brizyAPI` / `$brizyApi` / `$api` (BrizyAPI) создается локально в методах:
  - `runMigration()` (строка 506) - используется для `checkProjectManualMigration()`
  - `clearWorkspace()` (строка 692) - используется для `getAllProjectFromContainer()`, `deleteProject()`
  - `addTagManualMigrationFromDB()` (строка 709) - используется для `setLabelManualMigration()`
  - `delTagManualMigration()` (строка 732) - используется для `setLabelManualMigration()`
  - `addTagManualMigration()` (строка 755) - используется для `setLabelManualMigration()`
  - `setCloningLincMigration()` (строка 778) - используется для `setCloningLink()`
  - `mApp_2()` (строка 802) - используется для `setCloningLink()`
  - `mApp()` (строка 847) - сохраняется в `$this->brizyApi`, используется для `setLabelManualMigration()`, `setCloningLink()`
  - `doCloningProjects()` (строка 926) - используется для `cloneProject()`
  - `makeAllProjectsPRO()` (строка 999) - используется для `getAllProjectFromContainerV1()`, `upgradeProject()`

- `$mbProjectDataCollector` (MBProjectDataCollector) создается локально в методах:
  - `runMigration()` (строка 520) - используется для `getPages()`
  - `checkAllProjectChanges()` (строка 952) - используется для `getPages()`

- `$waveProc` (WaveProc) создается локально в методе:
  - `waveMigration()` (строка 174) - используется для `runMigrations()`

- `$this->DB` (MySQL) создается в методе:
  - `mApp()` (строка 840) - используется для `find()` (строка 895)

- `DateTime` создается локально в методе:
  - `compareDate()` (строки 309, 310) - используется для сравнения дат

## Выводы для рефакторинга

### Критические зависимости для инжекции через конструктор

1. **`BrizyAPI`** - создается в 10 местах в различных методах. Должен быть инжектирован через конструктор для тестируемости. Все методы используют одинаковый конструктор без параметров.

2. **`MBProjectDataCollector`** - создается в 2 местах с параметром `$projectId`. Может быть инжектирован через конструктор, но требует `$projectId`, который вычисляется динамически. Возможно, нужна фабрика или метод для создания с параметром.

### Зависимости, которые можно оставить как есть (но улучшить)

1. **`RequestHandlerGET`, `RequestHandlerPOST`, `RequestHandlerDELETE`** - создаются в конструкторе. Это обработчики запросов, специфичные для каждого запроса, поэтому их создание в конструкторе оправдано.

2. **`MgResponse`** - создается в конструкторе. Это DTO для ответа, можно оставить как есть.

3. **`MySQL`** - создается в конструкторе через `doConnectionToDB()`. Это подключение к БД, можно оставить как есть или инжектировать через конструктор.

4. **`DateTime`** - создается локально для сравнения дат. Это стандартный класс PHP, можно оставить как есть.

5. **`WaveProc`** - создается локально в методе `waveMigration()`. Используется только в этом методе, можно оставить как есть.

### Другие зависимости (локальные, создаются в методах)

- `MySQL` в методе `mApp()` - создается с хардкодными значениями. Это специальный метод, возможно, для администрирования. Можно оставить как есть или вынести в конфигурацию.

### Проблемы

1. **Множественные создания BrizyAPI**: `BrizyAPI` создается в 10 разных методах. Это нарушает принцип Dependency Injection и делает код менее тестируемым.

2. **Использование Logger::instance()**: В методе `mApp()` используется статический метод `Logger::instance()` вместо инжектированного логгера (если бы он был).

3. **Хардкодные значения в mApp()**: Метод `mApp()` содержит хардкодные значения для подключения к БД. Это плохая практика.

4. **Дублирование кода**: Создание `BrizyAPI` повторяется во многих методах с одинаковым паттерном.

## Примечания

1. **Класс большой**: Bridge содержит 1026 строк кода и множество методов. Рефакторинг должен быть осторожным, чтобы не сломать существующую функциональность.

2. **Разные паттерны использования**: Некоторые методы создают зависимости локально, другие используют свойства класса. Это создает несогласованность.

3. **Специальные методы**: Метод `mApp()` содержит хардкодные значения и, возможно, используется только для администрирования. Его можно оставить как есть или вынести в отдельный класс.

4. **Интерфейсы**: `BrizyAPI` уже имеет интерфейс `BrizyAPIInterface`, который можно использовать для инжекции.
