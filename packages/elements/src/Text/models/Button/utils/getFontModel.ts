import { getFontFamily } from "../../../../utils/getFontFamily";
import { Data } from "../types";
import {
  defaultDesktopNumberLineHeight,
  defaultMobileNumberLineHeight,
  defaultTabletNumberLineHeight,
  iconSelector
} from "elements/src/Text/utils/common";
import { Literal } from "utils";
import { dicKeyForDevices } from "utils/src/dicKeyForDevices";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { recursiveGetNodes } from "utils/src/dom/recursiveGetNodes";
import { capByPrefix } from "utils/src/text/capByPrefix";
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
        if (!families || !defaultFamily) return;

        const family = getFontFamily(styles, families);

        if (!family) {
          dic[toCamelCase(key)] = defaultFamily;
        } else {
          dic[toCamelCase(key)] = family.name;
        }
        break;
      }
      case "font-family-type": {
        if (!families || !defaultFamily) return;

        dic[toCamelCase(key)] =
          getFontFamily(styles, families)?.type ?? "upload";

        break;
      }
      case "font-style": {
        Object.assign(dic, dicKeyForDevices(key, ""));
        break;
      }
      case "font-size": {
        const fontSize = parseInt(`${styles[key]}`);

        if (fontSize) {
          Object.assign(dic, dicKeyForDevices(key, fontSize));
        }
        break;
      }
      case "font-weight": {
        const fontWeight = parseInt(`${styles[key]}`);

        if (fontWeight) {
          Object.assign(dic, dicKeyForDevices(key, fontWeight));
        }
        break;
      }
      case "line-height": {
        dic[toCamelCase(key)] = defaultDesktopNumberLineHeight;
        dic[toCamelCase(capByPrefix("tablet", key))] =
          defaultTabletNumberLineHeight;
        dic[toCamelCase(capByPrefix("mobile", key))] =
          defaultMobileNumberLineHeight;
        break;
      }
      case "letter-spacing": {
        const value = styles[key];

        if (!value) break;

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
