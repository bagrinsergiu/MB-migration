import { findNearestBlockParent } from "./findNearestBlockParent";
import { MValue } from "@/types";

export function getElementPositionAmongSiblings(
  element: Element,
  root: Element
): MValue<string> {
  const parent = findNearestBlockParent(element);

  if (!parent) {
    console.error("No block-level parent found.");
    return;
  }

  const siblings = Array.from(root.children);

  let totalSiblings = 0;
  let index = 0;

  if (root.contains(parent)) {
    totalSiblings = siblings.length;
    index = siblings.indexOf(parent);
  }

  if (index === -1) {
    console.error("Element is not a child of its parent.");
    return;
  }

  return index === 0
    ? "top"
    : index === totalSiblings - 1
    ? "bottom"
    : "middle";
}
