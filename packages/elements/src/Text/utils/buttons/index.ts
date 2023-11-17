import { getButtonModel } from "@/Text/models/Button";
import { textAlign } from "@/Text/utils/common";
import { findNearestBlockParent } from "utils/src/dom/findNearestBlockParent";
import { getElementPositionAmongSiblings } from "utils/src/dom/getElementPositionAmongSiblings";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { recursiveDeleteNodes } from "utils/src/dom/recursiveDeleteNodes";

export function removeAllButtons(node: Element): Element {
  const buttons = node.querySelectorAll(".sites-button");
  let buttonGroups = new Map();

  buttons.forEach((button) => {
    const position = getElementPositionAmongSiblings(button, node);
    const parentElement = findNearestBlockParent(button);
    const style = getNodeStyle(button);
    const model = getButtonModel(style, button);

    const group = buttonGroups.get(parentElement) ?? { items: [] };
    buttonGroups.set(parentElement, {
      ...group,
      position,
      align: textAlign[style["text-align"]],
      items: [...group.items, model]
    });
  });

  // buttonGroups.forEach((button) => {
  //   buttonsPositions.push(button);
  // });

  buttons.forEach(recursiveDeleteNodes);

  return node;
}
