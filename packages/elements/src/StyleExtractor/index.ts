import { Output } from "../types/type";
import { createData } from "../utils/getData";
import { Literal } from "utils";

export interface Data {
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
  styleProperties: Array<string>;
}

export const styleExtractor = (entry: Data): Output => {
  const { selector, styleProperties } = entry;
  const data: Record<string, Literal> = {};
  const element = document.querySelector(selector);

  if (!element) {
    return {
      error: `Element with selector ${selector} not found`
    };
  }

  const computedStyles = getComputedStyle(element);

  styleProperties.forEach((styleName) => {
    data[styleName] = computedStyles.getPropertyValue(styleName);
  });

  return createData({ data });
};
