---
apply: always
---

# Kit List Blocks - Brizy Builder Elements

This document contains a complete list of all elements available in Brizy Builder, extracted from `blocksKit.json` and `globalBlocksKit.json` files.

## Global Elements (from globalBlocksKit.json)

### Blocks
| ID | Name | Type | Description |
|---|---|---|---|
| `grid-media-layout` | Grid Media Layout | Block | Media grid layout component |
| `list-media-layout` | List Media Layout | Block | Media list layout component |
| `event-list-layout` | Event List Layout | Block | Event list display layout |
| `event-grid-layout` | Event Grid Layout | Block | Event grid display layout |
| `event-tile-layout` | Event Tile Layout | Block | Event tile display layout |

### Global Components
| ID | Name | Type | Description |
|---|---|---|---|
| `main` | Main Section | Section | Base section template |
| `Row` | Row Element | Row | Basic row container |
| `Column` | Column Element | Column | Basic column container |
| `wrapper--richText` | Rich Text Wrapper | Wrapper | Wrapper for rich text content |
| `wrapper--embedCode` | Embed Code Wrapper | Wrapper | Wrapper for embedded code |
| `wrapper--button` | Button Wrapper | Cloneable | Wrapper for button elements |
| `wrapper--image` | Image Wrapper | Wrapper | Wrapper for image elements |
| `wrapper--icon` | Icon Wrapper | Cloneable | Wrapper for icon elements |
| `wrapper--column` | Column Wrapper | Column | Wrapper column element |
| `wrapper--row` | Row Wrapper | Row | Wrapper row element |
| `wrapper--line` | Line Wrapper | Wrapper | Wrapper for line elements |
| `wrapper--form` | Form Wrapper | Wrapper | Wrapper for form elements |
| `wrapper--sermonLayout` | Sermon Layout Wrapper | Wrapper | Wrapper for sermon layout |
| `donation-button` | Donation Button | Cloneable | Donation button component |

### Dynamic Elements
| ID | Name | Type | Description |
|---|---|---|---|
| `Section` | Dynamic Section | Section | Dynamic section template |
| `GridMediaLayout` | Grid Media Layout | Section | Grid-based media layout |
| `SermonFeatured` | Featured Sermon | Section | Featured sermon display |
| `ListMediaLayout` | List Media Layout | Section | List-based media layout |
| `PrayerForm` | Prayer Form | Section | Prayer submission form |
| `EventLayoutElement` | Event Layout Element | Section | Event display element |
| `EventFeatured` | Featured Event | Section | Featured event display |
| `EventGalleryLayoutElement` | Event Gallery Layout | Section | Event gallery display |
| `GroupLayout` | Group Layout | Section | Group listing layout |
| `EventDetailsPage` | Event Details Page | Section | Event details page template |

### Theme Elements
| ID | Name | Type | Description |
|---|---|---|---|
| `Aurora` | Aurora Theme | Section | Aurora theme section template |

## Theme-Specific Elements (from Solstice blocksKit.json)

### Navigation & Structure
| ID | Name | Type | Description |
|---|---|---|---|
| `menu` | Menu Block | Section | Navigation menu component |
| `footer` | Footer Block | SectionFooter | Footer section component |

### Content Layout Blocks
| ID | Name | Type | Description |
|---|---|---|---|
| `left-media` | Left Media Block | Section | Left-aligned media layout |
| `right-media` | Right Media Block | Section | Right-aligned media layout |
| `full-text` | Full Text Block | Section | Full-width text layout |
| `full-media` | Full Media Block | Section | Full-width media layout |
| `left-media-circle` | Left Media Circle Block | Section | Left media with circular elements |
| `three-bottom-media-circle` | Three Bottom Media Circle | Section | Three media elements with circles |
| `four-horizontal-text` | Four Horizontal Text | Section | Four column text layout |
| `three-top-media` | Three Top Media | Section | Three top-aligned media elements |

### Specialized Layouts
| ID | Name | Type | Description |
|---|---|---|---|
| `gallery-layout` | Gallery Layout | Section | Image gallery with slider |
| `livestream-layout` | Livestream Layout | Section | Livestream video layout |
| `abstract-media-layout` | Abstract Media Layout | Section | Abstract media presentation |
| `event-calendar-layout` | Event Calendar Layout | Section | Calendar view for events |
| `event-list-layout` | Event List Layout | Section | List view for events |
| `event-tile-layout` | Event Tile Layout | Section | Tile view for events |
| `event-gallery-layout` | Event Gallery Layout | Section | Gallery view for events |
| `grid-layout` | Grid Layout | Section | Generic grid layout |
| `list-layout` | List Layout | Section | Generic list layout |

### Interactive Elements
| ID | Name | Type | Description |
|---|---|---|---|
| `form` | Form Layouts | Section | Various form layouts |
| `prayer-form` | Prayer Form | Section | Prayer submission form |
| `prayer-list` | Prayer List | Section | Prayer request listing |
| `small-groups-list` | Small Groups List | Section | Small groups directory |
| `tabs-layout` | Tabs Layout | Section | Tabbed content layout |
| `accordion-layout` | Accordion Layout | Section | Accordion/collapsible content |

## Common Element Sub-Components

### Form Elements
| ID | Name | Type | Description |
|---|---|---|---|
| `full-width` | Full Width Form | Section | Full-width form layout |
| `left-form` | Left Form | Section | Left-aligned form layout |
| `right-form` | Section | Right-aligned form layout |
| `form-wrapper` | Form Wrapper | Wrapper | Ministry Brands form widget wrapper |

### Menu Elements
| ID | Name | Type | Description |
|---|---|---|---|
| `item` | Menu Item | MenuItem | Navigation menu item |

### Footer Elements
| ID | Name | Type | Description |
|---|---|---|---|
| `item-text` | Footer Text Item | Column | Text content in footer |
| `item-image` | Footer Image Item | Column | Image content in footer |
| `item-empty` | Footer Empty Item | Column | Empty space in footer |
| `item` | Footer Icon Item | Icon | Social media icons in footer |

### Layout Components
| ID | Name | Type | Description |
|---|---|---|---|
| `column` | Layout Column | Column | Generic column component |
| `spacer` | Spacer Element | Wrapper | Spacing element |
| `media-circles` | Media Circles | Complex | Circular media arrangement |
| `image` | Image Element | Wrapper | Image display wrapper |
| `slide` | Gallery Slide | SectionItem | Gallery slider item |
| `video` | Video Slide | SectionItem | Video slider item |
| `head` | Section Head | Row | Header row for sections |
| `row` | Generic Row | Row | Generic row container |
| `item` | Generic Item | Various | Generic item component |
| `events` | Events Widget | Wrapper | Events display widget |
| `widget` | Generic Widget | Wrapper | Generic widget wrapper |
| `detail` | Detail View | Section | Detailed view template |

## Element Hierarchy Summary

```
Brizy Builder Elements
├── Sections
│   ├── Global Sections (main, dynamic themes)
│   ├── Layout Sections (media, text, gallery layouts)
│   ├── Interactive Sections (forms, calendars, tabs)
│   └── Theme Sections (navigation, footer)
├── Containers
│   ├── Rows (layout structure)
│   ├── Columns (content containers)
│   └── Wrappers (content wrappers)
├── Content Elements
│   ├── Text Elements (RichText)
│   ├── Media Elements (Image, Video)
│   ├── Interactive Elements (Button, Icon, Form)
│   └── Widget Elements (Ministry Brands widgets)
└── Specialized Components
    ├── Navigation (Menu, MenuItem)
    ├── Event Systems (calendars, lists, details)
    ├── Form Systems (prayer, contact, donation)
    └── Media Systems (galleries, sliders, grids)
```

## Total Element Count
- **Global Elements**: 13 core components + 10 dynamic layouts + 1 theme = 24 elements
- **Theme-Specific Elements**: 20 main blocks + 30+ sub-components = 50+ elements
- **Total Unique Elements**: 70+ distinct element types

---
*Generated from blocksKit.json and globalBlocksKit.json files for Brizy Builder Elements documentation.*
