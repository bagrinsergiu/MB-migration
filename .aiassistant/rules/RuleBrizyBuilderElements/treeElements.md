---
apply: always
---

# Element Nesting Tree - Brizy Builder Structure

This document defines the hierarchical structure and allowed nesting patterns for Brizy Builder elements.

## Root Level Structure

```
Page
└── Section (multiple allowed)
    └── SectionItem (required, at least 1)
        ├── Row (common layout choice)
        │   └── Column (required, at least 1)
        │       └── [Content Elements]
        └── Wrapper (alternative to Row/Column)
            └── [Content Element]
```

## Section Level Nesting

### Section → SectionItem
- **Required**: Every Section must contain at least 1 SectionItem
- **Multiple**: Sections can contain multiple SectionItems
- **Purpose**: SectionItems define the actual content areas within sections

### SectionFooter → Content
- **Allowed Children**: Same as SectionItem
- **Special Rules**: Typically contains footer-specific content (copyright, links, social media)

### SectionMegaMenu → Content
- **Allowed Children**: Limited to navigation-specific content
- **Constraints**: Used only within menu contexts

## Layout Container Nesting

### SectionItem Children Options

#### Option 1: Row-Based Layout (Recommended)
```
SectionItem
├── Row (multiple allowed)
│   └── Column (required, 1-12 per row)
│       ├── Wrapper Elements
│       ├── Cloneable Elements
│       └── Direct Content Elements
└── Row
    └── Column
        └── [More Content]
```

#### Option 2: Direct Wrapper Layout
```
SectionItem
├── Wrapper--richText
│   └── RichText
├── Wrapper--image
│   └── Image
└── Wrapper--spacer
    └── Spacer
```

### Row → Column Nesting Rules
- **Required**: Every Row must contain at least 1 Column
- **Maximum**: 12 columns per row (grid system)
- **Width Constraint**: Total column widths should not exceed 100%
- **Auto-sizing**: Columns without explicit width auto-distribute available space

### Column Content Rules
- **Flexible**: Columns can contain any content elements
- **Nesting**: Unlimited nesting depth (performance considerations apply)
- **Mixed Content**: Single column can contain multiple different element types

## Content Element Nesting

### Wrapper Element Hierarchy

#### Text Content Wrapper
```
Wrapper--richText
└── RichText (exactly 1)
```

#### Image Content Wrapper
```
Wrapper--image
└── Image (exactly 1)
```

#### Button Wrapper (Legacy)
```
Wrapper--button
└── Button (exactly 1)
```
**Note**: Deprecated in favor of Cloneable--button

#### Icon Wrapper (Legacy)
```
Wrapper--icon
└── Icon (exactly 1)
```
**Note**: Deprecated in favor of Cloneable--icon

#### Spacer Wrapper
```
Wrapper--spacer
└── Spacer (exactly 1)
```

#### Line Wrapper
```
Wrapper--line
└── Line (exactly 1)
```

#### Embed Code Wrapper
```
Wrapper--embedCode
└── EmbedCode (exactly 1)
```

#### Form Wrapper
```
Wrapper--form
└── [Form Elements]
```

#### Ministry Brands Wrapper
```
Wrapper--ministryBrands
├── MinistryBrandsFormWidget
├── MinistryBrandsPrayerWidget
├── MinistryBrandsEventList
├── MinistryBrandsGroupLayout
└── [Other Ministry Brands Widgets]
```

#### Accordion Wrapper
```
Wrapper--accordion
└── Accordion
    └── AccordionItem (multiple)
        └── [Content Elements]
```

### Cloneable Element Hierarchy

#### Button Cloneable
```
Cloneable--button
├── Button (multiple allowed)
├── Button
└── Button
```

#### Icon Cloneable
```
Cloneable--icon
├── Icon (multiple allowed)
├── Icon
└── Icon
```

### Interactive Elements Nesting

#### Tab Container
```
Tabs
├── Tab
│   ├── labelText: "Tab 1"
│   └── items: [Content Elements]
├── Tab
│   ├── labelText: "Tab 2"
│   └── items: [Content Elements]
└── Tab
    ├── labelText: "Tab 3"
    └── items: [Content Elements]
```

#### Accordion Container
```
Accordion
├── AccordionItem
│   ├── labelText: "Section 1"
│   └── items: [Content Elements]
├── AccordionItem
│   ├── labelText: "Section 2"
│   └── items: [Content Elements]
└── AccordionItem
    ├── labelText: "Section 3"
    └── items: [Content Elements]
```

## Navigation Element Nesting

### Menu Hierarchy
```
Menu Section
└── SectionItem
    └── Menu Container
        └── MenuItem (multiple)
            ├── title: "Menu Item"
            ├── url: "/page-url"
            ├── items: [Submenu Items] (optional)
            └── megaMenuItems: [SectionMegaMenu] (optional)
```

#### Submenu Nesting Rules
- **Maximum Depth**: 3 levels (Main → Sub → Mega)
- **Unlimited Items**: Each level can have unlimited menu items
- **Mega Menus**: Only available on top-level menu items

#### Menu Item Structure
```
MenuItem
├── Basic Properties (title, url, target)
├── items: [Sub MenuItems] (level 2)
│   └── MenuItem
│       └── items: [Sub-sub MenuItems] (level 3)
└── megaMenuItems: [SectionMegaMenu]
    └── SectionMegaMenu
        └── [Rich Content Layout]
```

## Specialized Layout Nesting

### Gallery Layout Structure
```
Gallery Section
└── SectionItem (slider container)
    ├── SectionItem (slide 1)
    │   └── [Slide Content]
    ├── SectionItem (slide 2)
    │   └── [Slide Content]
    └── SectionItem (slide 3)
        └── [Slide Content]
```

### Event Layout Structures

#### Event List Layout
```
Event Section
└── SectionItem
    ├── Row (header - optional)
    │   └── Column
    │       └── [Header Content]
    └── Wrapper--ministryBrands
        └── MinistryBrandsEventList
```

#### Event Detail Layout
```
Event Detail Section
└── SectionItem
    ├── Row (event info)
    │   ├── Column (image)
    │   │   └── Wrapper--image
    │   └── Column (details)
    │       ├── Wrapper--richText (title)
    │       ├── Wrapper--richText (date/time)
    │       └── Wrapper--richText (description)
    └── Row (registration/actions)
        └── Column
            └── Cloneable--button
```

### Form Layout Structures

#### Simple Form Layout
```
Form Section
└── SectionItem
    └── Wrapper--ministryBrands
        └── MinistryBrandsFormWidget
```

#### Complex Form Layout
```
Form Section
├── SectionItem (header)
│   └── Row
│       └── Column
│           └── Wrapper--richText (form title)
├── SectionItem (form)
│   ├── Row (left side - form)
│   │   └── Column
│   │       └── Wrapper--ministryBrands
│   └── Row (right side - info)
│       └── Column
│           ├── Wrapper--richText
│           └── Wrapper--image
└── SectionItem (footer)
    └── Row
        └── Column
            └── Wrapper--richText (disclaimer)
```

## Content Element Nesting Constraints

### Terminal Elements (No Children)
These elements cannot contain other elements:
- `RichText`
- `Image`
- `Button`
- `Icon` 
- `Spacer`
- `Line`
- `EmbedCode`
- `Video`
- All Ministry Brands widgets

### Container Elements (Must Have Children)
These elements require child elements:
- `Section` → `SectionItem`
- `SectionItem` → Content elements
- `Row` → `Column`
- `Column` → Content elements
- `Wrapper` → Single content element
- `Cloneable` → Multiple same-type elements
- `Accordion` → `AccordionItem`
- `Tabs` → `Tab`

### Optional Container Elements
These elements may or may not have children:
- `MenuItem` → Submenu items (optional)
- `Tab` → Content elements (can be empty)
- `AccordionItem` → Content elements (can be empty)

## Nesting Validation Rules

### Depth Limitations
- **Maximum Nesting Depth**: 10 levels recommended
- **Performance Consideration**: Deep nesting impacts rendering performance
- **Practical Limit**: Most layouts use 3-5 levels

### Required Relationships
1. `Section` must contain `SectionItem`
2. `Row` must contain at least one `Column`
3. `Wrapper` must contain exactly one content element
4. `Cloneable` should contain at least one item

### Forbidden Relationships
1. Content elements cannot directly contain other content elements
2. Sections cannot be nested within other sections
3. Rows cannot contain other rows directly
4. Columns cannot contain other columns directly

### Special Rules

#### Responsive Nesting
- Column stacking on mobile devices
- Hidden elements (`showOnMobile: "off"`) create conditional nesting
- Responsive layouts may change effective nesting structure

#### Theme-Specific Nesting
- Different themes may have different nesting patterns
- Global elements can be nested in any theme
- Theme-specific elements follow theme constraints

## Performance Considerations

### Optimal Nesting Patterns
1. **Shallow Hierarchies**: Prefer wider, shallower trees over deep nesting
2. **Efficient Containers**: Use appropriate container types (Row/Column vs direct Wrapper)
3. **Content Grouping**: Group related content in same container level

### Anti-Patterns to Avoid
1. **Excessive Nesting**: More than 8 levels deep
2. **Single-Child Containers**: Unnecessary wrapper levels
3. **Empty Containers**: Containers with no meaningful content
4. **Mixed Layout Patterns**: Inconsistent Row/Column vs Wrapper usage

## Error Recovery

### Invalid Nesting Handling
1. **Missing Required Children**: Auto-generate default child elements
2. **Invalid Parent-Child**: Move elements to valid container or remove
3. **Circular References**: Break circular relationships, keep valid elements
4. **Orphaned Elements**: Move to appropriate container or page root

### Validation Messages
- Clear error messages for invalid nesting attempts
- Suggestions for correct element placement
- Auto-correction options where possible

## Example Complete Nesting Structure

```
Section (Homepage Hero)
└── SectionItem
    └── Row
        ├── Column (60% width - Text Content)
        │   ├── Wrapper--richText
        │   │   └── RichText (Heading)
        │   ├── Wrapper--richText  
        │   │   └── RichText (Description)
        │   └── Cloneable--button
        │       ├── Button (Primary CTA)
        │       └── Button (Secondary CTA)
        └── Column (40% width - Image)
            └── Wrapper--image
                └── Image (Hero Image)
```

---
*This document defines the complete nesting structure and rules for Brizy Builder elements.*
