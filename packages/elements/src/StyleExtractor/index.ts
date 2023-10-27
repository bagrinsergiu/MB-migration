import { Entry, Output } from "@/types/type";
import { createData } from "@/utils/getData";
import { Literal } from "utils";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

export interface Data extends Entry {
  styleProperties: Array<string>;
}

export const styleExtractor = (data: Data): Output => {
  const { selector, styleProperties } = data;
  const styles: Record<string, Literal> = {};
  const element = document.querySelector(selector);

  if (!element) {
    return {
      error: `Element with selector ${selector} not found`
    };
  }

  const computedStyles = getNodeStyle(element);

  styleProperties.forEach((styleName) => {
    styles[styleName] = computedStyles[styleName];
  });

  return createData({ data: styles });
};
