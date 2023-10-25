import { getNodeStyle } from "./getNodeStyle";
import { recursiveGetNodes } from "./recursiveGetNodes";
import { Literal } from "@/types";

export function extractAllElementsStyles(
  node: Element
): Record<string, Literal> {
  const nodes = recursiveGetNodes(node);
  return nodes.reduce((acc, element) => {
    const styles = getNodeStyle(element);

    // Text-Align are wrong for Inline Elements
    if (styles["display"] === "inline") {
      delete styles["text-align"];
    }

    return { ...acc, ...styles };
  }, {});
}
