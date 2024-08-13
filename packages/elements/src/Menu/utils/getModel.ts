import { MenuItemElement } from "../../types/type";
import { dicKeyForDevices } from "./dicKeyForDevices";
import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { toCamelCase } from "utils/src/text/toCamelCase";

interface Model {
  node: MenuItemElement;
  modelDefaults: Record<string, string | number | undefined>;
  families: Record<string, string>;
  defaultFamily: string;
}

const pxToEm = (lineHeightValue: string, fontSize: string | number): number => {
  if (!lineHeightValue.includes("px")) return parseInt(lineHeightValue);
  const value = parseInt(lineHeightValue);
  return value / ((value / Number(fontSize)) * value);
};

export const getModel = (data: Model) => {
  const { node, modelDefaults, families, defaultFamily } = data;
  const styles = getNodeStyle(node.item, node.pseudoEl);
  const dic: Record<string, string | number> = {};

  Object.keys(modelDefaults).forEach((key) => {
    switch (key) {
      case "font-family": {
        const value = `${styles[key]}`;
        const fontFamily = value
          .replace(/['"\,]/g, "") // eslint-disable-line
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
      case "font-style": {
        Object.assign(dic, dicKeyForDevices(key, ""));
        break;
      }
      case "font-weight": {
        Object.assign(dic, dicKeyForDevices(key, parseInt(`${styles[key]}`)));
        break;
      }
      case "item-padding": {
        dic[toCamelCase(key)] = parseInt(`${styles["padding-left"]}`); // we naively assume the padding are equal
        dic["itemPaddingSuffix"] = "px"; // we naively assume the padding are equal
        break;
      }
      case "line-height": {
        const value = pxToEm(String(styles[key]), styles["font-size"]);

        if (isNaN(value)) {
          Object.assign(dic, dicKeyForDevices(key, 1));
        } else {
          Object.assign(dic, dicKeyForDevices(key, value));
        }
        break;
      }
      case "font-size": {
        Object.assign(dic, dicKeyForDevices(key, parseInt(`${styles[key]}`)));
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
      case "hover-color-hex":
      case "color-hex":
      case "active-color-hex": {
        const toHex = parseColorString(`${styles["color"]}`);
        dic[toCamelCase(key)] = toHex?.hex ?? "#ffffff";
        break;
      }
      case "hover-color-opacity":
      case "color-opacity":
      case "active-color-opacity": {
        const toHex = parseColorString(`${styles["color"]}`);
        const opacity = isNaN(+styles.opacity) ? 1 : styles.opacity;

        dic[toCamelCase(key)] = +(toHex?.opacity ?? opacity);
        break;
      }
      case "bg-color-hex":
      case "menu-bg-color-hex": {
        const toHex = parseColorString(`${styles["background-color"]}`);
        dic[toCamelCase(key)] = toHex?.hex ?? "#ffffff";
        break;
      }
      case "m-menu-icon-color-hex":
      case "m-menu-bg-color-hex": {
        const toHex = parseColorString(`${styles["background-color"]}`);
        const value = toHex?.hex ?? "#ffffff";

        Object.assign(dic, dicKeyForDevices(key, value));
        break;
      }
      case "bg-color-opacity":
      case "menu-bg-color-opacity": {
        const toHex = parseColorString(`${styles["background-color"]}`);
        const opacity = isNaN(+styles.opacity) ? 1 : styles.opacity;

        dic[toCamelCase(key)] = +(toHex?.opacity ?? opacity);
        break;
      }
      case "m-menu-icon-color-opacity":
      case "m-menu-bg-color-opacity": {
        const toHex = parseColorString(`${styles["background-color"]}`);
        const opacity = isNaN(+styles.opacity) ? 1 : styles.opacity;
        const value = +(toHex?.opacity ?? opacity);

        Object.assign(dic, dicKeyForDevices(key, value));
        break;
      }
      default: {
        dic[toCamelCase(key)] = styles[key];
      }
    }
  });

  return dic;
};
