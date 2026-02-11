---
apply: on_demand
---

# Dusk - Font Issues #762

## Issue Description

**Title**: Dusk - font issues #762  
**Priority**: High  
**Status**: Active  
**Date Created**: 2025-09-15

### Problem Summary

The Dusk theme in the Brizy Builder project has font inconsistencies where:
1. Some fonts don't match the Clover website reference
2. Font caching issues persist on the first website even after cache clearing
3. Pages are using **Lora** font when they should be using **Lato** font for consistency with the Brizy site

### Affected Areas

- **Theme**: Dusk only
- **Scope**: Builder directory (`lib/MBMigration/Builder/Layout/Theme/Dusk`)
- **Impact**: Visual inconsistency, poor user experience, brand inconsistency

## Analysis Results

### Current Font Usage Patterns

Based on project analysis:

1. **Existing Fonts Found in Dusk Theme**:
   - `poppins` (Google font) - used in sub-menu items
   - `montserrat` (Google font) - used in donation buttons
   - Various other fonts in truncated JSON configurations

2. **Problematic Font References**:
   - **Lora font** found extensively throughout the project:
     - In `lib/MBMigration/Builder/Fonts/googleFonts.json` (lines 17233-17259)
     - Multiple page files in `var/log/mb_tmp/` directories
     - Font weight variants: 400, 500, 600, 700, italic, 500italic

3. **Target Font**:
   - **Lato** should be the standard font for consistency with Brizy site

### Existing Font Rules Structure

Current font-related rules exist in:
- `aiRules/RuleBrizyBuilderElements/RuleElements.md` - Typography properties for RichText elements
- `aiRules/RuleBrizyBuilderElements/propertiesUseElements.md` - Font properties (fontFamily, fontSize, fontWeight, etc.)

## Action Plan

### Phase 1: Documentation and Rule Updates (No Code Changes)

#### 1.1 Update RuleElements.md
- **File**: `aiRules/RuleBrizyBuilderElements/RuleElements.md`
- **Action**: Add new font consistency rules
- **Details**:
  ```markdown
  ### Font Consistency Rules for Dusk Theme
  - **Standard Font**: Lato (Google font)
  - **Replacement Rules**:
    - Replace all instances of "Lora" with "Lato"
    - Ensure fontFamilyType is set to "google"
    - Maintain font weights: 400 (regular), 700 (bold), italic variants
  - **Priority Elements**: RichText, Button, Heading elements
  ```

#### 1.2 Update propertiesUseElements.md
- **File**: `aiRules/RuleBrizyBuilderElements/propertiesUseElements.md`
- **Action**: Add Dusk-specific font property rules
- **Details**:
  ```markdown
  ### Dusk Theme Font Properties
  | Property | Value | Description | Usage |
  |----------|-------|-------------|-------|
  | fontFamily | "lato" | Standard theme font | All text elements |
  | fontFamilyType | "google" | Font source | Required with Lato |
  | fontWeight | 400/700 | Normal/Bold weights | Standard weights only |
  
  ### Font Replacement Rules
  - **REPLACE**: fontFamily: "lora" → fontFamily: "lato"
  - **MAINTAIN**: All other font properties (size, weight, style)
  - **VERIFY**: fontFamilyType: "google" is present
  ```

#### 1.3 Update mainRules.md
- **File**: `aiRules/RuleBrizyBuilderElements/mainRules.md`
- **Action**: Register new font consistency rules
- **Details**:
  ```markdown
  ## Font Consistency Rules (Added 2025-09-15)
  
  ### Rule ID: FONT-001 - Dusk Theme Font Standardization
  **Purpose**: Ensure consistent font usage across Dusk theme
  **Scope**: All Dusk theme elements
  **Requirements**:
  1. Replace "lora" font family with "lato"
  2. Maintain existing font weights and styles
  3. Ensure fontFamilyType is "google"
  4. Apply to: RichText, Button, Heading elements
  
  ### Rule ID: FONT-002 - Font Cache Management
  **Purpose**: Address font caching issues
  **Scope**: Font loading and caching mechanisms
  **Requirements**:
  1. Clear font cache after font changes
  2. Verify font loading in browser
  3. Test across different devices/browsers
  ```

### Phase 2: Implementation Guidelines

#### 2.1 Font Search and Replace Strategy
1. **Identify Problematic Files**:
   - Search for "lora" in Dusk theme files
   - Focus on: `blocksKit.json`, PHP element files
   - Check: Text elements, Button elements, Heading elements

2. **Replacement Pattern**:
   ```json
   // BEFORE
   "fontFamily": "lora"
   
   // AFTER  
   "fontFamily": "lato"
   ```

3. **Verification Checklist**:
   - [ ] Font family changed to "lato"
   - [ ] fontFamilyType is "google"
   - [ ] Font weights preserved (400, 700, italic)
   - [ ] No other properties affected

#### 2.2 Files to Examine
Based on analysis, focus on:
- `lib/MBMigration/Builder/Layout/Theme/Dusk/blocksKit.json`
- `lib/MBMigration/Builder/Layout/Theme/Dusk/Elements/Text/*.php`
- Any custom font configuration files in Dusk theme

#### 2.3 Testing Requirements
1. **Visual Testing**:
   - Compare with Clover website reference
   - Verify font consistency across pages
   - Check font loading speed

2. **Technical Testing**:
   - Clear browser cache
   - Test on different browsers
   - Verify font file loading

### Phase 3: Quality Assurance

#### 3.1 Pre-Implementation Checklist
- [ ] Backup current Dusk theme configuration
- [ ] Document all current font usages
- [ ] Prepare rollback plan

#### 3.2 Post-Implementation Checklist
- [ ] All "lora" instances replaced with "lato"
- [ ] Font consistency verified visually
- [ ] No broken font references
- [ ] Cache clearing resolved loading issues
- [ ] Cross-browser compatibility confirmed

## Expected Outcomes

### Immediate Results
1. **Font Consistency**: All Dusk theme elements use Lato font
2. **Visual Alignment**: Better match with Clover website reference  
3. **Cache Resolution**: Font loading issues resolved

### Long-term Benefits
1. **Brand Consistency**: Uniform font usage across Brizy platform
2. **Maintenance**: Easier font management with standardized rules
3. **Performance**: Reduced font loading complexity

## Risk Assessment

### Low Risk
- Font family replacement (simple text substitution)
- Rule documentation updates

### Medium Risk  
- Font weight/style compatibility between Lora and Lato
- Cache clearing effectiveness

### Mitigation Strategies
- Test font compatibility before full deployment
- Implement gradual rollout with monitoring
- Maintain rollback capability

## Dependencies

### External Dependencies
- Google Fonts API (for Lato font)
- Browser font rendering capabilities

### Internal Dependencies
- Existing aiRules system compliance
- Brizy Builder font management system
- Theme compilation and deployment process

## Success Metrics

1. **Zero instances** of "lora" font in Dusk theme files
2. **100% Lato usage** for all text elements in Dusk theme
3. **Visual consistency** with reference Clover website
4. **No font loading errors** in browser console
5. **Improved page load performance** (font caching resolved)

---

**Note**: This task follows the principle of **adding rules only, no deletion** of existing guidelines. All changes are additive and focused on the specific Dusk theme font inconsistency issue.
