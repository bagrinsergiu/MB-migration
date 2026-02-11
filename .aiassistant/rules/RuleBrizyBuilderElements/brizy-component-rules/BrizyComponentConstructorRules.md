---
apply: on_demand
---

# Rules for the BrizyComponent Constructor and Related Classes

This document describes the rules, invariants, and usage practices of the BrizyComponent constructor and helper classes responsible for creating and managing Brizy structures (Row/Column/Wrapper/Image/Line/EmbedCode, etc.) in the MB-Migration project.

The document is intended for developers who build or modify Brizy component trees from JSON structures (Brizy Kit) and via object wrappers.

Related materials:
- docs/brizy-component-rules/Examples.md — practical examples.
- docs/brizy-component-rules/Nuances-and-FAQ.md — important nuances, errors, and frequently asked questions.
- docs/brizy-component-rules/Cheatsheet.md — a short API reference.

## Overview of Key Classes

- MBMigration\Builder\BrizyComponent\BrizyComponent — base component. Capabilities:
  - Initialize from an array of data (JSON) and hold a reference to its parent.
  - Automatically wrap the value field in BrizyComponentValue.
  - Act as a factory: BrizyComponent::fromArray(...) creates specialized components based on the type field (Row/Column/Line), otherwise returns the base component.
  - Provides numerous helpers for styling (padding/margin/color/typography/height/content alignment/mobile and tablet sizes, etc.).
  - Logs steps and errors via MBMigration\Core\Logger.
- MBMigration\Builder\BrizyComponent\BrizyComponentValue — value container:
  - Stores arbitrary value fields, including items (child components), automatically converting arrays into BrizyComponent::fromArray(...).
  - Supports set/get/add and magic methods set_*/get_*/add_*.
  - add(*, position) — insertion with support for negative indices and boundary limits.
  - Generates a unique _id if not provided.
- Component specializations:
  - BrizyRowComponent — type=Row, value._styles=["row"], value.items=[].
  - BrizyColumComponent — type=Column, value._styles=["column"], value.items=[].
  - BrizyLineComponent — type=Line, preconfigured styles and line properties.
  - BrizyWrapperComponent — type=Wrapper, value._styles=["wrapper", <variant>], value.items=[].
  - BrizyImageComponent — type=Image, prepared image structure.
  - BrizyEmbedCodeComponent — type=Wrapper with an embedded EmbedCode component.
  - BrizyComponentPage — simplified page wrapper (used less often in section builders).
- MBMigration\Builder\BrizyComponent\BrizyComponentBuilder — factory builder for creating sections/rows from the Brizy Kit (global.Row, etc.).

## Constructor and Factory Input Data

1. Base constructor BrizyComponent::__construct(array $data, ?BrizyComponent $parent=null)
  - Requires an array $data. Any other type will throw BadJsonProvided.
  - Mandatory (or expected) keys in $data:
    - type — string, the component type name (Row/Column/Line/Wrapper/Image, etc.). Optional but strongly recommended: influences factory behavior.
    - value — array with all component fields. If missing — an empty array is used.
    - blockId — optional: added to the object if present.
  - parent — optional: parent component. The relationship is stored for tree navigation and correct assembly of nested items.
  - Logging: the constructor writes key steps to the log, including data type, parent presence, and initialization result.

2. Factory BrizyComponent::fromArray(array $data, ?BrizyComponent $parent=null): BrizyComponent
  - Looks at strtolower($data['type']). Supports types:
    - 'row' => BrizyRowComponent
    - 'column' => BrizyColumComponent
    - 'line' => BrizyLineComponent
    - default => base BrizyComponent
  - Any exceptions from subtype constructors are caught; on error it safely falls back to the base BrizyComponent (with a warning in the logs).
  - On BadJsonProvided — the exception is rethrown (after logging).

3. Subtype constructors
  - Typically allow $data=null and in this case create a minimally valid JSON for themselves and then call parent::__construct($data, $parent).

## Invariants and Rules for Forming value

- Path to child components: $component->getValue()->get('items') — this is an array of BrizyComponent and/or “raw” arrays (if injected by external code). BrizyComponentValue’s constructor automatically converts arrays into objects via BrizyComponent::fromArray(...).
- The _id field
  - When constructing BrizyComponentValue a unique _id (a + 32 hex characters) is always set if not provided.
  - Some specialized components also have _id in their preset — overwriting is allowed.
- Magic methods
  - In BrizyComponentValue you can use: set_<field>, get_<field>, add_<field> with corresponding behavior.
  - add supports positional insertion: position=null (append), >=0 (insert with shift), <0 (offset from the end). Out-of-bounds indices are safely clamped.

## Working with Parent and Building the Tree

- Each component can have a parent (BrizyComponent|null). The parent is passed down when creating child items (see BrizyComponentValue::__construct, where fromArray is called with the same $parent).
- The getItemWithDepth() method and related helpers in BrizyComponent allow tree navigation and component search by type (findFirstByType).
- Recommended build order: create a container (Row/Column/Wrapper) => add child elements through value->add('items', <child>, <position?>).

## Styling and Settings (BrizyComponent Helpers)

BrizyComponent provides an API to modify value.* fields corresponding to Brizy JSON structures. Below are the most commonly used.

- Padding and margins:
  - addPadding($t,$r,$b,$l,$prefix,$measureType)
  - addGroupedPadding($p,$prefix,$measureType)
  - addMargin($t,$r,$b,$l,$prefix,$measureType)
  - addGroupedMargin($p,$prefix,$measureType)
  - addPaddingRight/Left, addMarginBottom, etc.
  - Mobile/tablet specific: addMobilePadding, addMobileMargin, addTabletPadding, addTabletMargin.
- Radius:
  - addRadius($radiusPx). There is a separate addMenuBorderRadius($radius) for menus.
- Content alignment:
  - addVerticalContentAlign, addHorizontalContentAlign, addMobileContentAlign, addMobileHorizontalContentAlign.
- Background and colors:
  - addBgColor($hex,$opacity), setMobileBgColorStyle($color,$opacity).
- Typography:
  - titleTypography(), previewTypography($t), typography($t), dataTypography($t), subscribeEventButtonTypography(), detailButtonTypography().
- Sizes:
  - sizeTypeOriginal(), mobileSizeTypeOriginal(), tabletSizeTypeOriginal().
  - mobileSize($size,$suffix), addHeight(int,$suffix), addHeightStyle(int,$suffix,$style), addSectionHeight(int,$suffix).
- Constructor helpers:
  - addConstructPadding/addConstructMargin($value,$position,$prefix,$measureType) — low-level universal inserts.
- Content:
  - addImage($mbSectionItem,$options,$position)
  - addLine($width,$color,$borderWidth,$options,$position,$align)
  - addRow($items,$position,$options)
- Miscellaneous:
  - addCustomCSS(string $css)

Important: most helpers write values into value.* and depend on correct suffix/type (“px”, “%”, etc.). Check allowed values for the specific Brizy element to avoid inconsistent JSON.

## Error Handling and Logging

- Any attempt to create a BrizyComponent with invalid input data (non-array) results in BadJsonProvided.
- The fromArray factory logs the subtype selection process and warnings/errors when falling back to the base type.
- Most public methods are designed to guard against fatal errors (e.g., add in BrizyComponentValue works correctly with a single value/list and positions), but it is assumed the calling code respects types and Brizy’s domain constraints.

## Extension Rules (Adding New Subtypes)

1. Create a new class in lib/MBMigration/Builder/BrizyComponent/ named Brizy<NewType>Component extends BrizyComponent.
2. In the constructor allow $data=null and form a minimally valid JSON template for this type.
3. Call parent::__construct($data, $parent).
4. Add a branch in BrizyComponent::fromArray for strtolower(type)==='<newtype>'.
5. If needed, add helpers in the base class or narrow-scope methods in the new subtype.

## JSON Schema (Simplified)

Example canonical component structure:


```
{
  "type": "Row",
  "value": {
    "_styles": ["row"],
    "items": [
      { "type": "Column", "value": { "_styles": ["column"], "items": [], "_id": "..." } }
    ],
    "_id": "...",
    "<другие-поля>": "..."
  },
  "blockId": "<опционально>"
}
```


Main expectations:
- type reflects the real widget/container type.
- value — an object with a set of fields; _styles — array of strings (Brizy CSS pseudo-classes), _id — unique.
- items — array of nested components (as objects or arrays that will be converted by the factory).

## Practical Recommendations

- Always generate _id for new value (or rely on BrizyComponentValue automation).
- For nested components use BrizyComponentValue::add('items', ...) or the magic add_items(...) — this ensures proper conversion and positioning.
- If you are unsure of the valid schema, use specialized subtype constructors (BrizyRowComponent, BrizyColumComponent, etc.). They already contain safe templates.
- Avoid manual interference with the structure managed by helpers: better call the corresponding method than write arbitrary fields.
- Check measurement unit suffixes (“px”, “%”, etc.) — incorrect suffixes often cause visual artifacts.
- Use Logger to diagnose complex build branches or invalid input.

## Interaction with BrizyComponentBuilder

- BrizyComponentBuilder::createSection() — creates a ComponentSection based on $this->brizyKit.
- BrizyComponentBuilder::createRow() — loads a row JSON (global.Row), decodes and wraps it in BrizyComponent. On invalid JSON — BadJsonProvided.
- The builder logs durations and errors, which is useful for profiling.

## Minimal Examples

See detailed examples and variations in Examples.md. Below — briefly.

- Create a row with a column and a line:
  - $row = new BrizyRowComponent();
  - $col = new BrizyColumComponent(null, $row);
  - $line = new BrizyLineComponent(null, $col);
  - $row->getValue()->add('items', $col);
  - $col->getValue()->add('items', $line);

- Add padding and background color:
  - $row->addGroupedPadding(20, 'padding', 'px')->addBgColor('#ffffff', 1);

## Integrity Check

- After building the tree serialize to JSON (json_encode($component)) and visually verify the structure of value/items/_styles.
- In case of display errors in the Brizy editor, check that:
  - type and _styles are consistent;
  - sizes and suffixes are valid;
  - nesting matches expectations (Row > Column > Wrapper/Element ...).

