import { getDataByEntry } from "../utils/getDataByEntry";
import { getModel } from "../utils/getModel";
import { model } from "./models";
import { Entry, Output } from "elements/src/types/type";
import { createData } from "elements/src/utils/getData";
import * as Num from "utils/src/reader/number";

interface NavData {
  list: Element;
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
}
const warns: Record<string, Record<string, string>> = {};

const getAccordionV = (data: NavData) => {
  const { list, selector } = data;
  const li = list.children[0];
  let v = {};

  if (!li) {
    warns["accordion li"] = {
      message: `Accordion don't have ul > li in ${selector}`
    };
    return v;
  }
  const title = li.querySelector(".accordion-title");
  if (!title) {
    warns["menu li title"] = {
      message: `Accordion don't have ul > li > .accordion-title in ${selector}`
    };
    return v;
  }

  const computedStyles = window.getComputedStyle(title, "::after");
  const fontSize = computedStyles.getPropertyValue("font-size");
  const content = computedStyles.getPropertyValue("content");
  const hasIcon = fontSize && content;

  v = getModel({
    node: title,
    modelDefaults: model,
    families: data.families,
    defaultFamily: data.defaultFamily
  });

  return {
    ...v,
    ...(hasIcon && {
      navIcon: "thin",
      navIconSize: "custom",
      navIconCustomSize: Math.round(Num.readInt(fontSize) ?? 12)
    })
  };
};

export const getAccordion = (_entry: Entry): Output => {
  const entry = window.isDev ? getDataByEntry(_entry) : _entry;

  const { selector, families, defaultFamily } = entry;

  if (!selector) {
    return {
      error: "Selector not found"
    };
  }

  const node = document.querySelector(selector);

  const list = node?.querySelector(".accordion-list");

  if (!list) {
    return {
      error: `Element with selector ${selector} has no accordion list`
    };
  }

  const data = getAccordionV({ list, selector, families, defaultFamily });

  return createData({ data });
};
