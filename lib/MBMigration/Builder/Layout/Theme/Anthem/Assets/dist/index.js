"use strict";
(() => {
  var __create = Object.create;
  var __defProp = Object.defineProperty;
  var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
  var __getOwnPropNames = Object.getOwnPropertyNames;
  var __getProtoOf = Object.getPrototypeOf;
  var __hasOwnProp = Object.prototype.hasOwnProperty;
  var __esm = (fn, res) => function __init() {
    return fn && (res = (0, fn[__getOwnPropNames(fn)[0]])(fn = 0)), res;
  };
  var __commonJS = (cb, mod) => function __require() {
    return mod || (0, cb[__getOwnPropNames(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
  };
  var __copyProps = (to, from, except, desc) => {
    if (from && typeof from === "object" || typeof from === "function") {
      for (let key of __getOwnPropNames(from))
        if (!__hasOwnProp.call(to, key) && key !== except)
          __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
    }
    return to;
  };
  var __toESM = (mod, isNodeMode, target) => (target = mod != null ? __create(__getProtoOf(mod)) : {}, __copyProps(
    // If the importer is in node compatibility mode or this is not an ESM
    // file that has been converted to a CommonJS file using a Babel-
    // compatible transform (i.e. "__esModule" has not been set), then set
    // "default" to the CommonJS "module.exports" for node compatibility.
    isNodeMode || !mod || !mod.__esModule ? __defProp(target, "default", { value: mod, enumerable: true }) : target,
    mod
  ));

  // ../../../../../../../packages/elements/src/utils/getDataByEntry.ts
  var getDataByEntry;
  var init_getDataByEntry = __esm({
    "../../../../../../../packages/elements/src/utils/getDataByEntry.ts"() {
      "use strict";
      getDataByEntry = (input) => {
        const {
          styleProperties,
          list,
          nav,
          selector,
          itemSelector,
          subItemSelector,
          sectionSelector
        } = input ?? {};
        return window.isDev ? {
          families: {},
          defaultFamily: "lato",
          ...styleProperties ? { styleProperties: [""] } : {},
          ...selector ? { selector: `[data-id="${window.elementId}"]` } : {},
          ...list ? { list: void 0 } : {},
          ...nav ? { nav: void 0 } : {},
          ...itemSelector ? { itemSelector: "" } : {},
          ...subItemSelector ? { subItemSelector: "" } : {},
          ...sectionSelector ? { sectionSelector: "" } : {}
        } : input;
      };
    }
  });

  // ../../../../../../../packages/utils/src/color/parseColorString.ts
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
  var hexRegex, rgbRegex, rgbaRegex, isHex, fromRgb;
  var init_parseColorString = __esm({
    "../../../../../../../packages/utils/src/color/parseColorString.ts"() {
      "use strict";
      hexRegex = /^#(?:[A-Fa-f0-9]{3}){1,2}$/;
      rgbRegex = /^rgb\s*[(]\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*[)]$/;
      rgbaRegex = /^rgba\s*[(]\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(0*(?:\.\d+)?|1(?:\.0*)?)\s*[)]$/;
      isHex = (v4) => hexRegex.test(v4);
      fromRgb = (rgb) => {
        return "#" + ("0" + rgb[0].toString(16)).slice(-2) + ("0" + rgb[1].toString(16)).slice(-2) + ("0" + rgb[2].toString(16)).slice(-2);
      };
    }
  });

  // ../../../../../../../packages/utils/src/dom/getNodeStyle.ts
  var getNodeStyle;
  var init_getNodeStyle = __esm({
    "../../../../../../../packages/utils/src/dom/getNodeStyle.ts"() {
      "use strict";
      getNodeStyle = (node, pseudoEl) => {
        const computedStyles = window.getComputedStyle(node, pseudoEl ?? "");
        const styles = {};
        Object.values(computedStyles).forEach((key) => {
          styles[key] = computedStyles.getPropertyValue(key);
        });
        return styles;
      };
    }
  });

  // ../../../../../../../packages/utils/src/text/capitalize.ts
  var capitalize;
  var init_capitalize = __esm({
    "../../../../../../../packages/utils/src/text/capitalize.ts"() {
      "use strict";
      capitalize = (str) => {
        return str.charAt(0).toUpperCase() + str.slice(1);
      };
    }
  });

  // ../../../../../../../packages/utils/src/text/toCamelCase.ts
  var toCamelCase;
  var init_toCamelCase = __esm({
    "../../../../../../../packages/utils/src/text/toCamelCase.ts"() {
      "use strict";
      init_capitalize();
      toCamelCase = (key) => {
        const parts = key.split("-");
        for (let i = 1; i < parts.length; i++) {
          parts[i] = capitalize(parts[i]);
        }
        return parts.join("");
      };
    }
  });

  // ../../../../../../../packages/elements/src/utils/getModel.ts
  var v, getModel;
  var init_getModel = __esm({
    "../../../../../../../packages/elements/src/utils/getModel.ts"() {
      "use strict";
      init_parseColorString();
      init_getNodeStyle();
      init_toCamelCase();
      v = {
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
      getModel = (data) => {
        const { node, families, defaultFamily } = data;
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
    }
  });

  // ../../../../../../../packages/elements/src/utils/getData.ts
  var createData;
  var init_getData = __esm({
    "../../../../../../../packages/elements/src/utils/getData.ts"() {
      "use strict";
      createData = (output) => {
        return output;
      };
    }
  });

  // ../../../../../../../packages/utils/src/reader/number.ts
  var read, readInt;
  var init_number = __esm({
    "../../../../../../../packages/utils/src/reader/number.ts"() {
      "use strict";
      read = (v4) => {
        switch (typeof v4) {
          case "string": {
            const v_ = v4 !== "" ? Number(v4) : NaN;
            return isNaN(v_) ? void 0 : v_;
          }
          case "number":
            return isNaN(v4) ? void 0 : v4;
          default:
            return void 0;
        }
      };
      readInt = (v4) => {
        if (typeof v4 === "string") {
          return parseInt(v4);
        }
        return read(v4);
      };
    }
  });

  // ../../../../../../../packages/elements/src/Accordion/index.ts
  var warns, getAccordionV, getAccordion;
  var init_Accordion = __esm({
    "../../../../../../../packages/elements/src/Accordion/index.ts"() {
      "use strict";
      init_getDataByEntry();
      init_getModel();
      init_getData();
      init_number();
      warns = {};
      getAccordionV = (data) => {
        const { list, selector } = data;
        const li = list.children[0];
        let v4 = {};
        if (!li) {
          warns["accordion li"] = {
            message: `Accordion don't have ul > li in ${selector}`
          };
          return v4;
        }
        const title = li.querySelector(".accordion-title");
        if (!title) {
          warns["menu li title"] = {
            message: `Accordion don't have ul > li > .accordion-title in ${selector}`
          };
          return v4;
        }
        const computedStyles = window.getComputedStyle(title, "::after");
        const fontSize = computedStyles.getPropertyValue("font-size");
        const content = computedStyles.getPropertyValue("content");
        const hasIcon = fontSize && content;
        v4 = getModel({
          node: title,
          families: data.families,
          defaultFamily: data.defaultFamily
        });
        return {
          ...v4,
          ...hasIcon && {
            navIcon: "thin",
            navIconSize: "custom",
            navIconCustomSize: Math.round(readInt(fontSize) ?? 12)
          }
        };
      };
      getAccordion = (_entry) => {
        const entry = window.isDev ? getDataByEntry(_entry) : _entry;
        const { selector, families, defaultFamily } = entry;
        if (!selector) {
          return {
            error: "Selector not found"
          };
        }
        const node = document.querySelector(selector);
        const list = node?.querySelector(".accordion-list");
        if (!list) {
          return {
            error: `Element with selector ${selector} has no accordion list`
          };
        }
        const data = getAccordionV({ list, selector, families, defaultFamily });
        return createData({ data });
      };
    }
  });

  // src/Accordion/index.ts
  var init_Accordion2 = __esm({
    "src/Accordion/index.ts"() {
      "use strict";
      init_Accordion();
    }
  });

  // src/GlobalMenu/index.ts
  var run;
  var init_GlobalMenu = __esm({
    "src/GlobalMenu/index.ts"() {
      "use strict";
      init_parseColorString();
      init_getNodeStyle();
      run = () => {
        const menuItem = document.querySelector(
          "#main-navigation li:not(.selected) a"
        );
        if (!menuItem) {
          return;
        }
        const styles = getNodeStyle(menuItem);
        const color = parseColorString(`${styles["color"]}`);
        if (color) {
          window.menuModel = {
            hoverColorHex: color.hex,
            hoverColorOpacity: color.opacity ?? 1
          };
        }
      };
    }
  });

  // ../../../../../../../packages/elements/src/Image/index.ts
  var getImage;
  var init_Image = __esm({
    "../../../../../../../packages/elements/src/Image/index.ts"() {
      "use strict";
      init_getData();
      init_getDataByEntry();
      getImage = (_entry) => {
        const entry = window.isDev ? getDataByEntry(_entry) : _entry;
        const { selector } = entry;
        const node = selector ? document.querySelector(selector) : void 0;
        if (!node) {
          return {
            error: `Element with selector ${selector} not found`
          };
        }
        const images = node.querySelectorAll("img");
        const data = [];
        images.forEach((image) => {
          const src = image.src || image.srcset;
          const width = image.width;
          const height = image.height;
          data.push({ src, width, height });
        });
        return createData({ data });
      };
    }
  });

  // src/Image/index.ts
  var init_Image2 = __esm({
    "src/Image/index.ts"() {
      "use strict";
      init_Image();
    }
  });

  // src/utils/getGlobalMenuModel.ts
  var getGlobalMenuModel;
  var init_getGlobalMenuModel = __esm({
    "src/utils/getGlobalMenuModel.ts"() {
      "use strict";
      getGlobalMenuModel = () => {
        return window.menuModel;
      };
    }
  });

  // src/Menu/utils/getModel.ts
  var v2, getModel2;
  var init_getModel2 = __esm({
    "src/Menu/utils/getModel.ts"() {
      "use strict";
      init_parseColorString();
      init_getNodeStyle();
      init_toCamelCase();
      v2 = {
        "font-family": void 0,
        "font-family-type": "uploaded",
        "font-weight": void 0,
        "font-size": void 0,
        "line-height": void 0,
        "letter-spacing": void 0,
        "font-style": "",
        colorHex: void 0,
        colorOpacity: 1,
        activeColorHex: void 0,
        activeColorOpacity: void 0
      };
      getModel2 = (data) => {
        const { node, families, defaultFamily } = data;
        const styles = getNodeStyle(node);
        const dic = {};
        Object.keys(v2).forEach((key) => {
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
              break;
            }
            default: {
              dic[toCamelCase(key)] = styles[key];
            }
          }
        });
        return dic;
      };
    }
  });

  // ../../../../../../../packages/utils/src/models/prefixed.ts
  var prefixed;
  var init_prefixed = __esm({
    "../../../../../../../packages/utils/src/models/prefixed.ts"() {
      "use strict";
      init_capitalize();
      prefixed = (v4, prefix) => {
        return Object.entries(v4).reduce((acc, [key, value]) => {
          let _key = prefix + capitalize(key);
          if (key.startsWith("active")) {
            _key = `active${capitalize(prefix)}${key.replace("active", "")}`;
          }
          return { ...acc, [_key]: value };
        }, {});
      };
    }
  });

  // src/Menu/index.ts
  var warns2, getMenuV, getSubMenuV, run2;
  var init_Menu = __esm({
    "src/Menu/index.ts"() {
      "use strict";
      init_getGlobalMenuModel();
      init_getModel2();
      init_getData();
      init_getDataByEntry();
      init_parseColorString();
      init_prefixed();
      warns2 = {};
      getMenuV = (data) => {
        const { nav, selector } = data;
        const ul = nav.children[0];
        let v4 = {};
        if (!ul) {
          warns2["menu"] = {
            message: `Navigation don't have ul in ${selector}`
          };
          return v4;
        }
        const li = ul.querySelector("li");
        if (!li) {
          warns2["menu li"] = {
            message: `Navigation don't have ul > li in ${selector}`
          };
          return v4;
        }
        const link = ul.querySelector("li > a");
        if (!link) {
          warns2["menu li a"] = {
            message: `Navigation don't have ul > li > a in ${selector}`
          };
          return v4;
        }
        const styles = window.getComputedStyle(li);
        const itemPadding = parseInt(styles.paddingLeft);
        v4 = getModel2({
          node: link,
          families: data.families,
          defaultFamily: data.defaultFamily
        });
        const globalModel = getGlobalMenuModel();
        return {
          ...globalModel,
          ...v4,
          itemPadding: isNaN(itemPadding) ? 10 : itemPadding
        };
      };
      getSubMenuV = (data) => {
        const { subNav, selector } = data;
        const ul = subNav.children[0];
        if (!ul) {
          warns2["submenu"] = {
            message: `Navigation don't have ul in ${selector}`
          };
          return;
        }
        const li = ul.querySelector("li");
        if (!li) {
          warns2["submenu li"] = {
            message: `Navigation don't have ul > li in ${selector}`
          };
          return;
        }
        const link = ul.querySelector("li > a");
        if (!link) {
          warns2["submenu li a"] = {
            message: `Navigation don't have ul > li > a in ${selector}`
          };
          return;
        }
        const typography = getModel2({
          node: link,
          families: data.families,
          defaultFamily: data.defaultFamily
        });
        const submenuTypography = prefixed(typography, "subMenu");
        const baseStyle = window.getComputedStyle(subNav);
        const bgColor = parseColorString(baseStyle.backgroundColor);
        return {
          ...submenuTypography,
          ...bgColor && bgColor.opacity !== "0" && {
            subMenuBgColorOpacity: bgColor.opacity,
            subMenuBgColorHex: bgColor.hex,
            subMenuBgColorPalette: ""
          }
        };
      };
      run2 = (_entry) => {
        const entry = window.isDev ? getDataByEntry(_entry) : _entry;
        const { selector, families, defaultFamily } = entry;
        if (!selector) {
          return {
            error: "Selector not found"
          };
        }
        const header = document.querySelector(selector);
        if (!header) {
          return {
            error: `Element with selector ${selector} has no header`
          };
        }
        const nav = header.querySelector("#main-navigation");
        if (!nav) {
          return {
            error: `Element with selector ${selector} has no nav`
          };
        }
        const subNav = header.querySelector("#selected-sub-navigation") ?? void 0;
        let data = getMenuV({ nav, selector, families, defaultFamily });
        if (subNav) {
          const _v = getSubMenuV({ nav, subNav, selector, families, defaultFamily });
          data = { ...data, ..._v };
        }
        return createData({ data });
      };
    }
  });

  // ../../../../../../../packages/elements/src/StyleExtractor/index.ts
  var styleExtractor;
  var init_StyleExtractor = __esm({
    "../../../../../../../packages/elements/src/StyleExtractor/index.ts"() {
      "use strict";
      init_getData();
      init_getDataByEntry();
      styleExtractor = (_entry) => {
        const entry = window.isDev ? getDataByEntry(_entry) : _entry;
        const { selector, styleProperties } = entry;
        const data = {};
        const element = selector ? document.querySelector(selector) : void 0;
        if (!element) {
          return {
            error: `Element with selector ${selector} not found`
          };
        }
        const computedStyles = getComputedStyle(element);
        if (styleProperties)
          styleProperties.forEach((styleName) => {
            data[styleName] = computedStyles.getPropertyValue(styleName);
          });
        return createData({ data });
      };
    }
  });

  // src/StyleExtractor/index.ts
  var init_StyleExtractor2 = __esm({
    "src/StyleExtractor/index.ts"() {
      "use strict";
      init_StyleExtractor();
    }
  });

  // ../../../../../../../packages/elements/src/Tabs/utils/getModel.ts
  var v3, getModel3;
  var init_getModel3 = __esm({
    "../../../../../../../packages/elements/src/Tabs/utils/getModel.ts"() {
      "use strict";
      init_getModel();
      init_parseColorString();
      init_getNodeStyle();
      init_toCamelCase();
      v3 = {
        borderColorHex: void 0,
        borderColorOpacity: 1,
        borderWidth: 1
      };
      getModel3 = (data) => {
        const { node } = data;
        const styles = getNodeStyle(node);
        const dic = {};
        Object.keys(v3).forEach((key) => {
          switch (key) {
            case "borderColorHex": {
              const toHex = parseColorString(`${styles["border-bottom-color"]}`);
              dic[toCamelCase(key)] = toHex?.hex ?? "#000000";
              break;
            }
            case "borderColorOpacity": {
              const toHex = parseColorString(`${styles["border-bottom-color"]}`);
              const opacity = isNaN(+styles.opacity) ? 1 : styles.opacity;
              dic[toCamelCase(key)] = +(toHex?.opacity ?? opacity);
              break;
            }
            case "borderWidth": {
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
        return { ...getModel(data), ...dic };
      };
    }
  });

  // ../../../../../../../packages/elements/src/Tabs/index.ts
  var warns3, getTabsV, getTabs;
  var init_Tabs = __esm({
    "../../../../../../../packages/elements/src/Tabs/index.ts"() {
      "use strict";
      init_getModel3();
      init_getData();
      init_parseColorString();
      warns3 = {};
      getTabsV = (data) => {
        const { node, list, selector } = data;
        const tab = list.children[0];
        let v4 = {};
        if (!tab) {
          warns3["tabs tab"] = {
            message: `Tabs don't have .tabs-list > .tab-title in ${selector}`
          };
          return v4;
        }
        v4 = getModel3({
          node: tab,
          families: data.families,
          defaultFamily: data.defaultFamily
        });
        const { backgroundColor, opacity } = window.getComputedStyle(node);
        const color = parseColorString(backgroundColor);
        return {
          ...v4,
          ...color && { bgColorHex: color.hex, opacity: color.opacity ?? +opacity },
          navStyle: "style-3"
        };
      };
      getTabs = (entry) => {
        const { selector, families, defaultFamily } = entry;
        const node = document.querySelector(selector);
        if (!node) {
          return {
            error: `Element with selector ${entry.selector} not found`
          };
        }
        const list = node.querySelector(".tabs-list");
        if (!list) {
          return {
            error: `Element with selector ${entry.selector} has no tab list`
          };
        }
        const data = getTabsV({ node, list, selector, families, defaultFamily });
        return createData({ data });
      };
    }
  });

  // src/Tabs/index.ts
  var init_Tabs2 = __esm({
    "src/Tabs/index.ts"() {
      "use strict";
      init_Tabs();
    }
  });

  // ../../../../../../../node_modules/nanoid/index.browser.js
  var random, customRandom, customAlphabet;
  var init_index_browser = __esm({
    "../../../../../../../node_modules/nanoid/index.browser.js"() {
      random = (bytes) => crypto.getRandomValues(new Uint8Array(bytes));
      customRandom = (alphabet2, defaultSize, getRandom) => {
        let mask = (2 << Math.log(alphabet2.length - 1) / Math.LN2) - 1;
        let step = -~(1.6 * mask * defaultSize / alphabet2.length);
        return (size = defaultSize) => {
          let id = "";
          while (true) {
            let bytes = getRandom(step);
            let j = step;
            while (j--) {
              id += alphabet2[bytes[j] & mask] || "";
              if (id.length === size)
                return id;
            }
          }
        };
      };
      customAlphabet = (alphabet2, size = 21) => customRandom(alphabet2, size, random);
    }
  });

  // ../../../../../../../packages/utils/src/uuid.ts
  var alphabet, fullSymbolList, uuid;
  var init_uuid = __esm({
    "../../../../../../../packages/utils/src/uuid.ts"() {
      "use strict";
      init_index_browser();
      alphabet = "abcdefghijklmnopqrstuvwxyz";
      fullSymbolList = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
      uuid = (length = 12) => {
        if (false) {
          return "1";
        }
        return customAlphabet(alphabet, 1)() + customAlphabet(fullSymbolList, length)(length - 1);
      };
    }
  });

  // ../../../../../../../packages/elements/src/Models/Cloneable/index.ts
  var createCloneableModel;
  var init_Cloneable = __esm({
    "../../../../../../../packages/elements/src/Models/Cloneable/index.ts"() {
      "use strict";
      init_uuid();
      createCloneableModel = (data) => {
        const { _styles, items, ...value } = data;
        return {
          type: "Cloneable",
          value: { _id: uuid(), _styles, items, ...value }
        };
      };
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/liftA2.js
  var require_liftA2 = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/liftA2.js"(exports) {
      "use strict";
      Object.defineProperty(exports, "__esModule", { value: true });
      exports.liftA2 = void 0;
      function liftA2(fn, f1, f2) {
        return function(a, b) {
          return fn(f1(a), f2(b));
        };
      }
      exports.liftA2 = liftA2;
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/match.js
  var require_match = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/match.js"(exports) {
      "use strict";
      Object.defineProperty(exports, "__esModule", { value: true });
      exports.match = void 0;
      function match() {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          args[_i] = arguments[_i];
        }
        return function(t) {
          for (var i = 0; i < args.length; i++) {
            if (args[i][0](t)) {
              return args[i][1](t);
            }
          }
        };
      }
      exports.match = match;
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/match2.js
  var require_match2 = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/match2.js"(exports) {
      "use strict";
      Object.defineProperty(exports, "__esModule", { value: true });
      exports.match2 = void 0;
      function match2() {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          args[_i] = arguments[_i];
        }
        return function(t, t2) {
          for (var i = 0; i < args.length; i++) {
            if (args[i][0](t) && args[i][1](t2)) {
              return args[i][2](t, t2);
            }
          }
        };
      }
      exports.match2 = match2;
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/Nothing.js
  var require_Nothing = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/Nothing.js"(exports) {
      "use strict";
      Object.defineProperty(exports, "__esModule", { value: true });
      exports.orElse = exports.isT = exports.isNothing = void 0;
      var isNothing = function(v4) {
        return v4 === null || v4 === void 0;
      };
      exports.isNothing = isNothing;
      var isT = function(t) {
        return !(0, exports.isNothing)(t);
      };
      exports.isT = isT;
      function orElse() {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          args[_i] = arguments[_i];
        }
        return args.length === 1 ? function(v4) {
          return (0, exports.isNothing)(v4) ? args[0] : v4;
        } : (0, exports.isNothing)(args[1]) ? args[0] : args[1];
      }
      exports.orElse = orElse;
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/mPipe.js
  var require_mPipe = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/mPipe.js"(exports) {
      "use strict";
      Object.defineProperty(exports, "__esModule", { value: true });
      exports.mPipe = void 0;
      var Nothing_1 = require_Nothing();
      function mPipe4() {
        var _a = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          _a[_i] = arguments[_i];
        }
        var h = _a[0], fns = _a.slice(1);
        return function() {
          var _a2;
          var args = [];
          for (var _i2 = 0; _i2 < arguments.length; _i2++) {
            args[_i2] = arguments[_i2];
          }
          return args.every(Nothing_1.isT) ? (_a2 = fns.reduce(function(v4, fn) {
            return (0, Nothing_1.isT)(v4) ? fn(v4) : void 0;
          }, h.apply(void 0, args))) !== null && _a2 !== void 0 ? _a2 : void 0 : void 0;
        };
      }
      exports.mPipe = mPipe4;
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/pass.js
  var require_pass = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/pass.js"(exports) {
      "use strict";
      Object.defineProperty(exports, "__esModule", { value: true });
      exports.pass = void 0;
      function pass(predicate) {
        return function(t) {
          return predicate(t) ? t : void 0;
        };
      }
      exports.pass = pass;
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/parsers/internals.js
  var require_internals = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/parsers/internals.js"(exports) {
      "use strict";
      Object.defineProperty(exports, "__esModule", { value: true });
      exports._parse = exports.call = exports.isOptional = void 0;
      var Nothing_1 = require_Nothing();
      var isOptional = function(v4) {
        return v4.__type === "optional";
      };
      exports.isOptional = isOptional;
      var call = function(p, v4) {
        switch (p.__type) {
          case "optional":
          case "strict":
            return p.fn(v4);
          default:
            return p(v4);
        }
      };
      exports.call = call;
      function _parse(parsers, object) {
        var b = {};
        for (var p in parsers) {
          if (!Object.prototype.hasOwnProperty.call(parsers, p)) {
            continue;
          }
          var v4 = (0, exports.call)(parsers[p], object);
          if (!(0, exports.isOptional)(parsers[p]) && (0, Nothing_1.isNothing)(v4)) {
            return void 0;
          }
          b[p] = v4;
        }
        return b;
      }
      exports._parse = _parse;
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/parsers/parse.js
  var require_parse = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/parsers/parse.js"(exports) {
      "use strict";
      Object.defineProperty(exports, "__esModule", { value: true });
      exports.parse = exports.optional = void 0;
      var internals_1 = require_internals();
      var optional = function(p) {
        return {
          __type: "optional",
          fn: p
        };
      };
      exports.optional = optional;
      function parse(parsers, object) {
        return object === void 0 ? function(o) {
          return (0, internals_1._parse)(parsers, o);
        } : (0, internals_1._parse)(parsers, object);
      }
      exports.parse = parse;
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/parsers/parseStrict.js
  var require_parseStrict = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/parsers/parseStrict.js"(exports) {
      "use strict";
      Object.defineProperty(exports, "__esModule", { value: true });
      exports.parseStrict = void 0;
      var internals_1 = require_internals();
      function parseStrict(parsers, object) {
        return object === void 0 ? function(o) {
          return (0, internals_1._parse)(parsers, o);
        } : (0, internals_1._parse)(parsers, object);
      }
      exports.parseStrict = parseStrict;
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/or.js
  var require_or = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/or.js"(exports) {
      "use strict";
      var __spreadArray = exports && exports.__spreadArray || function(to, from, pack) {
        if (pack || arguments.length === 2)
          for (var i = 0, l = from.length, ar; i < l; i++) {
            if (ar || !(i in from)) {
              if (!ar)
                ar = Array.prototype.slice.call(from, 0, i);
              ar[i] = from[i];
            }
          }
        return to.concat(ar || Array.prototype.slice.call(from));
      };
      Object.defineProperty(exports, "__esModule", { value: true });
      exports.or = void 0;
      var Nothing_1 = require_Nothing();
      function or() {
        var fns = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          fns[_i] = arguments[_i];
        }
        return function() {
          var _a;
          var args = [];
          for (var _i2 = 0; _i2 < arguments.length; _i2++) {
            args[_i2] = arguments[_i2];
          }
          for (var i = 0; i <= fns.length; i++) {
            var v4 = (_a = fns[i]) === null || _a === void 0 ? void 0 : _a.call.apply(_a, __spreadArray([fns], args, false));
            if (!(0, Nothing_1.isNothing)(v4)) {
              return v4;
            }
          }
        };
      }
      exports.or = or;
    }
  });

  // ../../../../../../../node_modules/fp-utilities/dist/index.js
  var require_dist = __commonJS({
    "../../../../../../../node_modules/fp-utilities/dist/index.js"(exports) {
      "use strict";
      var __createBinding = exports && exports.__createBinding || (Object.create ? function(o, m, k, k2) {
        if (k2 === void 0)
          k2 = k;
        var desc = Object.getOwnPropertyDescriptor(m, k);
        if (!desc || ("get" in desc ? !m.__esModule : desc.writable || desc.configurable)) {
          desc = { enumerable: true, get: function() {
            return m[k];
          } };
        }
        Object.defineProperty(o, k2, desc);
      } : function(o, m, k, k2) {
        if (k2 === void 0)
          k2 = k;
        o[k2] = m[k];
      });
      var __exportStar = exports && exports.__exportStar || function(m, exports2) {
        for (var p in m)
          if (p !== "default" && !Object.prototype.hasOwnProperty.call(exports2, p))
            __createBinding(exports2, m, p);
      };
      Object.defineProperty(exports, "__esModule", { value: true });
      __exportStar(require_liftA2(), exports);
      __exportStar(require_match(), exports);
      __exportStar(require_match2(), exports);
      __exportStar(require_mPipe(), exports);
      __exportStar(require_Nothing(), exports);
      __exportStar(require_pass(), exports);
      __exportStar(require_parse(), exports);
      __exportStar(require_parseStrict(), exports);
      __exportStar(require_or(), exports);
    }
  });

  // ../../../../../../../packages/utils/src/reader/object.ts
  var hasKey, readKey;
  var init_object = __esm({
    "../../../../../../../packages/utils/src/reader/object.ts"() {
      "use strict";
      hasKey = (key, obj) => key in obj;
      readKey = (key) => (
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        (obj) => hasKey(key, obj) ? obj[key] : void 0
      );
    }
  });

  // ../../../../../../../packages/utils/src/reader/string.ts
  var read2;
  var init_string = __esm({
    "../../../../../../../packages/utils/src/reader/string.ts"() {
      "use strict";
      read2 = (v4) => {
        switch (typeof v4) {
          case "string":
            return v4;
          case "number":
            return isNaN(v4) ? void 0 : v4.toString();
          default:
            return void 0;
        }
      };
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/common/index.ts
  function shouldExtractElement(element, exceptions) {
    const isAllowed = allowedTags.includes(element.tagName);
    if (isAllowed && exceptions) {
      return !exceptions.includes(element.tagName);
    }
    return isAllowed;
  }
  var import_fp_utilities, allowedTags, exceptExtractingStyle, defaultTabletLineHeight, defaultMobileLineHeight, extractedAttributes, textAlign, iconSelector, buttonSelector, embedSelector, extractUrlWithoutDomain, getHref, getTarget, normalizeOpacity;
  var init_common = __esm({
    "../../../../../../../packages/elements/src/Text/utils/common/index.ts"() {
      "use strict";
      import_fp_utilities = __toESM(require_dist());
      init_object();
      init_string();
      allowedTags = [
        "P",
        "H1",
        "H2",
        "H3",
        "H4",
        "H5",
        "H6",
        "UL",
        "OL",
        "LI"
      ];
      exceptExtractingStyle = ["UL", "OL"];
      defaultTabletLineHeight = "1_2";
      defaultMobileLineHeight = "1_2";
      extractedAttributes = [
        "font-size",
        "font-family",
        "font-weight",
        "font-style",
        "line-height",
        "text-align",
        "letter-spacing",
        "text-transform"
      ];
      textAlign = {
        "-webkit-center": "center",
        "-moz-center": "center",
        start: "left",
        end: "right",
        left: "left",
        right: "right",
        center: "center",
        justify: "justify"
      };
      iconSelector = `[data-socialicon],[style*="font-family: 'Mono Social Icons Font'"],[data-icon]`;
      buttonSelector = ".sites-button:not(.nav-menu-button)";
      embedSelector = ".embedded-paste";
      extractUrlWithoutDomain = (url) => {
        const urlObject = new URL(url);
        const _url = urlObject.origin === window.location.origin ? urlObject.pathname : urlObject.href;
        return _url;
      };
      getHref = (0, import_fp_utilities.mPipe)(
        readKey("href"),
        read2,
        extractUrlWithoutDomain
      );
      getTarget = (0, import_fp_utilities.mPipe)(readKey("target"), read2);
      normalizeOpacity = (color) => {
        const { hex, opacity } = color;
        return {
          hex,
          opacity: hex === "#ffffff" && opacity === "1" ? "0.99" : opacity
        };
      };
    }
  });

  // ../../../../../../../packages/elements/src/utils/getGlobalButtonModel.ts
  var getGlobalButtonModel;
  var init_getGlobalButtonModel = __esm({
    "../../../../../../../packages/elements/src/utils/getGlobalButtonModel.ts"() {
      "use strict";
      getGlobalButtonModel = () => {
        return window.buttonModel;
      };
    }
  });

  // ../../../../../../../packages/elements/src/utils/getGlobalIconModel.ts
  var getGlobalIconModel;
  var init_getGlobalIconModel = __esm({
    "../../../../../../../packages/elements/src/utils/getGlobalIconModel.ts"() {
      "use strict";
      getGlobalIconModel = () => {
        return window.iconModel;
      };
    }
  });

  // ../../../../../../../packages/utils/src/dom/getParentElementOfTextNode.ts
  function getParentElementOfTextNode(node) {
    if (node.nodeType === Node.TEXT_NODE) {
      return node.parentNode ?? void 0;
    }
    return Array.from(node.childNodes).find(
      (node2) => getParentElementOfTextNode(node2)
    );
  }
  var init_getParentElementOfTextNode = __esm({
    "../../../../../../../packages/utils/src/dom/getParentElementOfTextNode.ts"() {
      "use strict";
    }
  });

  // ../../../../../../../packages/elements/src/Text/models/Icon/utils/getModel.ts
  function getModel4(node) {
    const parentNode = getParentElementOfTextNode(node);
    const isIconText = parentNode?.nodeName === "#text";
    const iconNode = isIconText ? node : parentNode;
    const parentElement = node.parentElement;
    const isLink = parentElement?.tagName === "A" || node.tagName === "A";
    const parentHref = getHref(parentElement) ?? getHref(node) ?? "";
    const modelStyle = getStyleModel(node);
    const iconCode = iconNode?.textContent?.charCodeAt(0);
    const globalModel = getGlobalIconModel();
    return {
      type: "Icon",
      value: {
        _id: uuid(),
        _styles: ["icon"],
        ...globalModel,
        ...modelStyle,
        customSize: 26,
        padding: 7,
        name: iconCode ? codeToBuilderMap[iconCode] ?? defaultIcon : defaultIcon,
        type: iconCode ? "fa" : "glyph",
        ...isLink && {
          linkExternal: parentHref,
          linkType: "external",
          linkExternalBlank: "on"
        }
      }
    };
  }
  var import_fp_utilities2, defaultIcon, codeToBuilderMap, getColor, getBgColor, getStyles, getParentStyles, getStyleModel;
  var init_getModel4 = __esm({
    "../../../../../../../packages/elements/src/Text/models/Icon/utils/getModel.ts"() {
      "use strict";
      init_getGlobalIconModel();
      init_common();
      import_fp_utilities2 = __toESM(require_dist());
      init_parseColorString();
      init_getNodeStyle();
      init_getParentElementOfTextNode();
      init_object();
      init_string();
      init_uuid();
      defaultIcon = "favourite-31";
      codeToBuilderMap = {
        //#region No icons on our side
        thecity: defaultIcon,
        57680: defaultIcon,
        tableproject: defaultIcon,
        57681: defaultIcon,
        circlefeedburner: defaultIcon,
        57896: defaultIcon,
        circlethecity: defaultIcon,
        58192: defaultIcon,
        circletableproject: defaultIcon,
        58193: defaultIcon,
        roundedblip: defaultIcon,
        58385: defaultIcon,
        roundedfeedburner: defaultIcon,
        58408: defaultIcon,
        roundedthecity: defaultIcon,
        58704: defaultIcon,
        roundedtableproject: defaultIcon,
        58705: defaultIcon,
        //#endregion
        apple: "apple",
        57351: "apple",
        61817: "apple",
        57686: "map-marker-alt",
        mail: "envelope",
        57892: "envelope",
        57380: "envelope",
        57936: "music",
        facebook: "facebook-square",
        57895: "facebook-square",
        58407: "facebook-square",
        61570: "facebook-square",
        61594: "facebook",
        youtube: "youtube",
        58009: "youtube",
        58521: "youtube",
        62513: "youtube",
        vimeo: "vimeo-v",
        57993: "vimeo-v",
        twitter: "twitter",
        57990: "twitter",
        58503: "twitter",
        57991: "twitter",
        instagram: "instagram",
        58624: "instagram",
        58112: "instagram",
        61805: "instagram",
        58211: "arrow-alt-circle-right",
        63244: "user-run",
        addme: "plus",
        57346: "plus",
        appstorealt: "arrow-alt-circle-down",
        57349: "arrow-alt-circle-down",
        appstore: "app-store",
        57350: "app-store",
        blogger: "blogger-b",
        57362: "blogger-b",
        etsy: "etsy",
        57382: "etsy",
        57383: "facebook-f",
        foursquare: "foursquare",
        57394: "foursquare",
        flickr: "flickr",
        57385: "flickr",
        googleplus: "google-plus-g",
        57401: "google-plus-g",
        gowallapin: "map-pin",
        57409: "map-pin",
        heart: "heart",
        57412: "heart",
        61444: "heart",
        imessage: "comment",
        57417: "comment",
        itunes: "itunes",
        57424: "itunes",
        62388: "itunes",
        lastfm: "lastfm",
        57425: "lastfm",
        linkedin: "linkedin-in",
        57426: "linkedin-in",
        meetup: "meetup",
        57427: "meetup",
        myspace: "people-group",
        57433: "people-group",
        paypal: "paypal",
        57441: "paypal",
        61933: "paypal",
        pinterest: "pinterest-p",
        57444: "pinterest-p",
        61650: "pinterest",
        podcast: "podcast",
        57445: "podcast",
        rss: "rss",
        57457: "rss",
        sharethis: "share-alt",
        57459: "share-alt",
        skype: "skype",
        57460: "skype",
        slideshare: "slideshare",
        57462: "slideshare",
        soundcloud: "soundcloud",
        57464: "soundcloud",
        spotify: "spotify",
        57465: "spotify",
        star: "star",
        57474: "star",
        tumblr: "tumblr",
        57477: "tumblr",
        twitterbird: "twitter",
        57478: "twitter",
        57479: "twitter",
        57481: "vimeo-v",
        wordpress: "wordpress",
        57492: "wordpress",
        yelp: "yelp",
        57496: "yelp",
        57497: "youtube",
        57600: "instagram",
        bookmark: "bookmark",
        57682: "bookmark",
        61486: "bookmark",
        euro: "euro-sign",
        57683: "euro-sign",
        pound: "pound-sign",
        57684: "pound-sign",
        cash: "dollar-sign",
        57685: "dollar-sign",
        map: "map-marker-alt",
        62073: "map",
        video: "play",
        57687: "play",
        googleplay: "google-play",
        57696: "google-play",
        cinema: "film",
        57697: "film",
        57703: "film",
        uparrow: "arrow-up",
        57698: "arrow-up",
        rightarrow: "arrow-right",
        57699: "arrow-right",
        leftarrow: "arrow-left",
        57702: "arrow-left",
        downarrow: "arrow-down",
        57704: "arrow-down",
        record: "video",
        57700: "video",
        map2: "map-marked-alt",
        57701: "map-marked-alt",
        circleaddme: "plus-circle",
        57858: "plus-circle",
        circleappstorealt: "arrow-alt-circle-down",
        57861: "arrow-alt-circle-down",
        circleappstore: "app-store",
        57862: "app-store",
        circleapple: "app-store",
        57863: "app-store",
        circleblogger: "blogger-b",
        57874: "blogger-b",
        circleemail: "envelope",
        circleetsy: "etsy",
        57894: "etsy",
        circlefacebook: "facebook-f",
        circleflickr: "flickr",
        57897: "flickr",
        circlefoursquare: "foursquare",
        57906: "foursquare",
        circlegoogleplus: "google-plus",
        57913: "google-plus",
        circlegowallapin: "map-pin",
        57921: "map-pin",
        circleheart: "heart",
        57924: "heart",
        circleimessage: "comment",
        57929: "comment",
        circleitunes: "itunes",
        circlelastfm: "lastfm",
        57937: "lastfm",
        circlelinkedin: "linkedin",
        57938: "linkedin",
        circlemeetup: "meetup",
        57939: "meetup",
        circlemyspace: "people-group",
        57945: "people-group",
        circlepaypal: "paypal",
        57953: "paypal",
        circlepinterest: "pinterest",
        57956: "pinterest",
        circlepodcast: "podcast",
        57957: "podcast",
        circlerss: "rss",
        57969: "rss",
        circlesharethis: "share-alt",
        57971: "share-alt",
        circleskype: "skype",
        57972: "skype",
        circleslideshare: "slideshare",
        57974: "slideshare",
        circlesoundcloud: "soundcloud",
        57976: "soundcloud",
        circlespotify: "spotify",
        57977: "spotify",
        circlestar: "star",
        57986: "star",
        circletumblr: "tumblr",
        57989: "tumblr",
        circletwitterbird: "twitter",
        circlevimeo: "vimeo-v",
        circlewordpress: "wordpress",
        58004: "wordpress",
        circleyelp: "yelp",
        58008: "yelp",
        circleyoutube: "youtube",
        circleinstagram: "instagram",
        circlebookmark: "bookmark",
        58194: "bookmark",
        circleeuro: "euro-sign",
        58195: "euro-sign",
        circlepound: "pound-sign",
        58196: "pound-sign",
        circlecash: "dollar-sign",
        58197: "dollar-sign",
        circlemap: "map-marker-alt",
        58198: "map-marker-alt",
        circlevideo: "play-circle",
        58199: "play-circle",
        circlegoogleplay: "google-play",
        58208: "google-play",
        circlecinema: "film",
        circlefilm: "film",
        58209: "film",
        58215: "film",
        circleuparrow: "arrow-circle-up",
        58210: "arrow-circle-up",
        circlerightarrow: "arrow-circle-right",
        circlerecord: "video",
        58212: "video",
        circlemap2: "map-marked-alt",
        58213: "map-marked-alt",
        circleleftarrow: "arrow-circle-left",
        58214: "arrow-circle-left",
        circledownarrow: "arrow-circle-down",
        58216: "arrow-circle-down",
        roundedaddme: "plus-circle",
        58370: "plus-circle",
        roundedappstorealt: "arrow-circle-down",
        58373: "arrow-circle-down",
        roundedappstore: "app-store",
        58374: "app-store",
        roundedapple: "apple",
        58375: "apple",
        roundedblogger: "blogger",
        58386: "blogger",
        roundedemail: "envelope-square",
        58404: "envelope-square",
        roundedfacebook: "facebook-square",
        roundedflickr: "flickr",
        58409: "flickr",
        roundedfoursquare: "foursquare",
        58418: "foursquare",
        roundedgoogleplus: "google-plus-squar",
        58425: "google-plus-squar",
        roundedgowallapin: "map-pin",
        58433: "map-pin",
        roundedheart: "heart",
        58436: "heart",
        roundedimessage: "comment",
        58441: "comment",
        roundeditunes: "itunes",
        58448: "itunes",
        roundedlastfm: "lastfm",
        58449: "lastfm",
        roundedlinkedin: "linkedin",
        58450: "linkedin",
        roundedmeetup: "meetup",
        58451: "meetup",
        roundedmyspace: "people-group",
        58457: "people-group",
        roundedpaypal: "paypal",
        58465: "paypal",
        roundedpinterest: "pinterest-square",
        58468: "pinterest-square",
        roundedpodcast: "podcast",
        58469: "podcast",
        roundedrss: "rss",
        58481: "rss",
        roundedsharethis: "share-alt",
        58483: "share-alt",
        roundedskype: "skype",
        58484: "skype",
        roundedslideshare: "slideshare",
        58486: "slideshare",
        roundedsoundcloud: "soundcloud",
        58488: "soundcloud",
        roundedspotify: "spotify",
        58489: "spotify",
        roundedstar: "star",
        58498: "star",
        roundedtumblr: "tumblr-square",
        58501: "tumblr-square",
        roundedtwitterbird: "twitter-square",
        58502: "twitter-square",
        roundedtwitter: "twitter-square",
        roundedvimeo: "vimeo-square",
        58505: "vimeo-square",
        roundedwordpress: "wordpress",
        58516: "wordpress",
        roundedyelp: "yelp",
        58520: "yelp",
        roundedyoutube: "youtube-square",
        roundedinstagram: "instagram-square",
        roundedbookmark: "bookmark",
        58706: "bookmark",
        roundedeuro: "euro-sign",
        58707: "euro-sign",
        roundedpound: "pound-sign",
        58708: "pound-sign",
        roundedcash: "dollar-sign",
        58709: "dollar-sign",
        roundedmap: "map-marked-alt",
        58710: "map-marked-alt",
        roundedvideo: "play",
        58711: "play",
        roundedgoogleplay: "google-play",
        58720: "google-play",
        roundedcinema: "film",
        58721: "film",
        roundeduparrow: "arrow-circle-up",
        58722: "arrow-circle-up",
        roundedrightarrow: "arrow-circle-right",
        58723: "arrow-circle-right",
        roundedrecord: "video",
        58724: "video",
        roundedmap2: "map-marked-alt",
        58725: "map-marked-alt",
        roundedleftarrow: "arrow-circle-left",
        58726: "arrow-circle-left",
        roundedfilm: "film",
        58727: "film",
        roundeddownarrow: "arrow-circle-down",
        58728: "arrow-circle-down",
        roundeddiagonalarrow: "external-link-square",
        58729: "external-link-square",
        "address-book": "address-book",
        62137: "address-book",
        "address-card": "address-card",
        62139: "address-card",
        "air-freshener": "air-freshener",
        62928: "air-freshener",
        allergies: "allergies",
        62561: "allergies",
        ambulance: "ambulance",
        61689: "ambulance",
        "american-sign-language-interpreting": "american-sign-language-interpreting",
        62115: "american-sign-language-interpreting",
        anchor: "anchor",
        61757: "anchor",
        ankh: "ankh",
        63044: "ankh",
        "app-store": "app-store",
        62319: "app-store",
        "app-store-ios": "app-store-ios",
        62320: "app-store-ios",
        "apple-alt": "apple-alt",
        62929: "apple-alt",
        "apple-pay": "apple-pay",
        62485: "apple-pay",
        archive: "archive",
        61831: "archive",
        archway: "archway",
        62807: "archway",
        "assistive-listening-systems": "assistive-listening-systems",
        62114: "assistive-listening-systems",
        at: "at",
        61946: "at",
        atlas: "atlas",
        62808: "atlas",
        atom: "atom",
        62930: "atom",
        "audio-description": "audio-description",
        62110: "audio-description",
        award: "award",
        62809: "award",
        baby: "baby",
        63356: "baby",
        "baby-carriage": "baby-carriage",
        63357: "baby-carriage",
        bacon: "bacon",
        63461: "bacon",
        bahai: "bahai",
        63078: "bahai",
        "balance-scale": "balance-scale",
        62030: "balance-scale",
        "balance-scale-left": "balance-scale-left",
        62741: "balance-scale-left",
        "balance-scale-right": "balance-scale-right",
        62742: "balance-scale-right",
        barcode: "barcode",
        61482: "barcode",
        "baseball-ball": "baseball-ball",
        62515: "baseball-ball",
        "basketball-ball": "basketball-ball",
        62516: "basketball-ball",
        bath: "bath",
        62157: "bath",
        bed: "bed",
        62006: "bed",
        beer: "beer",
        61692: "beer",
        bell: "bell",
        61683: "bell",
        bible: "bible",
        63047: "bible",
        bicycle: "bicycle",
        61958: "bicycle",
        biking: "biking",
        63562: "biking",
        binoculars: "binoculars",
        61925: "binoculars",
        "birthday-cake": "birthday-cake",
        61949: "birthday-cake",
        blender: "blender",
        62743: "blender",
        "blender-phone": "blender-phone",
        63158: "blender-phone",
        blind: "blind",
        62109: "blind",
        blog: "blog",
        63361: "blog",
        bolt: "bolt-lightning",
        61671: "bolt-lightning",
        bone: "bone",
        62935: "bone",
        book: "book",
        61485: "book",
        "book-open": "book-open",
        62744: "book-open",
        "book-reader": "book-reader",
        62938: "book-reader",
        "bowling-ball": "bowling-ball",
        62518: "bowling-ball",
        braille: "braille",
        62113: "braille",
        "bread-slice": "bread-slice",
        63468: "bread-slice",
        briefcase: "briefcase",
        61617: "briefcase",
        "broadcast-tower": "broadcast-tower",
        62745: "broadcast-tower",
        broom: "broom",
        62746: "broom",
        bug: "bug",
        61832: "bug",
        building: "building",
        61869: "building",
        bullhorn: "bullhorn",
        61601: "bullhorn",
        bullseye: "bullseye",
        61760: "bullseye",
        burn: "burn",
        62570: "burn",
        bus: "bus-alt",
        61959: "bus-alt",
        "bus-alt": "bus",
        62814: "bus",
        "business-time": "business-time",
        63050: "business-time",
        calculator: "calculator",
        61932: "calculator",
        calendar: "calendar",
        61747: "calendar",
        "calendar-alt": "calendar-alt",
        61555: "calendar-alt",
        camera: "camera",
        61488: "camera",
        "camera-retro": "camera-retro",
        61571: "camera-retro",
        campground: "campground",
        63163: "campground",
        "canadian-maple-leaf": "canadian-maple-leaf",
        63365: "canadian-maple-leaf",
        "candy-cane": "candy-cane",
        63366: "candy-cane",
        car: "car",
        61881: "car",
        "car-alt": "car-alt",
        62942: "car-alt",
        "car-battery": "car-battery",
        62943: "car-battery",
        "car-crash": "car-crash",
        62945: "car-crash",
        "car-side": "car-side",
        62948: "car-side",
        caravan: "caravan",
        63743: "caravan",
        carrot: "carrot",
        63367: "carrot",
        "cart-arrow-down": "cart-arrow-down",
        61976: "cart-arrow-down",
        "cart-plus": "cart-plus",
        61975: "cart-plus",
        "cash-register": "cash-register",
        63368: "cash-register",
        cat: "cat",
        63166: "cat",
        "cc-amazon-pay": "cc-amazon-pay",
        62509: "cc-amazon-pay",
        "cc-diners-club": "",
        62028: "cc-diners-club",
        "cc-amex": "cc-amex",
        61939: "cc-amex",
        "cc-apple-pay": "cc-apple-pay",
        62486: "cc-apple-pay",
        "cc-discover": "cc-discover",
        61938: "cc-discover",
        "cc-jcb": "cc-jcb",
        62027: "cc-jcb",
        "cc-mastercard": "cc-mastercard",
        61937: "cc-mastercard",
        "cc-paypal": "cc-paypal",
        61940: "cc-paypal",
        "cc-stripe": "cc-stripe",
        61941: "cc-stripe",
        "cc-visa": "cc-visa",
        61936: "cc-visa",
        certificate: "certificate",
        61603: "certificate",
        chalkboard: "chalkboard",
        62747: "chalkboard",
        "chalkboard-teacher": "chalkboard-teacher",
        62748: "chalkboard-teacher",
        "charging-station": "charging-station",
        62951: "charging-station",
        "chart-area": "chart-area",
        61950: "chart-area",
        "check-double": "check-double",
        62816: "check-double",
        "chart-bar": "chart-column",
        61568: "chart-column",
        "chart-line": "chart-line",
        61953: "chart-line",
        "chart-pie": "chart-pie",
        61952: "chart-pie",
        cheese: "cheese",
        63471: "cheese",
        church: "church",
        62749: "church",
        child: "child",
        61870: "child",
        circle: "circle",
        61713: "circle",
        city: "city",
        63055: "city",
        "clinic-medical": "clinic-medical",
        63474: "clinic-medical",
        clock: "clock",
        61463: "clock",
        "closed-captioning": "closed-captioning",
        61962: "closed-captioning",
        cloud: "cloud",
        61634: "cloud",
        "cloud-meatball": "cloud-meatball",
        63291: "cloud-meatball",
        "cloud-moon": "cloud-moon",
        63171: "cloud-moon",
        "cloud-moon-rain": "cloud-moon-rain",
        63292: "cloud-moon-rain",
        "cloud-rain": "cloud-rain",
        63293: "cloud-rain",
        "cloud-showers-heavy": "cloud-showers-heavy",
        63296: "cloud-showers-heavy",
        "cloud-sun": "cloud-sun",
        63172: "cloud-sun",
        "cloud-sun-rain": "cloud-sun-rain",
        63299: "cloud-sun-rain",
        cocktail: "cocktail",
        62817: "cocktail",
        coffee: "coffee",
        61684: "coffee",
        coins: "coins",
        62750: "coins",
        comment: "comment",
        61557: "comment",
        "comment-alt": "comment-alt",
        62074: "comment-alt",
        "comment-dollar": "comment-dollar",
        63057: "comment-dollar",
        comments: "comments",
        61574: "comments",
        "comments-dollar": "comments-dollar",
        63059: "",
        "compact-disc": "compact-disc",
        62751: "compact-disc",
        compass: "compass",
        61774: "compass",
        "concierge-bell": "concierge-bell",
        62818: "concierge-bell",
        cookie: "cookie",
        62819: "cookie",
        "cookie-bite": "cookie-bite",
        62820: "cookie-bite",
        copyright: "copyright",
        61945: "copyright",
        "credit-card": "credit-card",
        61597: "credit-card",
        cross: "cross",
        63060: "cross",
        crow: "crow",
        62752: "crow",
        crown: "crown",
        62753: "crown",
        cubes: "cubes",
        61875: "cubes",
        cube: "cube",
        61874: "cube",
        cut: "cut",
        61636: "cut",
        database: "database",
        61888: "database",
        deaf: "deaf",
        62116: "deaf",
        democrat: "democrat",
        63303: "democrat",
        desktop: "desktop",
        61704: "desktop",
        dharmachakra: "dharmachakra",
        63061: "dharmachakra",
        dice: "dice",
        62754: "dice",
        "digital-tachograph": "digital-tachograph",
        62822: "",
        dog: "dog",
        63187: "dog",
        "dollar-sign": "dollar-sign",
        61781: "dollar-sign",
        donate: "donate",
        62649: "donate",
        "door-closed": "door-closed",
        62762: "door-closed",
        "door-open": "door-open",
        62763: "door-open",
        dove: "dove",
        62650: "dove",
        dragon: "dragon",
        63189: "dragon",
        drum: "drum",
        62825: "drum",
        "drum-steelpan": "drum-steelpan",
        62826: "drum-steelpan",
        "drumstick-bite": "drumstick-bite",
        63191: "drumstick-bite",
        dumbbell: "dumbbell",
        62539: "dumbbell",
        "dumpster-fire": "dumpster-fire",
        63380: "dumpster-fire",
        dungeon: "dungeon",
        63193: "dungeon",
        egg: "egg",
        63483: "egg",
        envelope: "envelope",
        61664: "envelope",
        "envelope-open": "envelope-open",
        62134: "envelope-open",
        "envelope-square": "envelope-square",
        61849: "envelope-square",
        "euro-sign": "euro-sign",
        61779: "euro-sign",
        exclamation: "exclamation",
        61738: "exclamation",
        "exclamation-circle": "exclamation-circle",
        61546: "exclamation-circle",
        "exclamation-triangle": "exclamation-triangle",
        61553: "exclamation-triangle",
        eye: "eye",
        61550: "eye",
        "facebook-f": "facebook-f",
        62366: "facebook-f",
        "facebook-messenger": "facebook-messenger",
        62367: "facebook-messenger",
        "facebook-square": "facebook-square",
        faucet: "faucet",
        fax: "fax",
        61868: "fax",
        feather: "feather",
        62765: "feather",
        "feather-alt": "feather-alt",
        62827: "feather-alt",
        female: "female",
        61826: "female",
        "fighter-jet": "fighter-jet",
        61691: "fighter-jet",
        film: "film",
        61448: "film",
        fire: "fire-alt",
        61549: "fire-alt",
        "fire-alt": "fire",
        63460: "fire",
        "fire-extinguisher": "fire-extinguisher",
        61748: "fire-extinguisher",
        "first-aid": "first-aid",
        62585: "first-aid",
        fish: "fish",
        62840: "fish",
        "fist-raised": "fist-raised",
        63198: "fist-raised",
        flag: "flag",
        61476: "flag",
        "flag-checkered": "flag-checkered",
        61726: "flag-checkered",
        "flag-usa": "flag-usa",
        63309: "flag-usa",
        flask: "flask",
        61635: "flask",
        "football-ball": "football-ball",
        62542: "football-ball",
        frog: "frog",
        62766: "frog",
        futbol: "futbol",
        61923: "futbol",
        gamepad: "gamepad",
        61723: "gamepad",
        "gas-pump": "gas-pump",
        62767: "gas-pump",
        gavel: "gavel",
        61667: "gavel",
        gem: "gem",
        62373: "gem",
        genderless: "genderless",
        61997: "genderless",
        ghost: "ghost",
        63202: "ghost",
        gift: "gift",
        61547: "gift",
        gifts: "gifts",
        63388: "gifts",
        "glass-cheers": "glass-cheers",
        63391: "glass-cheers",
        "glass-martini": "martini-glass-empty",
        61440: "martini-glass-empty",
        "glass-martini-alt": "glass-martini-alt",
        62843: "glass-martini-alt",
        "glass-whiskey": "glass-whiskey",
        63392: "glass-whiskey",
        glasses: "glasses",
        62768: "glasses",
        globe: "globe",
        61612: "globe",
        "globe-africa": "globe-africa",
        62844: "globe-africa",
        "globe-americas": "globe-americas",
        62845: "globe-americas",
        "globe-asia": "globe-asia",
        62846: "globe-asia",
        "globe-europe": "globe-europe",
        63394: "globe-europe",
        "golf-ball": "golf-bal",
        62544: "golf-bal",
        google: "google",
        61856: "google",
        "google-drive": "google-drive",
        62378: "google-drive",
        "google-pay": "google-pay",
        "google-play": "google-play",
        62379: "google-play",
        "google-wallet": "google-wallet",
        61934: "google-wallet",
        gopuram: "gopuram",
        63076: "gopuram",
        "graduation-cap": "graduation-cap",
        61853: "graduation-cap",
        grin: "grin",
        62848: "grin",
        "grin-stars": "grin-stars",
        62855: "grin-stars",
        "grin-alt": "grin-alt",
        62849: "grin-alt",
        "grin-beam": "grin-beam",
        62850: "grin-beam",
        "grin-beam-sweat": "grin-beam-sweat",
        62851: "grin-beam-sweat",
        "grin-hearts": "grin-hearts",
        62852: "grin-hearts",
        "grin-squint": "grin-squint",
        62853: "grin-squint",
        "grin-squint-tears": "grin-tears",
        62854: "grin-tears",
        "grin-tears": "grin-tears",
        62856: "grin-tears",
        "grin-tongue": "grin-tongue",
        62857: "grin-tongue",
        "grin-tongue-squint": "grin-tongue-squint",
        62858: "grin-tongue-squint",
        "grin-tongue-wink": "grin-tongue-wink",
        62859: "grin-tongue-wink",
        "grin-wink": "grin-wink",
        62860: "grin-wink",
        guitar: "guitar",
        63398: "guitar",
        hamburger: "hamburger",
        63493: "hamburger",
        hamsa: "hamsa",
        63077: "hamsa",
        "hand-holding": "hand-holding",
        62653: "hand-holding",
        "hand-holding-heart": "hand-holding-heart",
        62654: "hand-holding-heart",
        "hand-holding-medical": "hand-holding-medical",
        57436: "hand-holding-medical",
        "hand-holding-water": "hand-holding-water",
        62657: "hand-holding-water",
        "hand-holding-usd": "hand-holding-usd",
        62656: "hand-holding-usd",
        "hand-paper": "hand-paper",
        62038: "hand-paper",
        "hand-peace": "hand-peace",
        62043: "hand-peace",
        "hand-point-down": "hand-point-down",
        61607: "hand-point-down",
        "hand-point-left": "hand-point-left",
        61605: "hand-point-left",
        "hand-point-right": "hand-point-right",
        61604: "hand-point-right",
        "hand-point-up": "hand-point-up",
        61606: "hand-point-up",
        "hand-pointer": "hand-pointer",
        62042: "hand-pointer",
        "hand-rock": "hand-rock",
        62037: "hand-rock",
        "hand-scissors": "hand-scissors",
        62039: "hand-scissors",
        "hand-spock": "hand-spock",
        62041: "hand-spock",
        hands: "hands-holding",
        62658: "hands-holding",
        "hands-helping": "hands-helping",
        62660: "hands-helping",
        "hands-wash": "hands-wash",
        57438: "hands-wash",
        handshake: "handshake",
        62133: "handshake",
        "handshake-alt-slash": "handshake-alt-slash",
        57439: "handshake-alt-slash",
        "handshake-slash": "handshake-slash",
        57440: "handshake-slash",
        hanukiah: "hanukiah",
        63206: "hanukiah",
        hashtag: "hashtag",
        62098: "hashtag",
        "hat-cowboy": "hat-cowboy",
        63680: "hat-cowboy",
        "hat-cowboy-side": "hat-cowboy-side",
        63681: "hat-cowboy-side",
        headphones: "headphones",
        61477: "headphones",
        "headphones-alt": "headphones-alt",
        62863: "headphones-alt",
        headset: "headset",
        62864: "headset",
        "heart-broken": "heart-broken",
        63401: "heart-broken",
        heartbeat: "heartbeat",
        61982: "heartbeat",
        helicopter: "helicopter",
        62771: "helicopter",
        highlighter: "highlighter",
        62865: "highlighter",
        hiking: "hiking",
        63212: "hiking",
        hippo: "hippo",
        63213: "hippo",
        "hockey-puck": "hockey-puck",
        62547: "hockey-puck",
        "holly-berry": "holly-berry",
        63402: "holly-berry",
        home: "home",
        61461: "home",
        horse: "horse",
        63216: "horse",
        "horse-head": "horse-head",
        63403: "horse-head",
        hospital: "hospital",
        61688: "hospital",
        "hospital-alt": "hospital-alt",
        62589: "hospital-alt",
        "hospital-user": "hospital-user",
        63501: "hospital-user",
        "hot-tub": "hot-tub",
        62867: "hot-tub",
        hotdog: "hotdog",
        63503: "hotdog",
        hotel: "hotel",
        62868: "hotel",
        hourglass: "hourglass",
        62036: "hourglass",
        "hourglass-end": "hourglass-end",
        62035: "hourglass-end",
        "hourglass-half": "hourglass-half",
        62034: "hourglass-half",
        "hourglass-start": "hourglass-start",
        62033: "hourglass-start",
        "house-damage": "house-damage",
        63217: "house-damage",
        hryvnia: "hryvnia",
        63218: "hryvnia",
        "ice-cream": "ice-cream",
        63504: "ice-cream",
        icicles: "icicles",
        63405: "icicles",
        icons: "icons",
        63597: "icons",
        "id-badge": "id-badge",
        62145: "id-badge",
        "id-card": "address-card",
        62146: "address-card",
        "id-card-alt": "id-card-alt",
        62591: "id-card-alt",
        igloo: "igloo",
        63406: "igloo",
        image: "image",
        61502: "image",
        images: "images",
        62210: "images",
        inbox: "inbox",
        61468: "inbox",
        industry: "industry",
        62069: "industry",
        "instagram-square": "instagram-square",
        57429: "instagram-square",
        "itunes-note": "itunes-note",
        62389: "itunes-note",
        key: "key",
        61572: "key",
        kaaba: "kaaba",
        63083: "kaaba",
        khanda: "khanda",
        63085: "khanda",
        kiss: "kiss",
        62870: "kiss",
        "kiss-beam": "kiss-beam",
        62871: "kiss-beam",
        "kiss-wink-heart": "kiss-wink-heart",
        62872: "kiss-wink-heart",
        "kiwi-bird": "kiwi-bird",
        62773: "kiwi-bird",
        landmark: "landmark",
        63087: "landmark",
        language: "language",
        61867: "language",
        laptop: "laptop",
        61705: "laptop",
        "laptop-house": "laptop-house",
        57446: "laptop-house",
        laugh: "laugh",
        62873: "laugh",
        "laugh-beam": "laugh-beam",
        62874: "laugh-beam",
        "laugh-squint": "laugh-squint",
        62875: "laugh-squint",
        "laugh-wink": "laugh-wink",
        62876: "laugh-wink",
        leaf: "leaf",
        61548: "leaf",
        lemon: "lemon",
        61588: "lemon",
        transgender: "transgender",
        61988: "transgender",
        "life-ring": "life-ring",
        61901: "life-ring",
        lightbulb: "lightbulb",
        61675: "lightbulb",
        "lira-sign": "turkish-lira-sign",
        61845: "turkish-lira-sign",
        lock: "lock",
        61475: "lock",
        "lock-open": "lock-open",
        62401: "lock-open",
        "low-vision": "low-vision",
        62120: "low-vision",
        "luggage-cart": "luggage-cart",
        62877: "luggage-cart",
        magnet: "magnet",
        61558: "magnet",
        male: "male",
        61827: "male",
        "map-marked": "map-marked",
        62879: "map-marked",
        "map-marked-alt": "map-marked-alt",
        62880: "map-marked-alt",
        "map-marker": "map-marker",
        61505: "map-marker",
        "map-marker-alt": "map-marker-alt",
        62405: "map-marker-alt",
        "map-pin": "map-pin",
        62070: "map-pin",
        "map-signs": "map-signs",
        62071: "map-signs",
        marker: "marker",
        62881: "marker",
        mars: "mars",
        61986: "mars",
        "mars-double": "mars-double",
        61991: "mars-double",
        "mars-stroke": "mars-stroke",
        61993: "mars-stroke",
        "mars-stroke-h": "mars-stroke-h",
        61995: "mars-stroke-h",
        "mars-stroke-v": "mars-stroke-v",
        61994: "mars-stroke-v",
        mask: "mask",
        63226: "mask",
        medal: "medal",
        62882: "medal",
        medkit: "",
        61690: "",
        menorah: "menorah",
        63094: "menorah",
        mercury: "mercury",
        61987: "mercury",
        meteor: "meteor",
        63315: "meteor",
        microphone: "",
        61744: "microphone",
        "microphone-alt": "microphone-alt",
        62409: "microphone-alt",
        microscope: "microscope",
        62992: "microscope",
        mitten: "mitten",
        63413: "mitten",
        mobile: "mobile-button",
        61707: "mobile-button",
        "mobile-alt": "mobile-alt",
        62413: "mobile-alt",
        "money-bill": "money-bill",
        61654: "money-bill",
        "money-bill-alt": "money-bill-alt",
        62417: "money-bill-alt",
        "money-bill-wave": "money-bill-wave",
        62778: "money-bill-wave",
        "money-bill-wave-alt": "money-bill-wave",
        62779: "money-bill-wave",
        "money-check": "money-check",
        62780: "money-check",
        "money-check-alt": "money-check-alt",
        62781: "money-check-alt",
        monument: "monument",
        62886: "monument",
        moon: "moon",
        61830: "moon",
        mosque: "mosque",
        63096: "mosque",
        motorcycle: "motorcycle",
        61980: "motorcycle",
        mountain: "mountain",
        63228: "mountain",
        "mug-hot": "mug-hot",
        63414: "mug-hot",
        music: "music",
        61441: "music",
        neuter: "neuter",
        61996: "neuter",
        newspaper: "newspaper",
        61930: "newspaper",
        "oil-can": "oil-can",
        62995: "oil-can",
        om: "om",
        63097: "om",
        otter: "otter",
        63232: "otter",
        "paint-brush": "paint-brush",
        61948: "paint-brush",
        "paper-plane": "paper-plane",
        61912: "paper-plane",
        paperclip: "paperclip",
        61638: "paperclip",
        "parachute-box": "parachute-box",
        62669: "parachute-box",
        paragraph: "paragraph",
        61917: "paragraph",
        passport: "passport",
        62891: "passport",
        paw: "paw",
        61872: "paw",
        peace: "peace",
        63100: "peace",
        pen: "pen",
        62212: "pen",
        "pen-alt": "pen-alt",
        62213: "pen-alt",
        "pen-fancy": "pen-fancy",
        62892: "pen-fancy",
        "pen-nib": "pen-nib",
        62893: "pen-nib",
        "pen-square": "pen-square",
        61771: "pen-square",
        "pencil-alt": "pencil-alt",
        62211: "pencil-alt",
        "people-arrows": "people-arrows",
        57448: "people-arrows",
        "people-carry": "people-carry",
        62670: "people-carry",
        "pepper-hot": "pepper-hot",
        63510: "pepper-hot",
        percent: "percentage",
        62101: "percentage",
        percentage: "percentage",
        62785: "percentage",
        "person-booth": "person-booth",
        63318: "person-booth",
        phone: "phone-alt",
        61589: "phone-alt",
        "phone-alt": "phone",
        63609: "phone",
        "phone-square": "phone-square-alt",
        61592: "phone-square-alt",
        "phone-square-alt": "phone-square",
        63611: "phone-square",
        "phone-volume": "phone-volume",
        62112: "phone-volume",
        "photo-video": "photo-video",
        63612: "photo-video",
        "piggy-bank": "piggy-bank",
        62675: "piggy-bank",
        "pinterest-p": "pinterest-p",
        62001: "pinterest-p",
        "pinterest-square": "pinterest-square",
        61651: "pinterest-square",
        "pizza-slice": "pizza-slice",
        63512: "pizza-slice",
        "place-of-worship": "place-of-worship",
        63103: "place-of-worship",
        plane: "plane",
        61554: "plane",
        "plane-arrival": "plane-arrival",
        62895: "plane-arrival",
        "plane-departure": "plane-departure",
        62896: "plane-departure",
        "plane-slash": "plane-slash",
        57449: "plane-slash",
        plug: "plug",
        61926: "plug",
        62158: "podcast",
        poll: "poll",
        63105: "poll",
        "poll-h": "poll-h",
        63106: "poll-h",
        poo: "poo",
        62206: "poo",
        portrait: "address-book",
        62432: "address-book",
        "pound-sign": "pound-sign",
        61780: "pound-sign",
        pray: "pray",
        63107: "pray",
        "praying-hands": "praying-hands",
        63108: "praying-hands",
        "project-diagram": "project-diagram",
        62786: "project-diagram",
        "puzzle-piece": "puzzle-piece",
        61742: "puzzle-piece",
        "question-circle": "question-circle",
        61529: "question-circle",
        "quote-left": "quote-left",
        61709: "quote-left",
        "quote-right": "quote-right",
        61710: "quote-right",
        quran: "quran",
        63111: "quran",
        radiation: "radiation",
        63417: "radiation",
        "radiation-alt": "radiation-alt",
        63418: "radiation-alt",
        rainbow: "rainbow",
        63323: "rainbow",
        receipt: "receipt",
        62787: "receipt",
        "record-vinyl": "record-vinyl",
        63705: "record-vinyl",
        registered: "registered",
        62045: "registered",
        republican: "republican",
        63326: "republican",
        restroom: "restroom",
        63421: "restroom",
        ribbon: "ribbon",
        62678: "ribbon",
        ring: "ring",
        63243: "ring",
        road: "road",
        61464: "road",
        rocket: "rocket",
        61749: "rocket",
        robot: "robot",
        62788: "robot",
        route: "route",
        62679: "route",
        61598: "rss",
        "rss-square": "rss-square",
        61763: "rss-square",
        "ruler-combined": "ruler-combined",
        62790: "ruler-combined",
        "ruler-horizontal": "ruler-horizontal",
        62791: "ruler-horizontal",
        "ruler-vertical": "ruler-vertical",
        62792: "ruler-vertical",
        running: "running",
        "rupee-sign": "rupee-sign",
        61782: "rupee-sign",
        "sad-cry": "sad-cry",
        62899: "sad-cry",
        "sad-tear": "sad-tear",
        62900: "sad-tear",
        satellite: "satellite",
        63423: "satellite",
        "satellite-dish": "satellite-dish",
        63424: "satellite-dish",
        school: "school",
        62793: "school",
        screwdriver: "screwdriver",
        62794: "screwdriver",
        scroll: "scroll",
        63246: "scroll",
        search: "search",
        61442: "search",
        seedling: "seedling",
        62680: "seedling",
        shapes: "shapes",
        63007: "shapes",
        "share-alt": "share-alt",
        61920: "share-alt",
        "shekel-sign": "shekel-sign",
        61963: "shekel-sign",
        "shield-alt": "shield-alt",
        62445: "shield-alt",
        "shipping-fast": "shipping-fast",
        62603: "shipping-fast",
        "shoe-prints": "shoe-prints",
        62795: "shoe-prints",
        "shopping-bag": "shopping-bag",
        62096: "shopping-bag",
        "shopping-basket": "shopping-basket",
        62097: "shopping-basket",
        "shopping-cart": "shopping-cart",
        61562: "shopping-cart",
        shower: "shower",
        62156: "shower",
        "shuttle-van": "shuttle-van",
        62902: "shuttle-van",
        "sign-language": "sign-language",
        62119: "sign-language",
        signature: "signature",
        62903: "signature",
        skating: "skating",
        63429: "skating",
        skiing: "skiing",
        63433: "skiing",
        "skiing-nordic": "skiing-nordic",
        63434: "skiing-nordic",
        sleigh: "sleigh",
        63436: "sleigh",
        smile: "smile",
        61720: "smile",
        "smile-beam": "smile-beam",
        62904: "smile-beam",
        "smile-wink": "smile-wink",
        62682: "smile-wink",
        smog: "smog",
        63327: "smog",
        snowboarding: "snowboarding",
        63438: "snowboarding",
        snowflake: "snowflake",
        62172: "snowflake",
        snowman: "snowman",
        63440: "snowman",
        snowplow: "snowplow",
        63442: "snowplow",
        socks: "socks",
        63126: "socks",
        spa: "spa",
        62907: "spa",
        "space-shuttle": "space-shuttle",
        61847: "space-shuttle",
        spider: "spider",
        63255: "spider",
        square: "square",
        61640: "square",
        61445: "star",
        stamp: "stamp",
        62911: "stamp",
        "star-and-crescent": "star-and-crescent",
        63129: "star-and-crescent",
        "star-of-david": "star-of-david",
        63130: "star-of-david",
        "sticky-note": "sticky-note",
        62025: "sticky-note",
        stopwatch: "stopwatch",
        62194: "stopwatch",
        "stopwatch-20": "stopwatch-20",
        57455: "stopwatch-20",
        store: "store",
        62798: "store",
        "store-alt": "store-alt",
        62799: "store-alt",
        "store-alt-slash": "store-alt-slash",
        57456: "store-alt-slash",
        "store-slash": "store-slash",
        stream: "stream",
        62800: "stream",
        "street-view": "street-view",
        61981: "street-view",
        stroopwafel: "stroopwafel",
        62801: "stroopwafel",
        subway: "subway",
        62009: "subway",
        suitcase: "suitcase",
        61682: "suitcase",
        "suitcase-rolling": "suitcase-rolling",
        62913: "suitcase-rolling",
        sun: "sun",
        61829: "sun",
        surprise: "surprise",
        62914: "surprise",
        swimmer: "swimmer",
        62916: "swimmer",
        "swimming-pool": "swimming-pool",
        62917: "swimming-pool",
        synagogue: "synagogue",
        63131: "synagogue",
        table: "table",
        61646: "table",
        "table-tennis": "table-tennis",
        62557: "table-tennis",
        tablet: "tablet",
        61706: "tablet",
        "tablet-alt": "tablet-alt",
        62458: "tablet-alt",
        "tachometer-alt": "tachometer-alt",
        62461: "tachometer-alt",
        tag: "tag",
        61483: "tag",
        tags: "tags",
        61484: "tags",
        tasks: "tasks",
        61614: "tasks",
        taxi: "taxi",
        61882: "taxi",
        "temperature-high": "temperature-high",
        63337: "temperature-high",
        "temperature-low": "temperature-low",
        63339: "temperature-low",
        tenge: "tenge",
        63447: "tenge",
        "theater-masks": "theater-masks",
        63024: "theater-masks",
        "thumbs-down": "thumbs-down",
        61797: "thumbs-down",
        "thumbs-up": "thumbs-up",
        61796: "thumbs-up",
        thumbtack: "thumbtack",
        61581: "thumbtack",
        "ticket-alt": "ticket",
        62463: "ticket",
        toilet: "toilet",
        63448: "toilet",
        "toilet-paper": "toilet-paper",
        63262: "toilet-paper",
        toolbox: "toolbox",
        62802: "toolbox",
        tools: "tools",
        63449: "tools",
        torah: "torah",
        63136: "torah",
        "torii-gate": "torii-gate",
        63137: "torii-gate",
        tractor: "tractor",
        63266: "tractor",
        trademark: "trademark",
        62044: "trademark",
        trailer: "trailer",
        train: "train",
        62008: "train",
        tram: "tram",
        63450: "tram",
        "transgender-alt": "transgender",
        61989: "transgender",
        tree: "tree",
        61883: "tree",
        trophy: "trophy",
        61585: "trophy",
        truck: "truck",
        61649: "truck",
        "truck-monster": "truck-monster",
        63035: "truck-monster",
        "truck-pickup": "truck-pickup",
        63036: "truck-pickup",
        tshirt: "tshirt",
        62803: "tshirt",
        tty: "tty",
        61924: "tty",
        tv: "tv",
        62060: "tv",
        61593: "twitter",
        "twitter-square": "twitter-square",
        61569: "twitter-square",
        umbrella: "umbrella",
        61673: "umbrella",
        "umbrella-beach": "umbrella-beach",
        62922: "umbrella-beach",
        university: "university",
        61852: "university",
        "universal-access": "universal-access",
        62106: "universal-access",
        unlock: "unlock",
        61596: "unlock",
        "unlock-alt": "unlock-alt",
        61758: "unlock-alt",
        user: "user",
        61447: "user",
        users: "users",
        61632: "users",
        "utensil-spoon": "utensil-spoon",
        62181: "utensil-spoon",
        utensils: "utensils",
        62183: "utensils",
        venus: "venus",
        61985: "venus",
        "venus-double": "venus-double",
        61990: "venus-double",
        "venus-mars": "venus-mars",
        61992: "venus-mars",
        61501: "video",
        vihara: "vihara",
        63143: "vihara",
        62474: "vimeo",
        "vimeo-square": "vimeo-square",
        61844: "vimeo-square",
        "vimeo-v": "vimeo-v",
        62077: "vimeo-v",
        voicemail: "voicemail",
        63639: "voicemail",
        "volleyball-ball": "volleyball-ball",
        62559: "volleyball-ball",
        "vote-yea": "vote-yea",
        63346: "vote-yea",
        "vr-cardboard": "",
        63273: "vr-cardboard",
        walking: "walking",
        62804: "walking",
        wallet: "wallet",
        62805: "wallet",
        warehouse: "warehouse",
        62612: "warehouse",
        water: "water",
        63347: "water",
        weight: "weight",
        62614: "weight",
        "weight-hanging": "weight-hanging",
        62925: "weight-hanging",
        wheelchair: "wheelchair",
        61843: "wheelchair",
        wifi: "wifi",
        61931: "wifi",
        wind: "wind",
        63278: "wind",
        "wine-bottle": "wine-bottle",
        63279: "wine-bottle",
        "wine-glass": "wine-glass",
        62691: "wine-glass",
        "wine-glass-alt": "wine-glass-alt",
        62926: "wine-glass-alt",
        "won-sign": "won-sign",
        61785: "won-sign",
        wrench: "wrench",
        61613: "wrench",
        "yen-sign": "yen-sign",
        61783: "yen-sign",
        "yin-yang": "yin-yang",
        63149: "yin-yang",
        61799: "youtube",
        "youtube-square": "youtube-square",
        blip: "rss",
        57361: "rss",
        feedburner: "fire",
        57384: "fire",
        diagonalarrow: "arrow-up-right-from-square",
        57705: "arrow-up-right-from-square",
        circlediagonalarrow: "arrow-up-right-from-square",
        58217: "arrow-up-right-from-square",
        circleblip: "rss-square",
        57873: "rss-square",
        googletalk: "comment",
        57408: "comment",
        circlegoogletalk: "comment",
        57920: "comment",
        roundedgoogletalk: "comment",
        58432: "comment",
        photobucket: "camera",
        57442: "camera",
        circlephotobucket: "camera",
        57954: "camera",
        roundedphotobucket: "camera",
        58466: "camera",
        picasa: "image",
        57443: "image",
        circlepicasa: "image",
        57955: "image",
        roundedpicasa: "image",
        58467: "image"
      };
      getColor = (0, import_fp_utilities2.mPipe)(readKey("color"), read2, parseColorString);
      getBgColor = (0, import_fp_utilities2.mPipe)(
        readKey("background-color"),
        read2,
        parseColorString
      );
      getStyles = (node) => {
        const parentNode = getParentElementOfTextNode(node);
        const isIconText = parentNode?.nodeName === "#text";
        const iconNode = isIconText ? node : parentNode;
        return iconNode ? getNodeStyle(iconNode) : {};
      };
      getParentStyles = (node) => {
        const parentElement = node.parentElement;
        return parentElement ? getNodeStyle(parentElement) : {};
      };
      getStyleModel = (node) => {
        const style = getStyles(node);
        const parentStyle = getParentStyles(node);
        const opacity = +style.opacity;
        const color = getColor(style);
        const parentBgColor = getBgColor(parentStyle);
        return {
          ...color && {
            colorHex: normalizeOpacity({
              hex: color.hex,
              opacity: color.opacity ?? String(opacity)
            }).hex,
            colorOpacity: normalizeOpacity({
              hex: color.hex,
              opacity: isNaN(opacity) ? color.opacity ?? "1" : String(opacity)
            }).opacity,
            colorPalette: ""
          },
          ...parentBgColor && {
            bgColorHex: parentBgColor.hex,
            bgColorOpacity: parentBgColor.opacity,
            bgColorPalette: "",
            padding: 7
          }
        };
      };
    }
  });

  // ../../../../../../../packages/utils/src/fp/pipe.ts
  function pipe(...[h, ...fns]) {
    return (...args) => fns.reduce((v4, fn) => fn(v4), h(...args));
  }
  var init_pipe = __esm({
    "../../../../../../../packages/utils/src/fp/pipe.ts"() {
      "use strict";
    }
  });

  // ../../../../../../../packages/utils/src/isNullish.ts
  var isNullish;
  var init_isNullish = __esm({
    "../../../../../../../packages/utils/src/isNullish.ts"() {
      "use strict";
      isNullish = (v4) => v4 === void 0 || v4 === null || typeof v4 === "number" && Number.isNaN(v4);
    }
  });

  // ../../../../../../../packages/utils/src/onNullish.ts
  function onNullish(...args) {
    return args.length === 1 ? (v4) => isNullish(v4) ? args[0] : v4 : isNullish(args[1]) ? args[0] : args[1];
  }
  var init_onNullish = __esm({
    "../../../../../../../packages/utils/src/onNullish.ts"() {
      "use strict";
      init_isNullish();
    }
  });

  // ../../../../../../../packages/elements/src/Text/models/Button/utils/getModel.ts
  var import_fp_utilities3, getColor2, getBgColor2, getBorderWidth, getTransform, getText, getBgColorOpacity, getStyleModel2, getModel5;
  var init_getModel5 = __esm({
    "../../../../../../../packages/elements/src/Text/models/Button/utils/getModel.ts"() {
      "use strict";
      init_getGlobalButtonModel();
      init_common();
      init_getModel4();
      import_fp_utilities3 = __toESM(require_dist());
      init_parseColorString();
      init_getNodeStyle();
      init_pipe();
      init_onNullish();
      init_number();
      init_object();
      init_string();
      init_uuid();
      getColor2 = (0, import_fp_utilities3.mPipe)(readKey("color"), read2, parseColorString);
      getBgColor2 = (0, import_fp_utilities3.mPipe)(
        readKey("background-color"),
        read2,
        parseColorString
      );
      getBorderWidth = (0, import_fp_utilities3.mPipe)(readKey("border-width"), read);
      getTransform = (0, import_fp_utilities3.mPipe)(readKey("text-transform"), read2);
      getText = pipe(readKey("text"), read2, onNullish("BUTTON"));
      getBgColorOpacity = (color, opacity) => {
        if (color.opacity && +color.opacity === 0) {
          return 0;
        }
        return +(isNaN(opacity) ? color.opacity ?? 1 : opacity);
      };
      getStyleModel2 = (node) => {
        const style = getNodeStyle(node);
        const color = getColor2(style);
        const bgColor = getBgColor2(style);
        const opacity = +style.opacity;
        const borderWidth = getBorderWidth(style);
        return {
          ...color && {
            colorHex: normalizeOpacity({
              hex: color.hex,
              opacity: color.opacity ?? String(opacity)
            }).hex,
            colorOpacity: normalizeOpacity({
              hex: color.hex,
              opacity: color.opacity ?? String(opacity)
            }).opacity,
            colorPalette: ""
          },
          ...bgColor && {
            bgColorHex: bgColor.hex,
            bgColorOpacity: getBgColorOpacity(bgColor, opacity),
            bgColorPalette: "",
            ...getBgColorOpacity(bgColor, opacity) === 0 ? { bgColorType: "none", hoverBgColorType: "none" } : { bgColorType: "solid", hoverBgColorType: "solid" },
            hoverBgColorHex: bgColor.hex,
            hoverBgColorOpacity: 0.8,
            hoverBgColorPalette: ""
          },
          ...borderWidth === void 0 && { borderStyle: "none" }
        };
      };
      getModel5 = (node) => {
        const isLink = node.tagName === "A";
        const modelStyle = getStyleModel2(node);
        const globalModel = getGlobalButtonModel();
        const textTransform = getTransform(getNodeStyle(node));
        let iconModel = {};
        const icon = node.querySelector(iconSelector);
        if (icon) {
          const model = getModel4(icon);
          const name = read2(model.value.name);
          icon.remove();
          if (name) {
            iconModel = {
              iconName: name
            };
          }
        }
        let text = getText(node);
        const link = getTarget(node);
        const targetType = link === "_self" ? "off" : "on";
        switch (textTransform) {
          case "uppercase": {
            text = text.toUpperCase();
            break;
          }
          case "lowercase": {
            text = text.toUpperCase();
            break;
          }
        }
        return {
          type: "Button",
          value: {
            _id: uuid(),
            _styles: ["button"],
            text,
            ...globalModel,
            ...modelStyle,
            ...iconModel,
            ...isLink && {
              linkExternal: getHref(node),
              linkType: "external",
              linkExternalBlank: targetType
            }
          }
        };
      };
    }
  });

  // ../../../../../../../packages/utils/src/dom/findNearestBlockParent.ts
  function findNearestBlockParent(element) {
    if (!element.parentElement) {
      return void 0;
    }
    const displayStyle = window.getComputedStyle(element.parentElement).display;
    const isBlockElement = displayStyle === "block" || displayStyle === "flex" || displayStyle === "grid";
    if (isBlockElement) {
      return element.parentElement;
    } else {
      return findNearestBlockParent(element.parentElement);
    }
  }
  var init_findNearestBlockParent = __esm({
    "../../../../../../../packages/utils/src/dom/findNearestBlockParent.ts"() {
      "use strict";
    }
  });

  // ../../../../../../../packages/elements/src/Text/models/Button/index.ts
  function getButtonModel(node) {
    const buttons = node.querySelectorAll(buttonSelector);
    const groups = /* @__PURE__ */ new Map();
    buttons.forEach((button) => {
      const parentElement = findNearestBlockParent(button);
      const style = getNodeStyle(button);
      const model = getModel5(button);
      const group = groups.get(parentElement) ?? { value: { items: [] } };
      const wrapperModel = createCloneableModel({
        _styles: ["wrapper-clone", "wrapper-clone--button"],
        items: [...group.value.items, model],
        horizontalAlign: textAlign[style["text-align"]]
      });
      groups.set(parentElement, wrapperModel);
    });
    const models = [];
    groups.forEach((model) => {
      models.push(model);
    });
    return models;
  }
  var init_Button = __esm({
    "../../../../../../../packages/elements/src/Text/models/Button/index.ts"() {
      "use strict";
      init_Cloneable();
      init_common();
      init_getModel5();
      init_findNearestBlockParent();
      init_getNodeStyle();
    }
  });

  // ../../../../../../../packages/elements/src/Text/models/Embed/index.ts
  function getEmbedModel(node) {
    const embeds = node.querySelectorAll(embedSelector);
    const models = [];
    embeds.forEach(() => {
      models.push({ type: "EmbedCode" });
    });
    return models;
  }
  var init_Embed = __esm({
    "../../../../../../../packages/elements/src/Text/models/Embed/index.ts"() {
      "use strict";
      init_common();
    }
  });

  // ../../../../../../../packages/elements/src/Text/models/Icon/index.ts
  function getIconModel(node) {
    const icons = node.querySelectorAll(iconSelector);
    const groups = /* @__PURE__ */ new Map();
    icons.forEach((icon) => {
      const parentElement = findNearestBlockParent(icon);
      const parentNode = getParentElementOfTextNode(node);
      const isIconText = parentNode?.nodeName === "#text";
      const iconNode = isIconText ? node : parentNode;
      const style = iconNode ? getNodeStyle(iconNode) : {};
      const model = getModel4(icon);
      const group = groups.get(parentElement) ?? { value: { items: [] } };
      const wrapperModel = createCloneableModel({
        _styles: ["wrapper-clone", "wrapper-clone--icon"],
        items: [...group.value.items, model],
        horizontalAlign: textAlign[style["text-align"]]
      });
      groups.set(parentElement, wrapperModel);
    });
    const models = [];
    groups.forEach((model) => {
      models.push(model);
    });
    return models;
  }
  var init_Icon = __esm({
    "../../../../../../../packages/elements/src/Text/models/Icon/index.ts"() {
      "use strict";
      init_Cloneable();
      init_common();
      init_getModel4();
      init_findNearestBlockParent();
      init_getNodeStyle();
      init_getParentElementOfTextNode();
    }
  });

  // ../../../../../../../packages/elements/src/Models/Wrapper/index.ts
  var createWrapperModel;
  var init_Wrapper = __esm({
    "../../../../../../../packages/elements/src/Models/Wrapper/index.ts"() {
      "use strict";
      init_uuid();
      createWrapperModel = (data) => {
        const { _styles, items, ...value } = data;
        return {
          type: "Wrapper",
          value: { _id: uuid(), _styles, items, ...value }
        };
      };
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/styles/addMarginsToLists.ts
  var listMargins, addMarginsToLists;
  var init_addMarginsToLists = __esm({
    "../../../../../../../packages/elements/src/Text/utils/styles/addMarginsToLists.ts"() {
      "use strict";
      listMargins = (node) => {
        const allowedTags2 = ["UL", "OL"];
        if (allowedTags2.includes(node.nodeName)) {
          const { marginTop, marginBottom } = window.getComputedStyle(node);
          if (!isNaN(parseFloat(marginTop))) {
            const parsedMarginTop = Math.round(parseFloat(marginTop));
            node.firstElementChild?.classList.add(`brz-mt-lg-${parsedMarginTop}`);
          }
          if (!isNaN(parseFloat(marginBottom))) {
            const parsedMarginBottom = Math.round(parseFloat(marginBottom));
            node.lastElementChild?.classList.add(`brz-mb-lg-${parsedMarginBottom}`);
          }
        } else if (node.nodeType === Node.ELEMENT_NODE) {
          const children = Array.from(node.children);
          for (node of children) {
            if (node.textContent?.trim()) {
              listMargins(node);
            }
          }
        }
        return;
      };
      addMarginsToLists = (node) => {
        const children = Array.from(node.children);
        children.forEach((child) => {
          listMargins(child);
        });
        return node;
      };
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/dom/cleanClassNames.ts
  var cleanClassNames;
  var init_cleanClassNames = __esm({
    "../../../../../../../packages/elements/src/Text/utils/dom/cleanClassNames.ts"() {
      "use strict";
      cleanClassNames = (node) => {
        const classListExcepts = ["brz-"];
        const elementsWithClasses = node.querySelectorAll("[class]");
        elementsWithClasses.forEach(function(element) {
          element.classList.forEach((cls) => {
            if (!classListExcepts.some((except) => cls.startsWith(except))) {
              if (cls === "finaldraft_placeholder") {
                element.innerHTML = "";
              }
              element.classList.remove(cls);
            }
          });
          if (element.classList.length === 0) {
            element.removeAttribute("class");
          }
        });
      };
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/dom/removeAllStylesFromHTML.ts
  function removeStylesExceptFontWeightAndColor(htmlString) {
    const tempElement = document.createElement("div");
    tempElement.innerHTML = htmlString;
    const elementsWithStyles = tempElement.querySelectorAll("[style]");
    elementsWithStyles.forEach(function(element) {
      const styleAttribute = element.getAttribute("style") ?? "";
      const styleProperties = styleAttribute.split(";");
      let newStyle = "";
      for (let i = 0; i < styleProperties.length; i++) {
        const property = styleProperties[i].trim();
        if (property.startsWith("font-weight") || property.startsWith("color")) {
          newStyle += property + "; ";
        }
      }
      element.setAttribute("style", newStyle);
    });
    cleanClassNames(tempElement);
    return tempElement.innerHTML;
  }
  function removeAllStylesFromHTML(node) {
    const tagsToRemoveStyles = allowedTags.filter((item) => item !== "LI");
    const elementsWithStyles = node.querySelectorAll(
      tagsToRemoveStyles.join(",") + "[style]"
    );
    elementsWithStyles.forEach(function(element) {
      element.removeAttribute("style");
    });
    cleanClassNames(node);
    node.innerHTML = removeStylesExceptFontWeightAndColor(node.innerHTML);
    return node;
  }
  var init_removeAllStylesFromHTML = __esm({
    "../../../../../../../packages/elements/src/Text/utils/dom/removeAllStylesFromHTML.ts"() {
      "use strict";
      init_common();
      init_cleanClassNames();
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/dom/removeEmptyNodes.ts
  function removeEmptyNodes(node) {
    node.innerHTML = node.innerHTML.replace(/\n/g, "");
    return node;
  }
  var init_removeEmptyNodes = __esm({
    "../../../../../../../packages/elements/src/Text/utils/dom/removeEmptyNodes.ts"() {
      "use strict";
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/dom/transformDivsToParagraphs.ts
  function transformDivsToParagraphs(containerElement) {
    const divElements = containerElement.querySelectorAll("div");
    divElements.forEach(function(divElement) {
      const paragraphElement = document.createElement("p");
      for (let i = 0; i < divElement.attributes.length; i++) {
        const attr = divElement.attributes[i];
        paragraphElement.setAttribute(attr.name, attr.value);
      }
      paragraphElement.innerHTML = divElement.innerHTML;
      divElement.parentNode?.replaceChild(paragraphElement, divElement);
    });
    return containerElement;
  }
  var init_transformDivsToParagraphs = __esm({
    "../../../../../../../packages/elements/src/Text/utils/dom/transformDivsToParagraphs.ts"() {
      "use strict";
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/styles/copyParentColorToChild.ts
  function copyColorStyleToTextNodes(element) {
    if (element.nodeType === Node.TEXT_NODE) {
      let parentElement = element.parentElement;
      if (!parentElement) {
        return;
      }
      if (parentElement.tagName === "SPAN" || parentElement.tagName === "EM" || parentElement.tagName === "STRONG") {
        const parentOfParent = parentElement.parentElement;
        const parentStyle2 = parentElement.style;
        const parentComputedStyle = getComputedStyle(parentElement);
        if (attributes.includes("text-transform") && !parentStyle2?.textTransform) {
          const style = getNodeStyle(parentElement);
          if (style["text-transform"] === "uppercase") {
            parentElement.classList.add("brz-capitalize-on");
          }
        }
        if (attributes.includes("font-style") && parentComputedStyle.fontStyle === "italic") {
          const emElement = document.createElement("em");
          Array.from(parentElement.attributes).forEach((attr) => {
            emElement.setAttribute(attr.name, attr.value);
          });
          while (parentElement.firstChild) {
            emElement.appendChild(parentElement.firstChild);
          }
          parentElement.replaceWith(emElement);
          parentElement = emElement;
        }
        if (!parentOfParent) {
          return;
        }
        if (!parentStyle2?.color) {
          const parentOFParentStyle = getNodeStyle(parentOfParent);
          parentElement.style.color = `${parentOFParentStyle.color}`;
        }
        if (!parentStyle2?.fontWeight && parentOfParent.style?.fontWeight) {
          parentElement.style.fontWeight = parentOfParent.style.fontWeight;
        }
        if (parentOfParent.tagName === "SPAN") {
          const parentFontWeight = parentElement.style.fontWeight;
          parentElement.style.fontWeight = parentFontWeight || getComputedStyle(parentElement).fontWeight;
        }
        return;
      }
      let innerElementType = "span";
      const computedStyles = window.getComputedStyle(parentElement);
      const parentStyle = getNodeStyle(parentElement);
      if (attributes.includes("font-style") && (parentStyle["font-style"] === "italic" || computedStyles.fontStyle === "italic")) {
        innerElementType = "em";
      }
      const innerElement = document.createElement(innerElementType);
      if (attributes.includes("text-transform") && computedStyles.textTransform === "uppercase") {
        innerElement.classList.add("brz-capitalize-on");
      }
      if (computedStyles.color) {
        innerElement.style.color = computedStyles.color;
      }
      if (computedStyles.fontWeight) {
        innerElement.style.fontWeight = computedStyles.fontWeight;
      }
      innerElement.textContent = element.textContent;
      if (parentElement.tagName === "U") {
        parentElement.style.color = computedStyles.color;
      }
      if (element) {
        parentElement.replaceChild(innerElement, element);
      }
    } else if (element.nodeType === Node.ELEMENT_NODE) {
      const children = element.childNodes;
      for (let i = 0; i < children.length; i++) {
        const node = children[i];
        if (node.textContent?.trim()) {
          copyColorStyleToTextNodes(node);
        }
      }
    }
  }
  function copyParentColorToChild(node) {
    node.childNodes.forEach((child) => {
      copyColorStyleToTextNodes(child);
    });
    return node;
  }
  var attributes;
  var init_copyParentColorToChild = __esm({
    "../../../../../../../packages/elements/src/Text/utils/styles/copyParentColorToChild.ts"() {
      "use strict";
      init_common();
      init_getNodeStyle();
      attributes = extractedAttributes;
    }
  });

  // ../../../../../../../packages/utils/src/dom/recursiveGetNodes.ts
  var recursiveGetNodes;
  var init_recursiveGetNodes = __esm({
    "../../../../../../../packages/utils/src/dom/recursiveGetNodes.ts"() {
      "use strict";
      recursiveGetNodes = (node) => {
        let nodes = [];
        if (node.nodeType === Node.TEXT_NODE) {
          node.parentElement && nodes.push(node.parentElement);
        } else {
          for (let i = 0; i < node.childNodes.length; i++) {
            const child = node.childNodes[i];
            if (child) {
              nodes = nodes.concat(recursiveGetNodes(child));
            }
          }
        }
        return nodes;
      };
    }
  });

  // ../../../../../../../packages/utils/src/dom/extractAllElementsStyles.ts
  function extractAllElementsStyles(node) {
    const nodes = recursiveGetNodes(node);
    return nodes.reduce((acc, element) => {
      const styles = getNodeStyle(element);
      if (styles["display"] === "inline") {
        delete styles["text-align"];
      }
      return { ...acc, ...styles };
    }, {});
  }
  var init_extractAllElementsStyles = __esm({
    "../../../../../../../packages/utils/src/dom/extractAllElementsStyles.ts"() {
      "use strict";
      init_getNodeStyle();
      init_recursiveGetNodes();
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/styles/mergeStyles.ts
  function mergeStyles(element) {
    const elementStyles = getNodeStyle(element);
    if (elementStyles["display"] === "inline") {
      delete elementStyles["text-align"];
    }
    const innerStyles = extractAllElementsStyles(element);
    return {
      ...elementStyles,
      ...innerStyles,
      "line-height": elementStyles["line-height"]
    };
  }
  var init_mergeStyles = __esm({
    "../../../../../../../packages/elements/src/Text/utils/styles/mergeStyles.ts"() {
      "use strict";
      init_extractAllElementsStyles();
      init_getNodeStyle();
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/dom/extractParentElementsWithStyles.ts
  function extractParentElementsWithStyles(node) {
    let result = [];
    if (shouldExtractElement(node, exceptExtractingStyle)) {
      const uid = `uid-${Math.random()}-${Math.random()}`;
      node.setAttribute("data-uid", uid);
      result.push({
        uid,
        tagName: node.tagName,
        styles: mergeStyles(node)
      });
    }
    for (let i = 0; i < node.childNodes.length; i++) {
      const child = node.childNodes[i];
      result = result.concat(extractParentElementsWithStyles(child));
    }
    return result;
  }
  var init_extractParentElementsWithStyles = __esm({
    "../../../../../../../packages/elements/src/Text/utils/dom/extractParentElementsWithStyles.ts"() {
      "use strict";
      init_common();
      init_mergeStyles();
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/styles/getTypographyStyles.ts
  var getTypographyStyles;
  var init_getTypographyStyles = __esm({
    "../../../../../../../packages/elements/src/Text/utils/styles/getTypographyStyles.ts"() {
      "use strict";
      init_common();
      init_extractParentElementsWithStyles();
      getTypographyStyles = (node) => {
        const allRichTextElements = extractParentElementsWithStyles(node);
        return allRichTextElements.map((element) => {
          const { styles } = element;
          return {
            ...element,
            styles: extractedAttributes.reduce((acc, attribute) => {
              acc[attribute] = styles[attribute];
              return acc;
            }, {})
          };
        });
      };
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/styles/getLetterSpacing.ts
  function getLetterSpacing(value) {
    if (value === "normal") {
      return "0";
    }
    const letterSpacingValue = value.replace(/px/g, "").trim();
    const [integerPart, decimalPart = "0"] = letterSpacingValue.split(".");
    const toNumberI = +integerPart;
    if (toNumberI < 0 || Object.is(toNumberI, -0)) {
      return "m_" + -toNumberI + "_" + decimalPart[0];
    }
    return toNumberI + "_" + decimalPart[0];
  }
  var init_getLetterSpacing = __esm({
    "../../../../../../../packages/elements/src/Text/utils/styles/getLetterSpacing.ts"() {
      "use strict";
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/styles/getLineHeight.ts
  function getLineHeight(value, fontSize) {
    if (value === "normal") {
      return "1_2";
    }
    const lineHeightValue = value.replace("px", "");
    const lineHeight = Number(lineHeightValue) / Number(fontSize);
    const [integerPart, decimalPart = ""] = lineHeight.toString().split(".");
    return decimalPart ? integerPart + "_" + decimalPart[0] : integerPart;
  }
  var init_getLineHeight = __esm({
    "../../../../../../../packages/elements/src/Text/utils/styles/getLineHeight.ts"() {
      "use strict";
    }
  });

  // ../../../../../../../packages/elements/src/Text/models/Text/utils/stylesToClasses.ts
  var stylesToClasses;
  var init_stylesToClasses = __esm({
    "../../../../../../../packages/elements/src/Text/models/Text/utils/stylesToClasses.ts"() {
      "use strict";
      init_common();
      init_getLetterSpacing();
      init_getLineHeight();
      init_number();
      stylesToClasses = (styles, families, defaultFamily) => {
        const classes = [];
        Object.entries(styles).forEach(([key, value]) => {
          switch (key) {
            case "font-size": {
              const size = Math.round(readInt(value) ?? 1);
              classes.push(`brz-fs-lg-${size}`);
              break;
            }
            case "font-family": {
              const fontFamily = `${value}`.replace(/['"\,]/g, "").replace(/\s/g, "_").toLocaleLowerCase();
              if (!families[fontFamily]) {
                classes.push(`brz-ff-${defaultFamily}`, "brz-ft-upload");
                break;
              }
              classes.push(`brz-ff-${families[fontFamily]}`, "brz-ft-upload");
              break;
            }
            case "font-weight": {
              classes.push(`brz-fw-lg-${value}`);
              break;
            }
            case "text-align": {
              classes.push(`brz-text-lg-${textAlign[value] || "left"}`);
              break;
            }
            case "letter-spacing": {
              const letterSpacing = getLetterSpacing(`${value}`);
              classes.push(`brz-ls-lg-${letterSpacing}`);
              break;
            }
            case "line-height": {
              const fs = `${styles["font-size"]}`;
              const fontSize = fs.replace("px", "");
              const size = Math.round(readInt(value) ?? 1);
              const lineHeight = getLineHeight(`${size}`, fontSize);
              classes.push(`brz-lh-lg-${lineHeight}`);
              classes.push(`brz-lh-sm-${defaultTabletLineHeight}`);
              classes.push(`brz-lh-xs-${defaultMobileLineHeight}`);
              break;
            }
            default:
              break;
          }
        });
        return classes;
      };
    }
  });

  // ../../../../../../../packages/elements/src/Text/models/Text/index.ts
  var getTextModel;
  var init_Text = __esm({
    "../../../../../../../packages/elements/src/Text/models/Text/index.ts"() {
      "use strict";
      init_Wrapper();
      init_addMarginsToLists();
      init_removeAllStylesFromHTML();
      init_removeEmptyNodes();
      init_transformDivsToParagraphs();
      init_copyParentColorToChild();
      init_getTypographyStyles();
      init_stylesToClasses();
      init_uuid();
      getTextModel = (data) => {
        const { node: _node, families, defaultFamily } = data;
        let node = _node;
        node = transformDivsToParagraphs(node);
        node = copyParentColorToChild(node);
        const styles = getTypographyStyles(node);
        node = addMarginsToLists(node);
        node = removeAllStylesFromHTML(node);
        styles.map((style) => {
          const classes = stylesToClasses(style.styles, families, defaultFamily);
          const styleNode = node.querySelector(`[data-uid='${style.uid}']`);
          if (styleNode) {
            styleNode.classList.add(...classes);
            styleNode.removeAttribute("data-uid");
          }
        });
        node = removeEmptyNodes(node);
        const text = node.innerHTML;
        return createWrapperModel({
          _styles: ["wrapper", "wrapper--richText"],
          items: [
            {
              type: "RichText",
              value: {
                _id: uuid(),
                _styles: ["richText"],
                text
              }
            }
          ]
        });
      };
    }
  });

  // ../../../../../../../packages/elements/src/Text/utils/dom/getContainerStackWithNodes.ts
  function appendNodeStyles(node, targetNode) {
    const styles = window.getComputedStyle(node);
    extractedAttributes.forEach((style) => {
      targetNode.style.setProperty(style, styles.getPropertyValue(style));
    });
  }
  function removeNestedDivs(node, cssText) {
    Array.from(node.childNodes).forEach((child) => {
      if (child instanceof HTMLElement && child.nodeName === "DIV") {
        removeNestedDivs(child, cssText);
        const tagsToFlatten = ["DIV", "P"];
        const hasDivOrPChildren = Array.from(child.children).find(
          (node2) => tagsToFlatten.includes(node2.nodeName)
        );
        if (!hasDivOrPChildren)
          return;
        Array.from(child.childNodes).forEach((grandchild) => {
          if (grandchild instanceof HTMLElement) {
            appendNodeStyles(grandchild, grandchild);
            node.insertBefore(grandchild, child);
          } else if (grandchild.textContent?.trim()) {
            const containerOfNode = document.createElement("div");
            appendNodeStyles(child, containerOfNode);
            containerOfNode.append(grandchild);
            node.insertBefore(containerOfNode, child);
          }
        });
        node.removeChild(child);
      }
    });
  }
  var Stack, extractInnerText, flattenNode, getContainerStackWithNodes;
  var init_getContainerStackWithNodes = __esm({
    "../../../../../../../packages/elements/src/Text/utils/dom/getContainerStackWithNodes.ts"() {
      "use strict";
      init_common();
      Stack = class {
        collection = [];
        append(node, attr) {
          const div = document.createElement("div");
          div.append(node);
          if (attr) {
            Object.entries(attr).forEach(([name, value]) => {
              div.setAttribute(`data-${name}`, value);
            });
          }
          this.collection.push(div);
        }
        set(node, attr) {
          const colLength = this.collection.length;
          if (colLength === 0) {
            this.append(node, attr);
          } else {
            const lastCollection = this.collection[colLength - 1];
            lastCollection.append(node);
          }
        }
        getAll() {
          return this.collection;
        }
      };
      extractInnerText = (node, stack, selector) => {
        const _node = node.cloneNode(true);
        if (_node instanceof HTMLElement) {
          const innerElements = _node.querySelectorAll(selector);
          if (innerElements.length > 0) {
            innerElements.forEach((el) => {
              el.remove();
            });
          }
          const text = _node.textContent;
          if (text && text.trim()) {
            stack.append(_node, { type: "text" });
          }
        }
      };
      flattenNode = (node) => {
        const _node = node.cloneNode(true);
        node.parentElement?.append(_node);
        removeNestedDivs(_node, _node.style.cssText);
        _node.remove();
        return _node;
      };
      getContainerStackWithNodes = (node) => {
        const container = document.createElement("div");
        const stack = new Stack();
        let appendNewText = false;
        const flatNode = flattenNode(node);
        flatNode.childNodes.forEach((node2) => {
          const _node = node2.cloneNode(true);
          const containerOfNode = document.createElement("div");
          containerOfNode.append(_node);
          const excludeIcons = _node instanceof HTMLOListElement || _node instanceof HTMLUListElement;
          if (_node instanceof HTMLElement) {
            const icons = containerOfNode.querySelectorAll(iconSelector);
            const buttons = containerOfNode.querySelectorAll(buttonSelector);
            if (excludeIcons) {
              icons.forEach((node3) => {
                node3.remove();
              });
              buttons.forEach((node3) => {
                node3.remove();
              });
            } else {
              if (buttons.length > 0) {
                appendNewText = true;
                let appendedButton = false;
                _node.childNodes.forEach((node3) => {
                  if (node3 instanceof HTMLElement) {
                    const container2 = document.createElement("div");
                    container2.append(node3.cloneNode(true));
                    if (container2.querySelector(buttonSelector)) {
                      if (!appendedButton) {
                        stack.append(_node, { type: "button" });
                        appendedButton = true;
                      }
                    } else {
                      const text = node3.textContent;
                      if (text?.trim()) {
                        extractInnerText(_node, stack, buttonSelector);
                      }
                    }
                  } else {
                    const text = node3.textContent;
                    if (text?.trim()) {
                      extractInnerText(_node, stack, buttonSelector);
                    }
                  }
                });
                return;
              }
              if (icons.length > 0) {
                appendNewText = true;
                let appendedIcon = false;
                Array.from(_node.childNodes).forEach((node3) => {
                  if (node3 instanceof HTMLElement) {
                    const container2 = document.createElement("div");
                    container2.append(node3.cloneNode(true));
                    if (container2.querySelector(iconSelector)) {
                      if (appendedIcon) {
                        stack.set(node3);
                      } else {
                        stack.append(node3, { type: "icon" });
                        appendedIcon = true;
                      }
                    } else {
                      const text = node3.textContent;
                      if (text?.trim()) {
                        extractInnerText(_node, stack, iconSelector);
                        appendedIcon = false;
                      }
                    }
                  } else {
                    const text = node3.textContent;
                    if (text?.trim()) {
                      extractInnerText(_node, stack, iconSelector);
                    }
                  }
                });
                return;
              }
            }
            if (containerOfNode.querySelector(embedSelector)) {
              appendNewText = true;
              extractInnerText(_node, stack, embedSelector);
              stack.append(_node, { type: "embed" });
              return;
            }
            if (appendNewText) {
              appendNewText = false;
              stack.append(_node, { type: "text" });
            } else {
              stack.set(_node, { type: "text" });
            }
          } else {
            stack.append(_node, { type: "text" });
          }
        });
        const allElements = stack.getAll();
        allElements.forEach((node2) => {
          container.append(node2);
        });
        node.parentElement?.append(container);
        const destroy = () => {
          container.remove();
        };
        return { container, destroy };
      };
    }
  });

  // ../../../../../../../packages/elements/src/Text/index.ts
  var getText2;
  var init_Text2 = __esm({
    "../../../../../../../packages/elements/src/Text/index.ts"() {
      "use strict";
      init_getData();
      init_getDataByEntry();
      init_Button();
      init_Embed();
      init_Icon();
      init_Text();
      init_getContainerStackWithNodes();
      getText2 = (_entry) => {
        const entry = window.isDev ? getDataByEntry(_entry) : _entry;
        const { selector } = entry;
        let node = selector ? document.querySelector(selector) : void 0;
        if (!node) {
          return {
            error: `Element with selector ${selector} not found`
          };
        }
        node = node.children[0];
        if (!node) {
          return {
            error: `Element with selector ${entry.selector} has no wrapper`
          };
        }
        const data = [];
        const { container, destroy } = getContainerStackWithNodes(node);
        const containerChildren = Array.from(container.children);
        containerChildren.forEach((node2) => {
          if (node2 instanceof HTMLElement) {
            switch (node2.dataset.type) {
              case "text": {
                const model = getTextModel({ ...entry, node: node2 });
                data.push(model);
                break;
              }
              case "button": {
                const models = getButtonModel(node2);
                data.push(...models);
                break;
              }
              case "embed": {
                const models = getEmbedModel(node2);
                data.push(...models);
                break;
              }
              case "icon": {
                const models = getIconModel(node2);
                data.push(...models);
                break;
              }
            }
          }
        });
        destroy();
        return createData({ data });
      };
    }
  });

  // src/Text/index.ts
  var init_Text3 = __esm({
    "src/Text/index.ts"() {
      "use strict";
      init_Text2();
    }
  });

  // src/index.ts
  var require_src = __commonJS({
    "src/index.ts"() {
      init_Accordion2();
      init_GlobalMenu();
      init_Image2();
      init_Menu();
      init_StyleExtractor2();
      init_Tabs2();
      init_Text3();
      window.brizy = {
        globalMenuExtractor: run,
        getMenu: run2,
        getStyles: styleExtractor,
        getText: getText2,
        getImage,
        getAccordion,
        getTabs
      };
    }
  });
  require_src();
})();
