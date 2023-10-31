export function transformDivsToParagraphs(containerElement: Element): Element {
  // Get all the div elements within the container
  const divElements = containerElement.querySelectorAll("div");

  // Iterate through each div element
  divElements.forEach(function (divElement) {
    // Create a new paragraph element
    const paragraphElement = document.createElement("p");

    // Copy all attributes from the div to the paragraph
    for (let i = 0; i < divElement.attributes.length; i++) {
      const attr = divElement.attributes[i];
      paragraphElement.setAttribute(attr.name, attr.value);
    }

    // Transfer the content from the div to the paragraph
    paragraphElement.innerHTML = divElement.innerHTML;

    // Replace the div with the new paragraph element
    divElement.parentNode?.replaceChild(paragraphElement, divElement);
  });

  return containerElement;
}
