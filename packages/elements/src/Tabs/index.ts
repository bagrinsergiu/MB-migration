import { getModel } from "../utils/getModel";
import { bgModel, tabModel, textModel } from "./models";
import { Entry, Output } from "elements/src/types/type";
import { createData } from "elements/src/utils/getData";

interface NavData {
  node: Element;
  list: Element;
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
}

const getTabsV = (data: NavData) => {
  const { node, list, selector } = data;
  const tab = list.querySelector(".tab-title");

  if (!tab) {
    return {
      error: `Tabs don't have .tabs-list > .tab-title in ${selector}`
    };
  }

  const textNode = tab.querySelector("span") ?? tab;

  const textV = getModel({
    node: textNode,
    modelDefaults: textModel,
    families: data.families,
    defaultFamily: data.defaultFamily
  });

  const v = getModel({
    node: tab,
    modelDefaults: tabModel,
    families: data.families,
    defaultFamily: data.defaultFamily
  });

  const bgV = getModel({
    node,
    modelDefaults: bgModel,
    families: data.families,
    defaultFamily: data.defaultFamily
  });

  return {
    ...textV,
    ...v,
    ...bgV,
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
