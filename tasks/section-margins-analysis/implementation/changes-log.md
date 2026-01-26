# Лог изменений

## 2025-01-XX: Исправление мобильных padding'ов

### Изменения

**Файл:** `lib/MBMigration/Builder/Layout/Common/Concern/SectionStylesAble.php`

**Строки:** 139-148

**Было:**
```php
->set_mobilePaddingType('ungrouped')
->set_mobilePadding((int)($sectionStyles['margin-bottom'] ?? 0))
->set_mobilePaddingSuffix('px')
->set_mobilePaddingTop((int)($sectionStyles['margin-bottom'] ?? 0))
->set_mobilePaddingTopSuffix('px')
->set_mobilePaddingRight((int)($sectionStyles['margin-bottom'] ?? 0))
->set_mobilePaddingRightSuffix('px')
->set_mobilePaddingBottom((int)($sectionStyles['margin-bottom'] ?? 0))
->set_mobilePaddingBottomSuffix('px')
->set_mobilePaddingLeft((int)($sectionStyles['margin-bottom'] ?? 0))
->set_mobilePaddingLeftSuffix('px');
```

**Стало:**
```php
->set_mobilePaddingType('ungrouped')
->set_mobilePadding((int)($additionalOptions['mobilePadding'] ?? $sectionStyles['padding-top'] ?? 0))
->set_mobilePaddingSuffix('px')
->set_mobilePaddingTop((int)($additionalOptions['mobilePaddingTop'] ?? $sectionStyles['padding-top'] ?? 0))
->set_mobilePaddingTopSuffix('px')
->set_mobilePaddingRight((int)($additionalOptions['mobilePaddingRight'] ?? $sectionStyles['padding-right'] ?? 0))
->set_mobilePaddingRightSuffix('px')
->set_mobilePaddingBottom((int)($additionalOptions['mobilePaddingBottom'] ?? $sectionStyles['padding-bottom'] ?? 0))
->set_mobilePaddingBottomSuffix('px')
->set_mobilePaddingLeft((int)($additionalOptions['mobilePaddingLeft'] ?? $sectionStyles['padding-left'] ?? 0))
->set_mobilePaddingLeftSuffix('px');
```

### Причина

Все мобильные padding'ы устанавливались из `margin-bottom` вместо соответствующих `padding-*` значений, что было критической ошибкой.

### Результат

- ✅ Мобильные padding'ы теперь берутся из `additionalOptions` (если указаны) или из соответствующих `padding-*` значений
- ✅ Логика работы с `additionalOptions` сохранена (они имеют приоритет)
- ✅ Линтер не показывает ошибок

### Влияние

- Влияет на все темы, так как `SectionStylesAble` - общий трейт
- Исправляет проблемы с отступами на мобильных устройствах

---

## Планируемые изменения

### Проверка обработки margin'ов

**Статус:** ⏳ Планируется

**Что нужно сделать:**
1. Проверить, правильно ли извлекаются margin'ы из DOM
2. Проверить, правильно ли применяются margin'ы
3. Проверить, нужно ли обрабатывать margin collapse

---

### Добавление обработки мобильных margin'ов (если нужно)

**Статус:** ⏳ Планируется

**Что нужно сделать:**
1. Проверить, извлекаются ли мобильные margin'ы из DOM
2. Если нужно, добавить извлечение
3. Добавить применение мобильных margin'ов

---

### Добавление обработки tablet margin'ов (если нужно)

**Статус:** ⏳ Планируется

**Что нужно сделать:**
1. Проверить, извлекаются ли tablet margin'ы из DOM
2. Если нужно, добавить извлечение
3. Добавить применение tablet margin'ов
