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
      isHex = (v2) => hexRegex.test(v2);
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
        const opacity = +styles["opacity"];
        if (color) {
          window.menuModel = {
            hoverColorHex: color.hex,
            hoverColorOpacity: isNaN(opacity) ? color.opacity : opacity
          };
        }
      };
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

  // src/Menu/model/getModel.ts
  var v, getModel;
  var init_getModel = __esm({
    "src/Menu/model/getModel.ts"() {
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
        colorHex: void 0,
        colorOpacity: 1,
        activeColorHex: void 0,
        activeColorOpacity: void 0
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

  // ../../../../../../../packages/utils/src/models/prefixed.ts
  var prefixed;
  var init_prefixed = __esm({
    "../../../../../../../packages/utils/src/models/prefixed.ts"() {
      "use strict";
      init_capitalize();
      prefixed = (v2, prefix) => {
        return Object.entries(v2).reduce((acc, [key, value]) => {
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
  var warns, getMenuV, getSubMenuV, run2;
  var init_Menu = __esm({
    "src/Menu/index.ts"() {
      "use strict";
      init_getGlobalMenuModel();
      init_getModel();
      init_getData();
      init_parseColorString();
      init_prefixed();
      warns = {};
      getMenuV = (data) => {
        const { nav, selector } = data;
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
          families: data.families,
          defaultFamily: data.defaultFamily
        });
        const globalStyle = getGlobalMenuModel();
        return { ...globalStyle, ...v2, itemPadding: 20 };
      };
      getSubMenuV = (data) => {
        const { subNav: ul, header, selector } = data;
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
          families: data.families,
          defaultFamily: data.defaultFamily
        });
        const submenuTypography = prefixed(typography, "subMenu");
        const baseStyle = window.getComputedStyle(header);
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
      run2 = (entry) => {
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
        let data = getMenuV({ nav, header, selector, families, defaultFamily });
        if (subNav) {
          const _v = getSubMenuV({
            nav,
            header,
            subNav,
            selector,
            families,
            defaultFamily
          });
          data = { ...data, ..._v };
        }
        return createData({ data, warns });
      };
    }
  });

  // ../../../../../../../packages/elements/src/StyleExtractor/index.ts
  var styleExtractor;
  var init_StyleExtractor = __esm({
    "../../../../../../../packages/elements/src/StyleExtractor/index.ts"() {
      "use strict";
      init_getData();
      styleExtractor = (entry) => {
        const { selector, styleProperties } = entry;
        const data = {};
        const element = document.querySelector(selector);
        if (!element) {
          return {
            error: `Element with selector ${selector} not found`
          };
        }
        const computedStyles = getComputedStyle(element);
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
      var isNothing = function(v2) {
        return v2 === null || v2 === void 0;
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
        return args.length === 1 ? function(v2) {
          return (0, exports.isNothing)(v2) ? args[0] : v2;
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
          return args.every(Nothing_1.isT) ? (_a2 = fns.reduce(function(v2, fn) {
            return (0, Nothing_1.isT)(v2) ? fn(v2) : void 0;
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
      var isOptional = function(v2) {
        return v2.__type === "optional";
      };
      exports.isOptional = isOptional;
      var call = function(p, v2) {
        switch (p.__type) {
          case "optional":
          case "strict":
            return p.fn(v2);
          default:
            return p(v2);
        }
      };
      exports.call = call;
      function _parse(parsers, object) {
        var b = {};
        for (var p in parsers) {
          if (!Object.prototype.hasOwnProperty.call(parsers, p)) {
            continue;
          }
          var v2 = (0, exports.call)(parsers[p], object);
          if (!(0, exports.isOptional)(parsers[p]) && (0, Nothing_1.isNothing)(v2)) {
            return void 0;
          }
          b[p] = v2;
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
            var v2 = (_a = fns[i]) === null || _a === void 0 ? void 0 : _a.call.apply(_a, __spreadArray([fns], args, false));
            if (!(0, Nothing_1.isNothing)(v2)) {
              return v2;
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
  var read;
  var init_string = __esm({
    "../../../../../../../packages/utils/src/reader/string.ts"() {
      "use strict";
      read = (v2) => {
        switch (typeof v2) {
          case "string":
            return v2;
          case "number":
            return isNaN(v2) ? void 0 : v2.toString();
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
  var import_fp_utilities, allowedTags, exceptExtractingStyle, extractedAttributes, textAlign, iconSelector, buttonSelector, embedSelector, getHref, getTarget, normalizeOpacity;
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
      getHref = (0, import_fp_utilities.mPipe)(readKey("href"), read);
      getTarget = (0, import_fp_utilities.mPipe)(readKey("target"), read);
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
  function getModel2(node) {
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
        name: iconCode ? codeToBuilderMap[iconCode] ?? "favourite-31" : "favourite-31",
        ...isLink && {
          linkExternal: parentHref,
          linkType: "external",
          linkExternalBlank: "on"
        }
      }
    };
  }
  var import_fp_utilities2, codeToBuilderMap, getColor, getBgColor, getStyles, getParentStyles, getStyleModel;
  var init_getModel2 = __esm({
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
      codeToBuilderMap = {
        apple: "apple",
        57351: "apple",
        57686: "pin-3",
        mail: "email-85",
        57892: "email-85",
        57380: "email-85",
        57936: "note-03",
        facebook: "logo-facebook",
        57895: "logo-facebook",
        58407: "logo-facebook",
        61570: "logo-facebook",
        youtube: "logo-youtube",
        58009: "logo-youtube",
        58521: "logo-youtube",
        62513: "logo-youtube",
        vimeo: "logo-vimeo",
        57993: "logo-vimeo",
        twitter: "logo-twitter",
        57990: "logo-twitter",
        58503: "logo-twitter",
        instagram: "logo-instagram",
        58624: "logo-instagram",
        58112: "logo-instagram",
        61805: "logo-instagram",
        58211: "circle-right-37",
        63244: "user-run"
      };
      getColor = (0, import_fp_utilities2.mPipe)(
        readKey("color"),
        read,
        parseColorString,
        normalizeOpacity
      );
      getBgColor = (0, import_fp_utilities2.mPipe)(
        readKey("background-color"),
        read,
        parseColorString,
        normalizeOpacity
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
            colorHex: color.hex,
            colorOpacity: isNaN(opacity) ? color.opacity : opacity,
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
    return (...args) => fns.reduce((v2, fn) => fn(v2), h(...args));
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
      isNullish = (v2) => v2 === void 0 || v2 === null || typeof v2 === "number" && Number.isNaN(v2);
    }
  });

  // ../../../../../../../packages/utils/src/onNullish.ts
  function onNullish(...args) {
    return args.length === 1 ? (v2) => isNullish(v2) ? args[0] : v2 : isNullish(args[1]) ? args[0] : args[1];
  }
  var init_onNullish = __esm({
    "../../../../../../../packages/utils/src/onNullish.ts"() {
      "use strict";
      init_isNullish();
    }
  });

  // ../../../../../../../packages/utils/src/reader/number.ts
  var read2, readInt;
  var init_number = __esm({
    "../../../../../../../packages/utils/src/reader/number.ts"() {
      "use strict";
      read2 = (v2) => {
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
      readInt = (v2) => {
        if (typeof v2 === "string") {
          return parseInt(v2);
        }
        return read2(v2);
      };
    }
  });

  // ../../../../../../../packages/elements/src/Text/models/Button/utils/getModel.ts
  var import_fp_utilities3, getColor2, getBgColor2, getBorderWidth, getTransform, getText, getBgColorOpacity, getStyleModel2, getModel3;
  var init_getModel3 = __esm({
    "../../../../../../../packages/elements/src/Text/models/Button/utils/getModel.ts"() {
      "use strict";
      init_getGlobalButtonModel();
      init_common();
      init_getModel2();
      import_fp_utilities3 = __toESM(require_dist());
      init_parseColorString();
      init_getNodeStyle();
      init_pipe();
      init_onNullish();
      init_number();
      init_object();
      init_string();
      init_uuid();
      getColor2 = (0, import_fp_utilities3.mPipe)(
        readKey("color"),
        read,
        parseColorString,
        normalizeOpacity
      );
      getBgColor2 = (0, import_fp_utilities3.mPipe)(
        readKey("background-color"),
        read,
        parseColorString,
        normalizeOpacity
      );
      getBorderWidth = (0, import_fp_utilities3.mPipe)(readKey("border-width"), read2);
      getTransform = (0, import_fp_utilities3.mPipe)(readKey("text-transform"), read);
      getText = pipe(readKey("text"), read, onNullish("BUTTON"));
      getBgColorOpacity = (color, opacity) => {
        const colorOpacity = +color.opacity;
        if (colorOpacity === 0) {
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
            colorHex: color.hex,
            colorOpacity: +color.opacity,
            colorPalette: ""
          },
          ...bgColor && {
            bgColorHex: bgColor.hex,
            bgColorOpacity: getBgColorOpacity(bgColor, opacity),
            bgColorPalette: "",
            ...getBgColorOpacity(bgColor, opacity) === 0 ? { bgColorType: "none" } : { bgColorType: "solid" }
          },
          ...borderWidth === void 0 && { borderStyle: "none" }
        };
      };
      getModel3 = (node) => {
        const isLink = node.tagName === "A";
        const modelStyle = getStyleModel2(node);
        const globalModel = getGlobalButtonModel();
        const textTransform = getTransform(getNodeStyle(node));
        let iconModel = {};
        const icon = node.querySelector(iconSelector);
        if (icon) {
          const model = getModel2(icon);
          const name = read(model.value.name);
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
      const model = getModel3(button);
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
      init_getModel3();
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
      const model = getModel2(icon);
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
      init_getModel2();
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
    const elementsWithStyles = node.querySelectorAll(
      allowedTags.join(",") + "[style]"
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
              const lineHeight = getLineHeight(`${value}`, fontSize);
              classes.push(`brz-lh-lg-${lineHeight}`);
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
  var Stack, extractInnerText, getContainerStackWithNodes;
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
            const text = _node.textContent;
            if (text && text.trim()) {
              stack.append(_node, { type: "text" });
            }
          }
        }
      };
      getContainerStackWithNodes = (node) => {
        const container = document.createElement("div");
        const stack = new Stack();
        let appendNewText = false;
        node.childNodes.forEach((node2) => {
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
                _node.childNodes.forEach((node3) => {
                  if (node3 instanceof HTMLElement) {
                    const container2 = document.createElement("div");
                    container2.append(node3.cloneNode(true));
                    if (container2.querySelector(iconSelector)) {
                      if (!appendedIcon) {
                        stack.append(_node, { type: "icon" });
                        appendedIcon = true;
                      }
                    } else {
                      const text = node3.textContent;
                      if (text?.trim()) {
                        extractInnerText(_node, stack, iconSelector);
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
      init_Button();
      init_Embed();
      init_Icon();
      init_Text();
      init_getContainerStackWithNodes();
      getText2 = (entry) => {
        let node = document.querySelector(entry.selector);
        if (!node) {
          return {
            error: `Element with selector ${entry.selector} not found`
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
      init_GlobalMenu();
      init_Menu();
      init_StyleExtractor2();
      init_Text3();
      window.brizy = {
        globalMenuExtractor: run,
        getMenu: run2,
        getStyles: styleExtractor,
        getText: getText2
      };
    }
  });
  require_src();
})();
