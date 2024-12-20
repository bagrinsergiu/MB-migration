import { MenuItemSelector } from "../../types/type";

interface ToMenuItemElement {
  node: Element | null;
  selector?: MenuItemSelector;
  targetSelector: string;
}

interface ToMenuItemElementRes {
  item: Element;
  pseudoEl: string;
}

export const toMenuItemElement = ({
  node,
  selector,
  targetSelector
}: ToMenuItemElement): ToMenuItemElementRes | undefined => {
  if (!selector || !node) {
    return;
  }

  const targetElement = node.querySelector(targetSelector);

  if (targetElement) {
    return {
      item: targetElement,
      pseudoEl: selector.pseudoEl
    };
  }

  return;
};
