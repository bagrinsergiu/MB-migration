# Список задач

## Общий статус

- **Всего задач:** 10
- **Выполнено:** 2
- **В процессе:** 0
- **Ожидает:** 8

---

## Задачи

### ✅ TASK-001: Исправление мобильных padding'ов
**Статус:** ✅ Выполнено  
**Приоритет:** Критический  
**Файл:** [TASK-001-mobile-padding-fix.md](./TASK-001-mobile-padding-fix.md)

---

### ⏳ TASK-002: Проверка обработки desktop margin'ов
**Статус:** ⏳ Ожидает выполнения  
**Приоритет:** Высокий  
**Файл:** [TASK-002-desktop-margins-check.md](./TASK-002-desktop-margins-check.md)

---

### ⏳ TASK-003: Проверка и добавление мобильных margin'ов
**Статус:** ⏳ Ожидает выполнения  
**Приоритет:** Средний  
**Файл:** [TASK-003-mobile-margins.md](./TASK-003-mobile-margins.md)

---

### ⏳ TASK-004: Проверка и добавление tablet margin'ов
**Статус:** ⏳ Ожидает выполнения  
**Приоритет:** Средний  
**Файл:** [TASK-004-tablet-margins.md](./TASK-004-tablet-margins.md)

---

### ⏳ TASK-005: Проверка отступов через другие механизмы
**Статус:** ⏳ Ожидает выполнения  
**Приоритет:** Средний  
**Файл:** [TASK-005-other-spacing-mechanisms.md](./TASK-005-other-spacing-mechanisms.md)

---

### ⏳ TASK-006: Оптимизация установки margin'ов
**Статус:** ⏳ Ожидает выполнения  
**Приоритет:** Низкий  
**Файл:** [TASK-006-margin-optimization.md](./TASK-006-margin-optimization.md)

---

### ⚠️ TASK-007: Исправление setTopPaddingOfTheFirstElement
**Статус:** ⏳ Ожидает выполнения  
**Приоритет:** Высокий  
**Файл:** [TASK-007-setTopPaddingOfTheFirstElement-fix.md](./TASK-007-setTopPaddingOfTheFirstElement-fix.md)

**Описание:** Исправить установку mobileMarginType/mobileMarginTop и добавить обработку tablet padding'ов/margin'ов

---

### ⚠️ TASK-008: Исправление mobile margin'ов в FullTextBlurBox
**Статус:** ⏳ Ожидает выполнения  
**Приоритет:** Высокий  
**Зависит от:** TASK-003  
**Файл:** [TASK-008-FullTextBlurBox-mobile-margins-fix.md](./TASK-008-FullTextBlurBox-mobile-margins-fix.md)

**Описание:** Исправить установку mobile margin'ов из desktop margin'ов

---

### ✅ TASK-009: Исправление опечатки в Aurora/FullText.php
**Статус:** ✅ Выполнено  
**Приоритет:** Критический  
**Файл:** [TASK-009-aurora-fulltext-typo-fix.md](./TASK-009-aurora-fulltext-typo-fix.md)

**Описание:** Исправить опечатку `set_marginBottum` → `set_marginBottom`

---

### ⚠️ TASK-010: Исправление порядка установки mobile margin'ов в Boulevard/GridLayoutElement
**Статус:** ⏳ Ожидает выполнения  
**Приоритет:** Средний  
**Зависит от:** TASK-003  
**Файл:** [TASK-010-boulevard-gridlayout-order-fix.md](./TASK-010-boulevard-gridlayout-order-fix.md)

**Описание:** Исправить порядок установки mobile margin'ов (до/после handleSectionStyles)

---

## Порядок выполнения

1. ✅ TASK-001 (выполнено)
2. ✅ TASK-009 (выполнено - опечатка исправлена)
3. ⏳ TASK-002 (следующая)
4. ⏳ TASK-003
5. ⏳ TASK-004 (обновлена - теперь включает tablet padding'ы)
6. ⏳ TASK-005
7. ⚠️ TASK-007 (высокий приоритет - исправление багов)
8. ⚠️ TASK-008 (высокий приоритет - исправление багов, зависит от TASK-003)
9. ⚠️ TASK-010 (средний приоритет - порядок установки)
10. ⏳ TASK-006

---

## Связанные файлы

- `../analysis/issues-found.md` - Найденные проблемы
- `../planning/checklist.md` - Чеклист выполнения
- `../implementation/changes-log.md` - Лог изменений
