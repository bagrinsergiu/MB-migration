import { MValue } from "../types";

export function findNearestBlockParent(element: Element): MValue<Element> {
  if (!element.parentElement) {
    return undefined;
  }

  const displayStyle = window.getComputedStyle(element.parentElement).display;
  const isBlockElement =
    displayStyle === "block" ||
    displayStyle === "flex" ||
    displayStyle === "grid";

  if (isBlockElement) {
    return element.parentElement;
  } else {
    return findNearestBlockParent(element.parentElement);
  }
}
