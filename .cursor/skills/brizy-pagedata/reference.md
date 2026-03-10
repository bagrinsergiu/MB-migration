# Brizy PageData — справочник структур

Дополнительные детали для [SKILL.md](SKILL.md).

**Полная локальная документация:** `doc/brizy-pagedata/README.md` — оглавление и поиск по блокам. Документация разделена на мелкие файлы.

---

## Типы элементов (type)

| type | Назначение | Ограничения |
|------|------------|-------------|
| Section | Корневой блок страницы | содержит SectionItem |
| SectionHeader | Шапка страницы | SectionHeaderItem, SectionHeaderStickyItem |
| SectionFooter | Подвал страницы | — |
| SectionItem | Строка секции (фон, padding) | внутри Section |
| Row | Горизонтальный ряд | **только** Column в items |
| Column | Колонка | **только** внутри Row |
| Wrapper | Обёртка контента | **один** элемент в items |
| Cloneable | Кнопки/иконки в строку | **только** Button или Icon |
| RichText | Текст/HTML | — |
| Button | Кнопка | — |
| Image | Изображение | — |
| Icon | Иконка | — |
| Spacer | Вертикальный отступ | — |
| Line | Горизонтальная линия | — |
| EmbedCode | Встроенный код | — |

---

## Value._styles — варианты Wrapper

- `wrapper`, `wrapper--richText` — текст
- `wrapper`, `wrapper--image` — изображение
- `wrapper`, `wrapper--spacer` — отступ
- `wrapper`, `wrapper--button` — кнопка (внутри Cloneable)
- `wrapper-clone`, `wrapper-clone--button` — обёртка кнопки

---

## Типичные поля value по типам

### SectionItem

- `bgImageSrc`, `bgImageWidth`, `bgImageHeight`
- `paddingType`, `paddingTop`, `paddingBottom`, `padding`, `paddingSuffix`
- `mobilePadding*`, `tabletPadding*`
- `mobileBgColorType`, `mobileBgColorHex`, `mobileBgColorOpacity`, `mobileBgColorPalette`

### Row

- `mobileReverseColumns`: `"on"` — инвертирует порядок колонок на мобильном

### Column

- `verticalAlign`: `'top'` | `'center'` | `'bottom'`
- `horizontalAlign`, `mobileHorizontalAlign`
- `showOnMobile`, `showOnTablet`, `showOnDesktop`

### Wrapper

- `showOnMobile`, `showOnTablet`, `showOnDesktop`
- `padding*`, `margin*`, `mobilePadding*`, `tabletPadding*`

### RichText

- `text` — HTML-строка (классы `brz-tp-heading1`, `brz-cp-color2`, etc.)

### Button

- `text`, `iconName`, `iconType`, `iconPosition`
- `fillType`, `borderRadius`, `paddingRL`, `paddingTB`
- `colorPalette`, `bgColorPalette`, `borderColorPalette`
- `hoverBgColor*`, `hoverBorderColor*`

### Image

- `imageSrc`, `imageWidth`, `imageHeight`
- `height`, `positionX`, `positionY`
- `mobileSize`, `mobileWidth`, `mobileHeight`, `mobileWidthSuffix`, `mobileHeightSuffix`

### Spacer

- `height`, `tabletHeight`, `mobileHeight`

---

## Пример иерархии (упрощённо)

```
Section
└── SectionItem
    └── Row
        ├── Column[0]
        │   └── Wrapper (wrapper--richText)
        │       └── RichText
        └── Column[1]
            └── Wrapper (wrapper--image)
                └── Image
```

---

## Project Data (глобальные настройки)

- `selectedKit`, `selectedStyle`, `styles`, `fonts`
- **colorPalette:** ровно 8 цветов (color1–color8)
- **fontStyles:** 10 шрифтов: button, heading6–1, abovetitle, subtitle, paragraph
- `deletable: "off"` для всех font styles
- Шрифты в `fonts.config.data`

---

## Ссылки на документацию

| Документ | URL |
|----------|-----|
| Intro | /docs/intro |
| What is Element | /docs/elements/what-is-a-brizy-element |
| Section | /docs/elements/containers/section |
| SectionHeader | /docs/elements/containers/section-header |
| Row | /docs/elements/containers/row |
| Column | /docs/elements/containers/column |
| Wrapper | /docs/elements/containers/wrapper |
| Cloneable | /docs/elements/containers/cloneable |
| Button | /docs/elements/button |
| Image | /docs/elements/image |
| RichText | /docs/elements/richtext |
| Project Data | /docs/api/project-data |

---

## Локальные материалы

- `doc/brizy-component-keys.md` — ключи mobile/tablet/desktop
- `BrizyComponentConstructorRules.md` — правила конструктора и фабрики
