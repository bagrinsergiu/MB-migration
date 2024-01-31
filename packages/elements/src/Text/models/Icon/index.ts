import { createCloneableModel } from "../../../Models/Cloneable";
import { ElementModel } from "../../../types/type";
import { iconSelector, textAlign } from "../../utils/common";
import { getModel } from "./utils/getModel";
import { findNearestBlockParent } from "utils/src/dom/findNearestBlockParent";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { getParentElementOfTextNode } from "utils/src/dom/getParentElementOfTextNode";

export function getIconModel(node: Element): Array<ElementModel> {
  const icons = node.querySelectorAll(iconSelector);
  const groups = new Map();

  icons.forEach((icon) => {
    const parentElement = findNearestBlockParent(icon);
    const parentNode = getParentElementOfTextNode(node);
    const isIconText = parentNode?.nodeName === "#text";
    const iconNode = isIconText ? node : parentNode;
    const style = iconNode ? getNodeStyle(iconNode) : {};
    const model = getModel(icon);
    const group = groups.get(parentElement) ?? { value: { items: [] } };

    const wrapperModel = createCloneableModel({
      _styles: ["wrapper-clone", "wrapper-clone--icon"],
      items: [...group.value.items, model],
      horizontalAlign: textAlign[style["text-align"]]
    });

    groups.set(parentElement, wrapperModel);
  });

  const models: Array<ElementModel> = [];

  groups.forEach((model) => {
    models.push(model);
  });

  return models;
}