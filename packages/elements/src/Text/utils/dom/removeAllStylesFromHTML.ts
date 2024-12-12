import { allowedTags } from "../common";
import { cleanClassNames } from "./cleanClassNames";

function getValidStyles(element: Element): string[] {
  const baseValidStyles = ["font-weight", "color", "background-color"];
  const additionalStyles = ["font-size"];
  const parentTag = element.parentElement?.tagName ?? "";

  const isSpecialElement =
    ["A", "U"].includes(element.tagName) ||
    (element.tagName === "SPAN" && !allowedTags.includes(parentTag));

  return isSpecialElement
    ? [...baseValidStyles, ...additionalStyles]
    : baseValidStyles;
}

export function removeStylesExceptFontWeightAndColor(
  htmlString: string
): string {
  // Create a temporary element
  const tempElement = document.createElement("div");

  // Set the HTML content of the temporary element
  tempElement.innerHTML = htmlString;

  // Find elements with inline styles
  const elementsWithStyles = tempElement.querySelectorAll("[style]");

  // Iterate through elements with styles
  elementsWithStyles.forEach(function (element) {
    // Get the inline style attribute
    const styleAttribute = element.getAttribute("style") ?? "";

    // Split the inline style into individual properties
    const styleProperties = styleAttribute.split(";");

    // Initialize a new style string to retain only font-weight and color
    let newStyle = "";

    // Iterate through the style properties
    for (let i = 0; i < styleProperties.length; i++) {
      const property = styleProperties[i].trim();

      // Check if the property is font-weight or color
      const validStyles = getValidStyles(element);
      const hasProperty = validStyles.some((style) =>
        property.startsWith(style)
      );

      if (hasProperty) {
        newStyle += property + "; ";
      }
    }

    // Set the element's style attribute to retain only font-weight and color
    element.setAttribute("style", newStyle);
  });

  cleanClassNames(tempElement);
  // Return the cleaned HTML
  return tempElement.innerHTML;
}

export function removeAllStylesFromHTML(node: Element) {
  // Define the list of allowed tags
  const tagsToRemoveStyles = allowedTags.filter((item) => item !== "LI");

  // Find elements with inline styles only for allowed tags
  const elementsWithStyles = node.querySelectorAll(
    tagsToRemoveStyles.join(",") + "[style]"
  );

  // Remove the "style" attribute from each element
  elementsWithStyles.forEach(function (element) {
    element.removeAttribute("style");
  });

  // Remove the "style" attribute from each element
  cleanClassNames(node);

  node.innerHTML = removeStylesExceptFontWeightAndColor(node.innerHTML);

  // Return the cleaned HTML
  return node;
}
