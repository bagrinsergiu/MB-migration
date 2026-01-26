# TASK-004: Проверка и добавление tablet margin'ов

## Статус: ⏳ Ожидает выполнения

**Дата создания:** 2025-01-26  
**Приоритет:** Средний  
**Зависит от:** TASK-003

---

## Описание задачи

Проверить, нужно ли извлекать и применять tablet margin'ы и padding'ы из DOM для секций. В текущей реализации tablet margin'ы и padding'ы не извлекаются из DOM и могут быть установлены только через `additionalOptions`.

**⚠️ КРИТИЧНО:** В методе `handleSectionStyles()` полностью отсутствует обработка tablet padding'ов и margin'ов, в то время как desktop и mobile стили обрабатываются.

---

## Текущая реализация

### Что есть сейчас

1. **Desktop margin'ы:** Извлекаются и применяются ✅
2. **Desktop padding'ы:** Извлекаются и применяются ✅
3. **Mobile margin'ы:** НЕ извлекаются, могут быть установлены через `additionalOptions` ⚠️
4. **Mobile padding'ы:** Извлекаются и применяются ✅ (исправлено в TASK-001)
5. **Tablet margin'ы:** ❌ **НЕ извлекаются, НЕ применяются** (критическая проблема)
6. **Tablet padding'ы:** ❌ **НЕ извлекаются, НЕ применяются** (критическая проблема)

### Breakpoints в Brizy

Согласно документации:
- **Desktop:** > 991px
- **Tablet:** 768px - 991px
- **Mobile:** < 768px

---

## Что нужно сделать

### Шаг 0: Проверить текущую реализацию tablet padding'ов и margin'ов

**Что делать:**
1. Проверить метод `handleSectionStyles()` в `SectionStylesAble.php`
2. Убедиться, что tablet padding'ы и margin'ы не обрабатываются
3. Проверить, используются ли tablet padding'ы/margin'ы в других местах кода

**Ожидаемый результат:**
- Подтверждено, что tablet padding'ы и margin'ы не обрабатываются в `handleSectionStyles()`
- Найдены места, где tablet padding'ы/margin'ы используются (например, в `blocksKit.json`)

**Критерии готовности:**
- [ ] Проверен код `handleSectionStyles()`
- [ ] Найдены примеры использования tablet padding'ов/margin'ов
- [ ] Результаты задокументированы

---

### Шаг 1: Проверить, есть ли tablet margin'ы и padding'ы в исходных сайтах

**Что делать:**
1. Открыть реальные страницы в браузере
2. Переключиться в tablet режим (768px - 991px width)
3. Проверить computed styles для секций (margin'ы и padding'ы)
4. Проверить, есть ли tablet margin'ы и padding'ы в CSS (media queries)
5. Сравнить значения с desktop и mobile версиями

**Ожидаемый результат:**
- Определено, есть ли tablet margin'ы и padding'ы в исходных сайтах
- Если есть, задокументированы их значения
- Определено, отличаются ли они от desktop/mobile значений

**Критерии готовности:**
- [ ] Проверено на 2+ реальных страницах
- [ ] Проверено на разных tablet размерах (768px, 991px)
- [ ] Результаты задокументированы

---

### Шаг 2: Определить, нужно ли извлекать tablet margin'ы и padding'ы

**Что делать:**
1. Проанализировать результаты шага 1
2. Определить, есть ли в исходных сайтах tablet margin'ы и padding'ы
3. Если есть, определить, нужно ли их извлекать и применять
4. Учесть результаты TASK-003 (mobile margin'ы)
5. Учесть, что Brizy поддерживает tablet padding'ы и margin'ы

**Ожидаемый результат:**
- Принято решение: нужно ли извлекать tablet margin'ы и padding'ы
- Обоснование решения задокументировано

**Критерии готовности:**
- [ ] Решение принято
- [ ] Обоснование задокументировано

---

### Шаг 3: Если нужно, добавить извлечение tablet margin'ов и padding'ов

**Что делать:**
1. Изучить, как извлекаются tablet стили в браузере (нужно ли изменять viewport?)
2. Добавить извлечение tablet margin'ов и padding'ов в метод `getSectionStyles()` или создать отдельный метод
3. Убедиться, что извлечение работает правильно для tablet viewport

**Ожидаемый результат:**
- Tablet margin'ы и padding'ы извлекаются из DOM (если нужно)

**Критерии готовности:**
- [ ] Код добавлен
- [ ] Протестировано на реальных страницах
- [ ] Линтер не показывает ошибок

---

### Шаг 4: Если нужно, добавить применение tablet margin'ов и padding'ов

**Что делать:**
1. Добавить применение tablet margin'ов и padding'ов в метод `handleSectionStyles()`
2. Использовать логику, аналогичную мобильным padding'ам:
   - Сначала проверять `additionalOptions`
   - Затем использовать значения из DOM
   - Затем использовать 0
3. Убедиться, что устанавливаются все необходимые свойства:
   - `tabletPaddingType`, `tabletPaddingTop`, `tabletPaddingBottom`, `tabletPaddingLeft`, `tabletPaddingRight`
   - `tabletMarginType`, `tabletMarginTop`, `tabletMarginBottom`, `tabletMarginLeft`, `tabletMarginRight`
   - Соответствующие суффиксы (`Suffix`)

**Ожидаемый результат:**
- Tablet margin'ы и padding'ы применяются к секциям (если нужно)

**Критерии готовности:**
- [ ] Код добавлен
- [ ] Протестировано
- [ ] Линтер не показывает ошибок

---

## Пример реализации (если нужно)

```php
// В handleSectionStyles() после мобильных padding'ов (строка 148)
// Tablet padding'ы
->set_tabletPaddingType('ungrouped')
->set_tabletPadding((int)($additionalOptions['tabletPadding'] ?? $sectionStyles['tablet-padding-top'] ?? $sectionStyles['padding-top'] ?? 0))
->set_tabletPaddingSuffix('px')
->set_tabletPaddingTop((int)($additionalOptions['tabletPaddingTop'] ?? $sectionStyles['tablet-padding-top'] ?? $sectionStyles['padding-top'] ?? 0))
->set_tabletPaddingTopSuffix('px')
->set_tabletPaddingRight((int)($additionalOptions['tabletPaddingRight'] ?? $sectionStyles['tablet-padding-right'] ?? $sectionStyles['padding-right'] ?? 0))
->set_tabletPaddingRightSuffix('px')
->set_tabletPaddingBottom((int)($additionalOptions['tabletPaddingBottom'] ?? $sectionStyles['tablet-padding-bottom'] ?? $sectionStyles['padding-bottom'] ?? 0))
->set_tabletPaddingBottomSuffix('px')
->set_tabletPaddingLeft((int)($additionalOptions['tabletPaddingLeft'] ?? $sectionStyles['tablet-padding-left'] ?? $sectionStyles['padding-left'] ?? 0))
->set_tabletPaddingLeftSuffix('px');

// Tablet margin'ы
->set_tabletMarginType('ungrouped')
->set_tabletMargin((int)($additionalOptions['tabletMargin'] ?? $sectionStyles['tablet-margin-top'] ?? 0))
->set_tabletMarginSuffix('px')
->set_tabletMarginTop((int)($additionalOptions['tabletMarginTop'] ?? $sectionStyles['tablet-margin-top'] ?? 0))
->set_tabletMarginTopSuffix('px')
->set_tabletMarginRight((int)($additionalOptions['tabletMarginRight'] ?? $sectionStyles['tablet-margin-right'] ?? 0))
->set_tabletMarginRightSuffix('px')
->set_tabletMarginBottom((int)($additionalOptions['tabletMarginBottom'] ?? $sectionStyles['tablet-margin-bottom'] ?? 0))
->set_tabletMarginBottomSuffix('px')
->set_tabletMarginLeft((int)($additionalOptions['tabletMarginLeft'] ?? $sectionStyles['tablet-margin-left'] ?? 0))
->set_tabletMarginLeftSuffix('px');
```

**Примечания:**
1. Нужно сначала проверить, как извлекать tablet стили из браузера (нужно ли изменять viewport?)
2. Если tablet стили не извлекаются из DOM, можно использовать desktop стили как fallback (как в примере для padding'ов)
3. Нужно убедиться, что `tablet-*` ключи правильно извлекаются из DOM

---

## Результаты

**Статус:** ⏳ Ожидает выполнения

**Решение:** TBD

**Найденные проблемы:** TBD

**Рекомендации:** TBD

---

## Следующие шаги

После выполнения задачи:
1. Если tablet margin'ы добавлены - обновить документацию
2. Перейти к TASK-005 (другие механизмы отступов)

---

## Связанные файлы

- `../analysis/issues-found.md` - Проблема #3
- `../analysis/real-pages-analysis.md` - Анализ реальных страниц
- `TASK-003-mobile-margins.md` - Проверка mobile margin'ов
