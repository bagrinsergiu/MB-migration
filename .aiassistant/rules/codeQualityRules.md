---
apply: always
---

# Code Quality Rules for MB-Migration Project

## Overview

This document establishes code quality standards, best practices, and rules for the MB-Migration project. These rules complement the existing Brizy Builder element rules and address specific code quality issues identified in the project analysis.

## PHP Development Standards

### 1. Class Design Rules

#### Rule CQ-001: Single Responsibility Principle
**Status**: ❌ **VIOLATION FOUND**
- **Rule**: Each class should have only one reason to change
- **Violation Example**: `Bridge.php` (1079 lines, 30+ methods)
- **Standard**: Classes should not exceed 500 lines
- **Methods**: Should not exceed 50 lines
- **Enforcement**: Use static analysis tools (PHPStan level 6+)

```php
// ❌ BAD: God class handling multiple concerns
class Bridge {
    public function runMigration() { /* 134 lines */ }
    public function mApp() { /* 210 lines */ }
    public function handleDatabase() { /* ... */ }
    public function processAPI() { /* ... */ }
}

// ✅ GOOD: Separate concerns
class MigrationService { /* ... */ }
class DatabaseService { /* ... */ }  
class APIService { /* ... */ }
```

#### Rule CQ-002: Method Complexity
**Status**: ❌ **VIOLATION FOUND**
- **Cyclomatic Complexity**: Maximum 10 per method
- **Nesting Depth**: Maximum 3 levels
- **Current Issues**: `runMigration()` has 4+ nesting levels

```php
// ❌ BAD: Deep nesting
public function runMigration() {
    if ($condition1) {
        try {
            if ($condition2) {
                try {
                    if ($condition3) {
                        // Deep nesting
                    }
                } catch (Exception $e) {
                    // Handle exception
                }
            }
        } catch (Exception $e) {
            // Handle exception
        }
    }
}

// ✅ GOOD: Early returns, extracted methods
public function runMigration() {
    if (!$this->validateInput()) {
        return $this->errorResponse('Invalid input');
    }
    
    return $this->processValidMigration();
}
```

### 2. Error Handling Rules

#### Rule CQ-003: Consistent Exception Handling
**Status**: ❌ **VIOLATION FOUND**
- **Rule**: Use consistent exception types and handling patterns
- **Current Issue**: Mixed `Exception` and `\Exception` usage

```php
// ❌ BAD: Inconsistent exception handling
try {
    // operation
} catch (Exception $e) {
    $this->prepareResponseMessage($e->getMessage(), 'error', $e->getCode());
} catch (\Exception $e) {  // Different type!
    $this->prepareResponseMessage($e->getMessage(), 'error', 400);
}

// ✅ GOOD: Consistent and specific
try {
    // operation
} catch (ValidationException $e) {
    return $this->validationErrorResponse($e);
} catch (DatabaseException $e) {
    return $this->databaseErrorResponse($e);
} catch (Exception $e) {
    return $this->genericErrorResponse($e);
}
```

#### Rule CQ-004: Custom Exception Hierarchy
**Status**: 🔧 **NEEDS IMPLEMENTATION**
- **Rule**: Create domain-specific exception classes
- **Required Exceptions**:
  - `MigrationException` - Base migration exception
  - `ValidationException` - Input validation errors
  - `DatabaseException` - Database operation errors
  - `APIException` - External API errors

```php
// ✅ REQUIRED: Custom exception hierarchy
abstract class MigrationException extends Exception {}

class ValidationException extends MigrationException {}
class DatabaseException extends MigrationException {}
class APIException extends MigrationException {}
class ProjectNotFoundException extends MigrationException {}
```

### 3. Variable and Method Naming Rules

#### Rule CQ-005: Naming Conventions
**Status**: ❌ **VIOLATION FOUND**
- **Rule**: Use descriptive, consistent naming following PSR standards
- **Current Issues**: 
  - `$mgr_manual`, `$mgr_mapping` - abbreviations
  - `$brz_project_id`, `$brizy_project_id` - inconsistent

```php
// ❌ BAD: Abbreviations and inconsistency
$mgr_manual = $this->request->get('mgr_manual');
$brz_project_id = $this->request->get('brz_project_id');
$mb_project_uuid = $this->request->get('mb_project_uuid');

// ✅ GOOD: Descriptive and consistent
$isManualMigration = $this->request->get('is_manual_migration');
$brizyProjectId = $this->request->get('brizy_project_id');
$ministryBrandsProjectUuid = $this->request->get('mb_project_uuid');
```

#### Rule CQ-006: Boolean Variable Naming
**Status**: 🔧 **NEEDS IMPLEMENTATION**
- **Rule**: Boolean variables should have question-like names

```php
// ❌ BAD: Unclear boolean intent
$manual = true;
$migration = false;

// ✅ GOOD: Clear boolean intent
$isManual = true;
$hasBeenMigrated = false;
$canProcessMigration = true;
```

### 4. Input Validation Rules

#### Rule CQ-007: Comprehensive Input Validation
**Status**: ❌ **VIOLATION FOUND**
- **Rule**: All external input must be validated before processing
- **Current Issue**: Basic `isset()` checks only

```php
// ❌ BAD: Insufficient validation
$mb_project_uuid = $this->request->get('mb_project_uuid');
if (!isset($mb_project_uuid)) {
    throw new Exception('Invalid mb_project_uuid', 400);
}

// ✅ GOOD: Comprehensive validation
$mbProjectUuid = $this->validateProjectUuid(
    $this->request->get('mb_project_uuid')
);

private function validateProjectUuid($uuid): string {
    if (empty($uuid)) {
        throw new ValidationException('Project UUID is required');
    }
    
    if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid)) {
        throw new ValidationException('Invalid UUID format');
    }
    
    return $uuid;
}
```

#### Rule CQ-008: Input Sanitization
**Status**: 🔧 **NEEDS IMPLEMENTATION**
- **Rule**: Sanitize all input data according to expected format
- **Required**: Implement input sanitization layer

### 5. Constants and Magic Numbers

#### Rule CQ-009: No Magic Numbers
**Status**: ❌ **VIOLATION FOUND**
- **Rule**: Define constants for all numeric and string literals
- **Current Issues**: HTTP codes `400`, `500` hardcoded

```php
// ❌ BAD: Magic numbers
throw new Exception('Invalid input', 400);
$this->prepareResponseMessage($error, 'error', 500);

// ✅ GOOD: Named constants
class HttpStatusCode {
    public const BAD_REQUEST = 400;
    public const INTERNAL_SERVER_ERROR = 500;
    public const OK = 200;
}

throw new ValidationException('Invalid input', HttpStatusCode::BAD_REQUEST);
```

#### Rule CQ-010: Configuration Constants
**Status**: 🔧 **NEEDS IMPLEMENTATION**
- **Rule**: Centralize all configuration values in constants
- **Required**: Create `Constants.php` or use config files

### 6. Database Interaction Rules

#### Rule CQ-011: Query Optimization
**Status**: ⚠️ **NEEDS REVIEW**
- **Rule**: Prevent N+1 query problems
- **Standard**: Use bulk operations where possible
- **Monitoring**: Track query counts per request

```php
// ❌ BAD: N+1 queries in loop
foreach ($projectIds as $id) {
    $project = $this->db->find('projects', $id); // N queries
    $this->processProject($project);
}

// ✅ GOOD: Bulk fetch
$projects = $this->db->findMany('projects', $projectIds); // 1 query
foreach ($projects as $project) {
    $this->processProject($project);
}
```

#### Rule CQ-012: Transaction Usage
**Status**: 🔧 **NEEDS IMPLEMENTATION**
- **Rule**: Use database transactions for atomic operations
- **Required**: Implement transaction wrapper for migration operations

### 7. Memory Management Rules

#### Rule CQ-013: Memory Efficiency
**Status**: ⚠️ **NEEDS MONITORING**
- **Rule**: Monitor and optimize memory usage for large datasets
- **Standard**: Process data in batches for large migrations
- **Monitoring**: Track memory usage during migrations

### 8. Logging and Debugging Rules

#### Rule CQ-014: Structured Logging
**Status**: ✅ **IMPLEMENTED** (Monolog in use)
- **Rule**: Use structured logging with appropriate levels
- **Standards**: 
  - ERROR: For exceptions and failures
  - WARNING: For recoverable issues
  - INFO: For important events
  - DEBUG: For detailed troubleshooting

#### Rule CQ-015: Debug Information Security
**Status**: 🔧 **NEEDS REVIEW**
- **Rule**: Never expose sensitive information in logs or debug output
- **Required**: Review all logging statements for sensitive data

## Code Review Checklist

### Before Commit
- [ ] No methods exceed 50 lines
- [ ] No classes exceed 500 lines  
- [ ] Cyclomatic complexity ≤ 10
- [ ] All magic numbers replaced with constants
- [ ] Input validation implemented
- [ ] Proper exception handling
- [ ] Descriptive variable names
- [ ] No sensitive data in logs

### Before Release
- [ ] Static analysis passes (PHPStan level 6+)
- [ ] All tests pass with ≥80% coverage
- [ ] Security scan passes
- [ ] Performance benchmarks acceptable
- [ ] Documentation updated

## Automated Quality Gates

### Required Tools
1. **PHPStan** - Static analysis (level 6 minimum)
2. **PHP CS Fixer** - Code style enforcement
3. **PHPUnit** - Unit testing (≥80% coverage)
4. **Roave Security Advisories** - Dependency security
5. **Rector** - Code modernization

### CI/CD Pipeline Requirements
```yaml
# Example pipeline stage
quality_gates:
  - phpstan_analysis
  - code_style_check
  - unit_tests_coverage
  - security_audit
  - performance_benchmarks
```

## Refactoring Priorities

### Phase 1 (Critical)
1. **Bridge.php decomposition** - Split into service classes
2. **Exception hierarchy** - Implement custom exceptions
3. **Input validation** - Add comprehensive validation layer

### Phase 2 (High)
1. **Constants extraction** - Replace magic numbers
2. **Method extraction** - Reduce method complexity
3. **Transaction implementation** - Add database transaction support

### Phase 3 (Medium)
1. **Naming consistency** - Standardize variable names
2. **Query optimization** - Implement bulk operations
3. **Documentation** - Add comprehensive code documentation

## Metrics and Monitoring

### Code Quality Metrics
- **Maintainability Index**: Target ≥70
- **Cyclomatic Complexity**: Average ≤5, Max ≤10
- **Code Coverage**: ≥80%
- **Technical Debt Ratio**: ≤5%

### Performance Metrics
- **Memory Usage**: Monitor peak usage during migrations
- **Query Count**: Track N+1 query patterns
- **Response Time**: API endpoint performance
- **Error Rate**: Exception frequency and types

## Compliance and Standards

### PSR Compliance
- **PSR-1**: Basic coding standard
- **PSR-2**: Coding style guide (deprecated, use PSR-12)
- **PSR-12**: Extended coding style guide
- **PSR-4**: Autoloader standard (already implemented)

### Security Standards
- **Input Validation**: All external input validated
- **Output Encoding**: Prevent XSS in responses
- **Error Information**: No sensitive data exposure
- **Logging**: Secure logging practices

## Conclusion

These code quality rules address the specific issues identified in the MB-Migration project analysis. Implementation of these rules will:

1. **Improve Maintainability**: Smaller, focused classes and methods
2. **Enhance Security**: Better input validation and error handling
3. **Increase Reliability**: Consistent patterns and comprehensive testing
4. **Boost Performance**: Optimized queries and memory usage
5. **Reduce Technical Debt**: Automated quality gates and refactoring

Regular review and updates of these rules ensure continuous code quality improvement and alignment with evolving best practices.
