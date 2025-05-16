import { getModel } from "./utils/getModel";
import { toMenuItemElement } from "./utils/toMenuItemElement";
import {
  Families,
  MenuItemElement,
  MenuItemEntry,
  Output
} from "elements/src/types/type";
import { createData } from "elements/src/utils/getData";
import { dicKeyForDevices } from "utils/src/dicKeyForDevices";
import { prefixed } from "utils/src/models/prefixed";

interface MenuItemData {
  item: MenuItemElement;
  itemBg: MenuItemElement;
  itemPadding: MenuItemElement;
  itemMobileIcon?: MenuItemElement;
  itemMobileNav?: MenuItemElement;
  itemActive?: MenuItemElement;
  families: Families;
  defaultFamily: string;
}

const getV = (entry: MenuItemData) => {
  const {
    item,
    itemBg,
    itemPadding,
    itemMobileIcon,
    itemActive,
    itemMobileNav,
    families,
    defaultFamily
  } = entry;

  const model = {
    "font-family": undefined,
    "font-family-type": "uploaded",
    "font-weight": undefined,
    "font-size": undefined,
    "line-height": undefined,
    "letter-spacing": undefined,
    "font-style": "",
    "color-hex": undefined,
    "color-opacity": 1,
    "color-palette": "",
    italic: false
  };

  const activeModel = {
    "active-color-hex": undefined,
    "active-color-opacity": 1,
    "active-color-palette": ""
  };

  const v = getModel({
    node: item,
    modelDefaults: model,
    families: families,
    defaultFamily: defaultFamily
  });

  const activeV = itemActive
    ? getModel({
        node: itemActive,
        modelDefaults: activeModel,
        families: families,
        defaultFamily: defaultFamily
      })
    : {};

  const mMenu = prefixed(v, "mMenu");
  Object.assign(
    mMenu,
    dicKeyForDevices("m-menu-color-hex", mMenu["mMenuColorHex"])
  );
  Object.assign(
    mMenu,
    dicKeyForDevices("m-menu-color-opacity", mMenu["mMenuColorOpacity"])
  );

  const bgModel = {
    "menu-bg-color-hex": undefined,
    "menu-bg-color-opacity": 1,
    "menu-bg-color-palette": ""
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

  const mobileMenuV = {};

  const mobileMenuModel = {
    "m-menu-bg-color-hex": undefined,
    "m-menu-bg-color-opacity": 1,
    "m-menu-bg-color-palette": ""
  };

  if (itemMobileNav) {
    Object.assign(
      mobileMenuV,
      getModel({
        node: itemMobileNav,
        modelDefaults: mobileMenuModel,
        families: families,
        defaultFamily: defaultFamily
      })
    );
  }

  const mobileIconModel = {
    "m-menu-icon-color-hex": undefined,
    "m-menu-icon-color-opacity": undefined,
    "m-menu-icon-color-palette": ""
  };

  if (itemMobileIcon) {
    Object.assign(
      mobileMenuV,
      getModel({
        node: itemMobileIcon,
        modelDefaults: mobileIconModel,
        families: families,
        defaultFamily: defaultFamily
      })
    );
  }

  return {
    ...v,
    ...activeV,
    ...mMenu,
    ...bgV,
    ...paddingV,
    ...mobileMenuV,
  };
};

const getHoverV = (entry: MenuItemData) => {
  const { item, itemBg, families, defaultFamily } = entry;

  const model = {
    "hover-color-hex": undefined,
    "hover-color-opacity": 1,
    "hover-color-palette": ""
  };

  const v = getModel({
    node: item,
    modelDefaults: model,
    families: families,
    defaultFamily: defaultFamily
  });

  const mMenu = prefixed(v, "mMenu");
  const bgModel = {
    "hover-menu-bg-color-hex": undefined,
    "hover-menu-bg-color-opacity": undefined,
    "hover-menu-bg-color-palette": ""
  };

  const bgV = getModel({
    node: itemBg,
    modelDefaults: bgModel,
    families: families,
    defaultFamily: defaultFamily
  });
  return { ...v, ...bgV, ...mMenu };
};

const getMenuItem = (entry: MenuItemEntry): Output => {
  const {
    itemSelector,
    itemActiveSelector,
    itemBgSelector,
    itemPaddingSelector,
    itemMobileBtnSelector,
    itemMobileNavSelector,
    hover,
    families,
    defaultFamily
  } = entry;
  const itemElement = document.querySelector(itemSelector.selector);
  const itemBgElement = document.querySelector(itemBgSelector.selector);
  const itemActiveElement = itemActiveSelector?.selector
    ? document.querySelector(itemActiveSelector?.selector)
    : null;

  const itemPaddingElement = document.querySelector(
    itemPaddingSelector.selector
  );
  let itemMobileBtnElement = null;
  let itemMobileNavElement = null;

  const hasMobileSelectors = itemMobileBtnSelector && itemMobileNavSelector;

  if (hasMobileSelectors) {
    itemMobileBtnElement = document.querySelector(
      itemMobileBtnSelector.selector
    );
    itemMobileNavElement = document.querySelector(
      itemMobileNavSelector.selector
    );
  }

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

  const item = { item: itemElement, pseudoEl: itemSelector.pseudoEl };
  const itemActive = itemActiveElement
    ? {
        item: itemActiveElement,
        pseudoEl: itemActiveSelector?.pseudoEl ?? ""
      }
    : undefined;
  const itemBg = { item: itemBgElement, pseudoEl: itemBgSelector.pseudoEl };
  const itemPadding = {
    item: itemPaddingElement,
    pseudoEl: itemPaddingSelector.pseudoEl
  };
  const itemMobileIcon = toMenuItemElement({
    node: itemMobileBtnElement,
    selector: itemMobileBtnSelector,
    targetSelector: ".mobile-nav-icon > .first"
  });
  const itemMobileNav = toMenuItemElement({
    node: itemMobileNavElement,
    selector: itemMobileNavSelector,
    targetSelector: ".main-navigation"
  });

  let data = {};

  if (!hover) {
    data = getV({
      item,
      itemActive,
      itemBg,
      itemPadding,
      itemMobileIcon,
      itemMobileNav,
      families,
      defaultFamily
    });
  } else {
    data = getHoverV({
      item,
      itemBg,
      itemPadding,
      families,
      defaultFamily
    });
  }

  return createData({ data });
};

// For development
// window.isDev = true;

export { getMenuItem };
