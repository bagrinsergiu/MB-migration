import { Literal } from "../types";
import { getNodeStyle } from "./getNodeStyle";
import { recursiveGetNodes } from "./recursiveGetNodes";
import { ignoreStyleExtracting } from "elements/src/Text/utils/common";

export function extractAllElementsStyles(
  node: Element
): Record<string, Literal> {
  const nodes = recursiveGetNodes(node);
  return nodes.reduce((acc, element) => {
    const parentElementTag = ignoreStyleExtracting.some((selector) =>
      element.closest(selector)
    );

    const styles = getNodeStyle(element);

    // Text-Align are wrong for Inline Elements
    if (styles["display"] === "inline") {
      delete styles["text-align"];
    }

    const innerStyles = parentElementTag
      ? { "font-size": styles["font-size"] }
      : styles;

    return { ...acc, ...innerStyles };
  }, {});
}
