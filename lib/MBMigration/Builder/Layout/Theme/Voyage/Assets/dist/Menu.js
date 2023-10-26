"use strict";
var scripts;
(scripts ||= {}).Menu = (() => {
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
        selector: "{{selector}}",
        families: JSON.parse("{{families}}"),
        defaultFamily: "{{defaultFamily}}"
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
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vc3JjL01lbnUvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2NvbG9yL3JnYmFUb0hleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvZG9tL2dldE5vZGVTdHlsZS50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvdGV4dC9jYXBpdGFsaXplLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy90ZXh0L3RvQ2FtZWxDYXNlLnRzIiwgIi4uL3NyYy9NZW51L21vZGVsL2dldE1vZGVsLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy91dGlscy9nZXREYXRhLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9tb2RlbHMvcHJlZml4ZWQudHMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImltcG9ydCB7IGdldE1vZGVsIH0gZnJvbSBcIi4vbW9kZWwvZ2V0TW9kZWxcIjtcbmltcG9ydCB7IEVudHJ5LCBPdXRwdXQgfSBmcm9tIFwiZWxlbWVudHMvc3JjL3R5cGVzL3R5cGVcIjtcbmltcG9ydCB7IGNyZWF0ZURhdGEsIGdldERhdGEgfSBmcm9tIFwiZWxlbWVudHMvc3JjL3V0aWxzL2dldERhdGFcIjtcbmltcG9ydCB7IHJnYlRvSGV4IH0gZnJvbSBcInV0aWxzL3NyYy9jb2xvci9yZ2JhVG9IZXhcIjtcbmltcG9ydCB7IHByZWZpeGVkIH0gZnJvbSBcInV0aWxzL3NyYy9tb2RlbHMvcHJlZml4ZWRcIjtcblxuaW50ZXJmYWNlIE5hdkRhdGEge1xuICBuYXY6IEVsZW1lbnQ7XG4gIGhlYWRlcjogRWxlbWVudDtcbiAgc3ViTmF2PzogRWxlbWVudDtcbiAgc2VsZWN0b3I6IHN0cmluZztcbiAgZmFtaWxpZXM6IFJlY29yZDxzdHJpbmcsIHN0cmluZz47XG4gIGRlZmF1bHRGYW1pbHk6IHN0cmluZztcbn1cblxubGV0IHdhcm5zOiBSZWNvcmQ8c3RyaW5nLCBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+PiA9IHt9O1xuXG5jb25zdCBnZXRNZW51ViA9IChkYXRhOiBOYXZEYXRhKSA9PiB7XG4gIGNvbnN0IHsgbmF2LCBzZWxlY3RvciB9ID0gZGF0YTtcbiAgY29uc3QgdWwgPSBuYXYuY2hpbGRyZW5bMF07XG4gIGxldCB2ID0ge307XG5cbiAgaWYgKCF1bCkge1xuICAgIHdhcm5zW1wibWVudVwiXSA9IHtcbiAgICAgIG1lc3NhZ2U6IGBOYXZpZ2F0aW9uIGRvbid0IGhhdmUgdWwgaW4gJHtzZWxlY3Rvcn1gXG4gICAgfTtcbiAgICByZXR1cm4gdjtcbiAgfVxuXG4gIGNvbnN0IGxpID0gdWwucXVlcnlTZWxlY3RvcihcImxpXCIpO1xuICBpZiAoIWxpKSB7XG4gICAgd2FybnNbXCJtZW51IGxpXCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCA+IGxpIGluICR7c2VsZWN0b3J9YFxuICAgIH07XG4gICAgcmV0dXJuIHY7XG4gIH1cblxuICBjb25zdCBsaW5rID0gdWwucXVlcnlTZWxlY3RvcjxIVE1MRWxlbWVudD4oXCJsaSA+IGFcIik7XG4gIGlmICghbGluaykge1xuICAgIHdhcm5zW1wibWVudSBsaSBhXCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCA+IGxpID4gYSBpbiAke3NlbGVjdG9yfWBcbiAgICB9O1xuICAgIHJldHVybiB2O1xuICB9XG5cbiAgdiA9IGdldE1vZGVsKHtcbiAgICBub2RlOiBsaW5rLFxuICAgIGZhbWlsaWVzOiBkYXRhLmZhbWlsaWVzLFxuICAgIGRlZmF1bHRGYW1pbHk6IGRhdGEuZGVmYXVsdEZhbWlseVxuICB9KTtcblxuICByZXR1cm4geyAuLi52LCBpdGVtUGFkZGluZzogMjAgfTtcbn07XG5cbmNvbnN0IGdldFN1Yk1lbnVWID0gKGRhdGE6IFJlcXVpcmVkPE5hdkRhdGE+KSA9PiB7XG4gIGNvbnN0IHsgc3ViTmF2OiB1bCwgaGVhZGVyLCBzZWxlY3RvciB9ID0gZGF0YTtcblxuICBsZXQgdiA9IHt9O1xuXG4gIGNvbnN0IGxpID0gdWwucXVlcnlTZWxlY3RvcihcImxpXCIpO1xuICBpZiAoIWxpKSB7XG4gICAgd2FybnNbXCJzdWJtZW51IGxpXCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCA+IGxpIGluICR7c2VsZWN0b3J9YFxuICAgIH07XG4gICAgcmV0dXJuIHY7XG4gIH1cblxuICBjb25zdCBsaW5rID0gdWwucXVlcnlTZWxlY3RvcjxIVE1MRWxlbWVudD4oXCJsaSA+IGFcIik7XG4gIGlmICghbGluaykge1xuICAgIHdhcm5zW1wic3VibWVudSBsaSBhXCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCA+IGxpID4gYSBpbiAke3NlbGVjdG9yfWBcbiAgICB9O1xuICAgIHJldHVybiB2O1xuICB9XG5cbiAgY29uc3QgdHlwb2dyYXBoeSA9IGdldE1vZGVsKHtcbiAgICBub2RlOiBsaW5rLFxuICAgIGZhbWlsaWVzOiBkYXRhLmZhbWlsaWVzLFxuICAgIGRlZmF1bHRGYW1pbHk6IGRhdGEuZGVmYXVsdEZhbWlseVxuICB9KTtcbiAgY29uc3Qgc3VibWVudVR5cG9ncmFwaHkgPSBwcmVmaXhlZCh0eXBvZ3JhcGh5LCBcInN1Yk1lbnVcIik7XG4gIGNvbnN0IGJhc2VTdHlsZSA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKGhlYWRlcik7XG4gIGNvbnN0IGJnQ29sb3IgPSByZ2JUb0hleChiYXNlU3R5bGUuYmFja2dyb3VuZENvbG9yKSA/PyBcIiNmZmZmZmZcIjtcblxuICByZXR1cm4ge1xuICAgIC4uLnN1Ym1lbnVUeXBvZ3JhcGh5LFxuICAgIHN1Yk1lbnVCZ0NvbG9yT3BhY2l0eTogMSxcbiAgICBzdWJNZW51QmdDb2xvckhleDogYmdDb2xvclxuICB9O1xufTtcblxuY29uc3QgZ2V0TmF2U3R5bGVzID0gKGRhdGE6IE5hdkRhdGEpID0+IHtcbiAgY29uc3QgeyBzdWJOYXYgfSA9IGRhdGE7XG4gIGxldCBtZW51ViA9IGdldE1lbnVWKGRhdGEpO1xuXG4gIGlmIChzdWJOYXYpIHtcbiAgICBjb25zdCBfdiA9IGdldFN1Yk1lbnVWKHsgLi4uZGF0YSwgc3ViTmF2IH0pO1xuICAgIG1lbnVWID0geyAuLi5tZW51ViwgLi4uX3YgfTtcbiAgfVxuXG4gIHJldHVybiBtZW51Vjtcbn07XG5cbmNvbnN0IHJ1biA9IChkYXRhOiBFbnRyeSk6IE91dHB1dCA9PiB7XG4gIGNvbnN0IG5vZGUgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKGRhdGEuc2VsZWN0b3IpO1xuXG4gIGlmICghbm9kZSkge1xuICAgIHJldHVybiBKU09OLnN0cmluZ2lmeSh7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke2RhdGEuc2VsZWN0b3J9IG5vdCBmb3VuZGAsXG4gICAgICB3YXJuczogd2FybnNcbiAgICB9KTtcbiAgfVxuXG4gIGNvbnN0IGhlYWRlciA9IG5vZGU7XG5cbiAgaWYgKCFoZWFkZXIpIHtcbiAgICByZXR1cm4gSlNPTi5zdHJpbmdpZnkoe1xuICAgICAgZXJyb3I6IGBFbGVtZW50IHdpdGggc2VsZWN0b3IgJHtkYXRhLnNlbGVjdG9yfSBoYXMgbm8gaGVhZGVyYCxcbiAgICAgIHdhcm5zXG4gICAgfSk7XG4gIH1cblxuICBjb25zdCBuYXYgPSBoZWFkZXIucXVlcnlTZWxlY3RvcihcIiNtYWluLW5hdmlnYXRpb25cIik7XG5cbiAgaWYgKCFuYXYpIHtcbiAgICByZXR1cm4gSlNPTi5zdHJpbmdpZnkoe1xuICAgICAgZXJyb3I6IGBFbGVtZW50IHdpdGggc2VsZWN0b3IgJHtkYXRhLnNlbGVjdG9yfSBoYXMgbm8gbmF2YCxcbiAgICAgIHdhcm5zXG4gICAgfSk7XG4gIH1cblxuICBjb25zdCBzdWJOYXYgPSBoZWFkZXIucXVlcnlTZWxlY3RvcihcIi5zdWItbmF2aWdhdGlvblwiKSA/PyB1bmRlZmluZWQ7XG5cbiAgY29uc3QgbmF2RGF0YSA9IHtcbiAgICBoZWFkZXIsXG4gICAgbmF2OiBuYXYsXG4gICAgc3ViTmF2OiBzdWJOYXYsXG4gICAgc2VsZWN0b3I6IGRhdGEuc2VsZWN0b3IsXG4gICAgZmFtaWxpZXM6IGRhdGEuZmFtaWxpZXMsXG4gICAgZGVmYXVsdEZhbWlseTogZGF0YS5kZWZhdWx0RmFtaWx5XG4gIH07XG5cbiAgcmV0dXJuIGNyZWF0ZURhdGEoeyBkYXRhOiBnZXROYXZTdHlsZXMobmF2RGF0YSksIHdhcm5zIH0pO1xufTtcblxuY29uc3QgZGF0YSA9IGdldERhdGEoKTtcbmNvbnN0IG91dHB1dCA9IHJ1bihkYXRhKTtcblxuZXhwb3J0IGRlZmF1bHQgb3V0cHV0O1xuIiwgImltcG9ydCB7IE1WYWx1ZSB9IGZyb20gXCJAL3R5cGVzXCI7XG5cbmZ1bmN0aW9uIF9yZ2JUb0hleChyOiBudW1iZXIsIGc6IG51bWJlciwgYjogbnVtYmVyKTogc3RyaW5nIHtcbiAgciA9IE1hdGgubWluKDI1NSwgTWF0aC5tYXgoMCwgTWF0aC5yb3VuZChyKSkpO1xuICBnID0gTWF0aC5taW4oMjU1LCBNYXRoLm1heCgwLCBNYXRoLnJvdW5kKGcpKSk7XG4gIGIgPSBNYXRoLm1pbigyNTUsIE1hdGgubWF4KDAsIE1hdGgucm91bmQoYikpKTtcblxuICBjb25zdCBoZXhSID0gci50b1N0cmluZygxNikucGFkU3RhcnQoMiwgXCIwXCIpO1xuICBjb25zdCBoZXhHID0gZy50b1N0cmluZygxNikucGFkU3RhcnQoMiwgXCIwXCIpO1xuICBjb25zdCBoZXhCID0gYi50b1N0cmluZygxNikucGFkU3RhcnQoMiwgXCIwXCIpO1xuXG4gIHJldHVybiBgIyR7aGV4Un0ke2hleEd9JHtoZXhCfWAudG9VcHBlckNhc2UoKTtcbn1cblxuZXhwb3J0IGNvbnN0IHJnYlRvSGV4ID0gKHJnYmE6IHN0cmluZyk6IE1WYWx1ZTxzdHJpbmc+ID0+IHtcbiAgY29uc3QgcmdiVmFsdWVzID0gcmdiYVxuICAgIC5zbGljZSg0LCAtMSlcbiAgICAuc3BsaXQoXCIsXCIpXG4gICAgLm1hcCgodmFsdWUpID0+IHBhcnNlSW50KHZhbHVlLnRyaW0oKSkpO1xuXG4gIGlmIChyZ2JWYWx1ZXMubGVuZ3RoICE9PSAzKSB7XG4gICAgcmV0dXJuIHVuZGVmaW5lZDtcbiAgfVxuXG4gIHJldHVybiBfcmdiVG9IZXgocmdiVmFsdWVzWzBdLCByZ2JWYWx1ZXNbMV0sIHJnYlZhbHVlc1syXSk7XG59O1xuIiwgImltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwiQC90eXBlc1wiO1xuXG5leHBvcnQgY29uc3QgZ2V0Tm9kZVN0eWxlID0gKFxuICBub2RlOiBIVE1MRWxlbWVudCB8IEVsZW1lbnRcbik6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+ID0+IHtcbiAgY29uc3QgY29tcHV0ZWRTdHlsZXMgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShub2RlKTtcbiAgY29uc3Qgc3R5bGVzOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPiA9IHt9O1xuXG4gIE9iamVjdC52YWx1ZXMoY29tcHV0ZWRTdHlsZXMpLmZvckVhY2goKGtleSkgPT4ge1xuICAgIHN0eWxlc1trZXldID0gY29tcHV0ZWRTdHlsZXMuZ2V0UHJvcGVydHlWYWx1ZShrZXkpO1xuICB9KTtcblxuICByZXR1cm4gc3R5bGVzO1xufTtcbiIsICJleHBvcnQgY29uc3QgY2FwaXRhbGl6ZSA9IChzdHI6IHN0cmluZyk6IHN0cmluZyA9PiB7XG4gIHJldHVybiBzdHIuY2hhckF0KDApLnRvVXBwZXJDYXNlKCkgKyBzdHIuc2xpY2UoMSk7XG59O1xuIiwgImltcG9ydCB7IGNhcGl0YWxpemUgfSBmcm9tIFwiLi9jYXBpdGFsaXplXCI7XG5cbmV4cG9ydCBjb25zdCB0b0NhbWVsQ2FzZSA9IChrZXk6IHN0cmluZyk6IHN0cmluZyA9PiB7XG4gIGNvbnN0IHBhcnRzID0ga2V5LnNwbGl0KFwiLVwiKTtcbiAgZm9yIChsZXQgaSA9IDE7IGkgPCBwYXJ0cy5sZW5ndGg7IGkrKykge1xuICAgIHBhcnRzW2ldID0gY2FwaXRhbGl6ZShwYXJ0c1tpXSk7XG4gIH1cbiAgcmV0dXJuIHBhcnRzLmpvaW4oXCJcIik7XG59O1xuIiwgImltcG9ydCB7IHJnYlRvSGV4IH0gZnJvbSBcInV0aWxzL3NyYy9jb2xvci9yZ2JhVG9IZXhcIjtcbmltcG9ydCB7IGdldE5vZGVTdHlsZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldE5vZGVTdHlsZVwiO1xuaW1wb3J0IHsgdG9DYW1lbENhc2UgfSBmcm9tIFwidXRpbHMvc3JjL3RleHQvdG9DYW1lbENhc2VcIjtcblxuaW50ZXJmYWNlIE1vZGVsIHtcbiAgbm9kZTogSFRNTEVsZW1lbnQ7XG4gIGZhbWlsaWVzOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+O1xuICBkZWZhdWx0RmFtaWx5OiBzdHJpbmc7XG59XG5cbmNvbnN0IHYgPSB7XG4gIFwiZm9udC1mYW1pbHlcIjogdW5kZWZpbmVkLFxuICBcImZvbnQtZmFtaWx5LXR5cGVcIjogXCJ1cGxvYWRlZFwiLFxuICBcImZvbnQtd2VpZ2h0XCI6IHVuZGVmaW5lZCxcbiAgXCJmb250LXNpemVcIjogdW5kZWZpbmVkLFxuICBcImxpbmUtaGVpZ2h0XCI6IHVuZGVmaW5lZCxcbiAgXCJsZXR0ZXItc3BhY2luZ1wiOiB1bmRlZmluZWQsXG4gIGNvbG9ySGV4OiB1bmRlZmluZWQsXG4gIGNvbG9yT3BhY2l0eTogMSxcbiAgYWN0aXZlQ29sb3JIZXg6IHVuZGVmaW5lZCxcbiAgYWN0aXZlQ29sb3JPcGFjaXR5OiB1bmRlZmluZWRcbn07XG5cbmV4cG9ydCBjb25zdCBnZXRNb2RlbCA9IChkYXRhOiBNb2RlbCkgPT4ge1xuICBjb25zdCB7IG5vZGUsIGZhbWlsaWVzLCBkZWZhdWx0RmFtaWx5IH0gPSBkYXRhO1xuICBjb25zdCBzdHlsZXMgPSBnZXROb2RlU3R5bGUobm9kZSk7XG4gIGNvbnN0IGRpYzogUmVjb3JkPHN0cmluZywgc3RyaW5nIHwgbnVtYmVyPiA9IHt9O1xuXG4gIE9iamVjdC5rZXlzKHYpLmZvckVhY2goKGtleSkgPT4ge1xuICAgIHN3aXRjaCAoa2V5KSB7XG4gICAgICBjYXNlIFwiZm9udC1mYW1pbHlcIjoge1xuICAgICAgICBjb25zdCB2YWx1ZTogc3RyaW5nID0gc3R5bGVzW2tleV07XG4gICAgICAgIGNvbnN0IGZvbnRGYW1pbHkgPSB2YWx1ZVxuICAgICAgICAgIC5yZXBsYWNlKC9bJ1wiXFwsXS9nLCBcIlwiKVxuICAgICAgICAgIC5yZXBsYWNlKC9cXHMvZywgXCJfXCIpXG4gICAgICAgICAgLnRvTG9jYWxlTG93ZXJDYXNlKCk7XG5cbiAgICAgICAgaWYgKCFmYW1pbGllc1tmb250RmFtaWx5XSkge1xuICAgICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IGRlZmF1bHRGYW1pbHk7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gZmFtaWxpZXNbZm9udEZhbWlseV07XG4gICAgICAgIH1cbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiZm9udC1mYW1pbHktdHlwZVwiOiB7XG4gICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IFwidXBsb2FkXCI7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImxpbmUtaGVpZ2h0XCI6IHtcbiAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gMTtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiZm9udC1zaXplXCI6IHtcbiAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gcGFyc2VJbnQoc3R5bGVzW2tleV0pO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJsZXR0ZXItc3BhY2luZ1wiOiB7XG4gICAgICAgIGNvbnN0IHZhbHVlID0gc3R5bGVzW2tleV07XG4gICAgICAgIGlmICh2YWx1ZSA9PT0gXCJub3JtYWxcIikge1xuICAgICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IDA7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgLy8gUmVtb3ZlICdweCcgYW5kIGFueSBleHRyYSB3aGl0ZXNwYWNlXG4gICAgICAgICAgY29uc3QgbGV0dGVyU3BhY2luZ1ZhbHVlID0gdmFsdWUucmVwbGFjZSgvcHgvZywgXCJcIikudHJpbSgpO1xuICAgICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9ICtsZXR0ZXJTcGFjaW5nVmFsdWU7XG4gICAgICAgIH1cbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiY29sb3JIZXhcIjoge1xuICAgICAgICBjb25zdCB0b0hleCA9IHJnYlRvSGV4KHN0eWxlc1tcImNvbG9yXCJdKTtcbiAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gdG9IZXggPz8gXCIjMDAwMDAwXCI7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImNvbG9yT3BhY2l0eVwiOiB7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgZGVmYXVsdDoge1xuICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSBzdHlsZXNba2V5XTtcbiAgICAgIH1cbiAgICB9XG4gIH0pO1xuXG4gIHJldHVybiBkaWM7XG59O1xuIiwgImltcG9ydCB7IEVudHJ5LCBPdXRwdXQsIE91dHB1dERhdGEgfSBmcm9tIFwiQC90eXBlcy90eXBlXCI7XG5cbmV4cG9ydCBjb25zdCBnZXREYXRhID0gKCk6IEVudHJ5ID0+IHtcbiAgdHJ5IHtcbiAgICByZXR1cm4gd2luZG93LmlzRGV2XG4gICAgICA/IHtcbiAgICAgICAgICBzZWxlY3RvcjogYFtkYXRhLWlkPSckezE2NjMwMTMxfSddYCxcbiAgICAgICAgICBmYW1pbGllczoge1xuICAgICAgICAgICAgXCJwcm94aW1hX25vdmFfcHJveGltYV9ub3ZhX3JlZ3VsYXJfc2Fucy1zZXJpZlwiOiBcInVpZDExMTFcIixcbiAgICAgICAgICAgIFwiaGVsdmV0aWNhX25ldWVfaGVsdmV0aWNhbmV1ZV9oZWx2ZXRpY2FfYXJpYWxfc2Fucy1zZXJpZlwiOiBcInVpZDIyMjJcIlxuICAgICAgICAgIH0sXG4gICAgICAgICAgZGVmYXVsdEZhbWlseTogXCJsYXRvXCJcbiAgICAgICAgfVxuICAgICAgOiB7XG4gICAgICAgICAgc2VsZWN0b3I6IFwie3tzZWxlY3Rvcn19XCIsXG4gICAgICAgICAgZmFtaWxpZXM6IEpTT04ucGFyc2UoXCJ7e2ZhbWlsaWVzfX1cIiksXG4gICAgICAgICAgZGVmYXVsdEZhbWlseTogXCJ7e2RlZmF1bHRGYW1pbHl9fVwiXG4gICAgICAgIH07XG4gIH0gY2F0Y2ggKGUpIHtcbiAgICBjb25zdCBmYW1pbHlNb2NrID0ge1xuICAgICAgbGF0bzogXCJ1aWRfZm9yX2xhdG9cIixcbiAgICAgIHJvYm90bzogXCJ1aWRfZm9yX3JvYm90b1wiXG4gICAgfTtcbiAgICBjb25zdCBtb2NrOiBFbnRyeSA9IHtcbiAgICAgIHNlbGVjdG9yOiBcIi5teS1kaXZcIixcbiAgICAgIGZhbWlsaWVzOiBmYW1pbHlNb2NrLFxuICAgICAgZGVmYXVsdEZhbWlseTogXCJsYXRvXCJcbiAgICB9O1xuXG4gICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgSlNPTi5zdHJpbmdpZnkoe1xuICAgICAgICBlcnJvcjogYEludmFsaWQgSlNPTiAke2V9YCxcbiAgICAgICAgZGV0YWlsczogYE11c3QgYmU6ICR7SlNPTi5zdHJpbmdpZnkobW9jayl9YFxuICAgICAgfSlcbiAgICApO1xuICB9XG59O1xuXG5leHBvcnQgY29uc3QgY3JlYXRlRGF0YSA9IChvdXRwdXQ6IE91dHB1dERhdGEpOiBPdXRwdXQgPT4ge1xuICByZXR1cm4gSlNPTi5zdHJpbmdpZnkob3V0cHV0KTtcbn07XG4iLCAiaW1wb3J0IHsgY2FwaXRhbGl6ZSB9IGZyb20gXCJAL3RleHQvY2FwaXRhbGl6ZVwiO1xuXG5leHBvcnQgY29uc3QgcHJlZml4ZWQgPSA8VCBleHRlbmRzIFJlY29yZDxzdHJpbmcsIHVua25vd24+PihcbiAgdjogVCxcbiAgcHJlZml4OiBzdHJpbmdcbik6IFQgPT4ge1xuICByZXR1cm4gT2JqZWN0LmVudHJpZXModikucmVkdWNlKChhY2MsIFtrZXksIHZhbHVlXSkgPT4ge1xuICAgIGxldCBfa2V5ID0gcHJlZml4ICsgY2FwaXRhbGl6ZShrZXkpO1xuXG4gICAgaWYgKGtleS5zdGFydHNXaXRoKFwiYWN0aXZlXCIpKSB7XG4gICAgICBfa2V5ID0gYGFjdGl2ZSR7Y2FwaXRhbGl6ZShwcmVmaXgpfSR7a2V5LnJlcGxhY2UoXCJhY3RpdmVcIiwgXCJcIil9YDtcbiAgICB9XG5cbiAgICByZXR1cm4geyAuLi5hY2MsIFtfa2V5XTogdmFsdWUgfTtcbiAgfSwge30gYXMgVCk7XG59O1xuIl0sCiAgIm1hcHBpbmdzIjogIjs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQUFBO0FBQUE7QUFBQTtBQUFBOzs7QUNFQSxXQUFTLFVBQVUsR0FBVyxHQUFXLEdBQW1CO0FBQzFELFFBQUksS0FBSyxJQUFJLEtBQUssS0FBSyxJQUFJLEdBQUcsS0FBSyxNQUFNLENBQUMsQ0FBQyxDQUFDO0FBQzVDLFFBQUksS0FBSyxJQUFJLEtBQUssS0FBSyxJQUFJLEdBQUcsS0FBSyxNQUFNLENBQUMsQ0FBQyxDQUFDO0FBQzVDLFFBQUksS0FBSyxJQUFJLEtBQUssS0FBSyxJQUFJLEdBQUcsS0FBSyxNQUFNLENBQUMsQ0FBQyxDQUFDO0FBRTVDLFVBQU0sT0FBTyxFQUFFLFNBQVMsRUFBRSxFQUFFLFNBQVMsR0FBRyxHQUFHO0FBQzNDLFVBQU0sT0FBTyxFQUFFLFNBQVMsRUFBRSxFQUFFLFNBQVMsR0FBRyxHQUFHO0FBQzNDLFVBQU0sT0FBTyxFQUFFLFNBQVMsRUFBRSxFQUFFLFNBQVMsR0FBRyxHQUFHO0FBRTNDLFdBQU8sSUFBSSxJQUFJLEdBQUcsSUFBSSxHQUFHLElBQUksR0FBRyxZQUFZO0FBQUEsRUFDOUM7QUFFTyxNQUFNLFdBQVcsQ0FBQyxT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
