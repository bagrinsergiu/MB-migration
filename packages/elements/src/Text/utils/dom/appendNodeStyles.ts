import { extractedAttributes } from "../common";

export function appendNodeStyles(
  node: HTMLElement,
  targetNode: HTMLElement = node,
  extraAttributes: string[] = []
) {
  const styles = window.getComputedStyle(node);

  [...extractedAttributes, ...extraAttributes].forEach((style) => {
    targetNode.style.setProperty(style, styles.getPropertyValue(style));
  });
}
