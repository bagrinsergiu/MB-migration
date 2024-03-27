import { createCloneableModel } from "../../../Models/Cloneable";
import { ElementModel } from "../../../types/type";
import { buttonSelector, textAlign } from "../../utils/common";
import { getModel } from "./utils/getModel";
import { findNearestBlockParent } from "utils/src/dom/findNearestBlockParent";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

export function getButtonModel(
  node: Element,
  urlMap: Record<string, string>
): Array<ElementModel> {
  const buttons = node.querySelectorAll(buttonSelector);
  const groups = new Map();

  buttons.forEach((button) => {
    const parentElement = findNearestBlockParent(button);
    const style = getNodeStyle(button);
    const model = getModel(button, urlMap);
    const group = groups.get(parentElement) ?? { value: { items: [] } };

    const wrapperModel = createCloneableModel({
      _styles: ["wrapper-clone", "wrapper-clone--button"],
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
