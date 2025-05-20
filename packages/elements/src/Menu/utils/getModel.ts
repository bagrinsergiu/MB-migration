import { Families, MenuItemElement } from "../../types/type";
import { getFontFamily } from "../../utils/getFontFamily";
import { Literal, MValue } from "utils";
import { parseColorString } from "utils/src/color/parseColorString";
import { dicKeyForDevices } from "utils/src/dicKeyForDevices";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { toCamelCase } from "utils/src/text/toCamelCase";
import { roundToPrecision } from "../../utils/number";

interface Model {
  node: MenuItemElement;
  modelDefaults: Record<string, MValue<Literal | boolean>>;
  families: Families;
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
  const dic: Record<string, Literal | boolean> = {};

  Object.keys(modelDefaults).forEach((key) => {
    switch (key) {
      case "font-family": {
        const family = getFontFamily(styles, families);

        if (!family) {
          dic[toCamelCase(key)] = defaultFamily;
        } else {
          dic[toCamelCase(key)] = family.name;
        }

        break;
      }
      case "font-family-type": {
        dic[toCamelCase(key)] =
          getFontFamily(styles, families)?.type ?? "upload";
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
        let value = parseInt(`${styles["padding-left"]}`) * 2;

        const childNode = (node.item ?? node.pseudoEl)?.firstElementChild;

        if (childNode) {
          const childStyles = window.getComputedStyle(childNode);
          const childPadding = parseInt(`${childStyles["paddingLeft"]}`) * 2;
          value += childPadding;
        }

        dic[toCamelCase(key)] = value;
        dic["itemPaddingSuffix"] = "px";
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
        Object.assign(dic, dicKeyForDevices(key, roundToPrecision(styles[key], 2)));
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
      case "hover-menu-bg-color-hex":
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
      case "hover-menu-bg-color-opacity":
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
      case "italic": {
        const value = `${styles["font-style"]}`;
        const isItalic = value === "italic";

        dic[toCamelCase(key)] = isItalic;
        break;
      }
      case "menu-border-radius": {
        const topLeft= `${styles["border-start-start-radius"]}`.replace(/px/g, "");
        const topRight = `${styles["border-end-start-radius"]}`.replace(/px/g, "");
        const bottomLeft = `${styles["border-start-end-radius"]}`.replace(/px/g, "");
        const bottomRight = `${styles["border-end-end-radius"]}`.replace(/px/g, "");

        const topLeftValue = parseInt(topLeft);
        const topRightValue = parseInt(topRight);
        const bottomLeftValue = parseInt(bottomLeft);
        const bottomRightValue = parseInt(bottomRight);

        const topLeftKey = "menuBorderTopLeftRadius";
        const topRightKey = "menuBorderTopRightRadius";
        const bottomLeftKey = "menuBorderBottomLeftRadius";
        const bottomRightKey = "menuBorderBottomRightRadius";

        dic[topLeftKey] = topLeftValue;
        dic[topRightKey] = topRightValue;
        dic[bottomLeftKey] = bottomLeftValue;
        dic[bottomRightKey] = bottomRightValue;

        if (
          topLeftValue === topRightValue &&
          bottomLeftValue === bottomRightValue &&
          topLeftValue === bottomLeftValue
        ) {
          dic[toCamelCase(key)] = topLeftValue;
          dic["menuBorderRadiusType"] = "grouped";
        } else {
          dic["menuBorderRadiusType"] = "ungrouped";
        }

        break;
      }
      case "menu-padding": {
        const left = `${styles["padding-left"]}`.replace(/px/g, "");
        const right = `${styles["padding-right"]}`.replace(/px/g, "");
        const top = `${styles["padding-top"]}`.replace(/px/g, "");
        const bottom = `${styles["padding-bottom"]}`.replace(/px/g, "");

        const leftValue = parseInt(left);
        const rightValue = parseInt(right);
        const topValue = parseInt(top);
        const bottomValue = parseInt(bottom);

        const leftKey = "menuPaddingLeft";
        const rightKey = "menuPaddingRight";
        const topKey = "menuPaddingTop";
        const bottomKey = "menuPaddingBottom";

        dic[leftKey] = leftValue;
        dic[rightKey] = rightValue;
        dic[topKey] = topValue;
        dic[bottomKey] = bottomValue;

        if (
          leftValue === rightValue &&
          rightValue === topValue &&
          topValue === bottomValue
        ) {
          dic[toCamelCase(key)] = leftValue;
          dic["menuPaddingType"] = "grouped";
        } else {
          dic["menuPaddingType"] = "ungrouped";
        }

        break;
      }
      default: {
        dic[toCamelCase(key)] = styles[key] ?? modelDefaults[key];
      }
    }
  });

  return dic;
};
