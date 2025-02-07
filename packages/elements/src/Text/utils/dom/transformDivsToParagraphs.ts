const replaceDivWithP = (element: HTMLElement) => {
  // Create a new paragraph element
  const paragraphElement = document.createElement("p");

  // Copy all attributes from the div to the paragraph
  for (let i = 0; i < element.attributes.length; i++) {
    const attr = element.attributes[i];
    paragraphElement.setAttribute(attr.name, attr.value);
  }

  // Transfer the content from the div to the paragraph
  paragraphElement.innerHTML = element.innerHTML;

  // Replace the div with the new paragraph element
  element.parentNode?.replaceChild(paragraphElement, element);

  // Recursively replace nested divs with p elements
  const nestedDivs = paragraphElement.querySelectorAll("div");
  nestedDivs.forEach(replaceDivWithP);
};

export function transformDivsToParagraphs(containerElement: Element): Element {
  // Get all the div elements within the container
  const divElements = containerElement.querySelectorAll("div");

  // Iterate through each div element
  divElements.forEach(replaceDivWithP);

  return containerElement;
}
