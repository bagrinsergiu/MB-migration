# Image Element Implementation Rules

## Overview

This document provides specific implementation rules and clarifications for creating and handling Image elements in the Brizy Builder system, addressing gaps identified in the existing rule documentation.

## Image Element Creation Patterns

### Rule IMG-001: Image Element Structure
**Status**: ✅ **DOCUMENTED**

When creating image elements, follow this hierarchical structure:

```php
// ✅ CORRECT: Proper image element structure
$image = new BrizyImageComponent();
$wrapperImage = new BrizyWrapperComponent('wrapper--image');

// Set image properties
$image->getValue()
    ->set_imageSrc($imageSrc)
    ->set_imageFileName($fileName)
    ->set_imageExtension($extension);

// Wrap the image
$wrapperImage->getValue()->add_items([$image]);

// Add to parent component
$parentComponent->getValue()->add_items([$wrapperImage], $index);
```

### Rule IMG-002: Required Image Properties
**Status**: ✅ **DOCUMENTED**

All Image elements must include these essential properties:

#### Core Properties
- `imageSrc`: Image URL or base64 data (required)
- `imageFileName`: Original filename (required)
- `imageExtension`: File extension (png, jpg, gif, webp, svg)
- `_id`: Unique identifier (auto-generated)
- `_styles`: Must include `["image"]`

#### Dimension Properties
- `width`, `height`: Display dimensions
- `imageWidth`, `imageHeight`: Original image dimensions
- `widthSuffix`, `heightSuffix`: Unit suffix (px, %, vh, vw)
- `sizeType`: "original", "custom", "cover", "contain"

#### Responsive Properties
- `tabletWidth`, `tabletHeight`: Tablet dimensions
- `mobileWidth`, `mobileHeight`: Mobile dimensions
- `mobileSizeType`: Mobile size behavior
- Suffix properties for all responsive variants

### Rule IMG-003: Image Extension Handling
**Status**: ✅ **DOCUMENTED**

```php
// ✅ CORRECT: Extract extension from filename
if (!empty($imageFileName)) {
    $extension = pathinfo($imageFileName, PATHINFO_EXTENSION);
    if ($extension) {
        $image->getValue()->set_imageExtension($extension);
    }
}

// ❌ INCORRECT: Hardcoded extension
$image->getValue()->set_imageExtension('png'); // Don't assume format
```

### Rule IMG-004: Dimension Validation and Fallbacks
**Status**: ✅ **DOCUMENTED**

```php
// ✅ CORRECT: Validate dimensions with fallbacks
if (!empty($sizes['width']) && !empty($sizes['height'])) {
    // Handle percentage-based sizes
    if (strpos($sizes['width'], '%') !== false) {
        $selectorImageSizes = '[data-id="' . $id . '"] .photo-container';
        $sizes = $this->getDomElementSizes($selectorImageSizes, $browserPage);
    }
    
    // Set display dimensions
    $image->getValue()
        ->set_width((int)$sizes['width'])
        ->set_height((int)$sizes['height'])
        ->set_widthSuffix($sizeUnit)
        ->set_heightSuffix($sizeUnit);
        
    // Set responsive variants
    $image->getValue()
        ->set_tabletWidth((int)$sizes['width'])
        ->set_tabletHeight((int)$sizes['height'])
        ->set_mobileWidth((int)$sizes['width'])
        ->set_mobileHeight((int)$sizes['height'])
        ->set_mobileSizeType('original');
}
```

### Rule IMG-005: Original Image Dimensions
**Status**: ✅ **DOCUMENTED**

Original image dimensions should be set separately from display dimensions:

```php
// ✅ CORRECT: Set original dimensions when available
if (!empty($mbSectionItem['settings']['image']['width'])) {
    $image->getValue()->set_imageWidth($mbSectionItem['settings']['image']['width']);
}
if (!empty($mbSectionItem['settings']['image']['height'])) {
    $image->getValue()->set_imageHeight($mbSectionItem['settings']['image']['height']);
}
```

### Rule IMG-006: Link Handling on Images
**Status**: ✅ **DOCUMENTED**

When images have associated links, apply link properties to the image element itself:

```php
// ✅ CORRECT: Apply link to image element
$this->handleLink(
    $mbSectionItem,
    $image,  // Apply to image, not wrapper
    $linkSelector,
    $browserPage
);
```

## Method Implementation Patterns

### Rule IMPL-001: Image Handler Method Signature
**Status**: ✅ **DOCUMENTED**

Image handling methods should follow this signature pattern:

```php
private function handlePhotoItem(
    $mbSectionItemId,           // Source item identifier
    $mbSectionItem,             // Source item data
    BrizyComponent $brizyComponent,  // Parent container
    BrowserPageInterface $browserPage,  // Browser instance
    $families = [],             // Font families
    $default_fonts = 'helvetica_neue_helveticaneue_helvetica_arial_sans',
    $index = null               // Insertion index
): BrizyComponent
```

### Rule IMPL-002: Error Handling in Image Methods
**Status**: ✅ **DOCUMENTED**

Image handling methods should include proper error handling:

```php
// ✅ CORRECT: Validate content before processing
if (!empty($mbSectionItem['content'])) {
    // Process image
} else {
    Logger::instance()->warning('Empty image content for item: ' . $mbSectionItemId);
    return $brizyComponent; // Return unchanged
}
```

### Rule IMPL-003: Index-Based Insertion
**Status**: ✅ **DOCUMENTED**

When adding images to components, support index-based insertion:

```php
// ✅ CORRECT: Support index parameter
$brizyComponent->getValue()->add_items([$wrapperImage], $index);

// This allows precise positioning in component hierarchy
```

## Security Considerations for Images

### Rule SEC-IMG-001: Image Source Validation
**Status**: 🔧 **NEEDS IMPLEMENTATION**

Image sources should be validated before use:

```php
// ✅ REQUIRED: Validate image sources
private function validateImageSource(string $imageSrc): bool {
    // Check for valid URL or base64
    if (filter_var($imageSrc, FILTER_VALIDATE_URL)) {
        return true;
    }
    
    // Check for valid base64 image data
    if (preg_match('/^data:image\/(png|jpg|jpeg|gif|webp|svg\+xml);base64,/', $imageSrc)) {
        return true;
    }
    
    return false;
}
```

### Rule SEC-IMG-002: Filename Sanitization
**Status**: 🔧 **NEEDS IMPLEMENTATION**

Image filenames should be sanitized:

```php
// ✅ REQUIRED: Sanitize filenames
private function sanitizeFileName(string $fileName): string {
    // Remove directory traversal attempts
    $fileName = basename($fileName);
    
    // Remove or replace dangerous characters
    $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $fileName);
    
    // Limit length
    return substr($fileName, 0, 255);
}
```

## Performance Considerations

### Rule PERF-IMG-001: Image Size Optimization
**Status**: 📋 **RECOMMENDATION**

Consider image size optimization during migration:

```php
// 📋 RECOMMENDED: Log large images for optimization
if (!empty($sizes['width']) && $sizes['width'] > 2000) {
    Logger::instance()->info("Large image detected: {$imageFileName} ({$sizes['width']}px)");
}
```

## Common Pitfalls and Solutions

### Pitfall 1: Missing Wrapper Element
❌ **Problem**: Adding image directly to parent component
✅ **Solution**: Always wrap images in `wrapper--image`

### Pitfall 2: Hardcoded Extensions
❌ **Problem**: Using default 'png' extension for all images
✅ **Solution**: Extract extension from filename using `pathinfo()`

### Pitfall 3: Ignoring Responsive Dimensions
❌ **Problem**: Only setting desktop dimensions
✅ **Solution**: Set tablet and mobile dimensions for responsive behavior

### Pitfall 4: Missing Error Handling
❌ **Problem**: Not checking for empty content
✅ **Solution**: Validate input data before processing

## Integration with Existing Rules

This document supplements the existing rules:
- **RuleElements.md**: Provides general Image element rules
- **propertiesUseElements.md**: Defines available properties
- **codeQualityRules.md**: General code quality standards
- **securityRules.md**: Security requirements

## Validation Checklist

When implementing image handling methods:

- [ ] Creates proper wrapper--image structure
- [ ] Sets all required properties (imageSrc, imageFileName, extension)
- [ ] Handles dimension validation with fallbacks
- [ ] Supports index-based insertion
- [ ] Includes proper error handling
- [ ] Validates input data
- [ ] Sets responsive properties
- [ ] Handles link properties if present
- [ ] Uses consistent method signatures
- [ ] Follows existing code style guidelines
