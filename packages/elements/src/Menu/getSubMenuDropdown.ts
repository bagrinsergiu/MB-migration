import { getModel } from "./utils/getModel";
import {
  Families,
  MenuItemElement,
  Output,
  SubMenuDropDownEntry
} from "elements/src/types/type";
import { createData } from "elements/src/utils/getData";
import { Literal, MValue } from "utils";
import { prefixed } from "utils/src/models/prefixed";

interface SubMenuDropDownData {
  node: MenuItemElement;
  families: Families;
  defaultFamily: string;
  isBgHoverItemMenu?: boolean;
}

const getV = (entry: SubMenuDropDownData) => {
  const { node, families, defaultFamily } = entry;

  const bgModel: Record<string, MValue<Literal | boolean>> = {
    "menu-bg-color-hex": undefined,
    "menu-bg-color-opacity": 1,
    "menu-bg-color-palette": ""
  };

  const bgV = getModel({
    node,
    modelDefaults: bgModel,
    families: families,
    defaultFamily: defaultFamily
  });

  return prefixed(bgV, "subMenu");
};

const getSubMenuDropdown = (entry: SubMenuDropDownEntry): Output => {
  const { nodeSelector, families, defaultFamily } = entry;
  const itemElement = document.querySelector(nodeSelector.selector);

  if (!itemElement) {
    return {
      error: `Element with selector "${nodeSelector.selector}" not found`
    };
  }

  const item = { item: itemElement, pseudoEl: nodeSelector.pseudoEl };

  const data = getV({
    node: item,
    families,
    defaultFamily
  });

  return createData({ data });
};

// For development
// window.isDev = true;

export { getSubMenuDropdown };
