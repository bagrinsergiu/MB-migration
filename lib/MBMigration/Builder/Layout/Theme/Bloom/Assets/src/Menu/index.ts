import { getGlobalMenuModel } from "../utils/getGlobalMenuModel";
import { getModel } from "./model/getModel";
import { MenuEntry, Output } from "elements/src/types/type";
import { createData } from "elements/src/utils/getData";
import { getDataByEntry } from "elements/src/utils/getDataByEntry";
import { parseColorString } from "utils/src/color/parseColorString";
import { prefixed } from "utils/src/models/prefixed";

interface NavData {
  section: Element;
  menuItem: Element;
  subMenuItem?: Element;
  families: Record<string, string>;
  defaultFamily: string;
}

const warns: Record<string, Record<string, string>> = {};

const getMenuV = (data: NavData) => {
  const { menuItem, families, defaultFamily } = data;
  let v = {};

  v = getModel({
    node: menuItem,
    families: families,
    defaultFamily: defaultFamily
  });
  const globalStyle = getGlobalMenuModel();

  return { ...globalStyle, ...v, itemPadding: 20 };
};

const getSubMenuV = (data: Required<NavData>) => {
  const { subMenuItem, families, defaultFamily } = data;

  const typography = getModel({
    node: subMenuItem,
    families: families,
    defaultFamily: defaultFamily
  });

  const subMenuItemParentElement = subMenuItem.parentElement?.parentElement;

  if (subMenuItemParentElement) {
    const baseStyle = window.getComputedStyle(
      subMenuItem.parentElement.parentElement
    );

    const bgColor = parseColorString(baseStyle.backgroundColor) ?? {
      hex: "#ffffff",
      opacity: 1
    };

    const submenuTypography = prefixed(typography, "subMenu");

    return {
      ...submenuTypography,
      subMenuBgColorOpacity: bgColor.opacity,
      subMenuBgColorHex: bgColor.hex
    };
  }
};

const run = (_entry: MenuEntry): Output => {
  const entry = window.isDev ? getDataByEntry(_entry) : _entry;
  const {
    itemSelector,
    subItemSelector,
    sectionSelector,
    families,
    defaultFamily
  } = entry;

  const section = sectionSelector
    ? document.querySelector(sectionSelector)
    : undefined;
  const menuItem = itemSelector
    ? document.querySelector(itemSelector)
    : undefined;
  const subMenuItem = subItemSelector
    ? document.querySelector(subItemSelector)
    : undefined;

  if (!menuItem) {
    return {
      error: `Element with itemSelector: "${itemSelector}" was not found`
    };
  }

  if (!subMenuItem) {
    return {
      error: `Element with subMenuItem: "${subMenuItem}" was not found`
    };
  }

  if (!section) {
    return {
      error: `Element with sectionSelector: "${sectionSelector}" was not found`
    };
  }

  let data = getMenuV({
    section,
    menuItem,
    subMenuItem,
    families,
    defaultFamily
  });

  if (subMenuItem) {
    const _v = getSubMenuV({
      section,
      menuItem,
      subMenuItem,
      families,
      defaultFamily
    });
    data = { ...data, ..._v };
  }

  return createData({ data, warns });
};

// For development
// window.isDev = true;

export { run };
