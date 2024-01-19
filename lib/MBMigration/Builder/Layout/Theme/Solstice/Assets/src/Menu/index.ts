import { getGlobalMenuModel } from "../utils/getGlobalMenuModel";
import { getModel } from "./utils/getModel";
import { Entry, Output } from "elements/src/types/type";
import { createData, getData } from "elements/src/utils/getData";
import { parseColorString } from "utils/src/color/parseColorString";
import { prefixed } from "utils/src/models/prefixed";

interface NavData {
  nav: Element;
  subNav?: Element;
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
}
const warns: Record<string, Record<string, string>> = {};

const getMenuV = (data: NavData) => {
  const { nav, selector } = data;
  const ul = nav.children[0];
  let v = {};

  if (!ul) {
    warns["menu"] = {
      message: `Navigation don't have ul in ${selector}`
    };
    return v;
  }

  const li = ul.querySelector("li");
  if (!li) {
    warns["menu li"] = {
      message: `Navigation don't have ul > li in ${selector}`
    };
    return v;
  }

  const link = ul.querySelector("li:not(.selected) > a");
  if (!link) {
    warns["menu li a"] = {
      message: `Navigation don't have ul > li > a in ${selector}`
    };
    return v;
  }

  const span = ul.querySelector("li > a > span");
  const activeLink = ul.querySelector("li.selected > a");
  const styles = window.getComputedStyle(link);
  const itemPadding = parseInt(styles.paddingLeft);

  v = getModel({
    node: link,
    families: data.families,
    defaultFamily: data.defaultFamily
  });

  if (activeLink) {
    const styles = window.getComputedStyle(activeLink);
    const color = parseColorString(styles.color);

    if (color) {
      v = {
        ...v,
        activeColorHex: color.hex,
        activeColorOpacity: color.opacity
      };
    }
  }

  if (span) {
    const styles = window.getComputedStyle(span);
    const paddingTop = parseInt(styles.paddingTop ?? 0);
    const paddingRight = parseInt(styles.paddingRight ?? 0);
    const paddingBottom = parseInt(styles.paddingBottom ?? 0);
    const paddingLeft = parseInt(styles.paddingLeft ?? 0);
    const borderRadius = parseInt(styles.borderRadius ?? 0);

    v = {
      ...v,
      menuBorderRadius: borderRadius,
      menuBorderWidthType: "grouped",
      menuPaddingType: "ungrouped",
      menuPaddingTop: paddingTop,
      menuPaddingRight: paddingRight,
      menuPaddingBottom: paddingBottom,
      menuPaddingLeft: paddingLeft
    };
  }

  return { ...v, itemPadding: isNaN(itemPadding) ? 10 : itemPadding };
};

const getSubMenuV = (data: Required<NavData>) => {
  const { subNav: ul, selector } = data;

  const li = ul.querySelector("li");
  if (!li) {
    warns["submenu li"] = {
      message: `Navigation don't have ul > li in ${selector}`
    };
    return;
  }

  const link = ul.querySelector("li > a");
  if (!link) {
    warns["submenu li a"] = {
      message: `Navigation don't have ul > li > a in ${selector}`
    };
    return;
  }

  const typography = getModel({
    node: link,
    families: data.families,
    defaultFamily: data.defaultFamily
  });
  const submenuTypography = prefixed(typography, "subMenu");
  const baseStyle = window.getComputedStyle(ul);
  const bgColor = parseColorString(baseStyle.backgroundColor);

  return {
    ...submenuTypography,
    ...(bgColor && {
      subMenuBgColorPalette: "",
      subMenuBgColorOpacity: bgColor.opacity,
      subMenuBgColorHex: bgColor.hex
    })
  };
};

const run = (entry: Entry): Output => {
  const { selector, families, defaultFamily } = entry;
  const node = document.querySelector(selector);

  if (!node) {
    return {
      error: `Element with selector ${entry.selector} not found`
    };
  }

  const header = node;

  if (!header) {
    return {
      error: `Element with selector ${entry.selector} has no header`
    };
  }

  const nav = header.querySelector("#main-navigation");

  if (!nav) {
    return {
      error: `Element with selector ${entry.selector} has no nav`
    };
  }

  const subNav = header.querySelector(".sub-navigation") ?? undefined;
  let data = getMenuV({ nav, selector, families, defaultFamily });

  if (subNav) {
    const _v = getSubMenuV({ nav, subNav, selector, families, defaultFamily });
    data = { ...data, ..._v };
  }
  const globalModel = getGlobalMenuModel();
  data = { ...globalModel, ...data };

  return createData({ data: data });
};

// For development
// window.isDev = true;
const data = getData();
const output = run(data);

export default output;
