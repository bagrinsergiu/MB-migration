# План: Система волн миграций (Wave Migration System)

## Архитектура

Система будет состоять из:
1. **Frontend**: Новая страница `/wave` для создания и управления волнами
2. **Backend API**: Endpoints для создания волн и отслеживания прогресса
3. **Wave Service**: Сервис для выполнения миграций в фоне
4. **Database**: Хранение информации о волнах в `migrations_mapping` через `changes_json`

## Компоненты

### 1. Frontend (`dashboard/frontend/src/components/Wave.tsx`)

**Основная страница Wave:**
- **Таблица всех волн** с колонками:
  - Название волны
  - Workspace name
  - Статус (pending/in_progress/completed/error)
  - Прогресс (completed/total)
  - Дата создания
  - Действия (кнопка "Детали")
- **Форма создания новой волны** (модальное окно или секция):
  - Название волны (обязательно)
  - Список UUID проектов (textarea, по одному на строку)
  - Batch size (опционально, по умолчанию 3)
  - mgr_manual (чекбокс)

**Детальная страница волны (`/wave/:id`):**
- **Информация о волне:**
  - Название, workspace, статус, даты создания/завершения
  - Общий прогресс (completed/failed/total)
- **Таблица миграций в волне:**
  - MB Project UUID
  - Brizy Project ID
  - Статус миграции
  - Brizy Project Domain (ссылка)
  - Дата выполнения
  - Действия:
    - Перезапустить миграцию
    - Просмотреть логи
    - Перейти к деталям миграции

**Интерфейсы:**
```typescript
interface Wave {
  id: string; // уникальный ID волны (timestamp + random)
  name: string;
  workspace_id?: number;
  workspace_name: string;
  project_uuids: string[];
  status: 'pending' | 'in_progress' | 'completed' | 'error';
  created_at: string;
  completed_at?: string;
  progress: {
    total: number;
    completed: number;
    failed: number;
  };
}

interface WaveMigration {
  mb_project_uuid: string;
  brz_project_id?: number;
  status: 'pending' | 'in_progress' | 'completed' | 'error';
  brizy_project_domain?: string;
  error?: string;
  completed_at?: string;
  migration_id?: string; // ID из migrations_mapping
}
```

### 2. Backend API (`dashboard/api/controllers/WaveController.php`)

**Endpoints:**
- `POST /api/waves` - Создать новую волну
  - Параметры: `name`, `project_uuids[]`, `batch_size`, `mgr_manual`
  - Возвращает: `{ success: true, data: { wave_id, workspace_id } }`
  
- `GET /api/waves` - Список всех волн
  - Возвращает список волн с прогрессом
  - Фильтрация по статусу (опционально)
  
- `GET /api/waves/:id` - Детали волны
  - Возвращает информацию о волне и список миграций с деталями
  
- `GET /api/waves/:id/status` - Статус волны
  - Быстрый запрос для обновления прогресса
  
- `POST /api/waves/:id/migrations/:mb_uuid/restart` - Перезапустить миграцию в волне
  - Параметры: опционально `mb_site_id`, `mb_secret` (из настроек по умолчанию)
  - Возвращает результат перезапуска

- `GET /api/waves/:id/migrations/:mb_uuid/logs` - Получить логи миграции
  - Возвращает логи из файла или из БД

### 3. Wave Service (`dashboard/api/services/WaveService.php`)

**Основная логика:**

1. **Создание волны:**
   - Генерировать уникальный ID волны
   - Создать или найти workspace по имени (используя `BrizyAPI::getWorkspaces()`)
   - Сохранить информацию о волне в `migrations_mapping`:
     ```php
     changes_json: {
       wave_id: string,
       wave_name: string,
       workspace_id: number,
       workspace_name: string,
       project_uuids: string[],
       status: 'pending',
       progress: { total: N, completed: 0, failed: 0 },
       migrations: []
     }
     ```

2. **Выполнение волны (асинхронно):**
   - Запускать в фоне через wrapper script (как в `ApiProxyService`)
   - Для каждого UUID последовательно:
     a. Создать проект в workspace (`BrizyAPI::createProject()` с `brz_project_id = 0`)
     b. Выполнить миграцию через `ApplicationBootstrapper::migrationFlow()`
     c. Сохранить результат в `migrations_mapping` (отдельная запись для каждой миграции)
     d. Обновить прогресс волны в `changes_json`
     e. Добавить информацию о миграции в массив `migrations` волны

3. **Обработка ошибок:**
   - При ошибке миграции: записать в `migrations` с `status: 'error'` и `error: message`
   - Продолжать выполнение следующих миграций
   - Обновить счетчик `failed` в прогрессе

4. **Перезапуск миграции:**
   - Найти запись миграции в `migrations_mapping` по `mb_project_uuid` и `brz_project_id`
   - Запустить миграцию заново (используя существующий `brz_project_id`)
   - Обновить статус в массиве `migrations` волны

### 4. Database Service (`dashboard/api/services/DatabaseService.php`)

**Новые методы:**
- `createWave(string $waveId, string $name, array $projectUuids, int $workspaceId, string $workspaceName): int`
  - Создает запись в `migrations_mapping` с `brz_project_id = 0` и `mb_project_uuid = "wave_{waveId}"`
  - Сохраняет метаданные волны в `changes_json`

- `getWave(string $waveId): ?array`
  - Получает информацию о волне по ID

- `updateWaveProgress(string $waveId, array $progress, array $migrations): void`
  - Обновляет прогресс волны в `changes_json`

- `getWavesList(): array`
  - Получает список всех волн (записи где `mb_project_uuid LIKE 'wave_%'`)

- `getWaveMigrations(string $waveId): array`
  - Получает все миграции, связанные с волной (по `changes_json` или по связи через `brz_project_id`)

**Примечание:** Метод `BrizyAPI::createdWorkspaces($name = null)` обновлен для поддержки опционального параметра `$name`. Если параметр не указан, используется `Config::$nameMigration` по умолчанию.


### 5. Интеграция с существующим кодом

**Использование существующих компонентов:**
- `BrizyAPI::getWorkspaces($name)` - найти workspace по имени
- `BrizyAPI::createProject($projectName, $workspaceId, 'id')` - создать проект
- `ApplicationBootstrapper::migrationFlow()` - выполнить миграцию
- `DatabaseService::upsertMigrationMapping()` - сохранить результат миграции
- `LogController::getLogs()` - получить логи миграции (переиспользовать)

**Wrapper Script (аналогично `ApiProxyService`):**
- Создать PHP wrapper script для выполнения волны в фоне
- Скрипт должен:
  1. Загрузить информацию о волне из БД
  2. Для каждого UUID выполнить миграцию
  3. После каждой миграции:
     - Сохранить результат в `migrations_mapping` (отдельная запись)
     - Обновить прогресс волны в `changes_json`
     - Добавить информацию о миграции в массив `migrations`
  4. Обновить финальный статус волны

## Файлы для создания/изменения

### Frontend:
- `dashboard/frontend/src/components/Wave.tsx` - основной компонент со списком волн
- `dashboard/frontend/src/components/WaveDetails.tsx` - детальный просмотр волны
- `dashboard/frontend/src/components/Wave.css` - стили
- `dashboard/frontend/src/api/client.ts` - добавить методы для волн
- `dashboard/frontend/src/App.tsx` - добавить маршруты `/wave` и `/wave/:id`

### Backend:
- `dashboard/api/controllers/WaveController.php` - новый контроллер
- `dashboard/api/services/WaveService.php` - новый сервис
- `dashboard/api/services/DatabaseService.php` - добавить методы для волн
- `dashboard/api/index.php` - добавить маршруты для волн

## Последовательность выполнения

1. Создать структуру данных и интерфейсы в frontend
2. Реализовать DatabaseService методы для работы с волнами
3. Создать WaveService с логикой создания workspace и выполнения миграций
4. Реализовать WaveController с API endpoints (включая перезапуск и логи)
5. Создать frontend компонент Wave со списком волн
6. Создать компонент WaveDetails с таблицей миграций и действиями
7. Интегрировать wrapper script для асинхронного выполнения
8. Добавить автообновление прогресса на странице волны
9. Реализовать перезапуск миграций и просмотр логов

## Особенности реализации

- **Workspace**: Создавать новый workspace для каждой волны с указанным названием (через `BrizyAPI::getWorkspaces()` и создание если не существует)
- **Последовательность**: Выполнять миграции параллельно (batch_size = 3 по умолчанию), можно настроить количество параллельных миграций
- **Хранение**: Вся информация о волне хранится в `migrations_mapping` через `changes_json`, без отдельной таблицы. Каждая миграция в волне - отдельная запись в `migrations_mapping` с `brz_project_id` и `mb_project_uuid`
- **Прогресс**: Обновлять прогресс после каждой миграции для отображения в реальном времени
- **Ошибки**: Продолжать выполнение при ошибках, записывать их в метаданные миграции
- **Перезапуск**: Возможность перезапустить отдельную миграцию в волне, используя существующий `brz_project_id`
- **Логи**: Использовать существующий механизм получения логов из `LogController`

## TODO

1. Добавить методы в DatabaseService для работы с волнами: createWave, getWave, updateWaveProgress, getWavesList, getWaveMigrations
2. Создать WaveService с логикой создания workspace, выполнения миграций и обновления прогресса
3. Создать WaveController с API endpoints: POST /waves, GET /waves, GET /waves/:id, GET /waves/:id/status, POST /waves/:id/migrations/:mb_uuid/restart, GET /waves/:id/migrations/:mb_uuid/logs
4. Добавить маршруты для волн в dashboard/api/index.php
5. Создать wrapper script для асинхронного выполнения волны миграций в фоне
6. Добавить методы API для волн в dashboard/frontend/src/api/client.ts
7. Создать компонент Wave.tsx со списком всех волн в виде таблицы и формой создания
8. Создать компонент WaveDetails.tsx с детальной информацией о волне, таблицей миграций, возможностью перезапуска и просмотра логов
9. Добавить маршруты /wave и /wave/:id в App.tsx и ссылку в Layout.tsx
