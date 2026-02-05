import { Families, Family } from "../types/type";
import { Literal, MValue } from "utils";

/**
 * Improved getFontFamily with more accurate matching.
 *
 * Issue with the original version:
 * - "Oswald, 'Oswald Light', sans-serif" normalizes to "oswald_oswald_light_sans_serif"
 * - If not found, it only uses the first part "oswald"
 * - May resolve to the wrong font (e.g. oswald_regular instead of oswald_light)
 *
 * Improvement:
 * - First tries an exact match
 * - Then searches by each part of font-family (oswald, oswald_light)
 * - Uses the first part as fallback only at the end
 */
export const getFontFamily = (
  styles: Record<string, Literal>,
  families: Families
): MValue<Family> => {
  const value = `${styles["font-family"]}`;

  // Full normalization (same as original)
  const fontFamily = value
    .replace(/['"\,]/g, "") // eslint-disable-line
    .replace(/\s/g, "_")
    .toLocaleLowerCase();

  // 1. First try to find an exact match
  if (families[fontFamily]) {
    return families[fontFamily];
  }

  // 2. Extract all parts of font-family (excluding generic families)
  const genericFamilies = [
    "sans-serif",
    "serif",
    "monospace",
    "cursive",
    "fantasy"
  ];
  const parts = value
    .split(",")
    .map((part) => {
      // Strip quotes and spaces, normalize
      const normalized = part
        .trim()
        .replace(/['"]/g, "")
        .replace(/\s/g, "_")
        .toLocaleLowerCase();
      return normalized;
    })
    .filter((part) => {
      // Filter out generic families
      return part && !genericFamilies.includes(part);
    });

  // 3. Search by each part (from most to least specific)
  // E.g. for "Oswald, 'Oswald Light', sans-serif":
  // - First try "oswald_light" (more specific)
  // - Then "oswald" (less specific)
  for (let i = parts.length - 1; i >= 0; i--) {
    const partKey = parts[i];
    if (families[partKey]) {
      return families[partKey];
    }
  }

  // 4. Fallback: use the first part (same as original)
  const [firstFontFamily] = fontFamily.split("_");
  return families[firstFontFamily];
};
