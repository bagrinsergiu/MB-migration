import { extractedAttributes } from "../common";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

const attributes = extractedAttributes;

const appenStylesToNode = (node: HTMLElement, styles: CSSStyleDeclaration) => {
  const parentElement = node.parentElement;
  if (
    attributes.includes("text-transform") &&
    styles.textTransform === "uppercase"
  ) {
    node.classList.add("brz-capitalize-on");
  }

  if (styles.color) {
    node.style.color = styles.color;
  }

  if (styles.backgroundColor) {
    node.style.backgroundColor = styles.backgroundColor;
  }

  if (styles.fontWeight) {
    node.style.fontWeight = styles.fontWeight;
  }

  if (parentElement?.tagName === "U") {
    parentElement.style.color = styles.color;
  }
};

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
          const firstChild = parentElement.firstChild;

          if (firstChild instanceof HTMLElement) {
            const computedStyles = window.getComputedStyle(firstChild);

            appenStylesToNode(firstChild, computedStyles);
          }

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

      if (!parentStyle?.fontWeight) {
        const fontWeight =
          parentElement.tagName === "STRONG"
            ? getComputedStyle(parentElement).fontWeight
            : parentOfParent.style.fontWeight;

        if (fontWeight) {
          parentElement.style.fontWeight = fontWeight;
        }
      }

      if (parentOfParent.tagName === "SPAN") {
        const parentFontWeight = parentElement.style.fontWeight;
        parentElement.style.fontWeight =
          parentFontWeight || getComputedStyle(parentElement).fontWeight;
      }

      if (
        parentOfParent.tagName === "EM" &&
        parentComputedStyle.fontStyle === "normal"
      ) {
        // If an <em> element is present and its child is a <span> with font-style: normal,
        // replace the <em> element with the <span>, as the <em> is unnecessary in this case.

        const spanElement = document.createElement("span");

        // Copy attributes from the <em> element to the <span> element
        Array.from(parentOfParent.attributes).forEach((attr) => {
          spanElement.setAttribute(attr.name, attr.value);
        });

        // Move the children from the <em> element to the new <span> element
        while (parentOfParent.firstChild) {
          spanElement.appendChild(parentOfParent.firstChild);
        }

        // Set the color of the <span> element to the color of the parent element
        spanElement.style.color =
          parentStyle.color || getComputedStyle(parentOfParent).color;

        // Replace the <em> element with the new <span> element
        parentOfParent.replaceWith(spanElement);
      }

      return;
    }

    let innerElementType = "span";

    const computedStyles = window.getComputedStyle(parentElement);
    const parentStyle = getNodeStyle(parentElement);

    if (parentElement.tagName === "A") {
      // Quill not support span inside <a> tag
      appenStylesToNode(parentElement, computedStyles);
      return;
    }

    // Need to replace the span to em for Quill(Brizy Builder)
    if (
      attributes.includes("font-style") &&
      (parentStyle["font-style"] === "italic" ||
        computedStyles.fontStyle === "italic")
    ) {
      innerElementType = "em";
    }

    const innerElement = document.createElement(innerElementType);

    appenStylesToNode(innerElement, computedStyles);

    innerElement.textContent = element.textContent;

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
