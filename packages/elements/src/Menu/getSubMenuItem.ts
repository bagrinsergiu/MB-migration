import { getModel } from "./utils/getModel";
import {
  Families,
  MenuItemElement,
  MenuItemEntry,
  Output
} from "elements/src/types/type";
import { createData } from "elements/src/utils/getData";
import { prefixed } from "utils/src/models/prefixed";

interface MenuItemData {
  item: MenuItemElement;
  itemBg: MenuItemElement;
  families: Families;
  defaultFamily: string;
}

const getV = (entry: MenuItemData) => {
  const { item, itemBg, families, defaultFamily } = entry;

  const model = {
    "font-family": undefined,
    "font-family-type": "uploaded",
    "font-weight": undefined,
    "font-size": undefined,
    "line-height": undefined,
    "letter-spacing": undefined,
    "font-style": "",
    "color-hex": undefined,
    "color-opacity": undefined,
    "color-palette": "",
    italic: false
  };

  const v = getModel({
    node: item,
    modelDefaults: model,
    families: families,
    defaultFamily: defaultFamily
  });

  const bgModel = {
    "bg-color-hex": undefined,
    "bg-color-opacity": undefined,
    "bg-color-palette": ""
  };
  const bgV = getModel({
    node: itemBg,
    modelDefaults: bgModel,
    families: families,
    defaultFamily: defaultFamily
  });

  return { ...prefixed(v, "subMenu"), ...prefixed(bgV, "subMenu") };
};

const getHoverV = (entry: MenuItemData) => {
  const { item, itemBg, families, defaultFamily } = entry;

  const model = {
    "color-hex": undefined,
    "color-opacity": undefined,
    "color-palette": ""
  };

  const v = getModel({
    node: item,
    modelDefaults: model,
    families: families,
    defaultFamily: defaultFamily
  });

  const bgModel = {
    "bg-color-hex": undefined,
    "bg-color-opacity": 1,
    "bg-color-palette": ""
  };

  const bgV = getModel({
    node: itemBg,
    modelDefaults: bgModel,
    families: families,
    defaultFamily: defaultFamily
  });
  return {
    ...prefixed(v, "hoverSubMenu"),
    ...prefixed(v, "activeSubMenu"),
    ...prefixed(bgV, "hoverSubMenu")
  };
};

const getSubMenuItem = (entry: MenuItemEntry): Output => {
  const { itemSelector, itemBgSelector, hover, families, defaultFamily } =
    entry;
  const itemElement = document.querySelector(itemSelector.selector);
  const itemBgElement = document.querySelector(itemBgSelector.selector);

  if (!itemElement) {
    return {
      error: `Element with selector "${itemSelector}" not found`
    };
  }
  if (!itemBgElement) {
    return {
      error: `Element with selector "${itemBgSelector}" not found`
    };
  }

  const item = { item: itemElement, pseudoEl: itemSelector.pseudoEl };
  const itemBg = { item: itemBgElement, pseudoEl: itemBgSelector.pseudoEl };

  let data = {};
  if (!hover) data = getV({ item, itemBg, families, defaultFamily });
  else data = getHoverV({ item, itemBg, families, defaultFamily });

  return createData({ data });
};

// For development
// window.isDev = true;

export { getSubMenuItem };
