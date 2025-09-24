---
apply: always
---

# Analysis and Rules: Aligning Nested Elements via Column → Cloneable

Date: 2025-09-22  
Author: Automatic Assistant (Junie)  
Related files/changes:
- lib/MBMigration/Builder/BrizyComponent/BrizyComponent.php – added method applyHorizontalAlignToCloneableItemsInColumn()
- lib/MBMigration/Builder/Layout/Theme/Dusk/Elements/Footer.php – example of using the method in the footer column

## 1) Task Summary
It was necessary to implement the following logic: if a component is of type Column and directly contains a child of type Cloneable, then all nested elements inside that Cloneable should have horizontal alignment applied (addHorizontalContentAlign()).

A utility function was added to BrizyComponent: applyHorizontalAlignToCloneableItemsInColumn($align = 'center'), which:
- Checks that the current component is a Column.
- Searches among the immediate children for the first component of type Cloneable.
- Applies addHorizontalContentAlign($align) to all child elements of the found Cloneable.
- Does this safely (type and empty-array checks), with error logging.

In Theme Dusk/Footer a call was added meaningfully where we need to center the contents of the third footer column:
- $brizySection->getItemWithDepth(0, 2)
  ->addVerticalContentAlign('center')
  ->applyHorizontalAlignToCloneableItemsInColumn();

## 2) Application Rules
- Scope: only for structures of the form Column → Cloneable → [Elements…].
- Cloneable search: only immediate children of the column are considered. If a Cloneable is deeper, the method will not affect it.
- Alignment: allowed align values — 'left', 'center', 'right'. Default is 'center'.
- Idempotency: repeated calls do not produce unwanted effects; the method simply sets the same alignment values again.
- Safety: the method does nothing if the column has no items, no Cloneable among its children, or the Cloneable has no nested items.
- Associated alignment: addHorizontalContentAlign() sets horizontalAlign, tabletHorizontalAlign and mobileHorizontalAlign to the same value for consistent behavior across all breakpoints.

## 3) Implementation Details
The method (simplified):
- Checks the type of the current component (column – case-insensitive).
- Takes items of the current component (via getValue()->get('items')).
- Finds the first element of type 'cloneable'.
- Gets its items and for each nested BrizyComponent calls addHorizontalContentAlign($align).
- Exceptions are caught: a warning is written to the log and execution continues (fail-safe).

Platform model specifics:
- BrizyComponentValue, when constructed, converts the array items into BrizyComponent::fromArray() and stores object instances — this simplifies safe iteration and typing.
- getItemWithDepth() normalizes the found nodes to BrizyComponent as it descends, which is useful when accessing specific columns/rows.

## 4) Typical Usage Scenarios
1) Centering social media icons in a footer column:
- Structure: Column → Cloneable → [Icon, Icon, …].
- Call on the column: ->applyHorizontalAlignToCloneableItemsInColumn('center').

2) Right-aligning links/buttons:
- Structure: Column → Cloneable → [Button, Button].
- Call on the column: ->applyHorizontalAlignToCloneableItemsInColumn('right').

3) Left-aligning lists:
- Structure: Column → Cloneable → [Menu, Menu].
- ->applyHorizontalAlignToCloneableItemsInColumn('left').

## 5) Limitations and Nuances
- Only the first Cloneable: the method stops at the first immediate child of type Cloneable found. If there are multiple Cloneables in the column and each needs alignment — either call the method on each branch in turn, or extend the method for your needs (see Future Development).
- Only immediate level: if the Cloneable is nested deeper (e.g., Column → Wrapper → Cloneable), the current method will not affect it. This is done for minimal invasiveness. If necessary, a depth parameter or recursive strategy can be introduced.
- Does not change the horizontal alignment of the column itself: the method applies alignment to the children of the Cloneable, not to the column itself.
- Neutral to empty/incorrect data: in cases where the expected structures are missing, the method simply exits without errors.

## 6) Examples (pseudo-JSON)
Input:  
Column → Cloneable → [Icon A, Icon B, Icon C]  
Call: applyHorizontalAlignToCloneableItemsInColumn('center')  
Result: Icon A/B/C will have horizontalAlign = center (also mobile/tablet).

If Cloneable is absent:
- The method does nothing, returns $this.

If the elements inside the Cloneable are absent:
- The method does nothing, returns $this.

## 7) Testing and Verification
Manual check:
- Generate a column structure with a Cloneable and nested elements (Icon, Button, etc.).
- Call the method on the column with different align values.
- Verify that each nested element has the fields horizontalAlign, tabletHorizontalAlign, mobileHorizontalAlign with the expected value.

Automated tests (recommendations):
- Create a fixture Column → Cloneable → [Icon, Icon].
- Run BrizyComponent::applyHorizontalAlignToCloneableItemsInColumn('right') and check JSON serialization.
- Stability test: empty items, missing Cloneable, wrong type.
- Idempotency test: repeated call gives the same result.

## 8) Logging
- The method is wrapped in try/catch and writes a warning to the common log on exceptions without interrupting execution (resilient behavior), which matches the failover policy of the builder.

## 9) Performance
- Single pass through the column’s children and through the children of the found Cloneable. O(n) operations at the narrow point (n is usually small). Impact is negligible.

## 10) Future Recommendations (optional)
- Recursive variant: add a parameter $deep = false and support Cloneable search at any nesting level.
- Support for multiple Cloneables: optional flag allowing alignment of all immediate Cloneable children in the column.
- General alignment utility for any node: a generic method alignChildrenHorizontally($align, ?callable $filter = null), where $filter defines which children to apply the rule to.

## 11) Application in Theme Dusk/Footer
- After building the three footer columns, for the third column vertical and horizontal centering of the Cloneable’s nested elements is performed:
  - addVerticalContentAlign('center')
  - applyHorizontalAlignToCloneableItemsInColumn('center')

This ensures the expected behavior for blocks with social media icons and similar grouped elements.
