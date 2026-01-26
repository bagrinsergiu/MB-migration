# TASK-008: Исправление mobile margin'ов в FullTextBlurBox

## Статус: ⏳ Ожидает выполнения

**Дата создания:** 2025-01-26  
**Приоритет:** Высокий  
**Зависит от:** TASK-003

---

## Описание задачи

Исправить установку mobile margin'ов в `FullTextBlurBox.php`. В текущей реализации mobile margin'ы устанавливаются из desktop margin'ов, что может быть неправильно.

---

## Текущая реализация

### Проблемный код

**Файл:** `lib/MBMigration/Builder/Layout/Theme/Aurora/Elements/Text/FullTextBlurBox.php`  
**Строки:** 603-613

**Проблемный код:**
```php
->set_mobileMarginType('ungrouped')
->set_mobileMargin((int)($sectionStyles['margin-bottom'] ?? 0))  // Используется desktop margin-bottom
->set_mobileMarginSuffix('px')
->set_mobileMarginTop((int)($sectionStyles['margin-top'] ?? 0))  // Используется desktop margin-top
->set_mobileMarginTopSuffix('px')
->set_mobileMarginRight((int)($sectionStyles['margin-right'] ?? 0))  // Используется desktop margin-right
->set_mobileMarginRightSuffix('px')
->set_mobileMarginBottom((int)($sectionStyles['margin-bottom'] ?? 0))  // Используется desktop margin-bottom
->set_mobileMarginBottomSuffix('px')
->set_mobileMarginLeft((int)($sectionStyles['margin-left'] ?? 0))  // Используется desktop margin-left
->set_mobileMarginLeftSuffix('px');
```

**Проблема:**
- Mobile margin'ы устанавливаются из desktop margin'ов (`$sectionStyles['margin-*']`)
- Это может быть неправильно, если в исходном сайте есть разные margin'ы для mobile и desktop
- Не учитываются медиа-запросы для mobile margin'ов

---

## Что нужно сделать

### Шаг 1: Проверить, нужно ли извлекать mobile margin'ы из DOM

**Что делать:**
1. Проверить, есть ли в исходных сайтах разные margin'ы для mobile и desktop
2. Определить, нужно ли извлекать mobile margin'ы из DOM (как в TASK-003)
3. Если нужно, определить механизм извлечения mobile margin'ов

**Ожидаемый результат:**
- Определено, нужно ли извлекать mobile margin'ы из DOM
- Если нужно, определен механизм извлечения

**Критерии готовности:**
- [ ] Проверено на реальных страницах
- [ ] Результаты задокументированы

---

### Шаг 2: Исправить установку mobile margin'ов

**Что делать:**
1. Если mobile margin'ы нужно извлекать из DOM:
   - Изменить код, чтобы использовать mobile margin'ы из DOM (если они есть)
   - Использовать desktop margin'ы как fallback (если mobile margin'ы не найдены)
2. Если mobile margin'ы не нужно извлекать из DOM:
   - Оставить текущую реализацию, но добавить комментарий, объясняющий почему
   - Или использовать `additionalOptions` для установки mobile margin'ов

**Ожидаемый результат:**
- Mobile margin'ы устанавливаются правильно

**Критерии готовности:**
- [ ] Код исправлен
- [ ] Протестировано
- [ ] Линтер не показывает ошибок

---

## Пример реализации

### Вариант 1: Если mobile margin'ы нужно извлекать из DOM

```php
// Сначала пытаемся получить mobile margin'ы из DOM
$mobileSectionStyles = $this->getMobileSectionStyles($data, $browserPage);  // Нужно создать метод

->set_mobileMarginType('ungrouped')
->set_mobileMargin((int)($additionalOptions['mobileMargin'] ?? $mobileSectionStyles['margin-bottom'] ?? $sectionStyles['margin-bottom'] ?? 0))
->set_mobileMarginSuffix('px')
->set_mobileMarginTop((int)($additionalOptions['mobileMarginTop'] ?? $mobileSectionStyles['margin-top'] ?? $sectionStyles['margin-top'] ?? 0))
->set_mobileMarginTopSuffix('px')
->set_mobileMarginRight((int)($additionalOptions['mobileMarginRight'] ?? $mobileSectionStyles['margin-right'] ?? $sectionStyles['margin-right'] ?? 0))
->set_mobileMarginRightSuffix('px')
->set_mobileMarginBottom((int)($additionalOptions['mobileMarginBottom'] ?? $mobileSectionStyles['margin-bottom'] ?? $sectionStyles['margin-bottom'] ?? 0))
->set_mobileMarginBottomSuffix('px')
->set_mobileMarginLeft((int)($additionalOptions['mobileMarginLeft'] ?? $mobileSectionStyles['margin-left'] ?? $sectionStyles['margin-left'] ?? 0))
->set_mobileMarginLeftSuffix('px');
```

### Вариант 2: Если mobile margin'ы не нужно извлекать из DOM

```php
// Используем desktop margin'ы для mobile (если это намеренное поведение)
// Или используем additionalOptions, если они переданы
->set_mobileMarginType('ungrouped')
->set_mobileMargin((int)($additionalOptions['mobileMargin'] ?? $sectionStyles['margin-bottom'] ?? 0))
->set_mobileMarginSuffix('px')
->set_mobileMarginTop((int)($additionalOptions['mobileMarginTop'] ?? $sectionStyles['margin-top'] ?? 0))
->set_mobileMarginTopSuffix('px')
// ... и т.д.
```

**Примечание:** Нужно сначала выполнить TASK-003, чтобы понять, нужно ли извлекать mobile margin'ы из DOM.

---

## Результаты

**Статус:** ⏳ Ожидает выполнения

**Решение:** TBD

**Исправления:** TBD

**Найденные проблемы:** TBD

---

## Следующие шаги

После выполнения задачи:
1. Если mobile margin'ы извлекаются из DOM - обновить документацию
2. Протестировать на реальных страницах
3. Проверить, что mobile margin'ы правильные на мобильных устройствах

---

## Связанные файлы

- `lib/MBMigration/Builder/Layout/Theme/Aurora/Elements/Text/FullTextBlurBox.php` - проблемный файл
- `TASK-003-mobile-margins.md` - Проверка mobile margin'ов
- `../analysis/MISSING_ISSUES_ANALYSIS.md` - Проблема #4
