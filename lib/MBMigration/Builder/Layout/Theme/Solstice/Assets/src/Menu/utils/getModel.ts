import { dicKeyForDevices } from "elements/src/Menu/utils/dicKeyForDevices";
import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { toCamelCase } from "utils/src/text/toCamelCase";

interface Model {
  node: Element;
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
  "font-style": "",
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
        const value = parseInt(`${styles[key]}`);
        if (isNaN(value)) {
          Object.assign(dicKeyForDevices(key, 1));
        } else {
          Object.assign(dicKeyForDevices(key, value));
        }
        break;
      }
      case "font-size": {
        Object.assign(dicKeyForDevices(key, parseInt(`${styles[key]}`)));
        break;
      }
      case "letter-spacing": {
        const value = styles[key];
        if (value === "normal") {
          Object.assign(dicKeyForDevices(key, 0));
        } else {
          // Remove 'px' and any extra whitespace
          const letterSpacingValue = `${value}`.replace(/px/g, "").trim();
          Object.assign(dicKeyForDevices(key, +letterSpacingValue));
        }
        break;
      }
      case "colorHex": {
        const toHex = parseColorString(`${styles["color"]}`);
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
