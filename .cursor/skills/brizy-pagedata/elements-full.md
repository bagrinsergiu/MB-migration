# Brizy PageData — краткая сводка элементов

Краткая справка. **Подробная документация** — в `doc/brizy-pagedata/` с оглавлением и поиском по блокам.

---

## Навигация по документации

| Страница | URL |
|----------|-----|
| Intro | /docs/intro |
| What is Element | /docs/elements/what-is-a-brizy-element |
| Section | /docs/elements/containers/section |
| SectionHeader | /docs/elements/containers/section-header |
| SectionFooter | /docs/elements/containers/section-footer |
| Row | /docs/elements/containers/row |
| Column | /docs/elements/containers/column |
| Wrapper | /docs/elements/containers/wrapper |
| Cloneable | /docs/elements/containers/cloneable |
| Button | /docs/elements/button |
| Image | /docs/elements/image |
| RichText | /docs/elements/richtext |
| Icon | /docs/elements/icon |
| Project Data | /docs/api/project-data |

---

## 1. What is a Brizy Element

- Элемент = `type` (string) + `value` (object)
- `value` **обязательно** содержит `_id`
- Default values заполняются автоматически — достаточно указать переопределяемые ключи
- Responsive: префиксы `mobile`, `tablet`; desktop — без префикса
- Hover: `hover*` (только desktop)

---

## 2. Section

Корневой блок. Top-level key — `items`, массив Section или SectionHeader/SectionFooter.

**Структура:** Section содержит SectionItem (при `slider: "on"` — несколько SectionItem).

**Section.value:** padding, margin, height, fullHeight, showOnDesktop/Tablet/Mobile.

**SectionItem.value:** background (bgColor*, bgImage*, gradient*), containerType/Size, padding*, hover*, border*, boxShadow*.

---

## 3. SectionHeader

Тип `SectionHeader`. `value.items`:
- `SectionHeaderItem` — основной контент
- `SectionHeaderStickyItem` — опционально, для sticky-поведения

**value.type:** `"static"` | `"fixed"` | `"animated"`.

**SectionHeaderItem / SectionHeaderStickyItem:** padding, margin, background, containerType/Size, hover*.

---

## 4. Row

Горизонтальный ряд. **Только Column** в `items`.

**Ключи:** padding, margin, background, verticalAlign, size, columnsHeight, mobileReverseColumns, tabletReverseColumns, link*, hover*.

**Стили:** `["row"]` или `["row", "hide-row-borders", "padding-0"]`.

---

## 5. Column

Только внутри Row. Содержит Wrapper, RichText, Image и т.д.

**Ключи:** padding, margin, background, width, height, verticalAlign, showOn*, link*, hover*.

**DefaultValue:** `width: 50`, `mobileWidth: 100`.

---

## 6. Wrapper

Обёртка **одного** элемента (RichText, Image, Map).

**_styles:** `["wrapper"]` или `["wrapper", "wrapper--image"]`, `["wrapper", "wrapper--text"]`, `wrapper--richText`, `wrapper--spacer`, `wrapper--button`.

**Ключи:** padding, margin, horizontalAlign, zIndex, showOn*.

---

## 7. Cloneable

Дублирует Button или Icon **в одну строку**. Нельзя смешивать типы.

**_styles:** `["wrapper-clone", "wrapper-clone--button"]` или `wrapper-clone--icon`.

**items:** массив Button или Icon (все одного типа).

---

## 8. Button

**Ключи:** text, size (small/medium/large/custom), fillType (filled/outlined/default), iconName, iconPosition, iconType, paddingRL/TB, colorPalette, bgColor*, border*, borderRadius, link*, hover*.

---

## 9. Image

**Ключи:** imageSrc, sizeType (original/custom), size, positionX/Y, width, height, alt, linkLightBox, zoom, imageBrightness/Contrast/Saturation/Hue, link*, hover*.

**sizeType "original":** size (%), positionX, positionY.
**sizeType "custom":** width, height, zoom.

---

## 10. RichText

**text:** HTML внутри wrapper-тегов: `<p>`, `<h1>`–`<h6>`, `<pre>`, `<ul>`/`<ol>`, `<li>`.
Внутри — `<span>` для текста, `<a data-href="{encoded}">` для ссылок.

**Классы:** brz-ff-*, brz-fs-xs/sm/lg-*, brz-lh-*, brz-cp-color*.

**link:** URI-encoded JSON: `{type, external, externalBlank, externalRel, externalType}`.

---

## 11. Project Data (API)

Глобальные настройки: styles, colorPalette, fontStyles, fonts.

**colorPalette:** ровно 8 цветов (color1–color8).
**fontStyles:** 10 обязательных в порядке: button, heading6–1, abovetitle, subtitle, paragraph.
**deletable:** `"off"` для всех font styles.
Шрифты должны быть в `fonts.config.data`.

---

## Общие паттерны

### Padding / Margin
- `*Type`: grouped | ungrouped
- grouped: `padding` + `paddingSuffix`
- ungrouped: `paddingTop`, `paddingRight`, `paddingBottom`, `paddingLeft` + суффиксы

### Background
- `bgColorHex`, `bgColorOpacity`, `bgColorPalette`, `bgColorType`
- `bgImageSrc`, `bgImageWidth`, `bgImageHeight`, `bgSize`, `bgPosition`, `bgRepeat`

### Hover
- `hoverBgColor*`, `hoverBorderColor*`, `hoverBoxShadow*`, `hoverTransition`

### Link (external)
- `linkType`, `linkExternal`, `linkExternalBlank`, `linkExternalRel`
