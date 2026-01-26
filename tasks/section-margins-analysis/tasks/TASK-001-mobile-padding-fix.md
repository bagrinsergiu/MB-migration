# TASK-001: Исправление мобильных padding'ов

## Статус: ✅ Выполнено

**Дата создания:** 2025-01-26  
**Дата выполнения:** 2025-01-26  
**Приоритет:** Критический

---

## Описание проблемы

В файле `SectionStylesAble.php` (строки 139-148) все мобильные padding'ы устанавливались из `margin-bottom` вместо соответствующих `padding-*` значений.

### Проблемный код

```php
->set_mobilePadding((int)($sectionStyles['margin-bottom'] ?? 0))
->set_mobilePaddingTop((int)($sectionStyles['margin-bottom'] ?? 0))
->set_mobilePaddingRight((int)($sectionStyles['margin-bottom'] ?? 0))
->set_mobilePaddingBottom((int)($sectionStyles['margin-bottom'] ?? 0))
->set_mobilePaddingLeft((int)($sectionStyles['margin-bottom'] ?? 0))
```

---

## Что было сделано

### 1. Исправлен код

**Файл:** `lib/MBMigration/Builder/Layout/Common/Concern/SectionStylesAble.php`  
**Строки:** 139-148

**Исправление:**
```php
->set_mobilePadding((int)($additionalOptions['mobilePadding'] ?? $sectionStyles['padding-top'] ?? 0))
->set_mobilePaddingTop((int)($additionalOptions['mobilePaddingTop'] ?? $sectionStyles['padding-top'] ?? 0))
->set_mobilePaddingRight((int)($additionalOptions['mobilePaddingRight'] ?? $sectionStyles['padding-right'] ?? 0))
->set_mobilePaddingBottom((int)($additionalOptions['mobilePaddingBottom'] ?? $sectionStyles['padding-bottom'] ?? 0))
->set_mobilePaddingLeft((int)($additionalOptions['mobilePaddingLeft'] ?? $sectionStyles['padding-left'] ?? 0))
```

### 2. Логика исправления

1. Сначала проверяются `additionalOptions` (если они переданы через `getPropertiesMainSection()`)
2. Если их нет, используются соответствующие `padding-*` значения из `sectionStyles`
3. Если и их нет, используется 0

### 3. Проверка

- ✅ Линтер не показывает ошибок
- ✅ Логика работы с `additionalOptions` сохранена (они имеют приоритет)

---

## Влияние

- Влияет на все темы, так как `SectionStylesAble` - общий трейт
- Исправляет проблемы с отступами на мобильных устройствах

---

## Связанные файлы

- `../analysis/BOULEVARD_SECTION_STYLES_ANALYSIS.md` - Первоначальный анализ
- `../implementation/changes-log.md` - Лог изменений

---

## Результат

✅ Задача выполнена успешно. Мобильные padding'ы теперь правильно извлекаются и применяются.
