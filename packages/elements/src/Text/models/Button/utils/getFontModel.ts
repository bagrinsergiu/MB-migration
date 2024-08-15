import { iconSelector } from "../../../utils/common";
import { Data } from "../types";
import { Literal } from "utils";
import { dicKeyForDevices } from "utils/src/dicKeyForDevices";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { recursiveGetNodes } from "utils/src/dom/recursiveGetNodes";
import { toCamelCase } from "utils/src/text/toCamelCase";

export function extractAllFontElementsStyles(
  node: Element,
  ignoredNodeSelector: string
): Record<string, Literal> {
  const filteredNode = node.cloneNode(true) as Element;
  node.parentElement?.append(filteredNode);

  const ignoredNodes = Array.from(
    filteredNode.querySelectorAll(ignoredNodeSelector)
  );
  ignoredNodes.forEach((ignoredNode) => ignoredNode.remove());

  const nodes = recursiveGetNodes(filteredNode);

  const nodeStyles = nodes.reduce((acc, element) => {
    const styles = getNodeStyle(element);

    return { ...acc, ...styles };
  }, {});

  filteredNode.remove();
  return nodeStyles;
}

const modelDefaults = {
  "font-family": undefined,
  "font-family-type": "uploaded",
  "font-weight": undefined,
  "font-size": undefined,
  "letter-spacing": undefined,
  "line-height": undefined,
  "font-style": ""
};

export const getFontModel = (
  node: Element,
  defaultFamily?: Data["defaultFamily"],
  families?: Data["families"]
) => {
  const styles = extractAllFontElementsStyles(node, iconSelector);
  const dic: Record<string, string | number> = {};

  Object.keys(modelDefaults).forEach((key) => {
    switch (key) {
      case "font-family": {
        const value = `${styles[key]}`;
        const fontFamily = value
          .replace(/['"\,]/g, "") // eslint-disable-line
          .replace(/\s/g, "_")
          .toLocaleLowerCase();

        if (!families || !defaultFamily) return;

        if (!families[fontFamily]) {
          dic[toCamelCase(key)] = defaultFamily;
        } else {
          dic[toCamelCase(key)] = families[fontFamily];
        }
        break;
      }
      case "font-family-type": {
        dic[toCamelCase(key)] = "upload";
        break;
      }
      case "font-style": {
        Object.assign(dic, dicKeyForDevices(key, ""));
        break;
      }
      case "font-size": {
        Object.assign(dic, dicKeyForDevices(key, parseInt(`${styles[key]}`)));
        break;
      }
      case "font-weight": {
        Object.assign(dic, dicKeyForDevices(key, parseInt(`${styles[key]}`)));
        break;
      }
      case "line-height": {
        const value = parseInt(`${styles[key]}`);
        if (isNaN(value)) {
          Object.assign(dic, dicKeyForDevices(key, 1));
        } else {
          Object.assign(dic, dicKeyForDevices(key, value));
        }
        break;
      }
      case "letter-spacing": {
        const value = styles[key];
        if (value === "normal") {
          Object.assign(dic, dicKeyForDevices(key, 0));
        } else {
          // Remove 'px' and any extra whitespace
          const letterSpacingValue = `${value}`.replace(/px/g, "").trim();
          Object.assign(dic, dicKeyForDevices(key, +letterSpacingValue));
        }
        break;
      }
      default: {
        dic[toCamelCase(key)] = styles[key];
      }
    }
  });

  return dic;
};
