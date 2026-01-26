# TASK-010: Исправление порядка установки mobile margin'ов в Boulevard/GridLayoutElement

## Статус: ⏳ Ожидает выполнения

**Дата создания:** 2025-01-26  
**Приоритет:** Средний  
**Зависит от:** TASK-003

---

## Описание задачи

Исправить порядок установки mobile margin'ов в `Boulevard/GridLayoutElement.php`. Mobile margin'ы устанавливаются **ДО** вызова `handleSectionStyles()`, что может привести к их перезаписи через `additionalOptions`.

---

## Текущая реализация

### Проблемный код

**Файл:** `lib/MBMigration/Builder/Layout/Theme/Boulevard/Elements/Text/GridLayoutElement.php`  
**Строки:** 30-40

**Проблемный код:**
```php
$brizySection->getValue()
    ->set_mobileMarginType('ungrouped')
    ->set_mobileMarginTop(-10)
    ->set_mobileMarginBottom(-10);

$sectionItemComponent = $this->getSectionItemComponent($brizySection);
$elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

$this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());
```

**Проблема:**
- Mobile margin'ы устанавливаются **ДО** вызова `handleSectionStyles()`
- `handleSectionStyles()` может перезаписать эти значения через `additionalOptions`
- Порядок установки может привести к непредсказуемому поведению

---

## Что нужно сделать

### Шаг 1: Определить правильный порядок установки

**Что делать:**
1. Проверить, что возвращает `getPropertiesMainSection()` в этом классе
2. Определить, должны ли mobile margin'ы устанавливаться до или после `handleSectionStyles()`
3. Проверить, не перезаписываются ли они в `handleSectionStyles()`

**Ожидаемый результат:**
- Определен правильный порядок установки
- Понятно, где должны устанавливаться mobile margin'ы

**Критерии готовности:**
- [ ] Проверен код `getPropertiesMainSection()`
- [ ] Проверен код `handleSectionStyles()`
- [ ] Определен правильный порядок

---

### Шаг 2: Исправить порядок установки

**Что делать:**
1. Переместить установку mobile margin'ов **ПОСЛЕ** вызова `handleSectionStyles()`
2. Или добавить mobile margin'ы в `getPropertiesMainSection()`, чтобы они передавались через `additionalOptions`
3. Убедиться, что значения не перезаписываются

**Ожидаемый результат:**
- Mobile margin'ы устанавливаются в правильном порядке
- Значения не перезаписываются

**Критерии готовности:**
- [ ] Код исправлен
- [ ] Протестировано
- [ ] Линтер не показывает ошибок

---

## Пример исправления

### Вариант 1: Переместить после handleSectionStyles

```php
$sectionItemComponent = $this->getSectionItemComponent($brizySection);
$elementContext = $data->instanceWithBrizyComponent($sectionItemComponent);

$this->handleSectionStyles($elementContext, $this->browserPage, $this->getPropertiesMainSection());

// Устанавливаем mobile margin'ы после handleSectionStyles
$brizySection->getValue()
    ->set_mobileMarginType('ungrouped')
    ->set_mobileMarginTop(-10)
    ->set_mobileMarginBottom(-10);
```

### Вариант 2: Добавить в getPropertiesMainSection

```php
protected function getPropertiesMainSection(): array
{
    return [
        'mobileMarginType' => 'ungrouped',
        'mobileMarginTop' => -10,
        'mobileMarginBottom' => -10,
        // ... другие свойства
    ];
}
```

**Примечание:** Нужно выбрать вариант в зависимости от логики работы `handleSectionStyles()` и `additionalOptions`.

---

## Результаты

**Статус:** ⏳ Ожидает выполнения

**Решение:** TBD

**Исправления:** TBD

---

## Следующие шаги

После выполнения задачи:
1. Проверить, что mobile margin'ы правильно устанавливаются
2. Протестировать на реальных страницах с темой Boulevard
3. Убедиться, что значения не перезаписываются

---

## Связанные файлы

- `lib/MBMigration/Builder/Layout/Theme/Boulevard/Elements/Text/GridLayoutElement.php` - проблемный файл
- `../analysis/THEMES_ANALYSIS.md` - Проблема #3
