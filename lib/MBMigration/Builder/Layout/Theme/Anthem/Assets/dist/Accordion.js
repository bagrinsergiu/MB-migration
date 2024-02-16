"use strict";
var output = (() => {
  var __defProp = Object.defineProperty;
  var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
  var __getOwnPropNames = Object.getOwnPropertyNames;
  var __hasOwnProp = Object.prototype.hasOwnProperty;
  var __export = (target, all) => {
    for (var name in all)
      __defProp(target, name, { get: all[name], enumerable: true });
  };
  var __copyProps = (to, from, except, desc) => {
    if (from && typeof from === "object" || typeof from === "function") {
      for (let key of __getOwnPropNames(from))
        if (!__hasOwnProp.call(to, key) && key !== except)
          __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
    }
    return to;
  };
  var __toCommonJS = (mod) => __copyProps(__defProp({}, "__esModule", { value: true }), mod);

  // src/Accordion/index.ts
  var Accordion_exports = {};
  __export(Accordion_exports, {
    default: () => Accordion_default
  });

  // ../../../../../../../packages/utils/src/color/parseColorString.ts
  var hexRegex = /^#(?:[A-Fa-f0-9]{3}){1,2}$/;
  var rgbRegex = /^rgb\s*[(]\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*[)]$/;
  var rgbaRegex = /^rgba\s*[(]\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(0*(?:\.\d+)?|1(?:\.0*)?)\s*[)]$/;
  var isHex = (v2) => hexRegex.test(v2);
  var fromRgb = (rgb) => {
    return "#" + ("0" + rgb[0].toString(16)).slice(-2) + ("0" + rgb[1].toString(16)).slice(-2) + ("0" + rgb[2].toString(16)).slice(-2);
  };
  function parseRgb(color) {
    const matches = rgbRegex.exec(color);
    if (matches) {
      const [r, g, b] = matches.slice(1).map(Number);
      return [r, g, b];
    }
    return void 0;
  }
  function parseRgba(color) {
    const matches = rgbaRegex.exec(color);
    if (matches) {
      const [r, g, b, a] = matches.slice(1).map(Number);
      return [r, g, b, a];
    }
    return void 0;
  }
  function parseColorString(colorString) {
    if (isHex(colorString)) {
      return {
        hex: colorString
      };
    }
    const rgbResult = parseRgb(colorString);
    if (rgbResult) {
      return {
        hex: fromRgb(rgbResult)
      };
    }
    const rgbaResult = parseRgba(colorString);
    if (rgbaResult) {
      const [r, g, b, a] = rgbaResult;
      return {
        hex: fromRgb([r, g, b]),
        opacity: String(a)
      };
    }
    return void 0;
  }

  // ../../../../../../../packages/utils/src/dom/getNodeStyle.ts
  var getNodeStyle = (node) => {
    const computedStyles = window.getComputedStyle(node);
    const styles = {};
    Object.values(computedStyles).forEach((key) => {
      styles[key] = computedStyles.getPropertyValue(key);
    });
    return styles;
  };

  // ../../../../../../../packages/utils/src/text/capitalize.ts
  var capitalize = (str) => {
    return str.charAt(0).toUpperCase() + str.slice(1);
  };

  // ../../../../../../../packages/utils/src/text/toCamelCase.ts
  var toCamelCase = (key) => {
    const parts = key.split("-");
    for (let i = 1; i < parts.length; i++) {
      parts[i] = capitalize(parts[i]);
    }
    return parts.join("");
  };

  // ../../../../../../../packages/elements/src/Accordion/utils/getModel.ts
  var v = {
    "font-family": void 0,
    "font-family-type": "uploaded",
    "font-weight": void 0,
    "font-size": void 0,
    "line-height": void 0,
    "letter-spacing": void 0,
    "font-style": "",
    colorHex: void 0,
    colorOpacity: 1
  };
  var getModel = (data2) => {
    const { node, families, defaultFamily } = data2;
    const styles = getNodeStyle(node);
    const dic = {};
    Object.keys(v).forEach((key) => {
      switch (key) {
        case "font-family": {
          const value = `${styles[key]}`;
          const fontFamily = value.replace(/['"\,]/g, "").replace(/\s/g, "_").toLocaleLowerCase();
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
            dic[toCamelCase(key)] = 1;
          } else {
            dic[toCamelCase(key)] = value;
          }
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
            const letterSpacingValue = `${value}`.replace(/px/g, "").trim();
            dic[toCamelCase(key)] = +letterSpacingValue;
          }
          break;
        }
        case "colorHex": {
          const toHex = parseColorString(`${styles["color"]}`);
          dic[toCamelCase(key)] = toHex?.hex ?? "#000000";
          break;
        }
        case "colorOpacity": {
          const toHex = parseColorString(`${styles["color"]}`);
          const opacity = isNaN(+styles.opacity) ? 1 : styles.opacity;
          dic[toCamelCase(key)] = +(toHex?.opacity ?? opacity);
          break;
        }
        default: {
          dic[toCamelCase(key)] = styles[key];
        }
      }
    });
    return dic;
  };

  // ../../../../../../../packages/elements/src/utils/getData.ts
  var getData = () => {
    try {
      return window.isDev ? {
        selector: `[data-id='${window.elementId}']`,
        families: {
          "proxima_nova_proxima_nova_regular_sans-serif": "uid1111",
          "helvetica_neue_helveticaneue_helvetica_arial_sans-serif": "uid2222"
        },
        defaultFamily: "lato"
      } : {
        selector: SELECTOR,
        families: FAMILIES,
        defaultFamily: DEFAULT_FAMILY
      };
    } catch (e) {
      const familyMock = {
        lato: "uid_for_lato",
        roboto: "uid_for_roboto"
      };
      const mock = {
        selector: ".my-div",
        families: familyMock,
        defaultFamily: "lato"
      };
      throw new Error(
        JSON.stringify({
          error: `Invalid JSON ${e}`,
          details: `Must be: ${JSON.stringify(mock)}`
        })
      );
    }
  };
  var createData = (output2) => {
    return output2;
  };

  // ../../../../../../../packages/utils/src/reader/number.ts
  var read = (v2) => {
    switch (typeof v2) {
      case "string": {
        const v_ = v2 !== "" ? Number(v2) : NaN;
        return isNaN(v_) ? void 0 : v_;
      }
      case "number":
        return isNaN(v2) ? void 0 : v2;
      default:
        return void 0;
    }
  };
  var readInt = (v2) => {
    if (typeof v2 === "string") {
      return parseInt(v2);
    }
    return read(v2);
  };

  // ../../../../../../../packages/elements/src/Accordion/index.ts
  var warns = {};
  var getAccordionV = (data2) => {
    const { list, selector } = data2;
    const li = list.children[0];
    let v2 = {};
    if (!li) {
      warns["accordion li"] = {
        message: `Accordion don't have ul > li in ${selector}`
      };
      return v2;
    }
    const title = li.querySelector(".accordion-title");
    if (!title) {
      warns["menu li title"] = {
        message: `Accordion don't have ul > li > .accordion-title in ${selector}`
      };
      return v2;
    }
    const computedStyles = window.getComputedStyle(title, "::after");
    const fontSize = computedStyles.getPropertyValue("font-size");
    const content = computedStyles.getPropertyValue("content");
    const hasIcon = fontSize && content;
    v2 = getModel({
      node: title,
      families: data2.families,
      defaultFamily: data2.defaultFamily
    });
    return {
      ...v2,
      ...hasIcon && {
        navIcon: "thin",
        navIconSize: "custom",
        navIconCustomSize: Math.round(readInt(fontSize) ?? 12)
      }
    };
  };
  var getAccordion = (entry) => {
    const { selector, families, defaultFamily } = entry;
    const node = document.querySelector(selector);
    if (!node) {
      return {
        error: `Element with selector ${entry.selector} not found`
      };
    }
    const list = node.querySelector(".accordion-list");
    if (!list) {
      return {
        error: `Element with selector ${entry.selector} has no accordion list`
      };
    }
    const data2 = getAccordionV({ list, selector, families, defaultFamily });
    return createData({ data: data2 });
  };

  // src/Accordion/index.ts
  var data = getData();
  var output = getAccordion(data);
  var Accordion_default = output;
  return __toCommonJS(Accordion_exports);
})();
