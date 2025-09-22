---
apply: always
---

# Important Nuances and FAQ for BrizyComponent

## Structure and Type Nuances

- **type** and **_styles** must be consistent. For example, for Row `_styles` is expected to be ["row"], for Column — ["column"]. Inconsistency may not throw an error at the PHP level but will lead to incorrect rendering.
- **value.items** is an array of child components. It can contain either existing BrizyComponent instances or “raw” arrays. The BrizyComponentValue constructor automatically converts arrays using BrizyComponent::fromArray(...).
- **_id** must be unique within a page/section. BrizyComponentValue ensures generation if it’s not set.

## Positional Insertion via value->add('items', ..., $position)

- **position == null** — add to the end.
- **position >= 0** — insert with a right shift. If greater than the length — it will add to the end.
- **position < 0** — negative indices are counted from the end (e.g., -1 — before the last). Values outside the range are normalized to [0, len].
- If the field is not yet an array, **add** will convert it into an array and then perform insertion.

## Factory and Error Rollbacks

- **fromArray** uses strtolower(type). For unknown or unsupported types, the base BrizyComponent is returned.
- If a specialized constructor throws an exception (not BadJsonProvided), the factory logs a warning and returns the base BrizyComponent (resilience to unstable JSON).
- For **BadJsonProvided** the exception is re-thrown — this most often indicates an invalid input format (for example, not an array).

## Parents and Nesting

- The **parent** field is stored in each component and passed when building items. This helps navigate the tree and apply styles in context when needed.
- Some higher-level functions (search by type, depth calculation) rely on the correctness of **parent**.

## Styles and Measurement Units

- Most methods expect suffixes ("px", "%", etc.). An incorrect suffix won’t throw a PHP error but the JSON will become incompatible with the visual editor’s expectations.
- For mobile/tablet settings, use specialized methods (addMobilePadding, addTabletMargin, etc.).

## Performance

- Mass creation of elements through **fromArray** can be expensive. Whenever possible, use specialized constructors (Row/Column/Line/Wrapper) when creating components from scratch.
- Logging is enabled in critical places (constructors, factory). With large volumes this may increase time. Keep the logging level moderate in production.

## Debugging and Logging

- In case of unexpected results, check the logs: BrizyComponent and BrizyComponentBuilder report types, input keys, and durations.
- During JSON parsing (for example in createRow), json_last_error_msg() decoding errors are logged.

## Frequently Asked Questions

1) **Can I mix plain arrays and component objects in items?**
- Yes. BrizyComponentValue converts arrays to components via the factory when setting items. This is convenient when assembling data from JSON.

2) **How to add an element to the beginning of the items list?**
- value->add('items', $child, 0) or value->add_items($child, 0) — via the magic method.

3) **What happens if a negative position is specified beyond the length?**
- The position is normalized to 0 (insert at the beginning).

4) **How to correctly add your own component type?**
- Create Brizy<MyType>Component, define the template in the constructor, then add a branch in BrizyComponent::fromArray for strtolower(type)==='mytype'.

5) **What’s the difference between the base BrizyComponent and a specialized one?**
- The base one is universal but doesn’t add default _styles/values. Specialized ones contain safe default configurations for specific types.

6) **Why is my component not showing up in the editor?**
- Check type and _styles, presence of _id, correctness of measurement units, nesting (Row > Column > ...), and ensure json_encode($component) outputs the expected structure.
