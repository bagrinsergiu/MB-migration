import { extractedAttributes } from "../common";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

const attributes = extractedAttributes;

export function copyColorStyleToTextNodes(element: Element): void {
  if (element.nodeType === Node.TEXT_NODE) {
    let parentElement = element.parentElement;

    if (!parentElement) {
      return;
    }

    if (
      parentElement.tagName === "SPAN" ||
      parentElement.tagName === "EM" ||
      parentElement.tagName === "STRONG"
    ) {
      const parentOfParent = parentElement.parentElement;
      const parentStyle = parentElement.style;
      const parentComputedStyle = getComputedStyle(parentElement);

      if (
        attributes.includes("text-transform") &&
        !parentStyle?.textTransform
      ) {
        const style = getNodeStyle(parentElement);
        if (style["text-transform"] === "uppercase") {
          parentElement.classList.add("brz-capitalize-on");
        }
      }

      if (
        attributes.includes("font-style") &&
        parentComputedStyle.fontStyle === "italic"
      ) {
        const emElement = document.createElement("em");

        // Clone the attributes and child nodes from the current parent element
        Array.from(parentElement.attributes).forEach((attr) => {
          emElement.setAttribute(attr.name, attr.value);
        });

        while (parentElement.firstChild) {
          emElement.appendChild(parentElement.firstChild);
        }

        parentElement.replaceWith(emElement);
        parentElement = emElement;
      }

      if (!parentOfParent) {
        return;
      }

      if (!parentStyle?.color) {
        const parentOFParentStyle = getNodeStyle(parentOfParent);
        parentElement.style.color = `${parentOFParentStyle.color}`;
      }
      if (!parentStyle?.fontWeight && parentOfParent.style?.fontWeight) {
        parentElement.style.fontWeight = parentOfParent.style.fontWeight;
      }

      if (parentOfParent.tagName === "SPAN") {
        const parentFontWeight = parentElement.style.fontWeight;
        parentElement.style.fontWeight =
          parentFontWeight || getComputedStyle(parentElement).fontWeight;
      }

      return;
    }

    let innerElementType = "span";

    const computedStyles = window.getComputedStyle(parentElement);
    const parentStyle = getNodeStyle(parentElement);

    // Need to replace the span to em for Quill(Brizy Builder)
    if (
      attributes.includes("font-style") &&
      (parentStyle["font-style"] === "italic" ||
        computedStyles.fontStyle === "italic")
    ) {
      innerElementType = "em";
    }

    const innerElement = document.createElement(innerElementType);

    if (
      attributes.includes("text-transform") &&
      computedStyles.textTransform === "uppercase"
    ) {
      innerElement.classList.add("brz-capitalize-on");
    }

    if (computedStyles.color) {
      innerElement.style.color = computedStyles.color;
    }

    if (computedStyles.fontWeight) {
      innerElement.style.fontWeight = computedStyles.fontWeight;
    }

    innerElement.textContent = element.textContent;

    if (parentElement.tagName === "U") {
      parentElement.style.color = computedStyles.color;
    }

    if (element) {
      parentElement.replaceChild(innerElement, element);
    }
  } else if (element.nodeType === Node.ELEMENT_NODE) {
    // If the current node is an element node, recursively process its child nodes
    const children = element.childNodes;
    for (let i = 0; i < children.length; i++) {
      const node = children[i];
      // Check if not "\n" or empty ""
      if (node.textContent?.trim()) {
        copyColorStyleToTextNodes(node as Element);
      }
    }
  }
}

export function copyParentColorToChild(node: Element) {
  node.childNodes.forEach((child) => {
    copyColorStyleToTextNodes(child as Element);
  });

  return node;
}
