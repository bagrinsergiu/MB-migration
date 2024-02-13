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
  var globalMenuExtractor;
  var init_GlobalMenu = __esm({
    "src/GlobalMenu/index.ts"() {
      "use strict";
      init_parseColorString();
      init_getNodeStyle();
      globalMenuExtractor = () => {
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

  // ../../../../../../../packages/elements/src/utils/getGlobalButtonModel.ts
  var init_getGlobalButtonModel = __esm({
    "../../../../../../../packages/elements/src/utils/getGlobalButtonModel.ts"() {
      "use strict";
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
  var import_fp_utilities, iconSelector, buttonSelector, extractUrlWithoutDomain, getHref, getTarget, normalizeOpacity;
  var init_common = __esm({
    "../../../../../../../packages/elements/src/Text/utils/common/index.ts"() {
      "use strict";
      import_fp_utilities = __toESM(require_dist());
      init_object();
      init_string();
      iconSelector = `[data-socialicon],[style*="font-family: 'Mono Social Icons Font'"],[data-icon]`;
      buttonSelector = ".sites-button:not(.nav-menu-button)";
      extractUrlWithoutDomain = (url) => {
        const urlWithoutDomain = new URL(url).pathname;
        return urlWithoutDomain;
      };
      getHref = (0, import_fp_utilities.mPipe)(
        readKey("href"),
        read,
        extractUrlWithoutDomain
      );
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

  // ../../../../../../../packages/elements/src/utils/getGlobalIconModel.ts
  var init_getGlobalIconModel = __esm({
    "../../../../../../../packages/elements/src/utils/getGlobalIconModel.ts"() {
      "use strict";
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

  // ../../../../../../../packages/utils/src/uuid.ts
  var init_uuid = __esm({
    "../../../../../../../packages/utils/src/uuid.ts"() {
      "use strict";
    }
  });

  // ../../../../../../../packages/elements/src/Text/models/Icon/utils/getModel.ts
  var import_fp_utilities2, getColor, getBgColor, getStyles, getParentStyles, getStyleModel;
  var init_getModel = __esm({
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
  var read2;
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
    }
  });

  // ../../../../../../../packages/elements/src/Text/models/Button/utils/getModel.ts
  var import_fp_utilities3, getColor2, getBgColor2, getBorderWidth, getTransform, getText, getBgColorOpacity, getStyleModel2;
  var init_getModel2 = __esm({
    "../../../../../../../packages/elements/src/Text/models/Button/utils/getModel.ts"() {
      "use strict";
      init_getGlobalButtonModel();
      init_common();
      init_getModel();
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

  // ../../../../../../../packages/elements/src/Globals/index.ts
  var globalExtractor;
  var init_Globals = __esm({
    "../../../../../../../packages/elements/src/Globals/index.ts"() {
      "use strict";
      init_getModel2();
      init_getModel();
      init_common();
      init_prefixed();
      globalExtractor = () => {
        const icon = document.querySelector(iconSelector);
        const button = document.querySelector(buttonSelector);
        if (!icon && !button) {
          return;
        }
        if (icon) {
          const model = getStyleModel(icon);
          window.iconModel = prefixed(model, "hover");
        }
        if (button) {
          const model = getStyleModel2(button);
          window.buttonModel = prefixed(model, "hover");
        }
      };
    }
  });

  // src/Globals/index.ts
  var init_Globals2 = __esm({
    "src/Globals/index.ts"() {
      "use strict";
      init_Globals();
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
  var v, getModel2;
  var init_getModel3 = __esm({
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
      getModel2 = (data) => {
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

  // src/Menu/index.ts
  var warns, getMenuV, getSubMenuV, run;
  var init_Menu = __esm({
    "src/Menu/index.ts"() {
      "use strict";
      init_getGlobalMenuModel();
      init_getModel3();
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
        v2 = getModel2({
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
        const typography = getModel2({
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
      run = (entry) => {
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

  // src/index.ts
  var require_src = __commonJS({
    "src/index.ts"() {
      init_GlobalMenu();
      init_Globals2();
      init_Menu();
      init_StyleExtractor2();
      window.brizy = {
        globalMenuExtractor,
        globalExtractor,
        getMenu: run,
        getStyles: styleExtractor
      };
    }
  });
  require_src();
})();
