export const extractAllFontFamilies = () => {
  // Use a Set to store unique font-family values
  const fontFamiliesSet = new Map();

  // Iterate through all elements on the page
  document.querySelectorAll("*").forEach((element) => {
    const fontFamily = getComputedStyle(element).fontFamily;

    // Add the font-family to the Set if it's defined and non-empty
    if (fontFamily) {
      fontFamiliesSet.set(createId(fontFamily), fontFamily);
    }
  });

  return Array.from(fontFamiliesSet);
};

function createId(font) {
  return `${font}`
    .replace(/['"\,]/g, "") // eslint-disable-line
    .replace(/\s/g, "_")
    .toLocaleLowerCase()
}
