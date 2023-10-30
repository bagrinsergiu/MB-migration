import { getModel } from "./model/getModel";
import { Entry, Output } from "elements/src/types/type";
import { createData, getData } from "elements/src/utils/getData";
import { parseColorString } from "utils/src/color/parseColorString";
import { prefixed } from "utils/src/models/prefixed";

interface NavData {
  nav: Element;
  header: Element;
  subNav?: Element;
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
}

let warns: Record<string, Record<string, string>> = {};

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

  const link = ul.querySelector<HTMLElement>("li > a");
  if (!link) {
    warns["menu li a"] = {
      message: `Navigation don't have ul > li > a in ${selector}`
    };
    return v;
  }

  v = getModel({
    node: link,
    families: data.families,
    defaultFamily: data.defaultFamily
  });

  return { ...v, itemPadding: 20 };
};

const getSubMenuV = (data: Required<NavData>) => {
  const { subNav: ul, header, selector } = data;

  let v = {};

  const li = ul.querySelector("li");
  if (!li) {
    warns["submenu li"] = {
      message: `Navigation don't have ul > li in ${selector}`
    };
    return v;
  }

  const link = ul.querySelector<HTMLElement>("li > a");
  if (!link) {
    warns["submenu li a"] = {
      message: `Navigation don't have ul > li > a in ${selector}`
    };
    return v;
  }

  const typography = getModel({
    node: link,
    families: data.families,
    defaultFamily: data.defaultFamily
  });
  const submenuTypography = prefixed(typography, "subMenu");
  const baseStyle = window.getComputedStyle(header);
  const bgColor = parseColorString(baseStyle.backgroundColor) ?? { hex: "#ffffff", opacity: 1 };

  return {
    ...submenuTypography,
    subMenuBgColorOpacity: bgColor.opacity,
    subMenuBgColorHex: bgColor.hex
  };
};

const getNavStyles = (data: NavData) => {
  const { subNav } = data;
  let menuV = getMenuV(data);

  if (subNav) {
    const _v = getSubMenuV({ ...data, subNav });
    menuV = { ...menuV, ..._v };
  }

  return menuV;
};

const run = (data: Entry): Output => {
  const node = document.querySelector(data.selector);

  if (!node) {
    return {
      error: `Element with selector ${data.selector} not found`,
    };
  }

  const header = node;

  if (!header) {
    return {
      error: `Element with selector ${data.selector} has no header`,
    }
  }

  const nav = header.querySelector("#main-navigation");

  if (!nav) {
    return {
      error: `Element with selector ${data.selector} has no nav`,
    };
  }

  const subNav = header.querySelector(".sub-navigation") ?? undefined;

  const navData = {
    header,
    nav: nav,
    subNav: subNav,
    selector: data.selector,
    families: data.families,
    defaultFamily: data.defaultFamily
  };

  return createData({ data: getNavStyles(navData), warns });
};

// For development
// window.isDev = true;
const data = getData();
const output = run(data);

export default output;
