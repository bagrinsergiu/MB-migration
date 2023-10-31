import { allowedTags } from "../common";
import { cleanClassNames } from "./cleanClassNames";

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
      if (property.startsWith("font-weight") || property.startsWith("color")) {
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

  // Find elements with inline styles only for allowed tags
  const elementsWithStyles = node.querySelectorAll(
    allowedTags.join(",") + "[style]"
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
