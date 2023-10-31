import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { toCamelCase } from "utils/src/text/toCamelCase";

interface Model {
  node: HTMLElement;
  families: Record<string, string>;
  defaultFamily: string;
}

const v = {
  "font-family": undefined,
  "font-family-type": "uploaded",
  "font-weight": undefined,
  "font-size": undefined,
  "line-height": undefined,
  "letter-spacing": undefined,
  colorHex: undefined,
  colorOpacity: 1,
  activeColorHex: undefined,
  activeColorOpacity: undefined
};

export const getModel = (data: Model) => {
  const { node, families, defaultFamily } = data;
  const styles = getNodeStyle(node);
  const dic: Record<string, string | number> = {};

  Object.keys(v).forEach((key) => {
    switch (key) {
      case "font-family": {
        const value: string = styles[key];
        const fontFamily = value
          .replace(/['"\,]/g, "")
          .replace(/\s/g, "_")
          .toLocaleLowerCase();

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
      case "line-height": {
        dic[toCamelCase(key)] = 1;
        break;
      }
      case "font-size": {
        dic[toCamelCase(key)] = parseInt(styles[key]);
        break;
      }
      case "letter-spacing": {
        const value = styles[key];
        if (value === "normal") {
          dic[toCamelCase(key)] = 0;
        } else {
          // Remove 'px' and any extra whitespace
          const letterSpacingValue = value.replace(/px/g, "").trim();
          dic[toCamelCase(key)] = +letterSpacingValue;
        }
        break;
      }
      case "colorHex": {
        const toHex = parseColorString(styles["color"]);
        dic[toCamelCase(key)] = toHex?.hex ?? "#000000";
        break;
      }
      case "colorOpacity": {
        break;
      }
      default: {
        dic[toCamelCase(key)] = styles[key];
      }
    }
  });

  return dic;
};
