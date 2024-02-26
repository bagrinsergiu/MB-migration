import { Output } from "../types/type";
import { createData } from "../utils/getData";
import { getDataByEntry } from "../utils/getDataByEntry";
import { Literal } from "utils";

export interface Data {
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
  styleProperties: Array<string>;
}

export const styleExtractor = (_entry: Data): Output => {
  const entry = window.isDev ? getDataByEntry(_entry) : _entry;

  const { selector, styleProperties } = entry;

  const data: Record<string, Literal> = {};
  const element = document.querySelector(selector);

  if (!element) {
    return {
      error: `Element with selector ${selector} not found`
    };
  }

  const computedStyles = getComputedStyle(element);

  if (styleProperties)
    styleProperties.forEach((styleName: string) => {
      data[styleName] = computedStyles.getPropertyValue(styleName);
    });

  return createData({ data });
};
