import { getModel } from "./utils/getModel";
import {
  MenuItemElement,
  MenuItemEntry,
  Output
} from "elements/src/types/type";
import { createData } from "elements/src/utils/getData";
import { prefixed } from "utils/src/models/prefixed";

interface MenuItemData {
  item: MenuItemElement;
  itemBg: MenuItemElement;
  itemPadding: MenuItemElement;
  itemMobile: MenuItemElement;
  families: Record<string, string>;
  defaultFamily: string;
}

const getV = (entry: MenuItemData) => {
  const { item, itemBg, itemPadding, itemMobile, families, defaultFamily } =
    entry;

  const model = {
    "font-family": undefined,
    "font-family-type": "uploaded",
    "font-weight": undefined,
    "font-size": undefined,
    "line-height": undefined,
    "letter-spacing": undefined,
    "font-style": "",
    "color-hex": undefined,
    "color-opacity": 1
  };

  const v = getModel({
    node: item,
    modelDefaults: model,
    families: families,
    defaultFamily: defaultFamily
  });
  const mMenu = prefixed(v, "mMenu");

  const bgModel = {
    "menu-bg-color-hex": undefined,
    "menu-bg-color-opacity": 1
  };

  const bgV = getModel({
    node: itemBg,
    modelDefaults: bgModel,
    families: families,
    defaultFamily: defaultFamily
  });

  const paddingModel = {
    "item-padding": undefined
  };

  const paddingV = getModel({
    node: itemPadding,
    modelDefaults: paddingModel,
    families: families,
    defaultFamily: defaultFamily
  });

  const mobileModel = {
    "m-menu-icon-color-hex": undefined,
    "m-menu-icon-color-opacity": undefined
  };

  const mobileV = getModel({
    node: itemMobile,
    modelDefaults: mobileModel,
    families: families,
    defaultFamily: defaultFamily
  });

  return { ...v, ...mMenu, ...bgV, ...paddingV, ...mobileV };
};

const getHoverV = (entry: MenuItemData) => {
  const { item, itemBg, families, defaultFamily } = entry;

  const model = {
    "hover-color-hex": undefined,
    "hover-color-opacity": undefined
  };

  const v = getModel({
    node: item,
    modelDefaults: model,
    families: families,
    defaultFamily: defaultFamily
  });

  const bgModel = {
    "hover-menu-bg-color-hex": undefined,
    "hover-menu-bg-color-opacity": undefined
  };

  const bgV = getModel({
    node: itemBg,
    modelDefaults: bgModel,
    families: families,
    defaultFamily: defaultFamily
  });
  return { ...v, ...bgV };
};

const getMenuItem = (entry: MenuItemEntry): Output => {
  const {
    itemSelector,
    itemBgSelector,
    itemPaddingSelector,
    itemMobileSelector,
    hover,
    families,
    defaultFamily
  } = entry;
  const itemElement = document.querySelector(itemSelector.selector);
  const itemBgElement = document.querySelector(itemBgSelector.selector);
  const itemPaddingElement = document.querySelector(
    itemPaddingSelector.selector
  );
  const itemMobileElement = document.querySelector(itemMobileSelector.selector);

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
  if (!itemPaddingElement) {
    return {
      error: `Element with selector "${itemPaddingSelector}" not found`
    };
  }
  if (!itemMobileElement) {
    return {
      error: `Element with selector "${itemPaddingSelector}" not found`
    };
  }

  const item = { item: itemElement, pseudoEl: itemSelector.pseudoEl };
  const itemBg = { item: itemBgElement, pseudoEl: itemBgSelector.pseudoEl };
  const itemPadding = {
    item: itemPaddingElement,
    pseudoEl: itemPaddingSelector.pseudoEl
  };

  const _mobileNavButton =
    itemMobileElement.querySelector("#mobile-nav-button") ?? itemMobileElement;
  const itemMobile = {
    item: _mobileNavButton,
    pseudoEl: itemSelector.pseudoEl
  };
  let data = {};

  if (!hover) {
    data = getV({
      item,
      itemBg,
      itemPadding,
      itemMobile,
      families,
      defaultFamily
    });
  } else {
    data = getHoverV({
      item,
      itemBg,
      itemPadding,
      itemMobile,
      families,
      defaultFamily
    });
  }

  return createData({ data });
};

// For development
// window.isDev = true;

export { getMenuItem };
