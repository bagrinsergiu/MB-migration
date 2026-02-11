---
apply: on_demand
---

# Rules for Sections - Brizy Builder Elements

This document defines the rules, constraints, and usage guidelines for each type of section in Brizy Builder.

## Section Type Hierarchy

### 1. Base Section Types

#### Section
- **Type**: `Section`
- **Primary Use**: Main content sections
- **Required Properties**: 
  - `_styles`: Must include `["section"]`
  - `items`: Array of SectionItem elements
  - `_id`: Unique identifier
- **Allowed Children**: 
  - `SectionItem` (required, at least 1)
- **Common Properties**:
  - `_thumbnailSrc`, `_thumbnailWidth`, `_thumbnailHeight`, `_thumbnailTime`
  - `blockId`: Block identifier
  - `marginType`, `margin`, `marginSuffix`

#### SectionItem
- **Type**: `SectionItem`
- **Primary Use**: Content container within sections
- **Required Properties**:
  - `_styles`: Must include `["section-item"]`
  - `items`: Array of content elements
  - `_id`: Unique identifier
- **Allowed Children**:
  - `Row` (most common)
  - `Wrapper` elements
  - `Spacer` elements
- **Styling Properties**:
  - Background: `bgColorType`, `bgColorHex`, `bgColorOpacity`, `bgColorPalette`
  - Padding: `paddingType`, `paddingTop`, `paddingBottom`, `paddingLeft`, `paddingRight`
  - Container: `containerSize`, `containerSizeSuffix`

#### SectionFooter
- **Type**: `SectionFooter`
- **Primary Use**: Footer sections specifically
- **Required Properties**:
  - `_styles`: Must include `["sectionFooter"]`
  - `items`: Array of footer content
  - `_id`: Unique identifier
- **Special Rules**:
  - Typically contains contact info, social links, copyright
  - Usually positioned at bottom of page
  - Often has dark backgrounds

### 2. Specialized Section Types

#### SectionMegaMenu
- **Type**: `SectionMegaMenu`
- **Primary Use**: Mega menu dropdowns
- **Required Properties**:
  - `items`: Array of mega menu content
  - `_id`: Unique identifier
- **Constraints**:
  - Should only be used within navigation contexts
  - Limited nesting depth

## Section Layout Rules

### Container Rules
1. **Section Container Hierarchy**:
   ```
   Section
   └── SectionItem
       ├── Row (optional, but common)
       │   └── Column
       │       └── Content Elements
       └── Direct Wrappers (alternative to Row/Column)
   ```

2. **Container Size Rules**:
   - `containerSize`: 0-100 (percentage)
   - `containerSizeSuffix`: Usually "%"
   - Default container sizes:
     - Desktop: 85-100%
     - Tablet: 90-100%
     - Mobile: 100%

### Background Rules
1. **Background Types**:
   - `solid`: Single color background
   - `gradient`: Gradient background (requires start/end colors)
   - `image`: Background image (requires `bgImageSrc`)

2. **Background Properties Validation**:
   - Solid: Requires `bgColorHex`, `bgColorOpacity`
   - Gradient: Requires `gradientColorHex`, `gradientType`, `gradientStartPointer`, `gradientFinishPointer`
   - Image: Requires `bgImageSrc`, `bgImageFileName`, `bgImageExtension`

### Padding Rules
1. **Padding Types**:
   - `grouped`: All sides same value
   - `ungrouped`: Individual side values

2. **Responsive Padding**:
   - Desktop: `padding*` properties
   - Tablet: `tabletPadding*` properties
   - Mobile: `mobilePadding*` properties

## Section-Specific Rules

### Navigation Sections (Menu)
- **Required Elements**: MenuItem array
- **Constraints**:
  - Maximum depth: 3 levels (main → sub → mega)
  - Must have unique `itemId` for each menu item
  - Support for external/internal links

### Content Sections

#### Text-Based Sections
- **Allowed Content**: RichText, Headings, Paragraphs
- **Layout Constraints**: 
  - Maximum columns: 4 for readability
  - Minimum text size: 12px
  - Line height: 1.2-2.0

#### Media Sections
- **Allowed Content**: Image, Video, Gallery elements
- **File Constraints**:
  - Images: JPG, PNG, WebP (max 10MB)
  - Videos: MP4, WebM (embedded or external links)
  - Aspect ratios: 16:9, 4:3, 1:1, custom

#### Form Sections
- **Required Elements**: Form wrapper with Ministry Brands widgets
- **Validation Rules**:
  - Must have form action/endpoint
  - Required field validation
  - GDPR compliance for data collection

### Event Sections

#### Event List/Grid/Tile Layouts
- **Data Requirements**:
  - `source`: Collection endpoint
  - `detailPage`: Event detail page template
  - `category`: Event filtering
- **Display Rules**:
  - List: Vertical stacking, full-width items
  - Grid: 2-4 columns, equal height items  
  - Tile: Card-based layout with images

#### Event Calendar Layout
- **Special Properties**:
  - Calendar widget integration
  - Date range filtering
  - Month/week/day view modes

### Interactive Sections

#### Gallery Layout
- **Required Properties**:
  - `slider`: Enable/disable slider mode
  - `sliderAutoPlay`: Auto-advance slides
  - `sliderDotsColor*`: Navigation dot styling
- **Content Rules**:
  - Minimum 2 slides
  - Maximum 50 slides for performance
  - Consistent aspect ratios recommended

#### Accordion Layout
- **Structure Rules**:
  - Must contain Accordion wrapper
  - Accordion contains AccordionItem array
  - Each item has `labelText` and `items`
- **Behavior Rules**:
  - `collapsible`: Allow closing all items
  - `activeAccordionItem`: Default open item

#### Tabs Layout
- **Structure Rules**:
  - Must contain Tabs wrapper
  - Tabs contains Tab array
  - Each tab has `labelText` and `items`
- **Constraints**:
  - Maximum 8 tabs for usability
  - Minimum 2 tabs required

## Responsive Design Rules

### Breakpoint Rules
1. **Desktop**: > 991px
   - Full feature set
   - All properties available
2. **Tablet**: 768px - 991px
   - `tablet*` prefixed properties
   - Simplified layouts allowed
3. **Mobile**: < 768px
   - `mobile*` prefixed properties
   - Single column layouts preferred

### Mobile-Specific Rules
- **Show/Hide**: `showOnMobile`, `showOnTablet`
- **Layout Adjustments**:
  - Automatic column stacking
  - Touch-friendly button sizes (min 44px)
  - Readable font sizes (min 16px)

## Validation Rules

### Required Property Validation
1. Every section must have:
   - `type` field
   - `_id` field (unique)
   - `_styles` array
   - `items` array

### Content Validation
1. **Empty Sections**: Not allowed in production
2. **Nested Limits**: Maximum 5 levels deep
3. **Performance**: Sections with >100 elements require optimization

### Accessibility Rules
1. **Color Contrast**: Minimum 4.5:1 ratio for text
2. **Focus Indicators**: All interactive elements must be keyboard accessible
3. **Alt Text**: All images require alt text for screen readers

## Theme Integration Rules

### Theme Compatibility
1. **Global Themes**: Apply to all sections
2. **Section Themes**: Override global themes for specific sections
3. **Color Palettes**: Must reference theme color palette when available

### Custom CSS Rules
1. **Allowed**: `customCSS` property for advanced styling
2. **Constraints**: No external imports, must be valid CSS
3. **Performance**: Inline styles preferred over large CSS blocks

## Error Handling Rules

### Common Errors
1. **Missing Required Properties**: Use default values where possible
2. **Invalid Property Values**: Fallback to nearest valid value
3. **Broken References**: Remove broken links/references

### Recovery Rules
1. **Graceful Degradation**: Show simplified version if full version fails
2. **Content Preservation**: Never lose user content due to validation errors
3. **User Feedback**: Clear error messages for fixable issues

---
*This document defines the comprehensive rules for section usage in Brizy Builder Elements.*
