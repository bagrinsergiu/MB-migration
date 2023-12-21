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

  // src/utils/getGlobalMenuModel.ts
  var getGlobalMenuModel = () => {
    return window.menuModel;
  };

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

  // src/Menu/utils/getModel.ts
  var v = {
    "font-family": void 0,
    "font-family-type": "uploaded",
    "font-weight": void 0,
    "font-size": void 0,
    "line-height": void 0,
    "letter-spacing": void 0,
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

  // ../../../../../../../packages/elements/src/utils/getData.ts
  var getData = () => {
    try {
      return window.isDev ? {
        selector: `[data-id='${19576386}']`,
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
    const link = ul.querySelector("li:not(.selected) > a");
    if (!link) {
      warns["menu li a"] = {
        message: `Navigation don't have ul > li > a in ${selector}`
      };
      return v2;
    }
    const span = ul.querySelector("li > a > span");
    const activeLink = ul.querySelector("li.selected > a");
    const styles = window.getComputedStyle(link);
    const itemPadding = parseInt(styles.paddingLeft);
    v2 = getModel({
      node: link,
      families: data2.families,
      defaultFamily: data2.defaultFamily
    });
    if (activeLink) {
      const styles2 = window.getComputedStyle(activeLink);
      const color = parseColorString(styles2.color);
      if (color) {
        v2 = {
          ...v2,
          activeColorHex: color.hex,
          activeColorOpacity: color.opacity
        };
      }
    }
    if (span) {
      const styles2 = window.getComputedStyle(span);
      const paddingTop = parseInt(styles2.paddingTop ?? 0);
      const paddingRight = parseInt(styles2.paddingRight ?? 0);
      const paddingBottom = parseInt(styles2.paddingBottom ?? 0);
      const paddingLeft = parseInt(styles2.paddingLeft ?? 0);
      v2 = {
        ...v2,
        menuPaddingTop: paddingTop,
        menuPaddingRight: paddingRight,
        menuPaddingBottom: paddingBottom,
        menuPaddingLeft: paddingLeft
      };
    }
    return { ...v2, itemPadding: isNaN(itemPadding) ? 10 : itemPadding };
  };
  var getSubMenuV = (data2) => {
    const { subNav: ul, selector } = data2;
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
    const baseStyle = window.getComputedStyle(ul);
    const bgColor = parseColorString(baseStyle.backgroundColor) ?? {
      hex: "#ffffff",
      opacity: 1
    };
    return {
      ...submenuTypography,
      subMenuBgColorOpacity: bgColor.opacity,
      subMenuBgColorHex: bgColor.hex
    };
  };
  var run = (entry) => {
    const { selector, families, defaultFamily } = entry;
    const node = document.querySelector(selector);
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
    let data2 = getMenuV({ nav, selector, families, defaultFamily });
    if (subNav) {
      const _v = getSubMenuV({ nav, subNav, selector, families, defaultFamily });
      data2 = { ...data2, ..._v };
    }
    const globalModel = getGlobalMenuModel();
    data2 = { ...globalModel, ...data2 };
    return createData({ data: data2 });
  };
  var data = getData();
  var output = run(data);
  var Menu_default = output;
  return __toCommonJS(Menu_exports);
})();
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vc3JjL01lbnUvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2NvbG9yL3BhcnNlQ29sb3JTdHJpbmcudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGUudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL3RleHQvY2FwaXRhbGl6ZS50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvdGV4dC90b0NhbWVsQ2FzZS50cyIsICIuLi9zcmMvTWVudS91dGlscy9nZXRNb2RlbC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvdXRpbHMvZ2V0RGF0YS50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvbW9kZWxzL3ByZWZpeGVkLnRzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJpbXBvcnQgeyBnZXRNb2RlbCB9IGZyb20gXCIuL3V0aWxzL2dldE1vZGVsXCI7XG5pbXBvcnQgeyBFbnRyeSwgT3V0cHV0IH0gZnJvbSBcImVsZW1lbnRzL3NyYy90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBjcmVhdGVEYXRhLCBnZXREYXRhIH0gZnJvbSBcImVsZW1lbnRzL3NyYy91dGlscy9nZXREYXRhXCI7XG5pbXBvcnQgeyBwYXJzZUNvbG9yU3RyaW5nIH0gZnJvbSBcInV0aWxzL3NyYy9jb2xvci9wYXJzZUNvbG9yU3RyaW5nXCI7XG5pbXBvcnQgeyBwcmVmaXhlZCB9IGZyb20gXCJ1dGlscy9zcmMvbW9kZWxzL3ByZWZpeGVkXCI7XG5cbmludGVyZmFjZSBOYXZEYXRhIHtcbiAgbmF2OiBFbGVtZW50O1xuICBzdWJOYXY/OiBFbGVtZW50O1xuICBzZWxlY3Rvcjogc3RyaW5nO1xuICBmYW1pbGllczogUmVjb3JkPHN0cmluZywgc3RyaW5nPjtcbiAgZGVmYXVsdEZhbWlseTogc3RyaW5nO1xufVxuY29uc3Qgd2FybnM6IFJlY29yZDxzdHJpbmcsIFJlY29yZDxzdHJpbmcsIHN0cmluZz4+ID0ge307XG5cbmNvbnN0IGdldE1lbnVWID0gKGRhdGE6IE5hdkRhdGEpID0+IHtcbiAgY29uc3QgeyBuYXYsIHNlbGVjdG9yIH0gPSBkYXRhO1xuICBjb25zdCB1bCA9IG5hdi5jaGlsZHJlblswXTtcbiAgbGV0IHYgPSB7fTtcblxuICBpZiAoIXVsKSB7XG4gICAgd2FybnNbXCJtZW51XCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCBpbiAke3NlbGVjdG9yfWBcbiAgICB9O1xuICAgIHJldHVybiB2O1xuICB9XG5cbiAgY29uc3QgbGkgPSB1bC5xdWVyeVNlbGVjdG9yKFwibGlcIik7XG4gIGlmICghbGkpIHtcbiAgICB3YXJuc1tcIm1lbnUgbGlcIl0gPSB7XG4gICAgICBtZXNzYWdlOiBgTmF2aWdhdGlvbiBkb24ndCBoYXZlIHVsID4gbGkgaW4gJHtzZWxlY3Rvcn1gXG4gICAgfTtcbiAgICByZXR1cm4gdjtcbiAgfVxuXG4gIGNvbnN0IGxpbmsgPSB1bC5xdWVyeVNlbGVjdG9yKFwibGkgPiBhXCIpO1xuICBpZiAoIWxpbmspIHtcbiAgICB3YXJuc1tcIm1lbnUgbGkgYVwiXSA9IHtcbiAgICAgIG1lc3NhZ2U6IGBOYXZpZ2F0aW9uIGRvbid0IGhhdmUgdWwgPiBsaSA+IGEgaW4gJHtzZWxlY3Rvcn1gXG4gICAgfTtcbiAgICByZXR1cm4gdjtcbiAgfVxuXG4gIGNvbnN0IHN0eWxlcyA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKGxpKTtcbiAgY29uc3QgaXRlbVBhZGRpbmcgPSBwYXJzZUludChzdHlsZXMucGFkZGluZ0xlZnQpO1xuXG4gIHYgPSBnZXRNb2RlbCh7XG4gICAgbm9kZTogbGluayxcbiAgICBmYW1pbGllczogZGF0YS5mYW1pbGllcyxcbiAgICBkZWZhdWx0RmFtaWx5OiBkYXRhLmRlZmF1bHRGYW1pbHlcbiAgfSk7XG5cbiAgcmV0dXJuIHsgLi4udiwgaXRlbVBhZGRpbmc6IGlzTmFOKGl0ZW1QYWRkaW5nKSA/IDEwIDogaXRlbVBhZGRpbmcgfTtcbn07XG5cbmNvbnN0IGdldFN1Yk1lbnVWID0gKGRhdGE6IFJlcXVpcmVkPE5hdkRhdGE+KSA9PiB7XG4gIGNvbnN0IHsgc3ViTmF2LCBzZWxlY3RvciB9ID0gZGF0YTtcblxuICBjb25zdCB1bCA9IHN1Yk5hdi5jaGlsZHJlblswXTtcblxuICBpZiAoIXVsKSB7XG4gICAgd2FybnNbXCJzdWJtZW51XCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCBpbiAke3NlbGVjdG9yfWBcbiAgICB9O1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGNvbnN0IGxpID0gdWwucXVlcnlTZWxlY3RvcihcImxpXCIpO1xuICBpZiAoIWxpKSB7XG4gICAgd2FybnNbXCJzdWJtZW51IGxpXCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCA+IGxpIGluICR7c2VsZWN0b3J9YFxuICAgIH07XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgY29uc3QgbGluayA9IHVsLnF1ZXJ5U2VsZWN0b3IoXCJsaSA+IGFcIik7XG4gIGlmICghbGluaykge1xuICAgIHdhcm5zW1wic3VibWVudSBsaSBhXCJdID0ge1xuICAgICAgbWVzc2FnZTogYE5hdmlnYXRpb24gZG9uJ3QgaGF2ZSB1bCA+IGxpID4gYSBpbiAke3NlbGVjdG9yfWBcbiAgICB9O1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGNvbnN0IHR5cG9ncmFwaHkgPSBnZXRNb2RlbCh7XG4gICAgbm9kZTogbGluayxcbiAgICBmYW1pbGllczogZGF0YS5mYW1pbGllcyxcbiAgICBkZWZhdWx0RmFtaWx5OiBkYXRhLmRlZmF1bHRGYW1pbHlcbiAgfSk7XG4gIGNvbnN0IHN1Ym1lbnVUeXBvZ3JhcGh5ID0gcHJlZml4ZWQodHlwb2dyYXBoeSwgXCJzdWJNZW51XCIpO1xuICBjb25zdCBiYXNlU3R5bGUgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShzdWJOYXYpO1xuICBjb25zdCBiZ0NvbG9yID0gcGFyc2VDb2xvclN0cmluZyhiYXNlU3R5bGUuYmFja2dyb3VuZENvbG9yKSA/PyB7XG4gICAgaGV4OiBcIiNmZmZmZmZcIixcbiAgICBvcGFjaXR5OiAxXG4gIH07XG5cbiAgcmV0dXJuIHtcbiAgICAuLi5zdWJtZW51VHlwb2dyYXBoeSxcbiAgICBzdWJNZW51QmdDb2xvck9wYWNpdHk6IGJnQ29sb3Iub3BhY2l0eSxcbiAgICBzdWJNZW51QmdDb2xvckhleDogYmdDb2xvci5oZXhcbiAgfTtcbn07XG5cbmNvbnN0IHJ1biA9IChlbnRyeTogRW50cnkpOiBPdXRwdXQgPT4ge1xuICBjb25zdCB7IHNlbGVjdG9yLCBmYW1pbGllcywgZGVmYXVsdEZhbWlseSB9ID0gZW50cnk7XG4gIGNvbnN0IG5vZGUgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHNlbGVjdG9yKTtcblxuICBpZiAoIW5vZGUpIHtcbiAgICByZXR1cm4ge1xuICAgICAgZXJyb3I6IGBFbGVtZW50IHdpdGggc2VsZWN0b3IgJHtlbnRyeS5zZWxlY3Rvcn0gbm90IGZvdW5kYFxuICAgIH07XG4gIH1cblxuICBjb25zdCBoZWFkZXIgPSBub2RlO1xuXG4gIGlmICghaGVhZGVyKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGVycm9yOiBgRWxlbWVudCB3aXRoIHNlbGVjdG9yICR7ZW50cnkuc2VsZWN0b3J9IGhhcyBubyBoZWFkZXJgXG4gICAgfTtcbiAgfVxuXG4gIGNvbnN0IG5hdiA9IGhlYWRlci5xdWVyeVNlbGVjdG9yKFwiI21haW4tbmF2aWdhdGlvblwiKTtcblxuICBpZiAoIW5hdikge1xuICAgIHJldHVybiB7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke2VudHJ5LnNlbGVjdG9yfSBoYXMgbm8gbmF2YFxuICAgIH07XG4gIH1cblxuICBjb25zdCBzdWJOYXYgPSBoZWFkZXIucXVlcnlTZWxlY3RvcihcIiNzZWxlY3RlZC1zdWItbmF2aWdhdGlvblwiKSA/PyB1bmRlZmluZWQ7XG4gIGxldCBkYXRhID0gZ2V0TWVudVYoeyBuYXYsIHNlbGVjdG9yLCBmYW1pbGllcywgZGVmYXVsdEZhbWlseSB9KTtcblxuICBpZiAoc3ViTmF2KSB7XG4gICAgY29uc3QgX3YgPSBnZXRTdWJNZW51Vih7IG5hdiwgc3ViTmF2LCBzZWxlY3RvciwgZmFtaWxpZXMsIGRlZmF1bHRGYW1pbHkgfSk7XG4gICAgZGF0YSA9IHsgLi4uZGF0YSwgLi4uX3YgfTtcbiAgfVxuXG4gIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YTogZGF0YSB9KTtcbn07XG5cbi8vIEZvciBkZXZlbG9wbWVudFxuLy8gd2luZG93LmlzRGV2ID0gdHJ1ZTtcbmNvbnN0IGRhdGEgPSBnZXREYXRhKCk7XG5jb25zdCBvdXRwdXQgPSBydW4oZGF0YSk7XG5cbmV4cG9ydCBkZWZhdWx0IG91dHB1dDtcbiIsICJpbXBvcnQgeyBNVmFsdWUgfSBmcm9tIFwiLi4vdHlwZXNcIjtcblxuaW50ZXJmYWNlIENvbG9yIHtcbiAgaGV4OiBzdHJpbmc7XG4gIG9wYWNpdHk6IHN0cmluZztcbn1cblxuY29uc3QgaGV4UmVnZXggPSAvXiMoPzpbQS1GYS1mMC05XXszfSl7MSwyfSQvO1xuY29uc3QgcmdiUmVnZXggPSAvXnJnYlxccypbKF1cXHMqKFxcZCspXFxzKixcXHMqKFxcZCspXFxzKixcXHMqKFxcZCspXFxzKlspXSQvO1xuY29uc3QgcmdiYVJlZ2V4ID1cbiAgL15yZ2JhXFxzKlsoXVxccyooXFxkKylcXHMqLFxccyooXFxkKylcXHMqLFxccyooXFxkKylcXHMqLFxccyooMCooPzpcXC5cXGQrKT98MSg/OlxcLjAqKT8pXFxzKlspXSQvO1xuXG5jb25zdCBpc0hleCA9ICh2OiBzdHJpbmcpOiBib29sZWFuID0+IGhleFJlZ2V4LnRlc3Qodik7XG5cbmNvbnN0IGZyb21SZ2IgPSAocmdiOiBbbnVtYmVyLCBudW1iZXIsIG51bWJlcl0pOiBzdHJpbmcgPT4ge1xuICByZXR1cm4gKFxuICAgIFwiI1wiICtcbiAgICAoXCIwXCIgKyByZ2JbMF0udG9TdHJpbmcoMTYpKS5zbGljZSgtMikgK1xuICAgIChcIjBcIiArIHJnYlsxXS50b1N0cmluZygxNikpLnNsaWNlKC0yKSArXG4gICAgKFwiMFwiICsgcmdiWzJdLnRvU3RyaW5nKDE2KSkuc2xpY2UoLTIpXG4gICk7XG59O1xuXG5mdW5jdGlvbiBwYXJzZVJnYihjb2xvcjogc3RyaW5nKTogTVZhbHVlPFtudW1iZXIsIG51bWJlciwgbnVtYmVyXT4ge1xuICBjb25zdCBtYXRjaGVzID0gcmdiUmVnZXguZXhlYyhjb2xvcik7XG5cbiAgaWYgKG1hdGNoZXMpIHtcbiAgICBjb25zdCBbciwgZywgYl0gPSBtYXRjaGVzLnNsaWNlKDEpLm1hcChOdW1iZXIpO1xuICAgIHJldHVybiBbciwgZywgYl07XG4gIH1cblxuICByZXR1cm4gdW5kZWZpbmVkO1xufVxuXG5mdW5jdGlvbiBwYXJzZVJnYmEoY29sb3I6IHN0cmluZyk6IE1WYWx1ZTxbbnVtYmVyLCBudW1iZXIsIG51bWJlciwgbnVtYmVyXT4ge1xuICBjb25zdCBtYXRjaGVzID0gcmdiYVJlZ2V4LmV4ZWMoY29sb3IpO1xuXG4gIGlmIChtYXRjaGVzKSB7XG4gICAgY29uc3QgW3IsIGcsIGIsIGFdID0gbWF0Y2hlcy5zbGljZSgxKS5tYXAoTnVtYmVyKTtcbiAgICByZXR1cm4gW3IsIGcsIGIsIGFdO1xuICB9XG5cbiAgcmV0dXJuIHVuZGVmaW5lZDtcbn1cblxuZXhwb3J0IGZ1bmN0aW9uIHBhcnNlQ29sb3JTdHJpbmcoY29sb3JTdHJpbmc6IHN0cmluZyk6IE1WYWx1ZTxDb2xvcj4ge1xuICBpZiAoaXNIZXgoY29sb3JTdHJpbmcpKSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGhleDogY29sb3JTdHJpbmcsXG4gICAgICBvcGFjaXR5OiBcIjFcIlxuICAgIH07XG4gIH1cblxuICBjb25zdCByZ2JSZXN1bHQgPSBwYXJzZVJnYihjb2xvclN0cmluZyk7XG4gIGlmIChyZ2JSZXN1bHQpIHtcbiAgICByZXR1cm4ge1xuICAgICAgaGV4OiBmcm9tUmdiKHJnYlJlc3VsdCksXG4gICAgICBvcGFjaXR5OiBcIjFcIlxuICAgIH07XG4gIH1cblxuICBjb25zdCByZ2JhUmVzdWx0ID0gcGFyc2VSZ2JhKGNvbG9yU3RyaW5nKTtcbiAgaWYgKHJnYmFSZXN1bHQpIHtcbiAgICBjb25zdCBbciwgZywgYiwgYV0gPSByZ2JhUmVzdWx0O1xuICAgIHJldHVybiB7XG4gICAgICBoZXg6IGZyb21SZ2IoW3IsIGcsIGJdKSxcbiAgICAgIG9wYWNpdHk6IFN0cmluZyhhKVxuICAgIH07XG4gIH1cblxuICByZXR1cm4gdW5kZWZpbmVkO1xufVxuIiwgImltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwiLi4vdHlwZXNcIjtcblxuZXhwb3J0IGNvbnN0IGdldE5vZGVTdHlsZSA9IChcbiAgbm9kZTogSFRNTEVsZW1lbnQgfCBFbGVtZW50XG4pOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPiA9PiB7XG4gIGNvbnN0IGNvbXB1dGVkU3R5bGVzID0gd2luZG93LmdldENvbXB1dGVkU3R5bGUobm9kZSk7XG4gIGNvbnN0IHN0eWxlczogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4gPSB7fTtcblxuICBPYmplY3QudmFsdWVzKGNvbXB1dGVkU3R5bGVzKS5mb3JFYWNoKChrZXkpID0+IHtcbiAgICBzdHlsZXNba2V5XSA9IGNvbXB1dGVkU3R5bGVzLmdldFByb3BlcnR5VmFsdWUoa2V5KTtcbiAgfSk7XG5cbiAgcmV0dXJuIHN0eWxlcztcbn07XG4iLCAiZXhwb3J0IGNvbnN0IGNhcGl0YWxpemUgPSAoc3RyOiBzdHJpbmcpOiBzdHJpbmcgPT4ge1xuICByZXR1cm4gc3RyLmNoYXJBdCgwKS50b1VwcGVyQ2FzZSgpICsgc3RyLnNsaWNlKDEpO1xufTtcbiIsICJpbXBvcnQgeyBjYXBpdGFsaXplIH0gZnJvbSBcIi4vY2FwaXRhbGl6ZVwiO1xuXG5leHBvcnQgY29uc3QgdG9DYW1lbENhc2UgPSAoa2V5OiBzdHJpbmcpOiBzdHJpbmcgPT4ge1xuICBjb25zdCBwYXJ0cyA9IGtleS5zcGxpdChcIi1cIik7XG4gIGZvciAobGV0IGkgPSAxOyBpIDwgcGFydHMubGVuZ3RoOyBpKyspIHtcbiAgICBwYXJ0c1tpXSA9IGNhcGl0YWxpemUocGFydHNbaV0pO1xuICB9XG4gIHJldHVybiBwYXJ0cy5qb2luKFwiXCIpO1xufTtcbiIsICJpbXBvcnQgeyBwYXJzZUNvbG9yU3RyaW5nIH0gZnJvbSBcInV0aWxzL3NyYy9jb2xvci9wYXJzZUNvbG9yU3RyaW5nXCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcbmltcG9ydCB7IHRvQ2FtZWxDYXNlIH0gZnJvbSBcInV0aWxzL3NyYy90ZXh0L3RvQ2FtZWxDYXNlXCI7XG5cbmludGVyZmFjZSBNb2RlbCB7XG4gIG5vZGU6IEVsZW1lbnQ7XG4gIGZhbWlsaWVzOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+O1xuICBkZWZhdWx0RmFtaWx5OiBzdHJpbmc7XG59XG5cbmNvbnN0IHYgPSB7XG4gIFwiZm9udC1mYW1pbHlcIjogdW5kZWZpbmVkLFxuICBcImZvbnQtZmFtaWx5LXR5cGVcIjogXCJ1cGxvYWRlZFwiLFxuICBcImZvbnQtd2VpZ2h0XCI6IHVuZGVmaW5lZCxcbiAgXCJmb250LXNpemVcIjogdW5kZWZpbmVkLFxuICBcImxpbmUtaGVpZ2h0XCI6IHVuZGVmaW5lZCxcbiAgXCJsZXR0ZXItc3BhY2luZ1wiOiB1bmRlZmluZWQsXG4gIGNvbG9ySGV4OiB1bmRlZmluZWQsXG4gIGNvbG9yT3BhY2l0eTogMSxcbiAgYWN0aXZlQ29sb3JIZXg6IHVuZGVmaW5lZCxcbiAgYWN0aXZlQ29sb3JPcGFjaXR5OiB1bmRlZmluZWRcbn07XG5cbmV4cG9ydCBjb25zdCBnZXRNb2RlbCA9IChkYXRhOiBNb2RlbCkgPT4ge1xuICBjb25zdCB7IG5vZGUsIGZhbWlsaWVzLCBkZWZhdWx0RmFtaWx5IH0gPSBkYXRhO1xuICBjb25zdCBzdHlsZXMgPSBnZXROb2RlU3R5bGUobm9kZSk7XG4gIGNvbnN0IGRpYzogUmVjb3JkPHN0cmluZywgc3RyaW5nIHwgbnVtYmVyPiA9IHt9O1xuXG4gIE9iamVjdC5rZXlzKHYpLmZvckVhY2goKGtleSkgPT4ge1xuICAgIHN3aXRjaCAoa2V5KSB7XG4gICAgICBjYXNlIFwiZm9udC1mYW1pbHlcIjoge1xuICAgICAgICBjb25zdCB2YWx1ZSA9IGAke3N0eWxlc1trZXldfWA7XG4gICAgICAgIGNvbnN0IGZvbnRGYW1pbHkgPSB2YWx1ZVxuICAgICAgICAgIC5yZXBsYWNlKC9bJ1wiXFwsXS9nLCBcIlwiKSAvLyBlc2xpbnQtZGlzYWJsZS1saW5lXG4gICAgICAgICAgLnJlcGxhY2UoL1xccy9nLCBcIl9cIilcbiAgICAgICAgICAudG9Mb2NhbGVMb3dlckNhc2UoKTtcblxuICAgICAgICBpZiAoIWZhbWlsaWVzW2ZvbnRGYW1pbHldKSB7XG4gICAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gZGVmYXVsdEZhbWlseTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSBmYW1pbGllc1tmb250RmFtaWx5XTtcbiAgICAgICAgfVxuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJmb250LWZhbWlseS10eXBlXCI6IHtcbiAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gXCJ1cGxvYWRcIjtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwibGluZS1oZWlnaHRcIjoge1xuICAgICAgICBjb25zdCB2YWx1ZSA9IHBhcnNlSW50KGAke3N0eWxlc1trZXldfWApO1xuICAgICAgICBpZiAoaXNOYU4odmFsdWUpKSB7XG4gICAgICAgICAgZGljW3RvQ2FtZWxDYXNlKGtleSldID0gMTtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSB2YWx1ZTtcbiAgICAgICAgfVxuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJmb250LXNpemVcIjoge1xuICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSBwYXJzZUludChgJHtzdHlsZXNba2V5XX1gKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwibGV0dGVyLXNwYWNpbmdcIjoge1xuICAgICAgICBjb25zdCB2YWx1ZSA9IHN0eWxlc1trZXldO1xuICAgICAgICBpZiAodmFsdWUgPT09IFwibm9ybWFsXCIpIHtcbiAgICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSAwO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgIC8vIFJlbW92ZSAncHgnIGFuZCBhbnkgZXh0cmEgd2hpdGVzcGFjZVxuICAgICAgICAgIGNvbnN0IGxldHRlclNwYWNpbmdWYWx1ZSA9IGAke3ZhbHVlfWAucmVwbGFjZSgvcHgvZywgXCJcIikudHJpbSgpO1xuICAgICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9ICtsZXR0ZXJTcGFjaW5nVmFsdWU7XG4gICAgICAgIH1cbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiY29sb3JIZXhcIjoge1xuICAgICAgICBjb25zdCB0b0hleCA9IHBhcnNlQ29sb3JTdHJpbmcoYCR7c3R5bGVzW1wiY29sb3JcIl19YCk7XG4gICAgICAgIGRpY1t0b0NhbWVsQ2FzZShrZXkpXSA9IHRvSGV4Py5oZXggPz8gXCIjMDAwMDAwXCI7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImNvbG9yT3BhY2l0eVwiOiB7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgZGVmYXVsdDoge1xuICAgICAgICBkaWNbdG9DYW1lbENhc2Uoa2V5KV0gPSBzdHlsZXNba2V5XTtcbiAgICAgIH1cbiAgICB9XG4gIH0pO1xuXG4gIHJldHVybiBkaWM7XG59O1xuIiwgImltcG9ydCB7IEVudHJ5LCBPdXRwdXQsIE91dHB1dERhdGEgfSBmcm9tIFwiLi4vdHlwZXMvdHlwZVwiO1xuXG5leHBvcnQgY29uc3QgZ2V0RGF0YSA9ICgpOiBFbnRyeSA9PiB7XG4gIHRyeSB7XG4gICAgLy8gRm9yIGRldmVsb3BtZW50XG4gICAgLy8gd2luZG93LmlzRGV2ID0gdHJ1ZTtcbiAgICByZXR1cm4gd2luZG93LmlzRGV2XG4gICAgICA/IHtcbiAgICAgICAgICBzZWxlY3RvcjogYFtkYXRhLWlkPSckezQzNDE1Nzl9J11gLFxuICAgICAgICAgIGZhbWlsaWVzOiB7XG4gICAgICAgICAgICBcInByb3hpbWFfbm92YV9wcm94aW1hX25vdmFfcmVndWxhcl9zYW5zLXNlcmlmXCI6IFwidWlkMTExMVwiLFxuICAgICAgICAgICAgXCJoZWx2ZXRpY2FfbmV1ZV9oZWx2ZXRpY2FuZXVlX2hlbHZldGljYV9hcmlhbF9zYW5zLXNlcmlmXCI6IFwidWlkMjIyMlwiXG4gICAgICAgICAgfSxcbiAgICAgICAgICBkZWZhdWx0RmFtaWx5OiBcImxhdG9cIlxuICAgICAgICB9XG4gICAgICA6IHtcbiAgICAgICAgICBzZWxlY3RvcjogU0VMRUNUT1IsXG4gICAgICAgICAgZmFtaWxpZXM6IEZBTUlMSUVTLFxuICAgICAgICAgIGRlZmF1bHRGYW1pbHk6IERFRkFVTFRfRkFNSUxZXG4gICAgICAgIH07XG4gIH0gY2F0Y2ggKGUpIHtcbiAgICBjb25zdCBmYW1pbHlNb2NrID0ge1xuICAgICAgbGF0bzogXCJ1aWRfZm9yX2xhdG9cIixcbiAgICAgIHJvYm90bzogXCJ1aWRfZm9yX3JvYm90b1wiXG4gICAgfTtcbiAgICBjb25zdCBtb2NrOiBFbnRyeSA9IHtcbiAgICAgIHNlbGVjdG9yOiBcIi5teS1kaXZcIixcbiAgICAgIGZhbWlsaWVzOiBmYW1pbHlNb2NrLFxuICAgICAgZGVmYXVsdEZhbWlseTogXCJsYXRvXCJcbiAgICB9O1xuXG4gICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgSlNPTi5zdHJpbmdpZnkoe1xuICAgICAgICBlcnJvcjogYEludmFsaWQgSlNPTiAke2V9YCxcbiAgICAgICAgZGV0YWlsczogYE11c3QgYmU6ICR7SlNPTi5zdHJpbmdpZnkobW9jayl9YFxuICAgICAgfSlcbiAgICApO1xuICB9XG59O1xuXG5leHBvcnQgY29uc3QgY3JlYXRlRGF0YSA9IChvdXRwdXQ6IE91dHB1dERhdGEpOiBPdXRwdXQgPT4ge1xuICByZXR1cm4gb3V0cHV0O1xufTtcbiIsICJpbXBvcnQgeyBjYXBpdGFsaXplIH0gZnJvbSBcIi4uL3RleHQvY2FwaXRhbGl6ZVwiO1xuXG5leHBvcnQgY29uc3QgcHJlZml4ZWQgPSA8VCBleHRlbmRzIFJlY29yZDxzdHJpbmcsIHVua25vd24+PihcbiAgdjogVCxcbiAgcHJlZml4OiBzdHJpbmdcbik6IFQgPT4ge1xuICByZXR1cm4gT2JqZWN0LmVudHJpZXModikucmVkdWNlKChhY2MsIFtrZXksIHZhbHVlXSkgPT4ge1xuICAgIGxldCBfa2V5ID0gcHJlZml4ICsgY2FwaXRhbGl6ZShrZXkpO1xuXG4gICAgaWYgKGtleS5zdGFydHNXaXRoKFwiYWN0aXZlXCIpKSB7XG4gICAgICBfa2V5ID0gYGFjdGl2ZSR7Y2FwaXRhbGl6ZShwcmVmaXgpfSR7a2V5LnJlcGxhY2UoXCJhY3RpdmVcIiwgXCJcIil9YDtcbiAgICB9XG5cbiAgICByZXR1cm4geyAuLi5hY2MsIFtfa2V5XTogdmFsdWUgfTtcbiAgfSwge30gYXMgVCk7XG59O1xuIl0sCiAgIm1hcHBpbmdzIjogIjs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FBQUE7QUFBQTtBQUFBO0FBQUE7OztBQ09BLE1BQU0sV0FBVztBQUNqQixNQUFNLFdBQVc7QUFDakIsTUFBTSxZQUNKO0FBRUYsTUFBTSxRQUFRLENBQUNBLE9BQXVCLFNBQVMsS0FBS0EsRUFBQztBQUVyRCxNQUFNLFVBQVUsQ0FBQyxRQUEwQztBQUN6RCxXQUNFLE9BQ0MsTUFBTSxJQUFJLENB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
