# TASK-006: Оптимизация установки margin'ов

## Статус: ⏳ Ожидает выполнения

**Дата создания:** 2025-01-26  
**Приоритет:** Низкий  
**Зависит от:** TASK-002, TASK-003, TASK-004

---

## Описание задачи

Оптимизировать установку margin'ов: если margin равен 0, не устанавливать его явно, чтобы не засорять JSON и уменьшить размер выходных данных.

---

## Текущая реализация

### Что есть сейчас

В методе `handleSectionStyles()` мы всегда устанавливаем:
```php
->set_marginType('ungrouped')
->set_marginLeft((int)($sectionStyles['margin-left'] ?? 0))
->set_marginRight((int)($sectionStyles['margin-right'] ?? 0))
->set_marginTop((int)($sectionStyles['margin-top'] ?? 0))
->set_marginBottom((int)($sectionStyles['margin-bottom'] ?? 0))
```

Даже если все margin'ы равны 0, мы все равно устанавливаем `marginType: 'ungrouped'` и все margin'ы.

---

## Что нужно сделать

### Шаг 1: Проанализировать текущее использование

**Что делать:**
1. Проверить, сколько секций имеют ненулевые margin'ы
2. Оценить, сколько места в JSON занимают нулевые margin'ы
3. Определить, стоит ли оптимизировать

**Ожидаемый результат:**
- Оценка текущего использования margin'ов
- Определено, стоит ли оптимизировать

**Критерии готовности:**
- [ ] Проверено на реальных страницах
- [ ] Оценка задокументирована

---

### Шаг 2: Определить стратегию оптимизации

**Что делать:**
1. Определить, когда не устанавливать margin'ы:
   - Если все margin'ы равны 0?
   - Если только некоторые равны 0?
2. Определить, нужно ли устанавливать `marginType`, если margin'ы не устанавливаются
3. Учесть влияние на `additionalOptions`

**Ожидаемый результат:**
- Определена стратегия оптимизации
- Стратегия задокументирована

**Критерии готовности:**
- [ ] Стратегия определена
- [ ] Стратегия задокументирована

---

### Шаг 3: Реализовать оптимизацию

**Что делать:**
1. Изменить метод `handleSectionStyles()`
2. Добавить проверку на нулевые margin'ы
3. Устанавливать margin'ы только если они не равны 0
4. Устанавливать `marginType` только если есть ненулевые margin'ы

**Ожидаемый результат:**
- Оптимизация реализована
- Код работает правильно

**Критерии готовности:**
- [ ] Код изменен
- [ ] Протестировано
- [ ] Линтер не показывает ошибок

---

## Пример реализации

### Вариант 1: Не устанавливать margin'ы, если все равны 0

```php
$marginTop = (int)($sectionStyles['margin-top'] ?? 0);
$marginBottom = (int)($sectionStyles['margin-bottom'] ?? 0);
$marginLeft = (int)($sectionStyles['margin-left'] ?? 0);
$marginRight = (int)($sectionStyles['margin-right'] ?? 0);

// Устанавливаем marginType только если есть ненулевые margin'ы
if ($marginTop !== 0 || $marginBottom !== 0 || $marginLeft !== 0 || $marginRight !== 0) {
    $brizySection->getValue()->set_marginType('ungrouped');
    
    if ($marginTop !== 0) {
        $brizySection->getValue()->set_marginTop($marginTop);
    }
    if ($marginBottom !== 0) {
        $brizySection->getValue()->set_marginBottom($marginBottom);
    }
    if ($marginLeft !== 0) {
        $brizySection->getValue()->set_marginLeft($marginLeft);
    }
    if ($marginRight !== 0) {
        $brizySection->getValue()->set_marginRight($marginRight);
    }
}
```

### Вариант 2: Учитывать additionalOptions

```php
$marginTop = (int)($additionalOptions['marginTop'] ?? $sectionStyles['margin-top'] ?? 0);
$marginBottom = (int)($additionalOptions['marginBottom'] ?? $sectionStyles['margin-bottom'] ?? 0);
$marginLeft = (int)($additionalOptions['marginLeft'] ?? $sectionStyles['margin-left'] ?? 0);
$marginRight = (int)($additionalOptions['marginRight'] ?? $sectionStyles['margin-right'] ?? 0);

// Устанавливаем marginType только если есть ненулевые margin'ы
if ($marginTop !== 0 || $marginBottom !== 0 || $marginLeft !== 0 || $marginRight !== 0) {
    $brizySection->getValue()->set_marginType('ungrouped');
    
    if ($marginTop !== 0) {
        $brizySection->getValue()->set_marginTop($marginTop);
    }
    // ... и т.д.
}
```

**Примечание:** Нужно учесть, что `additionalOptions` применяются в цикле после установки базовых стилей, поэтому нужно быть осторожным.

---

## Результаты

**Статус:** ⏳ Ожидает выполнения

**Решение:** TBD

**Реализация:** TBD

**Эффект:** TBD

---

## Следующие шаги

После выполнения задачи:
1. Если оптимизация реализована - обновить документацию
2. Протестировать на реальных страницах
3. Измерить эффект (размер JSON до/после)

---

## Связанные файлы

- `../analysis/issues-found.md` - Идея #6
- `../analysis/current-implementation.md` - Текущая реализация
