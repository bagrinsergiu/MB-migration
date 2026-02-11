---
apply: on_demand
---

# Properties Usage Guide - Brizy Builder Elements

This document describes all properties used in Brizy Builder elements, their types, valid values, and usage patterns.

## Universal Properties

All Brizy Builder elements share certain universal properties that provide core functionality.

### Core Identity Properties
| Property | Type | Required | Description | Valid Values |
|----------|------|----------|-------------|--------------|
| `type` | String | Yes | Element type identifier | "Section", "Row", "Column", "Button", etc. |
| `_id` | String | Yes | Unique element identifier | Alphanumeric string, 32 chars |
| `_styles` | Array | Yes | CSS class identifiers | Array of strings |
| `items` | Array | Conditional | Child elements | Array of element objects |

### Metadata Properties
| Property | Type | Required | Description | Valid Values |
|----------|------|----------|-------------|--------------|
| `blockId` | String | No | Block template identifier | String identifier |
| `_thumbnailSrc` | Number | No | Thumbnail image ID | Numeric ID |
| `_thumbnailWidth` | Number | No | Thumbnail width | Pixels (number) |
| `_thumbnailHeight` | Number | No | Thumbnail height | Pixels (number) |
| `_thumbnailTime` | Number | No | Thumbnail timestamp | Unix timestamp |

## Layout & Positioning Properties

### Sizing Properties
| Property | Type | Description | Valid Values | Default |
|----------|------|-------------|--------------|---------|
| `width` | Number | Element width percentage | 0-100 | Auto |
| `height` | Number | Element height | 0-1000 | Auto |
| `widthSuffix` | String | Width unit | "%", "px" | "%" |
| `heightSuffix` | String | Height unit | "%", "px" | "px" |
| `size` | Number | Scale percentage | 0-200 | 100 |
| `sizeType` | String | Size calculation mode | "original", "custom", "cover", "contain" | "original" |

### Spacing Properties
| Property | Type | Description | Valid Values | Responsive |
|----------|------|-------------|--------------|------------|
| `padding` | Number | All sides padding | 0-200 | Yes |
| `paddingTop` | Number | Top padding | 0-200 | Yes |
| `paddingRight` | Number | Right padding | 0-200 | Yes |
| `paddingBottom` | Number | Bottom padding | 0-200 | Yes |
| `paddingLeft` | Number | Left padding | 0-200 | Yes |
| `paddingType` | String | Padding mode | "grouped", "ungrouped" | No |
| `paddingSuffix` | String | Padding unit | "px", "em", "%" | No |
| `margin` | Number | All sides margin | -200-200 | Yes |
| `marginTop` | Number | Top margin | -200-200 | Yes |
| `marginRight` | Number | Right margin | -200-200 | Yes |
| `marginBottom` | Number | Bottom margin | -200-200 | Yes |
| `marginLeft` | Number | Left margin | -200-200 | Yes |
| `marginType` | String | Margin mode | "grouped", "ungrouped" | No |
| `marginSuffix` | String | Margin unit | "px", "em", "%" | No |

### Container Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `containerSize` | Number | Container width percentage | 0-100 | Sections |
| `containerSizeSuffix` | String | Container size unit | "%" | Sections |
| `containerType` | String | Container behavior | "boxed", "fullWidth" | Sections |
| `verticalAlign` | String | Vertical alignment | "top", "center", "bottom" | Columns |
| `horizontalAlign` | String | Horizontal alignment | "left", "center", "right" | Various |

## Visual Styling Properties

### Color Properties
| Property | Type | Description | Valid Values | States |
|----------|------|-------------|--------------|--------|
| `bgColorType` | String | Background type | "solid", "gradient", "none" | Normal/Hover |
| `bgColorHex` | String | Background color | Hex color code (#RRGGBB) | Normal/Hover |
| `bgColorOpacity` | Number | Background opacity | 0-1 | Normal/Hover |
| `bgColorPalette` | String | Theme color reference | Theme color ID | Normal/Hover |
| `colorHex` | String | Text/foreground color | Hex color code (#RRGGBB) | Normal/Hover |
| `colorOpacity` | Number | Text opacity | 0-1 | Normal/Hover |
| `colorPalette` | String | Theme text color | Theme color ID | Normal/Hover |

### Gradient Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `gradientType` | String | Gradient type | "linear", "radial" | Backgrounds |
| `gradientColorHex` | String | Gradient color | Hex color code | Backgrounds |
| `gradientColorOpacity` | Number | Gradient opacity | 0-1 | Backgrounds |
| `gradientColorPalette` | String | Theme gradient color | Theme color ID | Backgrounds |
| `gradientStartPointer` | Number | Gradient start position | 0-100 | Linear gradients |
| `gradientFinishPointer` | Number | Gradient end position | 0-100 | Linear gradients |
| `gradientActivePointer` | String | Active gradient control | "startPointer", "finishPointer" | Editor UI |
| `gradientLinearDegree` | Number | Linear gradient angle | 0-360 | Linear gradients |
| `gradientRadialDegree` | Number | Radial gradient angle | 0-360 | Radial gradients |

### Border Properties
| Property | Type | Description | Valid Values | Responsive |
|----------|------|-------------|--------------|------------|
| `borderStyle` | String | Border style | "solid", "dashed", "dotted", "none" | Yes |
| `borderColorHex` | String | Border color | Hex color code | Yes |
| `borderColorOpacity` | Number | Border opacity | 0-1 | Yes |
| `borderColorPalette` | String | Theme border color | Theme color ID | Yes |
| `borderWidth` | Number | All sides border width | 0-20 | Yes |
| `borderWidthType` | String | Border width mode | "grouped", "ungrouped" | Yes |
| `borderTopWidth` | Number | Top border width | 0-20 | Yes |
| `borderRightWidth` | Number | Right border width | 0-20 | Yes |
| `borderBottomWidth` | Number | Bottom border width | 0-20 | Yes |
| `borderLeftWidth` | Number | Left border width | 0-20 | Yes |
| `borderRadius` | Number | Corner radius | 0-100 | Yes |
| `borderRadiusType` | String | Radius mode | "square", "rounded", "custom" | Yes |

## Typography Properties

### Font Properties
| Property | Type | Description | Valid Values | Responsive |
|----------|------|-------------|--------------|------------|
| `fontFamily` | String | Font family | Font name or web font | Yes |
| `fontFamilyType` | String | Font source | "google", "upload", "system" | Yes |
| `fontSize` | Number | Font size | 8-200 | Yes |
| `fontSizeSuffix` | String | Font size unit | "px", "em", "rem" | Yes |
| `fontWeight` | Number/String | Font weight | 100-900, "normal", "bold" | Yes |
| `fontStyle` | String | Font style | "normal", "italic" | Yes |
| `letterSpacing` | Number | Letter spacing | -5-20 | Yes |
| `lineHeight` | Number | Line height | 0.5-3.0 | Yes |
| `textAlign` | String | Text alignment | "left", "center", "right", "justify" | Yes |
| `textDecoration` | String | Text decoration | "none", "underline", "line-through" | Yes |
| `textTransform` | String | Text transform | "none", "uppercase", "lowercase", "capitalize" | Yes |

### Typography Styling
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `typographyFontStyle` | String | Typography preset | "heading1", "heading2", ..., "paragraph" | Text elements |
| `titleTypographyFontStyle` | String | Title typography preset | Typography style names | Widgets |
| `dateTypographyFontStyle` | String | Date typography preset | Typography style names | Event widgets |

## Interactive Properties

### Link Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `linkType` | String | Link type | "external", "page", "popup", "anchor" | Interactive elements |
| `linkExternal` | String | External URL | Valid URL | External links |
| `linkExternalType` | String | External link type | "linkExternal" | External links |
| `linkExternalBlank` | String | Open in new window | "on", "off" | External links |
| `linkSource` | String | Internal page reference | Page path/ID | Internal links |
| `linkPopulation` | String | Dynamic link source | Population reference | Dynamic content |
| `linkPopulationEntityId` | String | Entity identifier | Entity ID | Dynamic content |
| `linkPopulationEntityType` | String | Entity type | Entity type name | Dynamic content |

### Button Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `text` | String | Button text | Any string | Buttons |
| `fillType` | String | Button fill style | "filled", "outline" | Buttons |
| `size` | String | Button size | "small", "medium", "large", "custom" | Buttons |
| `paddingRL` | Number | Horizontal padding | 0-100 | Buttons |
| `paddingTB` | Number | Vertical padding | 0-50 | Buttons |

### Icon Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `iconName` | String | Icon identifier | Icon name from library | Icons |
| `iconType` | String | Icon library | "glyph", "fa" | Icons |
| `name` | String | Icon name | Icon identifier | Icon elements |
| `customSize` | Number | Custom icon size | 8-200 | Icons |

## Media Properties

### Image Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `imageSrc` | String | Image source | URL or base64 data | Images |
| `imageFileName` | String | Original filename | Filename with extension | Images |
| `imageExtension` | String | File extension | "jpg", "png", "gif", "webp", "svg" | Images |
| `imageWidth` | Number | Original image width | Pixels | Images |
| `imageHeight` | Number | Original image height | Pixels | Images |
| `bgImageSrc` | String | Background image | Image URL/reference | Backgrounds |
| `bgImageFileName` | String | Background image filename | Filename | Backgrounds |
| `bgImageExtension` | String | Background image extension | File extension | Backgrounds |
| `bgSize` | String | Background size | "cover", "contain", "auto" | Backgrounds |
| `bgSizeType` | String | Background size mode | "original", "custom" | Backgrounds |

### Video Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `videoSrc` | String | Video source | URL or file reference | Videos |
| `videoType` | String | Video type | "url", "file", "youtube", "vimeo" | Videos |
| `autoplay` | String | Auto-play video | "on", "off" | Videos |
| `loop` | String | Loop video | "on", "off" | Videos |
| `muted` | String | Mute video | "on", "off" | Videos |

## Interactive Widget Properties

### Slider Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `slider` | String | Enable slider | "on", "off" | Galleries |
| `sliderAutoPlay` | String | Auto-advance slides | "on", "off" | Galleries |
| `sliderAutoPlaySpeed` | Number | Auto-play speed (seconds) | 1-10 | Galleries |
| `sliderDotsColorHex` | String | Slider dots color | Hex color code | Galleries |
| `sliderDotsColorOpacity` | Number | Dots opacity | 0-1 | Galleries |
| `sliderDotsColorPalette` | String | Theme dots color | Theme color ID | Galleries |
| `sliderArrowsColorHex` | String | Arrow color | Hex color code | Galleries |
| `sliderArrowsColorOpacity` | Number | Arrow opacity | 0-1 | Galleries |
| `sliderArrowsColorPalette` | String | Theme arrow color | Theme color ID | Galleries |

### Accordion Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `collapsible` | String | Allow closing all | "on", "off" | Accordions |
| `activeAccordionItem` | Number | Default open item | Item index | Accordions |
| `navIcon` | String | Navigation icon style | "thin", "thick" | Accordions |
| `navIconSize` | String | Icon size | "small", "medium", "large" | Accordions |

### Tab Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `labelText` | String | Tab/accordion label | Any string | Tabs/Accordions |
| `tabsState` | String | Tab state | "normal", "active" | Tabs |
| `tabsCurrentElement` | String | Current tab | "tabCurrentElement" | Tab containers |
| `tabsColor` | String | Tab color mode | "tabOverlay", "tabNormal" | Tab containers |

## Form & Widget Properties

### Form Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `form` | String | Form configuration | Form ID/config | Form widgets |
| `code` | String | Embed code content | HTML/JavaScript | Embed elements |

### Ministry Brands Widget Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `source` | String | Data source URL | Collection endpoint | Event/Group widgets |
| `detailPage` | String | Detail page template | Page template path | Event/Group widgets |
| `detailPageTitle` | String | Detail page title | Page title | Event widgets |
| `detailPageButtonText` | String | Button text | Button label | Event widgets |
| `detailPageSource` | String | Detail page source | Source reference | Group widgets |
| `category` | String | Content category filter | Category ID/name | Event/Group widgets |
| `columnNumber` | Number | Display columns | 1-4 | Event widgets |
| `itemsNumber` | Number | Items per page | 1-50 | Event widgets |
| `itemSpacing` | Number | Space between items | 0-100 | Event widgets |
| `itemSpacingSuffix` | String | Spacing unit | "px" | Event widgets |

### Display Control Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `showMeta` | String | Show metadata | "on", "off" | Event widgets |
| `showCategory` | String | Show categories | "on", "off" | Event/Group widgets |
| `showLocation` | String | Show location | "on", "off" | Event widgets |
| `showPagination` | String | Show pagination | "on", "off" | Event widgets |
| `showStatus` | String | Show status | "on", "off" | Group widgets |
| `showGroup` | String | Show group info | "on", "off" | Group widgets |
| `showChildcare` | String | Show childcare info | "on", "off" | Group widgets |
| `showResourceLink` | String | Show resource links | "on", "off" | Group widgets |
| `showPreview` | String | Show preview | "on", "off" | Group widgets |
| `showGroupFilter` | String | Show group filter | "on", "off" | Group widgets |
| `showOnMobile` | String | Show on mobile | "on", "off" | Various |
| `showOnTablet` | String | Show on tablet | "on", "off" | Various |

### Filter Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `categoryFilterHeading` | String | Category filter title | Any string | Group widgets |
| `groupFilterHeading` | String | Group filter title | Any string | Group widgets |
| `searchPlaceholder` | String | Search field placeholder | Any string | Group widgets |
| `addCategoryFilter` | String | Enable category filter | "on", "off" | Group widgets |
| `addCategoryFilterHeading` | String | Category filter heading | Any string | Group widgets |

## Responsive Properties

All properties support responsive variants with prefixes:
- Desktop: No prefix (base properties)
- Tablet: `tablet` prefix (e.g., `tabletPadding`, `tabletFontSize`)
- Mobile: `mobile` prefix (e.g., `mobilePadding`, `mobileFontSize`)

### Common Responsive Properties
- `tabletWidth`, `mobileWidth` - Responsive widths
- `tabletPadding*`, `mobilePadding*` - Responsive padding
- `tabletMargin*`, `mobileMargin*` - Responsive margins  
- `tabletFontSize`, `mobileFontSize` - Responsive typography
- `tabletContainerSize`, `mobileContainerSize` - Responsive containers

## State Management Properties

### Tab State Properties
| Property | Type | Description | Valid Values | Usage |
|----------|------|-------------|--------------|-------|
| `tabsState` | String | Current state | "normal", "hover", "active" | Stateful elements |
| `tabsCurrentElement` | String | Current element ID | Element identifier | Containers |

### Hover State Properties
Properties with `hover` prefix control hover state styling:
- `hoverBgColorHex`, `hoverBgColorOpacity` - Hover background
- `hoverColorHex`, `hoverColorOpacity` - Hover text color
- `hoverBorderColorHex`, `hoverBorderColorOpacity` - Hover border

## Validation Rules

### Data Type Validation
- **Numbers**: Must be within specified ranges, default to nearest valid value if outside range
- **Strings**: Must match enum values where specified, fallback to default if invalid
- **Arrays**: Must contain valid element objects, empty arrays allowed where specified
- **Colors**: Hex colors must be valid 6-character format (#RRGGBB)

### Required Property Validation
- Elements missing required properties should use sensible defaults
- Critical properties (type, _id) must be present or element is invalid
- Child items arrays should exist but can be empty

### Cross-Property Validation
- Background type must match available properties (solid requires color, gradient requires gradient properties)
- Responsive properties should cascade (mobile inherits from tablet, tablet from desktop)
- Link properties must be consistent (linkType determines which link properties are required)

## Dusk Theme Font Properties (Added 2025-09-15)

### Font Standardization Rules

#### Dusk Theme Typography Standards
| Property | Required Value | Description | Usage |
|----------|----------------|-------------|-------|
| `fontFamily` | "lato" | Standard theme font | All text elements |
| `fontFamilyType` | "google" | Font source | Required with Lato |
| `fontWeight` | 400/700 | Normal/Bold weights | Standard weights only |
| `fontStyle` | "normal"/"italic" | Font style | Maintain existing styles |

#### Font Migration Rules
- **REPLACE**: `fontFamily: "lora"` → `fontFamily: "lato"`
- **MAINTAIN**: All other font properties (size, weight, style, spacing, line height)
- **VERIFY**: `fontFamilyType: "google"` is present
- **VALIDATE**: Font weights are standard values (400, 700)

#### Priority Elements for Font Consistency
1. **RichText Elements**: Primary content text
   - Required: `fontFamily: "lato"`, `fontFamilyType: "google"`
   - Maintain: `fontSize`, `fontWeight`, `fontStyle`, `lineHeight`, `letterSpacing`

2. **Button Elements**: Interactive text
   - Required: `fontFamily: "lato"`, `fontFamilyType: "google"`
   - Common weights: 400 (normal), 700 (bold)

3. **Menu Items**: Navigation text
   - Required: `fontFamily: "lato"`, `fontFamilyType: "google"`
   - Maintain existing size and weight configurations

#### Font Property Validation for Dusk Theme
- **Font Family**: Must be "lato" (lowercase) for consistency
- **Font Source**: Must be "google" when using Lato font
- **Font Weights**: Only standard values (400, 700) to optimize loading
- **Font Styles**: "normal" and "italic" supported
- **Case Sensitivity**: Font family name must be lowercase "lato"

#### Implementation Checklist
- [ ] Search for all `fontFamily: "lora"` instances
- [ ] Replace with `fontFamily: "lato"`
- [ ] Verify `fontFamilyType: "google"` exists
- [ ] Confirm font weights are valid (400/700)
- [ ] Test font loading and display
- [ ] Validate visual consistency

---
*This document defines all properties used across Brizy Builder elements with their types, constraints, and usage patterns.*
