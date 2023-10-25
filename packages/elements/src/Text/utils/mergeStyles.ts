import { Literal } from "utils";
import { extractAllElementsStyles } from "utils/src/dom/extractAllElementsStyles";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

export function mergeStyles(element: Element): Record<string, Literal> {
  const elementStyles = getNodeStyle(element);

  // Text-Align are wrong for Inline Elements
  if (elementStyles["display"] === "inline") {
    delete elementStyles["text-align"];
  }

  const innerStyles = extractAllElementsStyles(element);

  return {
    ...elementStyles,
    ...innerStyles,
    "line-height": elementStyles["line-height"]
  };
}
