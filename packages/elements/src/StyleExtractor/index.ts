import { Families, Output } from "../types/type";
import { createData } from "../utils/getData";
import { getDataByEntry } from "../utils/getDataByEntry";
import { Literal } from "utils";

export interface Data {
  selector: string;
  families: Families;
  defaultFamily: string;
  styleProperties: Array<string>;
  urlMap: Record<string, string>;
  attributeNames?: string[];
  pseudoElement?: string;
}

export const styleExtractor = (_entry: Data): Output => {
  const entry = window.isDev ? getDataByEntry(_entry) : _entry;

  const { selector, styleProperties } = entry;
  const pseudoElement = entry.pseudoElement;

  const data: Record<string, Literal> = {};
  const element = selector ? document.querySelector(selector) : undefined;

  if (!element) {
    return {
      error: `Element with selector ${selector} not found`
    };
  }

  const computedStyles = pseudoElement
    ? getComputedStyle(element, pseudoElement)
    : getComputedStyle(element);

  if (styleProperties)
    styleProperties.forEach((styleName: string) => {
      data[styleName] = computedStyles.getPropertyValue(styleName);
    });

  return createData({ data });
};

export const attributesExtractor = (_entry: Data): Output => {
  const entry = window.isDev ? getDataByEntry(_entry) : _entry;

  const { selector, attributeNames = [] } = entry;

  const data: Record<string, string | null> = {};
  const element = selector ? document.querySelector(selector) : undefined;

  if (!element) {
    return {
      error: `Element with selector ${selector} not found`
    };
  }
  attributeNames.forEach((attr: string) => {
    data[attr] = element.getAttribute(attr);
  });
  return createData({ data });
};

export const hasNode = (_entry: Data): Output => {
  const entry = window.isDev ? getDataByEntry(_entry) : _entry;

  const { selector } = entry;

  const data: Record<string, string | null> = {};

  if (!selector) {
    return {
      error: "Selector not found"
    };
  }

  data["hasNode"] = document.querySelector(selector) ? "true" : "false";

  return createData({ data });
};

export const getNodeText = (_entry: Data): Output => {
  const entry = window.isDev ? getDataByEntry(_entry) : _entry;

  const { selector } = entry;

  const data: Record<string, string | null> = {};

  if (!selector) {
    return {
      error: "Selector not found"
    };
  }

  const element = document.querySelector(selector);

  if (element) {
    data["textNode"] = element.textContent;
  }

  return createData({ data });
};
