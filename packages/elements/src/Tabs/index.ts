import { getModel } from "../utils/getModel";
import { Entry, Output } from "elements/src/types/type";
import { createData } from "elements/src/utils/getData";
import { parseColorString } from "utils/src/color/parseColorString";

interface NavData {
  node: Element;
  list: Element;
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
}
const warns: Record<string, Record<string, string>> = {};

const getTabsV = (data: NavData) => {
  const { node, list, selector } = data;
  const tab = list.children[0];
  let v = {};

  if (!tab) {
    warns["tabs tab"] = {
      message: `Tabs don't have .tabs-list > .tab-title in ${selector}`
    };
    return v;
  }

  v = getModel({
    node: tab,
    families: data.families,
    defaultFamily: data.defaultFamily
  });

  const { backgroundColor, opacity } = window.getComputedStyle(node);
  const color = parseColorString(backgroundColor);

  return {
    ...v,
    ...(color && { bgColorHex: color.hex, opacity: color.opacity ?? +opacity }),
    navStyle: "style-3"
  };
};

export const getTabs = (entry: Entry): Output => {
  const { selector, families, defaultFamily } = entry;
  const node = document.querySelector(selector);
  if (!node) {
    return {
      error: `Element with selector ${entry.selector} not found`
    };
  }
  const list = node.querySelector(".tabs-list");
  if (!list) {
    return {
      error: `Element with selector ${entry.selector} has no tab list`
    };
  }
  const data = getTabsV({ node, list, selector, families, defaultFamily });

  return createData({ data });
};
