export const extractAllFontFamilies = () => {
  // Use a Set to store unique font-family values
  const fontFamiliesSet = new Set();

  // Iterate through all elements on the page
  document.querySelectorAll('*').forEach(element => {
    const fontFamily = getComputedStyle(element).fontFamily;

    // Add the font-family to the Set if it's defined and non-empty
    if (fontFamily) {
      fontFamiliesSet.add(fontFamily);
    }
  });

  // Process font-family values: split, clean, normalize, and deduplicate
  const uniqueFontFamilies = Array.from(fontFamiliesSet)
    .flatMap(font => font.split(',')) // Split multiple fonts in a single property
    .map(font =>
      font.trim()                     // Remove extra whitespace
        .replace(/^["']|["']$/g, '')  // Remove quotes
        .replace(/\s+/g, '_')         // Replace spaces with underscores
        .toLowerCase()
    )
    .filter(Boolean); // Remove empty strings or invalid values

  // Return the final list of unique font-family values
  return Array.from(new Set(uniqueFontFamilies));
};
