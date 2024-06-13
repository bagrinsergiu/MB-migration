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
          sectionSelector,
          attributeNames
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
          ...sectionSelector ? { sectionSelector: "" } : {},
          ...attributeNames ? { attributeNames: [""] } : {},
          urlMap: {}
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

  // ../../../../../../../packages/elements/src/Dom/detectSubpalette.ts
  var subpalettes, detectSubpalette;
  var init_detectSubpalette = __esm({
    "../../../../../../../packages/elements/src/Dom/detectSubpalette.ts"() {
      "use strict";
      subpalettes = [
        "subpalette1",
        "subpalette2",
        "subpalette3",
        "subpalette4"
      ];
      detectSubpalette = (entry) => {
        const { selector } = entry;
        if (!selector) {
          return {
            error: "Selector not found"
          };
        }
        const element = document.querySelector(selector);
        if (element) {
          for (const subpalette of subpalettes) {
            if (element.classList.contains(subpalette)) {
              return {
                data: subpalette
              };
            }
          }
          return {
            data: false
          };
        }
        return {
          data: false
        };
      };
    }
  });

  // ../../../../../../../packages/elements/src/Dom/getNodeText.ts
  var getNodeText;
  var init_getNodeText = __esm({
    "../../../../../../../packages/elements/src/Dom/getNodeText.ts"() {
      "use strict";
      init_getData();
      getNodeText = (entry) => {
        const { selector } = entry;
        if (!selector) {
          return {
            error: "Selector not found"
          };
        }
        const element = document.querySelector(selector);
        if (element) {
          const data = {
            contain: element.textContent
          };
          return createData({ data });
        }
        return {
          error: "Selector not found"
        };
      };
    }
  });

  // ../../../../../../../packages/elements/src/Dom/getRootPropertyStyles.ts
  var getRootPropertyStyles;
  var init_getRootPropertyStyles = __esm({
    "../../../../../../../packages/elements/src/Dom/getRootPropertyStyles.ts"() {
      "use strict";
      init_getData();
      getRootPropertyStyles = () => {
        const data = {};
        const styleSheets = document.styleSheets;
        for (let i = 0; i < styleSheets.length; i++) {
          const styleSheet = styleSheets[i];
          if (!styleSheet.href) {
            const cssRules = styleSheet.cssRules || styleSheet.rules;
            for (let j = 0; j < cssRules.length; j++) {
              const rule = cssRules[j];
              if (rule.selectorText === ":root") {
                const declarations = rule.style;
                for (let k = 0; k < declarations.length; k++) {
                  const property = declarations[k];
                  const value = declarations.getPropertyValue(property);
                  data[property] = value;
                }
              }
            }
          }
        }
        return createData({ data });
      };
    }
  });

  // ../../../../../../../packages/elements/src/Dom/hasNode.ts
  var hasNode;
  var init_hasNode = __esm({
    "../../../../../../../packages/elements/src/Dom/hasNode.ts"() {
      "use strict";
      init_getData();
      hasNode = (entry) => {
        const { selector } = entry;
        if (!selector) {
          return {
            error: "Selector not found"
          };
        }
        const data = {
          hasNode: !!document.querySelector(selector)
        };
        return createData({ data });
      };
    }
  });

  // src/Dom/index.ts
  var Dom;
  var init_Dom = __esm({
    "src/Dom/index.ts"() {
      "use strict";
      init_detectSubpalette();
      init_getNodeText();
      init_getRootPropertyStyles();
      init_hasNode();
      Dom = {
        hasNode,
        getNodeText,
        getRootPropertyStyles,
        detectSubpalette
      };
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

  // ../../../../../../../packages/utils/src/text/capByPrefix.ts
  var capByPrefix;
  var init_capByPrefix = __esm({
    "../../../../../../../packages/utils/src/text/capByPrefix.ts"() {
      "use strict";
      init_capitalize();
      capByPrefix = (p, s) => p === "" ? s : p + "-" + capitalize(s);
    }
  });

  // ../../../../../../../packages/elements/src/Menu/utils/dicKeyForDevices.ts
  var dicKeyForDevices;
  var init_dicKeyForDevices = __esm({
    "../../../../../../../packages/elements/src/Menu/utils/dicKeyForDevices.ts"() {
      "use strict";
      init_capByPrefix();
      init_toCamelCase();
      dicKeyForDevices = (key, value) => {
        return {
          [toCamelCase(key)]: value,
          [toCamelCase(capByPrefix("mobile", key))]: value,
          [toCamelCase(capByPrefix("tablet", key))]: value
        };
      };
    }
  });

  // src/Menu/utils/getModel.ts
  var v2, getModel2;
  var init_getModel2 = __esm({
    "src/Menu/utils/getModel.ts"() {
      "use strict";
      init_dicKeyForDevices();
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
                const letterSpacingValue = `${value}`.replace(/px/g, "").trim();
                Object.assign(dic, dicKeyForDevices(key, +letterSpacingValue));
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
          const prefixes = ["active", "mobile", "tablet"];
          const matchedPrefix = prefixes.find((prefix2) => key.startsWith(prefix2));
          if (matchedPrefix) {
            _key = `${matchedPrefix}${capitalize(prefix)}${key.replace(
              `${matchedPrefix}`,
              ""
            )}`;
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
        const mMenu = prefixed(v4, "mMenu");
        const globalModel = getGlobalMenuModel();
        return {
          ...mMenu,
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
  var styleExtractor, attributesExtractor;
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
      attributesExtractor = (_entry) => {
        const entry = window.isDev ? getDataByEntry(_entry) : _entry;
        const { selector, attributeNames = [] } = entry;
        const data = {};
        const element = selector ? document.querySelector(selector) : void 0;
        if (!element) {
          return {
            error: `Element with selector ${selector} not found`
          };
        }
        attributeNames.forEach((attr) => {
          data[attr] = element.getAttribute(attr);
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
  var import_fp_utilities, allowedTags, exceptExtractingStyle, defaultDesktopLineHeight, defaultTabletLineHeight, defaultMobileLineHeight, extractedAttributes, textAlign, iconSelector, buttonSelector, embedSelector, extractUrlWithoutDomain, getHref, getTarget, normalizeOpacity, encodeToString;
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
      defaultDesktopLineHeight = "1_3";
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
      encodeToString = (value) => {
        return encodeURIComponent(JSON.stringify(value));
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

  // ../../../../../../../packages/elements/src/Text/models/Icon/utils/iconMapping.ts
  var defaultIcon, codeToBuilderMap;
  var init_iconMapping = __esm({
    "../../../../../../../packages/elements/src/Text/models/Icon/utils/iconMapping.ts"() {
      "use strict";
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
  function getModel4(node, urlMap) {
    const parentNode = getParentElementOfTextNode(node);
    const isIconText = parentNode?.nodeName === "#text";
    const iconNode = isIconText ? node : parentNode;
    const modelStyle = getStyleModel(node);
    const iconCode = iconNode?.textContent?.charCodeAt(0);
    const globalModel = getGlobalIconModel();
    const parentElement = node.parentElement;
    const isLink = parentElement?.tagName === "A" || node.tagName === "A";
    const href = getHref(parentElement) ?? getHref(node) ?? "";
    const mappedHref = href && urlMap[href] !== void 0 ? urlMap[href] : href;
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
          linkExternal: mappedHref,
          linkType: "external",
          linkExternalBlank: "on"
        }
      }
    };
  }
  var import_fp_utilities2, getColor, getBgColor, getStyles, getParentStyles, getStyleModel;
  var init_getModel4 = __esm({
    "../../../../../../../packages/elements/src/Text/models/Icon/utils/getModel.ts"() {
      "use strict";
      init_getGlobalIconModel();
      init_common();
      init_iconMapping();
      import_fp_utilities2 = __toESM(require_dist());
      init_parseColorString();
      init_getNodeStyle();
      init_getParentElementOfTextNode();
      init_object();
      init_string();
      init_uuid();
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
        const bgColor = getBgColor(parentStyle);
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
            colorPalette: "",
            hoverColorHex: normalizeOpacity({
              hex: color.hex,
              opacity: color.opacity ?? String(opacity)
            }).hex,
            hoverColorOpacity: 0.8,
            hoverColorPalette: ""
          },
          ...bgColor && {
            bgColorHex: bgColor.hex,
            bgColorOpacity: bgColor.opacity,
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
      getModel5 = (node, urlMap) => {
        let iconModel = {};
        const isLink = node.tagName === "A";
        const modelStyle = getStyleModel2(node);
        const globalModel = getGlobalButtonModel();
        const textTransform = getTransform(getNodeStyle(node));
        const icon = node.querySelector(iconSelector);
        if (icon) {
          const model = getModel4(icon, urlMap);
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
        const href = getHref(node);
        const mappedHref = href && urlMap[href] !== void 0 ? urlMap[href] : href;
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
              linkExternal: mappedHref,
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
  function getButtonModel(node, urlMap) {
    const buttons = node.querySelectorAll(buttonSelector);
    const groups = /* @__PURE__ */ new Map();
    buttons.forEach((button) => {
      const parentElement = findNearestBlockParent(button);
      const style = getNodeStyle(button);
      const model = getModel5(button, urlMap);
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
  function getIconModel(node, urlMap) {
    const icons = node.querySelectorAll(iconSelector);
    const groups = /* @__PURE__ */ new Map();
    icons.forEach((icon) => {
      const parentElement = findNearestBlockParent(icon);
      const parentNode = getParentElementOfTextNode(node);
      const isIconText = parentNode?.nodeName === "#text";
      const iconNode = isIconText ? node : parentNode;
      const style = iconNode ? getNodeStyle(iconNode) : {};
      const model = getModel4(icon, urlMap);
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
        const validStyles = ["font-weight", "color", "background-color"];
        const hasProperty = validStyles.some(
          (style) => property.startsWith(style)
        );
        if (hasProperty) {
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
    const children = Array.from(node.children);
    children.forEach((child) => {
      const text = child.textContent;
      if (text && text.includes("\n") && !text.trim()) {
        child.remove();
      }
    });
    node.innerHTML = node.innerHTML.replace(/\n/g, " ");
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
      if (computedStyles.backgroundColor) {
        innerElement.style.backgroundColor = computedStyles.backgroundColor;
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

  // ../../../../../../../packages/elements/src/Text/utils/styles/encodeLinks.ts
  function encodeLinks(node, urlMap) {
    const links = Array.from(node.querySelectorAll("a"));
    links.map((link) => {
      const href = getHref(link);
      const mappedHref = href && urlMap[href] !== void 0 ? urlMap[href] : href;
      const target = getTarget(link);
      const targetType = target === "_self" ? "off" : "on";
      link.dataset.href = encodeToString({
        type: "external",
        anchor: "",
        external: mappedHref,
        externalBlank: targetType,
        externalRel: "off",
        externalType: "external",
        population: "",
        populationEntityId: "",
        populationEntityType: "",
        popup: "",
        upload: "",
        linkToSlide: 1,
        internal: "",
        internalBlank: "off",
        pageTitle: "",
        pageSource: null
      });
      link.removeAttribute("href");
    });
    return node;
  }
  var init_encodeLinks = __esm({
    "../../../../../../../packages/elements/src/Text/utils/styles/encodeLinks.ts"() {
      "use strict";
      init_common();
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

  // ../../../../../../../packages/elements/src/Text/models/Text/utils/stylesToClasses.ts
  var stylesToClasses;
  var init_stylesToClasses = __esm({
    "../../../../../../../packages/elements/src/Text/models/Text/utils/stylesToClasses.ts"() {
      "use strict";
      init_common();
      init_getLetterSpacing();
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
              classes.push(`brz-lh-lg-${defaultDesktopLineHeight}`);
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
      init_encodeLinks();
      init_getTypographyStyles();
      init_stylesToClasses();
      init_uuid();
      getTextModel = (data) => {
        const { node: _node, families, defaultFamily, urlMap } = data;
        let node = _node;
        node = transformDivsToParagraphs(node);
        node = copyParentColorToChild(node);
        node = encodeLinks(node, urlMap);
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
  function removeNestedDivs(node) {
    const embeddedPasteExists = node.querySelectorAll(embedSelector).length > 0;
    if (!embeddedPasteExists) {
      Array.from(node.childNodes).forEach((child) => {
        if (child instanceof HTMLElement && (child.nodeName === "DIV" || child.nodeName === "CENTER")) {
          removeNestedDivs(child);
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
        removeNestedDivs(_node);
        _node.remove();
        return _node;
      };
      getContainerStackWithNodes = (parentNode) => {
        const container = document.createElement("div");
        const stack = new Stack();
        let appendNewText = false;
        const flatNode = flattenNode(parentNode);
        flatNode.childNodes.forEach((node) => {
          const _node = node.cloneNode(true);
          const containerOfNode = document.createElement("div");
          containerOfNode.append(_node);
          const excludeIcons = _node instanceof HTMLOListElement || _node instanceof HTMLUListElement;
          if (_node instanceof HTMLElement) {
            const icons = containerOfNode.querySelectorAll(iconSelector);
            const buttons = containerOfNode.querySelectorAll(buttonSelector);
            if (excludeIcons) {
              icons.forEach((node2) => {
                node2.remove();
              });
              buttons.forEach((node2) => {
                node2.remove();
              });
            } else {
              if (buttons.length > 0) {
                const container2 = document.createElement("div");
                container2.innerHTML = _node.innerHTML;
                const innerButtons = container2.querySelectorAll(buttonSelector);
                innerButtons.forEach((btn) => btn.remove());
                const onlyButtons = (container2.textContent?.trim() ?? "").length === 0;
                if (onlyButtons) {
                  appendNewText = true;
                  let appendedButton = false;
                  parentNode.parentElement?.append(_node);
                  _node.childNodes.forEach((node2) => {
                    if (node2 instanceof HTMLElement) {
                      const container3 = document.createElement("div");
                      container3.append(node2.cloneNode(true));
                      appendNodeStyles(node2, node2);
                      if (container3.querySelector(buttonSelector)) {
                        if (appendedButton) {
                          stack.set(node2);
                        } else {
                          stack.append(node2, { type: "button" });
                          appendedButton = true;
                        }
                      } else {
                        const text = node2.textContent;
                        if (text?.trim()) {
                          extractInnerText(node2, stack, buttonSelector);
                          appendedButton = false;
                        }
                      }
                    } else {
                      const text = node2.textContent;
                      if (text?.trim()) {
                        extractInnerText(_node, stack, buttonSelector);
                      }
                    }
                  });
                  _node.remove();
                  return;
                }
                _node.remove();
              }
              if (icons.length > 0) {
                appendNewText = true;
                let appendedIcon = false;
                Array.from(_node.childNodes).forEach((node2) => {
                  if (node2 instanceof HTMLElement) {
                    const container2 = document.createElement("div");
                    container2.append(node2.cloneNode(true));
                    if (container2.querySelector(iconSelector)) {
                      if (appendedIcon) {
                        stack.set(node2);
                      } else {
                        stack.append(node2, { type: "icon" });
                        appendedIcon = true;
                      }
                    } else {
                      const text = node2.textContent;
                      if (text?.trim()) {
                        extractInnerText(node2, stack, iconSelector);
                        appendedIcon = false;
                      }
                    }
                  } else {
                    const text = node2.textContent;
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
        allElements.forEach((node) => {
          container.append(node);
        });
        parentNode.parentElement?.append(container);
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
                const models = getButtonModel(node2, entry.urlMap);
                data.push(...models);
                break;
              }
              case "embed": {
                const models = getEmbedModel(node2);
                data.push(...models);
                break;
              }
              case "icon": {
                const models = getIconModel(node2, entry.urlMap);
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
      init_Dom();
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
        getAttributes: attributesExtractor,
        getText: getText2,
        getImage,
        getAccordion,
        getTabs,
        dom: Dom
      };
    }
  });
  require_src();
})();
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL3V0aWxzL2dldERhdGFCeUVudHJ5LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9jb2xvci9wYXJzZUNvbG9yU3RyaW5nLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy90ZXh0L2NhcGl0YWxpemUudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL3RleHQvdG9DYW1lbENhc2UudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL3V0aWxzL2dldE1vZGVsLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy91dGlscy9nZXREYXRhLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9yZWFkZXIvbnVtYmVyLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9BY2NvcmRpb24vaW5kZXgudHMiLCAiLi4vc3JjL0FjY29yZGlvbi9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvRG9tL2RldGVjdFN1YnBhbGV0dGUudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL0RvbS9nZXROb2RlVGV4dC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvRG9tL2dldFJvb3RQcm9wZXJ0eVN0eWxlcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvRG9tL2hhc05vZGUudHMiLCAiLi4vc3JjL0RvbS9pbmRleC50cyIsICIuLi9zcmMvR2xvYmFsTWVudS9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvSW1hZ2UvaW5kZXgudHMiLCAiLi4vc3JjL0ltYWdlL2luZGV4LnRzIiwgIi4uL3NyYy91dGlscy9nZXRHbG9iYWxNZW51TW9kZWwudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL3RleHQvY2FwQnlQcmVmaXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL01lbnUvdXRpbHMvZGljS2V5Rm9yRGV2aWNlcy50cyIsICIuLi9zcmMvTWVudS91dGlscy9nZXRNb2RlbC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvbW9kZWxzL3ByZWZpeGVkLnRzIiwgIi4uL3NyYy9NZW51L2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9TdHlsZUV4dHJhY3Rvci9pbmRleC50cyIsICIuLi9zcmMvU3R5bGVFeHRyYWN0b3IvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RhYnMvdXRpbHMvZ2V0TW9kZWwudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RhYnMvaW5kZXgudHMiLCAiLi4vc3JjL1RhYnMvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL25hbm9pZC9pbmRleC5icm93c2VyLmpzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy91dWlkLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9Nb2RlbHMvQ2xvbmVhYmxlL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9mcC11dGlsaXRpZXMvZGlzdC9saWZ0QTIuanMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2ZwLXV0aWxpdGllcy9kaXN0L21hdGNoLmpzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9mcC11dGlsaXRpZXMvZGlzdC9tYXRjaDIuanMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2ZwLXV0aWxpdGllcy9kaXN0L05vdGhpbmcuanMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2ZwLXV0aWxpdGllcy9kaXN0L21QaXBlLmpzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9mcC11dGlsaXRpZXMvZGlzdC9wYXNzLmpzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9mcC11dGlsaXRpZXMvZGlzdC9wYXJzZXJzL2ludGVybmFscy5qcyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvZnAtdXRpbGl0aWVzL2Rpc3QvcGFyc2Vycy9wYXJzZS5qcyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvZnAtdXRpbGl0aWVzL2Rpc3QvcGFyc2Vycy9wYXJzZVN0cmljdC5qcyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvZnAtdXRpbGl0aWVzL2Rpc3Qvb3IuanMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2ZwLXV0aWxpdGllcy9kaXN0L2luZGV4LmpzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9yZWFkZXIvb2JqZWN0LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9yZWFkZXIvc3RyaW5nLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2NvbW1vbi9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvdXRpbHMvZ2V0R2xvYmFsQnV0dG9uTW9kZWwudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL3V0aWxzL2dldEdsb2JhbEljb25Nb2RlbC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvSWNvbi91dGlscy9pY29uTWFwcGluZy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvZG9tL2dldFBhcmVudEVsZW1lbnRPZlRleHROb2RlLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L21vZGVscy9JY29uL3V0aWxzL2dldE1vZGVsLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9mcC9waXBlLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9pc051bGxpc2gudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL29uTnVsbGlzaC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvQnV0dG9uL3V0aWxzL2dldE1vZGVsLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vZmluZE5lYXJlc3RCbG9ja1BhcmVudC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvQnV0dG9uL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L21vZGVscy9FbWJlZC9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvSWNvbi9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvTW9kZWxzL1dyYXBwZXIvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2FkZE1hcmdpbnNUb0xpc3RzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2RvbS9jbGVhbkNsYXNzTmFtZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvZG9tL3JlbW92ZUFsbFN0eWxlc0Zyb21IVE1MLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2RvbS9yZW1vdmVFbXB0eU5vZGVzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2RvbS90cmFuc2Zvcm1EaXZzVG9QYXJhZ3JhcGhzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL3N0eWxlcy9jb3B5UGFyZW50Q29sb3JUb0NoaWxkLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL3N0eWxlcy9lbmNvZGVMaW5rcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvZG9tL3JlY3Vyc2l2ZUdldE5vZGVzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vZXh0cmFjdEFsbEVsZW1lbnRzU3R5bGVzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL3N0eWxlcy9tZXJnZVN0eWxlcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC91dGlscy9kb20vZXh0cmFjdFBhcmVudEVsZW1lbnRzV2l0aFN0eWxlcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC91dGlscy9zdHlsZXMvZ2V0VHlwb2dyYXBoeVN0eWxlcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC91dGlscy9zdHlsZXMvZ2V0TGV0dGVyU3BhY2luZy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvVGV4dC91dGlscy9zdHlsZXNUb0NsYXNzZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvbW9kZWxzL1RleHQvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvZG9tL2dldENvbnRhaW5lclN0YWNrV2l0aE5vZGVzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L2luZGV4LnRzIiwgIi4uL3NyYy9UZXh0L2luZGV4LnRzIiwgIi4uL3NyYy9pbmRleC50cyJdLAogICJzb3VyY2VzQ29udGVudCI6IFsiaW50ZXJmYWNlIE91dHB1dCB7XG4gIGZhbWlsaWVzOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+O1xuICBkZWZhdWx0RmFtaWx5OiBzdHJpbmc7XG4gIHNlbGVjdG9yPzogc3RyaW5nO1xuICBpdGVtU2VsZWN0b3I/OiBzdHJpbmc7XG4gIHN1Ykl0ZW1TZWxlY3Rvcj86IHN0cmluZztcbiAgc2VjdGlvblNlbGVjdG9yPzogc3RyaW5nO1xuICBzdHlsZVByb3BlcnRpZXM/OiBzdHJpbmdbXTtcbiAgbGlzdD86IEVsZW1lbnQgfCB1bmRlZmluZWQ7XG4gIG5hdj86IEVsZW1lbnQgfCB1bmRlZmluZWQ7XG4gIHVybE1hcDogUmVjb3JkPHN0cmluZywgc3RyaW5nPjtcbiAgYXR0cmlidXRlTmFtZXM/OiBzdHJpbmdbXTtcbn1cblxuZXhwb3J0IGNvbnN0IGdldERhdGFCeUVudHJ5ID0gKGlucHV0OiBPdXRwdXQpOiBPdXRwdXQgPT4ge1xuICBjb25zdCB7XG4gICAgc3R5bGVQcm9wZXJ0aWVzLFxuICAgIGxpc3QsXG4gICAgbmF2LFxuICAgIHNlbGVjdG9yLFxuICAgIGl0ZW1TZWxlY3RvcixcbiAgICBzdWJJdGVtU2VsZWN0b3IsXG4gICAgc2VjdGlvblNlbGVjdG9yLFxuICAgIGF0dHJpYnV0ZU5hbWVzXG4gIH0gPSBpbnB1dCA/PyB7fTtcblxuICByZXR1cm4gd2luZG93LmlzRGV2XG4gICAgPyB7XG4gICAgICAgIGZhbWlsaWVzOiB7fSxcbiAgICAgICAgZGVmYXVsdEZhbWlseTogXCJsYXRvXCIsXG4gICAgICAgIC4uLihzdHlsZVByb3BlcnRpZXMgPyB7IHN0eWxlUHJvcGVydGllczogW1wiXCJdIH0gOiB7fSksXG4gICAgICAgIC4uLihzZWxlY3RvciA/IHsgc2VsZWN0b3I6IGBbZGF0YS1pZD1cIiR7d2luZG93LmVsZW1lbnRJZH1cIl1gIH0gOiB7fSksXG4gICAgICAgIC4uLihsaXN0ID8geyBsaXN0OiB1bmRlZmluZWQgfSA6IHt9KSxcbiAgICAgICAgLi4uKG5hdiA/IHsgbmF2OiB1bmRlZmluZWQgfSA6IHt9KSxcbiAgICAgICAgLi4uKGl0ZW1TZWxlY3RvciA/IHsgaXRlbVNlbGVjdG9yOiBcIlwiIH0gOiB7fSksXG4gICAgICAgIC4uLihzdWJJdGVtU2VsZWN0b3IgPyB7IHN1Ykl0ZW1TZWxlY3RvcjogXCJcIiB9IDoge30pLFxuICAgICAgICAuLi4oc2VjdGlvblNlbGVjdG9yID8geyBzZWN0aW9uU2VsZWN0b3I6IFwiXCIgfSA6IHt9KSxcbiAgICAgICAgLi4uKGF0dHJpYnV0ZU5hbWVzID8geyBhdHRyaWJ1dGVOYW1lczogW1wiXCJdIH0gOiB7fSksXG4gICAgICAgIHVybE1hcDoge31cbiAgICAgIH1cbiAgICA6IGlucHV0O1xufTtcbiIsICJpbXBvcnQgeyBNVmFsdWUgfSBmcm9tIFwiLi4vdHlwZXNcIjtcblxuZXhwb3J0IGludGVyZmFjZSBDb2xvciB7XG4gIGhleDogc3RyaW5nO1xuICBvcGFjaXR5Pzogc3RyaW5nO1xufVxuXG5jb25zdCBoZXhSZWdleCA9IC9eIyg/OltBLUZhLWYwLTldezN9KXsxLDJ9JC87XG5jb25zdCByZ2JSZWdleCA9IC9ecmdiXFxzKlsoXVxccyooXFxkKylcXHMqLFxccyooXFxkKylcXHMqLFxccyooXFxkKylcXHMqWyldJC87XG5jb25zdCByZ2JhUmVnZXggPVxuICAvXnJnYmFcXHMqWyhdXFxzKihcXGQrKVxccyosXFxzKihcXGQrKVxccyosXFxzKihcXGQrKVxccyosXFxzKigwKig/OlxcLlxcZCspP3wxKD86XFwuMCopPylcXHMqWyldJC87XG5cbmNvbnN0IGlzSGV4ID0gKHY6IHN0cmluZyk6IGJvb2xlYW4gPT4gaGV4UmVnZXgudGVzdCh2KTtcblxuY29uc3QgZnJvbVJnYiA9IChyZ2I6IFtudW1iZXIsIG51bWJlciwgbnVtYmVyXSk6IHN0cmluZyA9PiB7XG4gIHJldHVybiAoXG4gICAgXCIjXCIgK1xuICAgIChcIjBcIiArIHJnYlswXS50b1N0cmluZygxNikpLnNsaWNlKC0yKSArXG4gICAgKFwiMFwiICsgcmdiWzFdLnRvU3RyaW5nKDE2KSkuc2xpY2UoLTIpICtcbiAgICAoXCIwXCIgKyByZ2JbMl0udG9TdHJpbmcoMTYpKS5zbGljZSgtMilcbiAgKTtcbn07XG5cbmZ1bmN0aW9uIHBhcnNlUmdiKGNvbG9yOiBzdHJpbmcpOiBNVmFsdWU8W251bWJlciwgbnVtYmVyLCBudW1iZXJdPiB7XG4gIGNvbnN0IG1hdGNoZXMgPSByZ2JSZWdleC5leGVjKGNvbG9yKTtcblxuICBpZiAobWF0Y2hlcykge1xuICAgIGNvbnN0IFtyLCBnLCBiXSA9IG1hdGNoZXMuc2xpY2UoMSkubWFwKE51bWJlcik7XG4gICAgcmV0dXJuIFtyLCBnLCBiXTtcbiAgfVxuXG4gIHJldHVybiB1bmRlZmluZWQ7XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBwYXJzZVJnYmEoXG4gIGNvbG9yOiBzdHJpbmdcbik6IE1WYWx1ZTxbbnVtYmVyLCBudW1iZXIsIG51bWJlciwgbnVtYmVyXT4ge1xuICBjb25zdCBtYXRjaGVzID0gcmdiYVJlZ2V4LmV4ZWMoY29sb3IpO1xuXG4gIGlmIChtYXRjaGVzKSB7XG4gICAgY29uc3QgW3IsIGcsIGIsIGFdID0gbWF0Y2hlcy5zbGljZSgxKS5tYXAoTnVtYmVyKTtcbiAgICByZXR1cm4gW3IsIGcsIGIsIGFdO1xuICB9XG5cbiAgcmV0dXJuIHVuZGVmaW5lZDtcbn1cblxuZXhwb3J0IGZ1bmN0aW9uIHBhcnNlQ29sb3JTdHJpbmcoY29sb3JTdHJpbmc6IHN0cmluZyk6IE1WYWx1ZTxDb2xvcj4ge1xuICBpZiAoaXNIZXgoY29sb3JTdHJpbmcpKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGhleDogY29sb3JTdHJpbmdcbiAgICB9O1xuICB9XG5cbiAgY29uc3QgcmdiUmVzdWx0ID0gcGFyc2VSZ2IoY29sb3JTdHJpbmcpO1xuICBpZiAocmdiUmVzdWx0KSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGhleDogZnJvbVJnYihyZ2JSZXN1bHQpXG4gICAgfTtcbiAgfVxuXG4gIGNvbnN0IHJnYmFSZXN1bHQgPSBwYXJzZVJnYmEoY29sb3JTdHJpbmcpO1xuICBpZiAocmdiYVJlc3VsdCkge1xuICAgIGNvbnN0IFtyLCBnLCBiLCBhXSA9IHJnYmFSZXN1bHQ7XG4gICAgcmV0dXJuIHtcbiAgICAgIGhleDogZnJvbVJnYihbciwgZywgYl0pLFxuICAgICAgb3BhY2l0eTogU3RyaW5nKGEpXG4gICAgfTtcbiAgfVxuXG4gIHJldHVybiB1bmRlZmluZWQ7XG59XG4iLCAiaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCIuLi90eXBlc1wiO1xuXG5leHBvcnQgY29uc3QgZ2V0Tm9kZVN0eWxlID0gKFxuICBub2RlOiBIVE1MRWxlbWVudCB8IEVsZW1lbnQsXG4gIHBzZXVkb0VsPzogc3RyaW5nIHwgbnVsbFxuKTogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4gPT4ge1xuICBjb25zdCBjb21wdXRlZFN0eWxlcyA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKG5vZGUsIHBzZXVkb0VsID8/IFwiXCIpO1xuICBjb25zdCBzdHlsZXM6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+ID0ge307XG5cbiAgT2JqZWN0LnZhbHVlcyhjb21wdXRlZFN0eWxlcykuZm9yRWFjaCgoa2V5KSA9PiB7XG4gICAgc3R5bGVzW2tleV0gPSBjb21wdXRlZFN0eWxlcy5nZXRQcm9wZXJ0eVZhbHVlKGtleSk7XG4gIH0pO1xuXG4gIHJldHVybiBzdHlsZXM7XG59O1xuIiwgImV4cG9ydCBjb25zdCBjYXBpdGFsaXplID0gKHN0cjogc3RyaW5nKTogc3RyaW5nID0+IHtcbiAgcmV0dXJuIHN0ci5jaGFyQXQoMCkudG9VcHBlckNhc2UoKSArIHN0ci5zbGljZSgxKTtcbn07XG4iLCAiaW1wb3J0IHsgY2FwaXRhbGl6ZSB9IGZyb20gXCIuL2NhcGl0YWxpemVcIjtcblxuZXhwb3J0IGNvbnN0IHRvQ2FtZWxDYXNlID0gKGtleTogc3RyaW5nKTogc3RyaW5nID0+IHtcbiAgY29uc3QgcGFydHMgPSBrZXkuc3BsaXQoXCItXCIpO1xuICBmb3IgKGxldCBpID0gMTsgaSA8IHBhcnRzLmxlbmd0aDsgaSsrKSB7XG4gICAgcGFydHNbaV0gPSBjYXBpdGFsaXplKHBhcnRzW2ldKTtcbiAgfVxuICByZXR1cm4gcGFydHMuam9pbihcIlwiKTtcbn07XG4iLCAiaW1wb3J0IHsgcGFyc2VDb2xvclN0cmluZyB9IGZyb20gXCJ1dGlscy9zcmMvY29sb3IvcGFyc2VDb2xvclN0cmluZ1wiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5pbXBvcnQgeyB0b0NhbWVsQ2FzZSB9IGZyb20gXCJ1dGlscy9zcmMvdGV4dC90b0NhbWVsQ2FzZVwiO1xuXG5pbnRlcmZhY2UgTW9kZWwge1xuICBub2RlOiBFbGVtZW50O1xuICBmYW1pbGllczogUmVjb3JkPHN0cmluZywgc3RyaW5nPjtcbiAgZGVmYXVsdEZhbWlseTogc3RyaW5nO1xufVxuXG5jb25zdCB2ID0ge1xuICBcImZvbnQtZmFtaWx5XCI6IHVuZGVmaW5lZCxcbiAgXCJmb250LWZhbWlseS10eXBlXCI6IFwidXBsb2FkZWRcIixcbiAgXCJmb250LXdlaWdodFwiOiB1bmRlZmluZWQsXG4gIFwiZm9udC1zaXplXCI6IHVuZGVmaW5lZCxcbiAgXCJsaW5lLWhlaWdodFwiOiB1bmRlZmluZWQsXG4gIFwibGV0dGVyLXNwYWNpbmdcIjogdW5kZWZpbmVkLFxuICBcImZvbnQtc3R5bGVcIjogXCJcIixcbiAgY29sb3JIZXg6IHVuZGVmaW5lZCxcbiAgY29sb3JPcGFjaXR5OiAxXG59O1xuXG5leHBvcnQgY29uc3QgZ2V0TW9kZWwgPSAoZGF0YTogTW9kZWwpID0+IHtcbiAgY29uc3QgeyBub2RlLCBmYW1pbGllcywgZGVmYXVsdEZhbWlseSB9ID0gZGF0YTtcbiAgY29uc3Qgc3R5bGVzID0gZ2V0Tm9kZVN0eWxlKG5vZGUpO1xuICBjb25zdCBkaWM6IFJlY29yZDxzdHJpbmcsIHN0cmluZyB8IG51bWJlcj4gPSB7fTtcblxuICBPYmplY3Qua2V5cyh2KS5mb3JFYWNoKChrZXkpID0+IHtcbiAgICBzd2l0Y2ggKGtleSkge1xuICAgICAgY2FzZSBcImZvbnQtZmFtaWx5XCI6IHtcbiAgICAgICAgY29uc3QgdmFsdWUgPSBgJHtzdHlsZXNba2V5XX1gO1xuICAgICAgICBjb25zdCBmb250RmFtaWx5ID0gdmFsdWVcbiAgICAgICAgICAucmVwbGFjZSgvWydcIlxcLF0vZywgXCJcIikgLy8gZXNsaW50LWRpc2FibGUtbGluZVxuICAgICAgICAgIC5yZXBsYWNlKC9cXHMvZywgXCJfXCIpXG4gICAgICAgICAgLnRvTG9jYWxlTG93ZXJDYXNlKCk7XG5cbiAgICAgICAgaWYgKCFmYW1pbGllc1tmb250RmFtaWx5XSkge1xuICAgICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IGRlZmF1bHRGYW1pbHk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gZmFtaWxpZXNbZm9udEZhbWlseV07XG4gICAgICAgIH1cbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiZm9udC1mYW1pbHktdHlwZVwiOiB7XG4gICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IFwidXBsb2FkXCI7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImZvbnQtc3R5bGVcIjoge1xuICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSBcIlwiO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJsaW5lLWhlaWdodFwiOiB7XG4gICAgICAgIGNvbnN0IHZhbHVlID0gcGFyc2VJbnQoYCR7c3R5bGVzW2tleV19YCk7XG4gICAgICAgIGlmIChpc05hTih2YWx1ZSkpIHtcbiAgICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSAxO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IHZhbHVlO1xuICAgICAgICB9XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImZvbnQtc2l6ZVwiOiB7XG4gICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IHBhcnNlSW50KGAke3N0eWxlc1trZXldfWApO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJsZXR0ZXItc3BhY2luZ1wiOiB7XG4gICAgICAgIGNvbnN0IHZhbHVlID0gc3R5bGVzW2tleV07XG4gICAgICAgIGlmICh2YWx1ZSA9PT0gXCJub3JtYWxcIikge1xuICAgICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IDA7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgLy8gUmVtb3ZlICdweCcgYW5kIGFueSBleHRyYSB3aGl0ZXNwYWNlXG4gICAgICAgICAgY29uc3QgbGV0dGVyU3BhY2luZ1ZhbHVlID0gYCR7dmFsdWV9YC5yZXBsYWNlKC9weC9nLCBcIlwiKS50cmltKCk7XG4gICAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gK2xldHRlclNwYWNpbmdWYWx1ZTtcbiAgICAgICAgfVxuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJjb2xvckhleFwiOiB7XG4gICAgICAgIGNvbnN0IHRvSGV4ID0gcGFyc2VDb2xvclN0cmluZyhgJHtzdHlsZXNbXCJjb2xvclwiXX1gKTtcblxuICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSB0b0hleD8uaGV4ID8/IFwiIzAwMDAwMFwiO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJjb2xvck9wYWNpdHlcIjoge1xuICAgICAgICBjb25zdCB0b0hleCA9IHBhcnNlQ29sb3JTdHJpbmcoYCR7c3R5bGVzW1wiY29sb3JcIl19YCk7XG4gICAgICAgIGNvbnN0IG9wYWNpdHkgPSBpc05hTigrc3R5bGVzLm9wYWNpdHkpID8gMSA6IHN0eWxlcy5vcGFjaXR5O1xuXG4gICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9ICsodG9IZXg/Lm9wYWNpdHkgPz8gb3BhY2l0eSk7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgZGVmYXVsdDoge1xuICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSBzdHlsZXNba2V5XTtcbiAgICAgIH1cbiAgICB9XG4gIH0pO1xuXG4gIHJldHVybiBkaWM7XG59O1xuIiwgImltcG9ydCB7IEVudHJ5LCBPdXRwdXQsIE91dHB1dERhdGEgfSBmcm9tIFwiLi4vdHlwZXMvdHlwZVwiO1xuXG5leHBvcnQgY29uc3QgZ2V0RGF0YSA9ICgpOiBFbnRyeSA9PiB7XG4gIHRyeSB7XG4gICAgLy8gRm9yIGRldmVsb3BtZW50XG4gICAgLy8gd2luZG93LmlzRGV2ID0gdHJ1ZTtcbiAgICByZXR1cm4gd2luZG93LmlzRGV2XG4gICAgICA/IHtcbiAgICAgICAgICBzZWxlY3RvcjogYFtkYXRhLWlkPScke3dpbmRvdy5lbGVtZW50SWR9J11gLFxuICAgICAgICAgIGZhbWlsaWVzOiB7XG4gICAgICAgICAgICBcInByb3hpbWFfbm92YV9wcm94aW1hX25vdmFfcmVndWxhcl9zYW5zLXNlcmlmXCI6IFwidWlkMTExMVwiLFxuICAgICAgICAgICAgXCJoZWx2ZXRpY2FfbmV1ZV9oZWx2ZXRpY2FuZXVlX2hlbHZldGljYV9hcmlhbF9zYW5zLXNlcmlmXCI6IFwidWlkMjIyMlwiXG4gICAgICAgICAgfSxcbiAgICAgICAgICBkZWZhdWx0RmFtaWx5OiBcImxhdG9cIixcbiAgICAgICAgICB1cmxNYXA6IHt9XG4gICAgICAgIH1cbiAgICAgIDoge1xuICAgICAgICAgIHNlbGVjdG9yOiBTRUxFQ1RPUixcbiAgICAgICAgICBmYW1pbGllczogRkFNSUxJRVMsXG4gICAgICAgICAgZGVmYXVsdEZhbWlseTogREVGQVVMVF9GQU1JTFksXG4gICAgICAgICAgdXJsTWFwOiB7fVxuICAgICAgICB9O1xuICB9IGNhdGNoIChlKSB7XG4gICAgY29uc3QgZmFtaWx5TW9jayA9IHtcbiAgICAgIGxhdG86IFwidWlkX2Zvcl9sYXRvXCIsXG4gICAgICByb2JvdG86IFwidWlkX2Zvcl9yb2JvdG9cIlxuICAgIH07XG4gICAgY29uc3QgbW9jazogRW50cnkgPSB7XG4gICAgICBzZWxlY3RvcjogXCIubXktZGl2XCIsXG4gICAgICBmYW1pbGllczogZmFtaWx5TW9jayxcbiAgICAgIGRlZmF1bHRGYW1pbHk6IFwibGF0b1wiLFxuICAgICAgdXJsTWFwOiB7fVxuICAgIH07XG5cbiAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICBKU09OLnN0cmluZ2lmeSh7XG4gICAgICAgIGVycm9yOiBgSW52YWxpZCBKU09OICR7ZX1gLFxuICAgICAgICBkZXRhaWxzOiBgTXVzdCBiZTogJHtKU09OLnN0cmluZ2lmeShtb2NrKX1gXG4gICAgICB9KVxuICAgICk7XG4gIH1cbn07XG5cbmV4cG9ydCBjb25zdCBjcmVhdGVEYXRhID0gKG91dHB1dDogT3V0cHV0RGF0YSk6IE91dHB1dCA9PiB7XG4gIHJldHVybiBvdXRwdXQ7XG59O1xuIiwgImltcG9ydCB7IFJlYWRlciB9IGZyb20gXCIuL3R5cGVzXCI7XG5cbmV4cG9ydCBjb25zdCByZWFkOiBSZWFkZXI8bnVtYmVyPiA9ICh2KSA9PiB7XG4gIHN3aXRjaCAodHlwZW9mIHYpIHtcbiAgICBjYXNlIFwic3RyaW5nXCI6IHtcbiAgICAgIGNvbnN0IHZfID0gdiAhPT0gXCJcIiA/IE51bWJlcih2KSA6IE5hTjtcbiAgICAgIHJldHVybiBpc05hTih2XykgPyB1bmRlZmluZWQgOiB2XztcbiAgICB9XG4gICAgY2FzZSBcIm51bWJlclwiOlxuICAgICAgcmV0dXJuIGlzTmFOKHYpID8gdW5kZWZpbmVkIDogdjtcbiAgICBkZWZhdWx0OlxuICAgICAgcmV0dXJuIHVuZGVmaW5lZDtcbiAgfVxufTtcblxuZXhwb3J0IGNvbnN0IHJlYWRJbnQ6IFJlYWRlcjxudW1iZXI+ID0gKHYpID0+IHtcbiAgaWYgKHR5cGVvZiB2ID09PSBcInN0cmluZ1wiKSB7XG4gICAgcmV0dXJuIHBhcnNlSW50KHYpO1xuICB9XG5cbiAgcmV0dXJuIHJlYWQodik7XG59O1xuIiwgImltcG9ydCB7IGdldERhdGFCeUVudHJ5IH0gZnJvbSBcIi4uL3V0aWxzL2dldERhdGFCeUVudHJ5XCI7XG5pbXBvcnQgeyBnZXRNb2RlbCB9IGZyb20gXCIuLi91dGlscy9nZXRNb2RlbFwiO1xuaW1wb3J0IHsgRW50cnksIE91dHB1dCB9IGZyb20gXCJlbGVtZW50cy9zcmMvdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgY3JlYXRlRGF0YSB9IGZyb20gXCJlbGVtZW50cy9zcmMvdXRpbHMvZ2V0RGF0YVwiO1xuaW1wb3J0ICogYXMgTnVtIGZyb20gXCJ1dGlscy9zcmMvcmVhZGVyL251bWJlclwiO1xuXG5pbnRlcmZhY2UgTmF2RGF0YSB7XG4gIGxpc3Q6IEVsZW1lbnQ7XG4gIHNlbGVjdG9yOiBzdHJpbmc7XG4gIGZhbWlsaWVzOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+O1xuICBkZWZhdWx0RmFtaWx5OiBzdHJpbmc7XG59XG5jb25zdCB3YXJuczogUmVjb3JkPHN0cmluZywgUmVjb3JkPHN0cmluZywgc3RyaW5nPj4gPSB7fTtcblxuY29uc3QgZ2V0QWNjb3JkaW9uViA9IChkYXRhOiBOYXZEYXRhKSA9PiB7XG4gIGNvbnN0IHsgbGlzdCwgc2VsZWN0b3IgfSA9IGRhdGE7XG4gIGNvbnN0IGxpID0gbGlzdC5jaGlsZHJlblswXTtcbiAgbGV0IHYgPSB7fTtcblxuICBpZiAoIWxpKSB7XG4gICAgd2FybnNbXCJhY2NvcmRpb24gbGlcIl0gPSB7XG4gICAgICBtZXNzYWdlOiBgQWNjb3JkaW9uIGRvbid0IGhhdmUgdWwgPiBsaSBpbiAke3NlbGVjdG9yfWBcbiAgICB9O1xuICAgIHJldHVybiB2O1xuICB9XG4gIGNvbnN0IHRpdGxlID0gbGkucXVlcnlTZWxlY3RvcihcIi5hY2NvcmRpb24tdGl0bGVcIik7XG4gIGlmICghdGl0bGUpIHtcbiAgICB3YXJuc1tcIm1lbnUgbGkgdGl0bGVcIl0gPSB7XG4gICAgICBtZXNzYWdlOiBgQWNjb3JkaW9uIGRvbid0IGhhdmUgdWwgPiBsaSA+IC5hY2NvcmRpb24tdGl0bGUgaW4gJHtzZWxlY3Rvcn1gXG4gICAgfTtcbiAgICByZXR1cm4gdjtcbiAgfVxuXG4gIGNvbnN0IGNvbXB1dGVkU3R5bGVzID0gd2luZG93LmdldENvbXB1dGVkU3R5bGUodGl0bGUsIFwiOjphZnRlclwiKTtcbiAgY29uc3QgZm9udFNpemUgPSBjb21wdXRlZFN0eWxlcy5nZXRQcm9wZXJ0eVZhbHVlKFwiZm9udC1zaXplXCIpO1xuICBjb25zdCBjb250ZW50ID0gY29tcHV0ZWRTdHlsZXMuZ2V0UHJvcGVydHlWYWx1ZShcImNvbnRlbnRcIik7XG4gIGNvbnN0IGhhc0ljb24gPSBmb250U2l6ZSAmJiBjb250ZW50O1xuXG4gIHYgPSBnZXRNb2RlbCh7XG4gICAgbm9kZTogdGl0bGUsXG4gICAgZmFtaWxpZXM6IGRhdGEuZmFtaWxpZXMsXG4gICAgZGVmYXVsdEZhbWlseTogZGF0YS5kZWZhdWx0RmFtaWx5XG4gIH0pO1xuXG4gIHJldHVybiB7XG4gICAgLi4udixcbiAgICAuLi4oaGFzSWNvbiAmJiB7XG4gICAgICBuYXZJY29uOiBcInRoaW5cIixcbiAgICAgIG5hdkljb25TaXplOiBcImN1c3RvbVwiLFxuICAgICAgbmF2SWNvbkN1c3RvbVNpemU6IE1hdGgucm91bmQoTnVtLnJlYWRJbnQoZm9udFNpemUpID8/IDEyKVxuICAgIH0pXG4gIH07XG59O1xuXG5leHBvcnQgY29uc3QgZ2V0QWNjb3JkaW9uID0gKF9lbnRyeTogRW50cnkpOiBPdXRwdXQgPT4ge1xuICBjb25zdCBlbnRyeSA9IHdpbmRvdy5pc0RldiA/IGdldERhdGFCeUVudHJ5KF9lbnRyeSkgOiBfZW50cnk7XG5cbiAgY29uc3QgeyBzZWxlY3RvciwgZmFtaWxpZXMsIGRlZmF1bHRGYW1pbHkgfSA9IGVudHJ5O1xuXG4gIGlmICghc2VsZWN0b3IpIHtcbiAgICByZXR1cm4ge1xuICAgICAgZXJyb3I6IFwiU2VsZWN0b3Igbm90IGZvdW5kXCJcbiAgICB9O1xuICB9XG5cbiAgY29uc3Qgbm9kZSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Ioc2VsZWN0b3IpO1xuXG4gIGNvbnN0IGxpc3QgPSBub2RlPy5xdWVyeVNlbGVjdG9yKFwiLmFjY29yZGlvbi1saXN0XCIpO1xuXG4gIGlmICghbGlzdCkge1xuICAgIHJldHVybiB7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke3NlbGVjdG9yfSBoYXMgbm8gYWNjb3JkaW9uIGxpc3RgXG4gICAgfTtcbiAgfVxuXG4gIGNvbnN0IGRhdGEgPSBnZXRBY2NvcmRpb25WKHsgbGlzdCwgc2VsZWN0b3IsIGZhbWlsaWVzLCBkZWZhdWx0RmFtaWx5IH0pO1xuXG4gIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YSB9KTtcbn07XG4iLCAiZXhwb3J0IHsgZ2V0QWNjb3JkaW9uIGFzIHJ1biB9IGZyb20gXCJlbGVtZW50cy9zcmMvQWNjb3JkaW9uXCI7XG4iLCAiaW1wb3J0IHsgRW50cnksIE91dHB1dCB9IGZyb20gXCIuLi90eXBlcy90eXBlXCI7XG5cbmNvbnN0IHN1YnBhbGV0dGVzID0gW1xuICBcInN1YnBhbGV0dGUxXCIsXG4gIFwic3VicGFsZXR0ZTJcIixcbiAgXCJzdWJwYWxldHRlM1wiLFxuICBcInN1YnBhbGV0dGU0XCJcbl07XG5cbmV4cG9ydCBjb25zdCBkZXRlY3RTdWJwYWxldHRlID0gKGVudHJ5OiBFbnRyeSk6IE91dHB1dCA9PiB7XG4gIGNvbnN0IHsgc2VsZWN0b3IgfSA9IGVudHJ5O1xuXG4gIGlmICghc2VsZWN0b3IpIHtcbiAgICByZXR1cm4ge1xuICAgICAgZXJyb3I6IFwiU2VsZWN0b3Igbm90IGZvdW5kXCJcbiAgICB9O1xuICB9XG5cbiAgY29uc3QgZWxlbWVudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Ioc2VsZWN0b3IpO1xuXG4gIGlmIChlbGVtZW50KSB7XG4gICAgZm9yIChjb25zdCBzdWJwYWxldHRlIG9mIHN1YnBhbGV0dGVzKSB7XG4gICAgICBpZiAoZWxlbWVudC5jbGFzc0xpc3QuY29udGFpbnMoc3VicGFsZXR0ZSkpIHtcbiAgICAgICAgcmV0dXJuIHtcbiAgICAgICAgICBkYXRhOiBzdWJwYWxldHRlXG4gICAgICAgIH07XG4gICAgICB9XG4gICAgfVxuICAgIHJldHVybiB7XG4gICAgICBkYXRhOiBmYWxzZVxuICAgIH07XG4gIH1cblxuICByZXR1cm4ge1xuICAgIGRhdGE6IGZhbHNlXG4gIH07XG59O1xuIiwgImltcG9ydCB7IEVudHJ5LCBPdXRwdXQgfSBmcm9tIFwiLi4vdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgY3JlYXRlRGF0YSB9IGZyb20gXCIuLi91dGlscy9nZXREYXRhXCI7XG5cbmV4cG9ydCBjb25zdCBnZXROb2RlVGV4dCA9IChlbnRyeTogRW50cnkpOiBPdXRwdXQgPT4ge1xuICBjb25zdCB7IHNlbGVjdG9yIH0gPSBlbnRyeTtcblxuICBpZiAoIXNlbGVjdG9yKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGVycm9yOiBcIlNlbGVjdG9yIG5vdCBmb3VuZFwiXG4gICAgfTtcbiAgfVxuXG4gIGNvbnN0IGVsZW1lbnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHNlbGVjdG9yKTtcblxuICBpZiAoZWxlbWVudCkge1xuICAgIGNvbnN0IGRhdGEgPSB7XG4gICAgICBjb250YWluOiBlbGVtZW50LnRleHRDb250ZW50XG4gICAgfTtcblxuICAgIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YSB9KTtcbiAgfVxuXG4gIHJldHVybiB7XG4gICAgZXJyb3I6IFwiU2VsZWN0b3Igbm90IGZvdW5kXCJcbiAgfTtcbn07XG4iLCAiaW1wb3J0IHsgT3V0cHV0IH0gZnJvbSBcIi4uL3R5cGVzL3R5cGVcIjtcbmltcG9ydCB7IGNyZWF0ZURhdGEgfSBmcm9tIFwiLi4vdXRpbHMvZ2V0RGF0YVwiO1xuXG5leHBvcnQgY29uc3QgZ2V0Um9vdFByb3BlcnR5U3R5bGVzID0gKCk6IE91dHB1dCA9PiB7XG4gIGNvbnN0IGRhdGE6IHsgW2tleTogc3RyaW5nXTogc3RyaW5nIH0gPSB7fTsgLy8gRGVmaW5lIHRoZSB0eXBlIGZvciAnZGF0YSdcbiAgY29uc3Qgc3R5bGVTaGVldHMgPSBkb2N1bWVudC5zdHlsZVNoZWV0cztcblxuICBmb3IgKGxldCBpID0gMDsgaSA8IHN0eWxlU2hlZXRzLmxlbmd0aDsgaSsrKSB7XG4gICAgY29uc3Qgc3R5bGVTaGVldCA9IHN0eWxlU2hlZXRzW2ldO1xuXG4gICAgaWYgKCFzdHlsZVNoZWV0LmhyZWYpIHtcbiAgICAgIGNvbnN0IGNzc1J1bGVzID0gKHN0eWxlU2hlZXQgYXMgQ1NTU3R5bGVTaGVldCkuY3NzUnVsZXMgfHwgKHN0eWxlU2hlZXQgYXMgQ1NTU3R5bGVTaGVldCkucnVsZXM7XG5cbiAgICAgIGZvciAobGV0IGogPSAwOyBqIDwgY3NzUnVsZXMubGVuZ3RoOyBqKyspIHtcbiAgICAgICAgY29uc3QgcnVsZSA9IGNzc1J1bGVzW2pdIGFzIENTU1N0eWxlUnVsZTsgLy8gTmFycm93IGRvd24gdG8gQ1NTU3R5bGVSdWxlXG5cbiAgICAgICAgaWYgKHJ1bGUuc2VsZWN0b3JUZXh0ID09PSBcIjpyb290XCIpIHtcbiAgICAgICAgICBjb25zdCBkZWNsYXJhdGlvbnMgPSBydWxlLnN0eWxlO1xuXG4gICAgICAgICAgZm9yIChsZXQgayA9IDA7IGsgPCBkZWNsYXJhdGlvbnMubGVuZ3RoOyBrKyspIHtcbiAgICAgICAgICAgIGNvbnN0IHByb3BlcnR5ID0gZGVjbGFyYXRpb25zW2tdO1xuICAgICAgICAgICAgY29uc3QgdmFsdWUgPSBkZWNsYXJhdGlvbnMuZ2V0UHJvcGVydHlWYWx1ZShwcm9wZXJ0eSk7XG4gICAgICAgICAgICBkYXRhW3Byb3BlcnR5XSA9IHZhbHVlO1xuICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgfVxuICAgIH1cbiAgfVxuXG4gIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YSB9KTtcbn07XG4iLCAiaW1wb3J0IHsgRW50cnksIE91dHB1dCB9IGZyb20gXCIuLi90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBjcmVhdGVEYXRhIH0gZnJvbSBcIi4uL3V0aWxzL2dldERhdGFcIjtcblxuZXhwb3J0IGNvbnN0IGhhc05vZGUgPSAoZW50cnk6IEVudHJ5KTogT3V0cHV0ID0+IHtcbiAgY29uc3QgeyBzZWxlY3RvciB9ID0gZW50cnk7XG5cbiAgaWYgKCFzZWxlY3Rvcikge1xuICAgIHJldHVybiB7XG4gICAgICBlcnJvcjogXCJTZWxlY3RvciBub3QgZm91bmRcIlxuICAgIH07XG4gIH1cblxuICBjb25zdCBkYXRhID0ge1xuICAgIGhhc05vZGU6ICEhZG9jdW1lbnQucXVlcnlTZWxlY3RvcihzZWxlY3RvcilcbiAgfTtcblxuICByZXR1cm4gY3JlYXRlRGF0YSh7IGRhdGEgfSk7XG59O1xuIiwgImltcG9ydCB7IGRldGVjdFN1YnBhbGV0dGUgfSBmcm9tIFwiZWxlbWVudHMvc3JjL0RvbS9kZXRlY3RTdWJwYWxldHRlXCI7XG5pbXBvcnQgeyBnZXROb2RlVGV4dCB9IGZyb20gXCJlbGVtZW50cy9zcmMvRG9tL2dldE5vZGVUZXh0XCI7XG5pbXBvcnQgeyBnZXRSb290UHJvcGVydHlTdHlsZXMgfSBmcm9tIFwiZWxlbWVudHMvc3JjL0RvbS9nZXRSb290UHJvcGVydHlTdHlsZXNcIjtcbmltcG9ydCB7IGhhc05vZGUgfSBmcm9tIFwiZWxlbWVudHMvc3JjL0RvbS9oYXNOb2RlXCI7XG5cbmV4cG9ydCBjb25zdCBEb20gPSB7XG4gIGhhc05vZGUsXG4gIGdldE5vZGVUZXh0LFxuICBnZXRSb290UHJvcGVydHlTdHlsZXMsXG4gIGRldGVjdFN1YnBhbGV0dGVcbn07XG4iLCAiaW1wb3J0IHsgcGFyc2VDb2xvclN0cmluZyB9IGZyb20gXCJ1dGlscy9zcmMvY29sb3IvcGFyc2VDb2xvclN0cmluZ1wiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5cbmNvbnN0IHJ1biA9ICgpID0+IHtcbiAgY29uc3QgbWVudUl0ZW0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFxuICAgIFwiI21haW4tbmF2aWdhdGlvbiBsaTpub3QoLnNlbGVjdGVkKSBhXCJcbiAgKTtcblxuICBpZiAoIW1lbnVJdGVtKSB7XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgY29uc3Qgc3R5bGVzID0gZ2V0Tm9kZVN0eWxlKG1lbnVJdGVtKTtcbiAgY29uc3QgY29sb3IgPSBwYXJzZUNvbG9yU3RyaW5nKGAke3N0eWxlc1tcImNvbG9yXCJdfWApO1xuXG4gIGlmIChjb2xvcikge1xuICAgIHdpbmRvdy5tZW51TW9kZWwgPSB7XG4gICAgICBob3ZlckNvbG9ySGV4OiBjb2xvci5oZXgsXG4gICAgICBob3ZlckNvbG9yT3BhY2l0eTogY29sb3Iub3BhY2l0eSA/PyAxXG4gICAgfTtcbiAgfVxufTtcblxuZXhwb3J0IHsgcnVuIH07XG4iLCAiaW1wb3J0IHsgY3JlYXRlRGF0YSB9IGZyb20gXCIuLi91dGlscy9nZXREYXRhXCI7XG5pbXBvcnQgeyBnZXREYXRhQnlFbnRyeSB9IGZyb20gXCIuLi91dGlscy9nZXREYXRhQnlFbnRyeVwiO1xuaW1wb3J0IHsgRW50cnksIE91dHB1dCB9IGZyb20gXCJlbGVtZW50cy9zcmMvdHlwZXMvdHlwZVwiO1xuXG5pbnRlcmZhY2UgSW1hZ2VNb2RlbCB7XG4gIHNyYzogc3RyaW5nO1xuICB3aWR0aDogbnVtYmVyO1xuICBoZWlnaHQ6IG51bWJlcjtcbn1cblxuZXhwb3J0IGNvbnN0IGdldEltYWdlID0gKF9lbnRyeTogRW50cnkpOiBPdXRwdXQgPT4ge1xuICBjb25zdCBlbnRyeSA9IHdpbmRvdy5pc0RldiA/IGdldERhdGFCeUVudHJ5KF9lbnRyeSkgOiBfZW50cnk7XG5cbiAgY29uc3QgeyBzZWxlY3RvciB9ID0gZW50cnk7XG5cbiAgY29uc3Qgbm9kZSA9IHNlbGVjdG9yID8gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihzZWxlY3RvcikgOiB1bmRlZmluZWQ7XG4gIGlmICghbm9kZSkge1xuICAgIHJldHVybiB7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke3NlbGVjdG9yfSBub3QgZm91bmRgXG4gICAgfTtcbiAgfVxuXG4gIGNvbnN0IGltYWdlcyA9IG5vZGUucXVlcnlTZWxlY3RvckFsbChcImltZ1wiKTtcblxuICBjb25zdCBkYXRhOiBBcnJheTxJbWFnZU1vZGVsPiA9IFtdO1xuXG4gIGltYWdlcy5mb3JFYWNoKChpbWFnZSkgPT4ge1xuICAgIGNvbnN0IHNyYyA9IGltYWdlLnNyYyB8fCBpbWFnZS5zcmNzZXQ7XG4gICAgY29uc3Qgd2lkdGggPSBpbWFnZS53aWR0aDtcbiAgICBjb25zdCBoZWlnaHQgPSBpbWFnZS5oZWlnaHQ7XG4gICAgZGF0YS5wdXNoKHsgc3JjLCB3aWR0aCwgaGVpZ2h0IH0pO1xuICB9KTtcblxuICByZXR1cm4gY3JlYXRlRGF0YSh7IGRhdGEgfSk7XG59O1xuIiwgImV4cG9ydCB7IGdldEltYWdlIGFzIHJ1biB9IGZyb20gXCJlbGVtZW50cy9zcmMvSW1hZ2VcIjtcbiIsICJleHBvcnQgY29uc3QgZ2V0R2xvYmFsTWVudU1vZGVsID0gKCkgPT4ge1xuICByZXR1cm4gd2luZG93Lm1lbnVNb2RlbDtcbn07XG4iLCAiaW1wb3J0IHsgY2FwaXRhbGl6ZSB9IGZyb20gXCIuL2NhcGl0YWxpemVcIjtcblxuLyoqXG4gKiBDYXBpdGFsaXplIHdvcmQgZGVwZW5kaW5nIG9uIHByZWZpeFxuICogLSBJZiBwcmVmaXggaXMgZW1wdHksIGRvIG5vdCBjYXBpdGFsaXplXG4gKiAtIElmIHByZWZpeCBpcyBub3QgZW1wdHkgY2FwaXRhbGl6ZSB3b3JkXG4gKlxuICogQHBhcmFtIHtzdHJpbmd9IHBcbiAqIEBwYXJhbSB7c3RyaW5nfSBzXG4gKiBAcmV0dXJucyB7c3RyaW5nfVxuICovXG5leHBvcnQgY29uc3QgY2FwQnlQcmVmaXggPSAocDogc3RyaW5nLCBzOiBzdHJpbmcpOiBzdHJpbmcgPT5cbiAgcCA9PT0gXCJcIiA/IHMgOiBwICsgXCItXCIgKyBjYXBpdGFsaXplKHMpO1xuIiwgImltcG9ydCB7IGNhcEJ5UHJlZml4IH0gZnJvbSBcInV0aWxzL3NyYy90ZXh0L2NhcEJ5UHJlZml4XCI7XG5pbXBvcnQgeyB0b0NhbWVsQ2FzZSB9IGZyb20gXCJ1dGlscy9zcmMvdGV4dC90b0NhbWVsQ2FzZVwiO1xuXG4vKipcbiAqIEFkZHMgZGVza3RvcCxtb2JpbGUgYW5kIHRhYmxldCBrZXlzIHRvIGRpY3Rpb25hcnlcbiAqL1xuZXhwb3J0IGNvbnN0IGRpY0tleUZvckRldmljZXMgPSAoa2V5OiBzdHJpbmcsIHZhbHVlOiBzdHJpbmcgfCBudW1iZXIpID0+IHtcbiAgcmV0dXJuIHtcbiAgICBbdG9DYW1lbENhc2Uoa2V5KV06IHZhbHVlLFxuICAgIFt0b0NhbWVsQ2FzZShjYXBCeVByZWZpeChcIm1vYmlsZVwiLCBrZXkpKV06IHZhbHVlLFxuICAgIFt0b0NhbWVsQ2FzZShjYXBCeVByZWZpeChcInRhYmxldFwiLCBrZXkpKV06IHZhbHVlXG4gIH07XG59O1xuIiwgImltcG9ydCB7IGRpY0tleUZvckRldmljZXMgfSBmcm9tIFwiZWxlbWVudHMvc3JjL01lbnUvdXRpbHMvZGljS2V5Rm9yRGV2aWNlc1wiO1xuaW1wb3J0IHsgcGFyc2VDb2xvclN0cmluZyB9IGZyb20gXCJ1dGlscy9zcmMvY29sb3IvcGFyc2VDb2xvclN0cmluZ1wiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5pbXBvcnQgeyB0b0NhbWVsQ2FzZSB9IGZyb20gXCJ1dGlscy9zcmMvdGV4dC90b0NhbWVsQ2FzZVwiO1xuXG5pbnRlcmZhY2UgTW9kZWwge1xuICBub2RlOiBFbGVtZW50O1xuICBmYW1pbGllczogUmVjb3JkPHN0cmluZywgc3RyaW5nPjtcbiAgZGVmYXVsdEZhbWlseTogc3RyaW5nO1xufVxuXG5jb25zdCB2ID0ge1xuICBcImZvbnQtZmFtaWx5XCI6IHVuZGVmaW5lZCxcbiAgXCJmb250LWZhbWlseS10eXBlXCI6IFwidXBsb2FkZWRcIixcbiAgXCJmb250LXdlaWdodFwiOiB1bmRlZmluZWQsXG4gIFwiZm9udC1zaXplXCI6IHVuZGVmaW5lZCxcbiAgXCJsaW5lLWhlaWdodFwiOiB1bmRlZmluZWQsXG4gIFwibGV0dGVyLXNwYWNpbmdcIjogdW5kZWZpbmVkLFxuICBcImZvbnQtc3R5bGVcIjogXCJcIixcbiAgY29sb3JIZXg6IHVuZGVmaW5lZCxcbiAgY29sb3JPcGFjaXR5OiAxLFxuICBhY3RpdmVDb2xvckhleDogdW5kZWZpbmVkLFxuICBhY3RpdmVDb2xvck9wYWNpdHk6IHVuZGVmaW5lZFxufTtcblxuZXhwb3J0IGNvbnN0IGdldE1vZGVsID0gKGRhdGE6IE1vZGVsKSA9PiB7XG4gIGNvbnN0IHsgbm9kZSwgZmFtaWxpZXMsIGRlZmF1bHRGYW1pbHkgfSA9IGRhdGE7XG4gIGNvbnN0IHN0eWxlcyA9IGdldE5vZGVTdHlsZShub2RlKTtcbiAgY29uc3QgZGljOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmcgfCBudW1iZXI+ID0ge307XG5cbiAgT2JqZWN0LmtleXModikuZm9yRWFjaCgoa2V5KSA9PiB7XG4gICAgc3dpdGNoIChrZXkpIHtcbiAgICAgIGNhc2UgXCJmb250LWZhbWlseVwiOiB7XG4gICAgICAgIGNvbnN0IHZhbHVlID0gYCR7c3R5bGVzW2tleV19YDtcbiAgICAgICAgY29uc3QgZm9udEZhbWlseSA9IHZhbHVlXG4gICAgICAgICAgLnJlcGxhY2UoL1snXCJcXCxdL2csIFwiXCIpIC8vIGVzbGludC1kaXNhYmxlLWxpbmVcbiAgICAgICAgICAucmVwbGFjZSgvXFxzL2csIFwiX1wiKVxuICAgICAgICAgIC50b0xvY2FsZUxvd2VyQ2FzZSgpO1xuXG4gICAgICAgIGlmICghZmFtaWxpZXNbZm9udEZhbWlseV0pIHtcbiAgICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSBkZWZhdWx0RmFtaWx5O1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IGZhbWlsaWVzW2ZvbnRGYW1pbHldO1xuICAgICAgICB9XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImZvbnQtZmFtaWx5LXR5cGVcIjoge1xuICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSBcInVwbG9hZFwiO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJmb250LXN0eWxlXCI6IHtcbiAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gXCJcIjtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwibGluZS1oZWlnaHRcIjoge1xuICAgICAgICBjb25zdCB2YWx1ZSA9IHBhcnNlSW50KGAke3N0eWxlc1trZXldfWApO1xuICAgICAgICBpZiAoaXNOYU4odmFsdWUpKSB7XG4gICAgICAgICAgT2JqZWN0LmFzc2lnbihkaWMsIGRpY0tleUZvckRldmljZXMoa2V5LCAxKSk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgT2JqZWN0LmFzc2lnbihkaWMsIGRpY0tleUZvckRldmljZXMoa2V5LCB2YWx1ZSkpO1xuICAgICAgICB9XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImZvbnQtc2l6ZVwiOiB7XG4gICAgICAgIE9iamVjdC5hc3NpZ24oZGljLCBkaWNLZXlGb3JEZXZpY2VzKGtleSwgcGFyc2VJbnQoYCR7c3R5bGVzW2tleV19YCkpKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwibGV0dGVyLXNwYWNpbmdcIjoge1xuICAgICAgICBjb25zdCB2YWx1ZSA9IHN0eWxlc1trZXldO1xuICAgICAgICBpZiAodmFsdWUgPT09IFwibm9ybWFsXCIpIHtcbiAgICAgICAgICBPYmplY3QuYXNzaWduKGRpYywgZGljS2V5Rm9yRGV2aWNlcyhrZXksIDApKTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAvLyBSZW1vdmUgJ3B4JyBhbmQgYW55IGV4dHJhIHdoaXRlc3BhY2VcbiAgICAgICAgICBjb25zdCBsZXR0ZXJTcGFjaW5nVmFsdWUgPSBgJHt2YWx1ZX1gLnJlcGxhY2UoL3B4L2csIFwiXCIpLnRyaW0oKTtcbiAgICAgICAgICBPYmplY3QuYXNzaWduKGRpYywgZGljS2V5Rm9yRGV2aWNlcyhrZXksICtsZXR0ZXJTcGFjaW5nVmFsdWUpKTtcbiAgICAgICAgfVxuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJjb2xvckhleFwiOiB7XG4gICAgICAgIGNvbnN0IHRvSGV4ID0gcGFyc2VDb2xvclN0cmluZyhgJHtzdHlsZXNbXCJjb2xvclwiXX1gKTtcbiAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gdG9IZXg/LmhleCA/PyBcIiMwMDAwMDBcIjtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiY29sb3JPcGFjaXR5XCI6IHtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBkZWZhdWx0OiB7XG4gICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IHN0eWxlc1trZXldO1xuICAgICAgfVxuICAgIH1cbiAgfSk7XG5cbiAgcmV0dXJuIGRpYztcbn07XG4iLCAiaW1wb3J0IHsgY2FwaXRhbGl6ZSB9IGZyb20gXCIuLi90ZXh0L2NhcGl0YWxpemVcIjtcblxuZXhwb3J0IGNvbnN0IHByZWZpeGVkID0gPFQgZXh0ZW5kcyBSZWNvcmQ8c3RyaW5nLCB1bmtub3duPj4oXG4gIHY6IFQsXG4gIHByZWZpeDogc3RyaW5nXG4pOiBUID0+IHtcbiAgcmV0dXJuIE9iamVjdC5lbnRyaWVzKHYpLnJlZHVjZSgoYWNjLCBba2V5LCB2YWx1ZV0pID0+IHtcbiAgICBsZXQgX2tleSA9IHByZWZpeCArIGNhcGl0YWxpemUoa2V5KTtcbiAgICBjb25zdCBwcmVmaXhlcyA9IFtcImFjdGl2ZVwiLCBcIm1vYmlsZVwiLCBcInRhYmxldFwiXTtcbiAgICBjb25zdCBtYXRjaGVkUHJlZml4ID0gcHJlZml4ZXMuZmluZCgocHJlZml4KSA9PiBrZXkuc3RhcnRzV2l0aChwcmVmaXgpKTtcblxuICAgIGlmIChtYXRjaGVkUHJlZml4KSB7XG4gICAgICBfa2V5ID0gYCR7bWF0Y2hlZFByZWZpeH0ke2NhcGl0YWxpemUocHJlZml4KX0ke2tleS5yZXBsYWNlKFxuICAgICAgICBgJHttYXRjaGVkUHJlZml4fWAsXG4gICAgICAgIFwiXCJcbiAgICAgICl9YDtcbiAgICB9XG5cbiAgICByZXR1cm4geyAuLi5hY2MsIFtfa2V5XTogdmFsdWUgfTtcbiAgfSwge30gYXMgVCk7XG59O1xuIiwgImltcG9ydCB7IGdldEdsb2JhbE1lbnVNb2RlbCB9IGZyb20gXCIuLi91dGlscy9nZXRHbG9iYWxNZW51TW9kZWxcIjtcbmltcG9ydCB7IGdldE1vZGVsIH0gZnJvbSBcIi4vdXRpbHMvZ2V0TW9kZWxcIjtcbmltcG9ydCB7IEVudHJ5LCBPdXRwdXQgfSBmcm9tIFwiZWxlbWVudHMvc3JjL3R5cGVzL3R5cGVcIjtcbmltcG9ydCB7IGNyZWF0ZURhdGEgfSBmcm9tIFwiZWxlbWVudHMvc3JjL3V0aWxzL2dldERhdGFcIjtcbmltcG9ydCB7IGdldERhdGFCeUVudHJ5IH0gZnJvbSBcImVsZW1lbnRzL3NyYy91dGlscy9nZXREYXRhQnlFbnRyeVwiO1xuaW1wb3J0IHsgcGFyc2VDb2xvclN0cmluZyB9IGZyb20gXCJ1dGlscy9zcmMvY29sb3IvcGFyc2VDb2xvclN0cmluZ1wiO1xuaW1wb3J0IHsgcHJlZml4ZWQgfSBmcm9tIFwidXRpbHMvc3JjL21vZGVscy9wcmVmaXhlZFwiO1xuXG5pbnRlcmZhY2UgTmF2RGF0YSB7XG4gIG5hdjogRWxlbWVudDtcbiAgc3ViTmF2PzogRWxlbWVudDtcbiAgc2VsZWN0b3I6IHN0cmluZztcbiAgZmFtaWxpZXM6IFJlY29yZDxzdHJpbmcsIHN0cmluZz47XG4gIGRlZmF1bHRGYW1pbHk6IHN0cmluZztcbn1cbmNvbnN0IHdhcm5zOiBSZWNvcmQ8c3RyaW5nLCBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+PiA9IHt9O1xuXG5jb25zdCBnZXRNZW51ViA9IChkYXRhOiBOYXZEYXRhKSA9PiB7XG4gIGNvbnN0IHsgbmF2LCBzZWxlY3RvciB9ID0gZGF0YTtcbiAgY29uc3QgdWwgPSBuYXYuY2hpbGRyZW5bMF07XG4gIGxldCB2ID0ge307XG5cbiAgaWYgKCF1bCkge1xuICAgIHdhcm5zW1wibWVudVwiXSA9IHtcbiAgICAgIG1lc3NhZ2U6IGBOYXZpZ2F0aW9uIGRvbid0IGhhdmUgdWwgaW4gJHtzZWxlY3Rvcn1gXG4gICAgfTtcbiAgICByZXR1cm4gdjtcbiAgfVxuXG4gIGNvbnN0IGxpID0gdWwucXVlcnlTZWxlY3RvcihcImxpXCIpO1xuICBpZiAoIWxpKSB7XG4gICAgd2FybnNbXCJtZW51IGxpXCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCA+IGxpIGluICR7c2VsZWN0b3J9YFxuICAgIH07XG4gICAgcmV0dXJuIHY7XG4gIH1cblxuICBjb25zdCBsaW5rID0gdWwucXVlcnlTZWxlY3RvcihcImxpID4gYVwiKTtcbiAgaWYgKCFsaW5rKSB7XG4gICAgd2FybnNbXCJtZW51IGxpIGFcIl0gPSB7XG4gICAgICBtZXNzYWdlOiBgTmF2aWdhdGlvbiBkb24ndCBoYXZlIHVsID4gbGkgPiBhIGluICR7c2VsZWN0b3J9YFxuICAgIH07XG4gICAgcmV0dXJuIHY7XG4gIH1cblxuICBjb25zdCBzdHlsZXMgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShsaSk7XG4gIGNvbnN0IGl0ZW1QYWRkaW5nID0gcGFyc2VJbnQoc3R5bGVzLnBhZGRpbmdMZWZ0KTtcblxuICB2ID0gZ2V0TW9kZWwoe1xuICAgIG5vZGU6IGxpbmssXG4gICAgZmFtaWxpZXM6IGRhdGEuZmFtaWxpZXMsXG4gICAgZGVmYXVsdEZhbWlseTogZGF0YS5kZWZhdWx0RmFtaWx5XG4gIH0pO1xuICBjb25zdCBtTWVudSA9IHByZWZpeGVkKHYsIFwibU1lbnVcIik7XG4gIGNvbnN0IGdsb2JhbE1vZGVsID0gZ2V0R2xvYmFsTWVudU1vZGVsKCk7XG5cbiAgcmV0dXJuIHtcbiAgICAuLi5tTWVudSxcbiAgICAuLi5nbG9iYWxNb2RlbCxcbiAgICAuLi52LFxuICAgIGl0ZW1QYWRkaW5nOiBpc05hTihpdGVtUGFkZGluZykgPyAxMCA6IGl0ZW1QYWRkaW5nXG4gIH07XG59O1xuXG5jb25zdCBnZXRTdWJNZW51ViA9IChkYXRhOiBSZXF1aXJlZDxOYXZEYXRhPikgPT4ge1xuICBjb25zdCB7IHN1Yk5hdiwgc2VsZWN0b3IgfSA9IGRhdGE7XG5cbiAgY29uc3QgdWwgPSBzdWJOYXYuY2hpbGRyZW5bMF07XG5cbiAgaWYgKCF1bCkge1xuICAgIHdhcm5zW1wic3VibWVudVwiXSA9IHtcbiAgICAgIG1lc3NhZ2U6IGBOYXZpZ2F0aW9uIGRvbid0IGhhdmUgdWwgaW4gJHtzZWxlY3Rvcn1gXG4gICAgfTtcbiAgICByZXR1cm47XG4gIH1cblxuICBjb25zdCBsaSA9IHVsLnF1ZXJ5U2VsZWN0b3IoXCJsaVwiKTtcbiAgaWYgKCFsaSkge1xuICAgIHdhcm5zW1wic3VibWVudSBsaVwiXSA9IHtcbiAgICAgIG1lc3NhZ2U6IGBOYXZpZ2F0aW9uIGRvbid0IGhhdmUgdWwgPiBsaSBpbiAke3NlbGVjdG9yfWBcbiAgICB9O1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGNvbnN0IGxpbmsgPSB1bC5xdWVyeVNlbGVjdG9yKFwibGkgPiBhXCIpO1xuICBpZiAoIWxpbmspIHtcbiAgICB3YXJuc1tcInN1Ym1lbnUgbGkgYVwiXSA9IHtcbiAgICAgIG1lc3NhZ2U6IGBOYXZpZ2F0aW9uIGRvbid0IGhhdmUgdWwgPiBsaSA+IGEgaW4gJHtzZWxlY3Rvcn1gXG4gICAgfTtcbiAgICByZXR1cm47XG4gIH1cblxuICBjb25zdCB0eXBvZ3JhcGh5ID0gZ2V0TW9kZWwoe1xuICAgIG5vZGU6IGxpbmssXG4gICAgZmFtaWxpZXM6IGRhdGEuZmFtaWxpZXMsXG4gICAgZGVmYXVsdEZhbWlseTogZGF0YS5kZWZhdWx0RmFtaWx5XG4gIH0pO1xuICBjb25zdCBzdWJtZW51VHlwb2dyYXBoeSA9IHByZWZpeGVkKHR5cG9ncmFwaHksIFwic3ViTWVudVwiKTtcbiAgY29uc3QgYmFzZVN0eWxlID0gd2luZG93LmdldENvbXB1dGVkU3R5bGUoc3ViTmF2KTtcbiAgY29uc3QgYmdDb2xvciA9IHBhcnNlQ29sb3JTdHJpbmcoYmFzZVN0eWxlLmJhY2tncm91bmRDb2xvcik7XG5cbiAgcmV0dXJuIHtcbiAgICAuLi5zdWJtZW51VHlwb2dyYXBoeSxcbiAgICAuLi4oYmdDb2xvciAmJlxuICAgICAgYmdDb2xvci5vcGFjaXR5ICE9PSBcIjBcIiAmJiB7XG4gICAgICAgIHN1Yk1lbnVCZ0NvbG9yT3BhY2l0eTogYmdDb2xvci5vcGFjaXR5LFxuICAgICAgICBzdWJNZW51QmdDb2xvckhleDogYmdDb2xvci5oZXgsXG4gICAgICAgIHN1Yk1lbnVCZ0NvbG9yUGFsZXR0ZTogXCJcIlxuICAgICAgfSlcbiAgfTtcbn07XG5cbmNvbnN0IHJ1biA9IChfZW50cnk6IEVudHJ5KTogT3V0cHV0ID0+IHtcbiAgY29uc3QgZW50cnkgPSB3aW5kb3cuaXNEZXYgPyBnZXREYXRhQnlFbnRyeShfZW50cnkpIDogX2VudHJ5O1xuXG4gIGNvbnN0IHsgc2VsZWN0b3IsIGZhbWlsaWVzLCBkZWZhdWx0RmFtaWx5IH0gPSBlbnRyeTtcblxuICBpZiAoIXNlbGVjdG9yKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGVycm9yOiBcIlNlbGVjdG9yIG5vdCBmb3VuZFwiXG4gICAgfTtcbiAgfVxuXG4gIGNvbnN0IGhlYWRlciA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Ioc2VsZWN0b3IpO1xuXG4gIGlmICghaGVhZGVyKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGVycm9yOiBgRWxlbWVudCB3aXRoIHNlbGVjdG9yICR7c2VsZWN0b3J9IGhhcyBubyBoZWFkZXJgXG4gICAgfTtcbiAgfVxuXG4gIGNvbnN0IG5hdiA9IGhlYWRlci5xdWVyeVNlbGVjdG9yKFwiI21haW4tbmF2aWdhdGlvblwiKTtcblxuICBpZiAoIW5hdikge1xuICAgIHJldHVybiB7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke3NlbGVjdG9yfSBoYXMgbm8gbmF2YFxuICAgIH07XG4gIH1cblxuICBjb25zdCBzdWJOYXYgPSBoZWFkZXIucXVlcnlTZWxlY3RvcihcIiNzZWxlY3RlZC1zdWItbmF2aWdhdGlvblwiKSA/PyB1bmRlZmluZWQ7XG4gIGxldCBkYXRhID0gZ2V0TWVudVYoeyBuYXYsIHNlbGVjdG9yLCBmYW1pbGllcywgZGVmYXVsdEZhbWlseSB9KTtcblxuICBpZiAoc3ViTmF2KSB7XG4gICAgY29uc3QgX3YgPSBnZXRTdWJNZW51Vih7IG5hdiwgc3ViTmF2LCBzZWxlY3RvciwgZmFtaWxpZXMsIGRlZmF1bHRGYW1pbHkgfSk7XG4gICAgZGF0YSA9IHsgLi4uZGF0YSwgLi4uX3YgfTtcbiAgfVxuXG4gIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YTogZGF0YSB9KTtcbn07XG5cbi8vIEZvciBkZXZlbG9wbWVudFxuLy8gd2luZG93LmlzRGV2ID0gdHJ1ZTtcblxuZXhwb3J0IHsgcnVuIH07XG4iLCAiaW1wb3J0IHsgT3V0cHV0IH0gZnJvbSBcIi4uL3R5cGVzL3R5cGVcIjtcbmltcG9ydCB7IGNyZWF0ZURhdGEgfSBmcm9tIFwiLi4vdXRpbHMvZ2V0RGF0YVwiO1xuaW1wb3J0IHsgZ2V0RGF0YUJ5RW50cnkgfSBmcm9tIFwiLi4vdXRpbHMvZ2V0RGF0YUJ5RW50cnlcIjtcbmltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwidXRpbHNcIjtcblxuZXhwb3J0IGludGVyZmFjZSBEYXRhIHtcbiAgc2VsZWN0b3I6IHN0cmluZztcbiAgZmFtaWxpZXM6IFJlY29yZDxzdHJpbmcsIHN0cmluZz47XG4gIGRlZmF1bHRGYW1pbHk6IHN0cmluZztcbiAgc3R5bGVQcm9wZXJ0aWVzOiBBcnJheTxzdHJpbmc+O1xuICB1cmxNYXA6IFJlY29yZDxzdHJpbmcsIHN0cmluZz47XG4gIGF0dHJpYnV0ZU5hbWVzPzogc3RyaW5nW107XG59XG5cbmV4cG9ydCBjb25zdCBzdHlsZUV4dHJhY3RvciA9IChfZW50cnk6IERhdGEpOiBPdXRwdXQgPT4ge1xuICBjb25zdCBlbnRyeSA9IHdpbmRvdy5pc0RldiA/IGdldERhdGFCeUVudHJ5KF9lbnRyeSkgOiBfZW50cnk7XG5cbiAgY29uc3QgeyBzZWxlY3Rvciwgc3R5bGVQcm9wZXJ0aWVzIH0gPSBlbnRyeTtcblxuICBjb25zdCBkYXRhOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPiA9IHt9O1xuICBjb25zdCBlbGVtZW50ID0gc2VsZWN0b3IgPyBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHNlbGVjdG9yKSA6IHVuZGVmaW5lZDtcblxuICBpZiAoIWVsZW1lbnQpIHtcbiAgICByZXR1cm4ge1xuICAgICAgZXJyb3I6IGBFbGVtZW50IHdpdGggc2VsZWN0b3IgJHtzZWxlY3Rvcn0gbm90IGZvdW5kYFxuICAgIH07XG4gIH1cblxuICBjb25zdCBjb21wdXRlZFN0eWxlcyA9IGdldENvbXB1dGVkU3R5bGUoZWxlbWVudCk7XG5cbiAgaWYgKHN0eWxlUHJvcGVydGllcylcbiAgICBzdHlsZVByb3BlcnRpZXMuZm9yRWFjaCgoc3R5bGVOYW1lOiBzdHJpbmcpID0+IHtcbiAgICAgIGRhdGFbc3R5bGVOYW1lXSA9IGNvbXB1dGVkU3R5bGVzLmdldFByb3BlcnR5VmFsdWUoc3R5bGVOYW1lKTtcbiAgICB9KTtcblxuICByZXR1cm4gY3JlYXRlRGF0YSh7IGRhdGEgfSk7XG59O1xuXG5leHBvcnQgY29uc3QgYXR0cmlidXRlc0V4dHJhY3RvciA9IChfZW50cnk6IERhdGEpOiBPdXRwdXQgPT4ge1xuICBjb25zdCBlbnRyeSA9IHdpbmRvdy5pc0RldiA/IGdldERhdGFCeUVudHJ5KF9lbnRyeSkgOiBfZW50cnk7XG5cbiAgY29uc3QgeyBzZWxlY3RvciwgYXR0cmlidXRlTmFtZXMgPSBbXSB9ID0gZW50cnk7XG5cbiAgY29uc3QgZGF0YTogUmVjb3JkPHN0cmluZywgc3RyaW5nIHwgbnVsbD4gPSB7fTtcbiAgY29uc3QgZWxlbWVudCA9IHNlbGVjdG9yID8gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihzZWxlY3RvcikgOiB1bmRlZmluZWQ7XG5cbiAgaWYgKCFlbGVtZW50KSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGVycm9yOiBgRWxlbWVudCB3aXRoIHNlbGVjdG9yICR7c2VsZWN0b3J9IG5vdCBmb3VuZGBcbiAgICB9O1xuICB9XG4gIGF0dHJpYnV0ZU5hbWVzLmZvckVhY2goKGF0dHI6IHN0cmluZykgPT4ge1xuICAgIGRhdGFbYXR0cl0gPSBlbGVtZW50LmdldEF0dHJpYnV0ZShhdHRyKTtcbiAgfSk7XG4gIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YSB9KTtcbn07XG5cbmV4cG9ydCBjb25zdCBoYXNOb2RlID0gKF9lbnRyeTogRGF0YSk6IE91dHB1dCA9PiB7XG4gIGNvbnN0IGVudHJ5ID0gd2luZG93LmlzRGV2ID8gZ2V0RGF0YUJ5RW50cnkoX2VudHJ5KSA6IF9lbnRyeTtcblxuICBjb25zdCB7IHNlbGVjdG9yIH0gPSBlbnRyeTtcblxuICBjb25zdCBkYXRhOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmcgfCBudWxsPiA9IHt9O1xuXG4gIGlmICghc2VsZWN0b3IpIHtcbiAgICByZXR1cm4ge1xuICAgICAgZXJyb3I6IFwiU2VsZWN0b3Igbm90IGZvdW5kXCJcbiAgICB9O1xuICB9XG5cbiAgZGF0YVtcImhhc05vZGVcIl0gPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHNlbGVjdG9yKSA/IFwidHJ1ZVwiIDogXCJmYWxzZVwiO1xuXG4gIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YSB9KTtcbn07XG5cbmV4cG9ydCBjb25zdCBnZXROb2RlVGV4dCA9IChfZW50cnk6IERhdGEpOiBPdXRwdXQgPT4ge1xuICBjb25zdCBlbnRyeSA9IHdpbmRvdy5pc0RldiA/IGdldERhdGFCeUVudHJ5KF9lbnRyeSkgOiBfZW50cnk7XG5cbiAgY29uc3QgeyBzZWxlY3RvciB9ID0gZW50cnk7XG5cbiAgY29uc3QgZGF0YTogUmVjb3JkPHN0cmluZywgc3RyaW5nIHwgbnVsbD4gPSB7fTtcblxuICBpZiAoIXNlbGVjdG9yKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGVycm9yOiBcIlNlbGVjdG9yIG5vdCBmb3VuZFwiXG4gICAgfTtcbiAgfVxuXG4gIGNvbnN0IGVsZW1lbnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHNlbGVjdG9yKTtcblxuICBpZiAoZWxlbWVudCkge1xuICAgIGRhdGFbXCJ0ZXh0Tm9kZVwiXSA9IGVsZW1lbnQudGV4dENvbnRlbnQ7XG4gIH1cblxuICByZXR1cm4gY3JlYXRlRGF0YSh7IGRhdGEgfSk7XG59O1xuIiwgImV4cG9ydCB7XG4gIHN0eWxlRXh0cmFjdG9yIGFzIHJ1bixcbiAgYXR0cmlidXRlc0V4dHJhY3RvciBhcyBhdHRyaWJ1dGVSdW4sXG4gIGdldE5vZGVUZXh0IGFzIGdldE5vZGVUZXh0LFxuICBoYXNOb2RlIGFzIGhhc05vZGUsXG59IGZyb20gXCJlbGVtZW50cy9zcmMvU3R5bGVFeHRyYWN0b3JcIjtcbiIsICJpbXBvcnQgeyBnZXRNb2RlbCBhcyBnZXRDb21tb25Nb2RlbCB9IGZyb20gXCIuLi8uLi91dGlscy9nZXRNb2RlbFwiO1xuaW1wb3J0IHsgcGFyc2VDb2xvclN0cmluZyB9IGZyb20gXCJ1dGlscy9zcmMvY29sb3IvcGFyc2VDb2xvclN0cmluZ1wiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5pbXBvcnQgeyB0b0NhbWVsQ2FzZSB9IGZyb20gXCJ1dGlscy9zcmMvdGV4dC90b0NhbWVsQ2FzZVwiO1xuXG5pbnRlcmZhY2UgTW9kZWwge1xuICBub2RlOiBFbGVtZW50O1xuICBmYW1pbGllczogUmVjb3JkPHN0cmluZywgc3RyaW5nPjtcbiAgZGVmYXVsdEZhbWlseTogc3RyaW5nO1xufVxuXG5jb25zdCB2ID0ge1xuICBib3JkZXJDb2xvckhleDogdW5kZWZpbmVkLFxuICBib3JkZXJDb2xvck9wYWNpdHk6IDEsXG4gIGJvcmRlcldpZHRoOiAxXG59O1xuXG5leHBvcnQgY29uc3QgZ2V0TW9kZWwgPSAoZGF0YTogTW9kZWwpID0+IHtcbiAgY29uc3QgeyBub2RlIH0gPSBkYXRhO1xuICBjb25zdCBzdHlsZXMgPSBnZXROb2RlU3R5bGUobm9kZSk7XG4gIGNvbnN0IGRpYzogUmVjb3JkPHN0cmluZywgc3RyaW5nIHwgbnVtYmVyPiA9IHt9O1xuXG4gIE9iamVjdC5rZXlzKHYpLmZvckVhY2goKGtleSkgPT4ge1xuICAgIHN3aXRjaCAoa2V5KSB7XG4gICAgICBjYXNlIFwiYm9yZGVyQ29sb3JIZXhcIjoge1xuICAgICAgICBjb25zdCB0b0hleCA9IHBhcnNlQ29sb3JTdHJpbmcoYCR7c3R5bGVzW1wiYm9yZGVyLWJvdHRvbS1jb2xvclwiXX1gKTtcblxuICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSB0b0hleD8uaGV4ID8/IFwiIzAwMDAwMFwiO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJib3JkZXJDb2xvck9wYWNpdHlcIjoge1xuICAgICAgICBjb25zdCB0b0hleCA9IHBhcnNlQ29sb3JTdHJpbmcoYCR7c3R5bGVzW1wiYm9yZGVyLWJvdHRvbS1jb2xvclwiXX1gKTtcbiAgICAgICAgY29uc3Qgb3BhY2l0eSA9IGlzTmFOKCtzdHlsZXMub3BhY2l0eSkgPyAxIDogc3R5bGVzLm9wYWNpdHk7XG5cbiAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gKyh0b0hleD8ub3BhY2l0eSA/PyBvcGFjaXR5KTtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiYm9yZGVyV2lkdGhcIjoge1xuICAgICAgICBjb25zdCBib3JkZXJXaWR0aCA9IGAke3N0eWxlc1tcImJvcmRlci1ib3R0b20td2lkdGhcIl19YC5yZXBsYWNlKFxuICAgICAgICAgIC9weC9nLFxuICAgICAgICAgIFwiXCJcbiAgICAgICAgKTtcblxuICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSArKGJvcmRlcldpZHRoID8/IDEpO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGRlZmF1bHQ6IHtcbiAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gc3R5bGVzW2tleV07XG4gICAgICB9XG4gICAgfVxuICB9KTtcblxuICByZXR1cm4geyAuLi5nZXRDb21tb25Nb2RlbChkYXRhKSwgLi4uZGljIH07XG59O1xuIiwgImltcG9ydCB7IGdldE1vZGVsIH0gZnJvbSBcIi4vdXRpbHMvZ2V0TW9kZWxcIjtcbmltcG9ydCB7IEVudHJ5LCBPdXRwdXQgfSBmcm9tIFwiZWxlbWVudHMvc3JjL3R5cGVzL3R5cGVcIjtcbmltcG9ydCB7IGNyZWF0ZURhdGEgfSBmcm9tIFwiZWxlbWVudHMvc3JjL3V0aWxzL2dldERhdGFcIjtcbmltcG9ydCB7IHBhcnNlQ29sb3JTdHJpbmcgfSBmcm9tIFwidXRpbHMvc3JjL2NvbG9yL3BhcnNlQ29sb3JTdHJpbmdcIjtcblxuaW50ZXJmYWNlIE5hdkRhdGEge1xuICBub2RlOiBFbGVtZW50O1xuICBsaXN0OiBFbGVtZW50O1xuICBzZWxlY3Rvcjogc3RyaW5nO1xuICBmYW1pbGllczogUmVjb3JkPHN0cmluZywgc3RyaW5nPjtcbiAgZGVmYXVsdEZhbWlseTogc3RyaW5nO1xufVxuY29uc3Qgd2FybnM6IFJlY29yZDxzdHJpbmcsIFJlY29yZDxzdHJpbmcsIHN0cmluZz4+ID0ge307XG5cbmNvbnN0IGdldFRhYnNWID0gKGRhdGE6IE5hdkRhdGEpID0+IHtcbiAgY29uc3QgeyBub2RlLCBsaXN0LCBzZWxlY3RvciB9ID0gZGF0YTtcbiAgY29uc3QgdGFiID0gbGlzdC5jaGlsZHJlblswXTtcbiAgbGV0IHYgPSB7fTtcblxuICBpZiAoIXRhYikge1xuICAgIHdhcm5zW1widGFicyB0YWJcIl0gPSB7XG4gICAgICBtZXNzYWdlOiBgVGFicyBkb24ndCBoYXZlIC50YWJzLWxpc3QgPiAudGFiLXRpdGxlIGluICR7c2VsZWN0b3J9YFxuICAgIH07XG4gICAgcmV0dXJuIHY7XG4gIH1cblxuICB2ID0gZ2V0TW9kZWwoe1xuICAgIG5vZGU6IHRhYixcbiAgICBmYW1pbGllczogZGF0YS5mYW1pbGllcyxcbiAgICBkZWZhdWx0RmFtaWx5OiBkYXRhLmRlZmF1bHRGYW1pbHlcbiAgfSk7XG5cbiAgY29uc3QgeyBiYWNrZ3JvdW5kQ29sb3IsIG9wYWNpdHkgfSA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKG5vZGUpO1xuICBjb25zdCBjb2xvciA9IHBhcnNlQ29sb3JTdHJpbmcoYmFja2dyb3VuZENvbG9yKTtcblxuICByZXR1cm4ge1xuICAgIC4uLnYsXG4gICAgLi4uKGNvbG9yICYmIHsgYmdDb2xvckhleDogY29sb3IuaGV4LCBvcGFjaXR5OiBjb2xvci5vcGFjaXR5ID8/ICtvcGFjaXR5IH0pLFxuICAgIG5hdlN0eWxlOiBcInN0eWxlLTNcIlxuICB9O1xufTtcblxuZXhwb3J0IGNvbnN0IGdldFRhYnMgPSAoZW50cnk6IEVudHJ5KTogT3V0cHV0ID0+IHtcbiAgY29uc3QgeyBzZWxlY3RvciwgZmFtaWxpZXMsIGRlZmF1bHRGYW1pbHkgfSA9IGVudHJ5O1xuICBjb25zdCBub2RlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihzZWxlY3Rvcik7XG4gIGlmICghbm9kZSkge1xuICAgIHJldHVybiB7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke2VudHJ5LnNlbGVjdG9yfSBub3QgZm91bmRgXG4gICAgfTtcbiAgfVxuICBjb25zdCBsaXN0ID0gbm9kZS5xdWVyeVNlbGVjdG9yKFwiLnRhYnMtbGlzdFwiKTtcbiAgaWYgKCFsaXN0KSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGVycm9yOiBgRWxlbWVudCB3aXRoIHNlbGVjdG9yICR7ZW50cnkuc2VsZWN0b3J9IGhhcyBubyB0YWIgbGlzdGBcbiAgICB9O1xuICB9XG4gIGNvbnN0IGRhdGEgPSBnZXRUYWJzVih7IG5vZGUsIGxpc3QsIHNlbGVjdG9yLCBmYW1pbGllcywgZGVmYXVsdEZhbWlseSB9KTtcblxuICByZXR1cm4gY3JlYXRlRGF0YSh7IGRhdGEgfSk7XG59O1xuIiwgImV4cG9ydCB7IGdldFRhYnMgYXMgcnVuIH0gZnJvbSBcImVsZW1lbnRzL3NyYy9UYWJzXCI7XG4iLCAiZXhwb3J0IHsgdXJsQWxwaGFiZXQgfSBmcm9tICcuL3VybC1hbHBoYWJldC9pbmRleC5qcydcbmV4cG9ydCBsZXQgcmFuZG9tID0gYnl0ZXMgPT4gY3J5cHRvLmdldFJhbmRvbVZhbHVlcyhuZXcgVWludDhBcnJheShieXRlcykpXG5leHBvcnQgbGV0IGN1c3RvbVJhbmRvbSA9IChhbHBoYWJldCwgZGVmYXVsdFNpemUsIGdldFJhbmRvbSkgPT4ge1xuICBsZXQgbWFzayA9ICgyIDw8IChNYXRoLmxvZyhhbHBoYWJldC5sZW5ndGggLSAxKSAvIE1hdGguTE4yKSkgLSAxXG4gIGxldCBzdGVwID0gLX4oKDEuNiAqIG1hc2sgKiBkZWZhdWx0U2l6ZSkgLyBhbHBoYWJldC5sZW5ndGgpXG4gIHJldHVybiAoc2l6ZSA9IGRlZmF1bHRTaXplKSA9PiB7XG4gICAgbGV0IGlkID0gJydcbiAgICB3aGlsZSAodHJ1ZSkge1xuICAgICAgbGV0IGJ5dGVzID0gZ2V0UmFuZG9tKHN0ZXApXG4gICAgICBsZXQgaiA9IHN0ZXBcbiAgICAgIHdoaWxlIChqLS0pIHtcbiAgICAgICAgaWQgKz0gYWxwaGFiZXRbYnl0ZXNbal0gJiBtYXNrXSB8fCAnJ1xuICAgICAgICBpZiAoaWQubGVuZ3RoID09PSBzaXplKSByZXR1cm4gaWRcbiAgICAgIH1cbiAgICB9XG4gIH1cbn1cbmV4cG9ydCBsZXQgY3VzdG9tQWxwaGFiZXQgPSAoYWxwaGFiZXQsIHNpemUgPSAyMSkgPT5cbiAgY3VzdG9tUmFuZG9tKGFscGhhYmV0LCBzaXplLCByYW5kb20pXG5leHBvcnQgbGV0IG5hbm9pZCA9IChzaXplID0gMjEpID0+XG4gIGNyeXB0by5nZXRSYW5kb21WYWx1ZXMobmV3IFVpbnQ4QXJyYXkoc2l6ZSkpLnJlZHVjZSgoaWQsIGJ5dGUpID0+IHtcbiAgICBieXRlICY9IDYzXG4gICAgaWYgKGJ5dGUgPCAzNikge1xuICAgICAgaWQgKz0gYnl0ZS50b1N0cmluZygzNilcbiAgICB9IGVsc2UgaWYgKGJ5dGUgPCA2Mikge1xuICAgICAgaWQgKz0gKGJ5dGUgLSAyNikudG9TdHJpbmcoMzYpLnRvVXBwZXJDYXNlKClcbiAgICB9IGVsc2UgaWYgKGJ5dGUgPiA2Mikge1xuICAgICAgaWQgKz0gJy0nXG4gICAgfSBlbHNlIHtcbiAgICAgIGlkICs9ICdfJ1xuICAgIH1cbiAgICByZXR1cm4gaWRcbiAgfSwgJycpXG4iLCAiaW1wb3J0IHsgY3VzdG9tQWxwaGFiZXQgfSBmcm9tIFwibmFub2lkXCI7XG5cbmNvbnN0IGFscGhhYmV0ID0gXCJhYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5elwiO1xuY29uc3QgZnVsbFN5bWJvbExpc3QgPVxuICBcIjAxMjM0NTY3ODlhYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5ekFCQ0RFRkdISUpLTE1OT1BRUlNUVVZXWFlaX1wiO1xuXG5leHBvcnQgY29uc3QgdXVpZCA9IChsZW5ndGggPSAxMik6IHN0cmluZyA9PiB7XG4gIC8vIE9OTFkgRk9SIEpFU1RcbiAgaWYgKFRBUkdFVCA9PT0gXCJKZXN0XCIpIHtcbiAgICByZXR1cm4gXCIxXCI7XG4gIH1cblxuICByZXR1cm4gKFxuICAgIGN1c3RvbUFscGhhYmV0KGFscGhhYmV0LCAxKSgpICtcbiAgICBjdXN0b21BbHBoYWJldChmdWxsU3ltYm9sTGlzdCwgbGVuZ3RoKShsZW5ndGggLSAxKVxuICApO1xufTtcbiIsICJpbXBvcnQgeyBFbGVtZW50TW9kZWwgfSBmcm9tIFwiLi4vLi4vdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgdXVpZCB9IGZyb20gXCJ1dGlscy9zcmMvdXVpZFwiO1xuXG5pbnRlcmZhY2UgRGF0YSB7XG4gIF9zdHlsZXM6IEFycmF5PHN0cmluZz47XG4gIGl0ZW1zOiBBcnJheTxFbGVtZW50TW9kZWw+O1xuICBbazogc3RyaW5nXTogc3RyaW5nIHwgQXJyYXk8c3RyaW5nIHwgRWxlbWVudE1vZGVsPjtcbn1cblxuZXhwb3J0IGNvbnN0IGNyZWF0ZUNsb25lYWJsZU1vZGVsID0gKGRhdGE6IERhdGEpOiBFbGVtZW50TW9kZWwgPT4ge1xuICBjb25zdCB7IF9zdHlsZXMsIGl0ZW1zLCAuLi52YWx1ZSB9ID0gZGF0YTtcbiAgcmV0dXJuIHtcbiAgICB0eXBlOiBcIkNsb25lYWJsZVwiLFxuICAgIHZhbHVlOiB7IF9pZDogdXVpZCgpLCBfc3R5bGVzLCBpdGVtcywgLi4udmFsdWUgfVxuICB9O1xufTtcbiIsICJcInVzZSBzdHJpY3RcIjtcbk9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBcIl9fZXNNb2R1bGVcIiwgeyB2YWx1ZTogdHJ1ZSB9KTtcbmV4cG9ydHMubGlmdEEyID0gdm9pZCAwO1xuLyoqXG4gKiBBcHBseSBhIGJpbmFyeSBmdW5jdGlvbiBvdmVyIHJlc3VsdCBvZiAyIHNpbmdsZSBmdW5jdGlvbnNcbiAqXG4gKiBFLmcuIGxpZnRBMihzdW0sIGluYywgc3FyKSgyLCAyKSA9ICgyICsgMSkgKyAoMiAqIDIpID0gMys0ID0gN1xuICovXG5mdW5jdGlvbiBsaWZ0QTIoZm4sIGYxLCBmMikge1xuICAgIHJldHVybiBmdW5jdGlvbiAoYSwgYikgeyByZXR1cm4gZm4oZjEoYSksIGYyKGIpKTsgfTtcbn1cbmV4cG9ydHMubGlmdEEyID0gbGlmdEEyO1xuIiwgIlwidXNlIHN0cmljdFwiO1xuLyoqXG4gKiBQcm92aWRlIGEgc2VyaWVzIG9mIHR5cGUgZ3VhcmQgcHJlZGljYXRlcyB0aGF0IHNhdGlzZmllcyB0aGUgaW5wdXQsXG4gKiBhbmQgYSBmdW5jdGlvbiB0aGF0IHdpbGwgcmVzb2x2ZSB0aGUgdmFsdWUgaWYgaXQgbWF0Y2hlcyB0aGUgdHlwZSBndWFyZC5cbiAqXG4gKiBJbiBvdGhlciB3b3JkcyB0aGlzIGlzIGEgdHlwZSBzYWZlIGBpZiBlbHNlYCBzdGF0ZW1lbnQuXG4gKlxuICogSW4gY2FzZSB0aGUgcHJvdmlkZWQgdHlwZSBndWFyZHMgbGlzdCBkb2Vzbid0IGNvdmVyIHRoZSBlbnRpcmUgaW5wdXQgdHlwZSxcbiAqIHlvdSdsbCBnZXQgYSB0eXBlIGVycm9yIGF0IGNvbXBpbGUgdGltZS5cbiAqL1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xuZXhwb3J0cy5tYXRjaCA9IHZvaWQgMDtcbi8vIGVuZHJlZ2lvblxuZnVuY3Rpb24gbWF0Y2goKSB7XG4gICAgdmFyIGFyZ3MgPSBbXTtcbiAgICBmb3IgKHZhciBfaSA9IDA7IF9pIDwgYXJndW1lbnRzLmxlbmd0aDsgX2krKykge1xuICAgICAgICBhcmdzW19pXSA9IGFyZ3VtZW50c1tfaV07XG4gICAgfVxuICAgIC8vIEB0cy1leHBlY3QtZXJyb3JcbiAgICByZXR1cm4gZnVuY3Rpb24gKHQpIHtcbiAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBhcmdzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICBpZiAoYXJnc1tpXVswXSh0KSkge1xuICAgICAgICAgICAgICAgIHJldHVybiBhcmdzW2ldWzFdKHQpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfTtcbn1cbmV4cG9ydHMubWF0Y2ggPSBtYXRjaDtcbiIsICJcInVzZSBzdHJpY3RcIjtcbi8qKlxuICogUHJvdmlkZSBhIHNlcmllcyBvZiB0eXBlIGd1YXJkIHByZWRpY2F0ZXMgdGhhdCBzYXRpc2ZpZXMgdGhlIGlucHV0LFxuICogYW5kIGEgZnVuY3Rpb24gdGhhdCB3aWxsIHJlc29sdmUgdGhlIHZhbHVlIGlmIGl0IG1hdGNoZXMgdGhlIHR5cGUgZ3VhcmQuXG4gKlxuICogVGhpcyBmdW5jdGlvbiBpcyBzaW1pbGFyIHRvIGBtYXRjaGAsIGJ1dCByZXNvbHZlcyAyIGFyZ3VtZW50cyBpbnB1dCBjYXNlc1xuICpcbiAqICFOb3RlOiBUaGVyZSBpcyBhIGJpZyBkaWZmZXJlbmNlIGJldHdlZW4gYG1hdGNoYCBhbmQgYG1hdGNoMmBcbiAqIGBtYXRjaDJgIGRvZXMgbm90IGZvcmNlIHlvdSB0byBwcm92aWRlIGFsbCBjb21iaW5hdGlvbnMsIHNvIGl0IGNhbiByZXR1cm4gdW5kZWZpbmVkLFxuICogIGFzIHJldHVybiB0eXBlIG1lbnRpb25zLlxuICogIFRoZXJlIGlzIHRlY2huaWNhbCBhbmQgY29kaW5nIGV4cGVyaWVuY2UgbGltaXRhdGlvbi4gSW4gb3JkZXIgdG8gbWFrZSBgbWF0Y2gyYCBiZSBzdHJpY3QgYXMgYG1hdGNoYCxcbiAqICB0aGUgdXNlIHdpbGwgaGF2ZSB0byBwcm92aWRlIGFsbCBwb3NzaWJsZSBjYXNlcyBiZXR3ZWVuIGZpcnN0IGFyZ3VtZW50IGFuZCB0aGUgc2Vjb25kIG9uZS4gQnV0IHRoaXMgaXMgYWxyZWFkeVxuICogIGNhcnRlc2lhbiBwcm9kdWN0IGFuZCBpdCBjYW4gZ2V0IGh1Z2UgdmVyeSBlYXN5LiBGb3IgM3gzIGlucHV0LCB5b3UgZ2V0IDkgY29tYmluYXRpb25zLiBBbmQgeWVzLCB5b3UgZ3Vlc3NlZCBpdCxcbiAqICBmb3IgNHg0LCAxNiBjb21iaW5hdGlvbnMuIEJ1dCBpbiByZWFsIHdvcmxkIHVzdWFsbHkgeW91IG5lZWQgNCBjb21iaW5hdGlvbnMuXG4gKiAgQnV0IGF0IHRoZSBzYW1lIG1vbWVudCBpdCBlbmZvcmNlcyB5b3UgdG8gc2F0aXNmeSBlbnRpcmUgaW5wdXQgdHlwZSBpbiBhdCBsZWFzdCBvbmUgb2YgdHlwZSBndWFyZHMuIEFuZCB0aGlzIGlzXG4gKiAgd2hhdCBtYWtlcyBpdCBkaWZmZXJlbnQgZnJvbSBgaWYgZWxzZWAgc3RhdGVtZW50LlxuICovXG5PYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgXCJfX2VzTW9kdWxlXCIsIHsgdmFsdWU6IHRydWUgfSk7XG5leHBvcnRzLm1hdGNoMiA9IHZvaWQgMDtcbi8vIGVuZHJlZ2lvblxuZnVuY3Rpb24gbWF0Y2gyKCkge1xuICAgIHZhciBhcmdzID0gW107XG4gICAgZm9yICh2YXIgX2kgPSAwOyBfaSA8IGFyZ3VtZW50cy5sZW5ndGg7IF9pKyspIHtcbiAgICAgICAgYXJnc1tfaV0gPSBhcmd1bWVudHNbX2ldO1xuICAgIH1cbiAgICAvLyBAdHMtZXhwZWN0LWVycm9yXG4gICAgcmV0dXJuIGZ1bmN0aW9uICh0LCB0Mikge1xuICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8IGFyZ3MubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgIGlmIChhcmdzW2ldWzBdKHQpICYmIGFyZ3NbaV1bMV0odDIpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGFyZ3NbaV1bMl0odCwgdDIpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfTtcbn1cbmV4cG9ydHMubWF0Y2gyID0gbWF0Y2gyO1xuIiwgIlwidXNlIHN0cmljdFwiO1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xuZXhwb3J0cy5vckVsc2UgPSBleHBvcnRzLmlzVCA9IGV4cG9ydHMuaXNOb3RoaW5nID0gdm9pZCAwO1xuLyoqXG4gKiBDaGVjayBpcyB0aGUgdmFsdWUgaXMgYSBOb3RoaW5nIHZhbHVlXG4gKiBOb3RoaW5nIGFyZSBjb25zaWRlcmVkIHVuZGVmaW5lZCBhbmQgbnVsbFxuICovXG52YXIgaXNOb3RoaW5nID0gZnVuY3Rpb24gKHYpIHsgcmV0dXJuIHYgPT09IG51bGwgfHwgdiA9PT0gdW5kZWZpbmVkOyB9O1xuZXhwb3J0cy5pc05vdGhpbmcgPSBpc05vdGhpbmc7XG4vKipcbiAqIENoZWNrIHdoZW5ldmVyIGEgcG90ZW50aWFsIG1heWJlIHZhbHVlIGlzIGVtcHR5IG9yIG5vdFxuICovXG52YXIgaXNUID0gZnVuY3Rpb24gKHQpIHsgcmV0dXJuICEoMCwgZXhwb3J0cy5pc05vdGhpbmcpKHQpOyB9O1xuZXhwb3J0cy5pc1QgPSBpc1Q7XG5mdW5jdGlvbiBvckVsc2UoKSB7XG4gICAgdmFyIGFyZ3MgPSBbXTtcbiAgICBmb3IgKHZhciBfaSA9IDA7IF9pIDwgYXJndW1lbnRzLmxlbmd0aDsgX2krKykge1xuICAgICAgICBhcmdzW19pXSA9IGFyZ3VtZW50c1tfaV07XG4gICAgfVxuICAgIHJldHVybiBhcmdzLmxlbmd0aCA9PT0gMSA/IGZ1bmN0aW9uICh2KSB7IHJldHVybiAoKDAsIGV4cG9ydHMuaXNOb3RoaW5nKSh2KSA/IGFyZ3NbMF0gOiB2KTsgfSA6ICgwLCBleHBvcnRzLmlzTm90aGluZykoYXJnc1sxXSkgPyBhcmdzWzBdIDogYXJnc1sxXTtcbn1cbmV4cG9ydHMub3JFbHNlID0gb3JFbHNlO1xuIiwgIlwidXNlIHN0cmljdFwiO1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xuZXhwb3J0cy5tUGlwZSA9IHZvaWQgMDtcbnZhciBOb3RoaW5nXzEgPSByZXF1aXJlKFwiLi9Ob3RoaW5nXCIpO1xuZnVuY3Rpb24gbVBpcGUoKSB7XG4gICAgdmFyIF9hID0gW107XG4gICAgZm9yICh2YXIgX2kgPSAwOyBfaSA8IGFyZ3VtZW50cy5sZW5ndGg7IF9pKyspIHtcbiAgICAgICAgX2FbX2ldID0gYXJndW1lbnRzW19pXTtcbiAgICB9XG4gICAgdmFyIGggPSBfYVswXSwgZm5zID0gX2Euc2xpY2UoMSk7XG4gICAgcmV0dXJuIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIF9hO1xuICAgICAgICB2YXIgYXJncyA9IFtdO1xuICAgICAgICBmb3IgKHZhciBfaSA9IDA7IF9pIDwgYXJndW1lbnRzLmxlbmd0aDsgX2krKykge1xuICAgICAgICAgICAgYXJnc1tfaV0gPSBhcmd1bWVudHNbX2ldO1xuICAgICAgICB9XG4gICAgICAgIHJldHVybiBhcmdzLmV2ZXJ5KE5vdGhpbmdfMS5pc1QpID8gKF9hID0gZm5zLnJlZHVjZShmdW5jdGlvbiAodiwgZm4pIHsgcmV0dXJuICgoMCwgTm90aGluZ18xLmlzVCkodikgPyBmbih2KSA6IHVuZGVmaW5lZCk7IH0sIGguYXBwbHkodm9pZCAwLCBhcmdzKSkpICE9PSBudWxsICYmIF9hICE9PSB2b2lkIDAgPyBfYSA6IHVuZGVmaW5lZCA6IHVuZGVmaW5lZDtcbiAgICB9O1xufVxuZXhwb3J0cy5tUGlwZSA9IG1QaXBlO1xuIiwgIlwidXNlIHN0cmljdFwiO1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xuZXhwb3J0cy5wYXNzID0gdm9pZCAwO1xuZnVuY3Rpb24gcGFzcyhwcmVkaWNhdGUpIHtcbiAgICByZXR1cm4gZnVuY3Rpb24gKHQpIHsgcmV0dXJuIChwcmVkaWNhdGUodCkgPyB0IDogdW5kZWZpbmVkKTsgfTtcbn1cbmV4cG9ydHMucGFzcyA9IHBhc3M7XG4iLCAiXCJ1c2Ugc3RyaWN0XCI7XG5PYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgXCJfX2VzTW9kdWxlXCIsIHsgdmFsdWU6IHRydWUgfSk7XG5leHBvcnRzLl9wYXJzZSA9IGV4cG9ydHMuY2FsbCA9IGV4cG9ydHMuaXNPcHRpb25hbCA9IHZvaWQgMDtcbnZhciBOb3RoaW5nXzEgPSByZXF1aXJlKFwiLi4vTm90aGluZ1wiKTtcbi8vIGVuZHJlZ2lvblxuLy8gcmVnaW9uIG9wdGlvbmFsICYgc3RyaWN0XG4vKipcbiAqIEBpbnRlcm5hbFxuICovXG52YXIgaXNPcHRpb25hbCA9IGZ1bmN0aW9uICh2KSB7XG4gICAgcmV0dXJuIHYuX190eXBlID09PSBcIm9wdGlvbmFsXCI7XG59O1xuZXhwb3J0cy5pc09wdGlvbmFsID0gaXNPcHRpb25hbDtcbi8qKlxuICogQGludGVybmFsXG4gKi9cbnZhciBjYWxsID0gZnVuY3Rpb24gKHAsIHYpIHtcbiAgICBzd2l0Y2ggKHAuX190eXBlKSB7XG4gICAgICAgIGNhc2UgXCJvcHRpb25hbFwiOlxuICAgICAgICBjYXNlIFwic3RyaWN0XCI6XG4gICAgICAgICAgICByZXR1cm4gcC5mbih2KTtcbiAgICAgICAgZGVmYXVsdDpcbiAgICAgICAgICAgIHJldHVybiBwKHYpO1xuICAgIH1cbn07XG5leHBvcnRzLmNhbGwgPSBjYWxsO1xuLyoqXG4gKiBAaW50ZXJuYWxcbiAqL1xuZnVuY3Rpb24gX3BhcnNlKHBhcnNlcnMsIG9iamVjdCkge1xuICAgIHZhciBiID0ge307XG4gICAgZm9yICh2YXIgcCBpbiBwYXJzZXJzKSB7XG4gICAgICAgIGlmICghT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKHBhcnNlcnMsIHApKSB7XG4gICAgICAgICAgICBjb250aW51ZTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgdiA9ICgwLCBleHBvcnRzLmNhbGwpKHBhcnNlcnNbcF0sIG9iamVjdCk7XG4gICAgICAgIGlmICghKDAsIGV4cG9ydHMuaXNPcHRpb25hbCkocGFyc2Vyc1twXSkgJiYgKDAsIE5vdGhpbmdfMS5pc05vdGhpbmcpKHYpKSB7XG4gICAgICAgICAgICByZXR1cm4gdW5kZWZpbmVkO1xuICAgICAgICB9XG4gICAgICAgIGJbcF0gPSB2O1xuICAgIH1cbiAgICByZXR1cm4gYjtcbn1cbmV4cG9ydHMuX3BhcnNlID0gX3BhcnNlO1xuLy8gZW5kcmVnaW9uXG4iLCAiXCJ1c2Ugc3RyaWN0XCI7XG5PYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgXCJfX2VzTW9kdWxlXCIsIHsgdmFsdWU6IHRydWUgfSk7XG5leHBvcnRzLnBhcnNlID0gZXhwb3J0cy5vcHRpb25hbCA9IHZvaWQgMDtcbnZhciBpbnRlcm5hbHNfMSA9IHJlcXVpcmUoXCIuL2ludGVybmFsc1wiKTtcbi8vIHJlZ2lvbiBvcHRpb25hbCAmIHN0cmljdFxuLyoqXG4gKiBFdmVuIGlmIHRoZSBwYXJzZXIgcmV0dXJucyBgdW5kZWZpbmVkYCwgdGhlIHBhcnNpbmcgcHJvY2VzcyB3aWxsIG5vdCBiZSBzdG9wcGVkLlxuICogSXQncyB1c2VkIHRvIHBhcnNlIGZvciB0eXBlcyB3aXRoIG9wdGlvbmFsIGtleXNcbiAqL1xudmFyIG9wdGlvbmFsID0gZnVuY3Rpb24gKHApIHsgcmV0dXJuICh7XG4gICAgX190eXBlOiBcIm9wdGlvbmFsXCIsXG4gICAgZm46IHAsXG59KTsgfTtcbmV4cG9ydHMub3B0aW9uYWwgPSBvcHRpb25hbDtcbmZ1bmN0aW9uIHBhcnNlKHBhcnNlcnMsIG9iamVjdCkge1xuICAgIHJldHVybiBvYmplY3QgPT09IHVuZGVmaW5lZFxuICAgICAgICA/IGZ1bmN0aW9uIChvKSB7IHJldHVybiAoMCwgaW50ZXJuYWxzXzEuX3BhcnNlKShwYXJzZXJzLCBvKTsgfVxuICAgICAgICA6ICgwLCBpbnRlcm5hbHNfMS5fcGFyc2UpKHBhcnNlcnMsIG9iamVjdCk7XG59XG5leHBvcnRzLnBhcnNlID0gcGFyc2U7XG4vLyBlbmRyZWdpb25cbiIsICJcInVzZSBzdHJpY3RcIjtcbk9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBcIl9fZXNNb2R1bGVcIiwgeyB2YWx1ZTogdHJ1ZSB9KTtcbmV4cG9ydHMucGFyc2VTdHJpY3QgPSB2b2lkIDA7XG52YXIgaW50ZXJuYWxzXzEgPSByZXF1aXJlKFwiLi9pbnRlcm5hbHNcIik7XG5mdW5jdGlvbiBwYXJzZVN0cmljdChwYXJzZXJzLCBvYmplY3QpIHtcbiAgICByZXR1cm4gb2JqZWN0ID09PSB1bmRlZmluZWRcbiAgICAgICAgP1xuICAgICAgICAgICAgZnVuY3Rpb24gKG8pIHsgcmV0dXJuICgwLCBpbnRlcm5hbHNfMS5fcGFyc2UpKHBhcnNlcnMsIG8pOyB9XG4gICAgICAgIDogKDAsIGludGVybmFsc18xLl9wYXJzZSkocGFyc2Vycywgb2JqZWN0KTtcbn1cbmV4cG9ydHMucGFyc2VTdHJpY3QgPSBwYXJzZVN0cmljdDtcbi8vIGVuZHJlZ2lvblxuIiwgIlwidXNlIHN0cmljdFwiO1xudmFyIF9fc3ByZWFkQXJyYXkgPSAodGhpcyAmJiB0aGlzLl9fc3ByZWFkQXJyYXkpIHx8IGZ1bmN0aW9uICh0bywgZnJvbSwgcGFjaykge1xuICAgIGlmIChwYWNrIHx8IGFyZ3VtZW50cy5sZW5ndGggPT09IDIpIGZvciAodmFyIGkgPSAwLCBsID0gZnJvbS5sZW5ndGgsIGFyOyBpIDwgbDsgaSsrKSB7XG4gICAgICAgIGlmIChhciB8fCAhKGkgaW4gZnJvbSkpIHtcbiAgICAgICAgICAgIGlmICghYXIpIGFyID0gQXJyYXkucHJvdG90eXBlLnNsaWNlLmNhbGwoZnJvbSwgMCwgaSk7XG4gICAgICAgICAgICBhcltpXSA9IGZyb21baV07XG4gICAgICAgIH1cbiAgICB9XG4gICAgcmV0dXJuIHRvLmNvbmNhdChhciB8fCBBcnJheS5wcm90b3R5cGUuc2xpY2UuY2FsbChmcm9tKSk7XG59O1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xuZXhwb3J0cy5vciA9IHZvaWQgMDtcbnZhciBOb3RoaW5nXzEgPSByZXF1aXJlKFwiLi9Ob3RoaW5nXCIpO1xuLy8gZW5kcmVnaW9uXG5mdW5jdGlvbiBvcigpIHtcbiAgICB2YXIgZm5zID0gW107XG4gICAgZm9yICh2YXIgX2kgPSAwOyBfaSA8IGFyZ3VtZW50cy5sZW5ndGg7IF9pKyspIHtcbiAgICAgICAgZm5zW19pXSA9IGFyZ3VtZW50c1tfaV07XG4gICAgfVxuICAgIC8vIEB0cy1leHBlY3QtZXJyb3IsIFRlY2huaWNhbGx5IHRoaXMgZnVuY3Rpb24gbWF5IHJldHVybiB1bmRlZmluZWQsXG4gICAgLy8gYnV0IHR5cGUgc3lzdGVtIGRvZXNuJ3QgYWxsb3cgdGhpc1xuICAgIHJldHVybiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBfYTtcbiAgICAgICAgdmFyIGFyZ3MgPSBbXTtcbiAgICAgICAgZm9yICh2YXIgX2kgPSAwOyBfaSA8IGFyZ3VtZW50cy5sZW5ndGg7IF9pKyspIHtcbiAgICAgICAgICAgIGFyZ3NbX2ldID0gYXJndW1lbnRzW19pXTtcbiAgICAgICAgfVxuICAgICAgICBmb3IgKHZhciBpID0gMDsgaSA8PSBmbnMubGVuZ3RoOyBpKyspIHtcbiAgICAgICAgICAgIHZhciB2ID0gKF9hID0gZm5zW2ldKSA9PT0gbnVsbCB8fCBfYSA9PT0gdm9pZCAwID8gdm9pZCAwIDogX2EuY2FsbC5hcHBseShfYSwgX19zcHJlYWRBcnJheShbZm5zXSwgYXJncywgZmFsc2UpKTtcbiAgICAgICAgICAgIGlmICghKDAsIE5vdGhpbmdfMS5pc05vdGhpbmcpKHYpKSB7XG4gICAgICAgICAgICAgICAgcmV0dXJuIHY7XG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9O1xufVxuZXhwb3J0cy5vciA9IG9yO1xuIiwgIlwidXNlIHN0cmljdFwiO1xudmFyIF9fY3JlYXRlQmluZGluZyA9ICh0aGlzICYmIHRoaXMuX19jcmVhdGVCaW5kaW5nKSB8fCAoT2JqZWN0LmNyZWF0ZSA/IChmdW5jdGlvbihvLCBtLCBrLCBrMikge1xuICAgIGlmIChrMiA9PT0gdW5kZWZpbmVkKSBrMiA9IGs7XG4gICAgdmFyIGRlc2MgPSBPYmplY3QuZ2V0T3duUHJvcGVydHlEZXNjcmlwdG9yKG0sIGspO1xuICAgIGlmICghZGVzYyB8fCAoXCJnZXRcIiBpbiBkZXNjID8gIW0uX19lc01vZHVsZSA6IGRlc2Mud3JpdGFibGUgfHwgZGVzYy5jb25maWd1cmFibGUpKSB7XG4gICAgICBkZXNjID0geyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGZ1bmN0aW9uKCkgeyByZXR1cm4gbVtrXTsgfSB9O1xuICAgIH1cbiAgICBPYmplY3QuZGVmaW5lUHJvcGVydHkobywgazIsIGRlc2MpO1xufSkgOiAoZnVuY3Rpb24obywgbSwgaywgazIpIHtcbiAgICBpZiAoazIgPT09IHVuZGVmaW5lZCkgazIgPSBrO1xuICAgIG9bazJdID0gbVtrXTtcbn0pKTtcbnZhciBfX2V4cG9ydFN0YXIgPSAodGhpcyAmJiB0aGlzLl9fZXhwb3J0U3RhcikgfHwgZnVuY3Rpb24obSwgZXhwb3J0cykge1xuICAgIGZvciAodmFyIHAgaW4gbSkgaWYgKHAgIT09IFwiZGVmYXVsdFwiICYmICFPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwoZXhwb3J0cywgcCkpIF9fY3JlYXRlQmluZGluZyhleHBvcnRzLCBtLCBwKTtcbn07XG5PYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgXCJfX2VzTW9kdWxlXCIsIHsgdmFsdWU6IHRydWUgfSk7XG5fX2V4cG9ydFN0YXIocmVxdWlyZShcIi4vbGlmdEEyXCIpLCBleHBvcnRzKTtcbl9fZXhwb3J0U3RhcihyZXF1aXJlKFwiLi9tYXRjaFwiKSwgZXhwb3J0cyk7XG5fX2V4cG9ydFN0YXIocmVxdWlyZShcIi4vbWF0Y2gyXCIpLCBleHBvcnRzKTtcbl9fZXhwb3J0U3RhcihyZXF1aXJlKFwiLi9tUGlwZVwiKSwgZXhwb3J0cyk7XG5fX2V4cG9ydFN0YXIocmVxdWlyZShcIi4vTm90aGluZ1wiKSwgZXhwb3J0cyk7XG5fX2V4cG9ydFN0YXIocmVxdWlyZShcIi4vcGFzc1wiKSwgZXhwb3J0cyk7XG5fX2V4cG9ydFN0YXIocmVxdWlyZShcIi4vcGFyc2Vycy9wYXJzZVwiKSwgZXhwb3J0cyk7XG5fX2V4cG9ydFN0YXIocmVxdWlyZShcIi4vcGFyc2Vycy9wYXJzZVN0cmljdFwiKSwgZXhwb3J0cyk7XG5fX2V4cG9ydFN0YXIocmVxdWlyZShcIi4vb3JcIiksIGV4cG9ydHMpO1xuIiwgImltcG9ydCB7IE9ialdpdGhVbmtub3ducywgUmVhZGVyIH0gZnJvbSBcIi4vdHlwZXNcIjtcblxuZXhwb3J0IGNvbnN0IGlzT2JqZWN0ID0gKHY6IHVua25vd24pOiB2IGlzIFJlY29yZDxzdHJpbmcsIHVua25vd24+ID0+XG4gIHR5cGVvZiB2ID09PSBcIm9iamVjdFwiICYmIHYgIT09IG51bGw7XG5cbmV4cG9ydCBjb25zdCBoYXNLZXkgPSA8VCBleHRlbmRzIHN0cmluZz4oXG4gIGtleTogVCxcbiAgb2JqOiBSZWNvcmQ8c3RyaW5nLCB1bmtub3duPlxuKTogb2JqIGlzIE9ialdpdGhVbmtub3duczxUPiA9PiBrZXkgaW4gb2JqO1xuXG5leHBvcnQgY29uc3QgcmVhZEtleSA9XG4gIChrZXk6IHN0cmluZykgPT5cbiAgLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIEB0eXBlc2NyaXB0LWVzbGludC9uby1leHBsaWNpdC1hbnlcbiAgKG9iajogUmVjb3JkPHN0cmluZywgYW55Pik6IHVua25vd24gPT5cbiAgICBoYXNLZXkoa2V5LCBvYmopID8gb2JqW2tleV0gOiB1bmRlZmluZWQ7XG5cbmV4cG9ydCBjb25zdCByZWFkOiBSZWFkZXI8UmVjb3JkPHN0cmluZywgdW5rbm93bj4+ID0gKHYpID0+IHtcbiAgaWYgKGlzT2JqZWN0KHYpKSB7XG4gICAgcmV0dXJuIHY7XG4gIH1cblxuICByZXR1cm4gdW5kZWZpbmVkO1xufTtcbiIsICJpbXBvcnQgeyBSZWFkZXIgfSBmcm9tIFwiLi90eXBlc1wiO1xuXG5leHBvcnQgY29uc3QgcmVhZDogUmVhZGVyPHN0cmluZz4gPSAodikgPT4ge1xuICBzd2l0Y2ggKHR5cGVvZiB2KSB7XG4gICAgY2FzZSBcInN0cmluZ1wiOlxuICAgICAgcmV0dXJuIHY7XG4gICAgY2FzZSBcIm51bWJlclwiOlxuICAgICAgcmV0dXJuIGlzTmFOKHYpID8gdW5kZWZpbmVkIDogdi50b1N0cmluZygpO1xuICAgIGRlZmF1bHQ6XG4gICAgICByZXR1cm4gdW5kZWZpbmVkO1xuICB9XG59O1xuXG5leHBvcnQgY29uc3QgcmVhZE9ubHlTdHJpbmc6IFJlYWRlcjxzdHJpbmc+ID0gKGEpID0+IHtcbiAgc3dpdGNoICh0eXBlb2YgYSkge1xuICAgIGNhc2UgXCJzdHJpbmdcIjpcbiAgICAgIHJldHVybiBhO1xuICAgIGNhc2UgXCJudW1iZXJcIjpcbiAgICAgIHJldHVybiB1bmRlZmluZWQ7XG4gICAgZGVmYXVsdDpcbiAgICAgIHJldHVybiB1bmRlZmluZWQ7XG4gIH1cbn07XG5cbmV4cG9ydCBjb25zdCBpcyA9IChzOiB1bmtub3duKTogcyBpcyBzdHJpbmcgPT4ge1xuICByZXR1cm4gdHlwZW9mIHMgPT09IFwic3RyaW5nXCI7XG59O1xuIiwgImltcG9ydCB7IG1QaXBlIH0gZnJvbSBcImZwLXV0aWxpdGllc1wiO1xuaW1wb3J0IHsgQ29sb3IgfSBmcm9tIFwidXRpbHMvc3JjL2NvbG9yL3BhcnNlQ29sb3JTdHJpbmdcIjtcbmltcG9ydCAqIGFzIE9iaiBmcm9tIFwidXRpbHMvc3JjL3JlYWRlci9vYmplY3RcIjtcbmltcG9ydCAqIGFzIFN0ciBmcm9tIFwidXRpbHMvc3JjL3JlYWRlci9zdHJpbmdcIjtcblxuZXhwb3J0IGNvbnN0IGFsbG93ZWRUYWdzID0gW1xuICBcIlBcIixcbiAgXCJIMVwiLFxuICBcIkgyXCIsXG4gIFwiSDNcIixcbiAgXCJINFwiLFxuICBcIkg1XCIsXG4gIFwiSDZcIixcbiAgXCJVTFwiLFxuICBcIk9MXCIsXG4gIFwiTElcIlxuXTtcblxuZXhwb3J0IGNvbnN0IGV4Y2VwdEV4dHJhY3RpbmdTdHlsZSA9IFtcIlVMXCIsIFwiT0xcIl07XG5cbmV4cG9ydCBjb25zdCBkZWZhdWx0RGVza3RvcExpbmVIZWlnaHQgPSBcIjFfM1wiO1xuXG5leHBvcnQgY29uc3QgZGVmYXVsdFRhYmxldExpbmVIZWlnaHQgPSBcIjFfMlwiO1xuXG5leHBvcnQgY29uc3QgZGVmYXVsdE1vYmlsZUxpbmVIZWlnaHQgPSBcIjFfMlwiO1xuXG5leHBvcnQgY29uc3QgZXh0cmFjdGVkQXR0cmlidXRlcyA9IFtcbiAgXCJmb250LXNpemVcIixcbiAgXCJmb250LWZhbWlseVwiLFxuICBcImZvbnQtd2VpZ2h0XCIsXG4gIFwiZm9udC1zdHlsZVwiLFxuICBcImxpbmUtaGVpZ2h0XCIsXG4gIFwidGV4dC1hbGlnblwiLFxuICBcImxldHRlci1zcGFjaW5nXCIsXG4gIFwidGV4dC10cmFuc2Zvcm1cIlxuXTtcblxuZXhwb3J0IGNvbnN0IHRleHRBbGlnbjogUmVjb3JkPHN0cmluZywgc3RyaW5nPiA9IHtcbiAgXCItd2Via2l0LWNlbnRlclwiOiBcImNlbnRlclwiLFxuICBcIi1tb3otY2VudGVyXCI6IFwiY2VudGVyXCIsXG4gIHN0YXJ0OiBcImxlZnRcIixcbiAgZW5kOiBcInJpZ2h0XCIsXG4gIGxlZnQ6IFwibGVmdFwiLFxuICByaWdodDogXCJyaWdodFwiLFxuICBjZW50ZXI6IFwiY2VudGVyXCIsXG4gIGp1c3RpZnk6IFwianVzdGlmeVwiXG59O1xuXG5leHBvcnQgZnVuY3Rpb24gc2hvdWxkRXh0cmFjdEVsZW1lbnQoXG4gIGVsZW1lbnQ6IEVsZW1lbnQsXG4gIGV4Y2VwdGlvbnM6IEFycmF5PHN0cmluZz5cbik6IGJvb2xlYW4ge1xuICBjb25zdCBpc0FsbG93ZWQgPSBhbGxvd2VkVGFncy5pbmNsdWRlcyhlbGVtZW50LnRhZ05hbWUpO1xuXG4gIGlmIChpc0FsbG93ZWQgJiYgZXhjZXB0aW9ucykge1xuICAgIHJldHVybiAhZXhjZXB0aW9ucy5pbmNsdWRlcyhlbGVtZW50LnRhZ05hbWUpO1xuICB9XG5cbiAgcmV0dXJuIGlzQWxsb3dlZDtcbn1cblxuZXhwb3J0IGNvbnN0IGljb25TZWxlY3RvciA9XG4gIFwiW2RhdGEtc29jaWFsaWNvbl0sW3N0eWxlKj1cXFwiZm9udC1mYW1pbHk6ICdNb25vIFNvY2lhbCBJY29ucyBGb250J1xcXCJdLFtkYXRhLWljb25dXCI7XG5leHBvcnQgY29uc3QgYnV0dG9uU2VsZWN0b3IgPSBcIi5zaXRlcy1idXR0b246bm90KC5uYXYtbWVudS1idXR0b24pXCI7XG5leHBvcnQgY29uc3QgZW1iZWRTZWxlY3RvciA9IFwiLmVtYmVkZGVkLXBhc3RlXCI7XG5cbmV4cG9ydCBjb25zdCBleHRyYWN0VXJsV2l0aG91dERvbWFpbiA9ICh1cmw6IHN0cmluZykgPT4ge1xuICBjb25zdCB1cmxPYmplY3QgPSBuZXcgVVJMKHVybCk7XG5cbiAgY29uc3QgX3VybCA9XG4gICAgdXJsT2JqZWN0Lm9yaWdpbiA9PT0gd2luZG93LmxvY2F0aW9uLm9yaWdpblxuICAgICAgPyB1cmxPYmplY3QucGF0aG5hbWVcbiAgICAgIDogdXJsT2JqZWN0LmhyZWY7XG5cbiAgcmV0dXJuIF91cmw7XG59O1xuXG5leHBvcnQgY29uc3QgZ2V0SHJlZiA9IG1QaXBlKFxuICBPYmoucmVhZEtleShcImhyZWZcIiksXG4gIFN0ci5yZWFkLFxuICBleHRyYWN0VXJsV2l0aG91dERvbWFpblxuKTtcblxuZXhwb3J0IGNvbnN0IGdldFRhcmdldCA9IG1QaXBlKE9iai5yZWFkS2V5KFwidGFyZ2V0XCIpLCBTdHIucmVhZCk7XG5cbmV4cG9ydCBjb25zdCBub3JtYWxpemVPcGFjaXR5ID0gKGNvbG9yOiBDb2xvcik6IENvbG9yID0+IHtcbiAgY29uc3QgeyBoZXgsIG9wYWNpdHkgfSA9IGNvbG9yO1xuXG4gIHJldHVybiB7XG4gICAgaGV4LFxuICAgIG9wYWNpdHk6IGhleCA9PT0gXCIjZmZmZmZmXCIgJiYgb3BhY2l0eSA9PT0gXCIxXCIgPyBcIjAuOTlcIiA6IG9wYWNpdHlcbiAgfTtcbn07XG5cbmV4cG9ydCBjb25zdCBlbmNvZGVUb1N0cmluZyA9IDxUPih2YWx1ZTogVCk6IHN0cmluZyA9PiB7XG4gIHJldHVybiBlbmNvZGVVUklDb21wb25lbnQoSlNPTi5zdHJpbmdpZnkodmFsdWUpKTtcbn07XG4iLCAiaW1wb3J0IHsgTGl0ZXJhbCwgTVZhbHVlIH0gZnJvbSBcInV0aWxzXCI7XG5cbmV4cG9ydCBjb25zdCBnZXRHbG9iYWxCdXR0b25Nb2RlbCA9ICgpOiBNVmFsdWU8UmVjb3JkPHN0cmluZywgTGl0ZXJhbD4+ID0+IHtcbiAgcmV0dXJuIHdpbmRvdy5idXR0b25Nb2RlbDtcbn07XG4iLCAiaW1wb3J0IHsgTGl0ZXJhbCwgTVZhbHVlIH0gZnJvbSBcInV0aWxzXCI7XG5cbmV4cG9ydCBjb25zdCBnZXRHbG9iYWxJY29uTW9kZWwgPSAoKTogTVZhbHVlPFJlY29yZDxzdHJpbmcsIExpdGVyYWw+PiA9PiB7XG4gIHJldHVybiB3aW5kb3cuaWNvbk1vZGVsO1xufTtcbiIsICJleHBvcnQgY29uc3QgZGVmYXVsdEljb24gPSBcImZhdm91cml0ZS0zMVwiO1xuXG5leHBvcnQgY29uc3QgY29kZVRvQnVpbGRlck1hcDogUmVjb3JkPHN0cmluZywgc3RyaW5nPiA9IHtcbiAgLy8jcmVnaW9uIE5vIGljb25zIG9uIG91ciBzaWRlXG5cbiAgdGhlY2l0eTogZGVmYXVsdEljb24sXG4gIDU3NjgwOiBkZWZhdWx0SWNvbixcblxuICB0YWJsZXByb2plY3Q6IGRlZmF1bHRJY29uLFxuICA1NzY4MTogZGVmYXVsdEljb24sXG5cbiAgY2lyY2xlZmVlZGJ1cm5lcjogZGVmYXVsdEljb24sXG4gIDU3ODk2OiBkZWZhdWx0SWNvbixcblxuICBjaXJjbGV0aGVjaXR5OiBkZWZhdWx0SWNvbixcbiAgNTgxOTI6IGRlZmF1bHRJY29uLFxuXG4gIGNpcmNsZXRhYmxlcHJvamVjdDogZGVmYXVsdEljb24sXG4gIDU4MTkzOiBkZWZhdWx0SWNvbixcblxuICByb3VuZGVkYmxpcDogZGVmYXVsdEljb24sXG4gIDU4Mzg1OiBkZWZhdWx0SWNvbixcblxuICByb3VuZGVkZmVlZGJ1cm5lcjogZGVmYXVsdEljb24sXG4gIDU4NDA4OiBkZWZhdWx0SWNvbixcblxuICByb3VuZGVkdGhlY2l0eTogZGVmYXVsdEljb24sXG4gIDU4NzA0OiBkZWZhdWx0SWNvbixcblxuICByb3VuZGVkdGFibGVwcm9qZWN0OiBkZWZhdWx0SWNvbixcbiAgNTg3MDU6IGRlZmF1bHRJY29uLFxuXG4gIC8vI2VuZHJlZ2lvblxuXG4gIGFwcGxlOiBcImFwcGxlXCIsXG4gIDU3MzUxOiBcImFwcGxlXCIsXG4gIDYxODE3OiBcImFwcGxlXCIsXG5cbiAgNTc2ODY6IFwibWFwLW1hcmtlci1hbHRcIixcblxuICBtYWlsOiBcImVudmVsb3BlXCIsXG4gIDU3ODkyOiBcImVudmVsb3BlXCIsXG4gIDU3MzgwOiBcImVudmVsb3BlXCIsXG5cbiAgNTc5MzY6IFwibXVzaWNcIixcblxuICBmYWNlYm9vazogXCJmYWNlYm9vay1zcXVhcmVcIixcbiAgNTc4OTU6IFwiZmFjZWJvb2stc3F1YXJlXCIsXG4gIDU4NDA3OiBcImZhY2Vib29rLXNxdWFyZVwiLFxuICA2MTU3MDogXCJmYWNlYm9vay1zcXVhcmVcIixcbiAgNjE1OTQ6IFwiZmFjZWJvb2tcIixcblxuICB5b3V0dWJlOiBcInlvdXR1YmVcIixcbiAgNTgwMDk6IFwieW91dHViZVwiLFxuICA1ODUyMTogXCJ5b3V0dWJlXCIsXG4gIDYyNTEzOiBcInlvdXR1YmVcIixcblxuICB2aW1lbzogXCJ2aW1lby12XCIsXG4gIDU3OTkzOiBcInZpbWVvLXZcIixcblxuICB0d2l0dGVyOiBcInR3aXR0ZXJcIixcbiAgNTc5OTA6IFwidHdpdHRlclwiLFxuICA1ODUwMzogXCJ0d2l0dGVyXCIsXG4gIDU3OTkxOiBcInR3aXR0ZXJcIixcblxuICBpbnN0YWdyYW06IFwiaW5zdGFncmFtXCIsXG4gIDU4NjI0OiBcImluc3RhZ3JhbVwiLFxuICA1ODExMjogXCJpbnN0YWdyYW1cIixcbiAgNjE4MDU6IFwiaW5zdGFncmFtXCIsXG5cbiAgNTgyMTE6IFwiYXJyb3ctYWx0LWNpcmNsZS1yaWdodFwiLFxuXG4gIDYzMjQ0OiBcInVzZXItcnVuXCIsXG5cbiAgYWRkbWU6IFwicGx1c1wiLFxuICA1NzM0NjogXCJwbHVzXCIsXG5cbiAgYXBwc3RvcmVhbHQ6IFwiYXJyb3ctYWx0LWNpcmNsZS1kb3duXCIsXG4gIDU3MzQ5OiBcImFycm93LWFsdC1jaXJjbGUtZG93blwiLFxuXG4gIGFwcHN0b3JlOiBcImFwcC1zdG9yZVwiLFxuICA1NzM1MDogXCJhcHAtc3RvcmVcIixcblxuICBibG9nZ2VyOiBcImJsb2dnZXItYlwiLFxuICA1NzM2MjogXCJibG9nZ2VyLWJcIixcblxuICBldHN5OiBcImV0c3lcIixcbiAgNTczODI6IFwiZXRzeVwiLFxuXG4gIDU3MzgzOiBcImZhY2Vib29rLWZcIixcblxuICBmb3Vyc3F1YXJlOiBcImZvdXJzcXVhcmVcIixcbiAgNTczOTQ6IFwiZm91cnNxdWFyZVwiLFxuXG4gIGZsaWNrcjogXCJmbGlja3JcIixcbiAgNTczODU6IFwiZmxpY2tyXCIsXG5cbiAgZ29vZ2xlcGx1czogXCJnb29nbGUtcGx1cy1nXCIsXG4gIDU3NDAxOiBcImdvb2dsZS1wbHVzLWdcIixcblxuICBnb3dhbGxhcGluOiBcIm1hcC1waW5cIixcbiAgNTc0MDk6IFwibWFwLXBpblwiLFxuXG4gIGhlYXJ0OiBcImhlYXJ0XCIsXG4gIDU3NDEyOiBcImhlYXJ0XCIsXG4gIDYxNDQ0OiBcImhlYXJ0XCIsXG5cbiAgaW1lc3NhZ2U6IFwiY29tbWVudFwiLFxuICA1NzQxNzogXCJjb21tZW50XCIsXG5cbiAgaXR1bmVzOiBcIml0dW5lc1wiLFxuICA1NzQyNDogXCJpdHVuZXNcIixcbiAgNjIzODg6IFwiaXR1bmVzXCIsXG5cbiAgbGFzdGZtOiBcImxhc3RmbVwiLFxuICA1NzQyNTogXCJsYXN0Zm1cIixcblxuICBsaW5rZWRpbjogXCJsaW5rZWRpbi1pblwiLFxuICA1NzQyNjogXCJsaW5rZWRpbi1pblwiLFxuXG4gIG1lZXR1cDogXCJtZWV0dXBcIixcbiAgNTc0Mjc6IFwibWVldHVwXCIsXG5cbiAgbXlzcGFjZTogXCJwZW9wbGUtZ3JvdXBcIixcbiAgNTc0MzM6IFwicGVvcGxlLWdyb3VwXCIsXG5cbiAgcGF5cGFsOiBcInBheXBhbFwiLFxuICA1NzQ0MTogXCJwYXlwYWxcIixcbiAgNjE5MzM6IFwicGF5cGFsXCIsXG5cbiAgcGludGVyZXN0OiBcInBpbnRlcmVzdC1wXCIsXG4gIDU3NDQ0OiBcInBpbnRlcmVzdC1wXCIsXG4gIDYxNjUwOiBcInBpbnRlcmVzdFwiLFxuXG4gIHBvZGNhc3Q6IFwicG9kY2FzdFwiLFxuICA1NzQ0NTogXCJwb2RjYXN0XCIsXG5cbiAgcnNzOiBcInJzc1wiLFxuICA1NzQ1NzogXCJyc3NcIixcblxuICBzaGFyZXRoaXM6IFwic2hhcmUtYWx0XCIsXG4gIDU3NDU5OiBcInNoYXJlLWFsdFwiLFxuXG4gIHNreXBlOiBcInNreXBlXCIsXG4gIDU3NDYwOiBcInNreXBlXCIsXG5cbiAgc2xpZGVzaGFyZTogXCJzbGlkZXNoYXJlXCIsXG4gIDU3NDYyOiBcInNsaWRlc2hhcmVcIixcblxuICBzb3VuZGNsb3VkOiBcInNvdW5kY2xvdWRcIixcbiAgNTc0NjQ6IFwic291bmRjbG91ZFwiLFxuXG4gIHNwb3RpZnk6IFwic3BvdGlmeVwiLFxuICA1NzQ2NTogXCJzcG90aWZ5XCIsXG5cbiAgc3RhcjogXCJzdGFyXCIsXG4gIDU3NDc0OiBcInN0YXJcIixcblxuICB0dW1ibHI6IFwidHVtYmxyXCIsXG4gIDU3NDc3OiBcInR1bWJsclwiLFxuXG4gIHR3aXR0ZXJiaXJkOiBcInR3aXR0ZXJcIixcbiAgNTc0Nzg6IFwidHdpdHRlclwiLFxuICA1NzQ3OTogXCJ0d2l0dGVyXCIsXG5cbiAgNTc0ODE6IFwidmltZW8tdlwiLFxuXG4gIHdvcmRwcmVzczogXCJ3b3JkcHJlc3NcIixcbiAgNTc0OTI6IFwid29yZHByZXNzXCIsXG5cbiAgeWVscDogXCJ5ZWxwXCIsXG4gIDU3NDk2OiBcInllbHBcIixcblxuICA1NzQ5NzogXCJ5b3V0dWJlXCIsXG5cbiAgNTc2MDA6IFwiaW5zdGFncmFtXCIsXG5cbiAgYm9va21hcms6IFwiYm9va21hcmtcIixcbiAgNTc2ODI6IFwiYm9va21hcmtcIixcbiAgNjE0ODY6IFwiYm9va21hcmtcIixcblxuICBldXJvOiBcImV1cm8tc2lnblwiLFxuICA1NzY4MzogXCJldXJvLXNpZ25cIixcblxuICBwb3VuZDogXCJwb3VuZC1zaWduXCIsXG4gIDU3Njg0OiBcInBvdW5kLXNpZ25cIixcblxuICBjYXNoOiBcImRvbGxhci1zaWduXCIsXG4gIDU3Njg1OiBcImRvbGxhci1zaWduXCIsXG5cbiAgbWFwOiBcIm1hcC1tYXJrZXItYWx0XCIsXG4gIDYyMDczOiBcIm1hcFwiLFxuXG4gIHZpZGVvOiBcInBsYXlcIixcbiAgNTc2ODc6IFwicGxheVwiLFxuXG4gIGdvb2dsZXBsYXk6IFwiZ29vZ2xlLXBsYXlcIixcbiAgNTc2OTY6IFwiZ29vZ2xlLXBsYXlcIixcblxuICBjaW5lbWE6IFwiZmlsbVwiLFxuICA1NzY5NzogXCJmaWxtXCIsXG4gIDU3NzAzOiBcImZpbG1cIixcblxuICB1cGFycm93OiBcImFycm93LXVwXCIsXG4gIDU3Njk4OiBcImFycm93LXVwXCIsXG5cbiAgcmlnaHRhcnJvdzogXCJhcnJvdy1yaWdodFwiLFxuICA1NzY5OTogXCJhcnJvdy1yaWdodFwiLFxuXG4gIGxlZnRhcnJvdzogXCJhcnJvdy1sZWZ0XCIsXG4gIDU3NzAyOiBcImFycm93LWxlZnRcIixcblxuICBkb3duYXJyb3c6IFwiYXJyb3ctZG93blwiLFxuICA1NzcwNDogXCJhcnJvdy1kb3duXCIsXG5cbiAgcmVjb3JkOiBcInZpZGVvXCIsXG4gIDU3NzAwOiBcInZpZGVvXCIsXG5cbiAgbWFwMjogXCJtYXAtbWFya2VkLWFsdFwiLFxuICA1NzcwMTogXCJtYXAtbWFya2VkLWFsdFwiLFxuXG4gIGNpcmNsZWFkZG1lOiBcInBsdXMtY2lyY2xlXCIsXG4gIDU3ODU4OiBcInBsdXMtY2lyY2xlXCIsXG5cbiAgY2lyY2xlYXBwc3RvcmVhbHQ6IFwiYXJyb3ctYWx0LWNpcmNsZS1kb3duXCIsXG4gIDU3ODYxOiBcImFycm93LWFsdC1jaXJjbGUtZG93blwiLFxuXG4gIGNpcmNsZWFwcHN0b3JlOiBcImFwcC1zdG9yZVwiLFxuICA1Nzg2MjogXCJhcHAtc3RvcmVcIixcblxuICBjaXJjbGVhcHBsZTogXCJhcHAtc3RvcmVcIixcbiAgNTc4NjM6IFwiYXBwLXN0b3JlXCIsXG5cbiAgY2lyY2xlYmxvZ2dlcjogXCJibG9nZ2VyLWJcIixcbiAgNTc4NzQ6IFwiYmxvZ2dlci1iXCIsXG5cbiAgY2lyY2xlZW1haWw6IFwiZW52ZWxvcGVcIixcblxuICBjaXJjbGVldHN5OiBcImV0c3lcIixcbiAgNTc4OTQ6IFwiZXRzeVwiLFxuXG4gIGNpcmNsZWZhY2Vib29rOiBcImZhY2Vib29rLWZcIixcblxuICBjaXJjbGVmbGlja3I6IFwiZmxpY2tyXCIsXG4gIDU3ODk3OiBcImZsaWNrclwiLFxuXG4gIGNpcmNsZWZvdXJzcXVhcmU6IFwiZm91cnNxdWFyZVwiLFxuICA1NzkwNjogXCJmb3Vyc3F1YXJlXCIsXG5cbiAgY2lyY2xlZ29vZ2xlcGx1czogXCJnb29nbGUtcGx1c1wiLFxuICA1NzkxMzogXCJnb29nbGUtcGx1c1wiLFxuXG4gIGNpcmNsZWdvd2FsbGFwaW46IFwibWFwLXBpblwiLFxuICA1NzkyMTogXCJtYXAtcGluXCIsXG5cbiAgY2lyY2xlaGVhcnQ6IFwiaGVhcnRcIixcbiAgNTc5MjQ6IFwiaGVhcnRcIixcblxuICBjaXJjbGVpbWVzc2FnZTogXCJjb21tZW50XCIsXG4gIDU3OTI5OiBcImNvbW1lbnRcIixcblxuICBjaXJjbGVpdHVuZXM6IFwiaXR1bmVzXCIsXG5cbiAgY2lyY2xlbGFzdGZtOiBcImxhc3RmbVwiLFxuICA1NzkzNzogXCJsYXN0Zm1cIixcblxuICBjaXJjbGVsaW5rZWRpbjogXCJsaW5rZWRpblwiLFxuICA1NzkzODogXCJsaW5rZWRpblwiLFxuXG4gIGNpcmNsZW1lZXR1cDogXCJtZWV0dXBcIixcbiAgNTc5Mzk6IFwibWVldHVwXCIsXG5cbiAgY2lyY2xlbXlzcGFjZTogXCJwZW9wbGUtZ3JvdXBcIixcbiAgNTc5NDU6IFwicGVvcGxlLWdyb3VwXCIsXG5cbiAgY2lyY2xlcGF5cGFsOiBcInBheXBhbFwiLFxuICA1Nzk1MzogXCJwYXlwYWxcIixcblxuICBjaXJjbGVwaW50ZXJlc3Q6IFwicGludGVyZXN0XCIsXG4gIDU3OTU2OiBcInBpbnRlcmVzdFwiLFxuXG4gIGNpcmNsZXBvZGNhc3Q6IFwicG9kY2FzdFwiLFxuICA1Nzk1NzogXCJwb2RjYXN0XCIsXG5cbiAgY2lyY2xlcnNzOiBcInJzc1wiLFxuICA1Nzk2OTogXCJyc3NcIixcblxuICBjaXJjbGVzaGFyZXRoaXM6IFwic2hhcmUtYWx0XCIsXG4gIDU3OTcxOiBcInNoYXJlLWFsdFwiLFxuXG4gIGNpcmNsZXNreXBlOiBcInNreXBlXCIsXG4gIDU3OTcyOiBcInNreXBlXCIsXG5cbiAgY2lyY2xlc2xpZGVzaGFyZTogXCJzbGlkZXNoYXJlXCIsXG4gIDU3OTc0OiBcInNsaWRlc2hhcmVcIixcblxuICBjaXJjbGVzb3VuZGNsb3VkOiBcInNvdW5kY2xvdWRcIixcbiAgNTc5NzY6IFwic291bmRjbG91ZFwiLFxuXG4gIGNpcmNsZXNwb3RpZnk6IFwic3BvdGlmeVwiLFxuICA1Nzk3NzogXCJzcG90aWZ5XCIsXG5cbiAgY2lyY2xlc3RhcjogXCJzdGFyXCIsXG4gIDU3OTg2OiBcInN0YXJcIixcblxuICBjaXJjbGV0dW1ibHI6IFwidHVtYmxyXCIsXG4gIDU3OTg5OiBcInR1bWJsclwiLFxuXG4gIGNpcmNsZXR3aXR0ZXJiaXJkOiBcInR3aXR0ZXJcIixcblxuICBjaXJjbGV2aW1lbzogXCJ2aW1lby12XCIsXG5cbiAgY2lyY2xld29yZHByZXNzOiBcIndvcmRwcmVzc1wiLFxuICA1ODAwNDogXCJ3b3JkcHJlc3NcIixcblxuICBjaXJjbGV5ZWxwOiBcInllbHBcIixcbiAgNTgwMDg6IFwieWVscFwiLFxuXG4gIGNpcmNsZXlvdXR1YmU6IFwieW91dHViZVwiLFxuXG4gIGNpcmNsZWluc3RhZ3JhbTogXCJpbnN0YWdyYW1cIixcblxuICBjaXJjbGVib29rbWFyazogXCJib29rbWFya1wiLFxuICA1ODE5NDogXCJib29rbWFya1wiLFxuXG4gIGNpcmNsZWV1cm86IFwiZXVyby1zaWduXCIsXG4gIDU4MTk1OiBcImV1cm8tc2lnblwiLFxuXG4gIGNpcmNsZXBvdW5kOiBcInBvdW5kLXNpZ25cIixcbiAgNTgxOTY6IFwicG91bmQtc2lnblwiLFxuXG4gIGNpcmNsZWNhc2g6IFwiZG9sbGFyLXNpZ25cIixcbiAgNTgxOTc6IFwiZG9sbGFyLXNpZ25cIixcblxuICBjaXJjbGVtYXA6IFwibWFwLW1hcmtlci1hbHRcIixcbiAgNTgxOTg6IFwibWFwLW1hcmtlci1hbHRcIixcblxuICBjaXJjbGV2aWRlbzogXCJwbGF5LWNpcmNsZVwiLFxuICA1ODE5OTogXCJwbGF5LWNpcmNsZVwiLFxuXG4gIGNpcmNsZWdvb2dsZXBsYXk6IFwiZ29vZ2xlLXBsYXlcIixcbiAgNTgyMDg6IFwiZ29vZ2xlLXBsYXlcIixcblxuICBjaXJjbGVjaW5lbWE6IFwiZmlsbVwiLFxuICBjaXJjbGVmaWxtOiBcImZpbG1cIixcbiAgNTgyMDk6IFwiZmlsbVwiLFxuICA1ODIxNTogXCJmaWxtXCIsXG5cbiAgY2lyY2xldXBhcnJvdzogXCJhcnJvdy1jaXJjbGUtdXBcIixcbiAgNTgyMTA6IFwiYXJyb3ctY2lyY2xlLXVwXCIsXG5cbiAgY2lyY2xlcmlnaHRhcnJvdzogXCJhcnJvdy1jaXJjbGUtcmlnaHRcIixcblxuICBjaXJjbGVyZWNvcmQ6IFwidmlkZW9cIixcbiAgNTgyMTI6IFwidmlkZW9cIixcblxuICBjaXJjbGVtYXAyOiBcIm1hcC1tYXJrZWQtYWx0XCIsXG4gIDU4MjEzOiBcIm1hcC1tYXJrZWQtYWx0XCIsXG5cbiAgY2lyY2xlbGVmdGFycm93OiBcImFycm93LWNpcmNsZS1sZWZ0XCIsXG4gIDU4MjE0OiBcImFycm93LWNpcmNsZS1sZWZ0XCIsXG5cbiAgY2lyY2xlZG93bmFycm93OiBcImFycm93LWNpcmNsZS1kb3duXCIsXG4gIDU4MjE2OiBcImFycm93LWNpcmNsZS1kb3duXCIsXG5cbiAgcm91bmRlZGFkZG1lOiBcInBsdXMtY2lyY2xlXCIsXG4gIDU4MzcwOiBcInBsdXMtY2lyY2xlXCIsXG5cbiAgcm91bmRlZGFwcHN0b3JlYWx0OiBcImFycm93LWNpcmNsZS1kb3duXCIsXG4gIDU4MzczOiBcImFycm93LWNpcmNsZS1kb3duXCIsXG5cbiAgcm91bmRlZGFwcHN0b3JlOiBcImFwcC1zdG9yZVwiLFxuICA1ODM3NDogXCJhcHAtc3RvcmVcIixcblxuICByb3VuZGVkYXBwbGU6IFwiYXBwbGVcIixcbiAgNTgzNzU6IFwiYXBwbGVcIixcblxuICByb3VuZGVkYmxvZ2dlcjogXCJibG9nZ2VyXCIsXG4gIDU4Mzg2OiBcImJsb2dnZXJcIixcblxuICByb3VuZGVkZW1haWw6IFwiZW52ZWxvcGUtc3F1YXJlXCIsXG4gIDU4NDA0OiBcImVudmVsb3BlLXNxdWFyZVwiLFxuXG4gIHJvdW5kZWRmYWNlYm9vazogXCJmYWNlYm9vay1zcXVhcmVcIixcblxuICByb3VuZGVkZmxpY2tyOiBcImZsaWNrclwiLFxuICA1ODQwOTogXCJmbGlja3JcIixcblxuICByb3VuZGVkZm91cnNxdWFyZTogXCJmb3Vyc3F1YXJlXCIsXG4gIDU4NDE4OiBcImZvdXJzcXVhcmVcIixcblxuICByb3VuZGVkZ29vZ2xlcGx1czogXCJnb29nbGUtcGx1cy1zcXVhclwiLFxuICA1ODQyNTogXCJnb29nbGUtcGx1cy1zcXVhclwiLFxuXG4gIHJvdW5kZWRnb3dhbGxhcGluOiBcIm1hcC1waW5cIixcbiAgNTg0MzM6IFwibWFwLXBpblwiLFxuXG4gIHJvdW5kZWRoZWFydDogXCJoZWFydFwiLFxuICA1ODQzNjogXCJoZWFydFwiLFxuXG4gIHJvdW5kZWRpbWVzc2FnZTogXCJjb21tZW50XCIsXG4gIDU4NDQxOiBcImNvbW1lbnRcIixcblxuICByb3VuZGVkaXR1bmVzOiBcIml0dW5lc1wiLFxuICA1ODQ0ODogXCJpdHVuZXNcIixcblxuICByb3VuZGVkbGFzdGZtOiBcImxhc3RmbVwiLFxuICA1ODQ0OTogXCJsYXN0Zm1cIixcblxuICByb3VuZGVkbGlua2VkaW46IFwibGlua2VkaW5cIixcbiAgNTg0NTA6IFwibGlua2VkaW5cIixcblxuICByb3VuZGVkbWVldHVwOiBcIm1lZXR1cFwiLFxuICA1ODQ1MTogXCJtZWV0dXBcIixcblxuICByb3VuZGVkbXlzcGFjZTogXCJwZW9wbGUtZ3JvdXBcIixcbiAgNTg0NTc6IFwicGVvcGxlLWdyb3VwXCIsXG5cbiAgcm91bmRlZHBheXBhbDogXCJwYXlwYWxcIixcbiAgNTg0NjU6IFwicGF5cGFsXCIsXG5cbiAgcm91bmRlZHBpbnRlcmVzdDogXCJwaW50ZXJlc3Qtc3F1YXJlXCIsXG4gIDU4NDY4OiBcInBpbnRlcmVzdC1zcXVhcmVcIixcblxuICByb3VuZGVkcG9kY2FzdDogXCJwb2RjYXN0XCIsXG4gIDU4NDY5OiBcInBvZGNhc3RcIixcblxuICByb3VuZGVkcnNzOiBcInJzc1wiLFxuICA1ODQ4MTogXCJyc3NcIixcblxuICByb3VuZGVkc2hhcmV0aGlzOiBcInNoYXJlLWFsdFwiLFxuICA1ODQ4MzogXCJzaGFyZS1hbHRcIixcblxuICByb3VuZGVkc2t5cGU6IFwic2t5cGVcIixcbiAgNTg0ODQ6IFwic2t5cGVcIixcblxuICByb3VuZGVkc2xpZGVzaGFyZTogXCJzbGlkZXNoYXJlXCIsXG4gIDU4NDg2OiBcInNsaWRlc2hhcmVcIixcblxuICByb3VuZGVkc291bmRjbG91ZDogXCJzb3VuZGNsb3VkXCIsXG4gIDU4NDg4OiBcInNvdW5kY2xvdWRcIixcblxuICByb3VuZGVkc3BvdGlmeTogXCJzcG90aWZ5XCIsXG4gIDU4NDg5OiBcInNwb3RpZnlcIixcblxuICByb3VuZGVkc3RhcjogXCJzdGFyXCIsXG4gIDU4NDk4OiBcInN0YXJcIixcblxuICByb3VuZGVkdHVtYmxyOiBcInR1bWJsci1zcXVhcmVcIixcbiAgNTg1MDE6IFwidHVtYmxyLXNxdWFyZVwiLFxuXG4gIHJvdW5kZWR0d2l0dGVyYmlyZDogXCJ0d2l0dGVyLXNxdWFyZVwiLFxuICA1ODUwMjogXCJ0d2l0dGVyLXNxdWFyZVwiLFxuXG4gIHJvdW5kZWR0d2l0dGVyOiBcInR3aXR0ZXItc3F1YXJlXCIsXG5cbiAgcm91bmRlZHZpbWVvOiBcInZpbWVvLXNxdWFyZVwiLFxuICA1ODUwNTogXCJ2aW1lby1zcXVhcmVcIixcblxuICByb3VuZGVkd29yZHByZXNzOiBcIndvcmRwcmVzc1wiLFxuICA1ODUxNjogXCJ3b3JkcHJlc3NcIixcblxuICByb3VuZGVkeWVscDogXCJ5ZWxwXCIsXG4gIDU4NTIwOiBcInllbHBcIixcblxuICByb3VuZGVkeW91dHViZTogXCJ5b3V0dWJlLXNxdWFyZVwiLFxuXG4gIHJvdW5kZWRpbnN0YWdyYW06IFwiaW5zdGFncmFtLXNxdWFyZVwiLFxuXG4gIHJvdW5kZWRib29rbWFyazogXCJib29rbWFya1wiLFxuICA1ODcwNjogXCJib29rbWFya1wiLFxuXG4gIHJvdW5kZWRldXJvOiBcImV1cm8tc2lnblwiLFxuICA1ODcwNzogXCJldXJvLXNpZ25cIixcblxuICByb3VuZGVkcG91bmQ6IFwicG91bmQtc2lnblwiLFxuICA1ODcwODogXCJwb3VuZC1zaWduXCIsXG5cbiAgcm91bmRlZGNhc2g6IFwiZG9sbGFyLXNpZ25cIixcbiAgNTg3MDk6IFwiZG9sbGFyLXNpZ25cIixcblxuICByb3VuZGVkbWFwOiBcIm1hcC1tYXJrZWQtYWx0XCIsXG4gIDU4NzEwOiBcIm1hcC1tYXJrZWQtYWx0XCIsXG5cbiAgcm91bmRlZHZpZGVvOiBcInBsYXlcIixcbiAgNTg3MTE6IFwicGxheVwiLFxuXG4gIHJvdW5kZWRnb29nbGVwbGF5OiBcImdvb2dsZS1wbGF5XCIsXG4gIDU4NzIwOiBcImdvb2dsZS1wbGF5XCIsXG5cbiAgcm91bmRlZGNpbmVtYTogXCJmaWxtXCIsXG4gIDU4NzIxOiBcImZpbG1cIixcblxuICByb3VuZGVkdXBhcnJvdzogXCJhcnJvdy1jaXJjbGUtdXBcIixcbiAgNTg3MjI6IFwiYXJyb3ctY2lyY2xlLXVwXCIsXG5cbiAgcm91bmRlZHJpZ2h0YXJyb3c6IFwiYXJyb3ctY2lyY2xlLXJpZ2h0XCIsXG4gIDU4NzIzOiBcImFycm93LWNpcmNsZS1yaWdodFwiLFxuXG4gIHJvdW5kZWRyZWNvcmQ6IFwidmlkZW9cIixcbiAgNTg3MjQ6IFwidmlkZW9cIixcblxuICByb3VuZGVkbWFwMjogXCJtYXAtbWFya2VkLWFsdFwiLFxuICA1ODcyNTogXCJtYXAtbWFya2VkLWFsdFwiLFxuXG4gIHJvdW5kZWRsZWZ0YXJyb3c6IFwiYXJyb3ctY2lyY2xlLWxlZnRcIixcbiAgNTg3MjY6IFwiYXJyb3ctY2lyY2xlLWxlZnRcIixcblxuICByb3VuZGVkZmlsbTogXCJmaWxtXCIsXG4gIDU4NzI3OiBcImZpbG1cIixcblxuICByb3VuZGVkZG93bmFycm93OiBcImFycm93LWNpcmNsZS1kb3duXCIsXG4gIDU4NzI4OiBcImFycm93LWNpcmNsZS1kb3duXCIsXG5cbiAgcm91bmRlZGRpYWdvbmFsYXJyb3c6IFwiZXh0ZXJuYWwtbGluay1zcXVhcmVcIixcbiAgNTg3Mjk6IFwiZXh0ZXJuYWwtbGluay1zcXVhcmVcIixcblxuICBcImFkZHJlc3MtYm9va1wiOiBcImFkZHJlc3MtYm9va1wiLFxuICA2MjEzNzogXCJhZGRyZXNzLWJvb2tcIixcblxuICBcImFkZHJlc3MtY2FyZFwiOiBcImFkZHJlc3MtY2FyZFwiLFxuICA2MjEzOTogXCJhZGRyZXNzLWNhcmRcIixcblxuICBcImFpci1mcmVzaGVuZXJcIjogXCJhaXItZnJlc2hlbmVyXCIsXG4gIDYyOTI4OiBcImFpci1mcmVzaGVuZXJcIixcblxuICBhbGxlcmdpZXM6IFwiYWxsZXJnaWVzXCIsXG4gIDYyNTYxOiBcImFsbGVyZ2llc1wiLFxuXG4gIGFtYnVsYW5jZTogXCJhbWJ1bGFuY2VcIixcbiAgNjE2ODk6IFwiYW1idWxhbmNlXCIsXG5cbiAgXCJhbWVyaWNhbi1zaWduLWxhbmd1YWdlLWludGVycHJldGluZ1wiOiBcImFtZXJpY2FuLXNpZ24tbGFuZ3VhZ2UtaW50ZXJwcmV0aW5nXCIsXG4gIDYyMTE1OiBcImFtZXJpY2FuLXNpZ24tbGFuZ3VhZ2UtaW50ZXJwcmV0aW5nXCIsXG5cbiAgYW5jaG9yOiBcImFuY2hvclwiLFxuICA2MTc1NzogXCJhbmNob3JcIixcblxuICBhbmtoOiBcImFua2hcIixcbiAgNjMwNDQ6IFwiYW5raFwiLFxuXG4gIFwiYXBwLXN0b3JlXCI6IFwiYXBwLXN0b3JlXCIsXG4gIDYyMzE5OiBcImFwcC1zdG9yZVwiLFxuXG4gIFwiYXBwLXN0b3JlLWlvc1wiOiBcImFwcC1zdG9yZS1pb3NcIixcbiAgNjIzMjA6IFwiYXBwLXN0b3JlLWlvc1wiLFxuXG4gIFwiYXBwbGUtYWx0XCI6IFwiYXBwbGUtYWx0XCIsXG4gIDYyOTI5OiBcImFwcGxlLWFsdFwiLFxuXG4gIFwiYXBwbGUtcGF5XCI6IFwiYXBwbGUtcGF5XCIsXG4gIDYyNDg1OiBcImFwcGxlLXBheVwiLFxuXG4gIGFyY2hpdmU6IFwiYXJjaGl2ZVwiLFxuICA2MTgzMTogXCJhcmNoaXZlXCIsXG5cbiAgYXJjaHdheTogXCJhcmNod2F5XCIsXG4gIDYyODA3OiBcImFyY2h3YXlcIixcblxuICBcImFzc2lzdGl2ZS1saXN0ZW5pbmctc3lzdGVtc1wiOiBcImFzc2lzdGl2ZS1saXN0ZW5pbmctc3lzdGVtc1wiLFxuICA2MjExNDogXCJhc3Npc3RpdmUtbGlzdGVuaW5nLXN5c3RlbXNcIixcblxuICBhdDogXCJhdFwiLFxuICA2MTk0NjogXCJhdFwiLFxuXG4gIGF0bGFzOiBcImF0bGFzXCIsXG4gIDYyODA4OiBcImF0bGFzXCIsXG5cbiAgYXRvbTogXCJhdG9tXCIsXG4gIDYyOTMwOiBcImF0b21cIixcblxuICBcImF1ZGlvLWRlc2NyaXB0aW9uXCI6IFwiYXVkaW8tZGVzY3JpcHRpb25cIixcbiAgNjIxMTA6IFwiYXVkaW8tZGVzY3JpcHRpb25cIixcblxuICBhd2FyZDogXCJhd2FyZFwiLFxuICA2MjgwOTogXCJhd2FyZFwiLFxuXG4gIGJhYnk6IFwiYmFieVwiLFxuICA2MzM1NjogXCJiYWJ5XCIsXG5cbiAgXCJiYWJ5LWNhcnJpYWdlXCI6IFwiYmFieS1jYXJyaWFnZVwiLFxuICA2MzM1NzogXCJiYWJ5LWNhcnJpYWdlXCIsXG5cbiAgYmFjb246IFwiYmFjb25cIixcbiAgNjM0NjE6IFwiYmFjb25cIixcblxuICBiYWhhaTogXCJiYWhhaVwiLFxuICA2MzA3ODogXCJiYWhhaVwiLFxuXG4gIFwiYmFsYW5jZS1zY2FsZVwiOiBcImJhbGFuY2Utc2NhbGVcIixcbiAgNjIwMzA6IFwiYmFsYW5jZS1zY2FsZVwiLFxuXG4gIFwiYmFsYW5jZS1zY2FsZS1sZWZ0XCI6IFwiYmFsYW5jZS1zY2FsZS1sZWZ0XCIsXG4gIDYyNzQxOiBcImJhbGFuY2Utc2NhbGUtbGVmdFwiLFxuXG4gIFwiYmFsYW5jZS1zY2FsZS1yaWdodFwiOiBcImJhbGFuY2Utc2NhbGUtcmlnaHRcIixcbiAgNjI3NDI6IFwiYmFsYW5jZS1zY2FsZS1yaWdodFwiLFxuXG4gIGJhcmNvZGU6IFwiYmFyY29kZVwiLFxuICA2MTQ4MjogXCJiYXJjb2RlXCIsXG5cbiAgXCJiYXNlYmFsbC1iYWxsXCI6IFwiYmFzZWJhbGwtYmFsbFwiLFxuICA2MjUxNTogXCJiYXNlYmFsbC1iYWxsXCIsXG5cbiAgXCJiYXNrZXRiYWxsLWJhbGxcIjogXCJiYXNrZXRiYWxsLWJhbGxcIixcbiAgNjI1MTY6IFwiYmFza2V0YmFsbC1iYWxsXCIsXG5cbiAgYmF0aDogXCJiYXRoXCIsXG4gIDYyMTU3OiBcImJhdGhcIixcblxuICBiZWQ6IFwiYmVkXCIsXG4gIDYyMDA2OiBcImJlZFwiLFxuXG4gIGJlZXI6IFwiYmVlclwiLFxuICA2MTY5MjogXCJiZWVyXCIsXG5cbiAgYmVsbDogXCJiZWxsXCIsXG4gIDYxNjgzOiBcImJlbGxcIixcblxuICBiaWJsZTogXCJiaWJsZVwiLFxuICA2MzA0NzogXCJiaWJsZVwiLFxuXG4gIGJpY3ljbGU6IFwiYmljeWNsZVwiLFxuICA2MTk1ODogXCJiaWN5Y2xlXCIsXG5cbiAgYmlraW5nOiBcImJpa2luZ1wiLFxuICA2MzU2MjogXCJiaWtpbmdcIixcblxuICBiaW5vY3VsYXJzOiBcImJpbm9jdWxhcnNcIixcbiAgNjE5MjU6IFwiYmlub2N1bGFyc1wiLFxuXG4gIFwiYmlydGhkYXktY2FrZVwiOiBcImJpcnRoZGF5LWNha2VcIixcbiAgNjE5NDk6IFwiYmlydGhkYXktY2FrZVwiLFxuXG4gIGJsZW5kZXI6IFwiYmxlbmRlclwiLFxuICA2Mjc0MzogXCJibGVuZGVyXCIsXG5cbiAgXCJibGVuZGVyLXBob25lXCI6IFwiYmxlbmRlci1waG9uZVwiLFxuICA2MzE1ODogXCJibGVuZGVyLXBob25lXCIsXG5cbiAgYmxpbmQ6IFwiYmxpbmRcIixcbiAgNjIxMDk6IFwiYmxpbmRcIixcblxuICBibG9nOiBcImJsb2dcIixcbiAgNjMzNjE6IFwiYmxvZ1wiLFxuXG4gIGJvbHQ6IFwiYm9sdC1saWdodG5pbmdcIixcbiAgNjE2NzE6IFwiYm9sdC1saWdodG5pbmdcIixcblxuICBib25lOiBcImJvbmVcIixcbiAgNjI5MzU6IFwiYm9uZVwiLFxuXG4gIGJvb2s6IFwiYm9va1wiLFxuICA2MTQ4NTogXCJib29rXCIsXG5cbiAgXCJib29rLW9wZW5cIjogXCJib29rLW9wZW5cIixcbiAgNjI3NDQ6IFwiYm9vay1vcGVuXCIsXG5cbiAgXCJib29rLXJlYWRlclwiOiBcImJvb2stcmVhZGVyXCIsXG4gIDYyOTM4OiBcImJvb2stcmVhZGVyXCIsXG5cbiAgXCJib3dsaW5nLWJhbGxcIjogXCJib3dsaW5nLWJhbGxcIixcbiAgNjI1MTg6IFwiYm93bGluZy1iYWxsXCIsXG5cbiAgYnJhaWxsZTogXCJicmFpbGxlXCIsXG4gIDYyMTEzOiBcImJyYWlsbGVcIixcblxuICBcImJyZWFkLXNsaWNlXCI6IFwiYnJlYWQtc2xpY2VcIixcbiAgNjM0Njg6IFwiYnJlYWQtc2xpY2VcIixcblxuICBicmllZmNhc2U6IFwiYnJpZWZjYXNlXCIsXG4gIDYxNjE3OiBcImJyaWVmY2FzZVwiLFxuXG4gIFwiYnJvYWRjYXN0LXRvd2VyXCI6IFwiYnJvYWRjYXN0LXRvd2VyXCIsXG4gIDYyNzQ1OiBcImJyb2FkY2FzdC10b3dlclwiLFxuXG4gIGJyb29tOiBcImJyb29tXCIsXG4gIDYyNzQ2OiBcImJyb29tXCIsXG5cbiAgYnVnOiBcImJ1Z1wiLFxuICA2MTgzMjogXCJidWdcIixcblxuICBidWlsZGluZzogXCJidWlsZGluZ1wiLFxuICA2MTg2OTogXCJidWlsZGluZ1wiLFxuXG4gIGJ1bGxob3JuOiBcImJ1bGxob3JuXCIsXG4gIDYxNjAxOiBcImJ1bGxob3JuXCIsXG5cbiAgYnVsbHNleWU6IFwiYnVsbHNleWVcIixcbiAgNjE3NjA6IFwiYnVsbHNleWVcIixcblxuICBidXJuOiBcImJ1cm5cIixcbiAgNjI1NzA6IFwiYnVyblwiLFxuXG4gIGJ1czogXCJidXMtYWx0XCIsXG4gIDYxOTU5OiBcImJ1cy1hbHRcIixcblxuICBcImJ1cy1hbHRcIjogXCJidXNcIixcbiAgNjI4MTQ6IFwiYnVzXCIsXG5cbiAgXCJidXNpbmVzcy10aW1lXCI6IFwiYnVzaW5lc3MtdGltZVwiLFxuICA2MzA1MDogXCJidXNpbmVzcy10aW1lXCIsXG5cbiAgY2FsY3VsYXRvcjogXCJjYWxjdWxhdG9yXCIsXG4gIDYxOTMyOiBcImNhbGN1bGF0b3JcIixcblxuICBjYWxlbmRhcjogXCJjYWxlbmRhclwiLFxuICA2MTc0NzogXCJjYWxlbmRhclwiLFxuXG4gIFwiY2FsZW5kYXItYWx0XCI6IFwiY2FsZW5kYXItYWx0XCIsXG4gIDYxNTU1OiBcImNhbGVuZGFyLWFsdFwiLFxuXG4gIGNhbWVyYTogXCJjYW1lcmFcIixcbiAgNjE0ODg6IFwiY2FtZXJhXCIsXG5cbiAgXCJjYW1lcmEtcmV0cm9cIjogXCJjYW1lcmEtcmV0cm9cIixcbiAgNjE1NzE6IFwiY2FtZXJhLXJldHJvXCIsXG5cbiAgY2FtcGdyb3VuZDogXCJjYW1wZ3JvdW5kXCIsXG4gIDYzMTYzOiBcImNhbXBncm91bmRcIixcblxuICBcImNhbmFkaWFuLW1hcGxlLWxlYWZcIjogXCJjYW5hZGlhbi1tYXBsZS1sZWFmXCIsXG4gIDYzMzY1OiBcImNhbmFkaWFuLW1hcGxlLWxlYWZcIixcblxuICBcImNhbmR5LWNhbmVcIjogXCJjYW5keS1jYW5lXCIsXG4gIDYzMzY2OiBcImNhbmR5LWNhbmVcIixcblxuICBjYXI6IFwiY2FyXCIsXG4gIDYxODgxOiBcImNhclwiLFxuXG4gIFwiY2FyLWFsdFwiOiBcImNhci1hbHRcIixcbiAgNjI5NDI6IFwiY2FyLWFsdFwiLFxuXG4gIFwiY2FyLWJhdHRlcnlcIjogXCJjYXItYmF0dGVyeVwiLFxuICA2Mjk0MzogXCJjYXItYmF0dGVyeVwiLFxuXG4gIFwiY2FyLWNyYXNoXCI6IFwiY2FyLWNyYXNoXCIsXG4gIDYyOTQ1OiBcImNhci1jcmFzaFwiLFxuXG4gIFwiY2FyLXNpZGVcIjogXCJjYXItc2lkZVwiLFxuICA2Mjk0ODogXCJjYXItc2lkZVwiLFxuXG4gIGNhcmF2YW46IFwiY2FyYXZhblwiLFxuICA2Mzc0MzogXCJjYXJhdmFuXCIsXG5cbiAgY2Fycm90OiBcImNhcnJvdFwiLFxuICA2MzM2NzogXCJjYXJyb3RcIixcblxuICBcImNhcnQtYXJyb3ctZG93blwiOiBcImNhcnQtYXJyb3ctZG93blwiLFxuICA2MTk3NjogXCJjYXJ0LWFycm93LWRvd25cIixcblxuICBcImNhcnQtcGx1c1wiOiBcImNhcnQtcGx1c1wiLFxuICA2MTk3NTogXCJjYXJ0LXBsdXNcIixcblxuICBcImNhc2gtcmVnaXN0ZXJcIjogXCJjYXNoLXJlZ2lzdGVyXCIsXG4gIDYzMzY4OiBcImNhc2gtcmVnaXN0ZXJcIixcblxuICBjYXQ6IFwiY2F0XCIsXG4gIDYzMTY2OiBcImNhdFwiLFxuXG4gIFwiY2MtYW1hem9uLXBheVwiOiBcImNjLWFtYXpvbi1wYXlcIixcbiAgNjI1MDk6IFwiY2MtYW1hem9uLXBheVwiLFxuXG4gIFwiY2MtZGluZXJzLWNsdWJcIjogXCJcIixcbiAgNjIwMjg6IFwiY2MtZGluZXJzLWNsdWJcIixcblxuICBcImNjLWFtZXhcIjogXCJjYy1hbWV4XCIsXG4gIDYxOTM5OiBcImNjLWFtZXhcIixcblxuICBcImNjLWFwcGxlLXBheVwiOiBcImNjLWFwcGxlLXBheVwiLFxuICA2MjQ4NjogXCJjYy1hcHBsZS1wYXlcIixcblxuICBcImNjLWRpc2NvdmVyXCI6IFwiY2MtZGlzY292ZXJcIixcbiAgNjE5Mzg6IFwiY2MtZGlzY292ZXJcIixcblxuICBcImNjLWpjYlwiOiBcImNjLWpjYlwiLFxuICA2MjAyNzogXCJjYy1qY2JcIixcblxuICBcImNjLW1hc3RlcmNhcmRcIjogXCJjYy1tYXN0ZXJjYXJkXCIsXG4gIDYxOTM3OiBcImNjLW1hc3RlcmNhcmRcIixcblxuICBcImNjLXBheXBhbFwiOiBcImNjLXBheXBhbFwiLFxuICA2MTk0MDogXCJjYy1wYXlwYWxcIixcblxuICBcImNjLXN0cmlwZVwiOiBcImNjLXN0cmlwZVwiLFxuICA2MTk0MTogXCJjYy1zdHJpcGVcIixcblxuICBcImNjLXZpc2FcIjogXCJjYy12aXNhXCIsXG4gIDYxOTM2OiBcImNjLXZpc2FcIixcblxuICBjZXJ0aWZpY2F0ZTogXCJjZXJ0aWZpY2F0ZVwiLFxuICA2MTYwMzogXCJjZXJ0aWZpY2F0ZVwiLFxuXG4gIGNoYWxrYm9hcmQ6IFwiY2hhbGtib2FyZFwiLFxuICA2Mjc0NzogXCJjaGFsa2JvYXJkXCIsXG5cbiAgXCJjaGFsa2JvYXJkLXRlYWNoZXJcIjogXCJjaGFsa2JvYXJkLXRlYWNoZXJcIixcbiAgNjI3NDg6IFwiY2hhbGtib2FyZC10ZWFjaGVyXCIsXG5cbiAgXCJjaGFyZ2luZy1zdGF0aW9uXCI6IFwiY2hhcmdpbmctc3RhdGlvblwiLFxuICA2Mjk1MTogXCJjaGFyZ2luZy1zdGF0aW9uXCIsXG5cbiAgXCJjaGFydC1hcmVhXCI6IFwiY2hhcnQtYXJlYVwiLFxuICA2MTk1MDogXCJjaGFydC1hcmVhXCIsXG5cbiAgXCJjaGVjay1kb3VibGVcIjogXCJjaGVjay1kb3VibGVcIixcbiAgNjI4MTY6IFwiY2hlY2stZG91YmxlXCIsXG5cbiAgXCJjaGFydC1iYXJcIjogXCJjaGFydC1jb2x1bW5cIixcbiAgNjE1Njg6IFwiY2hhcnQtY29sdW1uXCIsXG5cbiAgXCJjaGFydC1saW5lXCI6IFwiY2hhcnQtbGluZVwiLFxuICA2MTk1MzogXCJjaGFydC1saW5lXCIsXG5cbiAgXCJjaGFydC1waWVcIjogXCJjaGFydC1waWVcIixcbiAgNjE5NTI6IFwiY2hhcnQtcGllXCIsXG5cbiAgY2hlZXNlOiBcImNoZWVzZVwiLFxuICA2MzQ3MTogXCJjaGVlc2VcIixcblxuICBjaHVyY2g6IFwiY2h1cmNoXCIsXG4gIDYyNzQ5OiBcImNodXJjaFwiLFxuXG4gIGNoaWxkOiBcImNoaWxkXCIsXG4gIDYxODcwOiBcImNoaWxkXCIsXG5cbiAgY2lyY2xlOiBcImNpcmNsZVwiLFxuICA2MTcxMzogXCJjaXJjbGVcIixcblxuICBjaXR5OiBcImNpdHlcIixcbiAgNjMwNTU6IFwiY2l0eVwiLFxuXG4gIFwiY2xpbmljLW1lZGljYWxcIjogXCJjbGluaWMtbWVkaWNhbFwiLFxuICA2MzQ3NDogXCJjbGluaWMtbWVkaWNhbFwiLFxuXG4gIGNsb2NrOiBcImNsb2NrXCIsXG4gIDYxNDYzOiBcImNsb2NrXCIsXG5cbiAgXCJjbG9zZWQtY2FwdGlvbmluZ1wiOiBcImNsb3NlZC1jYXB0aW9uaW5nXCIsXG4gIDYxOTYyOiBcImNsb3NlZC1jYXB0aW9uaW5nXCIsXG5cbiAgY2xvdWQ6IFwiY2xvdWRcIixcbiAgNjE2MzQ6IFwiY2xvdWRcIixcblxuICBcImNsb3VkLW1lYXRiYWxsXCI6IFwiY2xvdWQtbWVhdGJhbGxcIixcbiAgNjMyOTE6IFwiY2xvdWQtbWVhdGJhbGxcIixcblxuICBcImNsb3VkLW1vb25cIjogXCJjbG91ZC1tb29uXCIsXG4gIDYzMTcxOiBcImNsb3VkLW1vb25cIixcblxuICBcImNsb3VkLW1vb24tcmFpblwiOiBcImNsb3VkLW1vb24tcmFpblwiLFxuICA2MzI5MjogXCJjbG91ZC1tb29uLXJhaW5cIixcblxuICBcImNsb3VkLXJhaW5cIjogXCJjbG91ZC1yYWluXCIsXG4gIDYzMjkzOiBcImNsb3VkLXJhaW5cIixcblxuICBcImNsb3VkLXNob3dlcnMtaGVhdnlcIjogXCJjbG91ZC1zaG93ZXJzLWhlYXZ5XCIsXG4gIDYzMjk2OiBcImNsb3VkLXNob3dlcnMtaGVhdnlcIixcblxuICBcImNsb3VkLXN1blwiOiBcImNsb3VkLXN1blwiLFxuICA2MzE3MjogXCJjbG91ZC1zdW5cIixcblxuICBcImNsb3VkLXN1bi1yYWluXCI6IFwiY2xvdWQtc3VuLXJhaW5cIixcbiAgNjMyOTk6IFwiY2xvdWQtc3VuLXJhaW5cIixcblxuICBjb2NrdGFpbDogXCJjb2NrdGFpbFwiLFxuICA2MjgxNzogXCJjb2NrdGFpbFwiLFxuXG4gIGNvZmZlZTogXCJjb2ZmZWVcIixcbiAgNjE2ODQ6IFwiY29mZmVlXCIsXG5cbiAgY29pbnM6IFwiY29pbnNcIixcbiAgNjI3NTA6IFwiY29pbnNcIixcblxuICBjb21tZW50OiBcImNvbW1lbnRcIixcbiAgNjE1NTc6IFwiY29tbWVudFwiLFxuXG4gIFwiY29tbWVudC1hbHRcIjogXCJjb21tZW50LWFsdFwiLFxuICA2MjA3NDogXCJjb21tZW50LWFsdFwiLFxuXG4gIFwiY29tbWVudC1kb2xsYXJcIjogXCJjb21tZW50LWRvbGxhclwiLFxuICA2MzA1NzogXCJjb21tZW50LWRvbGxhclwiLFxuXG4gIGNvbW1lbnRzOiBcImNvbW1lbnRzXCIsXG4gIDYxNTc0OiBcImNvbW1lbnRzXCIsXG5cbiAgXCJjb21tZW50cy1kb2xsYXJcIjogXCJjb21tZW50cy1kb2xsYXJcIixcbiAgNjMwNTk6IFwiXCIsXG5cbiAgXCJjb21wYWN0LWRpc2NcIjogXCJjb21wYWN0LWRpc2NcIixcbiAgNjI3NTE6IFwiY29tcGFjdC1kaXNjXCIsXG5cbiAgY29tcGFzczogXCJjb21wYXNzXCIsXG4gIDYxNzc0OiBcImNvbXBhc3NcIixcblxuICBcImNvbmNpZXJnZS1iZWxsXCI6IFwiY29uY2llcmdlLWJlbGxcIixcbiAgNjI4MTg6IFwiY29uY2llcmdlLWJlbGxcIixcblxuICBjb29raWU6IFwiY29va2llXCIsXG4gIDYyODE5OiBcImNvb2tpZVwiLFxuXG4gIFwiY29va2llLWJpdGVcIjogXCJjb29raWUtYml0ZVwiLFxuICA2MjgyMDogXCJjb29raWUtYml0ZVwiLFxuXG4gIGNvcHlyaWdodDogXCJjb3B5cmlnaHRcIixcbiAgNjE5NDU6IFwiY29weXJpZ2h0XCIsXG5cbiAgXCJjcmVkaXQtY2FyZFwiOiBcImNyZWRpdC1jYXJkXCIsXG4gIDYxNTk3OiBcImNyZWRpdC1jYXJkXCIsXG5cbiAgY3Jvc3M6IFwiY3Jvc3NcIixcbiAgNjMwNjA6IFwiY3Jvc3NcIixcblxuICBjcm93OiBcImNyb3dcIixcbiAgNjI3NTI6IFwiY3Jvd1wiLFxuXG4gIGNyb3duOiBcImNyb3duXCIsXG4gIDYyNzUzOiBcImNyb3duXCIsXG5cbiAgY3ViZXM6IFwiY3ViZXNcIixcbiAgNjE4NzU6IFwiY3ViZXNcIixcblxuICBjdWJlOiBcImN1YmVcIixcbiAgNjE4NzQ6IFwiY3ViZVwiLFxuXG4gIGN1dDogXCJjdXRcIixcbiAgNjE2MzY6IFwiY3V0XCIsXG5cbiAgZGF0YWJhc2U6IFwiZGF0YWJhc2VcIixcbiAgNjE4ODg6IFwiZGF0YWJhc2VcIixcblxuICBkZWFmOiBcImRlYWZcIixcbiAgNjIxMTY6IFwiZGVhZlwiLFxuXG4gIGRlbW9jcmF0OiBcImRlbW9jcmF0XCIsXG4gIDYzMzAzOiBcImRlbW9jcmF0XCIsXG5cbiAgZGVza3RvcDogXCJkZXNrdG9wXCIsXG4gIDYxNzA0OiBcImRlc2t0b3BcIixcblxuICBkaGFybWFjaGFrcmE6IFwiZGhhcm1hY2hha3JhXCIsXG4gIDYzMDYxOiBcImRoYXJtYWNoYWtyYVwiLFxuXG4gIGRpY2U6IFwiZGljZVwiLFxuICA2Mjc1NDogXCJkaWNlXCIsXG5cbiAgXCJkaWdpdGFsLXRhY2hvZ3JhcGhcIjogXCJkaWdpdGFsLXRhY2hvZ3JhcGhcIixcbiAgNjI4MjI6IFwiXCIsXG5cbiAgZG9nOiBcImRvZ1wiLFxuICA2MzE4NzogXCJkb2dcIixcblxuICBcImRvbGxhci1zaWduXCI6IFwiZG9sbGFyLXNpZ25cIixcbiAgNjE3ODE6IFwiZG9sbGFyLXNpZ25cIixcblxuICBkb25hdGU6IFwiZG9uYXRlXCIsXG4gIDYyNjQ5OiBcImRvbmF0ZVwiLFxuXG4gIFwiZG9vci1jbG9zZWRcIjogXCJkb29yLWNsb3NlZFwiLFxuICA2Mjc2MjogXCJkb29yLWNsb3NlZFwiLFxuXG4gIFwiZG9vci1vcGVuXCI6IFwiZG9vci1vcGVuXCIsXG4gIDYyNzYzOiBcImRvb3Itb3BlblwiLFxuXG4gIGRvdmU6IFwiZG92ZVwiLFxuICA2MjY1MDogXCJkb3ZlXCIsXG5cbiAgZHJhZ29uOiBcImRyYWdvblwiLFxuICA2MzE4OTogXCJkcmFnb25cIixcblxuICBkcnVtOiBcImRydW1cIixcbiAgNjI4MjU6IFwiZHJ1bVwiLFxuXG4gIFwiZHJ1bS1zdGVlbHBhblwiOiBcImRydW0tc3RlZWxwYW5cIixcbiAgNjI4MjY6IFwiZHJ1bS1zdGVlbHBhblwiLFxuXG4gIFwiZHJ1bXN0aWNrLWJpdGVcIjogXCJkcnVtc3RpY2stYml0ZVwiLFxuICA2MzE5MTogXCJkcnVtc3RpY2stYml0ZVwiLFxuXG4gIGR1bWJiZWxsOiBcImR1bWJiZWxsXCIsXG4gIDYyNTM5OiBcImR1bWJiZWxsXCIsXG5cbiAgXCJkdW1wc3Rlci1maXJlXCI6IFwiZHVtcHN0ZXItZmlyZVwiLFxuICA2MzM4MDogXCJkdW1wc3Rlci1maXJlXCIsXG5cbiAgZHVuZ2VvbjogXCJkdW5nZW9uXCIsXG4gIDYzMTkzOiBcImR1bmdlb25cIixcblxuICBlZ2c6IFwiZWdnXCIsXG4gIDYzNDgzOiBcImVnZ1wiLFxuXG4gIGVudmVsb3BlOiBcImVudmVsb3BlXCIsXG4gIDYxNjY0OiBcImVudmVsb3BlXCIsXG5cbiAgXCJlbnZlbG9wZS1vcGVuXCI6IFwiZW52ZWxvcGUtb3BlblwiLFxuICA2MjEzNDogXCJlbnZlbG9wZS1vcGVuXCIsXG5cbiAgXCJlbnZlbG9wZS1zcXVhcmVcIjogXCJlbnZlbG9wZS1zcXVhcmVcIixcbiAgNjE4NDk6IFwiZW52ZWxvcGUtc3F1YXJlXCIsXG5cbiAgXCJldXJvLXNpZ25cIjogXCJldXJvLXNpZ25cIixcbiAgNjE3Nzk6IFwiZXVyby1zaWduXCIsXG5cbiAgZXhjbGFtYXRpb246IFwiZXhjbGFtYXRpb25cIixcbiAgNjE3Mzg6IFwiZXhjbGFtYXRpb25cIixcblxuICBcImV4Y2xhbWF0aW9uLWNpcmNsZVwiOiBcImV4Y2xhbWF0aW9uLWNpcmNsZVwiLFxuICA2MTU0NjogXCJleGNsYW1hdGlvbi1jaXJjbGVcIixcblxuICBcImV4Y2xhbWF0aW9uLXRyaWFuZ2xlXCI6IFwiZXhjbGFtYXRpb24tdHJpYW5nbGVcIixcbiAgNjE1NTM6IFwiZXhjbGFtYXRpb24tdHJpYW5nbGVcIixcblxuICBleWU6IFwiZXllXCIsXG4gIDYxNTUwOiBcImV5ZVwiLFxuXG4gIFwiZmFjZWJvb2stZlwiOiBcImZhY2Vib29rLWZcIixcbiAgNjIzNjY6IFwiZmFjZWJvb2stZlwiLFxuXG4gIFwiZmFjZWJvb2stbWVzc2VuZ2VyXCI6IFwiZmFjZWJvb2stbWVzc2VuZ2VyXCIsXG4gIDYyMzY3OiBcImZhY2Vib29rLW1lc3NlbmdlclwiLFxuXG4gIFwiZmFjZWJvb2stc3F1YXJlXCI6IFwiZmFjZWJvb2stc3F1YXJlXCIsXG5cbiAgZmF1Y2V0OiBcImZhdWNldFwiLFxuXG4gIGZheDogXCJmYXhcIixcbiAgNjE4Njg6IFwiZmF4XCIsXG5cbiAgZmVhdGhlcjogXCJmZWF0aGVyXCIsXG4gIDYyNzY1OiBcImZlYXRoZXJcIixcblxuICBcImZlYXRoZXItYWx0XCI6IFwiZmVhdGhlci1hbHRcIixcbiAgNjI4Mjc6IFwiZmVhdGhlci1hbHRcIixcblxuICBmZW1hbGU6IFwiZmVtYWxlXCIsXG4gIDYxODI2OiBcImZlbWFsZVwiLFxuXG4gIFwiZmlnaHRlci1qZXRcIjogXCJmaWdodGVyLWpldFwiLFxuICA2MTY5MTogXCJmaWdodGVyLWpldFwiLFxuXG4gIGZpbG06IFwiZmlsbVwiLFxuICA2MTQ0ODogXCJmaWxtXCIsXG5cbiAgZmlyZTogXCJmaXJlLWFsdFwiLFxuICA2MTU0OTogXCJmaXJlLWFsdFwiLFxuXG4gIFwiZmlyZS1hbHRcIjogXCJmaXJlXCIsXG4gIDYzNDYwOiBcImZpcmVcIixcblxuICBcImZpcmUtZXh0aW5ndWlzaGVyXCI6IFwiZmlyZS1leHRpbmd1aXNoZXJcIixcbiAgNjE3NDg6IFwiZmlyZS1leHRpbmd1aXNoZXJcIixcblxuICBcImZpcnN0LWFpZFwiOiBcImZpcnN0LWFpZFwiLFxuICA2MjU4NTogXCJmaXJzdC1haWRcIixcblxuICBmaXNoOiBcImZpc2hcIixcbiAgNjI4NDA6IFwiZmlzaFwiLFxuXG4gIFwiZmlzdC1yYWlzZWRcIjogXCJmaXN0LXJhaXNlZFwiLFxuICA2MzE5ODogXCJmaXN0LXJhaXNlZFwiLFxuXG4gIGZsYWc6IFwiZmxhZ1wiLFxuICA2MTQ3NjogXCJmbGFnXCIsXG5cbiAgXCJmbGFnLWNoZWNrZXJlZFwiOiBcImZsYWctY2hlY2tlcmVkXCIsXG4gIDYxNzI2OiBcImZsYWctY2hlY2tlcmVkXCIsXG5cbiAgXCJmbGFnLXVzYVwiOiBcImZsYWctdXNhXCIsXG4gIDYzMzA5OiBcImZsYWctdXNhXCIsXG5cbiAgZmxhc2s6IFwiZmxhc2tcIixcbiAgNjE2MzU6IFwiZmxhc2tcIixcblxuICBcImZvb3RiYWxsLWJhbGxcIjogXCJmb290YmFsbC1iYWxsXCIsXG4gIDYyNTQyOiBcImZvb3RiYWxsLWJhbGxcIixcblxuICBmcm9nOiBcImZyb2dcIixcbiAgNjI3NjY6IFwiZnJvZ1wiLFxuXG4gIGZ1dGJvbDogXCJmdXRib2xcIixcbiAgNjE5MjM6IFwiZnV0Ym9sXCIsXG5cbiAgZ2FtZXBhZDogXCJnYW1lcGFkXCIsXG4gIDYxNzIzOiBcImdhbWVwYWRcIixcblxuICBcImdhcy1wdW1wXCI6IFwiZ2FzLXB1bXBcIixcbiAgNjI3Njc6IFwiZ2FzLXB1bXBcIixcblxuICBnYXZlbDogXCJnYXZlbFwiLFxuICA2MTY2NzogXCJnYXZlbFwiLFxuXG4gIGdlbTogXCJnZW1cIixcbiAgNjIzNzM6IFwiZ2VtXCIsXG5cbiAgZ2VuZGVybGVzczogXCJnZW5kZXJsZXNzXCIsXG4gIDYxOTk3OiBcImdlbmRlcmxlc3NcIixcblxuICBnaG9zdDogXCJnaG9zdFwiLFxuICA2MzIwMjogXCJnaG9zdFwiLFxuXG4gIGdpZnQ6IFwiZ2lmdFwiLFxuICA2MTU0NzogXCJnaWZ0XCIsXG5cbiAgZ2lmdHM6IFwiZ2lmdHNcIixcbiAgNjMzODg6IFwiZ2lmdHNcIixcblxuICBcImdsYXNzLWNoZWVyc1wiOiBcImdsYXNzLWNoZWVyc1wiLFxuICA2MzM5MTogXCJnbGFzcy1jaGVlcnNcIixcblxuICBcImdsYXNzLW1hcnRpbmlcIjogXCJtYXJ0aW5pLWdsYXNzLWVtcHR5XCIsXG4gIDYxNDQwOiBcIm1hcnRpbmktZ2xhc3MtZW1wdHlcIixcblxuICBcImdsYXNzLW1hcnRpbmktYWx0XCI6IFwiZ2xhc3MtbWFydGluaS1hbHRcIixcbiAgNjI4NDM6IFwiZ2xhc3MtbWFydGluaS1hbHRcIixcblxuICBcImdsYXNzLXdoaXNrZXlcIjogXCJnbGFzcy13aGlza2V5XCIsXG4gIDYzMzkyOiBcImdsYXNzLXdoaXNrZXlcIixcblxuICBnbGFzc2VzOiBcImdsYXNzZXNcIixcbiAgNjI3Njg6IFwiZ2xhc3Nlc1wiLFxuXG4gIGdsb2JlOiBcImdsb2JlXCIsXG4gIDYxNjEyOiBcImdsb2JlXCIsXG5cbiAgXCJnbG9iZS1hZnJpY2FcIjogXCJnbG9iZS1hZnJpY2FcIixcbiAgNjI4NDQ6IFwiZ2xvYmUtYWZyaWNhXCIsXG5cbiAgXCJnbG9iZS1hbWVyaWNhc1wiOiBcImdsb2JlLWFtZXJpY2FzXCIsXG4gIDYyODQ1OiBcImdsb2JlLWFtZXJpY2FzXCIsXG5cbiAgXCJnbG9iZS1hc2lhXCI6IFwiZ2xvYmUtYXNpYVwiLFxuICA2Mjg0NjogXCJnbG9iZS1hc2lhXCIsXG5cbiAgXCJnbG9iZS1ldXJvcGVcIjogXCJnbG9iZS1ldXJvcGVcIixcbiAgNjMzOTQ6IFwiZ2xvYmUtZXVyb3BlXCIsXG5cbiAgXCJnb2xmLWJhbGxcIjogXCJnb2xmLWJhbFwiLFxuICA2MjU0NDogXCJnb2xmLWJhbFwiLFxuXG4gIGdvb2dsZTogXCJnb29nbGVcIixcbiAgNjE4NTY6IFwiZ29vZ2xlXCIsXG5cbiAgXCJnb29nbGUtZHJpdmVcIjogXCJnb29nbGUtZHJpdmVcIixcbiAgNjIzNzg6IFwiZ29vZ2xlLWRyaXZlXCIsXG5cbiAgXCJnb29nbGUtcGF5XCI6IFwiZ29vZ2xlLXBheVwiLFxuXG4gIFwiZ29vZ2xlLXBsYXlcIjogXCJnb29nbGUtcGxheVwiLFxuICA2MjM3OTogXCJnb29nbGUtcGxheVwiLFxuXG4gIFwiZ29vZ2xlLXdhbGxldFwiOiBcImdvb2dsZS13YWxsZXRcIixcbiAgNjE5MzQ6IFwiZ29vZ2xlLXdhbGxldFwiLFxuXG4gIGdvcHVyYW06IFwiZ29wdXJhbVwiLFxuICA2MzA3NjogXCJnb3B1cmFtXCIsXG5cbiAgXCJncmFkdWF0aW9uLWNhcFwiOiBcImdyYWR1YXRpb24tY2FwXCIsXG4gIDYxODUzOiBcImdyYWR1YXRpb24tY2FwXCIsXG5cbiAgZ3JpbjogXCJncmluXCIsXG4gIDYyODQ4OiBcImdyaW5cIixcblxuICBcImdyaW4tc3RhcnNcIjogXCJncmluLXN0YXJzXCIsXG4gIDYyODU1OiBcImdyaW4tc3RhcnNcIixcblxuICBcImdyaW4tYWx0XCI6IFwiZ3Jpbi1hbHRcIixcbiAgNjI4NDk6IFwiZ3Jpbi1hbHRcIixcblxuICBcImdyaW4tYmVhbVwiOiBcImdyaW4tYmVhbVwiLFxuICA2Mjg1MDogXCJncmluLWJlYW1cIixcblxuICBcImdyaW4tYmVhbS1zd2VhdFwiOiBcImdyaW4tYmVhbS1zd2VhdFwiLFxuICA2Mjg1MTogXCJncmluLWJlYW0tc3dlYXRcIixcblxuICBcImdyaW4taGVhcnRzXCI6IFwiZ3Jpbi1oZWFydHNcIixcbiAgNjI4NTI6IFwiZ3Jpbi1oZWFydHNcIixcblxuICBcImdyaW4tc3F1aW50XCI6IFwiZ3Jpbi1zcXVpbnRcIixcbiAgNjI4NTM6IFwiZ3Jpbi1zcXVpbnRcIixcblxuICBcImdyaW4tc3F1aW50LXRlYXJzXCI6IFwiZ3Jpbi10ZWFyc1wiLFxuICA2Mjg1NDogXCJncmluLXRlYXJzXCIsXG5cbiAgXCJncmluLXRlYXJzXCI6IFwiZ3Jpbi10ZWFyc1wiLFxuICA2Mjg1NjogXCJncmluLXRlYXJzXCIsXG5cbiAgXCJncmluLXRvbmd1ZVwiOiBcImdyaW4tdG9uZ3VlXCIsXG4gIDYyODU3OiBcImdyaW4tdG9uZ3VlXCIsXG5cbiAgXCJncmluLXRvbmd1ZS1zcXVpbnRcIjogXCJncmluLXRvbmd1ZS1zcXVpbnRcIixcbiAgNjI4NTg6IFwiZ3Jpbi10b25ndWUtc3F1aW50XCIsXG5cbiAgXCJncmluLXRvbmd1ZS13aW5rXCI6IFwiZ3Jpbi10b25ndWUtd2lua1wiLFxuICA2Mjg1OTogXCJncmluLXRvbmd1ZS13aW5rXCIsXG5cbiAgXCJncmluLXdpbmtcIjogXCJncmluLXdpbmtcIixcbiAgNjI4NjA6IFwiZ3Jpbi13aW5rXCIsXG5cbiAgZ3VpdGFyOiBcImd1aXRhclwiLFxuICA2MzM5ODogXCJndWl0YXJcIixcblxuICBoYW1idXJnZXI6IFwiaGFtYnVyZ2VyXCIsXG4gIDYzNDkzOiBcImhhbWJ1cmdlclwiLFxuXG4gIGhhbXNhOiBcImhhbXNhXCIsXG4gIDYzMDc3OiBcImhhbXNhXCIsXG5cbiAgXCJoYW5kLWhvbGRpbmdcIjogXCJoYW5kLWhvbGRpbmdcIixcbiAgNjI2NTM6IFwiaGFuZC1ob2xkaW5nXCIsXG5cbiAgXCJoYW5kLWhvbGRpbmctaGVhcnRcIjogXCJoYW5kLWhvbGRpbmctaGVhcnRcIixcbiAgNjI2NTQ6IFwiaGFuZC1ob2xkaW5nLWhlYXJ0XCIsXG5cbiAgXCJoYW5kLWhvbGRpbmctbWVkaWNhbFwiOiBcImhhbmQtaG9sZGluZy1tZWRpY2FsXCIsXG4gIDU3NDM2OiBcImhhbmQtaG9sZGluZy1tZWRpY2FsXCIsXG5cbiAgXCJoYW5kLWhvbGRpbmctd2F0ZXJcIjogXCJoYW5kLWhvbGRpbmctd2F0ZXJcIixcbiAgNjI2NTc6IFwiaGFuZC1ob2xkaW5nLXdhdGVyXCIsXG5cbiAgXCJoYW5kLWhvbGRpbmctdXNkXCI6IFwiaGFuZC1ob2xkaW5nLXVzZFwiLFxuICA2MjY1NjogXCJoYW5kLWhvbGRpbmctdXNkXCIsXG5cbiAgXCJoYW5kLXBhcGVyXCI6IFwiaGFuZC1wYXBlclwiLFxuICA2MjAzODogXCJoYW5kLXBhcGVyXCIsXG5cbiAgXCJoYW5kLXBlYWNlXCI6IFwiaGFuZC1wZWFjZVwiLFxuICA2MjA0MzogXCJoYW5kLXBlYWNlXCIsXG5cbiAgXCJoYW5kLXBvaW50LWRvd25cIjogXCJoYW5kLXBvaW50LWRvd25cIixcbiAgNjE2MDc6IFwiaGFuZC1wb2ludC1kb3duXCIsXG5cbiAgXCJoYW5kLXBvaW50LWxlZnRcIjogXCJoYW5kLXBvaW50LWxlZnRcIixcbiAgNjE2MDU6IFwiaGFuZC1wb2ludC1sZWZ0XCIsXG5cbiAgXCJoYW5kLXBvaW50LXJpZ2h0XCI6IFwiaGFuZC1wb2ludC1yaWdodFwiLFxuICA2MTYwNDogXCJoYW5kLXBvaW50LXJpZ2h0XCIsXG5cbiAgXCJoYW5kLXBvaW50LXVwXCI6IFwiaGFuZC1wb2ludC11cFwiLFxuICA2MTYwNjogXCJoYW5kLXBvaW50LXVwXCIsXG5cbiAgXCJoYW5kLXBvaW50ZXJcIjogXCJoYW5kLXBvaW50ZXJcIixcbiAgNjIwNDI6IFwiaGFuZC1wb2ludGVyXCIsXG5cbiAgXCJoYW5kLXJvY2tcIjogXCJoYW5kLXJvY2tcIixcbiAgNjIwMzc6IFwiaGFuZC1yb2NrXCIsXG5cbiAgXCJoYW5kLXNjaXNzb3JzXCI6IFwiaGFuZC1zY2lzc29yc1wiLFxuICA2MjAzOTogXCJoYW5kLXNjaXNzb3JzXCIsXG5cbiAgXCJoYW5kLXNwb2NrXCI6IFwiaGFuZC1zcG9ja1wiLFxuICA2MjA0MTogXCJoYW5kLXNwb2NrXCIsXG5cbiAgaGFuZHM6IFwiaGFuZHMtaG9sZGluZ1wiLFxuICA2MjY1ODogXCJoYW5kcy1ob2xkaW5nXCIsXG5cbiAgXCJoYW5kcy1oZWxwaW5nXCI6IFwiaGFuZHMtaGVscGluZ1wiLFxuICA2MjY2MDogXCJoYW5kcy1oZWxwaW5nXCIsXG5cbiAgXCJoYW5kcy13YXNoXCI6IFwiaGFuZHMtd2FzaFwiLFxuICA1NzQzODogXCJoYW5kcy13YXNoXCIsXG5cbiAgaGFuZHNoYWtlOiBcImhhbmRzaGFrZVwiLFxuICA2MjEzMzogXCJoYW5kc2hha2VcIixcblxuICBcImhhbmRzaGFrZS1hbHQtc2xhc2hcIjogXCJoYW5kc2hha2UtYWx0LXNsYXNoXCIsXG4gIDU3NDM5OiBcImhhbmRzaGFrZS1hbHQtc2xhc2hcIixcblxuICBcImhhbmRzaGFrZS1zbGFzaFwiOiBcImhhbmRzaGFrZS1zbGFzaFwiLFxuICA1NzQ0MDogXCJoYW5kc2hha2Utc2xhc2hcIixcblxuICBoYW51a2lhaDogXCJoYW51a2lhaFwiLFxuICA2MzIwNjogXCJoYW51a2lhaFwiLFxuXG4gIGhhc2h0YWc6IFwiaGFzaHRhZ1wiLFxuICA2MjA5ODogXCJoYXNodGFnXCIsXG5cbiAgXCJoYXQtY293Ym95XCI6IFwiaGF0LWNvd2JveVwiLFxuICA2MzY4MDogXCJoYXQtY293Ym95XCIsXG5cbiAgXCJoYXQtY293Ym95LXNpZGVcIjogXCJoYXQtY293Ym95LXNpZGVcIixcbiAgNjM2ODE6IFwiaGF0LWNvd2JveS1zaWRlXCIsXG5cbiAgaGVhZHBob25lczogXCJoZWFkcGhvbmVzXCIsXG4gIDYxNDc3OiBcImhlYWRwaG9uZXNcIixcblxuICBcImhlYWRwaG9uZXMtYWx0XCI6IFwiaGVhZHBob25lcy1hbHRcIixcbiAgNjI4NjM6IFwiaGVhZHBob25lcy1hbHRcIixcblxuICBoZWFkc2V0OiBcImhlYWRzZXRcIixcbiAgNjI4NjQ6IFwiaGVhZHNldFwiLFxuXG4gIFwiaGVhcnQtYnJva2VuXCI6IFwiaGVhcnQtYnJva2VuXCIsXG4gIDYzNDAxOiBcImhlYXJ0LWJyb2tlblwiLFxuXG4gIGhlYXJ0YmVhdDogXCJoZWFydGJlYXRcIixcbiAgNjE5ODI6IFwiaGVhcnRiZWF0XCIsXG5cbiAgaGVsaWNvcHRlcjogXCJoZWxpY29wdGVyXCIsXG4gIDYyNzcxOiBcImhlbGljb3B0ZXJcIixcblxuICBoaWdobGlnaHRlcjogXCJoaWdobGlnaHRlclwiLFxuICA2Mjg2NTogXCJoaWdobGlnaHRlclwiLFxuXG4gIGhpa2luZzogXCJoaWtpbmdcIixcbiAgNjMyMTI6IFwiaGlraW5nXCIsXG5cbiAgaGlwcG86IFwiaGlwcG9cIixcbiAgNjMyMTM6IFwiaGlwcG9cIixcblxuICBcImhvY2tleS1wdWNrXCI6IFwiaG9ja2V5LXB1Y2tcIixcbiAgNjI1NDc6IFwiaG9ja2V5LXB1Y2tcIixcblxuICBcImhvbGx5LWJlcnJ5XCI6IFwiaG9sbHktYmVycnlcIixcbiAgNjM0MDI6IFwiaG9sbHktYmVycnlcIixcblxuICBob21lOiBcImhvbWVcIixcbiAgNjE0NjE6IFwiaG9tZVwiLFxuXG4gIGhvcnNlOiBcImhvcnNlXCIsXG4gIDYzMjE2OiBcImhvcnNlXCIsXG5cbiAgXCJob3JzZS1oZWFkXCI6IFwiaG9yc2UtaGVhZFwiLFxuICA2MzQwMzogXCJob3JzZS1oZWFkXCIsXG5cbiAgaG9zcGl0YWw6IFwiaG9zcGl0YWxcIixcbiAgNjE2ODg6IFwiaG9zcGl0YWxcIixcblxuICBcImhvc3BpdGFsLWFsdFwiOiBcImhvc3BpdGFsLWFsdFwiLFxuICA2MjU4OTogXCJob3NwaXRhbC1hbHRcIixcblxuICBcImhvc3BpdGFsLXVzZXJcIjogXCJob3NwaXRhbC11c2VyXCIsXG4gIDYzNTAxOiBcImhvc3BpdGFsLXVzZXJcIixcblxuICBcImhvdC10dWJcIjogXCJob3QtdHViXCIsXG4gIDYyODY3OiBcImhvdC10dWJcIixcblxuICBob3Rkb2c6IFwiaG90ZG9nXCIsXG4gIDYzNTAzOiBcImhvdGRvZ1wiLFxuXG4gIGhvdGVsOiBcImhvdGVsXCIsXG4gIDYyODY4OiBcImhvdGVsXCIsXG5cbiAgaG91cmdsYXNzOiBcImhvdXJnbGFzc1wiLFxuICA2MjAzNjogXCJob3VyZ2xhc3NcIixcblxuICBcImhvdXJnbGFzcy1lbmRcIjogXCJob3VyZ2xhc3MtZW5kXCIsXG4gIDYyMDM1OiBcImhvdXJnbGFzcy1lbmRcIixcblxuICBcImhvdXJnbGFzcy1oYWxmXCI6IFwiaG91cmdsYXNzLWhhbGZcIixcbiAgNjIwMzQ6IFwiaG91cmdsYXNzLWhhbGZcIixcblxuICBcImhvdXJnbGFzcy1zdGFydFwiOiBcImhvdXJnbGFzcy1zdGFydFwiLFxuICA2MjAzMzogXCJob3VyZ2xhc3Mtc3RhcnRcIixcblxuICBcImhvdXNlLWRhbWFnZVwiOiBcImhvdXNlLWRhbWFnZVwiLFxuICA2MzIxNzogXCJob3VzZS1kYW1hZ2VcIixcblxuICBocnl2bmlhOiBcImhyeXZuaWFcIixcbiAgNjMyMTg6IFwiaHJ5dm5pYVwiLFxuXG4gIFwiaWNlLWNyZWFtXCI6IFwiaWNlLWNyZWFtXCIsXG4gIDYzNTA0OiBcImljZS1jcmVhbVwiLFxuXG4gIGljaWNsZXM6IFwiaWNpY2xlc1wiLFxuICA2MzQwNTogXCJpY2ljbGVzXCIsXG5cbiAgaWNvbnM6IFwiaWNvbnNcIixcbiAgNjM1OTc6IFwiaWNvbnNcIixcblxuICBcImlkLWJhZGdlXCI6IFwiaWQtYmFkZ2VcIixcbiAgNjIxNDU6IFwiaWQtYmFkZ2VcIixcblxuICBcImlkLWNhcmRcIjogXCJhZGRyZXNzLWNhcmRcIixcbiAgNjIxNDY6IFwiYWRkcmVzcy1jYXJkXCIsXG5cbiAgXCJpZC1jYXJkLWFsdFwiOiBcImlkLWNhcmQtYWx0XCIsXG4gIDYyNTkxOiBcImlkLWNhcmQtYWx0XCIsXG5cbiAgaWdsb286IFwiaWdsb29cIixcbiAgNjM0MDY6IFwiaWdsb29cIixcblxuICBpbWFnZTogXCJpbWFnZVwiLFxuICA2MTUwMjogXCJpbWFnZVwiLFxuXG4gIGltYWdlczogXCJpbWFnZXNcIixcbiAgNjIyMTA6IFwiaW1hZ2VzXCIsXG5cbiAgaW5ib3g6IFwiaW5ib3hcIixcbiAgNjE0Njg6IFwiaW5ib3hcIixcblxuICBpbmR1c3RyeTogXCJpbmR1c3RyeVwiLFxuICA2MjA2OTogXCJpbmR1c3RyeVwiLFxuXG4gIFwiaW5zdGFncmFtLXNxdWFyZVwiOiBcImluc3RhZ3JhbS1zcXVhcmVcIixcbiAgNTc0Mjk6IFwiaW5zdGFncmFtLXNxdWFyZVwiLFxuXG4gIFwiaXR1bmVzLW5vdGVcIjogXCJpdHVuZXMtbm90ZVwiLFxuICA2MjM4OTogXCJpdHVuZXMtbm90ZVwiLFxuXG4gIGtleTogXCJrZXlcIixcbiAgNjE1NzI6IFwia2V5XCIsXG5cbiAga2FhYmE6IFwia2FhYmFcIixcbiAgNjMwODM6IFwia2FhYmFcIixcblxuICBraGFuZGE6IFwia2hhbmRhXCIsXG4gIDYzMDg1OiBcImtoYW5kYVwiLFxuXG4gIGtpc3M6IFwia2lzc1wiLFxuICA2Mjg3MDogXCJraXNzXCIsXG5cbiAgXCJraXNzLWJlYW1cIjogXCJraXNzLWJlYW1cIixcbiAgNjI4NzE6IFwia2lzcy1iZWFtXCIsXG5cbiAgXCJraXNzLXdpbmstaGVhcnRcIjogXCJraXNzLXdpbmstaGVhcnRcIixcbiAgNjI4NzI6IFwia2lzcy13aW5rLWhlYXJ0XCIsXG5cbiAgXCJraXdpLWJpcmRcIjogXCJraXdpLWJpcmRcIixcbiAgNjI3NzM6IFwia2l3aS1iaXJkXCIsXG5cbiAgbGFuZG1hcms6IFwibGFuZG1hcmtcIixcbiAgNjMwODc6IFwibGFuZG1hcmtcIixcblxuICBsYW5ndWFnZTogXCJsYW5ndWFnZVwiLFxuICA2MTg2NzogXCJsYW5ndWFnZVwiLFxuXG4gIGxhcHRvcDogXCJsYXB0b3BcIixcbiAgNjE3MDU6IFwibGFwdG9wXCIsXG5cbiAgXCJsYXB0b3AtaG91c2VcIjogXCJsYXB0b3AtaG91c2VcIixcbiAgNTc0NDY6IFwibGFwdG9wLWhvdXNlXCIsXG5cbiAgbGF1Z2g6IFwibGF1Z2hcIixcbiAgNjI4NzM6IFwibGF1Z2hcIixcblxuICBcImxhdWdoLWJlYW1cIjogXCJsYXVnaC1iZWFtXCIsXG4gIDYyODc0OiBcImxhdWdoLWJlYW1cIixcblxuICBcImxhdWdoLXNxdWludFwiOiBcImxhdWdoLXNxdWludFwiLFxuICA2Mjg3NTogXCJsYXVnaC1zcXVpbnRcIixcblxuICBcImxhdWdoLXdpbmtcIjogXCJsYXVnaC13aW5rXCIsXG4gIDYyODc2OiBcImxhdWdoLXdpbmtcIixcblxuICBsZWFmOiBcImxlYWZcIixcbiAgNjE1NDg6IFwibGVhZlwiLFxuXG4gIGxlbW9uOiBcImxlbW9uXCIsXG4gIDYxNTg4OiBcImxlbW9uXCIsXG5cbiAgdHJhbnNnZW5kZXI6IFwidHJhbnNnZW5kZXJcIixcbiAgNjE5ODg6IFwidHJhbnNnZW5kZXJcIixcblxuICBcImxpZmUtcmluZ1wiOiBcImxpZmUtcmluZ1wiLFxuICA2MTkwMTogXCJsaWZlLXJpbmdcIixcblxuICBsaWdodGJ1bGI6IFwibGlnaHRidWxiXCIsXG4gIDYxNjc1OiBcImxpZ2h0YnVsYlwiLFxuXG4gIFwibGlyYS1zaWduXCI6IFwidHVya2lzaC1saXJhLXNpZ25cIixcbiAgNjE4NDU6IFwidHVya2lzaC1saXJhLXNpZ25cIixcblxuICBsb2NrOiBcImxvY2tcIixcbiAgNjE0NzU6IFwibG9ja1wiLFxuXG4gIFwibG9jay1vcGVuXCI6IFwibG9jay1vcGVuXCIsXG4gIDYyNDAxOiBcImxvY2stb3BlblwiLFxuXG4gIFwibG93LXZpc2lvblwiOiBcImxvdy12aXNpb25cIixcbiAgNjIxMjA6IFwibG93LXZpc2lvblwiLFxuXG4gIFwibHVnZ2FnZS1jYXJ0XCI6IFwibHVnZ2FnZS1jYXJ0XCIsXG4gIDYyODc3OiBcImx1Z2dhZ2UtY2FydFwiLFxuXG4gIG1hZ25ldDogXCJtYWduZXRcIixcbiAgNjE1NTg6IFwibWFnbmV0XCIsXG5cbiAgbWFsZTogXCJtYWxlXCIsXG4gIDYxODI3OiBcIm1hbGVcIixcblxuICBcIm1hcC1tYXJrZWRcIjogXCJtYXAtbWFya2VkXCIsXG4gIDYyODc5OiBcIm1hcC1tYXJrZWRcIixcblxuICBcIm1hcC1tYXJrZWQtYWx0XCI6IFwibWFwLW1hcmtlZC1hbHRcIixcbiAgNjI4ODA6IFwibWFwLW1hcmtlZC1hbHRcIixcblxuICBcIm1hcC1tYXJrZXJcIjogXCJtYXAtbWFya2VyXCIsXG4gIDYxNTA1OiBcIm1hcC1tYXJrZXJcIixcblxuICBcIm1hcC1tYXJrZXItYWx0XCI6IFwibWFwLW1hcmtlci1hbHRcIixcbiAgNjI0MDU6IFwibWFwLW1hcmtlci1hbHRcIixcblxuICBcIm1hcC1waW5cIjogXCJtYXAtcGluXCIsXG4gIDYyMDcwOiBcIm1hcC1waW5cIixcblxuICBcIm1hcC1zaWduc1wiOiBcIm1hcC1zaWduc1wiLFxuICA2MjA3MTogXCJtYXAtc2lnbnNcIixcblxuICBtYXJrZXI6IFwibWFya2VyXCIsXG4gIDYyODgxOiBcIm1hcmtlclwiLFxuXG4gIG1hcnM6IFwibWFyc1wiLFxuICA2MTk4NjogXCJtYXJzXCIsXG5cbiAgXCJtYXJzLWRvdWJsZVwiOiBcIm1hcnMtZG91YmxlXCIsXG4gIDYxOTkxOiBcIm1hcnMtZG91YmxlXCIsXG5cbiAgXCJtYXJzLXN0cm9rZVwiOiBcIm1hcnMtc3Ryb2tlXCIsXG4gIDYxOTkzOiBcIm1hcnMtc3Ryb2tlXCIsXG5cbiAgXCJtYXJzLXN0cm9rZS1oXCI6IFwibWFycy1zdHJva2UtaFwiLFxuICA2MTk5NTogXCJtYXJzLXN0cm9rZS1oXCIsXG5cbiAgXCJtYXJzLXN0cm9rZS12XCI6IFwibWFycy1zdHJva2UtdlwiLFxuICA2MTk5NDogXCJtYXJzLXN0cm9rZS12XCIsXG5cbiAgbWFzazogXCJtYXNrXCIsXG4gIDYzMjI2OiBcIm1hc2tcIixcblxuICBtZWRhbDogXCJtZWRhbFwiLFxuICA2Mjg4MjogXCJtZWRhbFwiLFxuXG4gIG1lZGtpdDogXCJcIixcbiAgNjE2OTA6IFwiXCIsXG5cbiAgbWVub3JhaDogXCJtZW5vcmFoXCIsXG4gIDYzMDk0OiBcIm1lbm9yYWhcIixcblxuICBtZXJjdXJ5OiBcIm1lcmN1cnlcIixcbiAgNjE5ODc6IFwibWVyY3VyeVwiLFxuXG4gIG1ldGVvcjogXCJtZXRlb3JcIixcbiAgNjMzMTU6IFwibWV0ZW9yXCIsXG5cbiAgbWljcm9waG9uZTogXCJcIixcbiAgNjE3NDQ6IFwibWljcm9waG9uZVwiLFxuXG4gIFwibWljcm9waG9uZS1hbHRcIjogXCJtaWNyb3Bob25lLWFsdFwiLFxuICA2MjQwOTogXCJtaWNyb3Bob25lLWFsdFwiLFxuXG4gIG1pY3Jvc2NvcGU6IFwibWljcm9zY29wZVwiLFxuICA2Mjk5MjogXCJtaWNyb3Njb3BlXCIsXG5cbiAgbWl0dGVuOiBcIm1pdHRlblwiLFxuICA2MzQxMzogXCJtaXR0ZW5cIixcblxuICBtb2JpbGU6IFwibW9iaWxlLWJ1dHRvblwiLFxuICA2MTcwNzogXCJtb2JpbGUtYnV0dG9uXCIsXG5cbiAgXCJtb2JpbGUtYWx0XCI6IFwibW9iaWxlLWFsdFwiLFxuICA2MjQxMzogXCJtb2JpbGUtYWx0XCIsXG5cbiAgXCJtb25leS1iaWxsXCI6IFwibW9uZXktYmlsbFwiLFxuICA2MTY1NDogXCJtb25leS1iaWxsXCIsXG5cbiAgXCJtb25leS1iaWxsLWFsdFwiOiBcIm1vbmV5LWJpbGwtYWx0XCIsXG4gIDYyNDE3OiBcIm1vbmV5LWJpbGwtYWx0XCIsXG5cbiAgXCJtb25leS1iaWxsLXdhdmVcIjogXCJtb25leS1iaWxsLXdhdmVcIixcbiAgNjI3Nzg6IFwibW9uZXktYmlsbC13YXZlXCIsXG5cbiAgXCJtb25leS1iaWxsLXdhdmUtYWx0XCI6IFwibW9uZXktYmlsbC13YXZlXCIsXG4gIDYyNzc5OiBcIm1vbmV5LWJpbGwtd2F2ZVwiLFxuXG4gIFwibW9uZXktY2hlY2tcIjogXCJtb25leS1jaGVja1wiLFxuICA2Mjc4MDogXCJtb25leS1jaGVja1wiLFxuXG4gIFwibW9uZXktY2hlY2stYWx0XCI6IFwibW9uZXktY2hlY2stYWx0XCIsXG4gIDYyNzgxOiBcIm1vbmV5LWNoZWNrLWFsdFwiLFxuXG4gIG1vbnVtZW50OiBcIm1vbnVtZW50XCIsXG4gIDYyODg2OiBcIm1vbnVtZW50XCIsXG5cbiAgbW9vbjogXCJtb29uXCIsXG4gIDYxODMwOiBcIm1vb25cIixcblxuICBtb3NxdWU6IFwibW9zcXVlXCIsXG4gIDYzMDk2OiBcIm1vc3F1ZVwiLFxuXG4gIG1vdG9yY3ljbGU6IFwibW90b3JjeWNsZVwiLFxuICA2MTk4MDogXCJtb3RvcmN5Y2xlXCIsXG5cbiAgbW91bnRhaW46IFwibW91bnRhaW5cIixcbiAgNjMyMjg6IFwibW91bnRhaW5cIixcblxuICBcIm11Zy1ob3RcIjogXCJtdWctaG90XCIsXG4gIDYzNDE0OiBcIm11Zy1ob3RcIixcblxuICBtdXNpYzogXCJtdXNpY1wiLFxuICA2MTQ0MTogXCJtdXNpY1wiLFxuXG4gIG5ldXRlcjogXCJuZXV0ZXJcIixcbiAgNjE5OTY6IFwibmV1dGVyXCIsXG5cbiAgbmV3c3BhcGVyOiBcIm5ld3NwYXBlclwiLFxuICA2MTkzMDogXCJuZXdzcGFwZXJcIixcblxuICBcIm9pbC1jYW5cIjogXCJvaWwtY2FuXCIsXG4gIDYyOTk1OiBcIm9pbC1jYW5cIixcblxuICBvbTogXCJvbVwiLFxuICA2MzA5NzogXCJvbVwiLFxuXG4gIG90dGVyOiBcIm90dGVyXCIsXG4gIDYzMjMyOiBcIm90dGVyXCIsXG5cbiAgXCJwYWludC1icnVzaFwiOiBcInBhaW50LWJydXNoXCIsXG4gIDYxOTQ4OiBcInBhaW50LWJydXNoXCIsXG5cbiAgXCJwYXBlci1wbGFuZVwiOiBcInBhcGVyLXBsYW5lXCIsXG4gIDYxOTEyOiBcInBhcGVyLXBsYW5lXCIsXG5cbiAgcGFwZXJjbGlwOiBcInBhcGVyY2xpcFwiLFxuICA2MTYzODogXCJwYXBlcmNsaXBcIixcblxuICBcInBhcmFjaHV0ZS1ib3hcIjogXCJwYXJhY2h1dGUtYm94XCIsXG4gIDYyNjY5OiBcInBhcmFjaHV0ZS1ib3hcIixcblxuICBwYXJhZ3JhcGg6IFwicGFyYWdyYXBoXCIsXG4gIDYxOTE3OiBcInBhcmFncmFwaFwiLFxuXG4gIHBhc3Nwb3J0OiBcInBhc3Nwb3J0XCIsXG4gIDYyODkxOiBcInBhc3Nwb3J0XCIsXG5cbiAgcGF3OiBcInBhd1wiLFxuICA2MTg3MjogXCJwYXdcIixcblxuICBwZWFjZTogXCJwZWFjZVwiLFxuICA2MzEwMDogXCJwZWFjZVwiLFxuXG4gIHBlbjogXCJwZW5cIixcbiAgNjIyMTI6IFwicGVuXCIsXG5cbiAgXCJwZW4tYWx0XCI6IFwicGVuLWFsdFwiLFxuICA2MjIxMzogXCJwZW4tYWx0XCIsXG5cbiAgXCJwZW4tZmFuY3lcIjogXCJwZW4tZmFuY3lcIixcbiAgNjI4OTI6IFwicGVuLWZhbmN5XCIsXG5cbiAgXCJwZW4tbmliXCI6IFwicGVuLW5pYlwiLFxuICA2Mjg5MzogXCJwZW4tbmliXCIsXG5cbiAgXCJwZW4tc3F1YXJlXCI6IFwicGVuLXNxdWFyZVwiLFxuICA2MTc3MTogXCJwZW4tc3F1YXJlXCIsXG5cbiAgXCJwZW5jaWwtYWx0XCI6IFwicGVuY2lsLWFsdFwiLFxuICA2MjIxMTogXCJwZW5jaWwtYWx0XCIsXG5cbiAgXCJwZW9wbGUtYXJyb3dzXCI6IFwicGVvcGxlLWFycm93c1wiLFxuICA1NzQ0ODogXCJwZW9wbGUtYXJyb3dzXCIsXG5cbiAgXCJwZW9wbGUtY2FycnlcIjogXCJwZW9wbGUtY2FycnlcIixcbiAgNjI2NzA6IFwicGVvcGxlLWNhcnJ5XCIsXG5cbiAgXCJwZXBwZXItaG90XCI6IFwicGVwcGVyLWhvdFwiLFxuICA2MzUxMDogXCJwZXBwZXItaG90XCIsXG5cbiAgcGVyY2VudDogXCJwZXJjZW50YWdlXCIsXG4gIDYyMTAxOiBcInBlcmNlbnRhZ2VcIixcblxuICBwZXJjZW50YWdlOiBcInBlcmNlbnRhZ2VcIixcbiAgNjI3ODU6IFwicGVyY2VudGFnZVwiLFxuXG4gIFwicGVyc29uLWJvb3RoXCI6IFwicGVyc29uLWJvb3RoXCIsXG4gIDYzMzE4OiBcInBlcnNvbi1ib290aFwiLFxuXG4gIHBob25lOiBcInBob25lLWFsdFwiLFxuICA2MTU4OTogXCJwaG9uZS1hbHRcIixcblxuICBcInBob25lLWFsdFwiOiBcInBob25lXCIsXG4gIDYzNjA5OiBcInBob25lXCIsXG5cbiAgXCJwaG9uZS1zcXVhcmVcIjogXCJwaG9uZS1zcXVhcmUtYWx0XCIsXG4gIDYxNTkyOiBcInBob25lLXNxdWFyZS1hbHRcIixcblxuICBcInBob25lLXNxdWFyZS1hbHRcIjogXCJwaG9uZS1zcXVhcmVcIixcbiAgNjM2MTE6IFwicGhvbmUtc3F1YXJlXCIsXG5cbiAgXCJwaG9uZS12b2x1bWVcIjogXCJwaG9uZS12b2x1bWVcIixcbiAgNjIxMTI6IFwicGhvbmUtdm9sdW1lXCIsXG5cbiAgXCJwaG90by12aWRlb1wiOiBcInBob3RvLXZpZGVvXCIsXG4gIDYzNjEyOiBcInBob3RvLXZpZGVvXCIsXG5cbiAgXCJwaWdneS1iYW5rXCI6IFwicGlnZ3ktYmFua1wiLFxuICA2MjY3NTogXCJwaWdneS1iYW5rXCIsXG5cbiAgXCJwaW50ZXJlc3QtcFwiOiBcInBpbnRlcmVzdC1wXCIsXG4gIDYyMDAxOiBcInBpbnRlcmVzdC1wXCIsXG5cbiAgXCJwaW50ZXJlc3Qtc3F1YXJlXCI6IFwicGludGVyZXN0LXNxdWFyZVwiLFxuICA2MTY1MTogXCJwaW50ZXJlc3Qtc3F1YXJlXCIsXG5cbiAgXCJwaXp6YS1zbGljZVwiOiBcInBpenphLXNsaWNlXCIsXG4gIDYzNTEyOiBcInBpenphLXNsaWNlXCIsXG5cbiAgXCJwbGFjZS1vZi13b3JzaGlwXCI6IFwicGxhY2Utb2Ytd29yc2hpcFwiLFxuICA2MzEwMzogXCJwbGFjZS1vZi13b3JzaGlwXCIsXG5cbiAgcGxhbmU6IFwicGxhbmVcIixcbiAgNjE1NTQ6IFwicGxhbmVcIixcblxuICBcInBsYW5lLWFycml2YWxcIjogXCJwbGFuZS1hcnJpdmFsXCIsXG4gIDYyODk1OiBcInBsYW5lLWFycml2YWxcIixcblxuICBcInBsYW5lLWRlcGFydHVyZVwiOiBcInBsYW5lLWRlcGFydHVyZVwiLFxuICA2Mjg5NjogXCJwbGFuZS1kZXBhcnR1cmVcIixcblxuICBcInBsYW5lLXNsYXNoXCI6IFwicGxhbmUtc2xhc2hcIixcbiAgNTc0NDk6IFwicGxhbmUtc2xhc2hcIixcblxuICBwbHVnOiBcInBsdWdcIixcbiAgNjE5MjY6IFwicGx1Z1wiLFxuXG4gIDYyMTU4OiBcInBvZGNhc3RcIixcblxuICBwb2xsOiBcInBvbGxcIixcbiAgNjMxMDU6IFwicG9sbFwiLFxuXG4gIFwicG9sbC1oXCI6IFwicG9sbC1oXCIsXG4gIDYzMTA2OiBcInBvbGwtaFwiLFxuXG4gIHBvbzogXCJwb29cIixcbiAgNjIyMDY6IFwicG9vXCIsXG5cbiAgcG9ydHJhaXQ6IFwiYWRkcmVzcy1ib29rXCIsXG4gIDYyNDMyOiBcImFkZHJlc3MtYm9va1wiLFxuXG4gIFwicG91bmQtc2lnblwiOiBcInBvdW5kLXNpZ25cIixcbiAgNjE3ODA6IFwicG91bmQtc2lnblwiLFxuXG4gIHByYXk6IFwicHJheVwiLFxuICA2MzEwNzogXCJwcmF5XCIsXG5cbiAgXCJwcmF5aW5nLWhhbmRzXCI6IFwicHJheWluZy1oYW5kc1wiLFxuICA2MzEwODogXCJwcmF5aW5nLWhhbmRzXCIsXG5cbiAgXCJwcm9qZWN0LWRpYWdyYW1cIjogXCJwcm9qZWN0LWRpYWdyYW1cIixcbiAgNjI3ODY6IFwicHJvamVjdC1kaWFncmFtXCIsXG5cbiAgXCJwdXp6bGUtcGllY2VcIjogXCJwdXp6bGUtcGllY2VcIixcbiAgNjE3NDI6IFwicHV6emxlLXBpZWNlXCIsXG5cbiAgXCJxdWVzdGlvbi1jaXJjbGVcIjogXCJxdWVzdGlvbi1jaXJjbGVcIixcbiAgNjE1Mjk6IFwicXVlc3Rpb24tY2lyY2xlXCIsXG5cbiAgXCJxdW90ZS1sZWZ0XCI6IFwicXVvdGUtbGVmdFwiLFxuICA2MTcwOTogXCJxdW90ZS1sZWZ0XCIsXG5cbiAgXCJxdW90ZS1yaWdodFwiOiBcInF1b3RlLXJpZ2h0XCIsXG4gIDYxNzEwOiBcInF1b3RlLXJpZ2h0XCIsXG5cbiAgcXVyYW46IFwicXVyYW5cIixcbiAgNjMxMTE6IFwicXVyYW5cIixcblxuICByYWRpYXRpb246IFwicmFkaWF0aW9uXCIsXG4gIDYzNDE3OiBcInJhZGlhdGlvblwiLFxuXG4gIFwicmFkaWF0aW9uLWFsdFwiOiBcInJhZGlhdGlvbi1hbHRcIixcbiAgNjM0MTg6IFwicmFkaWF0aW9uLWFsdFwiLFxuXG4gIHJhaW5ib3c6IFwicmFpbmJvd1wiLFxuICA2MzMyMzogXCJyYWluYm93XCIsXG5cbiAgcmVjZWlwdDogXCJyZWNlaXB0XCIsXG4gIDYyNzg3OiBcInJlY2VpcHRcIixcblxuICBcInJlY29yZC12aW55bFwiOiBcInJlY29yZC12aW55bFwiLFxuICA2MzcwNTogXCJyZWNvcmQtdmlueWxcIixcblxuICByZWdpc3RlcmVkOiBcInJlZ2lzdGVyZWRcIixcbiAgNjIwNDU6IFwicmVnaXN0ZXJlZFwiLFxuXG4gIHJlcHVibGljYW46IFwicmVwdWJsaWNhblwiLFxuICA2MzMyNjogXCJyZXB1YmxpY2FuXCIsXG5cbiAgcmVzdHJvb206IFwicmVzdHJvb21cIixcbiAgNjM0MjE6IFwicmVzdHJvb21cIixcblxuICByaWJib246IFwicmliYm9uXCIsXG4gIDYyNjc4OiBcInJpYmJvblwiLFxuXG4gIHJpbmc6IFwicmluZ1wiLFxuICA2MzI0MzogXCJyaW5nXCIsXG5cbiAgcm9hZDogXCJyb2FkXCIsXG4gIDYxNDY0OiBcInJvYWRcIixcblxuICByb2NrZXQ6IFwicm9ja2V0XCIsXG4gIDYxNzQ5OiBcInJvY2tldFwiLFxuXG4gIHJvYm90OiBcInJvYm90XCIsXG4gIDYyNzg4OiBcInJvYm90XCIsXG5cbiAgcm91dGU6IFwicm91dGVcIixcbiAgNjI2Nzk6IFwicm91dGVcIixcblxuICA2MTU5ODogXCJyc3NcIixcblxuICBcInJzcy1zcXVhcmVcIjogXCJyc3Mtc3F1YXJlXCIsXG4gIDYxNzYzOiBcInJzcy1zcXVhcmVcIixcblxuICBcInJ1bGVyLWNvbWJpbmVkXCI6IFwicnVsZXItY29tYmluZWRcIixcbiAgNjI3OTA6IFwicnVsZXItY29tYmluZWRcIixcblxuICBcInJ1bGVyLWhvcml6b250YWxcIjogXCJydWxlci1ob3Jpem9udGFsXCIsXG4gIDYyNzkxOiBcInJ1bGVyLWhvcml6b250YWxcIixcblxuICBcInJ1bGVyLXZlcnRpY2FsXCI6IFwicnVsZXItdmVydGljYWxcIixcbiAgNjI3OTI6IFwicnVsZXItdmVydGljYWxcIixcblxuICBydW5uaW5nOiBcInJ1bm5pbmdcIixcblxuICBcInJ1cGVlLXNpZ25cIjogXCJydXBlZS1zaWduXCIsXG4gIDYxNzgyOiBcInJ1cGVlLXNpZ25cIixcblxuICBcInNhZC1jcnlcIjogXCJzYWQtY3J5XCIsXG4gIDYyODk5OiBcInNhZC1jcnlcIixcblxuICBcInNhZC10ZWFyXCI6IFwic2FkLXRlYXJcIixcbiAgNjI5MDA6IFwic2FkLXRlYXJcIixcblxuICBzYXRlbGxpdGU6IFwic2F0ZWxsaXRlXCIsXG4gIDYzNDIzOiBcInNhdGVsbGl0ZVwiLFxuXG4gIFwic2F0ZWxsaXRlLWRpc2hcIjogXCJzYXRlbGxpdGUtZGlzaFwiLFxuICA2MzQyNDogXCJzYXRlbGxpdGUtZGlzaFwiLFxuXG4gIHNjaG9vbDogXCJzY2hvb2xcIixcbiAgNjI3OTM6IFwic2Nob29sXCIsXG5cbiAgc2NyZXdkcml2ZXI6IFwic2NyZXdkcml2ZXJcIixcbiAgNjI3OTQ6IFwic2NyZXdkcml2ZXJcIixcblxuICBzY3JvbGw6IFwic2Nyb2xsXCIsXG4gIDYzMjQ2OiBcInNjcm9sbFwiLFxuXG4gIHNlYXJjaDogXCJzZWFyY2hcIixcbiAgNjE0NDI6IFwic2VhcmNoXCIsXG5cbiAgc2VlZGxpbmc6IFwic2VlZGxpbmdcIixcbiAgNjI2ODA6IFwic2VlZGxpbmdcIixcblxuICBzaGFwZXM6IFwic2hhcGVzXCIsXG4gIDYzMDA3OiBcInNoYXBlc1wiLFxuXG4gIFwic2hhcmUtYWx0XCI6IFwic2hhcmUtYWx0XCIsXG4gIDYxOTIwOiBcInNoYXJlLWFsdFwiLFxuXG4gIFwic2hla2VsLXNpZ25cIjogXCJzaGVrZWwtc2lnblwiLFxuICA2MTk2MzogXCJzaGVrZWwtc2lnblwiLFxuXG4gIFwic2hpZWxkLWFsdFwiOiBcInNoaWVsZC1hbHRcIixcbiAgNjI0NDU6IFwic2hpZWxkLWFsdFwiLFxuXG4gIFwic2hpcHBpbmctZmFzdFwiOiBcInNoaXBwaW5nLWZhc3RcIixcbiAgNjI2MDM6IFwic2hpcHBpbmctZmFzdFwiLFxuXG4gIFwic2hvZS1wcmludHNcIjogXCJzaG9lLXByaW50c1wiLFxuICA2Mjc5NTogXCJzaG9lLXByaW50c1wiLFxuXG4gIFwic2hvcHBpbmctYmFnXCI6IFwic2hvcHBpbmctYmFnXCIsXG4gIDYyMDk2OiBcInNob3BwaW5nLWJhZ1wiLFxuXG4gIFwic2hvcHBpbmctYmFza2V0XCI6IFwic2hvcHBpbmctYmFza2V0XCIsXG4gIDYyMDk3OiBcInNob3BwaW5nLWJhc2tldFwiLFxuXG4gIFwic2hvcHBpbmctY2FydFwiOiBcInNob3BwaW5nLWNhcnRcIixcbiAgNjE1NjI6IFwic2hvcHBpbmctY2FydFwiLFxuXG4gIHNob3dlcjogXCJzaG93ZXJcIixcbiAgNjIxNTY6IFwic2hvd2VyXCIsXG5cbiAgXCJzaHV0dGxlLXZhblwiOiBcInNodXR0bGUtdmFuXCIsXG4gIDYyOTAyOiBcInNodXR0bGUtdmFuXCIsXG5cbiAgXCJzaWduLWxhbmd1YWdlXCI6IFwic2lnbi1sYW5ndWFnZVwiLFxuICA2MjExOTogXCJzaWduLWxhbmd1YWdlXCIsXG5cbiAgc2lnbmF0dXJlOiBcInNpZ25hdHVyZVwiLFxuICA2MjkwMzogXCJzaWduYXR1cmVcIixcblxuICBza2F0aW5nOiBcInNrYXRpbmdcIixcbiAgNjM0Mjk6IFwic2thdGluZ1wiLFxuXG4gIHNraWluZzogXCJza2lpbmdcIixcbiAgNjM0MzM6IFwic2tpaW5nXCIsXG5cbiAgXCJza2lpbmctbm9yZGljXCI6IFwic2tpaW5nLW5vcmRpY1wiLFxuICA2MzQzNDogXCJza2lpbmctbm9yZGljXCIsXG5cbiAgc2xlaWdoOiBcInNsZWlnaFwiLFxuICA2MzQzNjogXCJzbGVpZ2hcIixcblxuICBzbWlsZTogXCJzbWlsZVwiLFxuICA2MTcyMDogXCJzbWlsZVwiLFxuXG4gIFwic21pbGUtYmVhbVwiOiBcInNtaWxlLWJlYW1cIixcbiAgNjI5MDQ6IFwic21pbGUtYmVhbVwiLFxuXG4gIFwic21pbGUtd2lua1wiOiBcInNtaWxlLXdpbmtcIixcbiAgNjI2ODI6IFwic21pbGUtd2lua1wiLFxuXG4gIHNtb2c6IFwic21vZ1wiLFxuICA2MzMyNzogXCJzbW9nXCIsXG5cbiAgc25vd2JvYXJkaW5nOiBcInNub3dib2FyZGluZ1wiLFxuICA2MzQzODogXCJzbm93Ym9hcmRpbmdcIixcblxuICBzbm93Zmxha2U6IFwic25vd2ZsYWtlXCIsXG4gIDYyMTcyOiBcInNub3dmbGFrZVwiLFxuXG4gIHNub3dtYW46IFwic25vd21hblwiLFxuICA2MzQ0MDogXCJzbm93bWFuXCIsXG5cbiAgc25vd3Bsb3c6IFwic25vd3Bsb3dcIixcbiAgNjM0NDI6IFwic25vd3Bsb3dcIixcblxuICBzb2NrczogXCJzb2Nrc1wiLFxuICA2MzEyNjogXCJzb2Nrc1wiLFxuXG4gIHNwYTogXCJzcGFcIixcbiAgNjI5MDc6IFwic3BhXCIsXG5cbiAgXCJzcGFjZS1zaHV0dGxlXCI6IFwic3BhY2Utc2h1dHRsZVwiLFxuICA2MTg0NzogXCJzcGFjZS1zaHV0dGxlXCIsXG5cbiAgc3BpZGVyOiBcInNwaWRlclwiLFxuICA2MzI1NTogXCJzcGlkZXJcIixcblxuICBzcXVhcmU6IFwic3F1YXJlXCIsXG4gIDYxNjQwOiBcInNxdWFyZVwiLFxuICA2MTQ0NTogXCJzdGFyXCIsXG5cbiAgc3RhbXA6IFwic3RhbXBcIixcbiAgNjI5MTE6IFwic3RhbXBcIixcblxuICBcInN0YXItYW5kLWNyZXNjZW50XCI6IFwic3Rhci1hbmQtY3Jlc2NlbnRcIixcbiAgNjMxMjk6IFwic3Rhci1hbmQtY3Jlc2NlbnRcIixcblxuICBcInN0YXItb2YtZGF2aWRcIjogXCJzdGFyLW9mLWRhdmlkXCIsXG4gIDYzMTMwOiBcInN0YXItb2YtZGF2aWRcIixcblxuICBcInN0aWNreS1ub3RlXCI6IFwic3RpY2t5LW5vdGVcIixcbiAgNjIwMjU6IFwic3RpY2t5LW5vdGVcIixcblxuICBzdG9wd2F0Y2g6IFwic3RvcHdhdGNoXCIsXG4gIDYyMTk0OiBcInN0b3B3YXRjaFwiLFxuXG4gIFwic3RvcHdhdGNoLTIwXCI6IFwic3RvcHdhdGNoLTIwXCIsXG4gIDU3NDU1OiBcInN0b3B3YXRjaC0yMFwiLFxuXG4gIHN0b3JlOiBcInN0b3JlXCIsXG4gIDYyNzk4OiBcInN0b3JlXCIsXG5cbiAgXCJzdG9yZS1hbHRcIjogXCJzdG9yZS1hbHRcIixcbiAgNjI3OTk6IFwic3RvcmUtYWx0XCIsXG5cbiAgXCJzdG9yZS1hbHQtc2xhc2hcIjogXCJzdG9yZS1hbHQtc2xhc2hcIixcbiAgNTc0NTY6IFwic3RvcmUtYWx0LXNsYXNoXCIsXG5cbiAgXCJzdG9yZS1zbGFzaFwiOiBcInN0b3JlLXNsYXNoXCIsXG5cbiAgc3RyZWFtOiBcInN0cmVhbVwiLFxuICA2MjgwMDogXCJzdHJlYW1cIixcblxuICBcInN0cmVldC12aWV3XCI6IFwic3RyZWV0LXZpZXdcIixcbiAgNjE5ODE6IFwic3RyZWV0LXZpZXdcIixcblxuICBzdHJvb3B3YWZlbDogXCJzdHJvb3B3YWZlbFwiLFxuICA2MjgwMTogXCJzdHJvb3B3YWZlbFwiLFxuXG4gIHN1YndheTogXCJzdWJ3YXlcIixcbiAgNjIwMDk6IFwic3Vid2F5XCIsXG5cbiAgc3VpdGNhc2U6IFwic3VpdGNhc2VcIixcbiAgNjE2ODI6IFwic3VpdGNhc2VcIixcblxuICBcInN1aXRjYXNlLXJvbGxpbmdcIjogXCJzdWl0Y2FzZS1yb2xsaW5nXCIsXG4gIDYyOTEzOiBcInN1aXRjYXNlLXJvbGxpbmdcIixcblxuICBzdW46IFwic3VuXCIsXG4gIDYxODI5OiBcInN1blwiLFxuXG4gIHN1cnByaXNlOiBcInN1cnByaXNlXCIsXG4gIDYyOTE0OiBcInN1cnByaXNlXCIsXG5cbiAgc3dpbW1lcjogXCJzd2ltbWVyXCIsXG4gIDYyOTE2OiBcInN3aW1tZXJcIixcblxuICBcInN3aW1taW5nLXBvb2xcIjogXCJzd2ltbWluZy1wb29sXCIsXG4gIDYyOTE3OiBcInN3aW1taW5nLXBvb2xcIixcblxuICBzeW5hZ29ndWU6IFwic3luYWdvZ3VlXCIsXG4gIDYzMTMxOiBcInN5bmFnb2d1ZVwiLFxuXG4gIHRhYmxlOiBcInRhYmxlXCIsXG4gIDYxNjQ2OiBcInRhYmxlXCIsXG5cbiAgXCJ0YWJsZS10ZW5uaXNcIjogXCJ0YWJsZS10ZW5uaXNcIixcbiAgNjI1NTc6IFwidGFibGUtdGVubmlzXCIsXG5cbiAgdGFibGV0OiBcInRhYmxldFwiLFxuICA2MTcwNjogXCJ0YWJsZXRcIixcblxuICBcInRhYmxldC1hbHRcIjogXCJ0YWJsZXQtYWx0XCIsXG4gIDYyNDU4OiBcInRhYmxldC1hbHRcIixcblxuICBcInRhY2hvbWV0ZXItYWx0XCI6IFwidGFjaG9tZXRlci1hbHRcIixcbiAgNjI0NjE6IFwidGFjaG9tZXRlci1hbHRcIixcblxuICB0YWc6IFwidGFnXCIsXG4gIDYxNDgzOiBcInRhZ1wiLFxuXG4gIHRhZ3M6IFwidGFnc1wiLFxuICA2MTQ4NDogXCJ0YWdzXCIsXG5cbiAgdGFza3M6IFwidGFza3NcIixcbiAgNjE2MTQ6IFwidGFza3NcIixcblxuICB0YXhpOiBcInRheGlcIixcbiAgNjE4ODI6IFwidGF4aVwiLFxuXG4gIFwidGVtcGVyYXR1cmUtaGlnaFwiOiBcInRlbXBlcmF0dXJlLWhpZ2hcIixcbiAgNjMzMzc6IFwidGVtcGVyYXR1cmUtaGlnaFwiLFxuXG4gIFwidGVtcGVyYXR1cmUtbG93XCI6IFwidGVtcGVyYXR1cmUtbG93XCIsXG4gIDYzMzM5OiBcInRlbXBlcmF0dXJlLWxvd1wiLFxuXG4gIHRlbmdlOiBcInRlbmdlXCIsXG4gIDYzNDQ3OiBcInRlbmdlXCIsXG5cbiAgXCJ0aGVhdGVyLW1hc2tzXCI6IFwidGhlYXRlci1tYXNrc1wiLFxuICA2MzAyNDogXCJ0aGVhdGVyLW1hc2tzXCIsXG5cbiAgXCJ0aHVtYnMtZG93blwiOiBcInRodW1icy1kb3duXCIsXG4gIDYxNzk3OiBcInRodW1icy1kb3duXCIsXG5cbiAgXCJ0aHVtYnMtdXBcIjogXCJ0aHVtYnMtdXBcIixcbiAgNjE3OTY6IFwidGh1bWJzLXVwXCIsXG5cbiAgdGh1bWJ0YWNrOiBcInRodW1idGFja1wiLFxuICA2MTU4MTogXCJ0aHVtYnRhY2tcIixcblxuICBcInRpY2tldC1hbHRcIjogXCJ0aWNrZXRcIixcbiAgNjI0NjM6IFwidGlja2V0XCIsXG5cbiAgdG9pbGV0OiBcInRvaWxldFwiLFxuICA2MzQ0ODogXCJ0b2lsZXRcIixcblxuICBcInRvaWxldC1wYXBlclwiOiBcInRvaWxldC1wYXBlclwiLFxuICA2MzI2MjogXCJ0b2lsZXQtcGFwZXJcIixcblxuICB0b29sYm94OiBcInRvb2xib3hcIixcbiAgNjI4MDI6IFwidG9vbGJveFwiLFxuXG4gIHRvb2xzOiBcInRvb2xzXCIsXG4gIDYzNDQ5OiBcInRvb2xzXCIsXG5cbiAgdG9yYWg6IFwidG9yYWhcIixcbiAgNjMxMzY6IFwidG9yYWhcIixcblxuICBcInRvcmlpLWdhdGVcIjogXCJ0b3JpaS1nYXRlXCIsXG4gIDYzMTM3OiBcInRvcmlpLWdhdGVcIixcblxuICB0cmFjdG9yOiBcInRyYWN0b3JcIixcbiAgNjMyNjY6IFwidHJhY3RvclwiLFxuXG4gIHRyYWRlbWFyazogXCJ0cmFkZW1hcmtcIixcbiAgNjIwNDQ6IFwidHJhZGVtYXJrXCIsXG5cbiAgdHJhaWxlcjogXCJ0cmFpbGVyXCIsXG5cbiAgdHJhaW46IFwidHJhaW5cIixcbiAgNjIwMDg6IFwidHJhaW5cIixcblxuICB0cmFtOiBcInRyYW1cIixcbiAgNjM0NTA6IFwidHJhbVwiLFxuXG4gIFwidHJhbnNnZW5kZXItYWx0XCI6IFwidHJhbnNnZW5kZXJcIixcbiAgNjE5ODk6IFwidHJhbnNnZW5kZXJcIixcblxuICB0cmVlOiBcInRyZWVcIixcbiAgNjE4ODM6IFwidHJlZVwiLFxuXG4gIHRyb3BoeTogXCJ0cm9waHlcIixcbiAgNjE1ODU6IFwidHJvcGh5XCIsXG5cbiAgdHJ1Y2s6IFwidHJ1Y2tcIixcbiAgNjE2NDk6IFwidHJ1Y2tcIixcblxuICBcInRydWNrLW1vbnN0ZXJcIjogXCJ0cnVjay1tb25zdGVyXCIsXG4gIDYzMDM1OiBcInRydWNrLW1vbnN0ZXJcIixcblxuICBcInRydWNrLXBpY2t1cFwiOiBcInRydWNrLXBpY2t1cFwiLFxuICA2MzAzNjogXCJ0cnVjay1waWNrdXBcIixcblxuICB0c2hpcnQ6IFwidHNoaXJ0XCIsXG4gIDYyODAzOiBcInRzaGlydFwiLFxuXG4gIHR0eTogXCJ0dHlcIixcbiAgNjE5MjQ6IFwidHR5XCIsXG5cbiAgdHY6IFwidHZcIixcbiAgNjIwNjA6IFwidHZcIixcbiAgNjE1OTM6IFwidHdpdHRlclwiLFxuXG4gIFwidHdpdHRlci1zcXVhcmVcIjogXCJ0d2l0dGVyLXNxdWFyZVwiLFxuICA2MTU2OTogXCJ0d2l0dGVyLXNxdWFyZVwiLFxuXG4gIHVtYnJlbGxhOiBcInVtYnJlbGxhXCIsXG4gIDYxNjczOiBcInVtYnJlbGxhXCIsXG5cbiAgXCJ1bWJyZWxsYS1iZWFjaFwiOiBcInVtYnJlbGxhLWJlYWNoXCIsXG4gIDYyOTIyOiBcInVtYnJlbGxhLWJlYWNoXCIsXG5cbiAgdW5pdmVyc2l0eTogXCJ1bml2ZXJzaXR5XCIsXG4gIDYxODUyOiBcInVuaXZlcnNpdHlcIixcblxuICBcInVuaXZlcnNhbC1hY2Nlc3NcIjogXCJ1bml2ZXJzYWwtYWNjZXNzXCIsXG4gIDYyMTA2OiBcInVuaXZlcnNhbC1hY2Nlc3NcIixcblxuICB1bmxvY2s6IFwidW5sb2NrXCIsXG4gIDYxNTk2OiBcInVubG9ja1wiLFxuXG4gIFwidW5sb2NrLWFsdFwiOiBcInVubG9jay1hbHRcIixcbiAgNjE3NTg6IFwidW5sb2NrLWFsdFwiLFxuXG4gIHVzZXI6IFwidXNlclwiLFxuICA2MTQ0NzogXCJ1c2VyXCIsXG5cbiAgdXNlcnM6IFwidXNlcnNcIixcbiAgNjE2MzI6IFwidXNlcnNcIixcblxuICBcInV0ZW5zaWwtc3Bvb25cIjogXCJ1dGVuc2lsLXNwb29uXCIsXG4gIDYyMTgxOiBcInV0ZW5zaWwtc3Bvb25cIixcblxuICB1dGVuc2lsczogXCJ1dGVuc2lsc1wiLFxuICA2MjE4MzogXCJ1dGVuc2lsc1wiLFxuXG4gIHZlbnVzOiBcInZlbnVzXCIsXG4gIDYxOTg1OiBcInZlbnVzXCIsXG5cbiAgXCJ2ZW51cy1kb3VibGVcIjogXCJ2ZW51cy1kb3VibGVcIixcbiAgNjE5OTA6IFwidmVudXMtZG91YmxlXCIsXG5cbiAgXCJ2ZW51cy1tYXJzXCI6IFwidmVudXMtbWFyc1wiLFxuICA2MTk5MjogXCJ2ZW51cy1tYXJzXCIsXG4gIDYxNTAxOiBcInZpZGVvXCIsXG5cbiAgdmloYXJhOiBcInZpaGFyYVwiLFxuICA2MzE0MzogXCJ2aWhhcmFcIixcblxuICA2MjQ3NDogXCJ2aW1lb1wiLFxuXG4gIFwidmltZW8tc3F1YXJlXCI6IFwidmltZW8tc3F1YXJlXCIsXG4gIDYxODQ0OiBcInZpbWVvLXNxdWFyZVwiLFxuXG4gIFwidmltZW8tdlwiOiBcInZpbWVvLXZcIixcbiAgNjIwNzc6IFwidmltZW8tdlwiLFxuXG4gIHZvaWNlbWFpbDogXCJ2b2ljZW1haWxcIixcbiAgNjM2Mzk6IFwidm9pY2VtYWlsXCIsXG5cbiAgXCJ2b2xsZXliYWxsLWJhbGxcIjogXCJ2b2xsZXliYWxsLWJhbGxcIixcbiAgNjI1NTk6IFwidm9sbGV5YmFsbC1iYWxsXCIsXG5cbiAgXCJ2b3RlLXllYVwiOiBcInZvdGUteWVhXCIsXG4gIDYzMzQ2OiBcInZvdGUteWVhXCIsXG5cbiAgXCJ2ci1jYXJkYm9hcmRcIjogXCJcIixcbiAgNjMyNzM6IFwidnItY2FyZGJvYXJkXCIsXG5cbiAgd2Fsa2luZzogXCJ3YWxraW5nXCIsXG4gIDYyODA0OiBcIndhbGtpbmdcIixcblxuICB3YWxsZXQ6IFwid2FsbGV0XCIsXG4gIDYyODA1OiBcIndhbGxldFwiLFxuXG4gIHdhcmVob3VzZTogXCJ3YXJlaG91c2VcIixcbiAgNjI2MTI6IFwid2FyZWhvdXNlXCIsXG5cbiAgd2F0ZXI6IFwid2F0ZXJcIixcbiAgNjMzNDc6IFwid2F0ZXJcIixcblxuICB3ZWlnaHQ6IFwid2VpZ2h0XCIsXG4gIDYyNjE0OiBcIndlaWdodFwiLFxuXG4gIFwid2VpZ2h0LWhhbmdpbmdcIjogXCJ3ZWlnaHQtaGFuZ2luZ1wiLFxuICA2MjkyNTogXCJ3ZWlnaHQtaGFuZ2luZ1wiLFxuXG4gIHdoZWVsY2hhaXI6IFwid2hlZWxjaGFpclwiLFxuICA2MTg0MzogXCJ3aGVlbGNoYWlyXCIsXG5cbiAgd2lmaTogXCJ3aWZpXCIsXG4gIDYxOTMxOiBcIndpZmlcIixcblxuICB3aW5kOiBcIndpbmRcIixcbiAgNjMyNzg6IFwid2luZFwiLFxuXG4gIFwid2luZS1ib3R0bGVcIjogXCJ3aW5lLWJvdHRsZVwiLFxuICA2MzI3OTogXCJ3aW5lLWJvdHRsZVwiLFxuXG4gIFwid2luZS1nbGFzc1wiOiBcIndpbmUtZ2xhc3NcIixcbiAgNjI2OTE6IFwid2luZS1nbGFzc1wiLFxuXG4gIFwid2luZS1nbGFzcy1hbHRcIjogXCJ3aW5lLWdsYXNzLWFsdFwiLFxuICA2MjkyNjogXCJ3aW5lLWdsYXNzLWFsdFwiLFxuXG4gIFwid29uLXNpZ25cIjogXCJ3b24tc2lnblwiLFxuICA2MTc4NTogXCJ3b24tc2lnblwiLFxuXG4gIHdyZW5jaDogXCJ3cmVuY2hcIixcbiAgNjE2MTM6IFwid3JlbmNoXCIsXG5cbiAgXCJ5ZW4tc2lnblwiOiBcInllbi1zaWduXCIsXG4gIDYxNzgzOiBcInllbi1zaWduXCIsXG5cbiAgXCJ5aW4teWFuZ1wiOiBcInlpbi15YW5nXCIsXG4gIDYzMTQ5OiBcInlpbi15YW5nXCIsXG5cbiAgNjE3OTk6IFwieW91dHViZVwiLFxuXG4gIFwieW91dHViZS1zcXVhcmVcIjogXCJ5b3V0dWJlLXNxdWFyZVwiLFxuXG4gIGJsaXA6IFwicnNzXCIsXG4gIDU3MzYxOiBcInJzc1wiLFxuXG4gIGZlZWRidXJuZXI6IFwiZmlyZVwiLFxuICA1NzM4NDogXCJmaXJlXCIsXG5cbiAgZGlhZ29uYWxhcnJvdzogXCJhcnJvdy11cC1yaWdodC1mcm9tLXNxdWFyZVwiLFxuICA1NzcwNTogXCJhcnJvdy11cC1yaWdodC1mcm9tLXNxdWFyZVwiLFxuXG4gIGNpcmNsZWRpYWdvbmFsYXJyb3c6IFwiYXJyb3ctdXAtcmlnaHQtZnJvbS1zcXVhcmVcIixcbiAgNTgyMTc6IFwiYXJyb3ctdXAtcmlnaHQtZnJvbS1zcXVhcmVcIixcblxuICBjaXJjbGVibGlwOiBcInJzcy1zcXVhcmVcIixcbiAgNTc4NzM6IFwicnNzLXNxdWFyZVwiLFxuXG4gIGdvb2dsZXRhbGs6IFwiY29tbWVudFwiLFxuICA1NzQwODogXCJjb21tZW50XCIsXG5cbiAgY2lyY2xlZ29vZ2xldGFsazogXCJjb21tZW50XCIsXG4gIDU3OTIwOiBcImNvbW1lbnRcIixcblxuICByb3VuZGVkZ29vZ2xldGFsazogXCJjb21tZW50XCIsXG4gIDU4NDMyOiBcImNvbW1lbnRcIixcblxuICBwaG90b2J1Y2tldDogXCJjYW1lcmFcIixcbiAgNTc0NDI6IFwiY2FtZXJhXCIsXG5cbiAgY2lyY2xlcGhvdG9idWNrZXQ6IFwiY2FtZXJhXCIsXG4gIDU3OTU0OiBcImNhbWVyYVwiLFxuXG4gIHJvdW5kZWRwaG90b2J1Y2tldDogXCJjYW1lcmFcIixcbiAgNTg0NjY6IFwiY2FtZXJhXCIsXG5cbiAgcGljYXNhOiBcImltYWdlXCIsXG4gIDU3NDQzOiBcImltYWdlXCIsXG5cbiAgY2lyY2xlcGljYXNhOiBcImltYWdlXCIsXG4gIDU3OTU1OiBcImltYWdlXCIsXG5cbiAgcm91bmRlZHBpY2FzYTogXCJpbWFnZVwiLFxuICA1ODQ2NzogXCJpbWFnZVwiXG59O1xuIiwgImltcG9ydCB7IE1WYWx1ZSB9IGZyb20gXCIuLi90eXBlc1wiO1xuXG5leHBvcnQgZnVuY3Rpb24gZ2V0UGFyZW50RWxlbWVudE9mVGV4dE5vZGUobm9kZTogRWxlbWVudCk6IE1WYWx1ZTxFbGVtZW50PiB7XG4gIGlmIChub2RlLm5vZGVUeXBlID09PSBOb2RlLlRFWFRfTk9ERSkge1xuICAgIHJldHVybiAobm9kZS5wYXJlbnROb2RlIGFzIEVsZW1lbnQpID8/IHVuZGVmaW5lZDtcbiAgfVxuXG4gIHJldHVybiBBcnJheS5mcm9tKG5vZGUuY2hpbGROb2RlcykuZmluZCgobm9kZSkgPT5cbiAgICBnZXRQYXJlbnRFbGVtZW50T2ZUZXh0Tm9kZShub2RlIGFzIEVsZW1lbnQpXG4gICkgYXMgRWxlbWVudDtcbn1cbiIsICJpbXBvcnQgeyBFbGVtZW50TW9kZWwgfSBmcm9tIFwiLi4vLi4vLi4vLi4vdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgZ2V0R2xvYmFsSWNvbk1vZGVsIH0gZnJvbSBcIi4uLy4uLy4uLy4uL3V0aWxzL2dldEdsb2JhbEljb25Nb2RlbFwiO1xuaW1wb3J0IHsgZ2V0SHJlZiwgbm9ybWFsaXplT3BhY2l0eSB9IGZyb20gXCIuLi8uLi8uLi91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IGNvZGVUb0J1aWxkZXJNYXAsIGRlZmF1bHRJY29uIH0gZnJvbSBcIi4vaWNvbk1hcHBpbmdcIjtcbmltcG9ydCB7IG1QaXBlIH0gZnJvbSBcImZwLXV0aWxpdGllc1wiO1xuaW1wb3J0IHsgcGFyc2VDb2xvclN0cmluZyB9IGZyb20gXCJ1dGlscy9zcmMvY29sb3IvcGFyc2VDb2xvclN0cmluZ1wiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5pbXBvcnQgeyBnZXRQYXJlbnRFbGVtZW50T2ZUZXh0Tm9kZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldFBhcmVudEVsZW1lbnRPZlRleHROb2RlXCI7XG5pbXBvcnQgKiBhcyBPYmogZnJvbSBcInV0aWxzL3NyYy9yZWFkZXIvb2JqZWN0XCI7XG5pbXBvcnQgKiBhcyBTdHIgZnJvbSBcInV0aWxzL3NyYy9yZWFkZXIvc3RyaW5nXCI7XG5pbXBvcnQgeyB1dWlkIH0gZnJvbSBcInV0aWxzL3NyYy91dWlkXCI7XG5cbmNvbnN0IGdldENvbG9yID0gbVBpcGUoT2JqLnJlYWRLZXkoXCJjb2xvclwiKSwgU3RyLnJlYWQsIHBhcnNlQ29sb3JTdHJpbmcpO1xuY29uc3QgZ2V0QmdDb2xvciA9IG1QaXBlKFxuICBPYmoucmVhZEtleShcImJhY2tncm91bmQtY29sb3JcIiksXG4gIFN0ci5yZWFkLFxuICBwYXJzZUNvbG9yU3RyaW5nXG4pO1xuXG5leHBvcnQgY29uc3QgZ2V0U3R5bGVzID0gKG5vZGU6IEVsZW1lbnQpID0+IHtcbiAgY29uc3QgcGFyZW50Tm9kZSA9IGdldFBhcmVudEVsZW1lbnRPZlRleHROb2RlKG5vZGUpO1xuICBjb25zdCBpc0ljb25UZXh0ID0gcGFyZW50Tm9kZT8ubm9kZU5hbWUgPT09IFwiI3RleHRcIjtcbiAgY29uc3QgaWNvbk5vZGUgPSBpc0ljb25UZXh0ID8gbm9kZSA6IHBhcmVudE5vZGU7XG4gIHJldHVybiBpY29uTm9kZSA/IGdldE5vZGVTdHlsZShpY29uTm9kZSkgOiB7fTtcbn07XG5cbmV4cG9ydCBjb25zdCBnZXRQYXJlbnRTdHlsZXMgPSAobm9kZTogRWxlbWVudCkgPT4ge1xuICBjb25zdCBwYXJlbnRFbGVtZW50ID0gbm9kZS5wYXJlbnRFbGVtZW50O1xuICByZXR1cm4gcGFyZW50RWxlbWVudCA/IGdldE5vZGVTdHlsZShwYXJlbnRFbGVtZW50KSA6IHt9O1xufTtcblxuZXhwb3J0IGNvbnN0IGdldFN0eWxlTW9kZWwgPSAobm9kZTogRWxlbWVudCkgPT4ge1xuICBjb25zdCBzdHlsZSA9IGdldFN0eWxlcyhub2RlKTtcbiAgY29uc3QgcGFyZW50U3R5bGUgPSBnZXRQYXJlbnRTdHlsZXMobm9kZSk7XG4gIGNvbnN0IG9wYWNpdHkgPSArc3R5bGUub3BhY2l0eTtcbiAgY29uc3QgY29sb3IgPSBnZXRDb2xvcihzdHlsZSk7XG4gIGNvbnN0IGJnQ29sb3IgPSBnZXRCZ0NvbG9yKHBhcmVudFN0eWxlKTtcblxuICByZXR1cm4ge1xuICAgIC4uLihjb2xvciAmJiB7XG4gICAgICBjb2xvckhleDogbm9ybWFsaXplT3BhY2l0eSh7XG4gICAgICAgIGhleDogY29sb3IuaGV4LFxuICAgICAgICBvcGFjaXR5OiBjb2xvci5vcGFjaXR5ID8/IFN0cmluZyhvcGFjaXR5KVxuICAgICAgfSkuaGV4LFxuICAgICAgY29sb3JPcGFjaXR5OiBub3JtYWxpemVPcGFjaXR5KHtcbiAgICAgICAgaGV4OiBjb2xvci5oZXgsXG4gICAgICAgIG9wYWNpdHk6IGlzTmFOKG9wYWNpdHkpID8gY29sb3Iub3BhY2l0eSA/PyBcIjFcIiA6IFN0cmluZyhvcGFjaXR5KVxuICAgICAgfSkub3BhY2l0eSxcbiAgICAgIGNvbG9yUGFsZXR0ZTogXCJcIixcblxuICAgICAgaG92ZXJDb2xvckhleDogbm9ybWFsaXplT3BhY2l0eSh7XG4gICAgICAgIGhleDogY29sb3IuaGV4LFxuICAgICAgICBvcGFjaXR5OiBjb2xvci5vcGFjaXR5ID8/IFN0cmluZyhvcGFjaXR5KVxuICAgICAgfSkuaGV4LFxuICAgICAgaG92ZXJDb2xvck9wYWNpdHk6IDAuOCxcbiAgICAgIGhvdmVyQ29sb3JQYWxldHRlOiBcIlwiXG4gICAgfSksXG4gICAgLi4uKGJnQ29sb3IgJiYge1xuICAgICAgYmdDb2xvckhleDogYmdDb2xvci5oZXgsXG4gICAgICBiZ0NvbG9yT3BhY2l0eTogYmdDb2xvci5vcGFjaXR5LFxuICAgICAgYmdDb2xvclBhbGV0dGU6IFwiXCIsXG5cbiAgICAgIHBhZGRpbmc6IDdcbiAgICB9KVxuICB9O1xufTtcblxuZXhwb3J0IGZ1bmN0aW9uIGdldE1vZGVsKFxuICBub2RlOiBFbGVtZW50LFxuICB1cmxNYXA6IFJlY29yZDxzdHJpbmcsIHN0cmluZz5cbik6IEVsZW1lbnRNb2RlbCB7XG4gIGNvbnN0IHBhcmVudE5vZGUgPSBnZXRQYXJlbnRFbGVtZW50T2ZUZXh0Tm9kZShub2RlKTtcbiAgY29uc3QgaXNJY29uVGV4dCA9IHBhcmVudE5vZGU/Lm5vZGVOYW1lID09PSBcIiN0ZXh0XCI7XG4gIGNvbnN0IGljb25Ob2RlID0gaXNJY29uVGV4dCA/IG5vZGUgOiBwYXJlbnROb2RlO1xuICBjb25zdCBtb2RlbFN0eWxlID0gZ2V0U3R5bGVNb2RlbChub2RlKTtcbiAgY29uc3QgaWNvbkNvZGUgPSBpY29uTm9kZT8udGV4dENvbnRlbnQ/LmNoYXJDb2RlQXQoMCk7XG4gIGNvbnN0IGdsb2JhbE1vZGVsID0gZ2V0R2xvYmFsSWNvbk1vZGVsKCk7XG5cbiAgY29uc3QgcGFyZW50RWxlbWVudCA9IG5vZGUucGFyZW50RWxlbWVudDtcbiAgY29uc3QgaXNMaW5rID0gcGFyZW50RWxlbWVudD8udGFnTmFtZSA9PT0gXCJBXCIgfHwgbm9kZS50YWdOYW1lID09PSBcIkFcIjtcbiAgY29uc3QgaHJlZiA9IGdldEhyZWYocGFyZW50RWxlbWVudCkgPz8gZ2V0SHJlZihub2RlKSA/PyBcIlwiO1xuICBjb25zdCBtYXBwZWRIcmVmID0gaHJlZiAmJiB1cmxNYXBbaHJlZl0gIT09IHVuZGVmaW5lZCA/IHVybE1hcFtocmVmXSA6IGhyZWY7XG5cbiAgcmV0dXJuIHtcbiAgICB0eXBlOiBcIkljb25cIixcbiAgICB2YWx1ZToge1xuICAgICAgX2lkOiB1dWlkKCksXG4gICAgICBfc3R5bGVzOiBbXCJpY29uXCJdLFxuICAgICAgLi4uZ2xvYmFsTW9kZWwsXG4gICAgICAuLi5tb2RlbFN0eWxlLFxuICAgICAgY3VzdG9tU2l6ZTogMjYsXG4gICAgICBwYWRkaW5nOiA3LFxuICAgICAgbmFtZTogaWNvbkNvZGUgPyBjb2RlVG9CdWlsZGVyTWFwW2ljb25Db2RlXSA/PyBkZWZhdWx0SWNvbiA6IGRlZmF1bHRJY29uLFxuICAgICAgdHlwZTogaWNvbkNvZGUgPyBcImZhXCIgOiBcImdseXBoXCIsXG4gICAgICAuLi4oaXNMaW5rICYmIHtcbiAgICAgICAgbGlua0V4dGVybmFsOiBtYXBwZWRIcmVmLFxuICAgICAgICBsaW5rVHlwZTogXCJleHRlcm5hbFwiLFxuICAgICAgICBsaW5rRXh0ZXJuYWxCbGFuazogXCJvblwiXG4gICAgICB9KVxuICAgIH1cbiAgfTtcbn1cbiIsICJleHBvcnQgZnVuY3Rpb24gcGlwZTxUMT4oZm4wOiAoKSA9PiBUMSk6ICgpID0+IFQxO1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFQxPihmbjA6ICh4MDogVjApID0+IFQxKTogKHgwOiBWMCkgPT4gVDE7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFQxPihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxXG4pOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxO1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBWMiwgVDE+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMVxuKTogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQxO1xuXG5leHBvcnQgZnVuY3Rpb24gcGlwZTxUMSwgVDI+KGZuMDogKCkgPT4gVDEsIGZuMTogKHg6IFQxKSA9PiBUMik6ICgpID0+IFQyO1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFQxLCBUMj4oXG4gIGZuMDogKHgwOiBWMCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMlxuKTogKHgwOiBWMCkgPT4gVDI7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFQxLCBUMj4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyXG4pOiAoeDA6IFYwLCB4MTogVjEpID0+IFQyO1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBWMiwgVDEsIFQyPihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMlxuKTogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQyO1xuXG5leHBvcnQgZnVuY3Rpb24gcGlwZTxUMSwgVDIsIFQzPihcbiAgZm4wOiAoKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDNcbik6ICgpID0+IFQzO1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFQxLCBUMiwgVDM+KFxuICBmbjA6ICh4OiBWMCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzXG4pOiAoeDogVjApID0+IFQzO1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBUMSwgVDIsIFQzPihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUM1xuKTogKHgwOiBWMCwgeDE6IFYxKSA9PiBUMztcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVjIsIFQxLCBUMiwgVDM+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDNcbik6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMztcblxuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VDEsIFQyLCBUMywgVDQ+KFxuICBmbjA6ICgpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0XG4pOiAoKSA9PiBUNDtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBUMSwgVDIsIFQzLCBUND4oXG4gIGZuMDogKHg6IFYwKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNFxuKTogKHg6IFYwKSA9PiBUNDtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVDEsIFQyLCBUMywgVDQ+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDRcbik6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDQ7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFYyLCBUMSwgVDIsIFQzLCBUND4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0XG4pOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDQ7XG5cbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFQxLCBUMiwgVDMsIFQ0LCBUNT4oXG4gIGZuMDogKCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNVxuKTogKCkgPT4gVDU7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVDEsIFQyLCBUMywgVDQsIFQ1PihcbiAgZm4wOiAoeDogVjApID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDVcbik6ICh4OiBWMCkgPT4gVDU7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFQxLCBUMiwgVDMsIFQ0LCBUNT4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1XG4pOiAoeDA6IFYwLCB4MTogVjEpID0+IFQ1O1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBWMiwgVDEsIFQyLCBUMywgVDQsIFQ1PihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNVxuKTogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQ1O1xuXG5leHBvcnQgZnVuY3Rpb24gcGlwZTxUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2PihcbiAgZm4wOiAoKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDZcbik6ICgpID0+IFQ2O1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDY+KFxuICBmbjA6ICh4OiBWMCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2XG4pOiAoeDogVjApID0+IFQ2O1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2PihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNlxuKTogKHgwOiBWMCwgeDE6IFYxKSA9PiBUNjtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVjIsIFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDY+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDZcbik6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUNjtcblxuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDc+KFxuICBmbjA6ICgpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm46ICh4OiBUNikgPT4gVDdcbik6ICgpID0+IFQ3O1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDYsIFQ3PihcbiAgZm4wOiAoeDogVjApID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm46ICh4OiBUNikgPT4gVDdcbik6ICh4OiBWMCkgPT4gVDc7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDYsIFQ3PihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3XG4pOiAoeDA6IFYwLCB4MTogVjEpID0+IFQ3O1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBWMiwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDc+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDYsXG4gIGZuNjogKHg6IFQ2KSA9PiBUN1xuKTogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQ3O1xuXG5leHBvcnQgZnVuY3Rpb24gcGlwZTxUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNywgVDg+KFxuICBmbjA6ICgpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3LFxuICBmbjogKHg6IFQ3KSA9PiBUOFxuKTogKCkgPT4gVDg7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDcsIFQ4PihcbiAgZm4wOiAoeDogVjApID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3LFxuICBmbjogKHg6IFQ3KSA9PiBUOFxuKTogKHg6IFYwKSA9PiBUODtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDcsIFQ4PihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3LFxuICBmbjc6ICh4OiBUNykgPT4gVDhcbik6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDg7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFYyLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNywgVDg+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDYsXG4gIGZuNjogKHg6IFQ2KSA9PiBUNyxcbiAgZm43OiAoeDogVDcpID0+IFQ4XG4pOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDg7XG5cbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDYsIFQ3LCBUOCwgVDk+KFxuICBmbjA6ICgpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3LFxuICBmbjc6ICh4OiBUNykgPT4gVDgsXG4gIGZuODogKHg6IFQ4KSA9PiBUOVxuKTogKCkgPT4gVDk7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDcsIFQ4LCBUOT4oXG4gIGZuMDogKHgwOiBWMCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjY6ICh4OiBUNikgPT4gVDcsXG4gIGZuNzogKHg6IFQ3KSA9PiBUOCxcbiAgZm44OiAoeDogVDgpID0+IFQ5XG4pOiAoeDA6IFYwKSA9PiBUOTtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDcsIFQ4LCBUOT4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDYsXG4gIGZuNjogKHg6IFQ2KSA9PiBUNyxcbiAgZm43OiAoeDogVDcpID0+IFQ4LFxuICBmbjg6ICh4OiBUOCkgPT4gVDlcbik6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDk7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFYyLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNywgVDgsIFQ5PihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjY6ICh4OiBUNikgPT4gVDcsXG4gIGZuNzogKHg6IFQ3KSA9PiBUOCxcbiAgZm44OiAoeDogVDgpID0+IFQ5XG4pOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDk7XG5cbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDYsIFQ3LCBUOCwgVDksIFQxMD4oXG4gIGZuMDogKCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjY6ICh4OiBUNikgPT4gVDcsXG4gIGZuNzogKHg6IFQ3KSA9PiBUOCxcbiAgZm44OiAoeDogVDgpID0+IFQ5LFxuICBmbjk6ICh4OiBUOSkgPT4gVDEwXG4pOiAoKSA9PiBUMTA7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDcsIFQ4LCBUOSwgVDEwPihcbiAgZm4wOiAoeDA6IFYwKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDYsXG4gIGZuNjogKHg6IFQ2KSA9PiBUNyxcbiAgZm43OiAoeDogVDcpID0+IFQ4LFxuICBmbjg6ICh4OiBUOCkgPT4gVDksXG4gIGZuOTogKHg6IFQ5KSA9PiBUMTBcbik6ICh4MDogVjApID0+IFQxMDtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDcsIFQ4LCBUOSwgVDEwPihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3LFxuICBmbjc6ICh4OiBUNykgPT4gVDgsXG4gIGZuODogKHg6IFQ4KSA9PiBUOSxcbiAgZm45OiAoeDogVDkpID0+IFQxMFxuKTogKHgwOiBWMCwgeDE6IFYxKSA9PiBUMTA7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFYyLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNywgVDgsIFQ5LCBUMTA+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDYsXG4gIGZuNjogKHg6IFQ2KSA9PiBUNyxcbiAgZm43OiAoeDogVDcpID0+IFQ4LFxuICBmbjg6ICh4OiBUOCkgPT4gVDksXG4gIGZuOTogKHg6IFQ5KSA9PiBUMTBcbik6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMTA7XG5leHBvcnQgZnVuY3Rpb24gcGlwZSguLi5baCwgLi4uZm5zXTogW0YsIC4uLkZbXV0pIHtcbiAgcmV0dXJuICguLi5hcmdzOiB1bmtub3duW10pOiB1bmtub3duID0+XG4gICAgZm5zLnJlZHVjZSgodiwgZm4pID0+IGZuKHYpLCBoKC4uLmFyZ3MpKTtcbn1cbnR5cGUgRiA9ICguLi5hcmdzOiB1bmtub3duW10pID0+IHVua25vd247XG4iLCAiaW1wb3J0IHsgTnVsbGlzaCB9IGZyb20gXCIuL3R5cGVzXCI7XG5cbmV4cG9ydCBjb25zdCBpc051bGxpc2ggPSAodjogdW5rbm93bik6IHYgaXMgTnVsbGlzaCA9PlxuICB2ID09PSB1bmRlZmluZWQgfHwgdiA9PT0gbnVsbCB8fCAodHlwZW9mIHYgPT09IFwibnVtYmVyXCIgJiYgTnVtYmVyLmlzTmFOKHYpKTtcbiIsICJpbXBvcnQgeyBpc051bGxpc2ggfSBmcm9tIFwiLi9pc051bGxpc2hcIjtcbmltcG9ydCB7IE51bGxpc2ggfSBmcm9tIFwiLi90eXBlc1wiO1xuXG5leHBvcnQgZnVuY3Rpb24gb25OdWxsaXNoPFQ+KG9yRWxzZTogVCwgdjogVCB8IE51bGxpc2gpOiBUO1xuZXhwb3J0IGZ1bmN0aW9uIG9uTnVsbGlzaDxUPihvckVsc2U6IFQpOiAodjogVCB8IE51bGxpc2gpID0+IFQ7XG5leHBvcnQgZnVuY3Rpb24gb25OdWxsaXNoPFQ+KFxuICAuLi5hcmdzOiBbVF0gfCBbVCwgVCB8IE51bGxpc2hdXG4pOiBUIHwgKCh2OiBUIHwgTnVsbGlzaCkgPT4gVCkge1xuICByZXR1cm4gYXJncy5sZW5ndGggPT09IDFcbiAgICA/ICh2OiBUIHwgTnVsbGlzaCk6IFQgPT4gKGlzTnVsbGlzaCh2KSA/IGFyZ3NbMF0gOiB2KVxuICAgIDogaXNOdWxsaXNoKGFyZ3NbMV0pXG4gICAgPyBhcmdzWzBdXG4gICAgOiBhcmdzWzFdO1xufVxuIiwgImltcG9ydCB7IEVsZW1lbnRNb2RlbCB9IGZyb20gXCIuLi8uLi8uLi8uLi90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBnZXRHbG9iYWxCdXR0b25Nb2RlbCB9IGZyb20gXCIuLi8uLi8uLi8uLi91dGlscy9nZXRHbG9iYWxCdXR0b25Nb2RlbFwiO1xuaW1wb3J0IHtcbiAgZ2V0SHJlZixcbiAgZ2V0VGFyZ2V0LFxuICBpY29uU2VsZWN0b3IsXG4gIG5vcm1hbGl6ZU9wYWNpdHlcbn0gZnJvbSBcIi4uLy4uLy4uL3V0aWxzL2NvbW1vblwiO1xuaW1wb3J0IHsgZ2V0TW9kZWwgYXMgZ2V0SWNvbk1vZGVsIH0gZnJvbSBcIi4uLy4uL0ljb24vdXRpbHMvZ2V0TW9kZWxcIjtcbmltcG9ydCB7IG1QaXBlIH0gZnJvbSBcImZwLXV0aWxpdGllc1wiO1xuaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCJ1dGlsc1wiO1xuaW1wb3J0IHsgQ29sb3IsIHBhcnNlQ29sb3JTdHJpbmcgfSBmcm9tIFwidXRpbHMvc3JjL2NvbG9yL3BhcnNlQ29sb3JTdHJpbmdcIjtcbmltcG9ydCB7IGdldE5vZGVTdHlsZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldE5vZGVTdHlsZVwiO1xuaW1wb3J0IHsgcGlwZSB9IGZyb20gXCJ1dGlscy9zcmMvZnAvcGlwZVwiO1xuaW1wb3J0IHsgb25OdWxsaXNoIH0gZnJvbSBcInV0aWxzL3NyYy9vbk51bGxpc2hcIjtcbmltcG9ydCAqIGFzIE51bSBmcm9tIFwidXRpbHMvc3JjL3JlYWRlci9udW1iZXJcIjtcbmltcG9ydCAqIGFzIE9iaiBmcm9tIFwidXRpbHMvc3JjL3JlYWRlci9vYmplY3RcIjtcbmltcG9ydCAqIGFzIFN0ciBmcm9tIFwidXRpbHMvc3JjL3JlYWRlci9zdHJpbmdcIjtcbmltcG9ydCB7IHV1aWQgfSBmcm9tIFwidXRpbHMvc3JjL3V1aWRcIjtcblxuY29uc3QgZ2V0Q29sb3IgPSBtUGlwZShPYmoucmVhZEtleShcImNvbG9yXCIpLCBTdHIucmVhZCwgcGFyc2VDb2xvclN0cmluZyk7XG5jb25zdCBnZXRCZ0NvbG9yID0gbVBpcGUoXG4gIE9iai5yZWFkS2V5KFwiYmFja2dyb3VuZC1jb2xvclwiKSxcbiAgU3RyLnJlYWQsXG4gIHBhcnNlQ29sb3JTdHJpbmdcbik7XG5cbmNvbnN0IGdldEJvcmRlcldpZHRoID0gbVBpcGUoT2JqLnJlYWRLZXkoXCJib3JkZXItd2lkdGhcIiksIE51bS5yZWFkKTtcbmNvbnN0IGdldFRyYW5zZm9ybSA9IG1QaXBlKE9iai5yZWFkS2V5KFwidGV4dC10cmFuc2Zvcm1cIiksIFN0ci5yZWFkKTtcbmNvbnN0IGdldFRleHQgPSBwaXBlKE9iai5yZWFkS2V5KFwidGV4dFwiKSwgU3RyLnJlYWQsIG9uTnVsbGlzaChcIkJVVFRPTlwiKSk7XG5cbmNvbnN0IGdldEJnQ29sb3JPcGFjaXR5ID0gKGNvbG9yOiBDb2xvciwgb3BhY2l0eTogbnVtYmVyKTogbnVtYmVyID0+IHtcbiAgaWYgKGNvbG9yLm9wYWNpdHkgJiYgK2NvbG9yLm9wYWNpdHkgPT09IDApIHtcbiAgICByZXR1cm4gMDtcbiAgfVxuXG4gIHJldHVybiArKGlzTmFOKG9wYWNpdHkpID8gY29sb3Iub3BhY2l0eSA/PyAxIDogb3BhY2l0eSk7XG59O1xuXG5leHBvcnQgY29uc3QgZ2V0U3R5bGVNb2RlbCA9IChub2RlOiBFbGVtZW50KTogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4gPT4ge1xuICBjb25zdCBzdHlsZSA9IGdldE5vZGVTdHlsZShub2RlKTtcbiAgY29uc3QgY29sb3IgPSBnZXRDb2xvcihzdHlsZSk7XG4gIGNvbnN0IGJnQ29sb3IgPSBnZXRCZ0NvbG9yKHN0eWxlKTtcbiAgY29uc3Qgb3BhY2l0eSA9ICtzdHlsZS5vcGFjaXR5O1xuICBjb25zdCBib3JkZXJXaWR0aCA9IGdldEJvcmRlcldpZHRoKHN0eWxlKTtcblxuICByZXR1cm4ge1xuICAgIC4uLihjb2xvciAmJiB7XG4gICAgICBjb2xvckhleDogbm9ybWFsaXplT3BhY2l0eSh7XG4gICAgICAgIGhleDogY29sb3IuaGV4LFxuICAgICAgICBvcGFjaXR5OiBjb2xvci5vcGFjaXR5ID8/IFN0cmluZyhvcGFjaXR5KVxuICAgICAgfSkuaGV4LFxuICAgICAgY29sb3JPcGFjaXR5OiBub3JtYWxpemVPcGFjaXR5KHtcbiAgICAgICAgaGV4OiBjb2xvci5oZXgsXG4gICAgICAgIG9wYWNpdHk6IGNvbG9yLm9wYWNpdHkgPz8gU3RyaW5nKG9wYWNpdHkpXG4gICAgICB9KS5vcGFjaXR5LFxuICAgICAgY29sb3JQYWxldHRlOiBcIlwiXG4gICAgfSksXG4gICAgLi4uKGJnQ29sb3IgJiYge1xuICAgICAgYmdDb2xvckhleDogYmdDb2xvci5oZXgsXG4gICAgICBiZ0NvbG9yT3BhY2l0eTogZ2V0QmdDb2xvck9wYWNpdHkoYmdDb2xvciwgb3BhY2l0eSksXG4gICAgICBiZ0NvbG9yUGFsZXR0ZTogXCJcIixcbiAgICAgIC4uLihnZXRCZ0NvbG9yT3BhY2l0eShiZ0NvbG9yLCBvcGFjaXR5KSA9PT0gMFxuICAgICAgICA/IHsgYmdDb2xvclR5cGU6IFwibm9uZVwiLCBob3ZlckJnQ29sb3JUeXBlOiBcIm5vbmVcIiB9XG4gICAgICAgIDogeyBiZ0NvbG9yVHlwZTogXCJzb2xpZFwiLCBob3ZlckJnQ29sb3JUeXBlOiBcInNvbGlkXCIgfSksXG4gICAgICBob3ZlckJnQ29sb3JIZXg6IGJnQ29sb3IuaGV4LFxuICAgICAgaG92ZXJCZ0NvbG9yT3BhY2l0eTogMC44LFxuICAgICAgaG92ZXJCZ0NvbG9yUGFsZXR0ZTogXCJcIlxuICAgIH0pLFxuICAgIC4uLihib3JkZXJXaWR0aCA9PT0gdW5kZWZpbmVkICYmIHsgYm9yZGVyU3R5bGU6IFwibm9uZVwiIH0pXG4gIH07XG59O1xuXG5leHBvcnQgY29uc3QgZ2V0TW9kZWwgPSAoXG4gIG5vZGU6IEVsZW1lbnQsXG4gIHVybE1hcDogUmVjb3JkPHN0cmluZywgc3RyaW5nPlxuKTogRWxlbWVudE1vZGVsID0+IHtcbiAgbGV0IGljb25Nb2RlbDogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4gPSB7fTtcbiAgY29uc3QgaXNMaW5rID0gbm9kZS50YWdOYW1lID09PSBcIkFcIjtcblxuICBjb25zdCBtb2RlbFN0eWxlID0gZ2V0U3R5bGVNb2RlbChub2RlKTtcbiAgY29uc3QgZ2xvYmFsTW9kZWwgPSBnZXRHbG9iYWxCdXR0b25Nb2RlbCgpO1xuICBjb25zdCB0ZXh0VHJhbnNmb3JtID0gZ2V0VHJhbnNmb3JtKGdldE5vZGVTdHlsZShub2RlKSk7XG4gIGNvbnN0IGljb24gPSBub2RlLnF1ZXJ5U2VsZWN0b3IoaWNvblNlbGVjdG9yKTtcblxuICBpZiAoaWNvbikge1xuICAgIGNvbnN0IG1vZGVsID0gZ2V0SWNvbk1vZGVsKGljb24sIHVybE1hcCk7XG4gICAgY29uc3QgbmFtZSA9IFN0ci5yZWFkKG1vZGVsLnZhbHVlLm5hbWUpO1xuXG4gICAgLy8gUmVtb3ZlIHRoZSBodG1sIGZvciBJY29uXG4gICAgLy8gaGF2ZSBjb25mbGljdHMgd2l0aCB0ZXh0IG9mIGJ1dHRvblxuICAgIGljb24ucmVtb3ZlKCk7XG5cbiAgICBpZiAobmFtZSkge1xuICAgICAgaWNvbk1vZGVsID0ge1xuICAgICAgICBpY29uTmFtZTogbmFtZVxuICAgICAgfTtcbiAgICB9XG4gIH1cblxuICBsZXQgdGV4dCA9IGdldFRleHQobm9kZSk7XG5cbiAgY29uc3QgbGluayA9IGdldFRhcmdldChub2RlKTtcbiAgY29uc3QgdGFyZ2V0VHlwZSA9IGxpbmsgPT09IFwiX3NlbGZcIiA/IFwib2ZmXCIgOiBcIm9uXCI7XG5cbiAgc3dpdGNoICh0ZXh0VHJhbnNmb3JtKSB7XG4gICAgY2FzZSBcInVwcGVyY2FzZVwiOiB7XG4gICAgICB0ZXh0ID0gdGV4dC50b1VwcGVyQ2FzZSgpO1xuICAgICAgYnJlYWs7XG4gICAgfVxuICAgIGNhc2UgXCJsb3dlcmNhc2VcIjoge1xuICAgICAgdGV4dCA9IHRleHQudG9VcHBlckNhc2UoKTtcbiAgICAgIGJyZWFrO1xuICAgIH1cbiAgfVxuXG4gIGNvbnN0IGhyZWYgPSBnZXRIcmVmKG5vZGUpO1xuICBjb25zdCBtYXBwZWRIcmVmID0gaHJlZiAmJiB1cmxNYXBbaHJlZl0gIT09IHVuZGVmaW5lZCA/IHVybE1hcFtocmVmXSA6IGhyZWY7XG5cbiAgcmV0dXJuIHtcbiAgICB0eXBlOiBcIkJ1dHRvblwiLFxuICAgIHZhbHVlOiB7XG4gICAgICBfaWQ6IHV1aWQoKSxcbiAgICAgIF9zdHlsZXM6IFtcImJ1dHRvblwiXSxcbiAgICAgIHRleHQsXG4gICAgICAuLi5nbG9iYWxNb2RlbCxcbiAgICAgIC4uLm1vZGVsU3R5bGUsXG4gICAgICAuLi5pY29uTW9kZWwsXG4gICAgICAuLi4oaXNMaW5rICYmIHtcbiAgICAgICAgbGlua0V4dGVybmFsOiBtYXBwZWRIcmVmLFxuICAgICAgICBsaW5rVHlwZTogXCJleHRlcm5hbFwiLFxuICAgICAgICBsaW5rRXh0ZXJuYWxCbGFuazogdGFyZ2V0VHlwZVxuICAgICAgfSlcbiAgICB9XG4gIH07XG59O1xuIiwgImltcG9ydCB7IE1WYWx1ZSB9IGZyb20gXCIuLi90eXBlc1wiO1xuXG5leHBvcnQgZnVuY3Rpb24gZmluZE5lYXJlc3RCbG9ja1BhcmVudChlbGVtZW50OiBFbGVtZW50KTogTVZhbHVlPEVsZW1lbnQ+IHtcbiAgaWYgKCFlbGVtZW50LnBhcmVudEVsZW1lbnQpIHtcbiAgICByZXR1cm4gdW5kZWZpbmVkO1xuICB9XG5cbiAgY29uc3QgZGlzcGxheVN0eWxlID0gd2luZG93LmdldENvbXB1dGVkU3R5bGUoZWxlbWVudC5wYXJlbnRFbGVtZW50KS5kaXNwbGF5O1xuICBjb25zdCBpc0Jsb2NrRWxlbWVudCA9XG4gICAgZGlzcGxheVN0eWxlID09PSBcImJsb2NrXCIgfHxcbiAgICBkaXNwbGF5U3R5bGUgPT09IFwiZmxleFwiIHx8XG4gICAgZGlzcGxheVN0eWxlID09PSBcImdyaWRcIjtcblxuICBpZiAoaXNCbG9ja0VsZW1lbnQpIHtcbiAgICByZXR1cm4gZWxlbWVudC5wYXJlbnRFbGVtZW50O1xuICB9IGVsc2Uge1xuICAgIHJldHVybiBmaW5kTmVhcmVzdEJsb2NrUGFyZW50KGVsZW1lbnQucGFyZW50RWxlbWVudCk7XG4gIH1cbn1cbiIsICJpbXBvcnQgeyBjcmVhdGVDbG9uZWFibGVNb2RlbCB9IGZyb20gXCIuLi8uLi8uLi9Nb2RlbHMvQ2xvbmVhYmxlXCI7XG5pbXBvcnQgeyBFbGVtZW50TW9kZWwgfSBmcm9tIFwiLi4vLi4vLi4vdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgYnV0dG9uU2VsZWN0b3IsIHRleHRBbGlnbiB9IGZyb20gXCIuLi8uLi91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IGdldE1vZGVsIH0gZnJvbSBcIi4vdXRpbHMvZ2V0TW9kZWxcIjtcbmltcG9ydCB7IGZpbmROZWFyZXN0QmxvY2tQYXJlbnQgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9maW5kTmVhcmVzdEJsb2NrUGFyZW50XCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIGdldEJ1dHRvbk1vZGVsKFxuICBub2RlOiBFbGVtZW50LFxuICB1cmxNYXA6IFJlY29yZDxzdHJpbmcsIHN0cmluZz5cbik6IEFycmF5PEVsZW1lbnRNb2RlbD4ge1xuICBjb25zdCBidXR0b25zID0gbm9kZS5xdWVyeVNlbGVjdG9yQWxsKGJ1dHRvblNlbGVjdG9yKTtcbiAgY29uc3QgZ3JvdXBzID0gbmV3IE1hcCgpO1xuXG4gIGJ1dHRvbnMuZm9yRWFjaCgoYnV0dG9uKSA9PiB7XG4gICAgY29uc3QgcGFyZW50RWxlbWVudCA9IGZpbmROZWFyZXN0QmxvY2tQYXJlbnQoYnV0dG9uKTtcbiAgICBjb25zdCBzdHlsZSA9IGdldE5vZGVTdHlsZShidXR0b24pO1xuICAgIGNvbnN0IG1vZGVsID0gZ2V0TW9kZWwoYnV0dG9uLCB1cmxNYXApO1xuICAgIGNvbnN0IGdyb3VwID0gZ3JvdXBzLmdldChwYXJlbnRFbGVtZW50KSA/PyB7IHZhbHVlOiB7IGl0ZW1zOiBbXSB9IH07XG5cbiAgICBjb25zdCB3cmFwcGVyTW9kZWwgPSBjcmVhdGVDbG9uZWFibGVNb2RlbCh7XG4gICAgICBfc3R5bGVzOiBbXCJ3cmFwcGVyLWNsb25lXCIsIFwid3JhcHBlci1jbG9uZS0tYnV0dG9uXCJdLFxuICAgICAgaXRlbXM6IFsuLi5ncm91cC52YWx1ZS5pdGVtcywgbW9kZWxdLFxuICAgICAgaG9yaXpvbnRhbEFsaWduOiB0ZXh0QWxpZ25bc3R5bGVbXCJ0ZXh0LWFsaWduXCJdXVxuICAgIH0pO1xuXG4gICAgZ3JvdXBzLnNldChwYXJlbnRFbGVtZW50LCB3cmFwcGVyTW9kZWwpO1xuICB9KTtcblxuICBjb25zdCBtb2RlbHM6IEFycmF5PEVsZW1lbnRNb2RlbD4gPSBbXTtcblxuICBncm91cHMuZm9yRWFjaCgobW9kZWwpID0+IHtcbiAgICBtb2RlbHMucHVzaChtb2RlbCk7XG4gIH0pO1xuXG4gIHJldHVybiBtb2RlbHM7XG59XG4iLCAiaW1wb3J0IHsgRW1iZWRNb2RlbCB9IGZyb20gXCIuLi8uLi8uLi90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBlbWJlZFNlbGVjdG9yIH0gZnJvbSBcIi4uLy4uL3V0aWxzL2NvbW1vblwiO1xuXG5leHBvcnQgZnVuY3Rpb24gZ2V0RW1iZWRNb2RlbChub2RlOiBFbGVtZW50KTogQXJyYXk8RW1iZWRNb2RlbD4ge1xuICBjb25zdCBlbWJlZHMgPSBub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoZW1iZWRTZWxlY3Rvcik7XG4gIGNvbnN0IG1vZGVsczogQXJyYXk8RW1iZWRNb2RlbD4gPSBbXTtcblxuICBlbWJlZHMuZm9yRWFjaCgoKSA9PiB7XG4gICAgbW9kZWxzLnB1c2goeyB0eXBlOiBcIkVtYmVkQ29kZVwiIH0pO1xuICB9KTtcblxuICByZXR1cm4gbW9kZWxzO1xufVxuIiwgImltcG9ydCB7IGNyZWF0ZUNsb25lYWJsZU1vZGVsIH0gZnJvbSBcIi4uLy4uLy4uL01vZGVscy9DbG9uZWFibGVcIjtcbmltcG9ydCB7IEVsZW1lbnRNb2RlbCB9IGZyb20gXCIuLi8uLi8uLi90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBpY29uU2VsZWN0b3IsIHRleHRBbGlnbiB9IGZyb20gXCIuLi8uLi91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IGdldE1vZGVsIH0gZnJvbSBcIi4vdXRpbHMvZ2V0TW9kZWxcIjtcbmltcG9ydCB7IGZpbmROZWFyZXN0QmxvY2tQYXJlbnQgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9maW5kTmVhcmVzdEJsb2NrUGFyZW50XCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcbmltcG9ydCB7IGdldFBhcmVudEVsZW1lbnRPZlRleHROb2RlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0UGFyZW50RWxlbWVudE9mVGV4dE5vZGVcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIGdldEljb25Nb2RlbChcbiAgbm9kZTogRWxlbWVudCxcbiAgdXJsTWFwOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+XG4pOiBBcnJheTxFbGVtZW50TW9kZWw+IHtcbiAgY29uc3QgaWNvbnMgPSBub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoaWNvblNlbGVjdG9yKTtcbiAgY29uc3QgZ3JvdXBzID0gbmV3IE1hcCgpO1xuXG4gIGljb25zLmZvckVhY2goKGljb24pID0+IHtcbiAgICBjb25zdCBwYXJlbnRFbGVtZW50ID0gZmluZE5lYXJlc3RCbG9ja1BhcmVudChpY29uKTtcbiAgICBjb25zdCBwYXJlbnROb2RlID0gZ2V0UGFyZW50RWxlbWVudE9mVGV4dE5vZGUobm9kZSk7XG4gICAgY29uc3QgaXNJY29uVGV4dCA9IHBhcmVudE5vZGU/Lm5vZGVOYW1lID09PSBcIiN0ZXh0XCI7XG4gICAgY29uc3QgaWNvbk5vZGUgPSBpc0ljb25UZXh0ID8gbm9kZSA6IHBhcmVudE5vZGU7XG4gICAgY29uc3Qgc3R5bGUgPSBpY29uTm9kZSA/IGdldE5vZGVTdHlsZShpY29uTm9kZSkgOiB7fTtcbiAgICBjb25zdCBtb2RlbCA9IGdldE1vZGVsKGljb24sIHVybE1hcCk7XG4gICAgY29uc3QgZ3JvdXAgPSBncm91cHMuZ2V0KHBhcmVudEVsZW1lbnQpID8/IHsgdmFsdWU6IHsgaXRlbXM6IFtdIH0gfTtcblxuICAgIGNvbnN0IHdyYXBwZXJNb2RlbCA9IGNyZWF0ZUNsb25lYWJsZU1vZGVsKHtcbiAgICAgIF9zdHlsZXM6IFtcIndyYXBwZXItY2xvbmVcIiwgXCJ3cmFwcGVyLWNsb25lLS1pY29uXCJdLFxuICAgICAgaXRlbXM6IFsuLi5ncm91cC52YWx1ZS5pdGVtcywgbW9kZWxdLFxuICAgICAgaG9yaXpvbnRhbEFsaWduOiB0ZXh0QWxpZ25bc3R5bGVbXCJ0ZXh0LWFsaWduXCJdXVxuICAgIH0pO1xuXG4gICAgZ3JvdXBzLnNldChwYXJlbnRFbGVtZW50LCB3cmFwcGVyTW9kZWwpO1xuICB9KTtcblxuICBjb25zdCBtb2RlbHM6IEFycmF5PEVsZW1lbnRNb2RlbD4gPSBbXTtcblxuICBncm91cHMuZm9yRWFjaCgobW9kZWwpID0+IHtcbiAgICBtb2RlbHMucHVzaChtb2RlbCk7XG4gIH0pO1xuXG4gIHJldHVybiBtb2RlbHM7XG59XG4iLCAiaW1wb3J0IHsgRWxlbWVudE1vZGVsIH0gZnJvbSBcIi4uLy4uL3R5cGVzL3R5cGVcIjtcbmltcG9ydCB7IHV1aWQgfSBmcm9tIFwidXRpbHMvc3JjL3V1aWRcIjtcblxuaW50ZXJmYWNlIERhdGEge1xuICBfc3R5bGVzOiBBcnJheTxzdHJpbmc+O1xuICBpdGVtczogQXJyYXk8RWxlbWVudE1vZGVsPjtcbiAgW2s6IHN0cmluZ106IHN0cmluZyB8IEFycmF5PHN0cmluZyB8IEVsZW1lbnRNb2RlbD47XG59XG5cbmV4cG9ydCBjb25zdCBjcmVhdGVXcmFwcGVyTW9kZWwgPSAoZGF0YTogRGF0YSk6IEVsZW1lbnRNb2RlbCA9PiB7XG4gIGNvbnN0IHsgX3N0eWxlcywgaXRlbXMsIC4uLnZhbHVlIH0gPSBkYXRhO1xuICByZXR1cm4ge1xuICAgIHR5cGU6IFwiV3JhcHBlclwiLFxuICAgIHZhbHVlOiB7IF9pZDogdXVpZCgpLCBfc3R5bGVzLCBpdGVtcywgLi4udmFsdWUgfVxuICB9O1xufTtcbiIsICJjb25zdCBsaXN0TWFyZ2lucyA9IChub2RlOiBFbGVtZW50KTogdm9pZCA9PiB7XG4gIGNvbnN0IGFsbG93ZWRUYWdzID0gW1wiVUxcIiwgXCJPTFwiXTtcbiAgaWYgKGFsbG93ZWRUYWdzLmluY2x1ZGVzKG5vZGUubm9kZU5hbWUpKSB7XG4gICAgY29uc3QgeyBtYXJnaW5Ub3AsIG1hcmdpbkJvdHRvbSB9ID0gd2luZG93LmdldENvbXB1dGVkU3R5bGUobm9kZSk7XG5cbiAgICBpZiAoIWlzTmFOKHBhcnNlRmxvYXQobWFyZ2luVG9wKSkpIHtcbiAgICAgIGNvbnN0IHBhcnNlZE1hcmdpblRvcCA9IE1hdGgucm91bmQocGFyc2VGbG9hdChtYXJnaW5Ub3ApKTtcbiAgICAgIG5vZGUuZmlyc3RFbGVtZW50Q2hpbGQ/LmNsYXNzTGlzdC5hZGQoYGJyei1tdC1sZy0ke3BhcnNlZE1hcmdpblRvcH1gKTtcbiAgICB9XG4gICAgaWYgKCFpc05hTihwYXJzZUZsb2F0KG1hcmdpbkJvdHRvbSkpKSB7XG4gICAgICBjb25zdCBwYXJzZWRNYXJnaW5Cb3R0b20gPSBNYXRoLnJvdW5kKHBhcnNlRmxvYXQobWFyZ2luQm90dG9tKSk7XG4gICAgICBub2RlLmxhc3RFbGVtZW50Q2hpbGQ/LmNsYXNzTGlzdC5hZGQoYGJyei1tYi1sZy0ke3BhcnNlZE1hcmdpbkJvdHRvbX1gKTtcbiAgICB9XG4gIH0gZWxzZSBpZiAobm9kZS5ub2RlVHlwZSA9PT0gTm9kZS5FTEVNRU5UX05PREUpIHtcbiAgICBjb25zdCBjaGlsZHJlbiA9IEFycmF5LmZyb20obm9kZS5jaGlsZHJlbik7XG5cbiAgICBmb3IgKG5vZGUgb2YgY2hpbGRyZW4pIHtcbiAgICAgIGlmIChub2RlLnRleHRDb250ZW50Py50cmltKCkpIHtcbiAgICAgICAgbGlzdE1hcmdpbnMobm9kZSk7XG4gICAgICB9XG4gICAgfVxuICB9XG4gIHJldHVybjtcbn07XG5cbmV4cG9ydCBjb25zdCBhZGRNYXJnaW5zVG9MaXN0cyA9IChub2RlOiBFbGVtZW50KSA9PiB7XG4gIGNvbnN0IGNoaWxkcmVuID0gQXJyYXkuZnJvbShub2RlLmNoaWxkcmVuKTtcbiAgY2hpbGRyZW4uZm9yRWFjaCgoY2hpbGQpID0+IHtcbiAgICBsaXN0TWFyZ2lucyhjaGlsZCBhcyBFbGVtZW50KTtcbiAgfSk7XG4gIHJldHVybiBub2RlO1xufTtcbiIsICJleHBvcnQgY29uc3QgY2xlYW5DbGFzc05hbWVzID0gKG5vZGU6IEVsZW1lbnQpOiB2b2lkID0+IHtcbiAgY29uc3QgY2xhc3NMaXN0RXhjZXB0cyA9IFtcImJyei1cIl07XG4gIGNvbnN0IGVsZW1lbnRzV2l0aENsYXNzZXMgPSBub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoXCJbY2xhc3NdXCIpO1xuICBlbGVtZW50c1dpdGhDbGFzc2VzLmZvckVhY2goZnVuY3Rpb24gKGVsZW1lbnQpIHtcbiAgICBlbGVtZW50LmNsYXNzTGlzdC5mb3JFYWNoKChjbHMpID0+IHtcbiAgICAgIGlmICghY2xhc3NMaXN0RXhjZXB0cy5zb21lKChleGNlcHQpID0+IGNscy5zdGFydHNXaXRoKGV4Y2VwdCkpKSB7XG4gICAgICAgIGlmIChjbHMgPT09IFwiZmluYWxkcmFmdF9wbGFjZWhvbGRlclwiKSB7XG4gICAgICAgICAgZWxlbWVudC5pbm5lckhUTUwgPSBcIlwiO1xuICAgICAgICB9XG4gICAgICAgIGVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShjbHMpO1xuICAgICAgfVxuICAgIH0pO1xuXG4gICAgaWYgKGVsZW1lbnQuY2xhc3NMaXN0Lmxlbmd0aCA9PT0gMCkge1xuICAgICAgZWxlbWVudC5yZW1vdmVBdHRyaWJ1dGUoXCJjbGFzc1wiKTtcbiAgICB9XG4gIH0pO1xufTtcbiIsICJpbXBvcnQgeyBhbGxvd2VkVGFncyB9IGZyb20gXCIuLi9jb21tb25cIjtcbmltcG9ydCB7IGNsZWFuQ2xhc3NOYW1lcyB9IGZyb20gXCIuL2NsZWFuQ2xhc3NOYW1lc1wiO1xuXG5leHBvcnQgZnVuY3Rpb24gcmVtb3ZlU3R5bGVzRXhjZXB0Rm9udFdlaWdodEFuZENvbG9yKFxuICBodG1sU3RyaW5nOiBzdHJpbmdcbik6IHN0cmluZyB7XG4gIC8vIENyZWF0ZSBhIHRlbXBvcmFyeSBlbGVtZW50XG4gIGNvbnN0IHRlbXBFbGVtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcblxuICAvLyBTZXQgdGhlIEhUTUwgY29udGVudCBvZiB0aGUgdGVtcG9yYXJ5IGVsZW1lbnRcbiAgdGVtcEVsZW1lbnQuaW5uZXJIVE1MID0gaHRtbFN0cmluZztcblxuICAvLyBGaW5kIGVsZW1lbnRzIHdpdGggaW5saW5lIHN0eWxlc1xuICBjb25zdCBlbGVtZW50c1dpdGhTdHlsZXMgPSB0ZW1wRWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiW3N0eWxlXVwiKTtcblxuICAvLyBJdGVyYXRlIHRocm91Z2ggZWxlbWVudHMgd2l0aCBzdHlsZXNcbiAgZWxlbWVudHNXaXRoU3R5bGVzLmZvckVhY2goZnVuY3Rpb24gKGVsZW1lbnQpIHtcbiAgICAvLyBHZXQgdGhlIGlubGluZSBzdHlsZSBhdHRyaWJ1dGVcbiAgICBjb25zdCBzdHlsZUF0dHJpYnV0ZSA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKFwic3R5bGVcIikgPz8gXCJcIjtcblxuICAgIC8vIFNwbGl0IHRoZSBpbmxpbmUgc3R5bGUgaW50byBpbmRpdmlkdWFsIHByb3BlcnRpZXNcbiAgICBjb25zdCBzdHlsZVByb3BlcnRpZXMgPSBzdHlsZUF0dHJpYnV0ZS5zcGxpdChcIjtcIik7XG5cbiAgICAvLyBJbml0aWFsaXplIGEgbmV3IHN0eWxlIHN0cmluZyB0byByZXRhaW4gb25seSBmb250LXdlaWdodCBhbmQgY29sb3JcbiAgICBsZXQgbmV3U3R5bGUgPSBcIlwiO1xuXG4gICAgLy8gSXRlcmF0ZSB0aHJvdWdoIHRoZSBzdHlsZSBwcm9wZXJ0aWVzXG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCBzdHlsZVByb3BlcnRpZXMubGVuZ3RoOyBpKyspIHtcbiAgICAgIGNvbnN0IHByb3BlcnR5ID0gc3R5bGVQcm9wZXJ0aWVzW2ldLnRyaW0oKTtcblxuICAgICAgLy8gQ2hlY2sgaWYgdGhlIHByb3BlcnR5IGlzIGZvbnQtd2VpZ2h0IG9yIGNvbG9yXG4gICAgICBjb25zdCB2YWxpZFN0eWxlcyA9IFtcImZvbnQtd2VpZ2h0XCIsIFwiY29sb3JcIiwgXCJiYWNrZ3JvdW5kLWNvbG9yXCJdO1xuICAgICAgY29uc3QgaGFzUHJvcGVydHkgPSB2YWxpZFN0eWxlcy5zb21lKChzdHlsZSkgPT5cbiAgICAgICAgcHJvcGVydHkuc3RhcnRzV2l0aChzdHlsZSlcbiAgICAgICk7XG5cbiAgICAgIGlmIChoYXNQcm9wZXJ0eSkge1xuICAgICAgICBuZXdTdHlsZSArPSBwcm9wZXJ0eSArIFwiOyBcIjtcbiAgICAgIH1cbiAgICB9XG5cbiAgICAvLyBTZXQgdGhlIGVsZW1lbnQncyBzdHlsZSBhdHRyaWJ1dGUgdG8gcmV0YWluIG9ubHkgZm9udC13ZWlnaHQgYW5kIGNvbG9yXG4gICAgZWxlbWVudC5zZXRBdHRyaWJ1dGUoXCJzdHlsZVwiLCBuZXdTdHlsZSk7XG4gIH0pO1xuXG4gIGNsZWFuQ2xhc3NOYW1lcyh0ZW1wRWxlbWVudCk7XG4gIC8vIFJldHVybiB0aGUgY2xlYW5lZCBIVE1MXG4gIHJldHVybiB0ZW1wRWxlbWVudC5pbm5lckhUTUw7XG59XG5cbmV4cG9ydCBmdW5jdGlvbiByZW1vdmVBbGxTdHlsZXNGcm9tSFRNTChub2RlOiBFbGVtZW50KSB7XG4gIC8vIERlZmluZSB0aGUgbGlzdCBvZiBhbGxvd2VkIHRhZ3NcbiAgY29uc3QgdGFnc1RvUmVtb3ZlU3R5bGVzID0gYWxsb3dlZFRhZ3MuZmlsdGVyKChpdGVtKSA9PiBpdGVtICE9PSBcIkxJXCIpO1xuXG4gIC8vIEZpbmQgZWxlbWVudHMgd2l0aCBpbmxpbmUgc3R5bGVzIG9ubHkgZm9yIGFsbG93ZWQgdGFnc1xuICBjb25zdCBlbGVtZW50c1dpdGhTdHlsZXMgPSBub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoXG4gICAgdGFnc1RvUmVtb3ZlU3R5bGVzLmpvaW4oXCIsXCIpICsgXCJbc3R5bGVdXCJcbiAgKTtcblxuICAvLyBSZW1vdmUgdGhlIFwic3R5bGVcIiBhdHRyaWJ1dGUgZnJvbSBlYWNoIGVsZW1lbnRcbiAgZWxlbWVudHNXaXRoU3R5bGVzLmZvckVhY2goZnVuY3Rpb24gKGVsZW1lbnQpIHtcbiAgICBlbGVtZW50LnJlbW92ZUF0dHJpYnV0ZShcInN0eWxlXCIpO1xuICB9KTtcblxuICAvLyBSZW1vdmUgdGhlIFwic3R5bGVcIiBhdHRyaWJ1dGUgZnJvbSBlYWNoIGVsZW1lbnRcbiAgY2xlYW5DbGFzc05hbWVzKG5vZGUpO1xuXG4gIG5vZGUuaW5uZXJIVE1MID0gcmVtb3ZlU3R5bGVzRXhjZXB0Rm9udFdlaWdodEFuZENvbG9yKG5vZGUuaW5uZXJIVE1MKTtcblxuICAvLyBSZXR1cm4gdGhlIGNsZWFuZWQgSFRNTFxuICByZXR1cm4gbm9kZTtcbn1cbiIsICJleHBvcnQgZnVuY3Rpb24gcmVtb3ZlRW1wdHlOb2Rlcyhub2RlOiBFbGVtZW50KTogRWxlbWVudCB7XG4gIGNvbnN0IGNoaWxkcmVuID0gQXJyYXkuZnJvbShub2RlLmNoaWxkcmVuKTtcblxuICBjaGlsZHJlbi5mb3JFYWNoKChjaGlsZCkgPT4ge1xuICAgIGNvbnN0IHRleHQgPSBjaGlsZC50ZXh0Q29udGVudDtcblxuICAgIC8vIENoZWNrIGlmIGhhdmUgb25seSBgXFxuYCB0aGVuIHJlbW92ZSBpdFxuICAgIC8vIHdoZW4gaGF2ZSA8YnI+IHRleHRDb250ZW50IGlzIGVtcHR5IHN0cmluZyBbJyddXG4gICAgaWYgKHRleHQgJiYgdGV4dC5pbmNsdWRlcyhcIlxcblwiKSAmJiAhdGV4dC50cmltKCkpIHtcbiAgICAgIGNoaWxkLnJlbW92ZSgpO1xuICAgIH1cbiAgfSk7XG5cbiAgbm9kZS5pbm5lckhUTUwgPSBub2RlLmlubmVySFRNTC5yZXBsYWNlKC9cXG4vZywgXCIgXCIpO1xuXG4gIHJldHVybiBub2RlO1xufVxuIiwgImV4cG9ydCBmdW5jdGlvbiB0cmFuc2Zvcm1EaXZzVG9QYXJhZ3JhcGhzKGNvbnRhaW5lckVsZW1lbnQ6IEVsZW1lbnQpOiBFbGVtZW50IHtcbiAgLy8gR2V0IGFsbCB0aGUgZGl2IGVsZW1lbnRzIHdpdGhpbiB0aGUgY29udGFpbmVyXG4gIGNvbnN0IGRpdkVsZW1lbnRzID0gY29udGFpbmVyRWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiZGl2XCIpO1xuXG4gIC8vIEl0ZXJhdGUgdGhyb3VnaCBlYWNoIGRpdiBlbGVtZW50XG4gIGRpdkVsZW1lbnRzLmZvckVhY2goZnVuY3Rpb24gKGRpdkVsZW1lbnQpIHtcbiAgICAvLyBDcmVhdGUgYSBuZXcgcGFyYWdyYXBoIGVsZW1lbnRcbiAgICBjb25zdCBwYXJhZ3JhcGhFbGVtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInBcIik7XG5cbiAgICAvLyBDb3B5IGFsbCBhdHRyaWJ1dGVzIGZyb20gdGhlIGRpdiB0byB0aGUgcGFyYWdyYXBoXG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCBkaXZFbGVtZW50LmF0dHJpYnV0ZXMubGVuZ3RoOyBpKyspIHtcbiAgICAgIGNvbnN0IGF0dHIgPSBkaXZFbGVtZW50LmF0dHJpYnV0ZXNbaV07XG4gICAgICBwYXJhZ3JhcGhFbGVtZW50LnNldEF0dHJpYnV0ZShhdHRyLm5hbWUsIGF0dHIudmFsdWUpO1xuICAgIH1cblxuICAgIC8vIFRyYW5zZmVyIHRoZSBjb250ZW50IGZyb20gdGhlIGRpdiB0byB0aGUgcGFyYWdyYXBoXG4gICAgcGFyYWdyYXBoRWxlbWVudC5pbm5lckhUTUwgPSBkaXZFbGVtZW50LmlubmVySFRNTDtcblxuICAgIC8vIFJlcGxhY2UgdGhlIGRpdiB3aXRoIHRoZSBuZXcgcGFyYWdyYXBoIGVsZW1lbnRcbiAgICBkaXZFbGVtZW50LnBhcmVudE5vZGU/LnJlcGxhY2VDaGlsZChwYXJhZ3JhcGhFbGVtZW50LCBkaXZFbGVtZW50KTtcbiAgfSk7XG5cbiAgcmV0dXJuIGNvbnRhaW5lckVsZW1lbnQ7XG59XG4iLCAiaW1wb3J0IHsgZXh0cmFjdGVkQXR0cmlidXRlcyB9IGZyb20gXCIuLi9jb21tb25cIjtcbmltcG9ydCB7IGdldE5vZGVTdHlsZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldE5vZGVTdHlsZVwiO1xuXG5jb25zdCBhdHRyaWJ1dGVzID0gZXh0cmFjdGVkQXR0cmlidXRlcztcblxuZXhwb3J0IGZ1bmN0aW9uIGNvcHlDb2xvclN0eWxlVG9UZXh0Tm9kZXMoZWxlbWVudDogRWxlbWVudCk6IHZvaWQge1xuICBpZiAoZWxlbWVudC5ub2RlVHlwZSA9PT0gTm9kZS5URVhUX05PREUpIHtcbiAgICBsZXQgcGFyZW50RWxlbWVudCA9IGVsZW1lbnQucGFyZW50RWxlbWVudDtcblxuICAgIGlmICghcGFyZW50RWxlbWVudCkge1xuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGlmIChcbiAgICAgIHBhcmVudEVsZW1lbnQudGFnTmFtZSA9PT0gXCJTUEFOXCIgfHxcbiAgICAgIHBhcmVudEVsZW1lbnQudGFnTmFtZSA9PT0gXCJFTVwiIHx8XG4gICAgICBwYXJlbnRFbGVtZW50LnRhZ05hbWUgPT09IFwiU1RST05HXCJcbiAgICApIHtcbiAgICAgIGNvbnN0IHBhcmVudE9mUGFyZW50ID0gcGFyZW50RWxlbWVudC5wYXJlbnRFbGVtZW50O1xuICAgICAgY29uc3QgcGFyZW50U3R5bGUgPSBwYXJlbnRFbGVtZW50LnN0eWxlO1xuICAgICAgY29uc3QgcGFyZW50Q29tcHV0ZWRTdHlsZSA9IGdldENvbXB1dGVkU3R5bGUocGFyZW50RWxlbWVudCk7XG5cbiAgICAgIGlmIChcbiAgICAgICAgYXR0cmlidXRlcy5pbmNsdWRlcyhcInRleHQtdHJhbnNmb3JtXCIpICYmXG4gICAgICAgICFwYXJlbnRTdHlsZT8udGV4dFRyYW5zZm9ybVxuICAgICAgKSB7XG4gICAgICAgIGNvbnN0IHN0eWxlID0gZ2V0Tm9kZVN0eWxlKHBhcmVudEVsZW1lbnQpO1xuICAgICAgICBpZiAoc3R5bGVbXCJ0ZXh0LXRyYW5zZm9ybVwiXSA9PT0gXCJ1cHBlcmNhc2VcIikge1xuICAgICAgICAgIHBhcmVudEVsZW1lbnQuY2xhc3NMaXN0LmFkZChcImJyei1jYXBpdGFsaXplLW9uXCIpO1xuICAgICAgICB9XG4gICAgICB9XG5cbiAgICAgIGlmIChcbiAgICAgICAgYXR0cmlidXRlcy5pbmNsdWRlcyhcImZvbnQtc3R5bGVcIikgJiZcbiAgICAgICAgcGFyZW50Q29tcHV0ZWRTdHlsZS5mb250U3R5bGUgPT09IFwiaXRhbGljXCJcbiAgICAgICkge1xuICAgICAgICBjb25zdCBlbUVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZW1cIik7XG5cbiAgICAgICAgLy8gQ2xvbmUgdGhlIGF0dHJpYnV0ZXMgYW5kIGNoaWxkIG5vZGVzIGZyb20gdGhlIGN1cnJlbnQgcGFyZW50IGVsZW1lbnRcbiAgICAgICAgQXJyYXkuZnJvbShwYXJlbnRFbGVtZW50LmF0dHJpYnV0ZXMpLmZvckVhY2goKGF0dHIpID0+IHtcbiAgICAgICAgICBlbUVsZW1lbnQuc2V0QXR0cmlidXRlKGF0dHIubmFtZSwgYXR0ci52YWx1ZSk7XG4gICAgICAgIH0pO1xuXG4gICAgICAgIHdoaWxlIChwYXJlbnRFbGVtZW50LmZpcnN0Q2hpbGQpIHtcbiAgICAgICAgICBlbUVsZW1lbnQuYXBwZW5kQ2hpbGQocGFyZW50RWxlbWVudC5maXJzdENoaWxkKTtcbiAgICAgICAgfVxuXG4gICAgICAgIHBhcmVudEVsZW1lbnQucmVwbGFjZVdpdGgoZW1FbGVtZW50KTtcbiAgICAgICAgcGFyZW50RWxlbWVudCA9IGVtRWxlbWVudDtcbiAgICAgIH1cblxuICAgICAgaWYgKCFwYXJlbnRPZlBhcmVudCkge1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG5cbiAgICAgIGlmICghcGFyZW50U3R5bGU/LmNvbG9yKSB7XG4gICAgICAgIGNvbnN0IHBhcmVudE9GUGFyZW50U3R5bGUgPSBnZXROb2RlU3R5bGUocGFyZW50T2ZQYXJlbnQpO1xuICAgICAgICBwYXJlbnRFbGVtZW50LnN0eWxlLmNvbG9yID0gYCR7cGFyZW50T0ZQYXJlbnRTdHlsZS5jb2xvcn1gO1xuICAgICAgfVxuICAgICAgaWYgKCFwYXJlbnRTdHlsZT8uZm9udFdlaWdodCAmJiBwYXJlbnRPZlBhcmVudC5zdHlsZT8uZm9udFdlaWdodCkge1xuICAgICAgICBwYXJlbnRFbGVtZW50LnN0eWxlLmZvbnRXZWlnaHQgPSBwYXJlbnRPZlBhcmVudC5zdHlsZS5mb250V2VpZ2h0O1xuICAgICAgfVxuXG4gICAgICBpZiAocGFyZW50T2ZQYXJlbnQudGFnTmFtZSA9PT0gXCJTUEFOXCIpIHtcbiAgICAgICAgY29uc3QgcGFyZW50Rm9udFdlaWdodCA9IHBhcmVudEVsZW1lbnQuc3R5bGUuZm9udFdlaWdodDtcbiAgICAgICAgcGFyZW50RWxlbWVudC5zdHlsZS5mb250V2VpZ2h0ID1cbiAgICAgICAgICBwYXJlbnRGb250V2VpZ2h0IHx8IGdldENvbXB1dGVkU3R5bGUocGFyZW50RWxlbWVudCkuZm9udFdlaWdodDtcbiAgICAgIH1cblxuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGxldCBpbm5lckVsZW1lbnRUeXBlID0gXCJzcGFuXCI7XG5cbiAgICBjb25zdCBjb21wdXRlZFN0eWxlcyA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKHBhcmVudEVsZW1lbnQpO1xuICAgIGNvbnN0IHBhcmVudFN0eWxlID0gZ2V0Tm9kZVN0eWxlKHBhcmVudEVsZW1lbnQpO1xuXG4gICAgLy8gTmVlZCB0byByZXBsYWNlIHRoZSBzcGFuIHRvIGVtIGZvciBRdWlsbChCcml6eSBCdWlsZGVyKVxuICAgIGlmIChcbiAgICAgIGF0dHJpYnV0ZXMuaW5jbHVkZXMoXCJmb250LXN0eWxlXCIpICYmXG4gICAgICAocGFyZW50U3R5bGVbXCJmb250LXN0eWxlXCJdID09PSBcIml0YWxpY1wiIHx8XG4gICAgICAgIGNvbXB1dGVkU3R5bGVzLmZvbnRTdHlsZSA9PT0gXCJpdGFsaWNcIilcbiAgICApIHtcbiAgICAgIGlubmVyRWxlbWVudFR5cGUgPSBcImVtXCI7XG4gICAgfVxuXG4gICAgY29uc3QgaW5uZXJFbGVtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChpbm5lckVsZW1lbnRUeXBlKTtcblxuICAgIGlmIChcbiAgICAgIGF0dHJpYnV0ZXMuaW5jbHVkZXMoXCJ0ZXh0LXRyYW5zZm9ybVwiKSAmJlxuICAgICAgY29tcHV0ZWRTdHlsZXMudGV4dFRyYW5zZm9ybSA9PT0gXCJ1cHBlcmNhc2VcIlxuICAgICkge1xuICAgICAgaW5uZXJFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJicnotY2FwaXRhbGl6ZS1vblwiKTtcbiAgICB9XG5cbiAgICBpZiAoY29tcHV0ZWRTdHlsZXMuY29sb3IpIHtcbiAgICAgIGlubmVyRWxlbWVudC5zdHlsZS5jb2xvciA9IGNvbXB1dGVkU3R5bGVzLmNvbG9yO1xuICAgIH1cblxuICAgIGlmIChjb21wdXRlZFN0eWxlcy5iYWNrZ3JvdW5kQ29sb3IpIHtcbiAgICAgIGlubmVyRWxlbWVudC5zdHlsZS5iYWNrZ3JvdW5kQ29sb3IgPSBjb21wdXRlZFN0eWxlcy5iYWNrZ3JvdW5kQ29sb3I7XG4gICAgfVxuXG4gICAgaWYgKGNvbXB1dGVkU3R5bGVzLmZvbnRXZWlnaHQpIHtcbiAgICAgIGlubmVyRWxlbWVudC5zdHlsZS5mb250V2VpZ2h0ID0gY29tcHV0ZWRTdHlsZXMuZm9udFdlaWdodDtcbiAgICB9XG5cbiAgICBpbm5lckVsZW1lbnQudGV4dENvbnRlbnQgPSBlbGVtZW50LnRleHRDb250ZW50O1xuXG4gICAgaWYgKHBhcmVudEVsZW1lbnQudGFnTmFtZSA9PT0gXCJVXCIpIHtcbiAgICAgIHBhcmVudEVsZW1lbnQuc3R5bGUuY29sb3IgPSBjb21wdXRlZFN0eWxlcy5jb2xvcjtcbiAgICB9XG5cbiAgICBpZiAoZWxlbWVudCkge1xuICAgICAgcGFyZW50RWxlbWVudC5yZXBsYWNlQ2hpbGQoaW5uZXJFbGVtZW50LCBlbGVtZW50KTtcbiAgICB9XG4gIH0gZWxzZSBpZiAoZWxlbWVudC5ub2RlVHlwZSA9PT0gTm9kZS5FTEVNRU5UX05PREUpIHtcbiAgICAvLyBJZiB0aGUgY3VycmVudCBub2RlIGlzIGFuIGVsZW1lbnQgbm9kZSwgcmVjdXJzaXZlbHkgcHJvY2VzcyBpdHMgY2hpbGQgbm9kZXNcbiAgICBjb25zdCBjaGlsZHJlbiA9IGVsZW1lbnQuY2hpbGROb2RlcztcbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IGNoaWxkcmVuLmxlbmd0aDsgaSsrKSB7XG4gICAgICBjb25zdCBub2RlID0gY2hpbGRyZW5baV07XG4gICAgICAvLyBDaGVjayBpZiBub3QgXCJcXG5cIiBvciBlbXB0eSBcIlwiXG4gICAgICBpZiAobm9kZS50ZXh0Q29udGVudD8udHJpbSgpKSB7XG4gICAgICAgIGNvcHlDb2xvclN0eWxlVG9UZXh0Tm9kZXMobm9kZSBhcyBFbGVtZW50KTtcbiAgICAgIH1cbiAgICB9XG4gIH1cbn1cblxuZXhwb3J0IGZ1bmN0aW9uIGNvcHlQYXJlbnRDb2xvclRvQ2hpbGQobm9kZTogRWxlbWVudCkge1xuICBub2RlLmNoaWxkTm9kZXMuZm9yRWFjaCgoY2hpbGQpID0+IHtcbiAgICBjb3B5Q29sb3JTdHlsZVRvVGV4dE5vZGVzKGNoaWxkIGFzIEVsZW1lbnQpO1xuICB9KTtcblxuICByZXR1cm4gbm9kZTtcbn1cbiIsICJpbXBvcnQgeyBlbmNvZGVUb1N0cmluZywgZ2V0SHJlZiwgZ2V0VGFyZ2V0IH0gZnJvbSBcIi4uL2NvbW1vbi9pbmRleFwiO1xuXG5leHBvcnQgZnVuY3Rpb24gZW5jb2RlTGlua3Mobm9kZTogRWxlbWVudCwgdXJsTWFwOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+KSB7XG4gIGNvbnN0IGxpbmtzID0gQXJyYXkuZnJvbShub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoXCJhXCIpKTtcblxuICBsaW5rcy5tYXAoKGxpbmspID0+IHtcbiAgICBjb25zdCBocmVmID0gZ2V0SHJlZihsaW5rKTtcbiAgICBjb25zdCBtYXBwZWRIcmVmID0gaHJlZiAmJiB1cmxNYXBbaHJlZl0gIT09IHVuZGVmaW5lZCA/IHVybE1hcFtocmVmXSA6IGhyZWY7XG4gICAgY29uc3QgdGFyZ2V0ID0gZ2V0VGFyZ2V0KGxpbmspO1xuICAgIGNvbnN0IHRhcmdldFR5cGUgPSB0YXJnZXQgPT09IFwiX3NlbGZcIiA/IFwib2ZmXCIgOiBcIm9uXCI7XG5cbiAgICBsaW5rLmRhdGFzZXQuaHJlZiA9IGVuY29kZVRvU3RyaW5nKHtcbiAgICAgIHR5cGU6IFwiZXh0ZXJuYWxcIixcbiAgICAgIGFuY2hvcjogXCJcIixcbiAgICAgIGV4dGVybmFsOiBtYXBwZWRIcmVmLFxuICAgICAgZXh0ZXJuYWxCbGFuazogdGFyZ2V0VHlwZSxcbiAgICAgIGV4dGVybmFsUmVsOiBcIm9mZlwiLFxuICAgICAgZXh0ZXJuYWxUeXBlOiBcImV4dGVybmFsXCIsXG4gICAgICBwb3B1bGF0aW9uOiBcIlwiLFxuICAgICAgcG9wdWxhdGlvbkVudGl0eUlkOiBcIlwiLFxuICAgICAgcG9wdWxhdGlvbkVudGl0eVR5cGU6IFwiXCIsXG4gICAgICBwb3B1cDogXCJcIixcbiAgICAgIHVwbG9hZDogXCJcIixcbiAgICAgIGxpbmtUb1NsaWRlOiAxLFxuICAgICAgaW50ZXJuYWw6IFwiXCIsXG4gICAgICBpbnRlcm5hbEJsYW5rOiBcIm9mZlwiLFxuICAgICAgcGFnZVRpdGxlOiBcIlwiLFxuICAgICAgcGFnZVNvdXJjZTogbnVsbFxuICAgIH0pO1xuXG4gICAgbGluay5yZW1vdmVBdHRyaWJ1dGUoXCJocmVmXCIpO1xuICB9KTtcblxuICByZXR1cm4gbm9kZTtcbn1cbiIsICJleHBvcnQgY29uc3QgcmVjdXJzaXZlR2V0Tm9kZXMgPSAobm9kZTogRWxlbWVudCk6IEFycmF5PEVsZW1lbnQ+ID0+IHtcbiAgbGV0IG5vZGVzOiBBcnJheTxFbGVtZW50PiA9IFtdO1xuICBpZiAobm9kZS5ub2RlVHlwZSA9PT0gTm9kZS5URVhUX05PREUpIHtcbiAgICAvLyBGb3VuZCBhIHRleHQgbm9kZSwgcmVjb3JkIGl0cyBmaXJzdCBwYXJlbnQgZWxlbWVudFxuICAgIG5vZGUucGFyZW50RWxlbWVudCAmJiBub2Rlcy5wdXNoKG5vZGUucGFyZW50RWxlbWVudCk7XG4gIH0gZWxzZSB7XG4gICAgZm9yIChsZXQgaSA9IDA7IGkgPCBub2RlLmNoaWxkTm9kZXMubGVuZ3RoOyBpKyspIHtcbiAgICAgIGNvbnN0IGNoaWxkID0gbm9kZS5jaGlsZE5vZGVzW2ldO1xuICAgICAgLy8gUmVjdXJzaXZlbHkgc2VhcmNoIGNoaWxkIG5vZGVzIGFuZCBhZGQgdGhlaXIgcmVzdWx0cyB0byB0aGUgcmVzdWx0IGFycmF5XG4gICAgICBpZiAoY2hpbGQpIHtcbiAgICAgICAgbm9kZXMgPSBub2Rlcy5jb25jYXQocmVjdXJzaXZlR2V0Tm9kZXMoY2hpbGQgYXMgRWxlbWVudCkpO1xuICAgICAgfVxuICAgIH1cbiAgfVxuICByZXR1cm4gbm9kZXM7XG59O1xuIiwgImltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwiLi4vdHlwZXNcIjtcbmltcG9ydCB7IGdldE5vZGVTdHlsZSB9IGZyb20gXCIuL2dldE5vZGVTdHlsZVwiO1xuaW1wb3J0IHsgcmVjdXJzaXZlR2V0Tm9kZXMgfSBmcm9tIFwiLi9yZWN1cnNpdmVHZXROb2Rlc1wiO1xuXG5leHBvcnQgZnVuY3Rpb24gZXh0cmFjdEFsbEVsZW1lbnRzU3R5bGVzKFxuICBub2RlOiBFbGVtZW50XG4pOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPiB7XG4gIGNvbnN0IG5vZGVzID0gcmVjdXJzaXZlR2V0Tm9kZXMobm9kZSk7XG4gIHJldHVybiBub2Rlcy5yZWR1Y2UoKGFjYywgZWxlbWVudCkgPT4ge1xuICAgIGNvbnN0IHN0eWxlcyA9IGdldE5vZGVTdHlsZShlbGVtZW50KTtcblxuICAgIC8vIFRleHQtQWxpZ24gYXJlIHdyb25nIGZvciBJbmxpbmUgRWxlbWVudHNcbiAgICBpZiAoc3R5bGVzW1wiZGlzcGxheVwiXSA9PT0gXCJpbmxpbmVcIikge1xuICAgICAgZGVsZXRlIHN0eWxlc1tcInRleHQtYWxpZ25cIl07XG4gICAgfVxuXG4gICAgcmV0dXJuIHsgLi4uYWNjLCAuLi5zdHlsZXMgfTtcbiAgfSwge30pO1xufVxuIiwgImltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwidXRpbHNcIjtcbmltcG9ydCB7IGV4dHJhY3RBbGxFbGVtZW50c1N0eWxlcyB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2V4dHJhY3RBbGxFbGVtZW50c1N0eWxlc1wiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5cbmV4cG9ydCBmdW5jdGlvbiBtZXJnZVN0eWxlcyhlbGVtZW50OiBFbGVtZW50KTogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4ge1xuICBjb25zdCBlbGVtZW50U3R5bGVzID0gZ2V0Tm9kZVN0eWxlKGVsZW1lbnQpO1xuXG4gIC8vIFRleHQtQWxpZ24gYXJlIHdyb25nIGZvciBJbmxpbmUgRWxlbWVudHNcbiAgaWYgKGVsZW1lbnRTdHlsZXNbXCJkaXNwbGF5XCJdID09PSBcImlubGluZVwiKSB7XG4gICAgZGVsZXRlIGVsZW1lbnRTdHlsZXNbXCJ0ZXh0LWFsaWduXCJdO1xuICB9XG5cbiAgY29uc3QgaW5uZXJTdHlsZXMgPSBleHRyYWN0QWxsRWxlbWVudHNTdHlsZXMoZWxlbWVudCk7XG5cbiAgcmV0dXJuIHtcbiAgICAuLi5lbGVtZW50U3R5bGVzLFxuICAgIC4uLmlubmVyU3R5bGVzLFxuICAgIFwibGluZS1oZWlnaHRcIjogZWxlbWVudFN0eWxlc1tcImxpbmUtaGVpZ2h0XCJdXG4gIH07XG59XG4iLCAiaW1wb3J0IHsgZXhjZXB0RXh0cmFjdGluZ1N0eWxlLCBzaG91bGRFeHRyYWN0RWxlbWVudCB9IGZyb20gXCIuLi9jb21tb25cIjtcbmltcG9ydCB7IG1lcmdlU3R5bGVzIH0gZnJvbSBcIi4uL3N0eWxlcy9tZXJnZVN0eWxlc1wiO1xuaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCJ1dGlsc1wiO1xuXG5pbnRlcmZhY2UgT3V0cHV0IHtcbiAgdWlkOiBzdHJpbmc7XG4gIHRhZ05hbWU6IHN0cmluZztcbiAgc3R5bGVzOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPjtcbn1cblxuZXhwb3J0IGZ1bmN0aW9uIGV4dHJhY3RQYXJlbnRFbGVtZW50c1dpdGhTdHlsZXMobm9kZTogRWxlbWVudCk6IEFycmF5PE91dHB1dD4ge1xuICBsZXQgcmVzdWx0OiBBcnJheTxPdXRwdXQ+ID0gW107XG5cbiAgaWYgKHNob3VsZEV4dHJhY3RFbGVtZW50KG5vZGUsIGV4Y2VwdEV4dHJhY3RpbmdTdHlsZSkpIHtcbiAgICBjb25zdCB1aWQgPSBgdWlkLSR7TWF0aC5yYW5kb20oKX0tJHtNYXRoLnJhbmRvbSgpfWA7XG4gICAgbm9kZS5zZXRBdHRyaWJ1dGUoXCJkYXRhLXVpZFwiLCB1aWQpO1xuXG4gICAgcmVzdWx0LnB1c2goe1xuICAgICAgdWlkLFxuICAgICAgdGFnTmFtZTogbm9kZS50YWdOYW1lLFxuICAgICAgc3R5bGVzOiBtZXJnZVN0eWxlcyhub2RlKVxuICAgIH0pO1xuICB9XG5cbiAgZm9yIChsZXQgaSA9IDA7IGkgPCBub2RlLmNoaWxkTm9kZXMubGVuZ3RoOyBpKyspIHtcbiAgICBjb25zdCBjaGlsZCA9IG5vZGUuY2hpbGROb2Rlc1tpXTtcbiAgICByZXN1bHQgPSByZXN1bHQuY29uY2F0KGV4dHJhY3RQYXJlbnRFbGVtZW50c1dpdGhTdHlsZXMoY2hpbGQgYXMgRWxlbWVudCkpO1xuICB9XG5cbiAgcmV0dXJuIHJlc3VsdDtcbn1cbiIsICJpbXBvcnQgeyBleHRyYWN0ZWRBdHRyaWJ1dGVzIH0gZnJvbSBcIi4uL2NvbW1vblwiO1xuaW1wb3J0IHsgZXh0cmFjdFBhcmVudEVsZW1lbnRzV2l0aFN0eWxlcyB9IGZyb20gXCIuLi9kb20vZXh0cmFjdFBhcmVudEVsZW1lbnRzV2l0aFN0eWxlc1wiO1xuaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCJ1dGlsc1wiO1xuXG5pbnRlcmZhY2UgT3V0cHV0IHtcbiAgdWlkOiBzdHJpbmc7XG4gIHRhZ05hbWU6IHN0cmluZztcbiAgc3R5bGVzOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPjtcbn1cblxuZXhwb3J0IGNvbnN0IGdldFR5cG9ncmFwaHlTdHlsZXMgPSAobm9kZTogRWxlbWVudCk6IEFycmF5PE91dHB1dD4gPT4ge1xuICBjb25zdCBhbGxSaWNoVGV4dEVsZW1lbnRzID0gZXh0cmFjdFBhcmVudEVsZW1lbnRzV2l0aFN0eWxlcyhub2RlKTtcbiAgcmV0dXJuIGFsbFJpY2hUZXh0RWxlbWVudHMubWFwKChlbGVtZW50KSA9PiB7XG4gICAgY29uc3QgeyBzdHlsZXMgfSA9IGVsZW1lbnQ7XG5cbiAgICByZXR1cm4ge1xuICAgICAgLi4uZWxlbWVudCxcbiAgICAgIHN0eWxlczogZXh0cmFjdGVkQXR0cmlidXRlcy5yZWR1Y2UoKGFjYywgYXR0cmlidXRlKSA9PiB7XG4gICAgICAgIGFjY1thdHRyaWJ1dGVdID0gc3R5bGVzW2F0dHJpYnV0ZV07XG4gICAgICAgIHJldHVybiBhY2M7XG4gICAgICB9LCB7fSBhcyBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPilcbiAgICB9O1xuICB9KTtcbn07XG4iLCAiZXhwb3J0IGZ1bmN0aW9uIGdldExldHRlclNwYWNpbmcodmFsdWU6IHN0cmluZyk6IHN0cmluZyB7XG4gIGlmICh2YWx1ZSA9PT0gXCJub3JtYWxcIikge1xuICAgIHJldHVybiBcIjBcIjtcbiAgfVxuXG4gIC8vIFJlbW92ZSAncHgnIGFuZCBhbnkgZXh0cmEgd2hpdGVzcGFjZVxuICBjb25zdCBsZXR0ZXJTcGFjaW5nVmFsdWUgPSB2YWx1ZS5yZXBsYWNlKC9weC9nLCBcIlwiKS50cmltKCk7XG4gIGNvbnN0IFtpbnRlZ2VyUGFydCwgZGVjaW1hbFBhcnQgPSBcIjBcIl0gPSBsZXR0ZXJTcGFjaW5nVmFsdWUuc3BsaXQoXCIuXCIpO1xuICBjb25zdCB0b051bWJlckkgPSAraW50ZWdlclBhcnQ7XG5cbiAgaWYgKHRvTnVtYmVySSA8IDAgfHwgT2JqZWN0LmlzKHRvTnVtYmVySSwgLTApKSB7XG4gICAgcmV0dXJuIFwibV9cIiArIC10b051bWJlckkgKyBcIl9cIiArIGRlY2ltYWxQYXJ0WzBdO1xuICB9XG4gIHJldHVybiB0b051bWJlckkgKyBcIl9cIiArIGRlY2ltYWxQYXJ0WzBdO1xufVxuIiwgImltcG9ydCB7XG4gIGRlZmF1bHREZXNrdG9wTGluZUhlaWdodCxcbiAgZGVmYXVsdE1vYmlsZUxpbmVIZWlnaHQsXG4gIGRlZmF1bHRUYWJsZXRMaW5lSGVpZ2h0LFxuICB0ZXh0QWxpZ25cbn0gZnJvbSBcIi4uLy4uLy4uL3V0aWxzL2NvbW1vblwiO1xuaW1wb3J0IHsgZ2V0TGV0dGVyU3BhY2luZyB9IGZyb20gXCIuLi8uLi8uLi91dGlscy9zdHlsZXMvZ2V0TGV0dGVyU3BhY2luZ1wiO1xuaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCJ1dGlsc1wiO1xuaW1wb3J0ICogYXMgTnVtIGZyb20gXCJ1dGlscy9zcmMvcmVhZGVyL251bWJlclwiO1xuXG5leHBvcnQgY29uc3Qgc3R5bGVzVG9DbGFzc2VzID0gKFxuICBzdHlsZXM6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+LFxuICBmYW1pbGllczogUmVjb3JkPHN0cmluZywgc3RyaW5nPixcbiAgZGVmYXVsdEZhbWlseTogc3RyaW5nXG4pOiBBcnJheTxzdHJpbmc+ID0+IHtcbiAgY29uc3QgY2xhc3NlczogQXJyYXk8c3RyaW5nPiA9IFtdO1xuXG4gIE9iamVjdC5lbnRyaWVzKHN0eWxlcykuZm9yRWFjaCgoW2tleSwgdmFsdWVdKSA9PiB7XG4gICAgc3dpdGNoIChrZXkpIHtcbiAgICAgIGNhc2UgXCJmb250LXNpemVcIjoge1xuICAgICAgICBjb25zdCBzaXplID0gTWF0aC5yb3VuZChOdW0ucmVhZEludCh2YWx1ZSkgPz8gMSk7XG4gICAgICAgIGNsYXNzZXMucHVzaChgYnJ6LWZzLWxnLSR7c2l6ZX1gKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiZm9udC1mYW1pbHlcIjoge1xuICAgICAgICBjb25zdCBmb250RmFtaWx5ID0gYCR7dmFsdWV9YFxuICAgICAgICAgIC5yZXBsYWNlKC9bJ1wiXFwsXS9nLCBcIlwiKSAvLyBlc2xpbnQtZGlzYWJsZS1saW5lXG4gICAgICAgICAgLnJlcGxhY2UoL1xccy9nLCBcIl9cIilcbiAgICAgICAgICAudG9Mb2NhbGVMb3dlckNhc2UoKTtcblxuICAgICAgICBpZiAoIWZhbWlsaWVzW2ZvbnRGYW1pbHldKSB7XG4gICAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotZmYtJHtkZWZhdWx0RmFtaWx5fWAsIFwiYnJ6LWZ0LXVwbG9hZFwiKTtcbiAgICAgICAgICBicmVhaztcbiAgICAgICAgfVxuICAgICAgICBjbGFzc2VzLnB1c2goYGJyei1mZi0ke2ZhbWlsaWVzW2ZvbnRGYW1pbHldfWAsIFwiYnJ6LWZ0LXVwbG9hZFwiKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiZm9udC13ZWlnaHRcIjoge1xuICAgICAgICBjbGFzc2VzLnB1c2goYGJyei1mdy1sZy0ke3ZhbHVlfWApO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJ0ZXh0LWFsaWduXCI6IHtcbiAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotdGV4dC1sZy0ke3RleHRBbGlnblt2YWx1ZV0gfHwgXCJsZWZ0XCJ9YCk7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImxldHRlci1zcGFjaW5nXCI6IHtcbiAgICAgICAgY29uc3QgbGV0dGVyU3BhY2luZyA9IGdldExldHRlclNwYWNpbmcoYCR7dmFsdWV9YCk7XG4gICAgICAgIGNsYXNzZXMucHVzaChgYnJ6LWxzLWxnLSR7bGV0dGVyU3BhY2luZ31gKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwibGluZS1oZWlnaHRcIjoge1xuICAgICAgICBjbGFzc2VzLnB1c2goYGJyei1saC1sZy0ke2RlZmF1bHREZXNrdG9wTGluZUhlaWdodH1gKTtcbiAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotbGgtc20tJHtkZWZhdWx0VGFibGV0TGluZUhlaWdodH1gKTtcbiAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotbGgteHMtJHtkZWZhdWx0TW9iaWxlTGluZUhlaWdodH1gKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBkZWZhdWx0OlxuICAgICAgICBicmVhaztcbiAgICB9XG4gIH0pO1xuXG4gIHJldHVybiBjbGFzc2VzO1xufTtcbiIsICJpbXBvcnQgeyBjcmVhdGVXcmFwcGVyTW9kZWwgfSBmcm9tIFwiLi4vLi4vLi4vTW9kZWxzL1dyYXBwZXJcIjtcbmltcG9ydCB7IEVsZW1lbnRNb2RlbCB9IGZyb20gXCIuLi8uLi8uLi90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBhZGRNYXJnaW5zVG9MaXN0cyB9IGZyb20gXCIuLi8uLi8vdXRpbHMvc3R5bGVzL2FkZE1hcmdpbnNUb0xpc3RzXCI7XG5pbXBvcnQgeyByZW1vdmVBbGxTdHlsZXNGcm9tSFRNTCB9IGZyb20gXCIuLi8uLi91dGlscy9kb20vcmVtb3ZlQWxsU3R5bGVzRnJvbUhUTUxcIjtcbmltcG9ydCB7IHJlbW92ZUVtcHR5Tm9kZXMgfSBmcm9tIFwiLi4vLi4vdXRpbHMvZG9tL3JlbW92ZUVtcHR5Tm9kZXNcIjtcbmltcG9ydCB7IHRyYW5zZm9ybURpdnNUb1BhcmFncmFwaHMgfSBmcm9tIFwiLi4vLi4vdXRpbHMvZG9tL3RyYW5zZm9ybURpdnNUb1BhcmFncmFwaHNcIjtcbmltcG9ydCB7IGNvcHlQYXJlbnRDb2xvclRvQ2hpbGQgfSBmcm9tIFwiLi4vLi4vdXRpbHMvc3R5bGVzL2NvcHlQYXJlbnRDb2xvclRvQ2hpbGRcIjtcbmltcG9ydCB7IGVuY29kZUxpbmtzIH0gZnJvbSBcIi4uLy4uL3V0aWxzL3N0eWxlcy9lbmNvZGVMaW5rc1wiO1xuaW1wb3J0IHsgZ2V0VHlwb2dyYXBoeVN0eWxlcyB9IGZyb20gXCIuLi8uLi91dGlscy9zdHlsZXMvZ2V0VHlwb2dyYXBoeVN0eWxlc1wiO1xuaW1wb3J0IHsgc3R5bGVzVG9DbGFzc2VzIH0gZnJvbSBcIi4vdXRpbHMvc3R5bGVzVG9DbGFzc2VzXCI7XG5pbXBvcnQgeyB1dWlkIH0gZnJvbSBcInV0aWxzL3NyYy91dWlkXCI7XG5cbmludGVyZmFjZSBEYXRhIHtcbiAgbm9kZTogRWxlbWVudDtcbiAgZmFtaWxpZXM6IFJlY29yZDxzdHJpbmcsIHN0cmluZz47XG4gIHVybE1hcDogUmVjb3JkPHN0cmluZywgc3RyaW5nPjtcbiAgZGVmYXVsdEZhbWlseTogc3RyaW5nO1xufVxuXG5leHBvcnQgY29uc3QgZ2V0VGV4dE1vZGVsID0gKGRhdGE6IERhdGEpOiBFbGVtZW50TW9kZWwgPT4ge1xuICBjb25zdCB7IG5vZGU6IF9ub2RlLCBmYW1pbGllcywgZGVmYXVsdEZhbWlseSwgdXJsTWFwIH0gPSBkYXRhO1xuICBsZXQgbm9kZSA9IF9ub2RlO1xuXG4gIC8vIFRyYW5zZm9ybSBhbGwgaW5zaWRlIGRpdiB0byBQXG4gIG5vZGUgPSB0cmFuc2Zvcm1EaXZzVG9QYXJhZ3JhcGhzKG5vZGUpO1xuXG4gIC8vIENvcHkgUGFyZW50IENvbG9yIHRvIENoaWxkLCBmcm9tIDxwPiB0byA8c3Bhbj5cbiAgbm9kZSA9IGNvcHlQYXJlbnRDb2xvclRvQ2hpbGQobm9kZSk7XG5cbiAgLy8gZW5jb2RlIGFsbCBsaW5rc1xuICBub2RlID0gZW5jb2RlTGlua3Mobm9kZSwgdXJsTWFwKTtcblxuICAvLyBHZXQgYWxsIG91cnMgc3R5bGUgZm9yIEJ1aWxkZXIgW2ZvbnQtZmFtaWx5LCBmb250LXNpemUsIGxpbmUtaGVpZ2h0LCAuZXRjXVxuICBjb25zdCBzdHlsZXMgPSBnZXRUeXBvZ3JhcGh5U3R5bGVzKG5vZGUpO1xuXG4gIC8vIEdldCBhbGwgbGlzdHMgYW5kIGFkZCBtYXJnaW5zIHRvIGl0O1xuICBub2RlID0gYWRkTWFyZ2luc1RvTGlzdHMobm9kZSk7XG5cbiAgLy8gUmVtb3ZlIGFsbCBpbmxpbmUgc3R5bGVzIGxpa2UgYmFja2dyb3VuZC1jb2xvciwgcG9zaXRpb25zLi4gZXRjLlxuICBub2RlID0gcmVtb3ZlQWxsU3R5bGVzRnJvbUhUTUwobm9kZSk7XG5cbiAgLy8gVHJhbnNmb3JtIGFsbCBzdHlsZXMgdG8gY2xhc3NOYW1lIGZvbnQtc2l6ZTogMjAgdG8gLmJyei1mcy0yMFxuICBzdHlsZXMubWFwKChzdHlsZSkgPT4ge1xuICAgIGNvbnN0IGNsYXNzZXMgPSBzdHlsZXNUb0NsYXNzZXMoc3R5bGUuc3R5bGVzLCBmYW1pbGllcywgZGVmYXVsdEZhbWlseSk7XG4gICAgY29uc3Qgc3R5bGVOb2RlID0gbm9kZS5xdWVyeVNlbGVjdG9yKGBbZGF0YS11aWQ9JyR7c3R5bGUudWlkfSddYCk7XG5cbiAgICBpZiAoc3R5bGVOb2RlKSB7XG4gICAgICBzdHlsZU5vZGUuY2xhc3NMaXN0LmFkZCguLi5jbGFzc2VzKTtcbiAgICAgIHN0eWxlTm9kZS5yZW1vdmVBdHRyaWJ1dGUoXCJkYXRhLXVpZFwiKTtcbiAgICB9XG4gIH0pO1xuXG4gIC8vIFJlbW92ZSBhbGwgZW1wdHkgUCB3aXRoIFsgXFxuIF1cbiAgbm9kZSA9IHJlbW92ZUVtcHR5Tm9kZXMobm9kZSk7XG5cbiAgY29uc3QgdGV4dCA9IG5vZGUuaW5uZXJIVE1MO1xuXG4gIHJldHVybiBjcmVhdGVXcmFwcGVyTW9kZWwoe1xuICAgIF9zdHlsZXM6IFtcIndyYXBwZXJcIiwgXCJ3cmFwcGVyLS1yaWNoVGV4dFwiXSxcbiAgICBpdGVtczogW1xuICAgICAge1xuICAgICAgICB0eXBlOiBcIlJpY2hUZXh0XCIsXG4gICAgICAgIHZhbHVlOiB7XG4gICAgICAgICAgX2lkOiB1dWlkKCksXG4gICAgICAgICAgX3N0eWxlczogW1wicmljaFRleHRcIl0sXG4gICAgICAgICAgdGV4dDogdGV4dFxuICAgICAgICB9XG4gICAgICB9XG4gICAgXVxuICB9KTtcbn07XG4iLCAiaW1wb3J0IHtcbiAgYnV0dG9uU2VsZWN0b3IsXG4gIGVtYmVkU2VsZWN0b3IsXG4gIGV4dHJhY3RlZEF0dHJpYnV0ZXMsXG4gIGljb25TZWxlY3RvclxufSBmcm9tIFwiLi4vY29tbW9uXCI7XG5cbmV4cG9ydCBjbGFzcyBTdGFjayB7XG4gIGNvbGxlY3Rpb246IEFycmF5PEVsZW1lbnQ+ID0gW107XG5cbiAgYXBwZW5kKG5vZGU6IEVsZW1lbnQgfCBOb2RlLCBhdHRyPzogUmVjb3JkPHN0cmluZywgc3RyaW5nPikge1xuICAgIGNvbnN0IGRpdiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gICAgZGl2LmFwcGVuZChub2RlKTtcblxuICAgIGlmIChhdHRyKSB7XG4gICAgICBPYmplY3QuZW50cmllcyhhdHRyKS5mb3JFYWNoKChbbmFtZSwgdmFsdWVdKSA9PiB7XG4gICAgICAgIGRpdi5zZXRBdHRyaWJ1dGUoYGRhdGEtJHtuYW1lfWAsIHZhbHVlKTtcbiAgICAgIH0pO1xuICAgIH1cblxuICAgIHRoaXMuY29sbGVjdGlvbi5wdXNoKGRpdik7XG4gIH1cblxuICBzZXQobm9kZTogRWxlbWVudCwgYXR0cj86IFJlY29yZDxzdHJpbmcsIHN0cmluZz4pIHtcbiAgICBjb25zdCBjb2xMZW5ndGggPSB0aGlzLmNvbGxlY3Rpb24ubGVuZ3RoO1xuXG4gICAgaWYgKGNvbExlbmd0aCA9PT0gMCkge1xuICAgICAgdGhpcy5hcHBlbmQobm9kZSwgYXR0cik7XG4gICAgfSBlbHNlIHtcbiAgICAgIGNvbnN0IGxhc3RDb2xsZWN0aW9uID0gdGhpcy5jb2xsZWN0aW9uW2NvbExlbmd0aCAtIDFdO1xuICAgICAgbGFzdENvbGxlY3Rpb24uYXBwZW5kKG5vZGUpO1xuICAgIH1cbiAgfVxuXG4gIGdldEFsbCgpIHtcbiAgICByZXR1cm4gdGhpcy5jb2xsZWN0aW9uO1xuICB9XG59XG5cbmludGVyZmFjZSBDb250YWluZXIge1xuICBjb250YWluZXI6IEVsZW1lbnQ7XG4gIGRlc3Ryb3k6ICgpID0+IHZvaWQ7XG59XG5cbmNvbnN0IGV4dHJhY3RJbm5lclRleHQgPSAobm9kZTogTm9kZSwgc3RhY2s6IFN0YWNrLCBzZWxlY3Rvcjogc3RyaW5nKTogdm9pZCA9PiB7XG4gIGNvbnN0IF9ub2RlID0gbm9kZS5jbG9uZU5vZGUodHJ1ZSk7XG5cbiAgaWYgKF9ub2RlIGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpIHtcbiAgICBjb25zdCBpbm5lckVsZW1lbnRzID0gX25vZGUucXVlcnlTZWxlY3RvckFsbChzZWxlY3Rvcik7XG5cbiAgICBpZiAoaW5uZXJFbGVtZW50cy5sZW5ndGggPiAwKSB7XG4gICAgICBpbm5lckVsZW1lbnRzLmZvckVhY2goKGVsKSA9PiB7XG4gICAgICAgIGVsLnJlbW92ZSgpO1xuICAgICAgfSk7XG4gICAgfVxuICAgIC8vIEV4dHJhY3QgdGhlIG90aGVyIGh0bWwgd2l0aG91dCBBcnRpZmFjdHMgbGlrZSBCdXR0b24sIEljb25zXG4gICAgY29uc3QgdGV4dCA9IF9ub2RlLnRleHRDb250ZW50O1xuXG4gICAgaWYgKHRleHQgJiYgdGV4dC50cmltKCkpIHtcbiAgICAgIHN0YWNrLmFwcGVuZChfbm9kZSwgeyB0eXBlOiBcInRleHRcIiB9KTtcbiAgICB9XG4gIH1cbn07XG5cbmZ1bmN0aW9uIGFwcGVuZE5vZGVTdHlsZXMobm9kZTogSFRNTEVsZW1lbnQsIHRhcmdldE5vZGU6IEhUTUxFbGVtZW50KSB7XG4gIGNvbnN0IHN0eWxlcyA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKG5vZGUpO1xuICBleHRyYWN0ZWRBdHRyaWJ1dGVzLmZvckVhY2goKHN0eWxlKSA9PiB7XG4gICAgdGFyZ2V0Tm9kZS5zdHlsZS5zZXRQcm9wZXJ0eShzdHlsZSwgc3R5bGVzLmdldFByb3BlcnR5VmFsdWUoc3R5bGUpKTtcbiAgfSk7XG59XG5cbmZ1bmN0aW9uIHJlbW92ZU5lc3RlZERpdnMobm9kZTogSFRNTEVsZW1lbnQpIHtcbiAgY29uc3QgZW1iZWRkZWRQYXN0ZUV4aXN0cyA9IG5vZGUucXVlcnlTZWxlY3RvckFsbChlbWJlZFNlbGVjdG9yKS5sZW5ndGggPiAwO1xuXG4gIGlmICghZW1iZWRkZWRQYXN0ZUV4aXN0cykge1xuICAgIEFycmF5LmZyb20obm9kZS5jaGlsZE5vZGVzKS5mb3JFYWNoKChjaGlsZCkgPT4ge1xuICAgICAgaWYgKFxuICAgICAgICBjaGlsZCBpbnN0YW5jZW9mIEhUTUxFbGVtZW50ICYmXG4gICAgICAgIChjaGlsZC5ub2RlTmFtZSA9PT0gXCJESVZcIiB8fCBjaGlsZC5ub2RlTmFtZSA9PT0gXCJDRU5URVJcIilcbiAgICAgICkge1xuICAgICAgICByZW1vdmVOZXN0ZWREaXZzKGNoaWxkKTtcbiAgICAgICAgLy8gaW4gY2FzZSBpZiB0aGVyZSBpcyBubyBkaXYgb3IgcCBpbnNpZGUgb2Ygbm9kZSBzaG91bGQgc3RvcCBmbGF0dGVuaW5nXG4gICAgICAgIGNvbnN0IHRhZ3NUb0ZsYXR0ZW4gPSBbXCJESVZcIiwgXCJQXCJdO1xuICAgICAgICBjb25zdCBoYXNEaXZPclBDaGlsZHJlbiA9IEFycmF5LmZyb20oY2hpbGQuY2hpbGRyZW4pLmZpbmQoKG5vZGUpID0+XG4gICAgICAgICAgdGFnc1RvRmxhdHRlbi5pbmNsdWRlcyhub2RlLm5vZGVOYW1lKVxuICAgICAgICApO1xuICAgICAgICBpZiAoIWhhc0Rpdk9yUENoaWxkcmVuKSByZXR1cm47XG5cbiAgICAgICAgLy8gaW5zZXJ0IGdyYW5jaGlsZCB0byBjaGlsZCBwYXJlbnQgbm9kZSBhbmQgcmVtb3ZlIGNoaWxkXG4gICAgICAgIEFycmF5LmZyb20oY2hpbGQuY2hpbGROb2RlcykuZm9yRWFjaCgoZ3JhbmRjaGlsZCkgPT4ge1xuICAgICAgICAgIGlmIChncmFuZGNoaWxkIGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpIHtcbiAgICAgICAgICAgIGFwcGVuZE5vZGVTdHlsZXMoZ3JhbmRjaGlsZCwgZ3JhbmRjaGlsZCk7XG5cbiAgICAgICAgICAgIG5vZGUuaW5zZXJ0QmVmb3JlKGdyYW5kY2hpbGQsIGNoaWxkKTtcbiAgICAgICAgICB9IGVsc2UgaWYgKGdyYW5kY2hpbGQudGV4dENvbnRlbnQ/LnRyaW0oKSkge1xuICAgICAgICAgICAgY29uc3QgY29udGFpbmVyT2ZOb2RlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgICAgICAgICAgIGFwcGVuZE5vZGVTdHlsZXMoY2hpbGQsIGNvbnRhaW5lck9mTm9kZSk7XG4gICAgICAgICAgICBjb250YWluZXJPZk5vZGUuYXBwZW5kKGdyYW5kY2hpbGQpO1xuXG4gICAgICAgICAgICBub2RlLmluc2VydEJlZm9yZShjb250YWluZXJPZk5vZGUsIGNoaWxkKTtcbiAgICAgICAgICB9XG4gICAgICAgIH0pO1xuXG4gICAgICAgIG5vZGUucmVtb3ZlQ2hpbGQoY2hpbGQpO1xuICAgICAgfVxuICAgIH0pO1xuICB9XG59XG5cbmNvbnN0IGZsYXR0ZW5Ob2RlID0gKG5vZGU6IEVsZW1lbnQpID0+IHtcbiAgY29uc3QgX25vZGUgPSBub2RlLmNsb25lTm9kZSh0cnVlKSBhcyBIVE1MRWxlbWVudDtcblxuICBub2RlLnBhcmVudEVsZW1lbnQ/LmFwcGVuZChfbm9kZSk7XG5cbiAgcmVtb3ZlTmVzdGVkRGl2cyhfbm9kZSk7XG5cbiAgX25vZGUucmVtb3ZlKCk7XG5cbiAgcmV0dXJuIF9ub2RlO1xufTtcblxuZXhwb3J0IGNvbnN0IGdldENvbnRhaW5lclN0YWNrV2l0aE5vZGVzID0gKHBhcmVudE5vZGU6IEVsZW1lbnQpOiBDb250YWluZXIgPT4ge1xuICBjb25zdCBjb250YWluZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuICBjb25zdCBzdGFjayA9IG5ldyBTdGFjaygpO1xuICBsZXQgYXBwZW5kTmV3VGV4dCA9IGZhbHNlO1xuXG4gIGNvbnN0IGZsYXROb2RlID0gZmxhdHRlbk5vZGUocGFyZW50Tm9kZSk7XG5cbiAgZmxhdE5vZGUuY2hpbGROb2Rlcy5mb3JFYWNoKChub2RlKSA9PiB7XG4gICAgY29uc3QgX25vZGUgPSBub2RlLmNsb25lTm9kZSh0cnVlKTtcbiAgICBjb25zdCBjb250YWluZXJPZk5vZGUgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuICAgIGNvbnRhaW5lck9mTm9kZS5hcHBlbmQoX25vZGUpO1xuXG4gICAgLy8gRXhjbHVkZSBleHRyYWN0aW5nIGljb25zICYgYnV0dG9uIGZvciBbIFVMLCBPTCBdXG4gICAgLy8gUmVtb3ZlZCBhbGwgaWNvbnMgJiBidXR0b24gaW5zaWRlIFsgVUwsIE9MIF1cbiAgICBjb25zdCBleGNsdWRlSWNvbnMgPVxuICAgICAgX25vZGUgaW5zdGFuY2VvZiBIVE1MT0xpc3RFbGVtZW50IHx8IF9ub2RlIGluc3RhbmNlb2YgSFRNTFVMaXN0RWxlbWVudDtcblxuICAgIGlmIChfbm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSB7XG4gICAgICBjb25zdCBpY29ucyA9IGNvbnRhaW5lck9mTm9kZS5xdWVyeVNlbGVjdG9yQWxsKGljb25TZWxlY3Rvcik7XG4gICAgICBjb25zdCBidXR0b25zID0gY29udGFpbmVyT2ZOb2RlLnF1ZXJ5U2VsZWN0b3JBbGwoYnV0dG9uU2VsZWN0b3IpO1xuXG4gICAgICBpZiAoZXhjbHVkZUljb25zKSB7XG4gICAgICAgIGljb25zLmZvckVhY2goKG5vZGUpID0+IHtcbiAgICAgICAgICBub2RlLnJlbW92ZSgpO1xuICAgICAgICB9KTtcbiAgICAgICAgYnV0dG9ucy5mb3JFYWNoKChub2RlKSA9PiB7XG4gICAgICAgICAgbm9kZS5yZW1vdmUoKTtcbiAgICAgICAgfSk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICAvLyBDaGVjayB0aGUgYnV0dG9uIGZpcnN0IGJlY2F1c2VcbiAgICAgICAgLy8gaW5zaWRlIGJ1dHRvbiBjYW4gYmUgaWNvbnNcbiAgICAgICAgaWYgKGJ1dHRvbnMubGVuZ3RoID4gMCkge1xuICAgICAgICAgIC8vIGNoZWNrIGZvciBub24gZW1wdHkgbm9kZXMgd2hpY2ggYXJlIG5vdCBpbnNpZGUgYnV0dG9uc1xuICAgICAgICAgIGNvbnN0IGNvbnRhaW5lciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gICAgICAgICAgY29udGFpbmVyLmlubmVySFRNTCA9IF9ub2RlLmlubmVySFRNTDtcblxuICAgICAgICAgIGNvbnN0IGlubmVyQnV0dG9ucyA9IGNvbnRhaW5lci5xdWVyeVNlbGVjdG9yQWxsKGJ1dHRvblNlbGVjdG9yKTtcbiAgICAgICAgICBpbm5lckJ1dHRvbnMuZm9yRWFjaCgoYnRuKSA9PiBidG4ucmVtb3ZlKCkpO1xuXG4gICAgICAgICAgY29uc3Qgb25seUJ1dHRvbnMgPVxuICAgICAgICAgICAgKGNvbnRhaW5lci50ZXh0Q29udGVudD8udHJpbSgpID8/IFwiXCIpLmxlbmd0aCA9PT0gMDtcblxuICAgICAgICAgIGlmIChvbmx5QnV0dG9ucykge1xuICAgICAgICAgICAgYXBwZW5kTmV3VGV4dCA9IHRydWU7XG4gICAgICAgICAgICBsZXQgYXBwZW5kZWRCdXR0b24gPSBmYWxzZTtcbiAgICAgICAgICAgIHBhcmVudE5vZGUucGFyZW50RWxlbWVudD8uYXBwZW5kKF9ub2RlKTtcblxuICAgICAgICAgICAgX25vZGUuY2hpbGROb2Rlcy5mb3JFYWNoKChub2RlKSA9PiB7XG4gICAgICAgICAgICAgIGlmIChub2RlIGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpIHtcbiAgICAgICAgICAgICAgICBjb25zdCBjb250YWluZXIgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuICAgICAgICAgICAgICAgIGNvbnRhaW5lci5hcHBlbmQobm9kZS5jbG9uZU5vZGUodHJ1ZSkpO1xuICAgICAgICAgICAgICAgIGFwcGVuZE5vZGVTdHlsZXMobm9kZSwgbm9kZSk7XG5cbiAgICAgICAgICAgICAgICBpZiAoY29udGFpbmVyLnF1ZXJ5U2VsZWN0b3IoYnV0dG9uU2VsZWN0b3IpKSB7XG4gICAgICAgICAgICAgICAgICAvLyBpZiBsYXRlc3QgYXBwZW5kZWQgaXMgaWNvbiwgaWNvbnMgbXVzdCBiZSB3cmFwcGVkIGluIHNhbWUgbm9kZVxuICAgICAgICAgICAgICAgICAgaWYgKGFwcGVuZGVkQnV0dG9uKSB7XG4gICAgICAgICAgICAgICAgICAgIHN0YWNrLnNldChub2RlKTtcbiAgICAgICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgIHN0YWNrLmFwcGVuZChub2RlLCB7IHR5cGU6IFwiYnV0dG9uXCIgfSk7XG4gICAgICAgICAgICAgICAgICAgIGFwcGVuZGVkQnV0dG9uID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgY29uc3QgdGV4dCA9IG5vZGUudGV4dENvbnRlbnQ7XG5cbiAgICAgICAgICAgICAgICAgIGlmICh0ZXh0Py50cmltKCkpIHtcbiAgICAgICAgICAgICAgICAgICAgZXh0cmFjdElubmVyVGV4dChub2RlLCBzdGFjaywgYnV0dG9uU2VsZWN0b3IpO1xuICAgICAgICAgICAgICAgICAgICBhcHBlbmRlZEJ1dHRvbiA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBjb25zdCB0ZXh0ID0gbm9kZS50ZXh0Q29udGVudDtcblxuICAgICAgICAgICAgICAgIGlmICh0ZXh0Py50cmltKCkpIHtcbiAgICAgICAgICAgICAgICAgIGV4dHJhY3RJbm5lclRleHQoX25vZGUsIHN0YWNrLCBidXR0b25TZWxlY3Rvcik7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIF9ub2RlLnJlbW92ZSgpO1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICAgIH1cbiAgICAgICAgICBfbm9kZS5yZW1vdmUoKTtcbiAgICAgICAgfVxuXG4gICAgICAgIGlmIChpY29ucy5sZW5ndGggPiAwKSB7XG4gICAgICAgICAgYXBwZW5kTmV3VGV4dCA9IHRydWU7XG4gICAgICAgICAgbGV0IGFwcGVuZGVkSWNvbiA9IGZhbHNlO1xuXG4gICAgICAgICAgQXJyYXkuZnJvbShfbm9kZS5jaGlsZE5vZGVzKS5mb3JFYWNoKChub2RlKSA9PiB7XG4gICAgICAgICAgICBpZiAobm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSB7XG4gICAgICAgICAgICAgIGNvbnN0IGNvbnRhaW5lciA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gICAgICAgICAgICAgIGNvbnRhaW5lci5hcHBlbmQobm9kZS5jbG9uZU5vZGUodHJ1ZSkpO1xuXG4gICAgICAgICAgICAgIGlmIChjb250YWluZXIucXVlcnlTZWxlY3RvcihpY29uU2VsZWN0b3IpKSB7XG4gICAgICAgICAgICAgICAgLy8gaWYgbGF0ZXN0IGFwcGVuZGVkIGlzIGljb24sIGljb25zIG11c3QgYmUgd3JhcHBlZCBpbiBzYW1lIG5vZGVcbiAgICAgICAgICAgICAgICBpZiAoYXBwZW5kZWRJY29uKSB7XG4gICAgICAgICAgICAgICAgICBzdGFjay5zZXQobm9kZSk7XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgIHN0YWNrLmFwcGVuZChub2RlLCB7IHR5cGU6IFwiaWNvblwiIH0pO1xuICAgICAgICAgICAgICAgICAgYXBwZW5kZWRJY29uID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgICAgICAgY29uc3QgdGV4dCA9IG5vZGUudGV4dENvbnRlbnQ7XG5cbiAgICAgICAgICAgICAgICBpZiAodGV4dD8udHJpbSgpKSB7XG4gICAgICAgICAgICAgICAgICBleHRyYWN0SW5uZXJUZXh0KG5vZGUsIHN0YWNrLCBpY29uU2VsZWN0b3IpO1xuICAgICAgICAgICAgICAgICAgYXBwZW5kZWRJY29uID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICBjb25zdCB0ZXh0ID0gbm9kZS50ZXh0Q29udGVudDtcblxuICAgICAgICAgICAgICBpZiAodGV4dD8udHJpbSgpKSB7XG4gICAgICAgICAgICAgICAgZXh0cmFjdElubmVyVGV4dChfbm9kZSwgc3RhY2ssIGljb25TZWxlY3Rvcik7XG4gICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgICB9KTtcbiAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cbiAgICAgIH1cblxuICAgICAgaWYgKGNvbnRhaW5lck9mTm9kZS5xdWVyeVNlbGVjdG9yKGVtYmVkU2VsZWN0b3IpKSB7XG4gICAgICAgIGFwcGVuZE5ld1RleHQgPSB0cnVlO1xuICAgICAgICBleHRyYWN0SW5uZXJUZXh0KF9ub2RlLCBzdGFjaywgZW1iZWRTZWxlY3Rvcik7XG4gICAgICAgIHN0YWNrLmFwcGVuZChfbm9kZSwgeyB0eXBlOiBcImVtYmVkXCIgfSk7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cblxuICAgICAgaWYgKGFwcGVuZE5ld1RleHQpIHtcbiAgICAgICAgYXBwZW5kTmV3VGV4dCA9IGZhbHNlO1xuICAgICAgICBzdGFjay5hcHBlbmQoX25vZGUsIHsgdHlwZTogXCJ0ZXh0XCIgfSk7XG4gICAgICB9IGVsc2Uge1xuICAgICAgICBzdGFjay5zZXQoX25vZGUsIHsgdHlwZTogXCJ0ZXh0XCIgfSk7XG4gICAgICB9XG4gICAgfSBlbHNlIHtcbiAgICAgIHN0YWNrLmFwcGVuZChfbm9kZSwgeyB0eXBlOiBcInRleHRcIiB9KTtcbiAgICB9XG4gIH0pO1xuXG4gIGNvbnN0IGFsbEVsZW1lbnRzID0gc3RhY2suZ2V0QWxsKCk7XG5cbiAgYWxsRWxlbWVudHMuZm9yRWFjaCgobm9kZSkgPT4ge1xuICAgIGNvbnRhaW5lci5hcHBlbmQobm9kZSk7XG4gIH0pO1xuXG4gIHBhcmVudE5vZGUucGFyZW50RWxlbWVudD8uYXBwZW5kKGNvbnRhaW5lcik7XG5cbiAgY29uc3QgZGVzdHJveSA9ICgpID0+IHtcbiAgICBjb250YWluZXIucmVtb3ZlKCk7XG4gIH07XG5cbiAgcmV0dXJuIHsgY29udGFpbmVyLCBkZXN0cm95IH07XG59O1xuIiwgImltcG9ydCB7IEVsZW1lbnRNb2RlbCwgRW1iZWRNb2RlbCwgRW50cnksIE91dHB1dCB9IGZyb20gXCIuLi90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBjcmVhdGVEYXRhIH0gZnJvbSBcIi4uL3V0aWxzL2dldERhdGFcIjtcbmltcG9ydCB7IGdldERhdGFCeUVudHJ5IH0gZnJvbSBcIi4uL3V0aWxzL2dldERhdGFCeUVudHJ5XCI7XG5pbXBvcnQgeyBnZXRCdXR0b25Nb2RlbCB9IGZyb20gXCIuL21vZGVscy9CdXR0b25cIjtcbmltcG9ydCB7IGdldEVtYmVkTW9kZWwgfSBmcm9tIFwiLi9tb2RlbHMvRW1iZWRcIjtcbmltcG9ydCB7IGdldEljb25Nb2RlbCB9IGZyb20gXCIuL21vZGVscy9JY29uXCI7XG5pbXBvcnQgeyBnZXRUZXh0TW9kZWwgfSBmcm9tIFwiLi9tb2RlbHMvVGV4dFwiO1xuaW1wb3J0IHsgZ2V0Q29udGFpbmVyU3RhY2tXaXRoTm9kZXMgfSBmcm9tIFwiLi91dGlscy9kb20vZ2V0Q29udGFpbmVyU3RhY2tXaXRoTm9kZXNcIjtcblxudHlwZSBUZXh0TW9kZWwgPSBFbGVtZW50TW9kZWwgfCBFbWJlZE1vZGVsO1xuXG5leHBvcnQgY29uc3QgZ2V0VGV4dCA9IChfZW50cnk6IEVudHJ5KTogT3V0cHV0ID0+IHtcbiAgY29uc3QgZW50cnkgPSB3aW5kb3cuaXNEZXYgPyBnZXREYXRhQnlFbnRyeShfZW50cnkpIDogX2VudHJ5O1xuXG4gIGNvbnN0IHsgc2VsZWN0b3IgfSA9IGVudHJ5O1xuXG4gIGxldCBub2RlID0gc2VsZWN0b3IgPyBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHNlbGVjdG9yKSA6IHVuZGVmaW5lZDtcblxuICBpZiAoIW5vZGUpIHtcbiAgICByZXR1cm4ge1xuICAgICAgZXJyb3I6IGBFbGVtZW50IHdpdGggc2VsZWN0b3IgJHtzZWxlY3Rvcn0gbm90IGZvdW5kYFxuICAgIH07XG4gIH1cblxuICBub2RlID0gbm9kZS5jaGlsZHJlblswXTtcblxuICBpZiAoIW5vZGUpIHtcbiAgICByZXR1cm4ge1xuICAgICAgZXJyb3I6IGBFbGVtZW50IHdpdGggc2VsZWN0b3IgJHtlbnRyeS5zZWxlY3Rvcn0gaGFzIG5vIHdyYXBwZXJgXG4gICAgfTtcbiAgfVxuXG4gIGNvbnN0IGRhdGE6IEFycmF5PFRleHRNb2RlbD4gPSBbXTtcblxuICBjb25zdCB7IGNvbnRhaW5lciwgZGVzdHJveSB9ID0gZ2V0Q29udGFpbmVyU3RhY2tXaXRoTm9kZXMobm9kZSk7XG4gIGNvbnN0IGNvbnRhaW5lckNoaWxkcmVuID0gQXJyYXkuZnJvbShjb250YWluZXIuY2hpbGRyZW4pO1xuXG4gIGNvbnRhaW5lckNoaWxkcmVuLmZvckVhY2goKG5vZGUpID0+IHtcbiAgICBpZiAobm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSB7XG4gICAgICBzd2l0Y2ggKG5vZGUuZGF0YXNldC50eXBlKSB7XG4gICAgICAgIGNhc2UgXCJ0ZXh0XCI6IHtcbiAgICAgICAgICBjb25zdCBtb2RlbCA9IGdldFRleHRNb2RlbCh7IC4uLmVudHJ5LCBub2RlIH0pO1xuICAgICAgICAgIGRhdGEucHVzaChtb2RlbCk7XG4gICAgICAgICAgYnJlYWs7XG4gICAgICAgIH1cbiAgICAgICAgY2FzZSBcImJ1dHRvblwiOiB7XG4gICAgICAgICAgY29uc3QgbW9kZWxzID0gZ2V0QnV0dG9uTW9kZWwobm9kZSwgZW50cnkudXJsTWFwKTtcbiAgICAgICAgICBkYXRhLnB1c2goLi4ubW9kZWxzKTtcbiAgICAgICAgICBicmVhaztcbiAgICAgICAgfVxuICAgICAgICBjYXNlIFwiZW1iZWRcIjoge1xuICAgICAgICAgIGNvbnN0IG1vZGVscyA9IGdldEVtYmVkTW9kZWwobm9kZSk7XG4gICAgICAgICAgZGF0YS5wdXNoKC4uLm1vZGVscyk7XG4gICAgICAgICAgYnJlYWs7XG4gICAgICAgIH1cbiAgICAgICAgY2FzZSBcImljb25cIjoge1xuICAgICAgICAgIGNvbnN0IG1vZGVscyA9IGdldEljb25Nb2RlbChub2RlLCBlbnRyeS51cmxNYXApO1xuICAgICAgICAgIGRhdGEucHVzaCguLi5tb2RlbHMpO1xuICAgICAgICAgIGJyZWFrO1xuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxuICB9KTtcblxuICBkZXN0cm95KCk7XG5cbiAgcmV0dXJuIGNyZWF0ZURhdGEoeyBkYXRhIH0pO1xufTtcbiIsICJleHBvcnQgeyBnZXRUZXh0IGFzIHJ1biB9IGZyb20gXCJlbGVtZW50cy9zcmMvVGV4dFwiO1xuIiwgImltcG9ydCB7IHJ1biBhcyBnZXRBY2NvcmRpb24gfSBmcm9tIFwiLi9BY2NvcmRpb25cIjtcbmltcG9ydCB7IERvbSB9IGZyb20gXCIuL0RvbVwiO1xuaW1wb3J0IHsgcnVuIGFzIGdsb2JhbE1lbnVFeHRyYWN0b3IgfSBmcm9tIFwiLi9HbG9iYWxNZW51XCI7XG5pbXBvcnQgeyBydW4gYXMgZ2V0SW1hZ2UgfSBmcm9tIFwiLi9JbWFnZVwiO1xuaW1wb3J0IHsgcnVuIGFzIGdldE1lbnUgfSBmcm9tIFwiLi9NZW51XCI7XG5pbXBvcnQge1xuICBhdHRyaWJ1dGVSdW4gYXMgZ2V0QXR0cmlidXRlcyxcbiAgcnVuIGFzIGdldFN0eWxlc1xufSBmcm9tIFwiLi9TdHlsZUV4dHJhY3RvclwiO1xuaW1wb3J0IHsgcnVuIGFzIGdldFRhYnMgfSBmcm9tIFwiLi9UYWJzXCI7XG5pbXBvcnQgeyBydW4gYXMgZ2V0VGV4dCB9IGZyb20gXCIuL1RleHRcIjtcblxud2luZG93LmJyaXp5ID0ge1xuICBnbG9iYWxNZW51RXh0cmFjdG9yLFxuICBnZXRNZW51LFxuICBnZXRTdHlsZXMsXG4gIGdldEF0dHJpYnV0ZXMsXG4gIGdldFRleHQsXG4gIGdldEltYWdlLFxuICBnZXRBY2NvcmRpb24sXG4gIGdldFRhYnMsXG4gIGRvbTogRG9tXG59O1xuIl0sCiAgIm1hcHBpbmdzIjogIjs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUFBQSxNQWNhO0FBZGI7QUFBQTtBQUFBO0FBY08sTUFBTSxpQkFBaUIsQ0FBQyxVQUEwQjtBQUN2RCxjQUFNO0FBQUEsVUFDSjtBQUFBLFVBQ0E7QUFBQSxVQUNBO0FBQUEsVUFDQTtBQUFBLFVBQ0E7QUFBQSxVQUNBO0FBQUEsVUFDQTtBQUFBLFVBQ0E7QUFBQSxRQUNGLElBQUksU0FBUyxDQUFDO0FBRWQsZUFBTyxPQUFPLFFBQ1Y7QUFBQSxVQUNFLFVBQVUsQ0FBQztBQUFBLFVBQ1gsZUFBZTtBQUFBLFVBQ2YsR0FBSSxrQkFBa0IsRUFBRSxpQkFBaUIsQ0FBQyxFQUFFLEVBQUUsSUFBSSxDQUFDO0FBQUEsVUFDbkQsR0FBSSxXQUFXLEVBQUUsVUFBVSxhQUFhLE9BQU8sU0FBUyxLQUFLLElBQUksQ0FBQztBQUFBLFVBQ2xFLEdBQUksT0FBTyxFQUFFLE1BQU0sT0FBVSxJQUFJLENBQUM7QUFBQSxVQUNsQyxHQUFJLE1BQU0sRUFBRSxLQUFLLE9BQVUsSUFBSSxDQUFDO0FBQUEsVUFDaEMsR0FBSSxlQUFlLEVBQUUsY0FBYyxHQUFHLElBQUksQ0FBQztBQUFBLFVBQzNDLEdBQUksa0JBQWtCLEVBQUUsaUJBQWlCLEdBQUcsSUFBSSxDQUFDO0FBQUEsVUFDakQsR0FBSSxrQkFBa0IsRUFBRSxpQkFBaUIsR0FBRyxJQUFJLENBQUM7QUFBQSxVQUNqRCxHQUFJLGlCQUFpQixFQUFFLGdCQUFnQixDQUFDLEVBQUUsRUFBRSxJQUFJLENBQUM7QUFBQSxVQUNqRCxRQUFRLENBQUM7QUFBQSxRQUNYLElBQ0E7QUFBQSxNQUNOO0FBQUE7QUFBQTs7O0FDbEJBLFdBQVMsU0FBUyxPQUFpRDtBQUNqRSxVQUFNLFVBQVUsU0FBUyxLQUFLLEtBQUs7QUFFbkMsUUFBSSxT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QUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFFYixZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUVoQixjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFFZCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUVuQixhQUFhO0FBQUEsUUFFYixpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFFZixpQkFBaUI7QUFBQSxRQUVqQixnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUVsQixjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxvQkFBb0I7QUFBQSxRQUNwQixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUVqQixlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxvQkFBb0I7QUFBQSxRQUNwQixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUVoQixjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUVoQixrQkFBa0I7QUFBQSxRQUVsQixpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxzQkFBc0I7QUFBQSxRQUN0QixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCx1Q0FBdUM7QUFBQSxRQUN2QyxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCwrQkFBK0I7QUFBQSxRQUMvQixPQUFPO0FBQUEsUUFFUCxJQUFJO0FBQUEsUUFDSixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxxQkFBcUI7QUFBQSxRQUNyQixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxzQkFBc0I7QUFBQSxRQUN0QixPQUFPO0FBQUEsUUFFUCx1QkFBdUI7QUFBQSxRQUN2QixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FB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UUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxzQkFBc0I7QUFBQSxRQUN0QixPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxzQkFBc0I7QUFBQSxRQUN0QixPQUFPO0FBQUEsUUFFUCx3QkFBd0I7QUFBQSxRQUN4QixPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxzQkFBc0I7QUFBQSxRQUN0QixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUVuQixRQUFRO0FBQUEsUUFFUixLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxxQkFBcUI7QUFBQSxRQUNyQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxxQkFBcUI7QUFBQSxRQUNyQixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixP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QUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxvQkFBb0I7QUFBQSxRQUNwQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCx1QkFBdUI7QUFBQSxRQUN2QixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxJQUFJO0FBQUEsUUFDSixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxvQkFBb0I7QUFBQSxRQUNwQixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxvQkFBb0I7QUFBQSxRQUNwQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxvQkFBb0I7QUFBQSxRQUNwQixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxvQkFBb0I7QUFBQSxRQUNwQixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFFVCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxhQUFhO0FBQUEsUUFDYixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxlQUFlO0FBQUEsUUFDZixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEs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QSxRQUNoQixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxLQUFLO0FBQUEsUUFDTCxPQUFPO0FBQUEsUUFFUCxJQUFJO0FBQUEsUUFDSixPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxrQkFBa0I7QUFBQSxRQUNsQixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxvQkFBb0I7QUFBQSxRQUNwQixPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFFUCxNQUFNO0FBQUEsUUFDTixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxpQkFBaUI7QUFBQSxRQUNqQixPQUFPO0FBQUEsUUFFUCxVQUFVO0FBQUEsUUFDVixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxjQUFjO0FBQUEsUUFDZCxPQUFPO0FBQUEsUUFDUCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFFUCxtQkFBbUI7QUFBQSxRQUNuQixPQUFPO0FBQUEsUUFFUCxZQUFZO0FBQUEsUUFDWixPQUFPO0FBQUEsUUFFUCxnQkFBZ0I7QUFBQSxRQUNoQixPQUFPO0FBQUEsUUFFUCxTQUFTO0FBQUEsUUFDVCxPQUFPO0FBQUEsUUFFUCxRQUFRO0FBQUEsUUFDUixPQUFPO0FBQUEsUUFFUCxXQUFXO0FBQUEsUUFDWCxPQUFPO0FBQUEsUUFF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Y0FDRjtBQUFBLGNBQ0EsS0FBSyxTQUFTO0FBQ1osc0JBQU0sU0FBUyxjQUFjQSxLQUFJO0FBQ2pDLHFCQUFLLEtBQUssR0FBRyxNQUFNO0FBQ25CO0FBQUEsY0FDRjtBQUFBLGNBQ0EsS0FBSyxRQUFRO0FBQ1gsc0JBQU0sU0FBUyxhQUFhQSxPQUFNLE1BQU0sTUFBTTtBQUM5QyxxQkFBSyxLQUFLLEdBQUcsTUFBTTtBQUNuQjtBQUFBLGNBQ0Y7QUFBQSxZQUNGO0FBQUEsVUFDRjtBQUFBLFFBQ0YsQ0FBQztBQUVELGdCQUFRO0FBRVIsZUFBTyxXQUFXLEVBQUUsS0FBSyxDQUFDO0FBQUEsTUFDNUI7QUFBQTtBQUFBOzs7QUNuRUEsTUFBQUMsYUFBQTtBQUFBO0FBQUE7QUFBQSxNQUFBQTtBQUFBO0FBQUE7OztBQ0FBO0FBQUE7QUFBQSxNQUFBQztBQUNBO0FBQ0E7QUFDQSxNQUFBQztBQUNBO0FBQ0EsTUFBQUM7QUFJQSxNQUFBQztBQUNBLE1BQUFDO0FBRUEsYUFBTyxRQUFRO0FBQUEsUUFDYjtBQUFBLFFBQ0EsU0FBQUM7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0EsU0FBQUM7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBLEtBQUs7QUFBQSxNQUNQO0FBQUE7QUFBQTsiLAogICJuYW1lcyI6IFsidiIsICJ2IiwgInYiLCAiaW5pdF9BY2NvcmRpb24iLCAiaW5pdF9JbWFnZSIsICJ2IiwgImdldE1vZGVsIiwgImluaXRfZ2V0TW9kZWwiLCAidiIsICJwcmVmaXgiLCAid2FybnMiLCAicnVuIiwgImluaXRfZ2V0TW9kZWwiLCAidiIsICJnZXRNb2RlbCIsICJpbml0X1N0eWxlRXh0cmFjdG9yIiwgInYiLCAiZ2V0TW9kZWwiLCAiaW5pdF9nZXRNb2RlbCIsICJ3YXJucyIsICJpbml0X2dldE1vZGVsIiwgInYiLCAiZ2V0TW9kZWwiLCAiaW5pdF9UYWJzIiwgImFscGhhYmV0IiwgInYiLCAibVBpcGUiLCAiX2EiLCAiX2kiLCAidiIsICJ2IiwgIl9pIiwgInYiLCAiZXhwb3J0cyIsICJyZWFkIiwgInYiLCAicmVhZCIsICJub2RlIiwgImdldE1vZGVsIiwgImltcG9ydF9mcF91dGlsaXRpZXMiLCAiaW5pdF9nZXRNb2RlbCIsICJyZWFkIiwgInYiLCAidiIsICJ2IiwgImltcG9ydF9mcF91dGlsaXRpZXMiLCAiZ2V0Q29sb3IiLCAiZ2V0QmdDb2xvciIsICJnZXRTdHlsZU1vZGVsIiwgImdldE1vZGVsIiwgImluaXRfZ2V0TW9kZWwiLCAicmVhZCIsICJnZXRNb2RlbCIsICJpbml0X2dldE1vZGVsIiwgImdldE1vZGVsIiwgImluaXRfZ2V0TW9kZWwiLCAiYWxsb3dlZFRhZ3MiLCAicGFyZW50U3R5bGUiLCAibm9kZSIsICJjb250YWluZXIiLCAiZ2V0VGV4dCIsICJpbml0X1RleHQiLCAibm9kZSIsICJpbml0X1RleHQiLCAiaW5pdF9BY2NvcmRpb24iLCAiaW5pdF9JbWFnZSIsICJpbml0X1N0eWxlRXh0cmFjdG9yIiwgImluaXRfVGFicyIsICJpbml0X1RleHQiLCAicnVuIiwgImdldFRleHQiXQp9Cg==
