import { getIconModel } from "@/Text/models/Icon";
import { textAlign } from "@/Text/utils/common";
import { findNearestBlockParent } from "utils/src/dom/findNearestBlockParent";
import { getElementPositionAmongSiblings } from "utils/src/dom/getElementPositionAmongSiblings";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { getParentElementOfTextNode } from "utils/src/dom/getParentElementOfTextNode";
import { recursiveDeleteNodes } from "utils/src/dom/recursiveDeleteNodes";

export function removeAllIcons(node: Element): Element {
  const icons = node.querySelectorAll(
    "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"]"
  );
  let iconGroups = new Map();

  icons.forEach((icon) => {
    const position = getElementPositionAmongSiblings(icon, node);
    const parentElement = findNearestBlockParent(icon);
    const parentNode = getParentElementOfTextNode(icon);
    const isIconText = parentNode.nodeName === "#text";
    const iconNode = isIconText ? icon : parentNode;
    const style = getNodeStyle(iconNode);

    const model = {
      ...getIconModel(style, icon),
      iconCode: iconNode.textContent.charCodeAt(0)
    };

    const group = iconGroups.get(parentElement) ?? { items: [] };
    iconGroups.set(parentElement, {
      ...group,
      position,
      align: textAlign[style["text-align"]],
      items: [...group.items, model]
    });
  });

  // iconGroups.forEach((button) => {
  //   iconsPositions.push(button);
  // });

  icons.forEach(recursiveDeleteNodes);

  return node;
}
