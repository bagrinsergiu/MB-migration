# План создания веб-панели управления миграцией

## Архитектура

Веб-панель будет состоять из:
- **Backend API** (PHP) - новые endpoints для дашборда в `dashboard/api/`
- **Frontend** (React) - SPA приложение в `dashboard/frontend/`
- **Аутентификация** - токен-базированная авторизация

## Структура директорий

```
dashboard/
├── api/                          # Backend API для дашборда
│   ├── index.php                 # Точка входа для API
│   ├── controllers/              # Контроллеры
│   │   ├── AuthController.php
│   │   ├── MigrationController.php
│   │   ├── ProjectController.php
│   │   └── LogController.php
│   ├── services/                 # Бизнес-логика
│   │   ├── AuthService.php
│   │   ├── MigrationService.php
│   │   └── DatabaseService.php  # Работа с БД (только запись в mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com)
│   ├── models/                   # Модели данных
│   │   ├── User.php
│   │   └── Migration.php
│   └── middleware/               # Middleware
│       └── AuthMiddleware.php
├── frontend/                     # React приложение
│   ├── package.json
│   ├── src/
│   │   ├── components/          # React компоненты
│   │   │   ├── Dashboard/
│   │   │   ├── MigrationList/
│   │   │   ├── MigrationDetail/
│   │   │   ├── MigrationRunner/  # Форма запуска миграции
│   │   │   ├── ProjectManager/
│   │   │   └── LogViewer/
│   │   ├── services/             # API клиенты
│   │   │   └── api.js
│   │   ├── hooks/                # React hooks
│   │   ├── context/              # Context для auth
│   │   └── App.jsx
│   └── public/
└── dashboard-plan.md             # Этот файл
```

## Backend API (PHP)

### 1. Database Service
**Файл:** `dashboard/api/services/DatabaseService.php`

- Создать сервис для работы с базой данных
- **КРИТИЧЕСКИ ВАЖНО**: Все операции записи (INSERT, UPDATE, DELETE) только в базу `mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com`
- Операции чтения могут использовать другие базы (только SELECT)
- Использовать существующий класс `MBMigration\Layer\DataSource\driver\MySQL`
- Методы:
  - `getWriteConnection()` - подключение к базе для записи (строго проверять host)
  - `getReadConnection($host)` - подключение к базе для чтения (опционально)
  - `validateWriteHost($host)` - валидация хоста перед записью

### 2. Auth Controller
**Файл:** `dashboard/api/controllers/AuthController.php`

- Токен-базированная аутентификация
- Использовать существующий механизм `APP_AUTHORIZATION_TOKEN` из конфига
- Endpoints:
  - `POST /api/auth/login` - вход (проверка токена)
  - `POST /api/auth/logout` - выход
  - `GET /api/auth/me` - текущий пользователь

### 3. Migration Controller
**Файл:** `dashboard/api/controllers/MigrationController.php`

- Управление миграциями
- **Запуск миграций**: Проксировать запросы к существующему endpoint `/?mb_project_uuid=...&brz_project_id=...`
- Endpoints:
  - `GET /api/migrations` - список всех миграций (из `migrations_mapping` и `migration_result_list`)
  - `GET /api/migrations/:id` - детали миграции
  - `POST /api/migrations/run` - запустить миграцию (прокси к существующему API)
    - Параметры:
      - `mb_project_uuid` (обязательно)
      - `brz_project_id` (обязательно)
      - `mb_site_id` (обязательно)
      - `mb_secret` (обязательно)
      - `brz_workspaces_id` (опционально)
      - `mb_page_slug` (опционально, для миграции одной страницы)
      - `mgr_manual` (опционально, для ручной миграции)
  - `GET /api/migrations/:id/status` - статус миграции
  - `DELETE /api/migrations/:id` - удалить миграцию из маппинга

### 4. Project Controller
**Файл:** `dashboard/api/controllers/ProjectController.php`

- Управление проектами и маппингами
- Endpoints:
  - `GET /api/projects` - список проектов из `migrations_mapping`
  - `GET /api/projects/:uuid` - детали проекта по MB UUID
  - `POST /api/projects/mapping` - создать маппинг проекта (использовать `Bridge::insertMigrationMapping`)
    - Параметры: `brz_project_id`, `source_project_id` (mb_project_uuid), `meta_data` (опционально)
  - `GET /api/projects/mapping/list` - список всех маппингов
  - `DELETE /api/projects/mapping/:id` - удалить маппинг

### 5. Log Controller
**Файл:** `dashboard/api/controllers/LogController.php`

- Просмотр логов миграций
- Endpoints:
  - `GET /api/logs/:brz_project_id` - логи миграции (использовать `ApplicationBootstrapper::getMigrationLogs`)
  - `GET /api/logs/:brz_project_id/download` - скачать лог файл
  - `GET /api/logs/recent` - последние логи

### 6. API Entry Point
**Файл:** `dashboard/api/index.php`

- Главная точка входа для API
- Маршрутизация запросов
- Подключение автозагрузки из корня проекта
- Обработка CORS для React приложения
- Middleware для аутентификации
- Проксирование запросов к существующему API при необходимости

## Frontend (React)

### 1. Настройка проекта
**Файл:** `dashboard/frontend/package.json`

- React 18+
- React Router для навигации
- Axios для HTTP запросов
- UI библиотека (Material-UI или Ant Design)
- Build инструменты (Vite рекомендуется для быстрой сборки)

### 2. Компоненты

#### Dashboard Component
**Файл:** `dashboard/frontend/src/components/Dashboard/Dashboard.jsx`

- Главная страница с обзором:
  - Статистика миграций (всего, успешных, ошибок, в процессе)
  - График миграций по времени
  - Последние активности
  - Быстрый запуск миграции

#### MigrationList Component
**Файл:** `dashboard/frontend/src/components/MigrationList/MigrationList.jsx`

- Таблица со списком миграций из `migrations_mapping` и `migration_result_list`
- Колонки:
  - MB Project UUID
  - Brizy Project ID
  - Статус (success, error, in_progress, pending)
  - Дата создания/обновления
  - Действия (просмотр, логи, запустить заново)
- Фильтры (статус, дата, проект)
- Поиск по UUID или Project ID
- Сортировка
- Пагинация

#### MigrationRunner Component
**Файл:** `dashboard/frontend/src/components/MigrationRunner/MigrationRunner.jsx`

- Форма для запуска миграции
- Поля:
  - MB Project UUID (обязательно)
  - Brizy Project ID (обязательно)
  - MB Site ID (обязательно)
  - MB Secret (обязательно, маскировать ввод)
  - Brizy Workspace ID (опционально)
  - MB Page Slug (опционально, для миграции одной страницы)
  - Manual Migration (чекбокс)
- Валидация полей
- Отображение прогресса выполнения
- Отображение результата миграции

#### MigrationDetail Component
**Файл:** `dashboard/frontend/src/components/MigrationDetail/MigrationDetail.jsx`

- Детальная информация о миграции:
  - Статус и прогресс
  - Информация о проектах (MB UUID, Brizy ID, домены)
  - Время выполнения
  - Логи в реальном времени (polling)
  - Кнопки управления (запустить, повторить)
  - Ссылка на S3 лог файл (если доступна)

#### ProjectManager Component
**Файл:** `dashboard/frontend/src/components/ProjectManager/ProjectManager.jsx`

- Управление маппингами проектов
- Таблица маппингов из `migrations_mapping`
- Создание новых маппингов (форма)
- Редактирование существующих
- Удаление маппингов
- Просмотр `changes_json` для каждого маппинга

#### LogViewer Component
**Файл:** `dashboard/frontend/src/components/LogViewer/LogViewer.jsx`

- Просмотр логов миграции
- Автообновление (polling каждые 5 секунд)
- Фильтрация по уровню (INFO, ERROR, WARNING, DEBUG)
- Поиск по логам
- Подсветка синтаксиса
- Экспорт логов (текст, JSON)
- Автопрокрутка к последним записям

### 3. Services
**Файл:** `dashboard/frontend/src/services/api.js`

- Централизованный API клиент на базе Axios
- Базовый URL: `/dashboard/api` или из env переменной
- Обработка токенов авторизации (в заголовках)
- Обработка ошибок (401 - редирект на логин, 500 - показ ошибки)
- Методы для всех endpoints:
  - `auth.login(token)`
  - `migrations.list()`
  - `migrations.run(params)` - запуск миграции с параметрами
  - `migrations.getStatus(id)`
  - `projects.getMappings()`
  - `projects.createMapping(data)`
  - `logs.get(brz_project_id)`

### 4. Authentication
**Файл:** `dashboard/frontend/src/context/AuthContext.jsx`

- Context для управления состоянием авторизации
- Хранение токена в localStorage
- Проверка авторизации при загрузке
- Защищенные маршруты через React Router

## Интеграция с существующим кодом

### Использование существующих классов:
- `MBMigration\Bridge\Bridge` - для операций с миграциями (`runMigration()`, `insertMigrationMapping()`)
- `MBMigration\ApplicationBootstrapper` - для получения логов (`getMigrationLogs()`, `migrationFlow()`)
- `MBMigration\Layer\DataSource\driver\MySQL` - для работы с БД
- `MBMigration\WaveProc` - для запуска волновых миграций

### Запуск миграций

Существующий endpoint для запуска миграции:
```
GET http://localhost:8080/?mb_project_uuid={uuid}&brz_project_id={id}&mb_site_id={site_id}&mb_secret={secret}
```

Опциональные параметры:
- `brz_workspaces_id` - ID workspace в Brizy
- `mb_page_slug` - для миграции одной страницы
- `mgr_manual` - флаг ручной миграции

**Реализация в MigrationController:**
- Принимать POST запрос с параметрами
- Формировать URL для существующего API
- Выполнять HTTP запрос к существующему endpoint
- Возвращать результат клиенту
- Сохранять статус в базу данных (только в `mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com`)

## Безопасность

1. **Аутентификация**: Токен из `APP_AUTHORIZATION_TOKEN` (из env)
2. **CORS**: Настроить только для домена дашборда
3. **Валидация**: Валидация всех входных данных на backend
4. **SQL Injection**: Использовать prepared statements (уже используется в MySQL классе)
5. **База данных**: Строго разделение чтения и записи
6. **Секреты**: Не логировать `mb_secret` в логи, маскировать в UI

## Важные ограничения

⚠️ **КРИТИЧЕСКИ ВАЖНО**:

- Все операции **записи** (INSERT, UPDATE, DELETE) только в базу `mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com`
- Другие базы данных используются **только для чтения**
- При создании DatabaseService обязательно проверить host перед записью
- Если host не соответствует `mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com`, выбрасывать исключение

## Этапы реализации

1. ✅ Создать структуру директорий `dashboard/`
2. ✅ Настроить DatabaseService с проверкой хоста для записи
3. ✅ Создать API endpoints (Auth, Migration, Project, Log)
4. ✅ Настроить React приложение
5. ✅ Создать основные компоненты (Dashboard, MigrationList, MigrationRunner, MigrationDetail)
6. ✅ Интегрировать с существующими API (проксирование запросов)
7. ✅ Добавить аутентификацию
8. ✅ Тестирование и документация

## Конфигурация

Добавить в `.env`:
```env
# Dashboard API
DASHBOARD_API_URL=/dashboard/api
DASHBOARD_FRONTEND_URL=/dashboard/frontend

# Database для записи (КРИТИЧЕСКИ ВАЖНО - только эта база для записи!)
DASHBOARD_DB_WRITE_HOST=mb-migration.cupzc9ey0cip.us-east-1.rds.amazonaws.com
DASHBOARD_DB_WRITE_NAME=MG_prepare_mapping
DASHBOARD_DB_WRITE_USER=admin
DASHBOARD_DB_WRITE_PASS=Vuhodanasos2
DASHBOARD_DB_WRITE_PORT=3306

# Существующий API для миграций
MIGRATION_API_BASE_URL=http://localhost:8080
```

## Примеры использования

### Запуск миграции через дашборд

**Frontend форма:**
```javascript
{
  mb_project_uuid: "3c56530e-ca31-4a7c-964f-e69be01f382a",
  brz_project_id: 23131991,
  mb_site_id: 31383,
  mb_secret: "b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q",
  brz_workspaces_id: 22925473, // опционально
  mb_page_slug: "home",        // опционально
  mgr_manual: false            // опционально
}
```

**Backend проксирует к:**
```
GET http://localhost:8080/?mb_project_uuid=3c56530e-ca31-4a7c-964f-e69be01f382a&brz_project_id=23131991&mb_site_id=31383&mb_secret=b0kcNmG1cvoMl471cFK2NiOvCIwtPB5Q
```

## Дополнительные возможности (опционально)

- WebSocket для real-time обновлений статуса миграций
- Экспорт отчетов (CSV, PDF)
- Уведомления (email, webhook) при завершении миграции
- История изменений маппингов
- Роли пользователей (admin, viewer)
- Батч-запуск нескольких миграций
- Планировщик миграций (cron-like)
