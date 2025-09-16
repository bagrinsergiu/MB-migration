# Rules for Elements - Brizy Builder Components

This document defines the usage rules, constraints, and best practices for individual Brizy Builder elements.

## Container Elements

### Row Element
- **Type**: `Row`
- **Primary Use**: Horizontal layout container for columns
- **Required Properties**:
  - `_styles`: Must include `["row"]`
  - `items`: Array of Column elements
  - `_id`: Unique identifier
- **Optional Style Classes**:
  - `hide-row-borders`: Hide border visualization
  - `padding-0`: Remove default padding
- **Layout Rules**:
  - Must contain at least 1 Column
  - Maximum 12 columns per row (grid system)
  - Columns auto-adjust width if not specified
- **Responsive Behavior**:
  - Desktop: Horizontal layout maintained
  - Tablet: May stack columns if width < 50%
  - Mobile: Automatic column stacking

### Column Element
- **Type**: `Column`
- **Primary Use**: Vertical content container within rows
- **Required Properties**:
  - `_styles`: Must include `["column"]`
  - `items`: Array of content elements
  - `_id`: Unique identifier
- **Width Rules**:
  - `width`: 0-100 (percentage of row)
  - Must total ≤100% per row
  - Default: Equal distribution among siblings
- **Content Rules**:
  - Can contain any content element
  - Unlimited nesting depth (performance permitting)
  - Automatic overflow handling

### Wrapper Elements
- **Type**: `Wrapper`
- **Primary Use**: Content-specific containers
- **Required Properties**:
  - `_styles`: Must include `["wrapper"]` + specific type
  - `items`: Array containing single content element
  - `_id`: Unique identifier
- **Wrapper Types**:
  - `wrapper--richText`: Text content wrapper
  - `wrapper--image`: Image content wrapper
  - `wrapper--button`: Button content wrapper (deprecated, use Cloneable)
  - `wrapper--icon`: Icon content wrapper (deprecated, use Cloneable)
  - `wrapper--embedCode`: Embedded content wrapper
  - `wrapper--spacer`: Spacing element wrapper
  - `wrapper--line`: Line/divider wrapper
  - `wrapper--form`: Form element wrapper
  - `wrapper--accordion`: Accordion container
  - `wrapper--ministryBrands`: Ministry Brands widget wrapper

## Content Elements

### RichText Element
- **Type**: `RichText`
- **Primary Use**: Formatted text content
- **Required Properties**:
  - `_styles`: Must include `["richText"]`
  - `_id`: Unique identifier
  - `text`: HTML text content
- **Content Rules**:
  - Supports HTML formatting (p, h1-h6, strong, em, a, ul, ol, li)
  - XSS protection: Script tags stripped
  - Maximum length: 10,000 characters
- **Typography Properties**:
  - Font family, size, weight, style
  - Line height, letter spacing
  - Color and opacity settings

### Image Element
- **Type**: `Image`
- **Primary Use**: Display images
- **Required Properties**:
  - `_styles`: Must include `["image"]`
  - `_id`: Unique identifier
- **Image Properties**:
  - `imageSrc`: Image URL or base64 data
  - `imageFileName`: Original filename
  - `imageExtension`: File extension (jpg, png, gif, webp)
  - `imageWidth`, `imageHeight`: Original dimensions
- **Display Properties**:
  - `width`, `height`: Display dimensions
  - `sizeType`: "original", "custom", "cover", "contain"
  - `size`: Scale percentage (0-200)
- **Constraints**:
  - Maximum file size: 10MB
  - Supported formats: JPG, PNG, GIF, WebP, SVG
  - Alt text required for accessibility

### Button Element
- **Type**: `Button`
- **Primary Use**: Interactive button/link
- **Required Properties**:
  - `_styles`: Must include `["button"]`
  - `_id`: Unique identifier
- **Content Properties**:
  - `text`: Button label text
  - `iconName`, `iconType`: Optional icon
- **Link Properties**:
  - `linkType`: "external", "page", "popup", "anchor"
  - `linkExternal`: External URL
  - `linkSource`: Internal page reference
  - `linkExternalBlank`: Open in new window
- **Style Properties**:
  - `fillType`: "filled", "outline"
  - `size`: "small", "medium", "large", "custom"
  - `borderRadius`: Button corner rounding
  - Colors: background, border, text, hover states

### Icon Element
- **Type**: `Icon`
- **Primary Use**: Display icons
- **Required Properties**:
  - `_styles`: Must include `["icon"]`
  - `_id`: Unique identifier
  - `name`: Icon name from icon library
  - `type`: "glyph", "fa" (Font Awesome)
- **Size Properties**:
  - `size`: "small", "medium", "large", "custom"
  - `customSize`: Custom size in pixels
- **Style Properties**:
  - Colors: fill, background, border
  - Border radius for background
- **Link Properties**: Same as Button element

### Spacer Element
- **Type**: `Spacer`
- **Primary Use**: Add vertical spacing
- **Required Properties**:
  - `_styles`: Must include `["spacer"]`
  - `_id`: Unique identifier
  - `height`: Height in pixels
  - `heightSuffix`: Usually "px"
- **Constraints**:
  - Minimum height: 1px
  - Maximum height: 500px
  - Responsive height adjustment available

### Line Element
- **Type**: `Line`
- **Primary Use**: Visual dividers/separators
- **Required Properties**:
  - `_styles`: Must include `["line"]`
  - `_id`: Unique identifier
- **Style Properties**:
  - `borderStyle`: "solid", "dashed", "dotted"
  - `borderColorHex`, `borderColorOpacity`
  - `borderWidth`: Line thickness
  - `width`: Line length (percentage)

## Interactive Elements

### Cloneable Element
- **Type**: `Cloneable`
- **Primary Use**: Container for duplicatable elements (buttons, icons)
- **Required Properties**:
  - `_styles`: Must include `["wrapper-clone"]` + specific type
  - `items`: Array of cloneable items
  - `_id`: Unique identifier
- **Clone Types**:
  - `wrapper-clone--button`: Button cloner
  - `wrapper-clone--icon`: Icon cloner
- **Layout Properties**:
  - `horizontalAlign`: "left", "center", "right"
  - `itemPadding`: Spacing between cloned items

### EmbedCode Element
- **Type**: `EmbedCode`
- **Primary Use**: Embed external content (HTML, scripts)
- **Required Properties**:
  - `_styles`: Must include `["embedCode"]`
  - `_id`: Unique identifier
  - `code`: HTML/JavaScript code
- **Security Rules**:
  - XSS protection applied
  - Script execution in sandboxed environment
  - External resource limitations

## Form Elements

### MinistryBrandsFormWidget
- **Type**: `MinistryBrandsFormWidget`
- **Primary Use**: Ministry Brands form integration
- **Required Properties**:
  - `_styles`: Must include `["ministryBrandsFormWidget"]`
  - `_id`: Unique identifier
  - `form`: Form identifier/configuration
- **Integration Rules**:
  - Must connect to valid Ministry Brands endpoint
  - GDPR compliance required
  - Data validation on client and server

### MinistryBrandsPrayerWidget
- **Type**: `MinistryBrandsPrayerWidget`
- **Primary Use**: Prayer request submission
- **Required Properties**:
  - `_styles`: Must include `["ministryBrandsPrayerWidget"]`
  - `_id`: Unique identifier
- **Content Rules**:
  - Prayer text validation
  - Optional contact information
  - Privacy controls for public/private requests

### MinistryBrandsEventList
- **Type**: `MinistryBrandsEventList`
- **Primary Use**: Display event listings
- **Required Properties**:
  - `_styles`: Must include `["ministryBrandsEventList"]`
  - `_id`: Unique identifier
  - `source`: Event data source
- **Display Properties**:
  - `columnNumber`: 1-4 columns
  - `itemsNumber`: Items per page
  - `showMeta`, `showCategory`, `showLocation`: Visibility flags
  - `showPagination`: Enable pagination
- **Filter Properties**:
  - `category`: Filter by event category
  - `dateRange`: Date filtering

### MinistryBrandsGroupLayout
- **Type**: `MinistryBrandsGroupLayout`
- **Primary Use**: Display group listings
- **Required Properties**:
  - `_styles`: Must include `["ministryBrandsGroupLayout"]`
  - `_id`: Unique identifier
- **Filter Properties**:
  - `categoryFilterHeading`: Category filter title
  - `groupFilterHeading`: Group filter title
  - `searchPlaceholder`: Search field placeholder
- **Display Properties**:
  - `showStatus`, `showGroup`, `showCategory`: Visibility flags
  - `showChildcare`, `showResourceLink`, `showPreview`: Additional info flags

## Navigation Elements

### MenuItem
- **Type**: `MenuItem`
- **Primary Use**: Navigation menu items
- **Required Properties**:
  - `id`: Unique identifier for menu item
  - `title`: Display text
  - `url`: Target URL
  - `items`: Submenu items array
- **Navigation Properties**:
  - `target`: Link target ("_self", "_blank")
  - `current`: Current page indicator
  - `megaMenuItems`: Mega menu content array
- **Hierarchy Rules**:
  - Maximum 3 levels deep
  - Unlimited items per level
  - Mega menus only on level 1 items

## Specialized Elements

### Tab Element
- **Type**: `Tab`
- **Primary Use**: Individual tab in tab container
- **Required Properties**:
  - `labelText`: Tab label
  - `items`: Tab content array
  - `_id`: Unique identifier
- **Content Rules**:
  - Can contain any layout elements
  - Lazy loading supported for performance

### AccordionItem
- **Type**: `AccordionItem`
- **Primary Use**: Individual item in accordion
- **Required Properties**:
  - `labelText`: Accordion header text
  - `items`: Accordion content array
  - `_id`: Unique identifier
- **Behavior Properties**:
  - Default open/closed state
  - Animation settings

### Video Element
- **Type**: `Video`
- **Primary Use**: Video playback
- **Required Properties**:
  - `_styles`: Must include `["video"]`
  - `_id`: Unique identifier
- **Video Properties**:
  - `videoSrc`: Video URL or file reference
  - `videoType`: "url", "file", "youtube", "vimeo"
  - `autoplay`, `loop`, `muted`: Playback controls
- **Constraints**:
  - Maximum file size: 100MB for uploads
  - Supported formats: MP4, WebM, OGV
  - External embeds: YouTube, Vimeo URLs

## Element Validation Rules

### Universal Requirements
1. **Required Fields**: All elements must have `type` and `_id`
2. **Unique IDs**: IDs must be unique within the document
3. **Style Arrays**: `_styles` must be array with valid classes
4. **Nesting Limits**: Maximum 10 levels deep

### Performance Rules
1. **Element Limits**: Maximum 500 elements per page
2. **Image Optimization**: Automatic compression for large images
3. **Lazy Loading**: Images and videos load on demand
4. **Caching**: Element configurations cached for performance

### Accessibility Rules
1. **Keyboard Navigation**: All interactive elements must be keyboard accessible
2. **Screen Readers**: Proper ARIA labels and roles
3. **Color Contrast**: Minimum 4.5:1 ratio for text
4. **Focus Indicators**: Visible focus states required

### Responsive Rules
1. **Breakpoint Properties**: Tablet/mobile-specific properties available
2. **Auto-stacking**: Columns stack automatically on mobile
3. **Touch Targets**: Minimum 44px touch target size
4. **Font Scaling**: Text scales appropriately across devices

## Error Handling

### Common Validation Errors
1. **Missing Required Properties**: Use default values
2. **Invalid Property Values**: Clamp to valid range
3. **Broken References**: Remove or replace with placeholders
4. **Malformed Content**: Strip invalid HTML/scripts

### Recovery Strategies
1. **Graceful Degradation**: Show simplified version if complex version fails
2. **Content Preservation**: Never lose user content during validation
3. **User Feedback**: Clear error messages for correctable issues
4. **Backup Rendering**: Fallback to basic HTML if component fails

## Font Consistency Rules for Dusk Theme

### Typography Standardization (Added 2025-09-15)

#### Dusk Theme Font Requirements
- **Standard Font**: Lato (Google font)
- **Font Family Type**: "google"
- **Supported Weights**: 400 (regular), 700 (bold), italic variants
- **Scope**: All text-rendering elements in Dusk theme

#### Font Replacement Rules
1. **Lora → Lato Migration**:
   - Replace all instances of `fontFamily: "lora"` with `fontFamily: "lato"`
   - Maintain existing font weights and styles
   - Ensure `fontFamilyType: "google"` is present
   
2. **Priority Elements for Font Consistency**:
   - **RichText Elements**: Primary text content
   - **Button Elements**: Interactive text elements  
   - **Heading Elements**: Title and subtitle text
   - **Menu Items**: Navigation text

3. **Font Property Validation**:
   - `fontFamily`: Must be "lato" for Dusk theme consistency
   - `fontFamilyType`: Must be "google" when using Lato
   - `fontWeight`: Standard values only (400, 700)
   - `fontStyle`: "normal" or "italic" accepted

#### Implementation Guidelines
- **Visual Consistency**: Ensure alignment with Clover website reference
- **Cache Management**: Clear font cache after changes
- **Cross-browser Testing**: Verify font loading across browsers
- **Performance**: Optimize font loading to prevent display issues

#### Compliance Checklist
- [ ] No "lora" font references in Dusk theme files
- [ ] All text elements use consistent Lato font
- [ ] Font weights and styles properly maintained
- [ ] Browser compatibility verified
- [ ] Visual consistency with reference site confirmed

---
*This document defines the comprehensive rules for individual element usage in Brizy Builder.*
