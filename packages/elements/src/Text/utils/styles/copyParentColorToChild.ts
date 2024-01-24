import { extractedAttributes } from "../common";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

const attributes = extractedAttributes;

export function copyColorStyleToTextNodes(element: Element): void {
  if (element.nodeType === Node.TEXT_NODE) {
    const parentElement = element.parentElement;

    if (!parentElement) {
      return;
    }

    if (parentElement.tagName === "SPAN") {
      const parentOfParent = element.parentElement.parentElement;
      const parentElement = element.parentElement;
      const parentStyle = parentElement.style;

      if (
        attributes.includes("text-transform") &&
        !parentStyle?.textTransform
      ) {
        const style = getNodeStyle(parentElement);
        if (style["text-transform"] === "uppercase") {
          parentElement.classList.add("brz-capitalize-on");
        }
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

    const spanElement = document.createElement("span");
    const computedStyles = window.getComputedStyle(parentElement);

    if (
      attributes.includes("text-transform") &&
      computedStyles.textTransform === "uppercase"
    ) {
      spanElement.classList.add("brz-capitalize-on");
    }

    if (computedStyles.color) {
      spanElement.style.color = computedStyles.color;
    }

    if (computedStyles.fontWeight) {
      spanElement.style.fontWeight = computedStyles.fontWeight;
    }

    spanElement.textContent = element.textContent;

    if (parentElement.tagName === "U") {
      element.parentElement.style.color = computedStyles.color;
    }

    if (element) {
      element.parentElement.replaceChild(spanElement, element);
    }
  } else if (element.nodeType === Node.ELEMENT_NODE) {
    // If the current node is an element node, recursively process its child nodes
    const children = element.children;
    for (let i = 0; i < children.length; i++) {
      copyColorStyleToTextNodes(children[i] as Element);
    }
  }
}

export function copyParentColorToChild(node: Element) {
  node.childNodes.forEach((child) => {
    copyColorStyleToTextNodes(child as Element);
  });

  return node;
}
