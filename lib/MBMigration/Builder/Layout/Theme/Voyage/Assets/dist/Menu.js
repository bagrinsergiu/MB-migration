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

  // src/Menu/index.ts
  var Menu_exports = {};
  __export(Menu_exports, {
    default: () => Menu_default
  });

  // ../../../../../../../packages/utils/src/color/rgbaToHex.ts
  function _rgbToHex(r, g, b) {
    r = Math.min(255, Math.max(0, Math.round(r)));
    g = Math.min(255, Math.max(0, Math.round(g)));
    b = Math.min(255, Math.max(0, Math.round(b)));
    const hexR = r.toString(16).padStart(2, "0");
    const hexG = g.toString(16).padStart(2, "0");
    const hexB = b.toString(16).padStart(2, "0");
    return `#${hexR}${hexG}${hexB}`.toUpperCase();
  }
  var rgbToHex = (rgba) => {
    const rgbValues = rgba.slice(4, -1).split(",").map((value) => parseInt(value.trim()));
    if (rgbValues.length !== 3) {
      return void 0;
    }
    return _rgbToHex(rgbValues[0], rgbValues[1], rgbValues[2]);
  };

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

  // src/Menu/model/getModel.ts
  var v = {
    "font-family": void 0,
    "font-family-type": "uploaded",
    "font-weight": void 0,
    "font-size": void 0,
    "line-height": void 0,
    "letter-spacing": void 0,
    colorHex: void 0,
    colorOpacity: 1,
    activeColorHex: void 0,
    activeColorOpacity: void 0
  };
  var getModel = (data2) => {
    const { node, families, defaultFamily } = data2;
    const styles = getNodeStyle(node);
    const dic = {};
    Object.keys(v).forEach((key) => {
      switch (key) {
        case "font-family": {
          const value = styles[key];
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
            const letterSpacingValue = value.replace(/px/g, "").trim();
            dic[toCamelCase(key)] = +letterSpacingValue;
          }
          break;
        }
        case "colorHex": {
          const toHex = rgbToHex(styles["color"]);
          dic[toCamelCase(key)] = toHex ?? "#000000";
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

  // ../../../../../../../packages/elements/src/utils/getData.ts
  var getData = () => {
    try {
      return window.isDev ? {
        selector: `[data-id='${16630131}']`,
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
    return JSON.stringify(output2);
  };

  // ../../../../../../../packages/utils/src/models/prefixed.ts
  var prefixed = (v2, prefix) => {
    return Object.entries(v2).reduce((acc, [key, value]) => {
      let _key = prefix + capitalize(key);
      if (key.startsWith("active")) {
        _key = `active${capitalize(prefix)}${key.replace("active", "")}`;
      }
      return { ...acc, [_key]: value };
    }, {});
  };

  // src/Menu/index.ts
  var warns = {};
  var getMenuV = (data2) => {
    const { nav, selector } = data2;
    const ul = nav.children[0];
    let v2 = {};
    if (!ul) {
      warns["menu"] = {
        message: `Navigation don't have ul in ${selector}`
      };
      return v2;
    }
    const li = ul.querySelector("li");
    if (!li) {
      warns["menu li"] = {
        message: `Navigation don't have ul > li in ${selector}`
      };
      return v2;
    }
    const link = ul.querySelector("li > a");
    if (!link) {
      warns["menu li a"] = {
        message: `Navigation don't have ul > li > a in ${selector}`
      };
      return v2;
    }
    v2 = getModel({
      node: link,
      families: data2.families,
      defaultFamily: data2.defaultFamily
    });
    return { ...v2, itemPadding: 20 };
  };
  var getSubMenuV = (data2) => {
    const { subNav: ul, header, selector } = data2;
    let v2 = {};
    const li = ul.querySelector("li");
    if (!li) {
      warns["submenu li"] = {
        message: `Navigation don't have ul > li in ${selector}`
      };
      return v2;
    }
    const link = ul.querySelector("li > a");
    if (!link) {
      warns["submenu li a"] = {
        message: `Navigation don't have ul > li > a in ${selector}`
      };
      return v2;
    }
    const typography = getModel({
      node: link,
      families: data2.families,
      defaultFamily: data2.defaultFamily
    });
    const submenuTypography = prefixed(typography, "subMenu");
    const baseStyle = window.getComputedStyle(header);
    const bgColor = rgbToHex(baseStyle.backgroundColor) ?? "#ffffff";
    return {
      ...submenuTypography,
      subMenuBgColorOpacity: 1,
      subMenuBgColorHex: bgColor
    };
  };
  var getNavStyles = (data2) => {
    const { subNav } = data2;
    let menuV = getMenuV(data2);
    if (subNav) {
      const _v = getSubMenuV({ ...data2, subNav });
      menuV = { ...menuV, ..._v };
    }
    return menuV;
  };
  var run = (data2) => {
    const node = document.querySelector(data2.selector);
    if (!node) {
      return JSON.stringify({
        error: `Element with selector ${data2.selector} not found`,
        warns
      });
    }
    const header = node;
    if (!header) {
      return JSON.stringify({
        error: `Element with selector ${data2.selector} has no header`,
        warns
      });
    }
    const nav = header.querySelector("#main-navigation");
    if (!nav) {
      return JSON.stringify({
        error: `Element with selector ${data2.selector} has no nav`,
        warns
      });
    }
    const subNav = header.querySelector(".sub-navigation") ?? void 0;
    const navData = {
      header,
      nav,
      subNav,
      selector: data2.selector,
      families: data2.families,
      defaultFamily: data2.defaultFamily
    };
    return createData({ data: getNavStyles(navData), warns });
  };
  var data = getData();
  var output = run(data);
  var Menu_default = output;
  return __toCommonJS(Menu_exports);
})();
