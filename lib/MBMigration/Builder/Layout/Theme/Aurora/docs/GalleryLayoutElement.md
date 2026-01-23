# GalleryLayoutElement Documentation

## Назначение

`GalleryLayoutElement` - специализированный элемент для миграции галерей изображений (слайдеров) из Clover Sites в Brizy Builder. Элемент извлекает стили каждого слайда из DOM через Playwright и применяет их только к Column (картинке), не затрагивая бэграунд секции.

## Сумаризация реализации

### Текущая рабочая реализация

**Принцип работы:**
1. **Бэграунд секции не изменяется** - используется стандартная логика из абстрактного класса `SectionStylesAble`
2. **Стили применяются только к картинкам** - метод `applySlideStyles()` применяет стили только к Column (картинке), полученной через `getItemWithDepth(0,0,0)`
3. **Извлечение стилей из DOM** - метод `handleStyle()` получает стили каждого слайда через Playwright и нормализует их

**Структура компонентов:**
```
Section (main)
  └── SectionItem (slide) - не изменяется, бэграунд работает стандартно
      └── Row
          └── Column (getItemWithDepth(0,0,0)) - к этому компоненту применяются стили
              └── bgImageSrc, height, padding, margin, border-radius
```

**Применяемые стили к Column:**
- `height`, `heightStyle` - высота картинки
- `bgSize`, `bgSizeType` - размер фонового изображения
- `padding*` (desktop, tablet, mobile) - отступы
- `margin*` (desktop, mobile) - внешние отступы
- `borderRadius*` - радиусы границ
- `opacity` - прозрачность

### Почему предыдущая реализация была проблемной

**Проблема 1: Переопределение логики бэграунда секции**
- Переопределялись методы `handleSectionBackground()`, `getSectionListStyle()`, `handleSectionTexture()`
- Это ломало стандартную логику работы с бэграундом секции из `SectionStylesAble`
- Результат: секция получала только один цвет (#91F2B2) и opacity 0, вместо правильного бэграунда

**Проблема 2: Применение стилей к SectionItem**
- Метод `applySlideStyles()` применял стили к SectionItem (слайдеру)
- Устанавливал `bgColorType = 'none'` и `bgColorOpacity = 0` на SectionItem
- Это перекрывало слайды и создавало визуальные проблемы

**Проблема 3: Сложная логика с градиентами и fallback**
- Попытки переопределить логику градиентов из `beforeBuildPage()`
- Сложные проверки и перезаписывание стилей
- Это создавало конфликты и непредсказуемое поведение

### Почему текущая реализация работает

**Причина 1: Не трогает бэграунд секции**
- Не переопределяет методы работы с бэграундом секции
- Использует стандартную логику из `SectionStylesAble` через `handleSectionStyles()`
- Бэграунд секции работает как и раньше, без изменений

**Причина 2: Фокус только на картинках**
- Метод `applySlideStyles()` применяет стили только к Column (картинке)
- Column получается через `getItemWithDepth(0,0,0)` - это компонент внутри Row внутри SectionItem
- SectionItem (слайдер) не изменяется, поэтому не перекрывает слайды

**Причина 3: Простая и понятная логика**
- `handleStyle()` - только получает стили из DOM
- `normalizeSlideStyles()` - только нормализует стили (размеры, отступы, радиусы)
- `applySlideStyles()` - только применяет стили к Column
- Нет сложной логики с градиентами, fallback на body, перезаписыванием стилей

**Причина 4: Правильная структура компонентов**
- Section → SectionItem → Row → Column
- Стили применяются к Column (картинке), а не к SectionItem (слайдеру)
- Это соответствует структуре шаблона и не создает конфликтов

## Ключевые особенности

### Извлечение стилей через Playwright

Элемент использует Playwright для получения вычисленных CSS-стилей из DOM:
- Извлечение стилей секции через `[data-id="{sectionId}"]` (для fallback)
- Извлечение стилей каждого слайда через `.slick-slide:nth-child(N)`, `.slide:nth-child(N)`
- Fallback на стили секции, если стили конкретного слайда не найдены
- **Важно**: Не извлекает `background-color` и `background-image` - бэграунд секции работает стандартно

### Применение стилей только к картинкам

Элемент применяет стили только к Column (картинке), не затрагивая SectionItem:
- **SectionItem** (слайдер): не изменяется, бэграунд работает стандартно
- **Column** (картинка): получает стили (height, padding, margin, border-radius, bgSize, opacity)
- Стили применяются через `applySlideStyles()` только к `getItemWithDepth(0,0,0)`

## Структура SlidesStyles

Элемент возвращает массив нормализованных стилей для каждого слайда. **Важно**: стили применяются только к Column (картинке), не к SectionItem.

```php
[
    [
        'height' => int,                     // Высота картинки (ВАЖНО: для Column)
        'heightStyle' => 'custom',           // Стиль высоты
        'bgSize' => string,                  // Размер фона (cover/contain/auto)
        'bgSizeType' => 'original',          // Тип размера
        'paddingType' => 'ungrouped',        // Тип padding
        'paddingTop' => int,                 // Padding сверху
        'paddingRight' => int,              // Padding справа
        'paddingBottom' => int,             // Padding снизу
        'paddingLeft' => int,               // Padding слева
        'paddingSuffix' => 'px',            // Суффикс padding
        'paddingTopSuffix' => 'px',        // Суффикс padding top
        'paddingRightSuffix' => 'px',       // Суффикс padding right
        'paddingBottomSuffix' => 'px',      // Суффикс padding bottom
        'paddingLeftSuffix' => 'px',       // Суффикс padding left
        'tabletPaddingType' => 'ungrouped', // Тип padding для tablet
        'tabletPaddingTop' => int,          // Padding top для tablet
        'tabletPaddingBottom' => int,       // Padding bottom для tablet
        'tabletPaddingTopSuffix' => 'px',   // Суффикс padding top для tablet
        'tabletPaddingBottomSuffix' => 'px', // Суффикс padding bottom для tablet
        'mobilePaddingType' => 'ungrouped',  // Тип padding для mobile
        'mobilePaddingTop' => int,          // Padding top для mobile
        'mobilePaddingRight' => int,        // Padding right для mobile
        'mobilePaddingBottom' => int,       // Padding bottom для mobile
        'mobilePaddingLeft' => int,         // Padding left для mobile
        'mobilePaddingSuffix' => 'px',      // Суффикс padding для mobile
        'marginType' => 'ungrouped',        // Тип margin
        'marginTop' => int,                 // Margin сверху
        'marginRight' => int,               // Margin справа
        'marginBottom' => int,              // Margin снизу
        'marginLeft' => int,                // Margin слева
        'marginSuffix' => 'px',            // Суффикс margin
        'mobileMarginType' => 'ungrouped',   // Тип margin для mobile
        'mobileMargin' => int,              // Margin для mobile
        'borderRadius' => int,              // Радиус границы
        'borderTopLeftRadius' => int,       // Радиус границы сверху слева
        'borderTopRightRadius' => int,      // Радиус границы сверху справа
        'borderBottomLeftRadius' => int,    // Радиус границы снизу слева
        'borderBottomRightRadius' => int,   // Радиус границы снизу справа
        'opacity' => float,                 // Прозрачность элемента
    ],
    // ... стили для каждого слайда
]
```

**Отсутствуют в структуре:**
- `bgColorHex`, `bgColorOpacity`, `bgColorType` - не применяются к картинкам
- `bgImageSrc` - устанавливается в `setSlideImage()`, не в стилях

## Ключевые методы

### handleStyle()

Главный метод обработки стилей слайдов. Выполняет:

1. **Извлечение стилей секции** (для fallback):
   - Селектор: `[data-id="{sectionId}"]`
   - Получает только размеры, отступы, радиусы (не background-color/image)
   - Используется как fallback, если стили слайда не найдены

2. **Извлечение стилей каждого слайда**:
   - Селекторы: `.slick-slide:nth-child(N)`, `.slide:nth-child(N)`
   - Получает: height, padding, margin, border-radius, background-size, opacity
   - Fallback на стили секции, если конкретный слайд не найден

3. **Нормализация стилей**:
   - Вызывает `normalizeSlideStyles()` для каждого слайда
   - Возвращает массив нормализованных стилей для применения к Column

**Важно**: Не извлекает `background-color` и `background-image` - бэграунд секции работает стандартно через `handleSectionStyles()`.

### normalizeSlideStyles()

Нормализует CSS-стили в формат параметров BrizyComponent. **Важно**: нормализует только размеры и отступы, не трогает бэграунд.

- **Height**: 
  - Извлекает высоту из `height` или `min-height`
  - Значение по умолчанию: 650px
  - Применяется к Column (картинке)

- **Background Size**: 
  - Нормализует `background-size` (cover/contain/auto)
  - Применяется к Column для настройки размера фонового изображения

- **Padding/Margin**: 
  - Извлекает числовые значения через `extractNumericValue()`
  - Создает responsive значения:
    - Tablet: padding = 20% от desktop
    - Mobile: padding = 40-60% от desktop, margin = 10px

- **Border Radius**: 
  - Извлекает значения для всех углов
  - Fallback на общий `border-radius`, если отдельные углы не заданы

- **Opacity**: 
  - Извлекает прозрачность элемента

### applySlideStyles()

Применяет нормализованные стили **только к Column (картинке)**, не к SectionItem.

**Применение к Column:**
- `height`, `heightStyle` - высота картинки
- `bgSize`, `bgSizeType` - размер фонового изображения
- `padding*` (desktop, tablet, mobile) - отступы
- `margin*` (desktop, mobile) - внешние отступы
- `borderRadius*` - радиусы границ
- `opacity` - прозрачность

**Защищенные свойства:**
- Не перезаписывает свойства, установленные в `setSlideImage()`:
  - `bgImageSrc`, `bgImageFileName`, `width`, `height`, `customCSS`, `sizeType`

**Важно**: SectionItem (слайдер) не изменяется - бэграунд секции работает стандартно через `handleSectionStyles()`.

### setSlideImage()

**Не переопределен в Aurora** - используется стандартная реализация из абстрактного класса.

Стандартная реализация:
- Устанавливает `bgImageSrc` и `bgImageFileName` на Column
- Устанавливает `customCSS` с цветом фона
- Настраивает размеры изображения (width, height, mobile, tablet)

## Источники стилей

### Стили секции (для fallback)
- Селектор: `[data-id="{sectionId}"]`
- Свойства: height, padding, margin, border-radius, background-size, opacity
- **Не извлекает**: background-color, background-image (бэграунд секции работает стандартно)

### Стили слайдов
- Селекторы: 
  - `.slick-slide:nth-child(N)`
  - `.slide:nth-child(N)`
- Свойства: height, padding, margin, border-radius, background-size, opacity
- Fallback: стили секции, если конкретный слайд не найден

**Важно**: Бэграунд секции (background-color, background-image) обрабатывается стандартным методом `handleSectionStyles()` из `SectionStylesAble`, не через `handleStyle()`.

## Важные особенности

### Разделение ответственности

**Бэграунд секции:**
- Обрабатывается стандартным методом `handleSectionStyles()` из `SectionStylesAble`
- Не переопределяется в Aurora
- Работает как и раньше, без изменений

**Стили картинок:**
- Обрабатываются методом `handleStyle()` в Aurora
- Применяются только к Column (картинке)
- Не затрагивают SectionItem (слайдер)

### Структура компонентов

```
Section (main)
  └── handleSectionStyles() - стандартная логика, не переопределяется
      └── SectionItem (slide) - бэграунд работает стандартно
          └── Row
              └── Column (getItemWithDepth(0,0,0)) - к этому применяются стили
                  ├── bgImageSrc (из setSlideImage)
                  ├── height (из applySlideStyles)
                  ├── padding* (из applySlideStyles)
                  ├── margin* (из applySlideStyles)
                  ├── borderRadius* (из applySlideStyles)
                  └── bgSize (из applySlideStyles)
```

### Нормализация значений

- **Цвета**: `ColorConverter::rgba2hex()` и `ColorConverter::rgba2opacity()`
- **Числа**: `extractNumericValue()` - извлекает числовое значение из строки (например, "10px" → 10)
- **Background Image**: `extractBackgroundImageUrl()` - извлекает URL из `url(...)`
- **Background Size**: `normalizeBackgroundSize()` - нормализует размер фона (cover/contain/auto)

### Responsive стили

Элемент автоматически создает responsive стили:
- **Tablet**: уменьшенные значения padding (20% от desktop)
- **Mobile**: уменьшенные значения padding (40-60% от desktop) и margin

## Использование

Элемент автоматически используется при миграции секций типа `gallery-layout`:

```php
case 'gallery-layout':
    return new GalleryLayoutElement($this->blockKit['blocks']['gallery-layout'], $browserPage);
```

Обработка стилей происходит в методе `internalTransformToItem()`:
1. Вызывается `handleStyle()` для получения стилей
2. Для каждого слайда вызывается `setSlideImage()` для установки изображения
3. Вызывается `applySlideStyles()` для применения стилей

## Отличия от абстрактного класса

Aurora переопределяет следующие методы:

- `handleStyle()` - извлечение стилей слайдов через Playwright (в абстрактном классе возвращает пустой массив)
- `normalizeSlideStyles()` - нормализация стилей для Column (картинки)
- `applySlideStyles()` - применение стилей только к Column, не к SectionItem

**Не переопределяет:**
- `handleSectionStyles()` - используется стандартная логика из `SectionStylesAble`
- `setSlideImage()` - используется стандартная реализация из абстрактного класса

Это позволяет другим темам использовать базовую функциональность, а Aurora - специфичную логику для правильной миграции стилей картинок в слайдерах.

## Уроки из предыдущей проблемной реализации

### Что не нужно делать

1. **Не переопределять методы работы с бэграундом секции**
   - `handleSectionBackground()`, `getSectionListStyle()`, `handleSectionTexture()`
   - Это ломает стандартную логику и создает непредсказуемое поведение

2. **Не применять стили к SectionItem**
   - SectionItem должен работать стандартно
   - Стили нужно применять только к Column (картинке)

3. **Не усложнять логику**
   - Не нужно переопределять логику градиентов, fallback на body
   - Простая логика: получить стили → нормализовать → применить к Column

### Что нужно делать

1. **Использовать стандартную логику для бэграунда секции**
   - Позволить `handleSectionStyles()` работать стандартно
   - Не переопределять методы из `SectionStylesAble`

2. **Фокус только на картинках**
   - Получать стили слайдов из DOM
   - Применять стили только к Column (картинке)
   - Не трогать SectionItem (слайдер)

3. **Простая и понятная логика**
   - `handleStyle()` - получить стили
   - `normalizeSlideStyles()` - нормализовать стили
   - `applySlideStyles()` - применить к Column
