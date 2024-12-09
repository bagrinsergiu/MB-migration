import {
  defaultDesktopNumberLineHeight,
  defaultMobileNumberLineHeight,
  defaultTabletNumberLineHeight
} from "elements/src/Text/utils/common";
import { Literal, MValue } from "utils";
import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { capByPrefix } from "utils/src/text/capByPrefix";
import { toCamelCase } from "utils/src/text/toCamelCase";

interface Model {
  node: Element;
  families: Record<string, string>;
  modelDefaults: Record<string, MValue<Literal | boolean>>;
  defaultFamily: string;
}

export const getModel = (data: Model) => {
  const { node, families, defaultFamily, modelDefaults } = data;
  const styles = getNodeStyle(node);
  const dic: Record<string, Literal | boolean> = {};

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
        dic[toCamelCase(key)] = "";
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
      case "font-size": {
        dic[toCamelCase(key)] = parseInt(`${styles[key]}`);
        break;
      }
      case "letter-spacing": {
        const value = styles[key];
        if (value === "normal") {
          dic[toCamelCase(key)] = 0;
        } else {
          // Remove 'px' and any extra whitespace
          const letterSpacingValue = `${value}`.replace(/px/g, "").trim();
          dic[toCamelCase(key)] = +letterSpacingValue;
        }
        break;
      }
      case "color-hex": {
        const toHex = parseColorString(`${styles["color"]}`);

        dic[toCamelCase(key)] = toHex?.hex ?? "#000000";
        break;
      }
      case "bg-color-hex":
      case "content-bg-color-hex": {
        const toHex = parseColorString(`${styles["background-color"]}`);
        dic[toCamelCase(key)] = toHex?.hex ?? "#ffffff";
        break;
      }
      case "color-opacity": {
        const toHex = parseColorString(`${styles["color"]}`);
        const opacity = isNaN(+styles.opacity) ? 1 : styles.opacity;

        dic[toCamelCase(key)] = +(toHex?.opacity ?? opacity);
        break;
      }
      case "bg-color-opacity":
      case "content-bg-color-opacity": {
        const toHex = parseColorString(`${styles["background-color"]}`);
        const opacity = isNaN(+styles.opacity) ? 1 : styles.opacity;

        dic[toCamelCase(key)] = +(toHex?.opacity ?? opacity);
        break;
      }
      case "uppercase": {
        const value = `${styles["text-transform"]}`;
        const isUppercase = value === "uppercase";

        dic[toCamelCase(key)] = isUppercase;
        break;
      }
      case "border-color-hex": {
        const toHex = parseColorString(`${styles["border-bottom-color"]}`);

        dic[toCamelCase(key)] = toHex?.hex ?? "#000000";
        break;
      }
      case "border-color-opacity": {
        const toHex = parseColorString(`${styles["border-bottom-color"]}`);
        const opacity = isNaN(+styles.opacity) ? 1 : styles.opacity;

        dic[toCamelCase(key)] = +(toHex?.opacity ?? opacity);
        break;
      }
      case "border-width": {
        const borderWidth = `${styles["border-bottom-width"]}`.replace(
          /px/g,
          ""
        );

        dic[toCamelCase(key)] = +(borderWidth ?? 1);
        break;
      }
      default: {
        dic[toCamelCase(key)] = styles[key];
      }
    }
  });

  return dic;
};
