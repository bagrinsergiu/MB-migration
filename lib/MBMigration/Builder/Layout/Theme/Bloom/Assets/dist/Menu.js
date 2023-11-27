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
        hex: colorString,
        opacity: "1"
      };
    }
    const rgbResult = parseRgb(colorString);
    if (rgbResult) {
      return {
        hex: fromRgb(rgbResult),
        opacity: "1"
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
        case "line-height": {
          dic[toCamelCase(key)] = 1;
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

  // ../../../../../../../packages/elements/src/utils/getData.ts
  var getData = () => {
    try {
      return window.isDev ? {
        selector: `[data-id='${4341579}']`,
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
    const li = ul.querySelector("li");
    if (!li) {
      warns["submenu li"] = {
        message: `Navigation don't have ul > li in ${selector}`
      };
      return;
    }
    const link = ul.querySelector("li > a");
    if (!link) {
      warns["submenu li a"] = {
        message: `Navigation don't have ul > li > a in ${selector}`
      };
      return;
    }
    const typography = getModel({
      node: link,
      families: data2.families,
      defaultFamily: data2.defaultFamily
    });
    const submenuTypography = prefixed(typography, "subMenu");
    const baseStyle = window.getComputedStyle(header);
    const bgColor = parseColorString(baseStyle.backgroundColor) ?? { hex: "#ffffff", opacity: 1 };
    return {
      ...submenuTypography,
      subMenuBgColorOpacity: bgColor.opacity,
      subMenuBgColorHex: bgColor.hex
    };
  };
  var run = (entry) => {
    const { selector, families, defaultFamily } = entry;
    const node = document.querySelector(entry.selector);
    if (!node) {
      return {
        error: `Element with selector ${entry.selector} not found`
      };
    }
    const header = node;
    if (!header) {
      return {
        error: `Element with selector ${entry.selector} has no header`
      };
    }
    const nav = header.querySelector("#main-navigation");
    if (!nav) {
      return {
        error: `Element with selector ${entry.selector} has no nav`
      };
    }
    const subNav = header.querySelector(".sub-navigation") ?? void 0;
    let data2 = getMenuV({ nav, header, selector, families, defaultFamily });
    if (subNav) {
      const _v = getSubMenuV({
        nav,
        header,
        subNav,
        selector,
        families,
        defaultFamily
      });
      data2 = { ...data2, ..._v };
    }
    return createData({ data: data2, warns });
  };
  var data = getData();
  var output = run(data);
  var Menu_default = output;
  return __toCommonJS(Menu_exports);
})();
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vc3JjL01lbnUvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2NvbG9yL3BhcnNlQ29sb3JTdHJpbmcudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGUudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL3RleHQvY2FwaXRhbGl6ZS50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvdGV4dC90b0NhbWVsQ2FzZS50cyIsICIuLi9zcmMvTWVudS9tb2RlbC9nZXRNb2RlbC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvdXRpbHMvZ2V0RGF0YS50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvbW9kZWxzL3ByZWZpeGVkLnRzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJpbXBvcnQgeyBnZXRNb2RlbCB9IGZyb20gXCIuL21vZGVsL2dldE1vZGVsXCI7XG5pbXBvcnQgeyBFbnRyeSwgT3V0cHV0IH0gZnJvbSBcImVsZW1lbnRzL3NyYy90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBjcmVhdGVEYXRhLCBnZXREYXRhIH0gZnJvbSBcImVsZW1lbnRzL3NyYy91dGlscy9nZXREYXRhXCI7XG5pbXBvcnQgeyBwYXJzZUNvbG9yU3RyaW5nIH0gZnJvbSBcInV0aWxzL3NyYy9jb2xvci9wYXJzZUNvbG9yU3RyaW5nXCI7XG5pbXBvcnQgeyBwcmVmaXhlZCB9IGZyb20gXCJ1dGlscy9zcmMvbW9kZWxzL3ByZWZpeGVkXCI7XG5cbmludGVyZmFjZSBOYXZEYXRhIHtcbiAgbmF2OiBFbGVtZW50O1xuICBoZWFkZXI6IEVsZW1lbnQ7XG4gIHN1Yk5hdj86IEVsZW1lbnQ7XG4gIHNlbGVjdG9yOiBzdHJpbmc7XG4gIGZhbWlsaWVzOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+O1xuICBkZWZhdWx0RmFtaWx5OiBzdHJpbmc7XG59XG5cbmNvbnN0IHdhcm5zOiBSZWNvcmQ8c3RyaW5nLCBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+PiA9IHt9O1xuXG5jb25zdCBnZXRNZW51ViA9IChkYXRhOiBOYXZEYXRhKSA9PiB7XG4gIGNvbnN0IHsgbmF2LCBzZWxlY3RvciB9ID0gZGF0YTtcbiAgY29uc3QgdWwgPSBuYXYuY2hpbGRyZW5bMF07XG4gIGxldCB2ID0ge307XG5cbiAgaWYgKCF1bCkge1xuICAgIHdhcm5zW1wibWVudVwiXSA9IHtcbiAgICAgIG1lc3NhZ2U6IGBOYXZpZ2F0aW9uIGRvbid0IGhhdmUgdWwgaW4gJHtzZWxlY3Rvcn1gXG4gICAgfTtcbiAgICByZXR1cm4gdjtcbiAgfVxuXG4gIGNvbnN0IGxpID0gdWwucXVlcnlTZWxlY3RvcihcImxpXCIpO1xuICBpZiAoIWxpKSB7XG4gICAgd2FybnNbXCJtZW51IGxpXCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCA+IGxpIGluICR7c2VsZWN0b3J9YFxuICAgIH07XG4gICAgcmV0dXJuIHY7XG4gIH1cblxuICBjb25zdCBsaW5rID0gdWwucXVlcnlTZWxlY3RvcjxIVE1MRWxlbWVudD4oXCJsaSA+IGFcIik7XG4gIGlmICghbGluaykge1xuICAgIHdhcm5zW1wibWVudSBsaSBhXCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCA+IGxpID4gYSBpbiAke3NlbGVjdG9yfWBcbiAgICB9O1xuICAgIHJldHVybiB2O1xuICB9XG5cbiAgdiA9IGdldE1vZGVsKHtcbiAgICBub2RlOiBsaW5rLFxuICAgIGZhbWlsaWVzOiBkYXRhLmZhbWlsaWVzLFxuICAgIGRlZmF1bHRGYW1pbHk6IGRhdGEuZGVmYXVsdEZhbWlseVxuICB9KTtcblxuICByZXR1cm4geyAuLi52LCBpdGVtUGFkZGluZzogMjAgfTtcbn07XG5cbmNvbnN0IGdldFN1Yk1lbnVWID0gKGRhdGE6IFJlcXVpcmVkPE5hdkRhdGE+KSA9PiB7XG4gIGNvbnN0IHsgc3ViTmF2OiB1bCwgaGVhZGVyLCBzZWxlY3RvciB9ID0gZGF0YTtcblxuICBjb25zdCBsaSA9IHVsLnF1ZXJ5U2VsZWN0b3IoXCJsaVwiKTtcbiAgaWYgKCFsaSkge1xuICAgIHdhcm5zW1wic3VibWVudSBsaVwiXSA9IHtcbiAgICAgIG1lc3NhZ2U6IGBOYXZpZ2F0aW9uIGRvbid0IGhhdmUgdWwgPiBsaSBpbiAke3NlbGVjdG9yfWBcbiAgICB9O1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGNvbnN0IGxpbmsgPSB1bC5xdWVyeVNlbGVjdG9yPEhUTUxFbGVtZW50PihcImxpID4gYVwiKTtcbiAgaWYgKCFsaW5rKSB7XG4gICAgd2FybnNbXCJzdWJtZW51IGxpIGFcIl0gPSB7XG4gICAgICBtZXNzYWdlOiBgTmF2aWdhdGlvbiBkb24ndCBoYXZlIHVsID4gbGkgPiBhIGluICR7c2VsZWN0b3J9YFxuICAgIH07XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgY29uc3QgdHlwb2dyYXBoeSA9IGdldE1vZGVsKHtcbiAgICBub2RlOiBsaW5rLFxuICAgIGZhbWlsaWVzOiBkYXRhLmZhbWlsaWVzLFxuICAgIGRlZmF1bHRGYW1pbHk6IGRhdGEuZGVmYXVsdEZhbWlseVxuICB9KTtcbiAgY29uc3Qgc3VibWVudVR5cG9ncmFwaHkgPSBwcmVmaXhlZCh0eXBvZ3JhcGh5LCBcInN1Yk1lbnVcIik7XG4gIGNvbnN0IGJhc2VTdHlsZSA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKGhlYWRlcik7XG4gIGNvbnN0IGJnQ29sb3IgPSBwYXJzZUNvbG9yU3RyaW5nKGJhc2VTdHlsZS5iYWNrZ3JvdW5kQ29sb3IpID8/IHsgaGV4OiBcIiNmZmZmZmZcIiwgb3BhY2l0eTogMSB9O1xuXG4gIHJldHVybiB7XG4gICAgLi4uc3VibWVudVR5cG9ncmFwaHksXG4gICAgc3ViTWVudUJnQ29sb3JPcGFjaXR5OiBiZ0NvbG9yLm9wYWNpdHksXG4gICAgc3ViTWVudUJnQ29sb3JIZXg6IGJnQ29sb3IuaGV4XG4gIH07XG59O1xuXG5jb25zdCBydW4gPSAoZW50cnk6IEVudHJ5KTogT3V0cHV0ID0+IHtcbiAgY29uc3QgeyBzZWxlY3RvciwgZmFtaWxpZXMsIGRlZmF1bHRGYW1pbHkgfSA9IGVudHJ5O1xuICBjb25zdCBub2RlID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihlbnRyeS5zZWxlY3Rvcik7XG5cbiAgaWYgKCFub2RlKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGVycm9yOiBgRWxlbWVudCB3aXRoIHNlbGVjdG9yICR7ZW50cnkuc2VsZWN0b3J9IG5vdCBmb3VuZGBcbiAgICB9O1xuICB9XG5cbiAgY29uc3QgaGVhZGVyID0gbm9kZTtcblxuICBpZiAoIWhlYWRlcikge1xuICAgIHJldHVybiB7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke2VudHJ5LnNlbGVjdG9yfSBoYXMgbm8gaGVhZGVyYFxuICAgIH07XG4gIH1cblxuICBjb25zdCBuYXYgPSBoZWFkZXIucXVlcnlTZWxlY3RvcihcIiNtYWluLW5hdmlnYXRpb25cIik7XG5cbiAgaWYgKCFuYXYpIHtcbiAgICByZXR1cm4ge1xuICAgICAgZXJyb3I6IGBFbGVtZW50IHdpdGggc2VsZWN0b3IgJHtlbnRyeS5zZWxlY3Rvcn0gaGFzIG5vIG5hdmBcbiAgICB9O1xuICB9XG5cbiAgY29uc3Qgc3ViTmF2ID0gaGVhZGVyLnF1ZXJ5U2VsZWN0b3IoXCIuc3ViLW5hdmlnYXRpb25cIikgPz8gdW5kZWZpbmVkO1xuXG4gIGxldCBkYXRhID0gZ2V0TWVudVYoeyBuYXYsIGhlYWRlciwgc2VsZWN0b3IsIGZhbWlsaWVzLCBkZWZhdWx0RmFtaWx5IH0pO1xuXG4gIGlmIChzdWJOYXYpIHtcbiAgICBjb25zdCBfdiA9IGdldFN1Yk1lbnVWKHtcbiAgICAgIG5hdixcbiAgICAgIGhlYWRlcixcbiAgICAgIHN1Yk5hdixcbiAgICAgIHNlbGVjdG9yLFxuICAgICAgZmFtaWxpZXMsXG4gICAgICBkZWZhdWx0RmFtaWx5XG4gICAgfSk7XG4gICAgZGF0YSA9IHsgLi4uZGF0YSwgLi4uX3YgfTtcbiAgfVxuXG4gIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YSwgd2FybnMgfSk7XG59O1xuXG4vLyBGb3IgZGV2ZWxvcG1lbnRcbi8vIHdpbmRvdy5pc0RldiA9IHRydWU7XG5jb25zdCBkYXRhID0gZ2V0RGF0YSgpO1xuY29uc3Qgb3V0cHV0ID0gcnVuKGRhdGEpO1xuXG5leHBvcnQgZGVmYXVsdCBvdXRwdXQ7XG4iLCAiaW1wb3J0IHsgTVZhbHVlIH0gZnJvbSBcIi4uL3R5cGVzXCI7XG5cbmludGVyZmFjZSBDb2xvciB7XG4gIGhleDogc3RyaW5nO1xuICBvcGFjaXR5OiBzdHJpbmc7XG59XG5cbmNvbnN0IGhleFJlZ2V4ID0gL14jKD86W0EtRmEtZjAtOV17M30pezEsMn0kLztcbmNvbnN0IHJnYlJlZ2V4ID0gL15yZ2JcXHMqWyhdXFxzKihcXGQrKVxccyosXFxzKihcXGQrKVxccyosXFxzKihcXGQrKVxccypbKV0kLztcbmNvbnN0IHJnYmFSZWdleCA9XG4gIC9ecmdiYVxccypbKF1cXHMqKFxcZCspXFxzKixcXHMqKFxcZCspXFxzKixcXHMqKFxcZCspXFxzKixcXHMqKDAqKD86XFwuXFxkKyk/fDEoPzpcXC4wKik/KVxccypbKV0kLztcblxuY29uc3QgaXNIZXggPSAodjogc3RyaW5nKTogYm9vbGVhbiA9PiBoZXhSZWdleC50ZXN0KHYpO1xuXG5jb25zdCBmcm9tUmdiID0gKHJnYjogW251bWJlciwgbnVtYmVyLCBudW1iZXJdKTogc3RyaW5nID0+IHtcbiAgcmV0dXJuIChcbiAgICBcIiNcIiArXG4gICAgKFwiMFwiICsgcmdiWzBdLnRvU3RyaW5nKDE2KSkuc2xpY2UoLTIpICtcbiAgICAoXCIwXCIgKyByZ2JbMV0udG9TdHJpbmcoMTYpKS5zbGljZSgtMikgK1xuICAgIChcIjBcIiArIHJnYlsyXS50b1N0cmluZygxNikpLnNsaWNlKC0yKVxuICApO1xufTtcblxuZnVuY3Rpb24gcGFyc2VSZ2IoY29sb3I6IHN0cmluZyk6IE1WYWx1ZTxbbnVtYmVyLCBudW1iZXIsIG51bWJlcl0+IHtcbiAgY29uc3QgbWF0Y2hlcyA9IHJnYlJlZ2V4LmV4ZWMoY29sb3IpO1xuXG4gIGlmIChtYXRjaGVzKSB7XG4gICAgY29uc3QgW3IsIGcsIGJdID0gbWF0Y2hlcy5zbGljZSgxKS5tYXAoTnVtYmVyKTtcbiAgICByZXR1cm4gW3IsIGcsIGJdO1xuICB9XG5cbiAgcmV0dXJuIHVuZGVmaW5lZDtcbn1cblxuZnVuY3Rpb24gcGFyc2VSZ2JhKGNvbG9yOiBzdHJpbmcpOiBNVmFsdWU8W251bWJlciwgbnVtYmVyLCBudW1iZXIsIG51bWJlcl0+IHtcbiAgY29uc3QgbWF0Y2hlcyA9IHJnYmFSZWdleC5leGVjKGNvbG9yKTtcblxuICBpZiAobWF0Y2hlcykge1xuICAgIGNvbnN0IFtyLCBnLCBiLCBhXSA9IG1hdGNoZXMuc2xpY2UoMSkubWFwKE51bWJlcik7XG4gICAgcmV0dXJuIFtyLCBnLCBiLCBhXTtcbiAgfVxuXG4gIHJldHVybiB1bmRlZmluZWQ7XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBwYXJzZUNvbG9yU3RyaW5nKGNvbG9yU3RyaW5nOiBzdHJpbmcpOiBNVmFsdWU8Q29sb3I+IHtcbiAgaWYgKGlzSGV4KGNvbG9yU3RyaW5nKSkge1xuICAgIHJldHVybiB7XG4gICAgICBoZXg6IGNvbG9yU3RyaW5nLFxuICAgICAgb3BhY2l0eTogXCIxXCJcbiAgICB9O1xuICB9XG5cbiAgY29uc3QgcmdiUmVzdWx0ID0gcGFyc2VSZ2IoY29sb3JTdHJpbmcpO1xuICBpZiAocmdiUmVzdWx0KSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGhleDogZnJvbVJnYihyZ2JSZXN1bHQpLFxuICAgICAgb3BhY2l0eTogXCIxXCJcbiAgICB9O1xuICB9XG5cbiAgY29uc3QgcmdiYVJlc3VsdCA9IHBhcnNlUmdiYShjb2xvclN0cmluZyk7XG4gIGlmIChyZ2JhUmVzdWx0KSB7XG4gICAgY29uc3QgW3IsIGcsIGIsIGFdID0gcmdiYVJlc3VsdDtcbiAgICByZXR1cm4ge1xuICAgICAgaGV4OiBmcm9tUmdiKFtyLCBnLCBiXSksXG4gICAgICBvcGFjaXR5OiBTdHJpbmcoYSlcbiAgICB9O1xuICB9XG5cbiAgcmV0dXJuIHVuZGVmaW5lZDtcbn1cbiIsICJpbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcIi4uL3R5cGVzXCI7XG5cbmV4cG9ydCBjb25zdCBnZXROb2RlU3R5bGUgPSAoXG4gIG5vZGU6IEhUTUxFbGVtZW50IHwgRWxlbWVudFxuKTogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4gPT4ge1xuICBjb25zdCBjb21wdXRlZFN0eWxlcyA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKG5vZGUpO1xuICBjb25zdCBzdHlsZXM6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+ID0ge307XG5cbiAgT2JqZWN0LnZhbHVlcyhjb21wdXRlZFN0eWxlcykuZm9yRWFjaCgoa2V5KSA9PiB7XG4gICAgc3R5bGVzW2tleV0gPSBjb21wdXRlZFN0eWxlcy5nZXRQcm9wZXJ0eVZhbHVlKGtleSk7XG4gIH0pO1xuXG4gIHJldHVybiBzdHlsZXM7XG59O1xuIiwgImV4cG9ydCBjb25zdCBjYXBpdGFsaXplID0gKHN0cjogc3RyaW5nKTogc3RyaW5nID0+IHtcbiAgcmV0dXJuIHN0ci5jaGFyQXQoMCkudG9VcHBlckNhc2UoKSArIHN0ci5zbGljZSgxKTtcbn07XG4iLCAiaW1wb3J0IHsgY2FwaXRhbGl6ZSB9IGZyb20gXCIuL2NhcGl0YWxpemVcIjtcblxuZXhwb3J0IGNvbnN0IHRvQ2FtZWxDYXNlID0gKGtleTogc3RyaW5nKTogc3RyaW5nID0+IHtcbiAgY29uc3QgcGFydHMgPSBrZXkuc3BsaXQoXCItXCIpO1xuICBmb3IgKGxldCBpID0gMTsgaSA8IHBhcnRzLmxlbmd0aDsgaSsrKSB7XG4gICAgcGFydHNbaV0gPSBjYXBpdGFsaXplKHBhcnRzW2ldKTtcbiAgfVxuICByZXR1cm4gcGFydHMuam9pbihcIlwiKTtcbn07XG4iLCAiaW1wb3J0IHsgcGFyc2VDb2xvclN0cmluZyB9IGZyb20gXCJ1dGlscy9zcmMvY29sb3IvcGFyc2VDb2xvclN0cmluZ1wiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5pbXBvcnQgeyB0b0NhbWVsQ2FzZSB9IGZyb20gXCJ1dGlscy9zcmMvdGV4dC90b0NhbWVsQ2FzZVwiO1xuXG5pbnRlcmZhY2UgTW9kZWwge1xuICBub2RlOiBIVE1MRWxlbWVudDtcbiAgZmFtaWxpZXM6IFJlY29yZDxzdHJpbmcsIHN0cmluZz47XG4gIGRlZmF1bHRGYW1pbHk6IHN0cmluZztcbn1cblxuY29uc3QgdiA9IHtcbiAgXCJmb250LWZhbWlseVwiOiB1bmRlZmluZWQsXG4gIFwiZm9udC1mYW1pbHktdHlwZVwiOiBcInVwbG9hZGVkXCIsXG4gIFwiZm9udC13ZWlnaHRcIjogdW5kZWZpbmVkLFxuICBcImZvbnQtc2l6ZVwiOiB1bmRlZmluZWQsXG4gIFwibGluZS1oZWlnaHRcIjogdW5kZWZpbmVkLFxuICBcImxldHRlci1zcGFjaW5nXCI6IHVuZGVmaW5lZCxcbiAgY29sb3JIZXg6IHVuZGVmaW5lZCxcbiAgY29sb3JPcGFjaXR5OiAxLFxuICBhY3RpdmVDb2xvckhleDogdW5kZWZpbmVkLFxuICBhY3RpdmVDb2xvck9wYWNpdHk6IHVuZGVmaW5lZFxufTtcblxuZXhwb3J0IGNvbnN0IGdldE1vZGVsID0gKGRhdGE6IE1vZGVsKSA9PiB7XG4gIGNvbnN0IHsgbm9kZSwgZmFtaWxpZXMsIGRlZmF1bHRGYW1pbHkgfSA9IGRhdGE7XG4gIGNvbnN0IHN0eWxlcyA9IGdldE5vZGVTdHlsZShub2RlKTtcbiAgY29uc3QgZGljOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmcgfCBudW1iZXI+ID0ge307XG5cbiAgT2JqZWN0LmtleXModikuZm9yRWFjaCgoa2V5KSA9PiB7XG4gICAgc3dpdGNoIChrZXkpIHtcbiAgICAgIGNhc2UgXCJmb250LWZhbWlseVwiOiB7XG4gICAgICAgIGNvbnN0IHZhbHVlID0gYCR7c3R5bGVzW2tleV19YDtcbiAgICAgICAgY29uc3QgZm9udEZhbWlseSA9IHZhbHVlXG4gICAgICAgICAgLnJlcGxhY2UoL1snXCJcXCxdL2csIFwiXCIpIC8vIGVzbGludC1kaXNhYmxlLWxpbmVcbiAgICAgICAgICAucmVwbGFjZSgvXFxzL2csIFwiX1wiKVxuICAgICAgICAgIC50b0xvY2FsZUxvd2VyQ2FzZSgpO1xuXG4gICAgICAgIGlmICghZmFtaWxpZXNbZm9udEZhbWlseV0pIHtcbiAgICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSBkZWZhdWx0RmFtaWx5O1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IGZhbWlsaWVzW2ZvbnRGYW1pbHldO1xuICAgICAgICB9XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImZvbnQtZmFtaWx5LXR5cGVcIjoge1xuICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSBcInVwbG9hZFwiO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJsaW5lLWhlaWdodFwiOiB7XG4gICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IDE7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImZvbnQtc2l6ZVwiOiB7XG4gICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IHBhcnNlSW50KGAke3N0eWxlc1trZXldfWApO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJsZXR0ZXItc3BhY2luZ1wiOiB7XG4gICAgICAgIGNvbnN0IHZhbHVlID0gc3R5bGVzW2tleV07XG4gICAgICAgIGlmICh2YWx1ZSA9PT0gXCJub3JtYWxcIikge1xuICAgICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IDA7XG4gICAgICAgIH0gZWxzZSB7XG4gICAgICAgICAgLy8gUmVtb3ZlICdweCcgYW5kIGFueSBleHRyYSB3aGl0ZXNwYWNlXG4gICAgICAgICAgY29uc3QgbGV0dGVyU3BhY2luZ1ZhbHVlID0gYCR7dmFsdWV9YC5yZXBsYWNlKC9weC9nLCBcIlwiKS50cmltKCk7XG4gICAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gK2xldHRlclNwYWNpbmdWYWx1ZTtcbiAgICAgICAgfVxuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJjb2xvckhleFwiOiB7XG4gICAgICAgIGNvbnN0IHRvSGV4ID0gcGFyc2VDb2xvclN0cmluZyhgJHtzdHlsZXNbXCJjb2xvclwiXX1gKTtcbiAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gdG9IZXg/LmhleCA/PyBcIiMwMDAwMDBcIjtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiY29sb3JPcGFjaXR5XCI6IHtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBkZWZhdWx0OiB7XG4gICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IHN0eWxlc1trZXldO1xuICAgICAgfVxuICAgIH1cbiAgfSk7XG5cbiAgcmV0dXJuIGRpYztcbn07XG4iLCAiaW1wb3J0IHsgRW50cnksIE91dHB1dCwgT3V0cHV0RGF0YSB9IGZyb20gXCIuLi90eXBlcy90eXBlXCI7XG5cbmV4cG9ydCBjb25zdCBnZXREYXRhID0gKCk6IEVudHJ5ID0+IHtcbiAgdHJ5IHtcbiAgICAvLyBGb3IgZGV2ZWxvcG1lbnRcbiAgICAvLyB3aW5kb3cuaXNEZXYgPSB0cnVlO1xuICAgIHJldHVybiB3aW5kb3cuaXNEZXZcbiAgICAgID8ge1xuICAgICAgICAgIHNlbGVjdG9yOiBgW2RhdGEtaWQ9JyR7NDM0MTU3OX0nXWAsXG4gICAgICAgICAgZmFtaWxpZXM6IHtcbiAgICAgICAgICAgIFwicHJveGltYV9ub3ZhX3Byb3hpbWFfbm92YV9yZWd1bGFyX3NhbnMtc2VyaWZcIjogXCJ1aWQxMTExXCIsXG4gICAgICAgICAgICBcImhlbHZldGljYV9uZXVlX2hlbHZldGljYW5ldWVfaGVsdmV0aWNhX2FyaWFsX3NhbnMtc2VyaWZcIjogXCJ1aWQyMjIyXCJcbiAgICAgICAgICB9LFxuICAgICAgICAgIGRlZmF1bHRGYW1pbHk6IFwibGF0b1wiXG4gICAgICAgIH1cbiAgICAgIDoge1xuICAgICAgICAgIHNlbGVjdG9yOiBTRUxFQ1RPUixcbiAgICAgICAgICBmYW1pbGllczogRkFNSUxJRVMsXG4gICAgICAgICAgZGVmYXVsdEZhbWlseTogREVGQVVMVF9GQU1JTFlcbiAgICAgICAgfTtcbiAgfSBjYXRjaCAoZSkge1xuICAgIGNvbnN0IGZhbWlseU1vY2sgPSB7XG4gICAgICBsYXRvOiBcInVpZF9mb3JfbGF0b1wiLFxuICAgICAgcm9ib3RvOiBcInVpZF9mb3Jfcm9ib3RvXCJcbiAgICB9O1xuICAgIGNvbnN0IG1vY2s6IEVudHJ5ID0ge1xuICAgICAgc2VsZWN0b3I6IFwiLm15LWRpdlwiLFxuICAgICAgZmFtaWxpZXM6IGZhbWlseU1vY2ssXG4gICAgICBkZWZhdWx0RmFtaWx5OiBcImxhdG9cIlxuICAgIH07XG5cbiAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICBKU09OLnN0cmluZ2lmeSh7XG4gICAgICAgIGVycm9yOiBgSW52YWxpZCBKU09OICR7ZX1gLFxuICAgICAgICBkZXRhaWxzOiBgTXVzdCBiZTogJHtKU09OLnN0cmluZ2lmeShtb2NrKX1gXG4gICAgICB9KVxuICAgICk7XG4gIH1cbn07XG5cbmV4cG9ydCBjb25zdCBjcmVhdGVEYXRhID0gKG91dHB1dDogT3V0cHV0RGF0YSk6IE91dHB1dCA9PiB7XG4gIHJldHVybiBvdXRwdXQ7XG59O1xuIiwgImltcG9ydCB7IGNhcGl0YWxpemUgfSBmcm9tIFwiLi4vdGV4dC9jYXBpdGFsaXplXCI7XG5cbmV4cG9ydCBjb25zdCBwcmVmaXhlZCA9IDxUIGV4dGVuZHMgUmVjb3JkPHN0cmluZywgdW5rbm93bj4+KFxuICB2OiBULFxuICBwcmVmaXg6IHN0cmluZ1xuKTogVCA9PiB7XG4gIHJldHVybiBPYmplY3QuZW50cmllcyh2KS5yZWR1Y2UoKGFjYywgW2tleSwgdmFsdWVdKSA9PiB7XG4gICAgbGV0IF9rZXkgPSBwcmVmaXggKyBjYXBpdGFsaXplKGtleSk7XG5cbiAgICBpZiAoa2V5LnN0YXJ0c1dpdGgoXCJhY3RpdmVcIikpIHtcbiAgICAgIF9rZXkgPSBgYWN0aXZlJHtjYXBpdGFsaXplKHByZWZpeCl9JHtrZXkucmVwbGFjZShcImFjdGl2ZVwiLCBcIlwiKX1gO1xuICAgIH1cblxuICAgIHJldHVybiB7IC4uLmFjYywgW19rZXldOiB2YWx1ZSB9O1xuICB9LCB7fSBhcyBUKTtcbn07XG4iXSwKICAibWFwcGluZ3MiOiAiOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUFBQTtBQUFBO0FBQUE7QUFBQTs7O0FDT0EsTUFBTSxXQUFXO0FBQ2pCLE1BQU0sV0FBVztBQUNqQixNQUFNLFlBQ0o7QUFFRixNQUFNLFFBQVEsQ0FBQ0EsT0FBdUIsU0FBUyxLQUFLQSxFQUFDO0FBRXJELE1BQU0sVUFBVSxDQUFDLFFBQTBDO0FBQ3pELFdBQ0UsT0FDQyxNQUFNLElBQUksQ0FBQyxFQUFFLFNBQVMsRUFBRSxHQUFHLE1BQU0sRUFBRSxLQUNuQyxNQUFNLElBQUksQ0FBQyxFQUFFLFNBQVMsRUFBRSxHQUFHLE1BQU0sRUFBRSxLQUNuQyxNQUFNLElBQUksQ0FBQyxFQUFFLFNBQVMsRUFBRSxHQUFHLE1BQU0sRUFBRTtBQUFBLEVBRXhDO0FBRUEsV0FBUyxTQUFTLE9BQWlEO0FBQ2pFLFVBQU0sVUFBVSxTQUFTLEtBQUssS0FBSztBQUVuQyxRQUFJLFNBQVM7QUFDWCxZQUFNLENBQUMsR0FBRyxHQUFHLENBQUMsSUFBSSxRQUFRLE1BQU0sQ0FBQyxFQUFFLElBQUksTUFBTTtBQUM3QyxhQUFPLENBQUMsR0FBRyxHQUFHLENBQUM7QUFBQSxJQUNqQjtBQUVBLFdBQU87QUFBQSxFQUNUO0FBRUEsV0FBUyxVQUFVLE9BQXlEO0FBQzFFLFVBQU0sVUFBVSxVQUFVLEtBQUssS0FBSztBQUVwQyxRQUFJLFNBQVM7QUFDWCxZQUFNLENBQUMsR0FBRyxHQUFHLEdBQUcsQ0FBQyxJQUFJLFFBQVEsTUFBTSxDQUFDLEVBQUUsSUFBSSxNQUFNO0FBQ2hELGFBQU8sQ0FBQyxHQUFHLEdBQUcsR0FBRyxDQUFDO0FBQUEsSUFDcEI7QUFFQSxXQUFPO0FBQUEsRUFDVDtBQUVPLFdBQVMsaUJBQWlCLGFBQW9DO0FBQ25FLFFBQUksTUFBTSxXQUFXLEdBQUc7QUFDdEIsYUFBTztBQUFBLFFBQ0wsS0FBSztBQUFBLFFBQ0wsU0FBUztBQUFBLE1BQ1g7QUFBQSxJQUNGO0FBRUEsVUFBTSxZQUFZLFNBQVMsV0FBVztBQUN0QyxRQUFJLFdBQVc7QUFDYixhQUFPO0FBQUEsUUFDTCxLQUFLLFFBQVEsU0FBUztBQUFBLFFBQ3RCLFNBQVM7QUFBQSxNQUNYO0FBQUEsSUFDRjtBQUVBLFVBQU0sYUFBYSxVQUFVLFdBQVc7QUFDeEMsUUFBSSxZQUFZO0FBQ2QsWUFBTSxDQUFDLEdBQUcsR0FBRyxHQUFHLENBQUMsSUFBSTtBQUNyQixhQUFPO0FBQUEsUUFDTCxLQUFLLFFBQVEsQ0FBQyxHQUFHLEdBQUcsQ0FBQyxDQUFDO0FBQUEsUUFDdEIsU0FBUyxPQUFPLENBQUM7QUFBQSxNQUNuQjtBQUFBLElBQ0Y7QUFFQSxXQUFPO0FBQUEsRUFDVDs7O0FDckVPLE1BQU0sZUFBZSxDQUMxQixTQUM0QjtBQUM1QixVQUFNLGlCQUFpQixPQUFPLGlCQUFpQixJQUFJO0FBQ25ELFVBQU0sU0FBa0MsQ0FBQztBQUV6QyxXQUFPLE9BQU8sY0FBYyxFQUFFLFFBQVEsQ0FBQyxRQUFRO0FBQzdDLGFBQU8sR0FBRyxJQUFJLGVBQWUsaUJBQWlCLEdBQUc7QUFBQSxJQUNuRCxDQUFDO0FBRUQsV0FBTztBQUFBLEVBQ1Q7OztBQ2JPLE1BQU0sYUFBYSxDQUFDLFFBQXdCO0FBQ2pELFdBQU8sSUFBSSxPQUFPLENBQUMsRUFBRSxZQUFZLElBQUksSUFBSSxNQUFNLENBQUM7QUFBQSxFQUNsRDs7O0FDQU8sTUFBTSxjQUFjLENBQUMsUUFBd0I7QUFDbEQsVUFBTSxRQUFRLElBQUksTUFBTSxHQUFHO0FBQzNCLGFBQVMsSUFBSSxHQUFHLElBQUksTUFBTSxRQUFRLEtBQUs7QUFDckMsWUFBTSxDQUFDLElBQUksV0FBVyxNQUFNLENBQUMsQ0FBQztBQUFBLElBQ2hDO0FBQ0EsV0FBTyxNQUFNLEtBQUssRUFBRTtBQUFBLEVBQ3RCOzs7QUNFQSxNQUFNLElBQUk7QUFBQSxJQUNSLGVBQWU7QUFBQSxJQUNmLG9CQUFvQjtBQUFBLElBQ3BCLGVBQWU7QUFBQSxJQUNmLGFBQWE7QUFBQSxJQUNiLGVBQWU7QUFBQSxJQUNmLGtCQUFrQjtBQUFBLElBQ2xCLFVBQVU7QUFBQSxJQUNWLGNBQWM7QUFBQSxJQUNkLGdCQUFnQjtBQUFBLElBQ2hCLG9CQUFvQjtBQUFBLEVBQ3RCO0FBRU8sTUFBTSxXQUFXLENBQUNDLFVBQWdCO0FBQ3ZDLFVBQU0sRUFBRSxNQUFNLFVBQVUsY0FBYyxJQUFJQTtBQUMxQyxVQUFNLFNBQVMsYUFBYSxJQUFJO0FBQ2hDLFVBQU0sTUFBdUMsQ0FBQztBQUU5QyxXQUFPLEtBQUssQ0FBQyxFQUFFLFFBQVEsQ0FBQyxRQUFRO0FBQzlCLGNBQVEsS0FBSztBQUFBLFFBQ1gsS0FBSyxlQUFlO0FBQ2xCLGdCQUFNLFFBQVEsR0FBRyxPQUFPLEdBQUcsQ0FBQztBQUM1QixnQkFBTSxhQUFhLE1BQ2hCLFFBQVEsV0FBVyxFQUFFLEVBQ3JCLFFBQVEsT0FBTyxHQUFHLEVBQ2xCLGtCQUFrQjtBQUVyQixjQUFJLENBQUMsU0FBUyxVQUFVLEdBQUc7QUFDekIsZ0JBQUksWUFBWSxHQUFHLENBQUMsSUFBSTtBQUFBLFVBQzFCLE9BQU87QUFDTCxnQkFBSSxZQUFZLEdBQUcsQ0FBQyxJQUFJLFNBQVMsVUFBVTtBQUFBLFVBQzdDO0FBQ0E7QUFBQSxRQUNGO0FBQUEsUUFDQSxLQUFLLG9CQUFvQjtBQUN2QixjQUFJLFlBQVksR0FBRyxDQUFDLElBQUk7QUFDeEI7QUFBQSxRQUNGO0FBQUEsUUFDQSxLQUFLLGVBQWU7QUFDbEIsY0FBSSxZQUFZLEdBQUcsQ0FBQyxJQUFJO0FBQ3hCO0FBQUEsUUFDRjtBQUFBLFFBQ0EsS0FBSyxhQUFhO0FBQ2hCLGNBQUksWUFBWSxHQUFHLENBQUMsSUFBSSxTQUFTLEdBQUcsT0FBTyxHQUFHLENBQUMsRUFBRTtBQUNqRDtBQUFBLFFBQ0Y7QUFBQSxRQUNBLEtBQUssa0JBQWtCO0FBQ3JCLGdCQUFNLFFBQVEsT0FBTyxHQUFHO0FBQ3hCLGNBQUksVUFBVSxVQUFVO0FBQ3RCLGdCQUFJLFlBQVksR0FBRyxDQUFDLElBQUk7QUFBQSxVQUMxQixPQUFPO0FBRUwsa0JBQU0scUJBQXFCLEdBQUcsS0FBSyxHQUFHLFFBQVEsT0FBTyxFQUFFLEVBQUUsS0FBSztBQUM5RCxnQkFBSSxZQUFZLEdBQUcsQ0FBQyxJQUFJLENBQUM7QUFBQSxVQUMzQjtBQUNBO0FBQUEsUUFDRjtBQUFBLFFBQ0EsS0FBSyxZQUFZO0FBQ2YsZ0JBQU0sUUFBUSxpQkFBaUIsR0FBRyxPQUFPLE9BQU8sQ0FBQyxFQUFFO0FBQ25ELGNBQUksWUFBWSxHQUFHLENBQUMsSUFBSSxPQUFPLE9BQU87QUFDdEM7QUFBQSxRQUNGO0FBQUEsUUFDQSxLQUFLLGdCQUFnQjtBQUNuQjtBQUFBLFFBQ0Y7QUFBQSxRQUNBLFNBQVM7QUFDUCxjQUFJLFlBQVksR0FBRyxDQUFDLElBQUksT0FBTyxHQUFHO0FBQUEsUUFDcEM7QUFBQSxNQUNGO0FBQUEsSUFDRixDQUFDO0FBRUQsV0FBTztBQUFBLEVBQ1Q7OztBQ2hGTyxNQUFNLFVBQVUsTUFBYTtBQUNsQyxRQUFJO0FBR0YsYUFBTyxPQUFPLFFBQ1Y7QUFBQSxRQUNFLFVBQVUsYUFBYSxPQUFPO0FBQUEsUUFDOUIsVUFBVTtBQUFBLFVBQ1IsZ0RBQWdEO0FBQUEsVUFDaEQsMkRBQTJEO0FBQUEsUUFDN0Q7QUFBQSxRQUNBLGVBQWU7QUFBQSxNQUNqQixJQUNBO0FBQUEsUUFDRSxVQUFVO0FBQUEsUUFDVixVQUFVO0FBQUEsUUFDVixlQUFlO0FBQUEsTUFDakI7QUFBQSxJQUNOLFNBQVMsR0FBRztBQUNWLFlBQU0sYUFBYTtBQUFBLFFBQ2pCLE1BQU07QUFBQSxRQUNOLFFBQVE7QUFBQSxNQUNWO0FBQ0EsWUFBTSxPQUFjO0FBQUEsUUFDbEIsVUFBVTtBQUFBLFFBQ1YsVUFBVTtBQUFBLFFBQ1YsZUFBZTtBQUFBLE1BQ2pCO0FBRUEsWUFBTSxJQUFJO0FBQUEsUUFDUixLQUFLLFVBQVU7QUFBQSxVQUNiLE9BQU8sZ0JBQWdCLENBQUM7QUFBQSxVQUN4QixTQUFTLFlBQVksS0FBSyxVQUFVLElBQUksQ0FBQztBQUFBLFFBQzNDLENBQUM7QUFBQSxNQUNIO0FBQUEsSUFDRjtBQUFBLEVBQ0Y7QUFFTyxNQUFNLGFBQWEsQ0FBQ0MsWUFBK0I7QUFDeEQsV0FBT0E7QUFBQSxFQUNUOzs7QUN4Q08sTUFBTSxXQUFXLENBQ3RCQyxJQUNBLFdBQ007QUFDTixXQUFPLE9BQU8sUUFBUUEsRUFBQyxFQUFFLE9BQU8sQ0FBQyxLQUFLLENBQUMsS0FBSyxLQUFLLE1BQU07QUFDckQsVUFBSSxPQUFPLFNBQVMsV0FBVyxHQUFHO0FBRWxDLFVBQUksSUFBSSxXQUFXLFFBQVEsR0FBRztBQUM1QixlQUFPLFNBQVMsV0FBVyxNQUFNLENBQUMsR0FBRyxJQUFJLFFBQVEsVUFBVSxFQUFFLENBQUM7QUFBQSxNQUNoRTtBQUVBLGFBQU8sRUFBRSxHQUFHLEtBQUssQ0FBQyxJQUFJLEdBQUcsTUFBTTtBQUFBLElBQ2pDLEdBQUcsQ0FBQyxDQUFNO0FBQUEsRUFDWjs7O0FQQUEsTUFBTSxRQUFnRCxDQUFDO0FBRXZELE1BQU0sV0FBVyxDQUFDQyxVQUFrQjtBQUNsQyxVQUFNLEVBQUUsS0FBSyxTQUFTLElBQUlBO0FBQzFCLFVBQU0sS0FBSyxJQUFJLFNBQVMsQ0FBQztBQUN6QixRQUFJQyxLQUFJLENBQUM7QUFFVCxRQUFJLENBQUMsSUFBSTtBQUNQLFlBQU0sTUFBTSxJQUFJO0FBQUEsUUFDZCxTQUFTLCtCQUErQixRQUFRO0FBQUEsTUFDbEQ7QUFDQSxhQUFPQTtBQUFBLElBQ1Q7QUFFQSxVQUFNLEtBQUssR0FBRyxjQUFjLElBQUk7QUFDaEMsUUFBSSxDQUFDLElBQUk7QUFDUCxZQUFNLFNBQVMsSUFBSTtBQUFBLFFBQ2pCLFNBQVMsb0NBQW9DLFFBQVE7QUFBQSxNQUN2RDtBQUNBLGFBQU9BO0FBQUEsSUFDVDtBQUVBLFVBQU0sT0FBTyxHQUFHLGNBQTJCLFFBQVE7QUFDbkQsUUFBSSxDQUFDLE1BQU07QUFDVCxZQUFNLFdBQVcsSUFBSTtBQUFBLFFBQ25CLFNBQVMsd0NBQXdDLFFBQVE7QUFBQSxNQUMzRDtBQUNBLGFBQU9BO0FBQUEsSUFDVDtBQUVBLElBQUFBLEtBQUksU0FBUztBQUFBLE1BQ1gsTUFBTTtBQUFBLE1BQ04sVUFBVUQsTUFBSztBQUFBLE1BQ2YsZUFBZUEsTUFBSztBQUFBLElBQ3RCLENBQUM7QUFFRCxXQUFPLEVBQUUsR0FBR0MsSUFBRyxhQUFhLEdBQUc7QUFBQSxFQUNqQztBQUVBLE1BQU0sY0FBYyxDQUFDRCxVQUE0QjtBQUMvQyxVQUFNLEVBQUUsUUFBUSxJQUFJLFFBQVEsU0FBUyxJQUFJQTtBQUV6QyxVQUFNLEtBQUssR0FBRyxjQUFjLElBQUk7QUFDaEMsUUFBSSxDQUFDLElBQUk7QUFDUCxZQUFNLFlBQVksSUFBSTtBQUFBLFFBQ3BCLFNBQVMsb0NBQW9DLFFBQVE7QUFBQSxNQUN2RDtBQUNBO0FBQUEsSUFDRjtBQUVBLFVBQU0sT0FBTyxHQUFHLGNBQTJCLFFBQVE7QUFDbkQsUUFBSSxDQUFDLE1BQU07QUFDVCxZQUFNLGNBQWMsSUFBSTtBQUFBLFFBQ3RCLFNBQVMsd0NBQXdDLFFBQVE7QUFBQSxNQUMzRDtBQUNBO0FBQUEsSUFDRjtBQUVBLFVBQU0sYUFBYSxTQUFTO0FBQUEsTUFDMUIsTUFBTTtBQUFBLE1BQ04sVUFBVUEsTUFBSztBQUFBLE1BQ2YsZUFBZUEsTUFBSztBQUFBLElBQ3RCLENBQUM7QUFDRCxVQUFNLG9CQUFvQixTQUFTLFlBQVksU0FBUztBQUN4RCxVQUFNLFlBQVksT0FBTyxpQkFBaUIsTUFBTTtBQUNoRCxVQUFNLFVBQVUsaUJBQWlCLFVBQVUsZUFBZSxLQUFLLEVBQUUsS0FBSyxXQUFXLFNBQVMsRUFBRTtBQUU1RixXQUFPO0FBQUEsTUFDTCxHQUFHO0FBQUEsTUFDSCx1QkFBdUIsUUFBUTtBQUFBLE1BQy9CLG1CQUFtQixRQUFRO0FBQUEsSUFDN0I7QUFBQSxFQUNGO0FBRUEsTUFBTSxNQUFNLENBQUMsVUFBeUI7QUFDcEMsVUFBTSxFQUFFLFVBQVUsVUFBVSxjQUFjLElBQUk7QUFDOUMsVUFBTSxPQUFPLFNBQVMsY0FBYyxNQUFNLFFBQVE7QUFFbEQsUUFBSSxDQUFDLE1BQU07QUFDVCxhQUFPO0FBQUEsUUFDTCxPQUFPLHlCQUF5QixNQUFNLFFBQVE7QUFBQSxNQUNoRDtBQUFBLElBQ0Y7QUFFQSxVQUFNLFNBQVM7QUFFZixRQUFJLENBQUMsUUFBUTtBQUNYLGFBQU87QUFBQSxRQUNMLE9BQU8seUJBQXlCLE1BQU0sUUFBUTtBQUFBLE1BQ2hEO0FBQUEsSUFDRjtBQUVBLFVBQU0sTUFBTSxPQUFPLGNBQWMsa0JBQWtCO0FBRW5ELFFBQUksQ0FBQyxLQUFLO0FBQ1IsYUFBTztBQUFBLFFBQ0wsT0FBTyx5QkFBeUIsTUFBTSxRQUFRO0FBQUEsTUFDaEQ7QUFBQSxJQUNGO0FBRUEsVUFBTSxTQUFTLE9BQU8sY0FBYyxpQkFBaUIsS0FBSztBQUUxRCxRQUFJQSxRQUFPLFNBQVMsRUFBRSxLQUFLLFFBQVEsVUFBVSxVQUFVLGNBQWMsQ0FBQztBQUV0RSxRQUFJLFFBQVE7QUFDVixZQUFNLEtBQUssWUFBWTtBQUFBLFFBQ3JCO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxNQUNGLENBQUM7QUFDRCxNQUFBQSxRQUFPLEVBQUUsR0FBR0EsT0FBTSxHQUFHLEdBQUc7QUFBQSxJQUMxQjtBQUVBLFdBQU8sV0FBVyxFQUFFLE1BQUFBLE9BQU0sTUFBTSxDQUFDO0FBQUEsRUFDbkM7QUFJQSxNQUFNLE9BQU8sUUFBUTtBQUNyQixNQUFNLFNBQVMsSUFBSSxJQUFJO0FBRXZCLE1BQU8sZUFBUTsiLAogICJuYW1lcyI6IFsidiIsICJkYXRhIiwgIm91dHB1dCIsICJ2IiwgImRhdGEiLCAidiJdCn0K
