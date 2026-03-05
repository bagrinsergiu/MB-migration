---
name: brizy-pagedata
description: Work with Brizy PageData JSON structure for page content and layout. Use when editing blocksKit.json, building Brizy components in PHP, migrating Brizy layouts, or when the user mentions Brizy, PageData, blocksKit, BrizyComponent. Documentation: doc/brizy-pagedata/
---

# Brizy PageData — правила работы

Skill для работы с Brizy PageData. **Источник истины — локальная документация** `doc/brizy-pagedata/`.

---

## Обязательно: читай документацию

Перед работой с конкретным блоком **прочитай** соответствующий файл из `doc/brizy-pagedata/`:

| Задача / блок | Файл для чтения |
|---------------|-----------------|
| Section, SectionItem | `doc/brizy-pagedata/containers/section.md` |
| Row, mobileReverseColumns | `doc/brizy-pagedata/containers/row.md` |
| Column | `doc/brizy-pagedata/containers/column.md` |
| Wrapper | `doc/brizy-pagedata/containers/wrapper.md` |
| Cloneable | `doc/brizy-pagedata/containers/cloneable.md` |
| Button | `doc/brizy-pagedata/elements/button.md` |
| Image | `doc/brizy-pagedata/elements/image.md` |
| RichText | `doc/brizy-pagedata/elements/richtext.md` |
| Project Data, стили | `doc/brizy-pagedata/api/project-data.md` |
| Паттерны (padding, hover) | `doc/brizy-pagedata/patterns.md` |
| Таблицы, типы | `doc/brizy-pagedata/quick-reference.md` |
| Оглавление | `doc/brizy-pagedata/README.md` |

Если не уверен в структуре — открой `doc/brizy-pagedata/README.md` для навигации.

---

## Что такое PageData

**PageData** — JSON-структура, представляющая контент и layout страницы. Каждый элемент из Brizy Editor (заголовок, изображение, кнопка, секция) преобразуется в объект JSON со своей конфигурацией.

**Pipeline:** Editor → PageData JSON → React-рендеринг.

---

## Структура компонента

Каждый элемент PageData имеет вид:

```json
{
  "type": "Section|SectionItem|Row|Column|Wrapper|RichText|Button|Image|Spacer|Cloneable|...",
  "value": {
    "_id": "уникальный-id",
    "_styles": ["section", "section-item", ...],
    "items": [ /* вложенные компоненты */ ],
    /* остальные поля */
  },
  "blockId": "опционально"
}
```

**Обязательные поля в value:** `_id` (генерируется, если отсутствует), `_styles` (массив CSS-классов), `items` (вложенные элементы).

---

## Правила работы с PageData

### 1. Иерархия контейнеров

```
Section → items → SectionItem → items → Row → items → Column → items → Wrapper → items → [RichText|Button|Image|Spacer|...]
```

- **Section** — корневой блок страницы
- **SectionItem** — строка внутри секции (может содержать фон, padding)
- **Row** — горизонтальный ряд колонок
- **Column** — колонка внутри Row
- **Wrapper** — обёртка для контента (`wrapper--richText`, `wrapper--image`, `wrapper--spacer`, `wrapper--button`)
- **Cloneable** — обёртка для кнопок (`wrapper-clone--button`)

### 2. Именование полей

- Все ключи в camelCase: `mobileReverseColumns`, `showOnMobile`, `paddingType`
- Суффиксы единиц измерения: `paddingSuffix`, `mobilePaddingTopSuffix` (`"px"`, `"%"`)
- Breakpoint-префиксы: `mobile*`, `tablet*`, без префикса = desktop

### 3. Right-X секции (mobileReverseColumns)

Для секций, где заголовок/медиа справа на десктопе, на мобильном колонки стекируются в DOM-порядке. Заголовок в Column[1] окажется вторым — **неверно**.

**Решение:** в `Row.value` добавить `"mobileReverseColumns": "on"`.

```json
{
  "type": "Row",
  "value": {
    "mobileReverseColumns": "on",
    "items": [...]
  }
}
```

**Правило:** если секция имеет `-right-` в названии и layout `[text | header/media]`, всегда `mobileReverseColumns: "on"`.

### 4. Видимость по breakpoint

- `showOnMobile`, `showOnTablet`, `showOnDesktop`: `"on"` | `"off"`
- По умолчанию `"on"` — элемент видим

### 5. Выравнивание

- `horizontalAlign`, `mobileHorizontalAlign`: `'left'` | `'center'` | `'right'`
- `verticalAlign` (Column): `'top'` | `'center'` | `'bottom'`

### 6. Padding / Margin

- `paddingType`, `mobilePaddingType`: `'grouped'` | `'ungrouped'`
- При `ungrouped`: отдельные `paddingTop`, `paddingRight`, `paddingBottom`, `paddingLeft` с суффиксами
- При `grouped`: общий `padding` + `paddingSuffix`

### 7. Единицы измерения

Всегда указывать суффикс для размеров: `paddingRightSuffix: "px"`, `mobilePaddingLeftSuffix: "px"`.
Некорректные суффиксы приводят к визуальным артефактам.

---

## PHP: BrizyComponent и BrizyComponentValue

Проект использует классы в `lib/MBMigration/Builder/BrizyComponent/`:

**BrizyComponentValue** — доступ к полям через magic methods:

```php
$component->getValue()->set_mobileReverseColumns('on');
$component->getValue()->get_paddingType();
$component->getValue()->add_items($childComponent);
```

Имя метода = camelCase ключ JSON с префиксом `set_` / `get_` / `add_`.

**BrizyComponent::fromArray()** — фабрика по `type`:
- `row` → BrizyRowComponent
- `column` → BrizyColumComponent
- `line` → BrizyLineComponent
- иначе → BrizyComponent

**Навигация по дереву:**

```php
$section->getItemWithDepth(0)           // SectionItem
$section->getItemWithDepth(0, 0)        // Row
$section->getItemWithDepth(0, 0, 0)     // Column[0]
$section->getItemWithDepth(0, 0, 1)    // Column[1]
```

**Right-X:** заголовок в Column[1] → `getItemWithDepth(0, 0, 1)`.

---

## blocksKit.json

Шаблоны блоков в `lib/MBMigration/Builder/Layout/Theme/{ThemeName}/blocksKit.json`:

```json
{
  "blocks": {
    "block-key": {
      "main": "{...JSON строка...}"
    }
  }
}
```

Поле `main` — **строка** (экранированный JSON), не объект.

---

## Контрольный список для right-X секций

- [ ] Заголовок/медиа в Column[1]?
- [ ] `mobileReverseColumns: "on"` в Row?
- [ ] `getHeaderContainerComponent` / `getTextContainerComponent` возвращают правильные индексы?
- [ ] Верхняя линия скрыта на мобильном (`set_showOnMobile('off')`)?
- [ ] Нижняя линия центрирована на мобильном (`set_mobileHorizontalAlign('center')`)?

---

## Обязательные правила из документации

### _id

**Каждый** элемент в `value` должен иметь `_id`. Без `_id` редактор может упасть при рендере.

### Responsive (device prefixes)

- `mobile*` — мобильный (xs)
- `tablet*` — планшет (sm)
- Без префикса — desktop (lg)

Примеры: `mobilePaddingTop`, `tabletFontSize`, `mobileReverseColumns`, `tabletReverseColumns`.

### Hover (только desktop)

Hover-состояния только для desktop: `hoverBgColorHex`, `hoverBorderColorHex`, `hoverColorHex`. Tablet и mobile не поддерживают hover.

### Row: только Column в items

Row содержит **только** элементы типа Column. Любой другой контент — внутри Column.

### Column: только внутри Row

Column должен быть прямым потомком Row. Column нельзя вкладывать в Column.

### Wrapper: только один элемент в items

Wrapper оборачивает **один** дочерний элемент (RichText, Image, Map и т.п.).

### Cloneable: только Button или Icon

Cloneable — для дублирования кнопок/иконок **в одну строку**. Содержит только Button или Icon (нельзя смешивать).

---

## Документация (источник истины)

Все детали берутся из `doc/brizy-pagedata/`. Не полагайся на память — **читай файлы** перед правками.

```
doc/brizy-pagedata/
├── README.md              ← оглавление, поиск по блокам
├── 01-intro.md, 02-element-basics.md, 03-hierarchy.md
├── patterns.md            ← padding, margin, background, hover, link
├── quick-reference.md     ← таблицы типов и ключей
├── containers/            ← section, row, column, wrapper, cloneable
├── elements/              ← button, image, richtext, icon, spacer
└── api/project-data.md
```

Также: `doc/brizy-component-keys.md` — ключи для MB-Migration (mobilePadding, showOnMobile и т.д.).
