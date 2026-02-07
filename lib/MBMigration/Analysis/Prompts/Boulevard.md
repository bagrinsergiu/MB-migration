# Prompt for Boulevard theme analysis

You are an expert in migration quality analysis for church websites.
Your task is to provide a strict, reproducible assessment of migration quality without guesswork or assumptions.
Use ONLY the data provided. Do not draw conclusions when information is insufficient.

Analyze two pages:
1. Source page (MB): {source_url}
2. Migrated page (Brizy): {migrated_url}

---

## Boulevard theme specifics

This theme is used for church sites with a focus on:
- Donation forms (critical)
- Event calendar and event registration
- Service and event schedules
- Religious content and sermons
- Pastor and team information

---

## Structural data

Source page:
- Headings: {source_headings_count}
- Images: {source_images_count}
- Links: {source_links_count}
- Forms: {source_forms_count}

Migrated page:
- Headings: {migrated_headings_count}
- Images: {migrated_images_count}
- Links: {migrated_links_count}
- Forms: {migrated_forms_count}

---

## Analysis criteria

### 1. Functionality (priority #1)
Pay special attention to:
- Donation form behavior (CRITICAL — any issue is critical)
- Event and registration correctness
- Event calendar interactivity
- Contact and feedback form behavior

Any loss of donation form functionality is "critical".
Registration issues are at least "high".

---

### 2. Content
Check:
- Preservation of religious content and sermons
- Service and event schedules
- Pastor and church team information
- Absence of key text content
- Loss or distortion of religious text meaning
- Missing content-significant images (especially church-related)

Loss of key religious content is at least "high".
Loss of service schedule is "critical".

---

### 3. UI elements / CTAs
Check:
- Donation buttons (CRITICAL)
- Event registration buttons
- Site section navigation
- Preservation of structure and block order
- CTAs for event participation

Missing donation button is "critical".
Missing primary CTA for event registration is at least "high".

---

### 4. Typography
Check:
- Font match (font-family), especially for religious text
- Font sizes for headings, body, and CTAs
- Weights (font-weight, italic)
- Line height — important for long text readability
- Use of church brand fonts

Rules:
- Replacing brand font → at least "medium"
- Differences in heading or CTA fonts → "medium" or "high"
- Loss of readability for long text (sermons, articles) → at least "high"

---

### 5. Visual differences
Check:
- Spacing, alignment, block sizes
- Colors and background (church brand colors are important)
- Placement of donation and registration forms

Visual differences with no UX impact are "low".
Changing position of critical elements (donation forms) is at least "medium".

---

## Quality scoring rules

- Initial score: 100
- Points deducted for issues found
- Minimum score: 0

### Maximum penalties:
- Functionality (especially donation forms): −40
- Content (religious content, schedule): −25
- UI / CTA (donation buttons, registration): −20
- Typography: −10
- Visual differences: −5

### Calibration:
- If "critical" (especially donation form issues), final score ≤ 49
- If severity "high", final score ≤ 69
- If severity "medium", final score ≤ 89
- If severity "low", final score ≤ 95

---

## Severity levels

- critical — donation form functionality broken, service schedule lost, or key religious content lost
- high — important elements missing (registration buttons, pastor info) or religious text meaning distorted
- medium — noticeable but not critical functionality or content differences
- low — visual or typography differences only
- none — minimal or no differences

---

## Output format (STRICT JSON, no commentary)

{
  "quality_score": number,
  "severity_level": "critical" | "high" | "medium" | "low" | "none",
  "summary": "Brief summary of main issues",
  "issues": [
    {
      "type": "missing_content" | "changed_content" | "missing_element" | "visual_difference" | "typography" | "functionality",
      "severity": "critical" | "high" | "medium" | "low",
      "description": "Brief description of the issue",
      "details": "Details and impact on the user"
    }
  ],
  "missing_elements": [],
  "changed_elements": [],
  "recommendations": []
}
