"use strict";
var output = (() => {
  var __getOwnPropNames = Object.getOwnPropertyNames;
  var __esm = (fn, res) => function __init() {
    return fn && (res = (0, fn[__getOwnPropNames(fn)[0]])(fn = 0)), res;
  };
  var __commonJS = (cb, mod) => function __require() {
    return mod || (0, cb[__getOwnPropNames(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
  };

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
  var hexRegex, rgbRegex, rgbaRegex, isHex, fromRgb;
  var init_parseColorString = __esm({
    "../../../../../../../packages/utils/src/color/parseColorString.ts"() {
      "use strict";
      hexRegex = /^#(?:[A-Fa-f0-9]{3}){1,2}$/;
      rgbRegex = /^rgb\s*[(]\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*[)]$/;
      rgbaRegex = /^rgba\s*[(]\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(0*(?:\.\d+)?|1(?:\.0*)?)\s*[)]$/;
      isHex = (v) => hexRegex.test(v);
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
      getNodeStyle = (node) => {
        const computedStyles = window.getComputedStyle(node);
        const styles = {};
        Object.values(computedStyles).forEach((key) => {
          styles[key] = computedStyles.getPropertyValue(key);
        });
        return styles;
      };
    }
  });

  // src/GlobalMenu/index.ts
  var require_GlobalMenu = __commonJS({
    "src/GlobalMenu/index.ts"() {
      init_parseColorString();
      init_getNodeStyle();
      var globalMenuExtractor = () => {
        const menuItem = document.querySelector("#main-navigation a");
        if (!menuItem) {
          return;
        }
        const styles = getNodeStyle(menuItem);
        const color = parseColorString(`${styles["color"]}`);
        const opacity = +styles["opacity"];
        if (color) {
          window.menuModel = {
            hoverColorHex: color.hex,
            hoverColorOpacity: isNaN(opacity) ? color.opacity : opacity
          };
        }
      };
      globalMenuExtractor();
    }
  });
  return require_GlobalMenu();
})();
