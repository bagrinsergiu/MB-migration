import { extractedAttributes } from "../common";

export function appendNodeStyles(
  node: HTMLElement,
  targetNode: HTMLElement = node
) {
  const styles = window.getComputedStyle(node);
  extractedAttributes.forEach((style) => {
    targetNode.style.setProperty(style, styles.getPropertyValue(style));
  });
}
