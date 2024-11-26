import { Literal } from "../types";
import { getNodeStyle } from "./getNodeStyle";
import { recursiveGetNodes } from "./recursiveGetNodes";
import { ignoreStyleExtracting } from "elements/src/Text/utils/common";

export function extractAllElementsStyles(
  node: Element
): Record<string, Literal> {
  const nodes = recursiveGetNodes(node);
  return nodes.reduce((acc, element) => {
    const parentElementTag = ignoreStyleExtracting.some(selector => element.closest(selector));

    if (parentElementTag) {
      return acc;
    }

    const styles = getNodeStyle(element);

    // Text-Align are wrong for Inline Elements
    if (styles["display"] === "inline") {
      delete styles["text-align"];
    }

    return { ...acc, ...styles };
  }, {});
}
