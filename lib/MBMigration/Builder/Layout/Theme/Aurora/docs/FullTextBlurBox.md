# FullTextBlurBox Element Documentation

## Назначение

`FullTextBlurBox` - специализированный элемент для миграции секций с эффектом blur-box overlay. Элемент создает структуру с фоновым изображением и полупрозрачным overlay для текстового контента.

## Структура BlurBoxStyles

Элемент использует нормализованный массив `BlurBoxStyles` для управления всеми стилями:

```php
[
    'background' => [
        'imageUrl' => string,           // URL фонового изображения
        'imageFileName' => string,      // Имя файла изображения
        'size' => string,                // Размер фона (cover)
        'height' => int,                 // Высота секции (600px)
        'heightSuffix' => string,       // Суффикс высоты ('px')
        'padding' => [                  // Padding для desktop
            'type' => string,
            'top' => int,
            'right' => int,
            'bottom' => int,
            'left' => int,
            'suffix' => string
        ],
        'tabletPadding' => [...],       // Padding для tablet
        'mobilePadding' => [...],       // Padding для mobile
        'mobileMargin' => [...]         // Margin для mobile
    ],
    'overlay' => [
        'bgColorHex' => string,         // Цвет фона overlay (#000000)
        'bgColorOpacity' => float,      // Прозрачность overlay (0.45)
        'borderStyle' => string,        // Стиль границы (solid)
        'borderColorHex' => string,     // Цвет границы (#ffffff)
        'borderWidth' => int,           // Ширина границы (1)
        'borderTopWidth' => int,        // Ширина границы сверху
        'borderRightWidth' => int,      // Ширина границы справа
        'borderBottomWidth' => int,     // Ширина границы снизу
        'borderLeftWidth' => int,       // Ширина границы слева
        'padding' => [...],             // Padding overlay
        'margin' => [...],               // Margin overlay
        'mobileMargin' => [...]         // Margin для mobile
    ]
]
```

## Ключевые методы

### handleBlurBoxStyles()
Главный метод обработки стилей blur-box. Выполняет:
1. Проверку наличия `.blur-box` в секции
2. Сбор всех стилей через `collectBlurBoxStyles()`
3. Применение стилей к `outerColumn` (фон) и `innerColumn` (overlay)
4. Очистку фона с `SectionItem`

### collectBlurBoxStyles()
Собирает и нормализует все стили из DOM и настроек секции:
- Вызывает `collectBackgroundStyles()` для фона
- Вызывает `collectOverlayStyles()` для overlay
- Возвращает единый массив `BlurBoxStyles`

### collectBackgroundStyles()
Собирает стили фона для `outerColumn`:
- **Источники фонового изображения** (в порядке приоритета):
  1. Настройки секции (`mbSectionItem['settings']['sections']['background']`)
  2. DOM секции (`[data-id="{id}"]`)
  3. `.blur-box .has-background`
- **Padding**: извлекается из `.content-wrapper` (родитель `.group`)
- **Значения по умолчанию**: height=600px, size=cover

### collectOverlayStyles()
Собирает стили overlay для `innerColumn`:
- **Источники overlay** (в порядке приоритета):
  1. `.blur-box::before` - псевдоэлемент (основной источник)
  2. `.bg-opacity` - альтернативный источник
  3. `.group` - fallback источник
- **Background-color и opacity**: извлекаются из псевдоэлемента `::before`
- **Border**: приоритет `::before` > `.group`
- **Padding/Margin**: из `.group` с fallback на значения по умолчанию

### applyBackgroundStyles()
Применяет стили фона к `outerColumn`:
- Устанавливает фоновое изображение
- Настраивает размер, высоту, выравнивание
- Применяет padding для desktop/tablet/mobile
- Настраивает margin для mobile

### applyOverlayStyles()
Применяет стили overlay к `innerColumn`:
- Устанавливает цвет фона и прозрачность
- Настраивает границы (стиль, цвет, ширина)
- Применяет padding и margin
- Настраивает responsive стили для mobile

### clearSectionItemBackground()
Очищает фоновое изображение с `SectionItem` и удаляет его из `customCSS`.

## Источники стилей

### Фоновое изображение
1. `mbSectionItem['settings']['sections']['background']['photo']`
2. `[data-id="{id}"]` (DOM секции)
3. `[data-id="{id}"] .blur-box .has-background`

### Padding outerColumn
- `[data-id="{id}"] .content-wrapper` (padding-top, padding-right, padding-bottom, padding-left)
- Значения по умолчанию: 95px, 115px, 95px, 115px

### Overlay стили
- **Background-color и opacity**: `[data-id="{id}"] .blur-box::before`
- **Border**: `[data-id="{id}"] .blur-box::before` (приоритет) или `[data-id="{id}"] .group`
- **Padding/Margin**: `[data-id="{id}"] .group` (значения по умолчанию: padding=60px, margin-right/left=60px)

## Важные особенности

### Поддержка псевдоэлементов
Элемент поддерживает извлечение стилей из CSS псевдоэлемента `::before` у `.blur-box`, который является основным источником overlay стилей.

### Нормализация значений
- **Цвета**: `ColorConverter::rgba2hex()` и `ColorConverter::rgba2opacity()`
- **Числа**: `NumberProcessor::convertToInt()` для извлечения числовых значений из строк
- **Fallback значения**: используются при отсутствии стилей в DOM

### Модульная архитектура
Код разбит на отдельные методы для каждого этапа:
- Сбор стилей (collect*)
- Применение стилей (apply*)
- Очистка (clear*)

### Структура компонентов
- **SectionItem** - очищается от фона
- **outerColumn** (0,0,0) - получает фоновое изображение и padding
- **innerColumn** (0,0,0,0,0) - получает overlay стили (фон, границы, padding, margin)

## Использование

Элемент автоматически используется при миграции секций с классом `full-text-blur-box`:

```php
case 'full-text-blur-box':
    return new FullTextBlurBox($this->blockKit['blocks']['full-text-blur-box'], $browserPage);
```

Обработка стилей происходит в методе `afterTransformItem()`, который вызывает `handleBlurBoxStyles()`.
