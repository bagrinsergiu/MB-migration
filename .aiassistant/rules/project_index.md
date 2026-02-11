---
apply: on_demand
---

# MB-Migration Project Index

## Project Statistics
- **Total PHP Files**: 614 files in `/lib` directory
- **Main Namespace**: `MBMigration`
- **Autoloading**: PSR-0 standard
- **Test Files**: 6+ PHPUnit test files
- **Themes**: 13 supported themes with individual asset workspaces
- **Languages**: PHP (primary), JavaScript/TypeScript (assets), SCSS (styling)

## Directory Structure Index

### Root Level Organization
```
/home/sg/projects/MB-migration/
├── lib/                    # Main application code (614+ PHP files)
├── tests/                  # PHPUnit test suite
├── public/                 # Web-accessible entry points
├── dashboard/              # Dashboard API and frontend (NEW)
│   ├── api/                # REST API endpoints
│   ├── frontend/           # React application (future)
│   ├── IMPORTANT.md        # Critical information
│   ├── CONTEXT.md          # Project context
│   └── API.md              # API documentation
├── vendor/                 # Composer dependencies
├── node_modules/           # NPM dependencies
├── docs/                   # Documentation
├── examples/               # Usage examples
├── db/                     # Database migrations and schema
├── var/                    # Runtime files (logs, cache)
├── utils/                  # Development utilities
├── .docker/                # Docker configuration
├── mysql/                  # MySQL specific files
└── mb_tmp/                 # Temporary migration files
```

## Core Application Index (`/lib/MBMigration/`)

### Main Entry Points
| File | Purpose | Size | Lines | Key Functions |
|------|---------|------|-------|---------------|
| `MigrationPlatform.php` | Main orchestrator | 17KB | 502 | `start()`, `run()`, `launch()` |
| `ApplicationBootstrapper.php` | App initialization | 7KB | - | Bootstrap services |
| `MigrationRunnerWave.php` | Wave execution | 6KB | - | Batch processing |
| `WaveProc.php` | Wave processing | 20KB | - | Process management |
| `CreateMigrationMapping.php` | Mapping creation | 10KB | - | Mapping utilities |
| `mappingUtils.php` | Mapping helpers | 9KB | - | Utility functions |

### Dashboard API (`/dashboard/`)
| File | Purpose | Key Functions |
|------|---------|---------------|
| `dashboard/api/index.php` | API entry point | Routing, CORS, autoloading |
| `dashboard/api/services/DatabaseService.php` | Database operations | `getWriteConnection()`, `validateWriteHost()` |
| `dashboard/api/services/MigrationService.php` | Migration logic | `getMigrationsList()`, `runMigration()` |
| `dashboard/api/controllers/MigrationController.php` | Migration endpoints | `list()`, `run()`, `restart()`, `getStatus()` |
| `dashboard/api/controllers/LogController.php` | Log endpoints | `getLogs()`, `getRecent()` |

### Component Directory Index

#### 1. Core Foundation (`/Core/`)
| Component | File | Purpose | Dependencies |
|-----------|------|---------|--------------|
| Configuration | `Config.php` (9KB) | Environment & settings management | Symfony DotEnv |
| Logging | `Logger.php` | Monolog integration | Monolog |
| Utilities | `Utils.php` (6KB) | General helper functions | - |
| Error Handling | `ErrorDump.php` | Error reporting & debugging | - |
| Cloud Storage | `S3Uploader.php` | AWS S3 integration | AWS SDK |

#### 2. Data Access Layer (`/Layer/`)
```
Layer/
├── Brizy/              # Destination API integration
├── MB/                 # Ministry Brands source API
├── DataSource/         # Database abstraction
│   └── driver/         # Database drivers
├── Graph/              # GraphQL query building
└── HTTP/               # HTTP client abstraction
```

**API Integration Points:**
- **Brizy Layer**: Target platform API calls, data transformation
- **MB Layer**: Source data extraction, MonkcmsAPI integration  
- **Graph Layer**: GraphQL query construction and execution
- **DataSource**: Multi-database support with driver abstraction
- **HTTP Layer**: RESTful API communication

#### 3. Content Builder System (`/Builder/`)
```
Builder/
├── BrizyComponent/     # UI component mapping
│   └── Components/     # Individual component builders
├── Layout/             # Layout management
│   ├── Common/         # Shared layout elements  
│   └── Theme/          # Theme-specific implementations
│       ├── Anthem/Assets/      ├── Hope/Assets/
│       ├── August/Assets/      ├── Majesty/Assets/
│       ├── Aurora/Assets/      ├── Serene/Assets/
│       ├── Bloom/Assets/       ├── Solstice/Assets/
│       ├── Boulevard/Assets/   ├── Tradition/Assets/
│       ├── Dusk/Assets/        ├── Voyage/Assets/
│       └── Ember/Assets/       └── Zion/Assets/
├── Media/              # Asset processing & migration
├── Menu/               # Navigation structure handling
├── Fonts/              # Typography management
│   └── fontsKit/       # Font processing utilities
├── Cms/                # CMS-specific functionality
└── Utils/              # Builder utilities
```

**Builder Components Index:**
- **Layout Engine**: Theme-agnostic layout processing
- **Component Mapping**: Ministry Brands → Brizy component translation
- **Asset Pipeline**: Image, font, and media processing
- **Menu Builder**: Navigation structure migration
- **Theme System**: 13 themes with individual asset compilation

#### 4. Browser Automation (`/Browser/`)
| Component | File | Purpose | Integration |
|-----------|------|---------|-------------|
| Interface | `BrowserInterface.php` | Browser abstraction | - |
| Page Interface | `BrowserPageInterface.php` | Page interaction contract | - |
| PHP Implementation | `BrowserPagePHP.php` (11KB) | Chrome automation | chrome-php/chrome |
| Main Controller | `Browser.php` | Browser lifecycle management | - |
| PHP Browser | `BrowserPHP.php` | PHP-specific browser logic | - |

**Browser Automation Capabilities:**
- Headless Chrome integration
- Dynamic content extraction
- JavaScript execution
- Screenshot capture
- Form interaction
- Cookie management

#### 5. System Integration Bridge (`/Bridge/`)
| Component | File | Size | Purpose |
|-----------|------|------|---------|
| Main Bridge | `Bridge.php` | 36KB | System orchestration & coordination |
| Cloning | `CloningManager.php` | 3KB | Content duplication logic |
| Wave Management | `MigrationWaveManager.php` | 2KB | Batch processing coordination |
| Tagging | `TagManager.php` | 2KB | Content categorization |
| Response | `MgResponse.php` | 0.6KB | Response standardization |

**Bridge Responsibilities:**
- Cross-system communication
- Data transformation orchestration  
- Migration wave coordination
- Error handling and recovery
- State management

#### 6. Content Parser (`/Parser/`)
```
Parser/
├── JS.php              # JavaScript parsing coordination
└── JsParse/            # JavaScript analysis tools
    ├── JSCode.php      # JavaScript code processing
    └── js/             # JavaScript utilities
```

**Parser Capabilities:**
- JavaScript code extraction
- Dynamic content analysis
- Template parsing
- Content structure analysis

## File Type Distribution

### PHP Files by Category
| Category | File Count (Est.) | Primary Purpose |
|----------|-------------------|-----------------|
| Theme Layouts | ~200 | Theme-specific component implementations |
| Builder Components | ~150 | Content construction logic |
| Layer APIs | ~100 | Data access and API integration |
| Browser Automation | ~50 | Web scraping and automation |
| Core Utilities | ~50 | Foundation services |
| Bridge Integration | ~30 | System coordination |
| Parser Tools | ~20 | Content analysis |
| Configuration | ~14 | Settings and environment |

### Asset Files
| Type | Location | Purpose |
|------|----------|---------|
| SCSS | `*/Assets/` | Theme styling |
| JavaScript | `*/Assets/` | Theme interactions |
| TypeScript | `*/Assets/` | Type-safe scripting |
| JSON | Various | Configuration & data |
| Images | `public/`, themes | UI assets |

## Component Relationship Index

### Dependency Flow
```
MigrationPlatform (Entry Point)
├── ApplicationBootstrapper → Core Services
├── Layer Components → Data Access
│   ├── MB Layer → Source Data
│   ├── Brizy Layer → Destination Data
│   ├── Graph Layer → Query Building
│   └── DataSource → Database Access
├── Builder Components → Content Construction  
│   ├── Layout System → Theme Processing
│   ├── BrizyComponent → UI Mapping
│   ├── Media Handler → Asset Processing
│   └── Menu Builder → Navigation
├── Browser Components → Web Automation
├── Bridge Components → System Integration
└── Parser Components → Content Analysis
```

### Key Integration Points
1. **MigrationPlatform** orchestrates entire process
2. **Bridge** coordinates between systems
3. **Layer** provides data abstraction
4. **Builder** constructs target content
5. **Browser** handles dynamic content
6. **Parser** analyzes source structure

## Testing Index

### Test Coverage Mapping
```
tests/
├── MBMigration/
│   ├── Builder/
│   │   ├── BrizyComponent/
│   │   │   └── BrizyComponentTest.php
│   │   ├── SectionTest.php
│   │   ├── Utils/
│   │   │   └── PathSlugExtractorTest.php
│   │   └── Layout/
│   │       └── LayoutUtilsTest.php
│   └── Core/
│       └── UtilsTest.php
└── bootstrap.php
```

**Test Categories:**
- **Component Tests**: UI component mapping validation
- **Utility Tests**: Helper function verification  
- **Layout Tests**: Theme layout processing
- **Core Tests**: Foundation utility testing

## Configuration Index

### Environment Files
| File | Purpose | Environment |
|------|---------|-------------|
| `.env.dev` | Development template | Development |
| `.env.dev.local` | Local development | Development |
| `.env` | Production settings | Production |
| `docker-compose.yaml` | Container configuration | Docker |
| `phinx.php` | Database migrations | All |

### Build Configuration
| File | Purpose | Technology |
|------|---------|------------|
| `composer.json` | PHP dependencies | PHP/Composer |
| `package.json` | JS dependencies & scripts | Node.js/NPM |
| `turbo.json` | Monorepo build config | Turbo |
| `phpunit.xml.dist` | Test configuration | PHPUnit |
| `rector.php` | Code modernization | Rector |

## API Integration Index

### External Service Integration
| Service | Layer | Purpose | Authentication |
|---------|-------|---------|----------------|
| Ministry Brands | MB Layer | Source data extraction | API keys |
| Brizy | Brizy Layer | Destination content creation | API tokens |
| AWS S3 | S3Uploader | Asset storage | AWS credentials |
| MySQL | DataSource | State persistence | DB credentials |
| Chrome | Browser | Web automation | Local process |

### Internal API Points
| Component | Interface | Methods | Purpose |
|-----------|-----------|---------|---------|
| BrowserInterface | Browser abstraction | `navigate()`, `click()`, `extract()` | Web automation |
| BrowserPageInterface | Page interaction | `getContent()`, `screenshot()` | Page operations |
| DataSource drivers | Database abstraction | `query()`, `insert()`, `update()` | Data persistence |

## Performance & Scalability Index

### Optimization Points
| Component | Strategy | Implementation |
|-----------|----------|----------------|
| Migration Processing | Wave-based batching | MigrationRunnerWave |
| Browser Automation | Instance reuse | Browser lifecycle management |
| Asset Processing | S3 CDN integration | S3Uploader |
| Database Access | Connection pooling | DataSource drivers |
| Memory Management | Efficient data structures | Utils, ArrayManipulator |

### Monitoring Points
| Metric | Component | Purpose |
|--------|-----------|---------|
| Processing Time | ExecutionTimer | Performance tracking |
| Memory Usage | VariableCache | Resource monitoring |
| Error Rates | ErrorDump, Logger | Error tracking |
| Migration Progress | Wave processing | Progress reporting |

## Security Index

### Security Components
| Component | File | Purpose |
|-----------|------|---------|
| Environment Security | Core/Config.php | Secure configuration management |
| Asset Security | S3Uploader.php | Secure cloud storage |
| Browser Security | Browser/* | Secure web automation |
| Error Security | ErrorDump.php | Secure error reporting |

### Security Boundaries
- **Environment Isolation**: Docker containerization
- **Credential Management**: Environment variable based
- **Process Isolation**: Separate browser processes
- **Data Encryption**: S3 encrypted storage
- **Access Control**: API key based authentication

## Development Workflow Index

### Development Commands
```bash
# PHP Development
composer install           # Install dependencies
composer test              # Run tests
composer test:coverage     # Test with coverage

# JavaScript Development  
npm install               # Install dependencies
npm run build:prod        # Production build
npm run lint              # Code linting
npm run check-all         # Comprehensive checks

# Docker Development
docker-compose up -d      # Start containers
docker-compose down       # Stop containers
```

### Code Quality Tools
| Tool | Configuration | Purpose |
|------|---------------|---------|
| PHPUnit | `phpunit.xml.dist` | Unit testing |
| Rector | `rector.php` | Code modernization |
| Prettier | NPM scripts | Code formatting |
| Turbo | `turbo.json` | Monorepo builds |
| ESLint | NPM scripts | JavaScript linting |

## Maintenance Index

### Regular Maintenance Tasks
1. **Dependency Updates**: Composer and NPM package updates
2. **Security Patches**: Regular vulnerability scanning
3. **Performance Monitoring**: Execution time and memory usage
4. **Test Coverage**: Expand test coverage for new components
5. **Documentation**: Keep API documentation current

### Troubleshooting Points
| Issue Type | Primary Component | Debug Tool |
|------------|-------------------|------------|
| Migration Failures | MigrationPlatform | Logger, ErrorDump |
| Browser Issues | Browser components | Chrome debugging |
| API Integration | Layer components | HTTP logging |
| Theme Problems | Builder/Layout | Theme-specific logs |
| Database Issues | DataSource | Query logging |

This index provides comprehensive navigation and reference information for the MB-Migration project, facilitating development, maintenance, and troubleshooting activities.
