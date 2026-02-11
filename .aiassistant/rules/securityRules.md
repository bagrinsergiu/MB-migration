---
apply: on_demand
---

# Security Rules for MB-Migration Project

## Overview

This document establishes security standards and rules for the MB-Migration project, addressing security vulnerabilities, compliance requirements, and best practices for handling sensitive data during migration processes.

## Security Assessment Status

### Current Security Posture: ⚠️ **MODERATE RISK**
- **Good**: Using prepared statements (SQL injection prevention)
- **Concern**: End-of-life PHP version (7.4)
- **Gap**: Insufficient input validation
- **Missing**: Security headers and CSRF protection

## Input Security Rules

### Rule SEC-001: Input Validation and Sanitization
**Status**: ❌ **INSUFFICIENT**
- **Current Issue**: Basic `isset()` checks only
- **Risk Level**: HIGH
- **Impact**: Potential injection attacks, data corruption

```php
// ❌ CURRENT: Insufficient validation
$mb_project_uuid = $this->request->get('mb_project_uuid');
if (!isset($mb_project_uuid)) {
    throw new Exception('Invalid mb_project_uuid', 400);
}

// ✅ REQUIRED: Comprehensive validation
class InputValidator {
    public function validateProjectUuid(string $input): string {
        // Check for null/empty
        if (empty($input)) {
            throw new ValidationException('Project UUID is required');
        }
        
        // Sanitize input
        $sanitized = filter_var($input, FILTER_SANITIZE_STRING);
        
        // Validate UUID format
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $sanitized)) {
            throw new ValidationException('Invalid UUID format');
        }
        
        return $sanitized;
    }
    
    public function validateProjectId(mixed $input): int {
        if (!is_numeric($input) || $input <= 0) {
            throw new ValidationException('Invalid project ID');
        }
        
        return (int)filter_var($input, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => PHP_INT_MAX]
        ]);
    }
}
```

### Rule SEC-002: Request Parameter Whitelisting
**Status**: 🔧 **NEEDS IMPLEMENTATION**
- **Rule**: Only accept known, expected parameters
- **Implementation**: Create parameter validation schemas

```php
// ✅ REQUIRED: Parameter whitelisting
class MigrationRequestValidator {
    private const ALLOWED_PARAMETERS = [
        'mb_project_uuid' => 'uuid',
        'brz_project_id' => 'integer',
        'brz_workspaces_id' => 'integer',
        'mb_page_slug' => 'slug',
        'mgr_manual' => 'boolean'
    ];
    
    public function validateRequest(Request $request): array {
        $validated = [];
        
        foreach (self::ALLOWED_PARAMETERS as $param => $type) {
            $value = $request->get($param);
            if ($value !== null) {
                $validated[$param] = $this->validateByType($value, $type, $param);
            }
        }
        
        // Detect unexpected parameters
        $unexpected = array_diff_key($request->query->all(), self::ALLOWED_PARAMETERS);
        if (!empty($unexpected)) {
            throw new SecurityException('Unexpected parameters: ' . implode(', ', array_keys($unexpected)));
        }
        
        return $validated;
    }
}
```

## Database Security Rules

### Rule SEC-003: SQL Injection Prevention
**Status**: ✅ **IMPLEMENTED** (Prepared statements in use)
- **Current**: Using PDO with parameter binding
- **Verification**: ✅ No `mysql_query()` usage found
- **Maintenance**: Regular code reviews for raw SQL usage

```php
// ✅ CURRENT: Good prepared statement usage
$this->db->delete('migrations_mapping', 'id = ?', [(int)$value]);

// ❌ AVOID: Raw SQL concatenation (not found in current code)
$query = "DELETE FROM migrations_mapping WHERE id = " . $id; // NEVER DO THIS
```

### Rule SEC-004: Database Connection Security
**Status**: 🔧 **NEEDS REVIEW**
- **Rule**: Use secure database connections with proper credentials
- **Requirements**:
  - SSL/TLS connections in production
  - Minimal database privileges
  - Connection timeout limits
  - Credential rotation policy

```php
// ✅ REQUIRED: Secure database configuration
$dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4;sslmode=require";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
    PDO::ATTR_TIMEOUT => 30
];
```

## API Security Rules

### Rule SEC-005: API Authentication and Authorization
**Status**: 🔧 **NEEDS IMPLEMENTATION**
- **Current Issue**: No visible authentication mechanism
- **Risk Level**: CRITICAL
- **Required**: Implement API key or OAuth authentication

```php
// ✅ REQUIRED: API authentication middleware
class APIAuthenticationMiddleware {
    public function authenticate(Request $request): void {
        $apiKey = $request->headers->get('X-API-Key');
        
        if (empty($apiKey)) {
            throw new UnauthorizedException('API key required');
        }
        
        if (!$this->isValidApiKey($apiKey)) {
            throw new UnauthorizedException('Invalid API key');
        }
        
        // Rate limiting
        if ($this->isRateLimited($apiKey)) {
            throw new TooManyRequestsException('Rate limit exceeded');
        }
    }
}
```

### Rule SEC-006: HTTPS Enforcement
**Status**: 🔧 **NEEDS VERIFICATION**
- **Rule**: All API communications must use HTTPS
- **Implementation**: Force HTTPS redirects, HSTS headers

```php
// ✅ REQUIRED: HTTPS enforcement
class SecurityHeadersMiddleware {
    public function addSecurityHeaders(Response $response): Response {
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Content-Security-Policy', "default-src 'self'");
        
        return $response;
    }
}
```

## Error Handling Security

### Rule SEC-007: Secure Error Messages
**Status**: ❌ **VIOLATION FOUND**
- **Current Issue**: Potentially exposing internal information in exceptions
- **Risk Level**: MEDIUM
- **Fix Required**: Sanitize error messages for public consumption

```php
// ❌ CURRENT: Potential information leakage
} catch (Exception $e) {
    $this->prepareResponseMessage($e->getMessage(), 'error', $e->getCode());
}

// ✅ REQUIRED: Sanitized error responses
class ErrorHandler {
    public function handleException(Exception $e): array {
        // Log full error details for debugging
        Logger::instance()->error('Exception occurred', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        // Return sanitized message to user
        return [
            'error' => $this->getSafeErrorMessage($e),
            'code' => $this->getSafeErrorCode($e),
            'request_id' => $this->generateRequestId()
        ];
    }
    
    private function getSafeErrorMessage(Exception $e): string {
        // Only expose safe messages to users
        if ($e instanceof ValidationException) {
            return $e->getMessage(); // Safe to expose validation errors
        }
        
        return 'An internal error occurred. Please contact support with request ID.';
    }
}
```

## Logging Security Rules

### Rule SEC-008: Secure Logging Practices
**Status**: ⚠️ **NEEDS REVIEW**
- **Rule**: Never log sensitive information
- **Current**: Monolog in use, need to audit log statements
- **Sensitive Data**: Passwords, API keys, personal information, UUIDs in some contexts

```php
// ❌ AVOID: Logging sensitive information
Logger::instance()->info('Processing migration', [
    'user_password' => $password,  // NEVER LOG PASSWORDS
    'api_key' => $apiKey,         // NEVER LOG API KEYS
    'credit_card' => $ccNumber    // NEVER LOG FINANCIAL DATA
]);

// ✅ REQUIRED: Safe logging
Logger::instance()->info('Processing migration', [
    'project_id' => $projectId,
    'user_id' => $userId,
    'migration_type' => $type,
    'timestamp' => time()
]);
```

### Rule SEC-009: Log Injection Prevention
**Status**: 🔧 **NEEDS IMPLEMENTATION**
- **Rule**: Sanitize all data before logging
- **Implementation**: Use structured logging with proper escaping

```php
// ✅ REQUIRED: Safe log data handling
class SecureLogger {
    public function logMigrationEvent(string $event, array $data): void {
        // Sanitize log data
        $sanitizedData = array_map(function($value) {
            if (is_string($value)) {
                // Remove potential log injection characters
                return preg_replace('/[\r\n\t]/', ' ', $value);
            }
            return $value;
        }, $data);
        
        Logger::instance()->info($event, $sanitizedData);
    }
}
```

## File Upload Security

### Rule SEC-010: File Upload Validation
**Status**: 🔧 **NEEDS ASSESSMENT**
- **Rule**: If file uploads are supported, implement comprehensive validation
- **Requirements**:
  - File type validation (whitelist approach)
  - File size limits
  - Virus scanning
  - Secure file storage

```php
// ✅ REQUIRED: If file uploads are implemented
class SecureFileUploader {
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'application/pdf'
    ];
    
    private const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
    
    public function validateUpload(UploadedFile $file): void {
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new ValidationException('File too large');
        }
        
        // Validate MIME type
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw new ValidationException('Invalid file type');
        }
        
        // Check for malicious content (basic)
        $content = file_get_contents($file->getPathname());
        if (strpos($content, '<?php') !== false || strpos($content, '<script') !== false) {
            throw new SecurityException('Potentially malicious file detected');
        }
    }
}
```

## Session Security

### Rule SEC-011: Session Management
**Status**: 🔧 **NEEDS ASSESSMENT**
- **Rule**: Implement secure session handling if sessions are used
- **Requirements**:
  - Secure session configuration
  - Session regeneration
  - Timeout handling

```php
// ✅ REQUIRED: If sessions are used
class SecureSessionManager {
    public function startSecureSession(): void {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 1);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.cookie_samesite', 'Strict');
        
        session_start();
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['last_regeneration'])) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        } elseif (time() - $_SESSION['last_regeneration'] > 300) {
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }
}
```

## Dependency Security

### Rule SEC-012: Dependency Management
**Status**: ❌ **CRITICAL ISSUE**
- **Current Issue**: PHP 7.4 is End-of-Life (no security updates)
- **Risk Level**: CRITICAL
- **Required Actions**:
  1. Immediate PHP upgrade to 8.1+
  2. Regular dependency security audits
  3. Automated vulnerability scanning

```bash
# ✅ REQUIRED: Regular security audits
composer audit                    # Check for known vulnerabilities
composer require roave/security-advisories:dev-latest  # Block insecure packages
```

### Rule SEC-013: Third-Party Service Security
**Status**: ⚠️ **NEEDS REVIEW**
- **Services Used**: AWS S3, Brizy API, Ministry Brands API
- **Requirements**:
  - API key rotation
  - Service-specific security configurations
  - Network security (firewall rules)

## Browser Automation Security

### Rule SEC-014: Chrome Browser Security
**Status**: ⚠️ **NEEDS REVIEW**
- **Current**: Using chrome-php/chrome for automation
- **Security Concerns**:
  - Sandbox configuration
  - Resource limits
  - Network restrictions

```php
// ✅ REQUIRED: Secure browser configuration
class SecureBrowserManager {
    public function createSecureBrowser(): Browser {
        $options = [
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--disable-gpu',
            '--remote-debugging-port=0',  // Disable remote debugging
            '--disable-web-security=false',
            '--user-data-dir=/tmp/chrome-session-' . uniqid(),
            '--timeout=30000'
        ];
        
        return new Browser($options);
    }
}
```

## Compliance and Monitoring

### Rule SEC-015: Security Monitoring
**Status**: 🔧 **NEEDS IMPLEMENTATION**
- **Requirements**:
  - Failed authentication logging
  - Unusual activity detection
  - Performance anomaly monitoring
  - Security event alerting

```php
// ✅ REQUIRED: Security event monitoring
class SecurityMonitor {
    public function logSecurityEvent(string $event, array $context): void {
        $securityLog = [
            'event' => $event,
            'timestamp' => date('c'),
            'ip_address' => $this->getClientIP(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'context' => $context
        ];
        
        Logger::instance()->warning('SECURITY_EVENT: ' . $event, $securityLog);
        
        // Alert for critical events
        if (in_array($event, ['FAILED_AUTH', 'SUSPICIOUS_REQUEST', 'RATE_LIMIT_EXCEEDED'])) {
            $this->sendSecurityAlert($securityLog);
        }
    }
}
```

## Data Protection and Privacy

### Rule SEC-016: Sensitive Data Handling
**Status**: 🔧 **NEEDS ASSESSMENT**
- **Rule**: Identify and protect all sensitive data
- **Potential Sensitive Data**:
  - Project content and configurations
  - User credentials and API keys
  - Migration logs with business data

```php
// ✅ REQUIRED: Data classification and protection
class DataProtectionManager {
    public function classifyData(array $data): array {
        $classified = [];
        
        foreach ($data as $key => $value) {
            if ($this->isSensitive($key)) {
                $classified[$key] = $this->encrypt($value);
            } else {
                $classified[$key] = $value;
            }
        }
        
        return $classified;
    }
    
    private function isSensitive(string $key): bool {
        $sensitiveFields = [
            'password', 'api_key', 'token', 'secret',
            'credit_card', 'ssn', 'personal_data'
        ];
        
        return in_array(strtolower($key), $sensitiveFields);
    }
}
```

## Security Checklist

### Pre-Production Security Review
- [ ] PHP version upgraded to 8.1+
- [ ] All dependencies security-audited
- [ ] Input validation implemented for all endpoints
- [ ] Error messages sanitized
- [ ] Security headers configured
- [ ] HTTPS enforced
- [ ] Authentication/authorization implemented
- [ ] Logging audited for sensitive data
- [ ] Rate limiting implemented
- [ ] File upload security (if applicable)
- [ ] Session security configured
- [ ] Browser automation sandboxed
- [ ] Security monitoring active

### Regular Security Maintenance
- [ ] Weekly dependency updates
- [ ] Monthly security audits
- [ ] Quarterly penetration testing
- [ ] Annual security policy review

## Threat Model

### High-Risk Threats
1. **Unauthorized API Access** - No authentication visible
2. **Data Injection** - Insufficient input validation
3. **Information Disclosure** - Detailed error messages
4. **Outdated Software** - PHP 7.4 EOL

### Medium-Risk Threats
1. **Session Hijacking** - Session security needs review
2. **Log Injection** - Need log data sanitization
3. **CSRF Attacks** - No visible CSRF protection

### Low-Risk Threats
1. **SQL Injection** - Well-protected with prepared statements
2. **XSS** - API nature reduces risk, but validate anyway

## Conclusion

The MB-Migration project requires immediate security improvements, particularly:

1. **Critical**: PHP version upgrade (security compliance)
2. **High**: API authentication implementation
3. **High**: Comprehensive input validation
4. **Medium**: Error message sanitization
5. **Medium**: Security monitoring implementation

These security rules provide a roadmap for achieving production-ready security standards and maintaining ongoing security compliance.
