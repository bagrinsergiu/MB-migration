# Текущая реализация отступов для секций

## Основной файл

`lib/MBMigration/Builder/Layout/Common/Concern/SectionStylesAble.php`

## Как извлекаются стили из DOM

### Метод `getSectionStyles()` (строки 465-502)

```php
protected function getSectionStyles(
    $sectionId,
    BrowserPageInterface $browserPage,
    array $families,
    string $defaultFont,
    $pseudoElement = null
)
```

**Что делает:**
- Использует селектор `[data-id="sectionId"]`
- Извлекает следующие свойства:
  - `padding-top`, `padding-bottom`, `padding-right`, `padding-left`
  - `margin-top`, `margin-bottom`, `margin-left`, `margin-right`
  - `background-color`, `background-image`, `background-size`
  - `opacity`, `height`, `position`
- Возвращает массив со стилями

### Метод `getSectionListStyle()` (строки 386-450)

**Что делает:**
- Получает базовые стили секции через `getSectionStyles()`
- Дополнительно получает стили фона:
  - `getBgEclipseStyles()` - для bg-eclipse
  - `getBgVideoStyles()` - для bg-video
  - `getBgHelperStyles()` - для bg-helper
- Объединяет все стили в один массив

## Как применяются стили

### Метод `handleSectionStyles()` (строки 92-169)

**Параметры:**
- `ElementContextInterface $data` - контекст элемента
- `BrowserPageInterface $browserPage` - браузерная страница
- `$additionalOptions = []` - дополнительные опции

**Что делает:**

1. **Получает стили секции:**
   ```php
   $sectionStyles = $this->getSectionListStyle($data, $browserPage);
   ```

2. **Обрабатывает фон и текстуру:**
   ```php
   $this->handleSectionBackground($brizySection, $mbSectionItem, $sectionStyles, $options);
   $this->handleSectionTexture($brizySection, $mbSectionItem, $sectionStyles, $options);
   ```

3. **Применяет padding и margin:**
   ```php
   ->set_paddingType('ungrouped')
   ->set_marginType('ungrouped')
   ->set_paddingTop((int)($sectionStyles['padding-top'] ?? 0))
   ->set_paddingBottom((int)($sectionStyles['padding-bottom'] ?? 0))
   ->set_paddingRight((int)($sectionStyles['padding-right'] ?? 0))
   ->set_paddingLeft((int)($sectionStyles['padding-left'] ?? 0))
   ->set_marginLeft((int)($sectionStyles['margin-left'] ?? 0))
   ->set_marginRight((int)($sectionStyles['margin-right'] ?? 0))
   ->set_marginTop((int)($sectionStyles['margin-top'] ?? 0))
   ->set_marginBottom((int)($sectionStyles['margin-bottom'] ?? 0))
   ```

4. **Применяет мобильные padding'ы:**
   ```php
   ->set_mobilePaddingType('ungrouped')
   ->set_mobilePadding((int)($additionalOptions['mobilePadding'] ?? $sectionStyles['padding-top'] ?? 0))
   ->set_mobilePaddingTop((int)($additionalOptions['mobilePaddingTop'] ?? $sectionStyles['padding-top'] ?? 0))
   ->set_mobilePaddingRight((int)($additionalOptions['mobilePaddingRight'] ?? $sectionStyles['padding-right'] ?? 0))
   ->set_mobilePaddingBottom((int)($additionalOptions['mobilePaddingBottom'] ?? $sectionStyles['padding-bottom'] ?? 0))
   ->set_mobilePaddingLeft((int)($additionalOptions['mobilePaddingLeft'] ?? $sectionStyles['padding-left'] ?? 0))
   ```
   
   **Исправление:** Ранее все мобильные padding'ы устанавливались из `margin-bottom`, что было ошибкой. Теперь они берутся из `additionalOptions` или соответствующих `padding-*` значений.

5. **Применяет дополнительные опции:**
   ```php
   foreach ($additionalOptions as $key => $value) {
       if (is_array($value)) {
           continue;
       }
       $method = 'set_' . $key;
       $brizySection->getValue()->$method($value);
   }
   ```
   
   **Важно:** `additionalOptions` применяются после установки базовых стилей, поэтому они имеют приоритет.

## Особенности

### Desktop стили
- Padding'ы берутся напрямую из `sectionStyles`
- Margin'ы берутся напрямую из `sectionStyles`
- Всегда устанавливается `paddingType: 'ungrouped'` и `marginType: 'ungrouped'`

### Mobile стили
- Padding'ы берутся из `additionalOptions` или `sectionStyles` (исправлено)
- Margin'ы **НЕ устанавливаются** из DOM (только через `additionalOptions`)

### Tablet стили
- **НЕ устанавливаются** из DOM
- Могут быть установлены через `additionalOptions`

## Использование в темах

### Boulevard
- `Head.php` - использует `handleSectionStyles()` с `additionalOptions`
- `Text/GridLayoutElement.php` - использует `handleSectionStyles()` с `getPropertiesMainSection()`
- `Text/AccordionLayoutElement.php` - использует `handleSectionStyles()` с `additionalOptions`
- `Text/TwoRightMediaCircle.php` - использует `handleSectionStyles()` без `additionalOptions`

### Другие темы
- Все темы используют общий трейт `SectionStylesAble`
- Некоторые темы переопределяют `getPropertiesMainSection()` для установки специфичных padding'ов

## Известные проблемы (до исправления)

1. ✅ **Исправлено:** Мобильные padding'ы устанавливались из `margin-bottom` вместо соответствующих `padding-*` значений
2. ⏳ **Проверяется:** Margin'ы между секциями могут не учитывать margin collapse
3. ⏳ **Проверяется:** Мобильные margin'ы не извлекаются из DOM
4. ⏳ **Проверяется:** Tablet margin'ы не извлекаются из DOM
