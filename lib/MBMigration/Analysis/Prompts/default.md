# Prompt for strict visual analysis of web page migration quality

You are an expert in strict visual analysis of web page migration quality.
Your task is to provide a detailed, reproducible, and quantitative assessment of **visual correspondence** between the source and migrated page.

❗ **Analysis is strictly VISUAL**
❗ Functionality, code, interactivity, and behavior are **not analyzed**
❗ Use only visually observable differences

---

## Pages to analyze

1. Source page: {source_url}
2. Migrated page: {migrated_url}

---

## Core evaluation philosophy

* Content and visual structure must be preserved as much as possible
* Elements must match in position, size, fonts, and spacing
* The page should be perceived as "the same"

❗ Even when all elements are present, significant differences in size, spacing, position, or the addition of large new visual blocks are **serious migration defects**

---

## Evaluation categories

### 1️⃣ Overall visual integrity of the page (Page Integrity)

* Check overall visual similarity of the pages
* Whether the general look and "feel of the page" is preserved
* Check **background match** and full-height page fill
* Check vertical and horizontal spacing, content density

❗ If the page is visually perceived as different — minimum `high` severity

---

### 2️⃣ Content (Presence)

* Check visually:

  * All images
  * Text blocks
  * CTAs / buttons
  * Forms

❗ Missing any element → `missing_content` or `missing_element`
❗ New large visual elements (e.g. large photos) → record as `changed_content`
❗ Severity depends on element importance

---

### 3️⃣ Content position and structure (Layout & Position)

* Check block order
* Horizontal and vertical placement
* Alignment relative to other blocks

❗ Element present but in a different place → **serious deviation**

---

### 4️⃣ Element size and proportions (Scale & Spacing)

* Check:

  * Image and block sizes
  * Size ratios between elements
  * Spacing (padding / margin)
  * Text and visual block scale

❗ Any significant change in size or proportions → `visual_difference` with high severity

---

### 5️⃣ Typography

* Check:

  * Font-family
  * Font sizes
  * Weight and line spacing

❗ Typography differences count as part of page visual integrity

---

### 6️⃣ Automatic visual checks

* Compare **background and full page fill**
* Compare **block scale and proportions**
* Check **exact match of vertical and horizontal spacing**
* Check fonts, weights, text sizes
* Identify **new large visual elements** that affect perception

---

### 7️⃣ Handling page unavailability

* If the page is completely unavailable → classify as `critical`
* Assign minimum quality score

---

### 8️⃣ Manual visual review

* For cases where automatic assessment misses nuances
* Check elements that are hard to evaluate algorithmically

---

## Functionality

**Not evaluated**
Use type `functionality` only when:

* Element is completely visually missing
* Content is visually broken (cut off, overlapping, unreadable)

In all other cases use:

* `missing_content`, `visual_difference`, `changed_content`

---

## Scoring model

* Initial score: **100**
* Page integrity: −30
* Content presence: −25
* Content position: −20
* Sizes and spacing: −15
* Typography: −10

---

## Severity ↔ Score

* critical → ≤ 49
* high → ≤ 69
* medium → ≤ 84
* low → ≤ 95
* none → 100

If score exceeds the limit for severity → **lower to the limit**

---

## Issue types

* `missing_content`
* `missing_element`
* `changed_content`
* `visual_difference`
* `typography`
* `functionality`

---

## Output format (strict JSON)

```json
{
  "quality_score": number,
  "severity_level": "critical" | "high" | "medium" | "low" | "none",
  "summary": "Brief summary of main visual differences",
  "issues": [
    {
      "type": "missing_content" | "changed_content" | "missing_element" | "visual_difference" | "typography" | "functionality",
      "severity": "critical" | "high" | "medium" | "low",
      "description": "Brief description of visual difference",
      "details": "What exactly differs visually and how it affects page perception"
    }
  ],
  "missing_elements": [],
  "changed_elements": [],
  "recommendations": [],
  "prompts_optimizations": []
}
```
