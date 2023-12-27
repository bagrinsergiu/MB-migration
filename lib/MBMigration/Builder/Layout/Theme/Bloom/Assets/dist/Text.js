"use strict";
var output = (() => {
  var __create = Object.create;
  var __defProp = Object.defineProperty;
  var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
  var __getOwnPropNames = Object.getOwnPropertyNames;
  var __getProtoOf = Object.getPrototypeOf;
  var __hasOwnProp = Object.prototype.hasOwnProperty;
  var __commonJS = (cb, mod) => function __require() {
    return mod || (0, cb[__getOwnPropNames(cb)[0]])((mod = { exports: {} }).exports, mod), mod.exports;
  };
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
  var __toESM = (mod, isNodeMode, target) => (target = mod != null ? __create(__getProtoOf(mod)) : {}, __copyProps(
    // If the importer is in node compatibility mode or this is not an ESM
    // file that has been converted to a CommonJS file using a Babel-
    // compatible transform (i.e. "__esModule" has not been set), then set
    // "default" to the CommonJS "module.exports" for node compatibility.
    isNodeMode || !mod || !mod.__esModule ? __defProp(target, "default", { value: mod, enumerable: true }) : target,
    mod
  ));
  var __toCommonJS = (mod) => __copyProps(__defProp({}, "__esModule", { value: true }), mod);

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
      var isNothing = function(v) {
        return v === null || v === void 0;
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
        return args.length === 1 ? function(v) {
          return (0, exports.isNothing)(v) ? args[0] : v;
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
          return args.every(Nothing_1.isT) ? (_a2 = fns.reduce(function(v, fn) {
            return (0, Nothing_1.isT)(v) ? fn(v) : void 0;
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
      var isOptional = function(v) {
        return v.__type === "optional";
      };
      exports.isOptional = isOptional;
      var call = function(p, v) {
        switch (p.__type) {
          case "optional":
          case "strict":
            return p.fn(v);
          default:
            return p(v);
        }
      };
      exports.call = call;
      function _parse(parsers, object) {
        var b = {};
        for (var p in parsers) {
          if (!Object.prototype.hasOwnProperty.call(parsers, p)) {
            continue;
          }
          var v = (0, exports.call)(parsers[p], object);
          if (!(0, exports.isOptional)(parsers[p]) && (0, Nothing_1.isNothing)(v)) {
            return void 0;
          }
          b[p] = v;
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
            var v = (_a = fns[i]) === null || _a === void 0 ? void 0 : _a.call.apply(_a, __spreadArray([fns], args, false));
            if (!(0, Nothing_1.isNothing)(v)) {
              return v;
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

  // src/Text/index.ts
  var Text_exports = {};
  __export(Text_exports, {
    default: () => Text_default
  });

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

  // ../../../../../../../node_modules/nanoid/index.browser.js
  var random = (bytes) => crypto.getRandomValues(new Uint8Array(bytes));
  var customRandom = (alphabet2, defaultSize, getRandom) => {
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
  var customAlphabet = (alphabet2, size = 21) => customRandom(alphabet2, size, random);

  // ../../../../../../../packages/utils/src/uuid.ts
  var alphabet = "abcdefghijklmnopqrstuvwxyz";
  var fullSymbolList = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
  var uuid = (length = 12) => customAlphabet(alphabet, 1)() + customAlphabet(fullSymbolList, length)(length - 1);

  // ../../../../../../../packages/elements/src/Models/Cloneable/index.ts
  var createCloneableModel = (data2) => {
    const { _styles, items, ...value } = data2;
    return {
      type: "Cloneable",
      value: { _id: uuid(), _styles, items, ...value }
    };
  };

  // ../../../../../../../packages/elements/src/Text/utils/common/index.ts
  var import_fp_utilities = __toESM(require_dist());

  // ../../../../../../../packages/utils/src/reader/object.ts
  var hasKey = (key, obj) => key in obj;
  var readKey = (key) => (
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (obj) => hasKey(key, obj) ? obj[key] : void 0
  );

  // ../../../../../../../packages/utils/src/reader/string.ts
  var read = (v) => {
    switch (typeof v) {
      case "string":
        return v;
      case "number":
        return isNaN(v) ? void 0 : v.toString();
      default:
        return void 0;
    }
  };

  // ../../../../../../../packages/elements/src/Text/utils/common/index.ts
  var allowedTags = [
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
  var exceptExtractingStyle = ["UL", "OL"];
  var extractedAttributes = [
    "font-size",
    "font-family",
    "font-weight",
    "text-align",
    "letter-spacing",
    "text-transform"
  ];
  var textAlign = {
    "-webkit-center": "center",
    "-moz-center": "center",
    start: "left",
    end: "right",
    left: "left",
    right: "right",
    center: "center",
    justify: "justify"
  };
  function shouldExtractElement(element, exceptions) {
    const isAllowed = allowedTags.includes(element.tagName);
    if (isAllowed && exceptions) {
      return !exceptions.includes(element.tagName);
    }
    return isAllowed;
  }
  var iconSelector = `[data-socialicon],[style*="font-family: 'Mono Social Icons Font'"],[data-icon]`;
  var buttonSelector = ".sites-button:not(.nav-menu-button)";
  var embedSelector = ".embedded-paste";
  var getHref = (0, import_fp_utilities.mPipe)(readKey("href"), read);
  var normalizeOpacity = (color) => {
    const { hex, opacity } = color;
    return {
      hex,
      opacity: hex === "#ffffff" && opacity === "1" ? "0.99" : opacity
    };
  };

  // ../../../../../../../packages/elements/src/utils/getGlobalButtonModel.ts
  var getGlobalButtonModel = () => {
    return window.buttonModel;
  };

  // ../../../../../../../packages/elements/src/Text/models/Button/utils/getModel.ts
  var import_fp_utilities2 = __toESM(require_dist());

  // ../../../../../../../packages/utils/src/color/parseColorString.ts
  var hexRegex = /^#(?:[A-Fa-f0-9]{3}){1,2}$/;
  var rgbRegex = /^rgb\s*[(]\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*[)]$/;
  var rgbaRegex = /^rgba\s*[(]\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(0*(?:\.\d+)?|1(?:\.0*)?)\s*[)]$/;
  var isHex = (v) => hexRegex.test(v);
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

  // ../../../../../../../packages/utils/src/fp/pipe.ts
  function pipe(...[h, ...fns]) {
    return (...args) => fns.reduce((v, fn) => fn(v), h(...args));
  }

  // ../../../../../../../packages/utils/src/isNullish.ts
  var isNullish = (v) => v === void 0 || v === null || typeof v === "number" && Number.isNaN(v);

  // ../../../../../../../packages/utils/src/onNullish.ts
  function onNullish(...args) {
    return args.length === 1 ? (v) => isNullish(v) ? args[0] : v : isNullish(args[1]) ? args[0] : args[1];
  }

  // ../../../../../../../packages/elements/src/Text/models/Button/utils/getModel.ts
  var getColor = (0, import_fp_utilities2.mPipe)(
    readKey("color"),
    read,
    parseColorString,
    normalizeOpacity
  );
  var getBgColor = (0, import_fp_utilities2.mPipe)(
    readKey("background-color"),
    read,
    parseColorString,
    normalizeOpacity
  );
  var getText = pipe(readKey("text"), read, onNullish("BUTTON"));
  var getStyleModel = (node) => {
    const style = getNodeStyle(node);
    const color = getColor(style);
    const bgColor = getBgColor(style);
    const opacity = +style.opacity;
    return {
      ...color && {
        colorHex: color.hex,
        colorOpacity: color.opacity,
        colorPalette: ""
      },
      ...bgColor && {
        bgColorHex: bgColor.hex,
        bgColorOpacity: isNaN(opacity) ? bgColor.opacity ?? 1 : opacity,
        bgColorPalette: "",
        bgColorType: "solid"
      }
    };
  };
  var getModel = (node) => {
    const isLink = node.tagName === "A";
    const modelStyle = getStyleModel(node);
    const globalModel = getGlobalButtonModel();
    return {
      type: "Button",
      value: {
        _id: uuid(),
        _styles: ["button"],
        text: getText(node),
        ...globalModel,
        ...modelStyle,
        ...isLink && {
          linkExternal: getHref(node),
          linkType: "external",
          linkExternalBlank: "on"
        }
      }
    };
  };

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

  // ../../../../../../../packages/elements/src/Text/models/Button/index.ts
  function getButtonModel(node) {
    const buttons = node.querySelectorAll(buttonSelector);
    const groups = /* @__PURE__ */ new Map();
    buttons.forEach((button) => {
      const parentElement = findNearestBlockParent(button);
      const style = getNodeStyle(button);
      const model = getModel(button);
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

  // ../../../../../../../packages/elements/src/Text/models/Embed/index.ts
  function getEmbedModel(node) {
    const embeds = node.querySelectorAll(embedSelector);
    const models = [];
    embeds.forEach(() => {
      models.push({ type: "EmbedCode" });
    });
    return models;
  }

  // ../../../../../../../packages/elements/src/utils/getGlobalIconModel.ts
  var getGlobalIconModel = () => {
    return window.iconModel;
  };

  // ../../../../../../../packages/elements/src/Text/models/Icon/utils/getModel.ts
  var import_fp_utilities3 = __toESM(require_dist());

  // ../../../../../../../packages/utils/src/dom/getParentElementOfTextNode.ts
  function getParentElementOfTextNode(node) {
    if (node.nodeType === Node.TEXT_NODE) {
      return node.parentNode ?? void 0;
    }
    return Array.from(node.childNodes).find(
      (node2) => getParentElementOfTextNode(node2)
    );
  }

  // ../../../../../../../packages/elements/src/Text/models/Icon/utils/getModel.ts
  var codeToBuilderMap = {
    apple: "apple",
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
    61805: "logo-instagram"
  };
  var getColor2 = (0, import_fp_utilities3.mPipe)(
    readKey("color"),
    read,
    parseColorString,
    normalizeOpacity
  );
  var getBgColor2 = (0, import_fp_utilities3.mPipe)(
    readKey("background-color"),
    read,
    parseColorString,
    normalizeOpacity
  );
  var getStyles = (node) => {
    const parentNode = getParentElementOfTextNode(node);
    const isIconText = parentNode?.nodeName === "#text";
    const iconNode = isIconText ? node : parentNode;
    return iconNode ? getNodeStyle(iconNode) : {};
  };
  var getParentStyles = (node) => {
    const parentElement = node.parentElement;
    return parentElement ? getNodeStyle(parentElement) : {};
  };
  var getStyleModel2 = (node) => {
    const style = getStyles(node);
    const parentStyle = getParentStyles(node);
    const opacity = +style.opacity;
    const color = getColor2(style);
    const parentBgColor = getBgColor2(parentStyle);
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
  function getModel2(node) {
    const parentNode = getParentElementOfTextNode(node);
    const isIconText = parentNode?.nodeName === "#text";
    const iconNode = isIconText ? node : parentNode;
    const parentElement = node.parentElement;
    const isLink = parentElement?.tagName === "A" || node.tagName === "A";
    const parentHref = getHref(parentElement) ?? getHref(node) ?? "";
    const modelStyle = getStyleModel2(node);
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

  // ../../../../../../../packages/elements/src/Models/Wrapper/index.ts
  var createWrapperModel = (data2) => {
    const { _styles, items, ...value } = data2;
    return {
      type: "Wrapper",
      value: { _id: uuid(), _styles, items, ...value }
    };
  };

  // ../../../../../../../packages/elements/src/Text/utils/dom/cleanClassNames.ts
  var cleanClassNames = (node) => {
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

  // ../../../../../../../packages/elements/src/Text/utils/dom/removeEmptyNodes.ts
  function removeEmptyNodes(node) {
    const children = Array.from(node.children);
    children.forEach((child) => {
      const haveText = child.textContent?.trim();
      if (!haveText) {
        child.remove();
      }
    });
    return node;
  }

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

  // ../../../../../../../packages/elements/src/Text/utils/styles/copyParentColorToChild.ts
  var attributes = extractedAttributes;
  function copyColorStyleToTextNodes(element) {
    if (element.nodeType === Node.TEXT_NODE) {
      const parentElement = element.parentElement;
      if (!parentElement) {
        return;
      }
      if (parentElement.tagName === "SPAN") {
        const parentOfParent = element.parentElement.parentElement;
        const parentElement2 = element.parentElement;
        const parentStyle = parentElement2.style;
        if (attributes.includes("text-transform") && !parentStyle?.textTransform) {
          const style = getNodeStyle(parentElement2);
          if (style["text-transform"] === "uppercase") {
            parentElement2.classList.add("brz-capitalize-on");
          }
        }
        if (!parentOfParent) {
          return;
        }
        if (!parentStyle?.color) {
          const parentOFParentStyle = getNodeStyle(parentOfParent);
          parentElement2.style.color = `${parentOFParentStyle.color}`;
        }
        if (!parentStyle?.fontWeight && parentOfParent.style?.fontWeight) {
          parentElement2.style.fontWeight = parentOfParent.style.fontWeight;
        }
        if (parentOfParent.tagName === "SPAN") {
          const parentFontWeight = parentElement2.style.fontWeight;
          parentElement2.style.fontWeight = parentFontWeight || getComputedStyle(parentElement2).fontWeight;
        }
        return;
      }
      const spanElement = document.createElement("span");
      const computedStyles = window.getComputedStyle(parentElement);
      if (attributes.includes("text-transform") && computedStyles.textTransform === "uppercase") {
        spanElement.classList.add("brz-capitalize-on");
      }
      if (computedStyles.color) {
        spanElement.style.color = computedStyles.color;
      }
      if (computedStyles.fontWeight) {
        spanElement.style.fontWeight = computedStyles.fontWeight;
      }
      spanElement.textContent = element.textContent;
      if (parentElement.tagName === "U") {
        element.parentElement.style.color = computedStyles.color;
      }
      if (element) {
        element.parentElement.replaceChild(spanElement, element);
      }
    } else if (element.nodeType === Node.ELEMENT_NODE) {
      const childNodes = element.childNodes;
      for (let i = 0; i < childNodes.length; i++) {
        copyColorStyleToTextNodes(childNodes[i]);
      }
    }
  }
  function copyParentColorToChild(node) {
    node.childNodes.forEach((child) => {
      copyColorStyleToTextNodes(child);
    });
    return node;
  }

  // ../../../../../../../packages/utils/src/dom/recursiveGetNodes.ts
  var recursiveGetNodes = (node) => {
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

  // ../../../../../../../packages/elements/src/Text/utils/styles/getTypographyStyles.ts
  var getTypographyStyles = (node) => {
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

  // ../../../../../../../packages/utils/src/reader/number.ts
  var read2 = (v) => {
    switch (typeof v) {
      case "string": {
        const v_ = v !== "" ? Number(v) : NaN;
        return isNaN(v_) ? void 0 : v_;
      }
      case "number":
        return isNaN(v) ? void 0 : v;
      default:
        return void 0;
    }
  };
  var readInt = (v) => {
    if (typeof v === "string") {
      return parseInt(v);
    }
    return read2(v);
  };

  // ../../../../../../../packages/elements/src/Text/models/Text/utils/stylesToClasses.ts
  var stylesToClasses = (styles, families, defaultFamily) => {
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

  // ../../../../../../../packages/elements/src/Text/models/Text/index.ts
  var getTextModel = (data2) => {
    const { node: _node, families, defaultFamily } = data2;
    let node = _node;
    node = transformDivsToParagraphs(node);
    node = removeEmptyNodes(node);
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

  // ../../../../../../../packages/elements/src/Text/utils/dom/getContainerStackWithNodes.ts
  var Stack = class {
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
  var getContainerStackWithNodes = (node) => {
    const container = document.createElement("div");
    const stack = new Stack();
    let appendNewText = false;
    node.childNodes.forEach((node2) => {
      const _node = node2.cloneNode(true);
      const containerOfNode = document.createElement("div");
      containerOfNode.append(_node);
      if (_node instanceof HTMLElement) {
        if (containerOfNode.querySelector(iconSelector)) {
          appendNewText = true;
          stack.append(_node, { type: "icon" });
          return;
        }
        if (containerOfNode.querySelector(buttonSelector)) {
          appendNewText = true;
          stack.append(_node, { type: "button" });
          return;
        }
        if (containerOfNode.querySelector(embedSelector)) {
          appendNewText = true;
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

  // ../../../../../../../packages/elements/src/Text/index.ts
  var getText2 = (entry) => {
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
    const data2 = [];
    const { container, destroy } = getContainerStackWithNodes(node);
    const containerChildren = Array.from(container.children);
    containerChildren.forEach((node2) => {
      if (node2 instanceof HTMLElement) {
        switch (node2.dataset.type) {
          case "text": {
            const model = getTextModel({ ...entry, node: node2 });
            data2.push(model);
            break;
          }
          case "button": {
            const models = getButtonModel(node2);
            data2.push(...models);
            break;
          }
          case "embed": {
            const models = getEmbedModel(node2);
            data2.push(...models);
            break;
          }
          case "icon": {
            const models = getIconModel(node2);
            data2.push(...models);
            break;
          }
        }
      }
    });
    destroy();
    return createData({ data: data2 });
  };

  // src/Text/index.ts
  var data = getData();
  var output = getText2(data);
  var Text_default = output;
  return __toCommonJS(Text_exports);
})();
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2ZwLXV0aWxpdGllcy9kaXN0L2xpZnRBMi5qcyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvZnAtdXRpbGl0aWVzL2Rpc3QvbWF0Y2guanMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2ZwLXV0aWxpdGllcy9kaXN0L21hdGNoMi5qcyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvZnAtdXRpbGl0aWVzL2Rpc3QvTm90aGluZy5qcyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvZnAtdXRpbGl0aWVzL2Rpc3QvbVBpcGUuanMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2ZwLXV0aWxpdGllcy9kaXN0L3Bhc3MuanMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL2ZwLXV0aWxpdGllcy9kaXN0L3BhcnNlcnMvaW50ZXJuYWxzLmpzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9mcC11dGlsaXRpZXMvZGlzdC9wYXJzZXJzL3BhcnNlLmpzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9mcC11dGlsaXRpZXMvZGlzdC9wYXJzZXJzL3BhcnNlU3RyaWN0LmpzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL25vZGVfbW9kdWxlcy9mcC11dGlsaXRpZXMvZGlzdC9vci5qcyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvZnAtdXRpbGl0aWVzL2Rpc3QvaW5kZXguanMiLCAiLi4vc3JjL1RleHQvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL3V0aWxzL2dldERhdGEudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL25hbm9pZC9pbmRleC5icm93c2VyLmpzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy91dWlkLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9Nb2RlbHMvQ2xvbmVhYmxlL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2NvbW1vbi9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvcmVhZGVyL29iamVjdC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvcmVhZGVyL3N0cmluZy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvQnV0dG9uL3V0aWxzL2dldE1vZGVsLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9jb2xvci9wYXJzZUNvbG9yU3RyaW5nLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9mcC9waXBlLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9pc051bGxpc2gudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL29uTnVsbGlzaC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvZG9tL2ZpbmROZWFyZXN0QmxvY2tQYXJlbnQudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvbW9kZWxzL0J1dHRvbi9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvRW1iZWQvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvbW9kZWxzL0ljb24vdXRpbHMvZ2V0TW9kZWwudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2RvbS9nZXRQYXJlbnRFbGVtZW50T2ZUZXh0Tm9kZS50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvSWNvbi9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvTW9kZWxzL1dyYXBwZXIvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvZG9tL2NsZWFuQ2xhc3NOYW1lcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC91dGlscy9kb20vcmVtb3ZlQWxsU3R5bGVzRnJvbUhUTUwudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvZG9tL3JlbW92ZUVtcHR5Tm9kZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvZG9tL3RyYW5zZm9ybURpdnNUb1BhcmFncmFwaHMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2NvcHlQYXJlbnRDb2xvclRvQ2hpbGQudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2RvbS9yZWN1cnNpdmVHZXROb2Rlcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvZG9tL2V4dHJhY3RBbGxFbGVtZW50c1N0eWxlcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC91dGlscy9zdHlsZXMvbWVyZ2VTdHlsZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvZG9tL2V4dHJhY3RQYXJlbnRFbGVtZW50c1dpdGhTdHlsZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2dldFR5cG9ncmFwaHlTdHlsZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2dldExldHRlclNwYWNpbmcudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2dldExpbmVIZWlnaHQudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL3JlYWRlci9udW1iZXIudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvbW9kZWxzL1RleHQvdXRpbHMvc3R5bGVzVG9DbGFzc2VzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L21vZGVscy9UZXh0L2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2RvbS9nZXRDb250YWluZXJTdGFja1dpdGhOb2Rlcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9pbmRleC50cyJdLAogICJzb3VyY2VzQ29udGVudCI6IFsiXCJ1c2Ugc3RyaWN0XCI7XG5PYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgXCJfX2VzTW9kdWxlXCIsIHsgdmFsdWU6IHRydWUgfSk7XG5leHBvcnRzLmxpZnRBMiA9IHZvaWQgMDtcbi8qKlxuICogQXBwbHkgYSBiaW5hcnkgZnVuY3Rpb24gb3ZlciByZXN1bHQgb2YgMiBzaW5nbGUgZnVuY3Rpb25zXG4gKlxuICogRS5nLiBsaWZ0QTIoc3VtLCBpbmMsIHNxcikoMiwgMikgPSAoMiArIDEpICsgKDIgKiAyKSA9IDMrNCA9IDdcbiAqL1xuZnVuY3Rpb24gbGlmdEEyKGZuLCBmMSwgZjIpIHtcbiAgICByZXR1cm4gZnVuY3Rpb24gKGEsIGIpIHsgcmV0dXJuIGZuKGYxKGEpLCBmMihiKSk7IH07XG59XG5leHBvcnRzLmxpZnRBMiA9IGxpZnRBMjtcbiIsICJcInVzZSBzdHJpY3RcIjtcbi8qKlxuICogUHJvdmlkZSBhIHNlcmllcyBvZiB0eXBlIGd1YXJkIHByZWRpY2F0ZXMgdGhhdCBzYXRpc2ZpZXMgdGhlIGlucHV0LFxuICogYW5kIGEgZnVuY3Rpb24gdGhhdCB3aWxsIHJlc29sdmUgdGhlIHZhbHVlIGlmIGl0IG1hdGNoZXMgdGhlIHR5cGUgZ3VhcmQuXG4gKlxuICogSW4gb3RoZXIgd29yZHMgdGhpcyBpcyBhIHR5cGUgc2FmZSBgaWYgZWxzZWAgc3RhdGVtZW50LlxuICpcbiAqIEluIGNhc2UgdGhlIHByb3ZpZGVkIHR5cGUgZ3VhcmRzIGxpc3QgZG9lc24ndCBjb3ZlciB0aGUgZW50aXJlIGlucHV0IHR5cGUsXG4gKiB5b3UnbGwgZ2V0IGEgdHlwZSBlcnJvciBhdCBjb21waWxlIHRpbWUuXG4gKi9cbk9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBcIl9fZXNNb2R1bGVcIiwgeyB2YWx1ZTogdHJ1ZSB9KTtcbmV4cG9ydHMubWF0Y2ggPSB2b2lkIDA7XG4vLyBlbmRyZWdpb25cbmZ1bmN0aW9uIG1hdGNoKCkge1xuICAgIHZhciBhcmdzID0gW107XG4gICAgZm9yICh2YXIgX2kgPSAwOyBfaSA8IGFyZ3VtZW50cy5sZW5ndGg7IF9pKyspIHtcbiAgICAgICAgYXJnc1tfaV0gPSBhcmd1bWVudHNbX2ldO1xuICAgIH1cbiAgICAvLyBAdHMtZXhwZWN0LWVycm9yXG4gICAgcmV0dXJuIGZ1bmN0aW9uICh0KSB7XG4gICAgICAgIGZvciAodmFyIGkgPSAwOyBpIDwgYXJncy5sZW5ndGg7IGkrKykge1xuICAgICAgICAgICAgaWYgKGFyZ3NbaV1bMF0odCkpIHtcbiAgICAgICAgICAgICAgICByZXR1cm4gYXJnc1tpXVsxXSh0KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH07XG59XG5leHBvcnRzLm1hdGNoID0gbWF0Y2g7XG4iLCAiXCJ1c2Ugc3RyaWN0XCI7XG4vKipcbiAqIFByb3ZpZGUgYSBzZXJpZXMgb2YgdHlwZSBndWFyZCBwcmVkaWNhdGVzIHRoYXQgc2F0aXNmaWVzIHRoZSBpbnB1dCxcbiAqIGFuZCBhIGZ1bmN0aW9uIHRoYXQgd2lsbCByZXNvbHZlIHRoZSB2YWx1ZSBpZiBpdCBtYXRjaGVzIHRoZSB0eXBlIGd1YXJkLlxuICpcbiAqIFRoaXMgZnVuY3Rpb24gaXMgc2ltaWxhciB0byBgbWF0Y2hgLCBidXQgcmVzb2x2ZXMgMiBhcmd1bWVudHMgaW5wdXQgY2FzZXNcbiAqXG4gKiAhTm90ZTogVGhlcmUgaXMgYSBiaWcgZGlmZmVyZW5jZSBiZXR3ZWVuIGBtYXRjaGAgYW5kIGBtYXRjaDJgXG4gKiBgbWF0Y2gyYCBkb2VzIG5vdCBmb3JjZSB5b3UgdG8gcHJvdmlkZSBhbGwgY29tYmluYXRpb25zLCBzbyBpdCBjYW4gcmV0dXJuIHVuZGVmaW5lZCxcbiAqICBhcyByZXR1cm4gdHlwZSBtZW50aW9ucy5cbiAqICBUaGVyZSBpcyB0ZWNobmljYWwgYW5kIGNvZGluZyBleHBlcmllbmNlIGxpbWl0YXRpb24uIEluIG9yZGVyIHRvIG1ha2UgYG1hdGNoMmAgYmUgc3RyaWN0IGFzIGBtYXRjaGAsXG4gKiAgdGhlIHVzZSB3aWxsIGhhdmUgdG8gcHJvdmlkZSBhbGwgcG9zc2libGUgY2FzZXMgYmV0d2VlbiBmaXJzdCBhcmd1bWVudCBhbmQgdGhlIHNlY29uZCBvbmUuIEJ1dCB0aGlzIGlzIGFscmVhZHlcbiAqICBjYXJ0ZXNpYW4gcHJvZHVjdCBhbmQgaXQgY2FuIGdldCBodWdlIHZlcnkgZWFzeS4gRm9yIDN4MyBpbnB1dCwgeW91IGdldCA5IGNvbWJpbmF0aW9ucy4gQW5kIHllcywgeW91IGd1ZXNzZWQgaXQsXG4gKiAgZm9yIDR4NCwgMTYgY29tYmluYXRpb25zLiBCdXQgaW4gcmVhbCB3b3JsZCB1c3VhbGx5IHlvdSBuZWVkIDQgY29tYmluYXRpb25zLlxuICogIEJ1dCBhdCB0aGUgc2FtZSBtb21lbnQgaXQgZW5mb3JjZXMgeW91IHRvIHNhdGlzZnkgZW50aXJlIGlucHV0IHR5cGUgaW4gYXQgbGVhc3Qgb25lIG9mIHR5cGUgZ3VhcmRzLiBBbmQgdGhpcyBpc1xuICogIHdoYXQgbWFrZXMgaXQgZGlmZmVyZW50IGZyb20gYGlmIGVsc2VgIHN0YXRlbWVudC5cbiAqL1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xuZXhwb3J0cy5tYXRjaDIgPSB2b2lkIDA7XG4vLyBlbmRyZWdpb25cbmZ1bmN0aW9uIG1hdGNoMigpIHtcbiAgICB2YXIgYXJncyA9IFtdO1xuICAgIGZvciAodmFyIF9pID0gMDsgX2kgPCBhcmd1bWVudHMubGVuZ3RoOyBfaSsrKSB7XG4gICAgICAgIGFyZ3NbX2ldID0gYXJndW1lbnRzW19pXTtcbiAgICB9XG4gICAgLy8gQHRzLWV4cGVjdC1lcnJvclxuICAgIHJldHVybiBmdW5jdGlvbiAodCwgdDIpIHtcbiAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPCBhcmdzLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICBpZiAoYXJnc1tpXVswXSh0KSAmJiBhcmdzW2ldWzFdKHQyKSkge1xuICAgICAgICAgICAgICAgIHJldHVybiBhcmdzW2ldWzJdKHQsIHQyKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH07XG59XG5leHBvcnRzLm1hdGNoMiA9IG1hdGNoMjtcbiIsICJcInVzZSBzdHJpY3RcIjtcbk9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBcIl9fZXNNb2R1bGVcIiwgeyB2YWx1ZTogdHJ1ZSB9KTtcbmV4cG9ydHMub3JFbHNlID0gZXhwb3J0cy5pc1QgPSBleHBvcnRzLmlzTm90aGluZyA9IHZvaWQgMDtcbi8qKlxuICogQ2hlY2sgaXMgdGhlIHZhbHVlIGlzIGEgTm90aGluZyB2YWx1ZVxuICogTm90aGluZyBhcmUgY29uc2lkZXJlZCB1bmRlZmluZWQgYW5kIG51bGxcbiAqL1xudmFyIGlzTm90aGluZyA9IGZ1bmN0aW9uICh2KSB7IHJldHVybiB2ID09PSBudWxsIHx8IHYgPT09IHVuZGVmaW5lZDsgfTtcbmV4cG9ydHMuaXNOb3RoaW5nID0gaXNOb3RoaW5nO1xuLyoqXG4gKiBDaGVjayB3aGVuZXZlciBhIHBvdGVudGlhbCBtYXliZSB2YWx1ZSBpcyBlbXB0eSBvciBub3RcbiAqL1xudmFyIGlzVCA9IGZ1bmN0aW9uICh0KSB7IHJldHVybiAhKDAsIGV4cG9ydHMuaXNOb3RoaW5nKSh0KTsgfTtcbmV4cG9ydHMuaXNUID0gaXNUO1xuZnVuY3Rpb24gb3JFbHNlKCkge1xuICAgIHZhciBhcmdzID0gW107XG4gICAgZm9yICh2YXIgX2kgPSAwOyBfaSA8IGFyZ3VtZW50cy5sZW5ndGg7IF9pKyspIHtcbiAgICAgICAgYXJnc1tfaV0gPSBhcmd1bWVudHNbX2ldO1xuICAgIH1cbiAgICByZXR1cm4gYXJncy5sZW5ndGggPT09IDEgPyBmdW5jdGlvbiAodikgeyByZXR1cm4gKCgwLCBleHBvcnRzLmlzTm90aGluZykodikgPyBhcmdzWzBdIDogdik7IH0gOiAoMCwgZXhwb3J0cy5pc05vdGhpbmcpKGFyZ3NbMV0pID8gYXJnc1swXSA6IGFyZ3NbMV07XG59XG5leHBvcnRzLm9yRWxzZSA9IG9yRWxzZTtcbiIsICJcInVzZSBzdHJpY3RcIjtcbk9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBcIl9fZXNNb2R1bGVcIiwgeyB2YWx1ZTogdHJ1ZSB9KTtcbmV4cG9ydHMubVBpcGUgPSB2b2lkIDA7XG52YXIgTm90aGluZ18xID0gcmVxdWlyZShcIi4vTm90aGluZ1wiKTtcbmZ1bmN0aW9uIG1QaXBlKCkge1xuICAgIHZhciBfYSA9IFtdO1xuICAgIGZvciAodmFyIF9pID0gMDsgX2kgPCBhcmd1bWVudHMubGVuZ3RoOyBfaSsrKSB7XG4gICAgICAgIF9hW19pXSA9IGFyZ3VtZW50c1tfaV07XG4gICAgfVxuICAgIHZhciBoID0gX2FbMF0sIGZucyA9IF9hLnNsaWNlKDEpO1xuICAgIHJldHVybiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBfYTtcbiAgICAgICAgdmFyIGFyZ3MgPSBbXTtcbiAgICAgICAgZm9yICh2YXIgX2kgPSAwOyBfaSA8IGFyZ3VtZW50cy5sZW5ndGg7IF9pKyspIHtcbiAgICAgICAgICAgIGFyZ3NbX2ldID0gYXJndW1lbnRzW19pXTtcbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gYXJncy5ldmVyeShOb3RoaW5nXzEuaXNUKSA/IChfYSA9IGZucy5yZWR1Y2UoZnVuY3Rpb24gKHYsIGZuKSB7IHJldHVybiAoKDAsIE5vdGhpbmdfMS5pc1QpKHYpID8gZm4odikgOiB1bmRlZmluZWQpOyB9LCBoLmFwcGx5KHZvaWQgMCwgYXJncykpKSAhPT0gbnVsbCAmJiBfYSAhPT0gdm9pZCAwID8gX2EgOiB1bmRlZmluZWQgOiB1bmRlZmluZWQ7XG4gICAgfTtcbn1cbmV4cG9ydHMubVBpcGUgPSBtUGlwZTtcbiIsICJcInVzZSBzdHJpY3RcIjtcbk9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBcIl9fZXNNb2R1bGVcIiwgeyB2YWx1ZTogdHJ1ZSB9KTtcbmV4cG9ydHMucGFzcyA9IHZvaWQgMDtcbmZ1bmN0aW9uIHBhc3MocHJlZGljYXRlKSB7XG4gICAgcmV0dXJuIGZ1bmN0aW9uICh0KSB7IHJldHVybiAocHJlZGljYXRlKHQpID8gdCA6IHVuZGVmaW5lZCk7IH07XG59XG5leHBvcnRzLnBhc3MgPSBwYXNzO1xuIiwgIlwidXNlIHN0cmljdFwiO1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xuZXhwb3J0cy5fcGFyc2UgPSBleHBvcnRzLmNhbGwgPSBleHBvcnRzLmlzT3B0aW9uYWwgPSB2b2lkIDA7XG52YXIgTm90aGluZ18xID0gcmVxdWlyZShcIi4uL05vdGhpbmdcIik7XG4vLyBlbmRyZWdpb25cbi8vIHJlZ2lvbiBvcHRpb25hbCAmIHN0cmljdFxuLyoqXG4gKiBAaW50ZXJuYWxcbiAqL1xudmFyIGlzT3B0aW9uYWwgPSBmdW5jdGlvbiAodikge1xuICAgIHJldHVybiB2Ll9fdHlwZSA9PT0gXCJvcHRpb25hbFwiO1xufTtcbmV4cG9ydHMuaXNPcHRpb25hbCA9IGlzT3B0aW9uYWw7XG4vKipcbiAqIEBpbnRlcm5hbFxuICovXG52YXIgY2FsbCA9IGZ1bmN0aW9uIChwLCB2KSB7XG4gICAgc3dpdGNoIChwLl9fdHlwZSkge1xuICAgICAgICBjYXNlIFwib3B0aW9uYWxcIjpcbiAgICAgICAgY2FzZSBcInN0cmljdFwiOlxuICAgICAgICAgICAgcmV0dXJuIHAuZm4odik7XG4gICAgICAgIGRlZmF1bHQ6XG4gICAgICAgICAgICByZXR1cm4gcCh2KTtcbiAgICB9XG59O1xuZXhwb3J0cy5jYWxsID0gY2FsbDtcbi8qKlxuICogQGludGVybmFsXG4gKi9cbmZ1bmN0aW9uIF9wYXJzZShwYXJzZXJzLCBvYmplY3QpIHtcbiAgICB2YXIgYiA9IHt9O1xuICAgIGZvciAodmFyIHAgaW4gcGFyc2Vycykge1xuICAgICAgICBpZiAoIU9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbChwYXJzZXJzLCBwKSkge1xuICAgICAgICAgICAgY29udGludWU7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIHYgPSAoMCwgZXhwb3J0cy5jYWxsKShwYXJzZXJzW3BdLCBvYmplY3QpO1xuICAgICAgICBpZiAoISgwLCBleHBvcnRzLmlzT3B0aW9uYWwpKHBhcnNlcnNbcF0pICYmICgwLCBOb3RoaW5nXzEuaXNOb3RoaW5nKSh2KSkge1xuICAgICAgICAgICAgcmV0dXJuIHVuZGVmaW5lZDtcbiAgICAgICAgfVxuICAgICAgICBiW3BdID0gdjtcbiAgICB9XG4gICAgcmV0dXJuIGI7XG59XG5leHBvcnRzLl9wYXJzZSA9IF9wYXJzZTtcbi8vIGVuZHJlZ2lvblxuIiwgIlwidXNlIHN0cmljdFwiO1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xuZXhwb3J0cy5wYXJzZSA9IGV4cG9ydHMub3B0aW9uYWwgPSB2b2lkIDA7XG52YXIgaW50ZXJuYWxzXzEgPSByZXF1aXJlKFwiLi9pbnRlcm5hbHNcIik7XG4vLyByZWdpb24gb3B0aW9uYWwgJiBzdHJpY3Rcbi8qKlxuICogRXZlbiBpZiB0aGUgcGFyc2VyIHJldHVybnMgYHVuZGVmaW5lZGAsIHRoZSBwYXJzaW5nIHByb2Nlc3Mgd2lsbCBub3QgYmUgc3RvcHBlZC5cbiAqIEl0J3MgdXNlZCB0byBwYXJzZSBmb3IgdHlwZXMgd2l0aCBvcHRpb25hbCBrZXlzXG4gKi9cbnZhciBvcHRpb25hbCA9IGZ1bmN0aW9uIChwKSB7IHJldHVybiAoe1xuICAgIF9fdHlwZTogXCJvcHRpb25hbFwiLFxuICAgIGZuOiBwLFxufSk7IH07XG5leHBvcnRzLm9wdGlvbmFsID0gb3B0aW9uYWw7XG5mdW5jdGlvbiBwYXJzZShwYXJzZXJzLCBvYmplY3QpIHtcbiAgICByZXR1cm4gb2JqZWN0ID09PSB1bmRlZmluZWRcbiAgICAgICAgPyBmdW5jdGlvbiAobykgeyByZXR1cm4gKDAsIGludGVybmFsc18xLl9wYXJzZSkocGFyc2Vycywgbyk7IH1cbiAgICAgICAgOiAoMCwgaW50ZXJuYWxzXzEuX3BhcnNlKShwYXJzZXJzLCBvYmplY3QpO1xufVxuZXhwb3J0cy5wYXJzZSA9IHBhcnNlO1xuLy8gZW5kcmVnaW9uXG4iLCAiXCJ1c2Ugc3RyaWN0XCI7XG5PYmplY3QuZGVmaW5lUHJvcGVydHkoZXhwb3J0cywgXCJfX2VzTW9kdWxlXCIsIHsgdmFsdWU6IHRydWUgfSk7XG5leHBvcnRzLnBhcnNlU3RyaWN0ID0gdm9pZCAwO1xudmFyIGludGVybmFsc18xID0gcmVxdWlyZShcIi4vaW50ZXJuYWxzXCIpO1xuZnVuY3Rpb24gcGFyc2VTdHJpY3QocGFyc2Vycywgb2JqZWN0KSB7XG4gICAgcmV0dXJuIG9iamVjdCA9PT0gdW5kZWZpbmVkXG4gICAgICAgID9cbiAgICAgICAgICAgIGZ1bmN0aW9uIChvKSB7IHJldHVybiAoMCwgaW50ZXJuYWxzXzEuX3BhcnNlKShwYXJzZXJzLCBvKTsgfVxuICAgICAgICA6ICgwLCBpbnRlcm5hbHNfMS5fcGFyc2UpKHBhcnNlcnMsIG9iamVjdCk7XG59XG5leHBvcnRzLnBhcnNlU3RyaWN0ID0gcGFyc2VTdHJpY3Q7XG4vLyBlbmRyZWdpb25cbiIsICJcInVzZSBzdHJpY3RcIjtcbnZhciBfX3NwcmVhZEFycmF5ID0gKHRoaXMgJiYgdGhpcy5fX3NwcmVhZEFycmF5KSB8fCBmdW5jdGlvbiAodG8sIGZyb20sIHBhY2spIHtcbiAgICBpZiAocGFjayB8fCBhcmd1bWVudHMubGVuZ3RoID09PSAyKSBmb3IgKHZhciBpID0gMCwgbCA9IGZyb20ubGVuZ3RoLCBhcjsgaSA8IGw7IGkrKykge1xuICAgICAgICBpZiAoYXIgfHwgIShpIGluIGZyb20pKSB7XG4gICAgICAgICAgICBpZiAoIWFyKSBhciA9IEFycmF5LnByb3RvdHlwZS5zbGljZS5jYWxsKGZyb20sIDAsIGkpO1xuICAgICAgICAgICAgYXJbaV0gPSBmcm9tW2ldO1xuICAgICAgICB9XG4gICAgfVxuICAgIHJldHVybiB0by5jb25jYXQoYXIgfHwgQXJyYXkucHJvdG90eXBlLnNsaWNlLmNhbGwoZnJvbSkpO1xufTtcbk9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBcIl9fZXNNb2R1bGVcIiwgeyB2YWx1ZTogdHJ1ZSB9KTtcbmV4cG9ydHMub3IgPSB2b2lkIDA7XG52YXIgTm90aGluZ18xID0gcmVxdWlyZShcIi4vTm90aGluZ1wiKTtcbi8vIGVuZHJlZ2lvblxuZnVuY3Rpb24gb3IoKSB7XG4gICAgdmFyIGZucyA9IFtdO1xuICAgIGZvciAodmFyIF9pID0gMDsgX2kgPCBhcmd1bWVudHMubGVuZ3RoOyBfaSsrKSB7XG4gICAgICAgIGZuc1tfaV0gPSBhcmd1bWVudHNbX2ldO1xuICAgIH1cbiAgICAvLyBAdHMtZXhwZWN0LWVycm9yLCBUZWNobmljYWxseSB0aGlzIGZ1bmN0aW9uIG1heSByZXR1cm4gdW5kZWZpbmVkLFxuICAgIC8vIGJ1dCB0eXBlIHN5c3RlbSBkb2Vzbid0IGFsbG93IHRoaXNcbiAgICByZXR1cm4gZnVuY3Rpb24gKCkge1xuICAgICAgICB2YXIgX2E7XG4gICAgICAgIHZhciBhcmdzID0gW107XG4gICAgICAgIGZvciAodmFyIF9pID0gMDsgX2kgPCBhcmd1bWVudHMubGVuZ3RoOyBfaSsrKSB7XG4gICAgICAgICAgICBhcmdzW19pXSA9IGFyZ3VtZW50c1tfaV07XG4gICAgICAgIH1cbiAgICAgICAgZm9yICh2YXIgaSA9IDA7IGkgPD0gZm5zLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICB2YXIgdiA9IChfYSA9IGZuc1tpXSkgPT09IG51bGwgfHwgX2EgPT09IHZvaWQgMCA/IHZvaWQgMCA6IF9hLmNhbGwuYXBwbHkoX2EsIF9fc3ByZWFkQXJyYXkoW2Zuc10sIGFyZ3MsIGZhbHNlKSk7XG4gICAgICAgICAgICBpZiAoISgwLCBOb3RoaW5nXzEuaXNOb3RoaW5nKSh2KSkge1xuICAgICAgICAgICAgICAgIHJldHVybiB2O1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfTtcbn1cbmV4cG9ydHMub3IgPSBvcjtcbiIsICJcInVzZSBzdHJpY3RcIjtcbnZhciBfX2NyZWF0ZUJpbmRpbmcgPSAodGhpcyAmJiB0aGlzLl9fY3JlYXRlQmluZGluZykgfHwgKE9iamVjdC5jcmVhdGUgPyAoZnVuY3Rpb24obywgbSwgaywgazIpIHtcbiAgICBpZiAoazIgPT09IHVuZGVmaW5lZCkgazIgPSBrO1xuICAgIHZhciBkZXNjID0gT2JqZWN0LmdldE93blByb3BlcnR5RGVzY3JpcHRvcihtLCBrKTtcbiAgICBpZiAoIWRlc2MgfHwgKFwiZ2V0XCIgaW4gZGVzYyA/ICFtLl9fZXNNb2R1bGUgOiBkZXNjLndyaXRhYmxlIHx8IGRlc2MuY29uZmlndXJhYmxlKSkge1xuICAgICAgZGVzYyA9IHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBmdW5jdGlvbigpIHsgcmV0dXJuIG1ba107IH0gfTtcbiAgICB9XG4gICAgT2JqZWN0LmRlZmluZVByb3BlcnR5KG8sIGsyLCBkZXNjKTtcbn0pIDogKGZ1bmN0aW9uKG8sIG0sIGssIGsyKSB7XG4gICAgaWYgKGsyID09PSB1bmRlZmluZWQpIGsyID0gaztcbiAgICBvW2syXSA9IG1ba107XG59KSk7XG52YXIgX19leHBvcnRTdGFyID0gKHRoaXMgJiYgdGhpcy5fX2V4cG9ydFN0YXIpIHx8IGZ1bmN0aW9uKG0sIGV4cG9ydHMpIHtcbiAgICBmb3IgKHZhciBwIGluIG0pIGlmIChwICE9PSBcImRlZmF1bHRcIiAmJiAhT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKGV4cG9ydHMsIHApKSBfX2NyZWF0ZUJpbmRpbmcoZXhwb3J0cywgbSwgcCk7XG59O1xuT2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFwiX19lc01vZHVsZVwiLCB7IHZhbHVlOiB0cnVlIH0pO1xuX19leHBvcnRTdGFyKHJlcXVpcmUoXCIuL2xpZnRBMlwiKSwgZXhwb3J0cyk7XG5fX2V4cG9ydFN0YXIocmVxdWlyZShcIi4vbWF0Y2hcIiksIGV4cG9ydHMpO1xuX19leHBvcnRTdGFyKHJlcXVpcmUoXCIuL21hdGNoMlwiKSwgZXhwb3J0cyk7XG5fX2V4cG9ydFN0YXIocmVxdWlyZShcIi4vbVBpcGVcIiksIGV4cG9ydHMpO1xuX19leHBvcnRTdGFyKHJlcXVpcmUoXCIuL05vdGhpbmdcIiksIGV4cG9ydHMpO1xuX19leHBvcnRTdGFyKHJlcXVpcmUoXCIuL3Bhc3NcIiksIGV4cG9ydHMpO1xuX19leHBvcnRTdGFyKHJlcXVpcmUoXCIuL3BhcnNlcnMvcGFyc2VcIiksIGV4cG9ydHMpO1xuX19leHBvcnRTdGFyKHJlcXVpcmUoXCIuL3BhcnNlcnMvcGFyc2VTdHJpY3RcIiksIGV4cG9ydHMpO1xuX19leHBvcnRTdGFyKHJlcXVpcmUoXCIuL29yXCIpLCBleHBvcnRzKTtcbiIsICJpbXBvcnQgeyBnZXRUZXh0IH0gZnJvbSBcImVsZW1lbnRzL3NyYy9UZXh0XCI7XG5pbXBvcnQgeyBnZXREYXRhIH0gZnJvbSBcImVsZW1lbnRzL3NyYy91dGlscy9nZXREYXRhXCI7XG5cbi8vIE9ubHkgRm9yIERldlxuLy8gd2luZG93LmlzRGV2ID0gdHJ1ZTtcbmNvbnN0IGRhdGEgPSBnZXREYXRhKCk7XG5jb25zdCBvdXRwdXQgPSBnZXRUZXh0KGRhdGEpO1xuXG5leHBvcnQgZGVmYXVsdCBvdXRwdXQ7XG4iLCAiaW1wb3J0IHsgRW50cnksIE91dHB1dCwgT3V0cHV0RGF0YSB9IGZyb20gXCIuLi90eXBlcy90eXBlXCI7XG5cbmV4cG9ydCBjb25zdCBnZXREYXRhID0gKCk6IEVudHJ5ID0+IHtcbiAgdHJ5IHtcbiAgICAvLyBGb3IgZGV2ZWxvcG1lbnRcbiAgICAvLyB3aW5kb3cuaXNEZXYgPSB0cnVlO1xuICAgIHJldHVybiB3aW5kb3cuaXNEZXZcbiAgICAgID8ge1xuICAgICAgICAgIHNlbGVjdG9yOiBgW2RhdGEtaWQ9JyR7NDM0MTU3OX0nXWAsXG4gICAgICAgICAgZmFtaWxpZXM6IHtcbiAgICAgICAgICAgIFwicHJveGltYV9ub3ZhX3Byb3hpbWFfbm92YV9yZWd1bGFyX3NhbnMtc2VyaWZcIjogXCJ1aWQxMTExXCIsXG4gICAgICAgICAgICBcImhlbHZldGljYV9uZXVlX2hlbHZldGljYW5ldWVfaGVsdmV0aWNhX2FyaWFsX3NhbnMtc2VyaWZcIjogXCJ1aWQyMjIyXCJcbiAgICAgICAgICB9LFxuICAgICAgICAgIGRlZmF1bHRGYW1pbHk6IFwibGF0b1wiXG4gICAgICAgIH1cbiAgICAgIDoge1xuICAgICAgICAgIHNlbGVjdG9yOiBTRUxFQ1RPUixcbiAgICAgICAgICBmYW1pbGllczogRkFNSUxJRVMsXG4gICAgICAgICAgZGVmYXVsdEZhbWlseTogREVGQVVMVF9GQU1JTFlcbiAgICAgICAgfTtcbiAgfSBjYXRjaCAoZSkge1xuICAgIGNvbnN0IGZhbWlseU1vY2sgPSB7XG4gICAgICBsYXRvOiBcInVpZF9mb3JfbGF0b1wiLFxuICAgICAgcm9ib3RvOiBcInVpZF9mb3Jfcm9ib3RvXCJcbiAgICB9O1xuICAgIGNvbnN0IG1vY2s6IEVudHJ5ID0ge1xuICAgICAgc2VsZWN0b3I6IFwiLm15LWRpdlwiLFxuICAgICAgZmFtaWxpZXM6IGZhbWlseU1vY2ssXG4gICAgICBkZWZhdWx0RmFtaWx5OiBcImxhdG9cIlxuICAgIH07XG5cbiAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICBKU09OLnN0cmluZ2lmeSh7XG4gICAgICAgIGVycm9yOiBgSW52YWxpZCBKU09OICR7ZX1gLFxuICAgICAgICBkZXRhaWxzOiBgTXVzdCBiZTogJHtKU09OLnN0cmluZ2lmeShtb2NrKX1gXG4gICAgICB9KVxuICAgICk7XG4gIH1cbn07XG5cbmV4cG9ydCBjb25zdCBjcmVhdGVEYXRhID0gKG91dHB1dDogT3V0cHV0RGF0YSk6IE91dHB1dCA9PiB7XG4gIHJldHVybiBvdXRwdXQ7XG59O1xuIiwgImV4cG9ydCB7IHVybEFscGhhYmV0IH0gZnJvbSAnLi91cmwtYWxwaGFiZXQvaW5kZXguanMnXG5leHBvcnQgbGV0IHJhbmRvbSA9IGJ5dGVzID0+IGNyeXB0by5nZXRSYW5kb21WYWx1ZXMobmV3IFVpbnQ4QXJyYXkoYnl0ZXMpKVxuZXhwb3J0IGxldCBjdXN0b21SYW5kb20gPSAoYWxwaGFiZXQsIGRlZmF1bHRTaXplLCBnZXRSYW5kb20pID0+IHtcbiAgbGV0IG1hc2sgPSAoMiA8PCAoTWF0aC5sb2coYWxwaGFiZXQubGVuZ3RoIC0gMSkgLyBNYXRoLkxOMikpIC0gMVxuICBsZXQgc3RlcCA9IC1+KCgxLjYgKiBtYXNrICogZGVmYXVsdFNpemUpIC8gYWxwaGFiZXQubGVuZ3RoKVxuICByZXR1cm4gKHNpemUgPSBkZWZhdWx0U2l6ZSkgPT4ge1xuICAgIGxldCBpZCA9ICcnXG4gICAgd2hpbGUgKHRydWUpIHtcbiAgICAgIGxldCBieXRlcyA9IGdldFJhbmRvbShzdGVwKVxuICAgICAgbGV0IGogPSBzdGVwXG4gICAgICB3aGlsZSAoai0tKSB7XG4gICAgICAgIGlkICs9IGFscGhhYmV0W2J5dGVzW2pdICYgbWFza10gfHwgJydcbiAgICAgICAgaWYgKGlkLmxlbmd0aCA9PT0gc2l6ZSkgcmV0dXJuIGlkXG4gICAgICB9XG4gICAgfVxuICB9XG59XG5leHBvcnQgbGV0IGN1c3RvbUFscGhhYmV0ID0gKGFscGhhYmV0LCBzaXplID0gMjEpID0+XG4gIGN1c3RvbVJhbmRvbShhbHBoYWJldCwgc2l6ZSwgcmFuZG9tKVxuZXhwb3J0IGxldCBuYW5vaWQgPSAoc2l6ZSA9IDIxKSA9PlxuICBjcnlwdG8uZ2V0UmFuZG9tVmFsdWVzKG5ldyBVaW50OEFycmF5KHNpemUpKS5yZWR1Y2UoKGlkLCBieXRlKSA9PiB7XG4gICAgYnl0ZSAmPSA2M1xuICAgIGlmIChieXRlIDwgMzYpIHtcbiAgICAgIGlkICs9IGJ5dGUudG9TdHJpbmcoMzYpXG4gICAgfSBlbHNlIGlmIChieXRlIDwgNjIpIHtcbiAgICAgIGlkICs9IChieXRlIC0gMjYpLnRvU3RyaW5nKDM2KS50b1VwcGVyQ2FzZSgpXG4gICAgfSBlbHNlIGlmIChieXRlID4gNjIpIHtcbiAgICAgIGlkICs9ICctJ1xuICAgIH0gZWxzZSB7XG4gICAgICBpZCArPSAnXydcbiAgICB9XG4gICAgcmV0dXJuIGlkXG4gIH0sICcnKVxuIiwgImltcG9ydCB7IGN1c3RvbUFscGhhYmV0IH0gZnJvbSBcIm5hbm9pZFwiO1xuXG5jb25zdCBhbHBoYWJldCA9IFwiYWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXpcIjtcbmNvbnN0IGZ1bGxTeW1ib2xMaXN0ID1cbiAgXCIwMTIzNDU2Nzg5YWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXpBQkNERUZHSElKS0xNTk9QUVJTVFVWV1hZWl9cIjtcblxuZXhwb3J0IGNvbnN0IHV1aWQgPSAobGVuZ3RoID0gMTIpOiBzdHJpbmcgPT5cbiAgY3VzdG9tQWxwaGFiZXQoYWxwaGFiZXQsIDEpKCkgK1xuICBjdXN0b21BbHBoYWJldChmdWxsU3ltYm9sTGlzdCwgbGVuZ3RoKShsZW5ndGggLSAxKTtcbiIsICJpbXBvcnQgeyBFbGVtZW50TW9kZWwgfSBmcm9tIFwiLi4vLi4vdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgdXVpZCB9IGZyb20gXCJ1dGlscy9zcmMvdXVpZFwiO1xuXG5pbnRlcmZhY2UgRGF0YSB7XG4gIF9zdHlsZXM6IEFycmF5PHN0cmluZz47XG4gIGl0ZW1zOiBBcnJheTxFbGVtZW50TW9kZWw+O1xuICBbazogc3RyaW5nXTogc3RyaW5nIHwgQXJyYXk8c3RyaW5nIHwgRWxlbWVudE1vZGVsPjtcbn1cblxuZXhwb3J0IGNvbnN0IGNyZWF0ZUNsb25lYWJsZU1vZGVsID0gKGRhdGE6IERhdGEpOiBFbGVtZW50TW9kZWwgPT4ge1xuICBjb25zdCB7IF9zdHlsZXMsIGl0ZW1zLCAuLi52YWx1ZSB9ID0gZGF0YTtcbiAgcmV0dXJuIHtcbiAgICB0eXBlOiBcIkNsb25lYWJsZVwiLFxuICAgIHZhbHVlOiB7IF9pZDogdXVpZCgpLCBfc3R5bGVzLCBpdGVtcywgLi4udmFsdWUgfVxuICB9O1xufTtcbiIsICJpbXBvcnQgeyBtUGlwZSB9IGZyb20gXCJmcC11dGlsaXRpZXNcIjtcbmltcG9ydCAqIGFzIE9iaiBmcm9tIFwidXRpbHMvc3JjL3JlYWRlci9vYmplY3RcIjtcbmltcG9ydCAqIGFzIFN0ciBmcm9tIFwidXRpbHMvc3JjL3JlYWRlci9zdHJpbmdcIjtcblxuZXhwb3J0IGNvbnN0IGFsbG93ZWRUYWdzID0gW1xuICBcIlBcIixcbiAgXCJIMVwiLFxuICBcIkgyXCIsXG4gIFwiSDNcIixcbiAgXCJINFwiLFxuICBcIkg1XCIsXG4gIFwiSDZcIixcbiAgXCJVTFwiLFxuICBcIk9MXCIsXG4gIFwiTElcIlxuXTtcblxuZXhwb3J0IGNvbnN0IGV4Y2VwdEV4dHJhY3RpbmdTdHlsZSA9IFtcIlVMXCIsIFwiT0xcIl07XG5cbmV4cG9ydCBjb25zdCBleHRyYWN0ZWRBdHRyaWJ1dGVzID0gW1xuICBcImZvbnQtc2l6ZVwiLFxuICBcImZvbnQtZmFtaWx5XCIsXG4gIFwiZm9udC13ZWlnaHRcIixcbiAgXCJ0ZXh0LWFsaWduXCIsXG4gIFwibGV0dGVyLXNwYWNpbmdcIixcbiAgXCJ0ZXh0LXRyYW5zZm9ybVwiXG5dO1xuXG5leHBvcnQgY29uc3QgdGV4dEFsaWduOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+ID0ge1xuICBcIi13ZWJraXQtY2VudGVyXCI6IFwiY2VudGVyXCIsXG4gIFwiLW1vei1jZW50ZXJcIjogXCJjZW50ZXJcIixcbiAgc3RhcnQ6IFwibGVmdFwiLFxuICBlbmQ6IFwicmlnaHRcIixcbiAgbGVmdDogXCJsZWZ0XCIsXG4gIHJpZ2h0OiBcInJpZ2h0XCIsXG4gIGNlbnRlcjogXCJjZW50ZXJcIixcbiAganVzdGlmeTogXCJqdXN0aWZ5XCJcbn07XG5cbmV4cG9ydCBmdW5jdGlvbiBzaG91bGRFeHRyYWN0RWxlbWVudChcbiAgZWxlbWVudDogRWxlbWVudCxcbiAgZXhjZXB0aW9uczogQXJyYXk8c3RyaW5nPlxuKTogYm9vbGVhbiB7XG4gIGNvbnN0IGlzQWxsb3dlZCA9IGFsbG93ZWRUYWdzLmluY2x1ZGVzKGVsZW1lbnQudGFnTmFtZSk7XG5cbiAgaWYgKGlzQWxsb3dlZCAmJiBleGNlcHRpb25zKSB7XG4gICAgcmV0dXJuICFleGNlcHRpb25zLmluY2x1ZGVzKGVsZW1lbnQudGFnTmFtZSk7XG4gIH1cblxuICByZXR1cm4gaXNBbGxvd2VkO1xufVxuXG5leHBvcnQgY29uc3QgaWNvblNlbGVjdG9yID1cbiAgXCJbZGF0YS1zb2NpYWxpY29uXSxbc3R5bGUqPVxcXCJmb250LWZhbWlseTogJ01vbm8gU29jaWFsIEljb25zIEZvbnQnXFxcIl1cIjtcbmV4cG9ydCBjb25zdCBidXR0b25TZWxlY3RvciA9IFwiLnNpdGVzLWJ1dHRvblwiO1xuZXhwb3J0IGNvbnN0IGVtYmVkU2VsZWN0b3IgPSBcIi5lbWJlZGRlZC1wYXN0ZVwiO1xuXG5leHBvcnQgY29uc3QgZ2V0SHJlZiA9IG1QaXBlKE9iai5yZWFkS2V5KFwiaHJlZlwiKSwgU3RyLnJlYWQpO1xuIiwgImltcG9ydCB7IE9ialdpdGhVbmtub3ducywgUmVhZGVyIH0gZnJvbSBcIi4vdHlwZXNcIjtcblxuZXhwb3J0IGNvbnN0IGlzT2JqZWN0ID0gKHY6IHVua25vd24pOiB2IGlzIFJlY29yZDxzdHJpbmcsIHVua25vd24+ID0+XG4gIHR5cGVvZiB2ID09PSBcIm9iamVjdFwiICYmIHYgIT09IG51bGw7XG5cbmV4cG9ydCBjb25zdCBoYXNLZXkgPSA8VCBleHRlbmRzIHN0cmluZz4oXG4gIGtleTogVCxcbiAgb2JqOiBSZWNvcmQ8c3RyaW5nLCB1bmtub3duPlxuKTogb2JqIGlzIE9ialdpdGhVbmtub3duczxUPiA9PiBrZXkgaW4gb2JqO1xuXG5leHBvcnQgY29uc3QgcmVhZEtleSA9XG4gIChrZXk6IHN0cmluZykgPT5cbiAgLy8gZXNsaW50LWRpc2FibGUtbmV4dC1saW5lIEB0eXBlc2NyaXB0LWVzbGludC9uby1leHBsaWNpdC1hbnlcbiAgKG9iajogUmVjb3JkPHN0cmluZywgYW55Pik6IHVua25vd24gPT5cbiAgICBoYXNLZXkoa2V5LCBvYmopID8gb2JqW2tleV0gOiB1bmRlZmluZWQ7XG5cbmV4cG9ydCBjb25zdCByZWFkOiBSZWFkZXI8UmVjb3JkPHN0cmluZywgdW5rbm93bj4+ID0gKHYpID0+IHtcbiAgaWYgKGlzT2JqZWN0KHYpKSB7XG4gICAgcmV0dXJuIHY7XG4gIH1cblxuICByZXR1cm4gdW5kZWZpbmVkO1xufTtcbiIsICJpbXBvcnQgeyBSZWFkZXIgfSBmcm9tIFwiLi90eXBlc1wiO1xuXG5leHBvcnQgY29uc3QgcmVhZDogUmVhZGVyPHN0cmluZz4gPSAodikgPT4ge1xuICBzd2l0Y2ggKHR5cGVvZiB2KSB7XG4gICAgY2FzZSBcInN0cmluZ1wiOlxuICAgICAgcmV0dXJuIHY7XG4gICAgY2FzZSBcIm51bWJlclwiOlxuICAgICAgcmV0dXJuIGlzTmFOKHYpID8gdW5kZWZpbmVkIDogdi50b1N0cmluZygpO1xuICAgIGRlZmF1bHQ6XG4gICAgICByZXR1cm4gdW5kZWZpbmVkO1xuICB9XG59O1xuXG5leHBvcnQgY29uc3QgcmVhZE9ubHlTdHJpbmc6IFJlYWRlcjxzdHJpbmc+ID0gKGEpID0+IHtcbiAgc3dpdGNoICh0eXBlb2YgYSkge1xuICAgIGNhc2UgXCJzdHJpbmdcIjpcbiAgICAgIHJldHVybiBhO1xuICAgIGNhc2UgXCJudW1iZXJcIjpcbiAgICAgIHJldHVybiB1bmRlZmluZWQ7XG4gICAgZGVmYXVsdDpcbiAgICAgIHJldHVybiB1bmRlZmluZWQ7XG4gIH1cbn07XG5cbmV4cG9ydCBjb25zdCBpcyA9IChzOiB1bmtub3duKTogcyBpcyBzdHJpbmcgPT4ge1xuICByZXR1cm4gdHlwZW9mIHMgPT09IFwic3RyaW5nXCI7XG59O1xuIiwgImltcG9ydCB7IEVsZW1lbnRNb2RlbCB9IGZyb20gXCIuLi8uLi8uLi8uLi90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBnZXRIcmVmIH0gZnJvbSBcIi4uLy4uLy4uL3V0aWxzL2NvbW1vblwiO1xuaW1wb3J0IHsgbVBpcGUgfSBmcm9tIFwiZnAtdXRpbGl0aWVzXCI7XG5pbXBvcnQgeyBwYXJzZUNvbG9yU3RyaW5nIH0gZnJvbSBcInV0aWxzL3NyYy9jb2xvci9wYXJzZUNvbG9yU3RyaW5nXCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcbmltcG9ydCB7IHBpcGUgfSBmcm9tIFwidXRpbHMvc3JjL2ZwL3BpcGVcIjtcbmltcG9ydCB7IG9uTnVsbGlzaCB9IGZyb20gXCJ1dGlscy9zcmMvb25OdWxsaXNoXCI7XG5pbXBvcnQgKiBhcyBPYmogZnJvbSBcInV0aWxzL3NyYy9yZWFkZXIvb2JqZWN0XCI7XG5pbXBvcnQgKiBhcyBTdHIgZnJvbSBcInV0aWxzL3NyYy9yZWFkZXIvc3RyaW5nXCI7XG5pbXBvcnQgeyB1dWlkIH0gZnJvbSBcInV0aWxzL3NyYy91dWlkXCI7XG5cbmNvbnN0IGdldENvbG9yID0gbVBpcGUoT2JqLnJlYWRLZXkoXCJjb2xvclwiKSwgU3RyLnJlYWQsIHBhcnNlQ29sb3JTdHJpbmcpO1xuY29uc3QgZ2V0QmdDb2xvciA9IG1QaXBlKFxuICBPYmoucmVhZEtleShcImJhY2tncm91bmQtY29sb3JcIiksXG4gIFN0ci5yZWFkLFxuICBwYXJzZUNvbG9yU3RyaW5nXG4pO1xuY29uc3QgZ2V0VGV4dCA9IHBpcGUoT2JqLnJlYWRLZXkoXCJ0ZXh0XCIpLCBTdHIucmVhZCwgb25OdWxsaXNoKFwiQlVUVE9OXCIpKTtcblxuZXhwb3J0IGNvbnN0IGdldE1vZGVsID0gKG5vZGU6IEVsZW1lbnQpOiBFbGVtZW50TW9kZWwgPT4ge1xuICBjb25zdCBpc0xpbmsgPSBub2RlLnRhZ05hbWUgPT09IFwiQVwiO1xuICBjb25zdCBzdHlsZSA9IGdldE5vZGVTdHlsZShub2RlKTtcbiAgY29uc3QgY29sb3IgPSBnZXRDb2xvcihzdHlsZSk7XG4gIGNvbnN0IGJnQ29sb3IgPSBnZXRCZ0NvbG9yKHN0eWxlKTtcbiAgY29uc3Qgb3BhY2l0eSA9ICtzdHlsZS5vcGFjaXR5O1xuXG4gIHJldHVybiB7XG4gICAgdHlwZTogXCJCdXR0b25cIixcbiAgICB2YWx1ZToge1xuICAgICAgX2lkOiB1dWlkKCksXG4gICAgICBfc3R5bGVzOiBbXCJidXR0b25cIl0sXG4gICAgICBiZ0NvbG9ySGV4OiBiZ0NvbG9yPy5oZXggPz8gXCIjZmZmZmZmXCIsXG4gICAgICAuLi4oYmdDb2xvciAhPT0gdW5kZWZpbmVkICYmIHtcbiAgICAgICAgYmdDb2xvclBhbGV0dGU6IFwiXCJcbiAgICAgIH0pLFxuICAgICAgYmdDb2xvck9wYWNpdHk6IGlzTmFOKG9wYWNpdHkpID8gYmdDb2xvcj8ub3BhY2l0eSA/PyAxIDogb3BhY2l0eSxcbiAgICAgIGJnQ29sb3JUeXBlOiBcInNvbGlkXCIsXG4gICAgICBjb2xvckhleDogY29sb3I/LmhleCA/PyBcIiNmZmZmZmZcIixcbiAgICAgIGNvbG9yT3BhY2l0eTogY29sb3I/Lm9wYWNpdHkgPz8gMSxcbiAgICAgIC4uLihjb2xvciAhPT0gdW5kZWZpbmVkICYmIHtcbiAgICAgICAgY29sb3JQYWxldHRlOiBcIlwiXG4gICAgICB9KSxcbiAgICAgIHRleHQ6IGdldFRleHQobm9kZSksXG4gICAgICAuLi4oaXNMaW5rICYmIHtcbiAgICAgICAgbGlua0V4dGVybmFsOiBnZXRIcmVmKG5vZGUpLFxuICAgICAgICBsaW5rVHlwZTogXCJleHRlcm5hbFwiLFxuICAgICAgICBsaW5rRXh0ZXJuYWxCbGFuazogXCJvblwiXG4gICAgICB9KVxuICAgIH1cbiAgfTtcbn07XG4iLCAiaW1wb3J0IHsgTVZhbHVlIH0gZnJvbSBcIi4uL3R5cGVzXCI7XG5cbmludGVyZmFjZSBDb2xvciB7XG4gIGhleDogc3RyaW5nO1xuICBvcGFjaXR5OiBzdHJpbmc7XG59XG5cbmNvbnN0IGhleFJlZ2V4ID0gL14jKD86W0EtRmEtZjAtOV17M30pezEsMn0kLztcbmNvbnN0IHJnYlJlZ2V4ID0gL15yZ2JcXHMqWyhdXFxzKihcXGQrKVxccyosXFxzKihcXGQrKVxccyosXFxzKihcXGQrKVxccypbKV0kLztcbmNvbnN0IHJnYmFSZWdleCA9XG4gIC9ecmdiYVxccypbKF1cXHMqKFxcZCspXFxzKixcXHMqKFxcZCspXFxzKixcXHMqKFxcZCspXFxzKixcXHMqKDAqKD86XFwuXFxkKyk/fDEoPzpcXC4wKik/KVxccypbKV0kLztcblxuY29uc3QgaXNIZXggPSAodjogc3RyaW5nKTogYm9vbGVhbiA9PiBoZXhSZWdleC50ZXN0KHYpO1xuXG5jb25zdCBmcm9tUmdiID0gKHJnYjogW251bWJlciwgbnVtYmVyLCBudW1iZXJdKTogc3RyaW5nID0+IHtcbiAgcmV0dXJuIChcbiAgICBcIiNcIiArXG4gICAgKFwiMFwiICsgcmdiWzBdLnRvU3RyaW5nKDE2KSkuc2xpY2UoLTIpICtcbiAgICAoXCIwXCIgKyByZ2JbMV0udG9TdHJpbmcoMTYpKS5zbGljZSgtMikgK1xuICAgIChcIjBcIiArIHJnYlsyXS50b1N0cmluZygxNikpLnNsaWNlKC0yKVxuICApO1xufTtcblxuZnVuY3Rpb24gcGFyc2VSZ2IoY29sb3I6IHN0cmluZyk6IE1WYWx1ZTxbbnVtYmVyLCBudW1iZXIsIG51bWJlcl0+IHtcbiAgY29uc3QgbWF0Y2hlcyA9IHJnYlJlZ2V4LmV4ZWMoY29sb3IpO1xuXG4gIGlmIChtYXRjaGVzKSB7XG4gICAgY29uc3QgW3IsIGcsIGJdID0gbWF0Y2hlcy5zbGljZSgxKS5tYXAoTnVtYmVyKTtcbiAgICByZXR1cm4gW3IsIGcsIGJdO1xuICB9XG5cbiAgcmV0dXJuIHVuZGVmaW5lZDtcbn1cblxuZnVuY3Rpb24gcGFyc2VSZ2JhKGNvbG9yOiBzdHJpbmcpOiBNVmFsdWU8W251bWJlciwgbnVtYmVyLCBudW1iZXIsIG51bWJlcl0+IHtcbiAgY29uc3QgbWF0Y2hlcyA9IHJnYmFSZWdleC5leGVjKGNvbG9yKTtcblxuICBpZiAobWF0Y2hlcykge1xuICAgIGNvbnN0IFtyLCBnLCBiLCBhXSA9IG1hdGNoZXMuc2xpY2UoMSkubWFwKE51bWJlcik7XG4gICAgcmV0dXJuIFtyLCBnLCBiLCBhXTtcbiAgfVxuXG4gIHJldHVybiB1bmRlZmluZWQ7XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBwYXJzZUNvbG9yU3RyaW5nKGNvbG9yU3RyaW5nOiBzdHJpbmcpOiBNVmFsdWU8Q29sb3I+IHtcbiAgaWYgKGlzSGV4KGNvbG9yU3RyaW5nKSkge1xuICAgIHJldHVybiB7XG4gICAgICBoZXg6IGNvbG9yU3RyaW5nLFxuICAgICAgb3BhY2l0eTogXCIxXCJcbiAgICB9O1xuICB9XG5cbiAgY29uc3QgcmdiUmVzdWx0ID0gcGFyc2VSZ2IoY29sb3JTdHJpbmcpO1xuICBpZiAocmdiUmVzdWx0KSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGhleDogZnJvbVJnYihyZ2JSZXN1bHQpLFxuICAgICAgb3BhY2l0eTogXCIxXCJcbiAgICB9O1xuICB9XG5cbiAgY29uc3QgcmdiYVJlc3VsdCA9IHBhcnNlUmdiYShjb2xvclN0cmluZyk7XG4gIGlmIChyZ2JhUmVzdWx0KSB7XG4gICAgY29uc3QgW3IsIGcsIGIsIGFdID0gcmdiYVJlc3VsdDtcbiAgICByZXR1cm4ge1xuICAgICAgaGV4OiBmcm9tUmdiKFtyLCBnLCBiXSksXG4gICAgICBvcGFjaXR5OiBTdHJpbmcoYSlcbiAgICB9O1xuICB9XG5cbiAgcmV0dXJuIHVuZGVmaW5lZDtcbn1cbiIsICJpbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcIi4uL3R5cGVzXCI7XG5cbmV4cG9ydCBjb25zdCBnZXROb2RlU3R5bGUgPSAoXG4gIG5vZGU6IEhUTUxFbGVtZW50IHwgRWxlbWVudFxuKTogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4gPT4ge1xuICBjb25zdCBjb21wdXRlZFN0eWxlcyA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKG5vZGUpO1xuICBjb25zdCBzdHlsZXM6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+ID0ge307XG5cbiAgT2JqZWN0LnZhbHVlcyhjb21wdXRlZFN0eWxlcykuZm9yRWFjaCgoa2V5KSA9PiB7XG4gICAgc3R5bGVzW2tleV0gPSBjb21wdXRlZFN0eWxlcy5nZXRQcm9wZXJ0eVZhbHVlKGtleSk7XG4gIH0pO1xuXG4gIHJldHVybiBzdHlsZXM7XG59O1xuIiwgImV4cG9ydCBmdW5jdGlvbiBwaXBlPFQxPihmbjA6ICgpID0+IFQxKTogKCkgPT4gVDE7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVDE+KGZuMDogKHgwOiBWMCkgPT4gVDEpOiAoeDA6IFYwKSA9PiBUMTtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVDE+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDFcbik6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDE7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFYyLCBUMT4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQxXG4pOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDE7XG5cbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFQxLCBUMj4oZm4wOiAoKSA9PiBUMSwgZm4xOiAoeDogVDEpID0+IFQyKTogKCkgPT4gVDI7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVDEsIFQyPihcbiAgZm4wOiAoeDA6IFYwKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyXG4pOiAoeDA6IFYwKSA9PiBUMjtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVDEsIFQyPihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDJcbik6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDI7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFYyLCBUMSwgVDI+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyXG4pOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDI7XG5cbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFQxLCBUMiwgVDM+KFxuICBmbjA6ICgpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUM1xuKTogKCkgPT4gVDM7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVDEsIFQyLCBUMz4oXG4gIGZuMDogKHg6IFYwKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDNcbik6ICh4OiBWMCkgPT4gVDM7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFQxLCBUMiwgVDM+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzXG4pOiAoeDA6IFYwLCB4MTogVjEpID0+IFQzO1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBWMiwgVDEsIFQyLCBUMz4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUM1xuKTogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQzO1xuXG5leHBvcnQgZnVuY3Rpb24gcGlwZTxUMSwgVDIsIFQzLCBUND4oXG4gIGZuMDogKCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDRcbik6ICgpID0+IFQ0O1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFQxLCBUMiwgVDMsIFQ0PihcbiAgZm4wOiAoeDogVjApID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0XG4pOiAoeDogVjApID0+IFQ0O1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBUMSwgVDIsIFQzLCBUND4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNFxuKTogKHgwOiBWMCwgeDE6IFYxKSA9PiBUNDtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVjIsIFQxLCBUMiwgVDMsIFQ0PihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDRcbik6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUNDtcblxuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VDEsIFQyLCBUMywgVDQsIFQ1PihcbiAgZm4wOiAoKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1XG4pOiAoKSA9PiBUNTtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBUMSwgVDIsIFQzLCBUNCwgVDU+KFxuICBmbjA6ICh4OiBWMCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNVxuKTogKHg6IFYwKSA9PiBUNTtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVDEsIFQyLCBUMywgVDQsIFQ1PihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDVcbik6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDU7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFYyLCBUMSwgVDIsIFQzLCBUNCwgVDU+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1XG4pOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDU7XG5cbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDY+KFxuICBmbjA6ICgpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNlxuKTogKCkgPT4gVDY7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNj4oXG4gIGZuMDogKHg6IFYwKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDZcbik6ICh4OiBWMCkgPT4gVDY7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDY+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2XG4pOiAoeDA6IFYwLCB4MTogVjEpID0+IFQ2O1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBWMiwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNj4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNlxuKTogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQ2O1xuXG5leHBvcnQgZnVuY3Rpb24gcGlwZTxUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNz4oXG4gIGZuMDogKCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjogKHg6IFQ2KSA9PiBUN1xuKTogKCkgPT4gVDc7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDc+KFxuICBmbjA6ICh4OiBWMCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjogKHg6IFQ2KSA9PiBUN1xuKTogKHg6IFYwKSA9PiBUNztcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDc+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjY6ICh4OiBUNikgPT4gVDdcbik6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDc7XG5leHBvcnQgZnVuY3Rpb24gcGlwZTxWMCwgVjEsIFYyLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNz4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3XG4pOiAoeDA6IFYwLCB4MTogVjEsIHgyOiBWMikgPT4gVDc7XG5cbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDYsIFQ3LCBUOD4oXG4gIGZuMDogKCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjY6ICh4OiBUNikgPT4gVDcsXG4gIGZuOiAoeDogVDcpID0+IFQ4XG4pOiAoKSA9PiBUODtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNywgVDg+KFxuICBmbjA6ICh4OiBWMCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjY6ICh4OiBUNikgPT4gVDcsXG4gIGZuOiAoeDogVDcpID0+IFQ4XG4pOiAoeDogVjApID0+IFQ4O1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNywgVDg+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjY6ICh4OiBUNikgPT4gVDcsXG4gIGZuNzogKHg6IFQ3KSA9PiBUOFxuKTogKHgwOiBWMCwgeDE6IFYxKSA9PiBUODtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVjIsIFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDYsIFQ3LCBUOD4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3LFxuICBmbjc6ICh4OiBUNykgPT4gVDhcbik6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUODtcblxuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDcsIFQ4LCBUOT4oXG4gIGZuMDogKCkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjY6ICh4OiBUNikgPT4gVDcsXG4gIGZuNzogKHg6IFQ3KSA9PiBUOCxcbiAgZm44OiAoeDogVDgpID0+IFQ5XG4pOiAoKSA9PiBUOTtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNywgVDgsIFQ5PihcbiAgZm4wOiAoeDA6IFYwKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDYsXG4gIGZuNjogKHg6IFQ2KSA9PiBUNyxcbiAgZm43OiAoeDogVDcpID0+IFQ4LFxuICBmbjg6ICh4OiBUOCkgPT4gVDlcbik6ICh4MDogVjApID0+IFQ5O1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNywgVDgsIFQ5PihcbiAgZm4wOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3LFxuICBmbjc6ICh4OiBUNykgPT4gVDgsXG4gIGZuODogKHg6IFQ4KSA9PiBUOVxuKTogKHgwOiBWMCwgeDE6IFYxKSA9PiBUOTtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVjIsIFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDYsIFQ3LCBUOCwgVDk+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDYsXG4gIGZuNjogKHg6IFQ2KSA9PiBUNyxcbiAgZm43OiAoeDogVDcpID0+IFQ4LFxuICBmbjg6ICh4OiBUOCkgPT4gVDlcbik6ICh4MDogVjAsIHgxOiBWMSwgeDI6IFYyKSA9PiBUOTtcblxuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VDEsIFQyLCBUMywgVDQsIFQ1LCBUNiwgVDcsIFQ4LCBUOSwgVDEwPihcbiAgZm4wOiAoKSA9PiBUMSxcbiAgZm4xOiAoeDogVDEpID0+IFQyLFxuICBmbjI6ICh4OiBUMikgPT4gVDMsXG4gIGZuMzogKHg6IFQzKSA9PiBUNCxcbiAgZm40OiAoeDogVDQpID0+IFQ1LFxuICBmbjU6ICh4OiBUNSkgPT4gVDYsXG4gIGZuNjogKHg6IFQ2KSA9PiBUNyxcbiAgZm43OiAoeDogVDcpID0+IFQ4LFxuICBmbjg6ICh4OiBUOCkgPT4gVDksXG4gIGZuOTogKHg6IFQ5KSA9PiBUMTBcbik6ICgpID0+IFQxMDtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNywgVDgsIFQ5LCBUMTA+KFxuICBmbjA6ICh4MDogVjApID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3LFxuICBmbjc6ICh4OiBUNykgPT4gVDgsXG4gIGZuODogKHg6IFQ4KSA9PiBUOSxcbiAgZm45OiAoeDogVDkpID0+IFQxMFxuKTogKHgwOiBWMCkgPT4gVDEwO1xuZXhwb3J0IGZ1bmN0aW9uIHBpcGU8VjAsIFYxLCBUMSwgVDIsIFQzLCBUNCwgVDUsIFQ2LCBUNywgVDgsIFQ5LCBUMTA+KFxuICBmbjA6ICh4MDogVjAsIHgxOiBWMSkgPT4gVDEsXG4gIGZuMTogKHg6IFQxKSA9PiBUMixcbiAgZm4yOiAoeDogVDIpID0+IFQzLFxuICBmbjM6ICh4OiBUMykgPT4gVDQsXG4gIGZuNDogKHg6IFQ0KSA9PiBUNSxcbiAgZm41OiAoeDogVDUpID0+IFQ2LFxuICBmbjY6ICh4OiBUNikgPT4gVDcsXG4gIGZuNzogKHg6IFQ3KSA9PiBUOCxcbiAgZm44OiAoeDogVDgpID0+IFQ5LFxuICBmbjk6ICh4OiBUOSkgPT4gVDEwXG4pOiAoeDA6IFYwLCB4MTogVjEpID0+IFQxMDtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlPFYwLCBWMSwgVjIsIFQxLCBUMiwgVDMsIFQ0LCBUNSwgVDYsIFQ3LCBUOCwgVDksIFQxMD4oXG4gIGZuMDogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQxLFxuICBmbjE6ICh4OiBUMSkgPT4gVDIsXG4gIGZuMjogKHg6IFQyKSA9PiBUMyxcbiAgZm4zOiAoeDogVDMpID0+IFQ0LFxuICBmbjQ6ICh4OiBUNCkgPT4gVDUsXG4gIGZuNTogKHg6IFQ1KSA9PiBUNixcbiAgZm42OiAoeDogVDYpID0+IFQ3LFxuICBmbjc6ICh4OiBUNykgPT4gVDgsXG4gIGZuODogKHg6IFQ4KSA9PiBUOSxcbiAgZm45OiAoeDogVDkpID0+IFQxMFxuKTogKHgwOiBWMCwgeDE6IFYxLCB4MjogVjIpID0+IFQxMDtcbmV4cG9ydCBmdW5jdGlvbiBwaXBlKC4uLltoLCAuLi5mbnNdOiBbRiwgLi4uRltdXSkge1xuICByZXR1cm4gKC4uLmFyZ3M6IHVua25vd25bXSk6IHVua25vd24gPT5cbiAgICBmbnMucmVkdWNlKCh2LCBmbikgPT4gZm4odiksIGgoLi4uYXJncykpO1xufVxudHlwZSBGID0gKC4uLmFyZ3M6IHVua25vd25bXSkgPT4gdW5rbm93bjtcbiIsICJpbXBvcnQgeyBOdWxsaXNoIH0gZnJvbSBcIi4vdHlwZXNcIjtcblxuZXhwb3J0IGNvbnN0IGlzTnVsbGlzaCA9ICh2OiB1bmtub3duKTogdiBpcyBOdWxsaXNoID0+XG4gIHYgPT09IHVuZGVmaW5lZCB8fCB2ID09PSBudWxsIHx8ICh0eXBlb2YgdiA9PT0gXCJudW1iZXJcIiAmJiBOdW1iZXIuaXNOYU4odikpO1xuIiwgImltcG9ydCB7IGlzTnVsbGlzaCB9IGZyb20gXCIuL2lzTnVsbGlzaFwiO1xuaW1wb3J0IHsgTnVsbGlzaCB9IGZyb20gXCIuL3R5cGVzXCI7XG5cbmV4cG9ydCBmdW5jdGlvbiBvbk51bGxpc2g8VD4ob3JFbHNlOiBULCB2OiBUIHwgTnVsbGlzaCk6IFQ7XG5leHBvcnQgZnVuY3Rpb24gb25OdWxsaXNoPFQ+KG9yRWxzZTogVCk6ICh2OiBUIHwgTnVsbGlzaCkgPT4gVDtcbmV4cG9ydCBmdW5jdGlvbiBvbk51bGxpc2g8VD4oXG4gIC4uLmFyZ3M6IFtUXSB8IFtULCBUIHwgTnVsbGlzaF1cbik6IFQgfCAoKHY6IFQgfCBOdWxsaXNoKSA9PiBUKSB7XG4gIHJldHVybiBhcmdzLmxlbmd0aCA9PT0gMVxuICAgID8gKHY6IFQgfCBOdWxsaXNoKTogVCA9PiAoaXNOdWxsaXNoKHYpID8gYXJnc1swXSA6IHYpXG4gICAgOiBpc051bGxpc2goYXJnc1sxXSlcbiAgICA/IGFyZ3NbMF1cbiAgICA6IGFyZ3NbMV07XG59XG4iLCAiaW1wb3J0IHsgTVZhbHVlIH0gZnJvbSBcIi4uL3R5cGVzXCI7XG5cbmV4cG9ydCBmdW5jdGlvbiBmaW5kTmVhcmVzdEJsb2NrUGFyZW50KGVsZW1lbnQ6IEVsZW1lbnQpOiBNVmFsdWU8RWxlbWVudD4ge1xuICBpZiAoIWVsZW1lbnQucGFyZW50RWxlbWVudCkge1xuICAgIHJldHVybiB1bmRlZmluZWQ7XG4gIH1cblxuICBjb25zdCBkaXNwbGF5U3R5bGUgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShlbGVtZW50LnBhcmVudEVsZW1lbnQpLmRpc3BsYXk7XG4gIGNvbnN0IGlzQmxvY2tFbGVtZW50ID1cbiAgICBkaXNwbGF5U3R5bGUgPT09IFwiYmxvY2tcIiB8fFxuICAgIGRpc3BsYXlTdHlsZSA9PT0gXCJmbGV4XCIgfHxcbiAgICBkaXNwbGF5U3R5bGUgPT09IFwiZ3JpZFwiO1xuXG4gIGlmIChpc0Jsb2NrRWxlbWVudCkge1xuICAgIHJldHVybiBlbGVtZW50LnBhcmVudEVsZW1lbnQ7XG4gIH0gZWxzZSB7XG4gICAgcmV0dXJuIGZpbmROZWFyZXN0QmxvY2tQYXJlbnQoZWxlbWVudC5wYXJlbnRFbGVtZW50KTtcbiAgfVxufVxuIiwgImltcG9ydCB7IGNyZWF0ZUNsb25lYWJsZU1vZGVsIH0gZnJvbSBcIi4uLy4uLy4uL01vZGVscy9DbG9uZWFibGVcIjtcbmltcG9ydCB7IEVsZW1lbnRNb2RlbCB9IGZyb20gXCIuLi8uLi8uLi90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBidXR0b25TZWxlY3RvciwgdGV4dEFsaWduIH0gZnJvbSBcIi4uLy4uL3V0aWxzL2NvbW1vblwiO1xuaW1wb3J0IHsgZ2V0TW9kZWwgfSBmcm9tIFwiLi91dGlscy9nZXRNb2RlbFwiO1xuaW1wb3J0IHsgZmluZE5lYXJlc3RCbG9ja1BhcmVudCB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2ZpbmROZWFyZXN0QmxvY2tQYXJlbnRcIjtcbmltcG9ydCB7IGdldE5vZGVTdHlsZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldE5vZGVTdHlsZVwiO1xuXG5leHBvcnQgZnVuY3Rpb24gZ2V0QnV0dG9uTW9kZWwobm9kZTogRWxlbWVudCk6IEFycmF5PEVsZW1lbnRNb2RlbD4ge1xuICBjb25zdCBidXR0b25zID0gbm9kZS5xdWVyeVNlbGVjdG9yQWxsKGJ1dHRvblNlbGVjdG9yKTtcbiAgY29uc3QgZ3JvdXBzID0gbmV3IE1hcCgpO1xuXG4gIGJ1dHRvbnMuZm9yRWFjaCgoYnV0dG9uKSA9PiB7XG4gICAgY29uc3QgcGFyZW50RWxlbWVudCA9IGZpbmROZWFyZXN0QmxvY2tQYXJlbnQoYnV0dG9uKTtcbiAgICBjb25zdCBzdHlsZSA9IGdldE5vZGVTdHlsZShidXR0b24pO1xuICAgIGNvbnN0IG1vZGVsID0gZ2V0TW9kZWwoYnV0dG9uKTtcbiAgICBjb25zdCBncm91cCA9IGdyb3Vwcy5nZXQocGFyZW50RWxlbWVudCkgPz8geyB2YWx1ZTogeyBpdGVtczogW10gfSB9O1xuXG4gICAgY29uc3Qgd3JhcHBlck1vZGVsID0gY3JlYXRlQ2xvbmVhYmxlTW9kZWwoe1xuICAgICAgX3N0eWxlczogW1wid3JhcHBlci1jbG9uZVwiLCBcIndyYXBwZXItY2xvbmUtLWJ1dHRvblwiXSxcbiAgICAgIGl0ZW1zOiBbLi4uZ3JvdXAudmFsdWUuaXRlbXMsIG1vZGVsXSxcbiAgICAgIGhvcml6b250YWxBbGlnbjogdGV4dEFsaWduW3N0eWxlW1widGV4dC1hbGlnblwiXV1cbiAgICB9KTtcblxuICAgIGdyb3Vwcy5zZXQocGFyZW50RWxlbWVudCwgd3JhcHBlck1vZGVsKTtcbiAgfSk7XG5cbiAgY29uc3QgbW9kZWxzOiBBcnJheTxFbGVtZW50TW9kZWw+ID0gW107XG5cbiAgZ3JvdXBzLmZvckVhY2goKG1vZGVsKSA9PiB7XG4gICAgbW9kZWxzLnB1c2gobW9kZWwpO1xuICB9KTtcblxuICByZXR1cm4gbW9kZWxzO1xufVxuIiwgImltcG9ydCB7IEVtYmVkTW9kZWwgfSBmcm9tIFwiLi4vLi4vLi4vdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgZW1iZWRTZWxlY3RvciB9IGZyb20gXCIuLi8uLi91dGlscy9jb21tb25cIjtcblxuZXhwb3J0IGZ1bmN0aW9uIGdldEVtYmVkTW9kZWwobm9kZTogRWxlbWVudCk6IEFycmF5PEVtYmVkTW9kZWw+IHtcbiAgY29uc3QgZW1iZWRzID0gbm9kZS5xdWVyeVNlbGVjdG9yQWxsKGVtYmVkU2VsZWN0b3IpO1xuICBjb25zdCBtb2RlbHM6IEFycmF5PEVtYmVkTW9kZWw+ID0gW107XG5cbiAgZW1iZWRzLmZvckVhY2goKCkgPT4ge1xuICAgIG1vZGVscy5wdXNoKHsgdHlwZTogXCJFbWJlZENvZGVcIiB9KTtcbiAgfSk7XG5cbiAgcmV0dXJuIG1vZGVscztcbn1cbiIsICJpbXBvcnQgeyBFbGVtZW50TW9kZWwgfSBmcm9tIFwiLi4vLi4vLi4vLi4vdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgZ2V0SHJlZiB9IGZyb20gXCIuLi8uLi8uLi91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IG1QaXBlIH0gZnJvbSBcImZwLXV0aWxpdGllc1wiO1xuaW1wb3J0IHsgcGFyc2VDb2xvclN0cmluZyB9IGZyb20gXCJ1dGlscy9zcmMvY29sb3IvcGFyc2VDb2xvclN0cmluZ1wiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5pbXBvcnQgeyBnZXRQYXJlbnRFbGVtZW50T2ZUZXh0Tm9kZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldFBhcmVudEVsZW1lbnRPZlRleHROb2RlXCI7XG5pbXBvcnQgKiBhcyBPYmogZnJvbSBcInV0aWxzL3NyYy9yZWFkZXIvb2JqZWN0XCI7XG5pbXBvcnQgKiBhcyBTdHIgZnJvbSBcInV0aWxzL3NyYy9yZWFkZXIvc3RyaW5nXCI7XG5pbXBvcnQgeyB1dWlkIH0gZnJvbSBcInV0aWxzL3NyYy91dWlkXCI7XG5cbmNvbnN0IGNvZGVUb0J1aWxkZXJNYXA6IFJlY29yZDxzdHJpbmcsIHN0cmluZz4gPSB7XG4gIGZhY2Vib29rOiBcImxvZ28tZmFjZWJvb2tcIixcbiAgaW5zdGFncmFtOiBcImxvZ28taW5zdGFncmFtXCIsXG4gIHlvdXR1YmU6IFwibG9nby15b3V0dWJlXCIsXG4gIHR3aXR0ZXI6IFwibG9nby10d2l0dGVyXCIsXG4gIHZpbWVvOiBcImxvZ28tdmltZW9cIixcbiAgbWFpbDogXCJlbWFpbC04NVwiLFxuICBhcHBsZTogXCJhcHBsZVwiLFxuICA1NzM4MDogXCJlbWFpbC04NVwiLFxuICA1ODYyNDogXCJsb2dvLWluc3RhZ3JhbVwiLFxuICA1ODQwNzogXCJsb2dvLWZhY2Vib29rXCIsXG4gIDU3ODk1OiBcImxvZ28tZmFjZWJvb2tcIixcbiAgNTc5MzY6IFwibm90ZS0wM1wiLFxuICA1ODAwOTogXCJsb2dvLXlvdXR1YmVcIixcbiAgNTc5OTM6IFwibG9nby12aW1lb1wiLFxuICA1Nzk5MDogXCJsb2dvLXR3aXR0ZXJcIixcbiAgNTgxMTI6IFwibG9nby1pbnN0YWdyYW1cIixcbiAgNTg1MjE6IFwibG9nby15b3V0dWJlXCIsXG4gIDU4NTAzOiBcImxvZ28tdHdpdHRlclwiLFxuICA1Nzg5MjogXCJlbWFpbC04NVwiLFxuICA1NzY4NjogXCJwaW4tM1wiXG59O1xuY29uc3QgZ2V0Q29sb3IgPSBtUGlwZShPYmoucmVhZEtleShcImNvbG9yXCIpLCBTdHIucmVhZCwgcGFyc2VDb2xvclN0cmluZyk7XG5jb25zdCBnZXRCZ0NvbG9yID0gbVBpcGUoXG4gIE9iai5yZWFkS2V5KFwiYmFja2dyb3VuZC1jb2xvclwiKSxcbiAgU3RyLnJlYWQsXG4gIHBhcnNlQ29sb3JTdHJpbmdcbik7XG5cbmV4cG9ydCBmdW5jdGlvbiBnZXRNb2RlbChub2RlOiBFbGVtZW50KTogRWxlbWVudE1vZGVsIHtcbiAgY29uc3QgcGFyZW50Tm9kZSA9IGdldFBhcmVudEVsZW1lbnRPZlRleHROb2RlKG5vZGUpO1xuICBjb25zdCBpc0ljb25UZXh0ID0gcGFyZW50Tm9kZT8ubm9kZU5hbWUgPT09IFwiI3RleHRcIjtcbiAgY29uc3QgaWNvbk5vZGUgPSBpc0ljb25UZXh0ID8gbm9kZSA6IHBhcmVudE5vZGU7XG4gIGNvbnN0IHN0eWxlID0gaWNvbk5vZGUgPyBnZXROb2RlU3R5bGUoaWNvbk5vZGUpIDoge307XG4gIGNvbnN0IHBhcmVudEVsZW1lbnQgPSBub2RlLnBhcmVudEVsZW1lbnQ7XG4gIGNvbnN0IGlzTGluayA9IHBhcmVudEVsZW1lbnQ/LnRhZ05hbWUgPT09IFwiQVwiIHx8IG5vZGUudGFnTmFtZSA9PT0gXCJBXCI7XG4gIGNvbnN0IHBhcmVudFN0eWxlID0gcGFyZW50RWxlbWVudCA/IGdldE5vZGVTdHlsZShwYXJlbnRFbGVtZW50KSA6IHt9O1xuICBjb25zdCBwYXJlbnRCZ0NvbG9yID0gZ2V0QmdDb2xvcihwYXJlbnRTdHlsZSk7XG4gIGNvbnN0IHBhcmVudEhyZWYgPSBnZXRIcmVmKHBhcmVudEVsZW1lbnQpID8/IGdldEhyZWYobm9kZSkgPz8gXCJcIjtcbiAgY29uc3Qgb3BhY2l0eSA9ICtzdHlsZS5vcGFjaXR5O1xuICBjb25zdCBjb2xvciA9IGdldENvbG9yKHN0eWxlKTtcbiAgY29uc3QgaWNvbkNvZGUgPSBpY29uTm9kZT8udGV4dENvbnRlbnQ/LmNoYXJDb2RlQXQoMCk7XG5cbiAgcmV0dXJuIHtcbiAgICB0eXBlOiBcIkljb25cIixcbiAgICB2YWx1ZToge1xuICAgICAgX2lkOiB1dWlkKCksXG4gICAgICBfc3R5bGVzOiBbXCJpY29uXCJdLFxuICAgICAgY29sb3JIZXg6IGNvbG9yPy5oZXggPz8gXCIjZmZmZmZmXCIsXG4gICAgICBjb2xvck9wYWNpdHk6IGlzTmFOKG9wYWNpdHkpID8gY29sb3I/Lm9wYWNpdHkgPz8gMSA6IG9wYWNpdHksXG4gICAgICAuLi4oY29sb3IgIT09IHVuZGVmaW5lZCAmJiB7IGNvbG9yUGFsZXR0ZTogXCJcIiB9KSxcbiAgICAgIG5hbWU6IGljb25Db2RlXG4gICAgICAgID8gY29kZVRvQnVpbGRlck1hcFtpY29uQ29kZV0gPz8gXCJmYXZvdXJpdGUtMzFcIlxuICAgICAgICA6IFwiZmF2b3VyaXRlLTMxXCIsXG4gICAgICAuLi4oaXNMaW5rICYmIHtcbiAgICAgICAgbGlua0V4dGVybmFsOiBwYXJlbnRIcmVmLFxuICAgICAgICBsaW5rVHlwZTogXCJleHRlcm5hbFwiLFxuICAgICAgICBsaW5rRXh0ZXJuYWxCbGFuazogXCJvblwiLFxuICAgICAgICAuLi4ocGFyZW50QmdDb2xvciAmJiB7XG4gICAgICAgICAgYmdDb2xvckhleDogcGFyZW50QmdDb2xvci5oZXgsXG4gICAgICAgICAgYmdDb2xvck9wYWNpdHk6IHBhcmVudEJnQ29sb3Iub3BhY2l0eSxcbiAgICAgICAgICBiZ0NvbG9yUGFsZXR0ZTogXCJcIlxuICAgICAgICB9KVxuICAgICAgfSlcbiAgICB9XG4gIH07XG59XG4iLCAiaW1wb3J0IHsgTVZhbHVlIH0gZnJvbSBcIi4uL3R5cGVzXCI7XG5cbmV4cG9ydCBmdW5jdGlvbiBnZXRQYXJlbnRFbGVtZW50T2ZUZXh0Tm9kZShub2RlOiBFbGVtZW50KTogTVZhbHVlPEVsZW1lbnQ+IHtcbiAgaWYgKG5vZGUubm9kZVR5cGUgPT09IE5vZGUuVEVYVF9OT0RFKSB7XG4gICAgcmV0dXJuIChub2RlLnBhcmVudE5vZGUgYXMgRWxlbWVudCkgPz8gdW5kZWZpbmVkO1xuICB9XG5cbiAgcmV0dXJuIEFycmF5LmZyb20obm9kZS5jaGlsZE5vZGVzKS5maW5kKChub2RlKSA9PlxuICAgIGdldFBhcmVudEVsZW1lbnRPZlRleHROb2RlKG5vZGUgYXMgRWxlbWVudClcbiAgKSBhcyBFbGVtZW50O1xufVxuIiwgImltcG9ydCB7IGNyZWF0ZUNsb25lYWJsZU1vZGVsIH0gZnJvbSBcIi4uLy4uLy4uL01vZGVscy9DbG9uZWFibGVcIjtcbmltcG9ydCB7IEVsZW1lbnRNb2RlbCB9IGZyb20gXCIuLi8uLi8uLi90eXBlcy90eXBlXCI7XG5pbXBvcnQgeyBpY29uU2VsZWN0b3IsIHRleHRBbGlnbiB9IGZyb20gXCIuLi8uLi91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IGdldE1vZGVsIH0gZnJvbSBcIi4vdXRpbHMvZ2V0TW9kZWxcIjtcbmltcG9ydCB7IGZpbmROZWFyZXN0QmxvY2tQYXJlbnQgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9maW5kTmVhcmVzdEJsb2NrUGFyZW50XCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcbmltcG9ydCB7IGdldFBhcmVudEVsZW1lbnRPZlRleHROb2RlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0UGFyZW50RWxlbWVudE9mVGV4dE5vZGVcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIGdldEljb25Nb2RlbChub2RlOiBFbGVtZW50KTogQXJyYXk8RWxlbWVudE1vZGVsPiB7XG4gIGNvbnN0IGljb25zID0gbm9kZS5xdWVyeVNlbGVjdG9yQWxsKGljb25TZWxlY3Rvcik7XG4gIGNvbnN0IGdyb3VwcyA9IG5ldyBNYXAoKTtcblxuICBpY29ucy5mb3JFYWNoKChpY29uKSA9PiB7XG4gICAgY29uc3QgcGFyZW50RWxlbWVudCA9IGZpbmROZWFyZXN0QmxvY2tQYXJlbnQoaWNvbik7XG4gICAgY29uc3QgcGFyZW50Tm9kZSA9IGdldFBhcmVudEVsZW1lbnRPZlRleHROb2RlKG5vZGUpO1xuICAgIGNvbnN0IGlzSWNvblRleHQgPSBwYXJlbnROb2RlPy5ub2RlTmFtZSA9PT0gXCIjdGV4dFwiO1xuICAgIGNvbnN0IGljb25Ob2RlID0gaXNJY29uVGV4dCA/IG5vZGUgOiBwYXJlbnROb2RlO1xuICAgIGNvbnN0IHN0eWxlID0gaWNvbk5vZGUgPyBnZXROb2RlU3R5bGUoaWNvbk5vZGUpIDoge307XG4gICAgY29uc3QgbW9kZWwgPSBnZXRNb2RlbChpY29uKTtcbiAgICBjb25zdCBncm91cCA9IGdyb3Vwcy5nZXQocGFyZW50RWxlbWVudCkgPz8geyB2YWx1ZTogeyBpdGVtczogW10gfSB9O1xuXG4gICAgY29uc3Qgd3JhcHBlck1vZGVsID0gY3JlYXRlQ2xvbmVhYmxlTW9kZWwoe1xuICAgICAgX3N0eWxlczogW1wid3JhcHBlci1jbG9uZVwiLCBcIndyYXBwZXItY2xvbmUtLWljb25cIl0sXG4gICAgICBpdGVtczogWy4uLmdyb3VwLnZhbHVlLml0ZW1zLCBtb2RlbF0sXG4gICAgICBob3Jpem9udGFsQWxpZ246IHRleHRBbGlnbltzdHlsZVtcInRleHQtYWxpZ25cIl1dXG4gICAgfSk7XG5cbiAgICBncm91cHMuc2V0KHBhcmVudEVsZW1lbnQsIHdyYXBwZXJNb2RlbCk7XG4gIH0pO1xuXG4gIGNvbnN0IG1vZGVsczogQXJyYXk8RWxlbWVudE1vZGVsPiA9IFtdO1xuXG4gIGdyb3Vwcy5mb3JFYWNoKChtb2RlbCkgPT4ge1xuICAgIG1vZGVscy5wdXNoKG1vZGVsKTtcbiAgfSk7XG5cbiAgcmV0dXJuIG1vZGVscztcbn1cbiIsICJpbXBvcnQgeyBFbGVtZW50TW9kZWwgfSBmcm9tIFwiLi4vLi4vdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgdXVpZCB9IGZyb20gXCJ1dGlscy9zcmMvdXVpZFwiO1xuXG5pbnRlcmZhY2UgRGF0YSB7XG4gIF9zdHlsZXM6IEFycmF5PHN0cmluZz47XG4gIGl0ZW1zOiBBcnJheTxFbGVtZW50TW9kZWw+O1xuICBbazogc3RyaW5nXTogc3RyaW5nIHwgQXJyYXk8c3RyaW5nIHwgRWxlbWVudE1vZGVsPjtcbn1cblxuZXhwb3J0IGNvbnN0IGNyZWF0ZVdyYXBwZXJNb2RlbCA9IChkYXRhOiBEYXRhKTogRWxlbWVudE1vZGVsID0+IHtcbiAgY29uc3QgeyBfc3R5bGVzLCBpdGVtcywgLi4udmFsdWUgfSA9IGRhdGE7XG4gIHJldHVybiB7XG4gICAgdHlwZTogXCJXcmFwcGVyXCIsXG4gICAgdmFsdWU6IHsgX2lkOiB1dWlkKCksIF9zdHlsZXMsIGl0ZW1zLCAuLi52YWx1ZSB9XG4gIH07XG59O1xuIiwgImV4cG9ydCBjb25zdCBjbGVhbkNsYXNzTmFtZXMgPSAobm9kZTogRWxlbWVudCk6IHZvaWQgPT4ge1xuICBjb25zdCBjbGFzc0xpc3RFeGNlcHRzID0gW1wiYnJ6LVwiXTtcbiAgY29uc3QgZWxlbWVudHNXaXRoQ2xhc3NlcyA9IG5vZGUucXVlcnlTZWxlY3RvckFsbChcIltjbGFzc11cIik7XG4gIGVsZW1lbnRzV2l0aENsYXNzZXMuZm9yRWFjaChmdW5jdGlvbiAoZWxlbWVudCkge1xuICAgIGVsZW1lbnQuY2xhc3NMaXN0LmZvckVhY2goKGNscykgPT4ge1xuICAgICAgaWYgKCFjbGFzc0xpc3RFeGNlcHRzLnNvbWUoKGV4Y2VwdCkgPT4gY2xzLnN0YXJ0c1dpdGgoZXhjZXB0KSkpIHtcbiAgICAgICAgaWYgKGNscyA9PT0gXCJmaW5hbGRyYWZ0X3BsYWNlaG9sZGVyXCIpIHtcbiAgICAgICAgICBlbGVtZW50LmlubmVySFRNTCA9IFwiXCI7XG4gICAgICAgIH1cbiAgICAgICAgZWxlbWVudC5jbGFzc0xpc3QucmVtb3ZlKGNscyk7XG4gICAgICB9XG4gICAgfSk7XG5cbiAgICBpZiAoZWxlbWVudC5jbGFzc0xpc3QubGVuZ3RoID09PSAwKSB7XG4gICAgICBlbGVtZW50LnJlbW92ZUF0dHJpYnV0ZShcImNsYXNzXCIpO1xuICAgIH1cbiAgfSk7XG59O1xuIiwgImltcG9ydCB7IGFsbG93ZWRUYWdzIH0gZnJvbSBcIi4uL2NvbW1vblwiO1xuaW1wb3J0IHsgY2xlYW5DbGFzc05hbWVzIH0gZnJvbSBcIi4vY2xlYW5DbGFzc05hbWVzXCI7XG5cbmV4cG9ydCBmdW5jdGlvbiByZW1vdmVTdHlsZXNFeGNlcHRGb250V2VpZ2h0QW5kQ29sb3IoXG4gIGh0bWxTdHJpbmc6IHN0cmluZ1xuKTogc3RyaW5nIHtcbiAgLy8gQ3JlYXRlIGEgdGVtcG9yYXJ5IGVsZW1lbnRcbiAgY29uc3QgdGVtcEVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuXG4gIC8vIFNldCB0aGUgSFRNTCBjb250ZW50IG9mIHRoZSB0ZW1wb3JhcnkgZWxlbWVudFxuICB0ZW1wRWxlbWVudC5pbm5lckhUTUwgPSBodG1sU3RyaW5nO1xuXG4gIC8vIEZpbmQgZWxlbWVudHMgd2l0aCBpbmxpbmUgc3R5bGVzXG4gIGNvbnN0IGVsZW1lbnRzV2l0aFN0eWxlcyA9IHRlbXBFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCJbc3R5bGVdXCIpO1xuXG4gIC8vIEl0ZXJhdGUgdGhyb3VnaCBlbGVtZW50cyB3aXRoIHN0eWxlc1xuICBlbGVtZW50c1dpdGhTdHlsZXMuZm9yRWFjaChmdW5jdGlvbiAoZWxlbWVudCkge1xuICAgIC8vIEdldCB0aGUgaW5saW5lIHN0eWxlIGF0dHJpYnV0ZVxuICAgIGNvbnN0IHN0eWxlQXR0cmlidXRlID0gZWxlbWVudC5nZXRBdHRyaWJ1dGUoXCJzdHlsZVwiKSA/PyBcIlwiO1xuXG4gICAgLy8gU3BsaXQgdGhlIGlubGluZSBzdHlsZSBpbnRvIGluZGl2aWR1YWwgcHJvcGVydGllc1xuICAgIGNvbnN0IHN0eWxlUHJvcGVydGllcyA9IHN0eWxlQXR0cmlidXRlLnNwbGl0KFwiO1wiKTtcblxuICAgIC8vIEluaXRpYWxpemUgYSBuZXcgc3R5bGUgc3RyaW5nIHRvIHJldGFpbiBvbmx5IGZvbnQtd2VpZ2h0IGFuZCBjb2xvclxuICAgIGxldCBuZXdTdHlsZSA9IFwiXCI7XG5cbiAgICAvLyBJdGVyYXRlIHRocm91Z2ggdGhlIHN0eWxlIHByb3BlcnRpZXNcbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IHN0eWxlUHJvcGVydGllcy5sZW5ndGg7IGkrKykge1xuICAgICAgY29uc3QgcHJvcGVydHkgPSBzdHlsZVByb3BlcnRpZXNbaV0udHJpbSgpO1xuXG4gICAgICAvLyBDaGVjayBpZiB0aGUgcHJvcGVydHkgaXMgZm9udC13ZWlnaHQgb3IgY29sb3JcbiAgICAgIGlmIChwcm9wZXJ0eS5zdGFydHNXaXRoKFwiZm9udC13ZWlnaHRcIikgfHwgcHJvcGVydHkuc3RhcnRzV2l0aChcImNvbG9yXCIpKSB7XG4gICAgICAgIG5ld1N0eWxlICs9IHByb3BlcnR5ICsgXCI7IFwiO1xuICAgICAgfVxuICAgIH1cblxuICAgIC8vIFNldCB0aGUgZWxlbWVudCdzIHN0eWxlIGF0dHJpYnV0ZSB0byByZXRhaW4gb25seSBmb250LXdlaWdodCBhbmQgY29sb3JcbiAgICBlbGVtZW50LnNldEF0dHJpYnV0ZShcInN0eWxlXCIsIG5ld1N0eWxlKTtcbiAgfSk7XG5cbiAgY2xlYW5DbGFzc05hbWVzKHRlbXBFbGVtZW50KTtcbiAgLy8gUmV0dXJuIHRoZSBjbGVhbmVkIEhUTUxcbiAgcmV0dXJuIHRlbXBFbGVtZW50LmlubmVySFRNTDtcbn1cblxuZXhwb3J0IGZ1bmN0aW9uIHJlbW92ZUFsbFN0eWxlc0Zyb21IVE1MKG5vZGU6IEVsZW1lbnQpIHtcbiAgLy8gRGVmaW5lIHRoZSBsaXN0IG9mIGFsbG93ZWQgdGFnc1xuXG4gIC8vIEZpbmQgZWxlbWVudHMgd2l0aCBpbmxpbmUgc3R5bGVzIG9ubHkgZm9yIGFsbG93ZWQgdGFnc1xuICBjb25zdCBlbGVtZW50c1dpdGhTdHlsZXMgPSBub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoXG4gICAgYWxsb3dlZFRhZ3Muam9pbihcIixcIikgKyBcIltzdHlsZV1cIlxuICApO1xuXG4gIC8vIFJlbW92ZSB0aGUgXCJzdHlsZVwiIGF0dHJpYnV0ZSBmcm9tIGVhY2ggZWxlbWVudFxuICBlbGVtZW50c1dpdGhTdHlsZXMuZm9yRWFjaChmdW5jdGlvbiAoZWxlbWVudCkge1xuICAgIGVsZW1lbnQucmVtb3ZlQXR0cmlidXRlKFwic3R5bGVcIik7XG4gIH0pO1xuXG4gIC8vIFJlbW92ZSB0aGUgXCJzdHlsZVwiIGF0dHJpYnV0ZSBmcm9tIGVhY2ggZWxlbWVudFxuICBjbGVhbkNsYXNzTmFtZXMobm9kZSk7XG5cbiAgbm9kZS5pbm5lckhUTUwgPSByZW1vdmVTdHlsZXNFeGNlcHRGb250V2VpZ2h0QW5kQ29sb3Iobm9kZS5pbm5lckhUTUwpO1xuXG4gIC8vIFJldHVybiB0aGUgY2xlYW5lZCBIVE1MXG4gIHJldHVybiBub2RlO1xufVxuIiwgImV4cG9ydCBmdW5jdGlvbiByZW1vdmVFbXB0eU5vZGVzKG5vZGU6IEVsZW1lbnQpOiBFbGVtZW50IHtcbiAgY29uc3QgY2hpbGRyZW4gPSBBcnJheS5mcm9tKG5vZGUuY2hpbGRyZW4pO1xuXG4gIGNoaWxkcmVuLmZvckVhY2goKGNoaWxkKSA9PiB7XG4gICAgY29uc3QgaGF2ZVRleHQgPSBjaGlsZC50ZXh0Q29udGVudD8udHJpbSgpO1xuXG4gICAgaWYgKCFoYXZlVGV4dCkge1xuICAgICAgY2hpbGQucmVtb3ZlKCk7XG4gICAgfVxuICB9KTtcblxuICByZXR1cm4gbm9kZTtcbn1cbiIsICJleHBvcnQgZnVuY3Rpb24gdHJhbnNmb3JtRGl2c1RvUGFyYWdyYXBocyhjb250YWluZXJFbGVtZW50OiBFbGVtZW50KTogRWxlbWVudCB7XG4gIC8vIEdldCBhbGwgdGhlIGRpdiBlbGVtZW50cyB3aXRoaW4gdGhlIGNvbnRhaW5lclxuICBjb25zdCBkaXZFbGVtZW50cyA9IGNvbnRhaW5lckVsZW1lbnQucXVlcnlTZWxlY3RvckFsbChcImRpdlwiKTtcblxuICAvLyBJdGVyYXRlIHRocm91Z2ggZWFjaCBkaXYgZWxlbWVudFxuICBkaXZFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChkaXZFbGVtZW50KSB7XG4gICAgLy8gQ3JlYXRlIGEgbmV3IHBhcmFncmFwaCBlbGVtZW50XG4gICAgY29uc3QgcGFyYWdyYXBoRWxlbWVudCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJwXCIpO1xuXG4gICAgLy8gQ29weSBhbGwgYXR0cmlidXRlcyBmcm9tIHRoZSBkaXYgdG8gdGhlIHBhcmFncmFwaFxuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgZGl2RWxlbWVudC5hdHRyaWJ1dGVzLmxlbmd0aDsgaSsrKSB7XG4gICAgICBjb25zdCBhdHRyID0gZGl2RWxlbWVudC5hdHRyaWJ1dGVzW2ldO1xuICAgICAgcGFyYWdyYXBoRWxlbWVudC5zZXRBdHRyaWJ1dGUoYXR0ci5uYW1lLCBhdHRyLnZhbHVlKTtcbiAgICB9XG5cbiAgICAvLyBUcmFuc2ZlciB0aGUgY29udGVudCBmcm9tIHRoZSBkaXYgdG8gdGhlIHBhcmFncmFwaFxuICAgIHBhcmFncmFwaEVsZW1lbnQuaW5uZXJIVE1MID0gZGl2RWxlbWVudC5pbm5lckhUTUw7XG5cbiAgICAvLyBSZXBsYWNlIHRoZSBkaXYgd2l0aCB0aGUgbmV3IHBhcmFncmFwaCBlbGVtZW50XG4gICAgZGl2RWxlbWVudC5wYXJlbnROb2RlPy5yZXBsYWNlQ2hpbGQocGFyYWdyYXBoRWxlbWVudCwgZGl2RWxlbWVudCk7XG4gIH0pO1xuXG4gIHJldHVybiBjb250YWluZXJFbGVtZW50O1xufVxuIiwgImltcG9ydCB7IGV4dHJhY3RlZEF0dHJpYnV0ZXMgfSBmcm9tIFwiLi4vY29tbW9uXCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcblxuY29uc3QgYXR0cmlidXRlcyA9IGV4dHJhY3RlZEF0dHJpYnV0ZXM7XG5cbmV4cG9ydCBmdW5jdGlvbiBjb3B5Q29sb3JTdHlsZVRvVGV4dE5vZGVzKGVsZW1lbnQ6IEVsZW1lbnQpOiB2b2lkIHtcbiAgaWYgKGVsZW1lbnQubm9kZVR5cGUgPT09IE5vZGUuVEVYVF9OT0RFKSB7XG4gICAgY29uc3QgcGFyZW50RWxlbWVudCA9IGVsZW1lbnQucGFyZW50RWxlbWVudDtcblxuICAgIGlmICghcGFyZW50RWxlbWVudCkge1xuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGlmIChwYXJlbnRFbGVtZW50LnRhZ05hbWUgPT09IFwiU1BBTlwiKSB7XG4gICAgICBjb25zdCBwYXJlbnRPZlBhcmVudCA9IGVsZW1lbnQucGFyZW50RWxlbWVudC5wYXJlbnRFbGVtZW50O1xuICAgICAgY29uc3QgcGFyZW50RWxlbWVudCA9IGVsZW1lbnQucGFyZW50RWxlbWVudDtcbiAgICAgIGNvbnN0IHBhcmVudFN0eWxlID0gcGFyZW50RWxlbWVudC5zdHlsZTtcblxuICAgICAgaWYgKFxuICAgICAgICBhdHRyaWJ1dGVzLmluY2x1ZGVzKFwidGV4dC10cmFuc2Zvcm1cIikgJiZcbiAgICAgICAgIXBhcmVudFN0eWxlPy50ZXh0VHJhbnNmb3JtXG4gICAgICApIHtcbiAgICAgICAgY29uc3Qgc3R5bGUgPSBnZXROb2RlU3R5bGUocGFyZW50RWxlbWVudCk7XG4gICAgICAgIGlmIChzdHlsZVtcInRleHQtdHJhbnNmb3JtXCJdID09PSBcInVwcGVyY2FzZVwiKSB7XG4gICAgICAgICAgcGFyZW50RWxlbWVudC5jbGFzc0xpc3QuYWRkKFwiYnJ6LWNhcGl0YWxpemUtb25cIik7XG4gICAgICAgIH1cbiAgICAgIH1cblxuICAgICAgaWYgKCFwYXJlbnRPZlBhcmVudCkge1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG5cbiAgICAgIGlmICghcGFyZW50U3R5bGU/LmNvbG9yKSB7XG4gICAgICAgIGNvbnN0IHBhcmVudE9GUGFyZW50U3R5bGUgPSBnZXROb2RlU3R5bGUocGFyZW50T2ZQYXJlbnQpO1xuICAgICAgICBwYXJlbnRFbGVtZW50LnN0eWxlLmNvbG9yID0gYCR7cGFyZW50T0ZQYXJlbnRTdHlsZS5jb2xvcn1gO1xuICAgICAgfVxuICAgICAgaWYgKCFwYXJlbnRTdHlsZT8uZm9udFdlaWdodCAmJiBwYXJlbnRPZlBhcmVudC5zdHlsZT8uZm9udFdlaWdodCkge1xuICAgICAgICBwYXJlbnRFbGVtZW50LnN0eWxlLmZvbnRXZWlnaHQgPSBwYXJlbnRPZlBhcmVudC5zdHlsZS5mb250V2VpZ2h0O1xuICAgICAgfVxuXG4gICAgICBpZiAocGFyZW50T2ZQYXJlbnQudGFnTmFtZSA9PT0gXCJTUEFOXCIpIHtcbiAgICAgICAgY29uc3QgcGFyZW50Rm9udFdlaWdodCA9IHBhcmVudEVsZW1lbnQuc3R5bGUuZm9udFdlaWdodDtcbiAgICAgICAgcGFyZW50RWxlbWVudC5zdHlsZS5mb250V2VpZ2h0ID1cbiAgICAgICAgICBwYXJlbnRGb250V2VpZ2h0IHx8IGdldENvbXB1dGVkU3R5bGUocGFyZW50RWxlbWVudCkuZm9udFdlaWdodDtcbiAgICAgIH1cblxuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGNvbnN0IHNwYW5FbGVtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNwYW5cIik7XG4gICAgY29uc3QgY29tcHV0ZWRTdHlsZXMgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShwYXJlbnRFbGVtZW50KTtcblxuICAgIGlmIChcbiAgICAgIGF0dHJpYnV0ZXMuaW5jbHVkZXMoXCJ0ZXh0LXRyYW5zZm9ybVwiKSAmJlxuICAgICAgY29tcHV0ZWRTdHlsZXMudGV4dFRyYW5zZm9ybSA9PT0gXCJ1cHBlcmNhc2VcIlxuICAgICkge1xuICAgICAgc3BhbkVsZW1lbnQuY2xhc3NMaXN0LmFkZChcImJyei1jYXBpdGFsaXplLW9uXCIpO1xuICAgIH1cblxuICAgIGlmIChjb21wdXRlZFN0eWxlcy5jb2xvcikge1xuICAgICAgc3BhbkVsZW1lbnQuc3R5bGUuY29sb3IgPSBjb21wdXRlZFN0eWxlcy5jb2xvcjtcbiAgICB9XG5cbiAgICBpZiAoY29tcHV0ZWRTdHlsZXMuZm9udFdlaWdodCkge1xuICAgICAgc3BhbkVsZW1lbnQuc3R5bGUuZm9udFdlaWdodCA9IGNvbXB1dGVkU3R5bGVzLmZvbnRXZWlnaHQ7XG4gICAgfVxuXG4gICAgc3BhbkVsZW1lbnQudGV4dENvbnRlbnQgPSBlbGVtZW50LnRleHRDb250ZW50O1xuXG4gICAgaWYgKHBhcmVudEVsZW1lbnQudGFnTmFtZSA9PT0gXCJVXCIpIHtcbiAgICAgIGVsZW1lbnQucGFyZW50RWxlbWVudC5zdHlsZS5jb2xvciA9IGNvbXB1dGVkU3R5bGVzLmNvbG9yO1xuICAgIH1cblxuICAgIGlmIChlbGVtZW50KSB7XG4gICAgICBlbGVtZW50LnBhcmVudEVsZW1lbnQucmVwbGFjZUNoaWxkKHNwYW5FbGVtZW50LCBlbGVtZW50KTtcbiAgICB9XG4gIH0gZWxzZSBpZiAoZWxlbWVudC5ub2RlVHlwZSA9PT0gTm9kZS5FTEVNRU5UX05PREUpIHtcbiAgICAvLyBJZiB0aGUgY3VycmVudCBub2RlIGlzIGFuIGVsZW1lbnQgbm9kZSwgcmVjdXJzaXZlbHkgcHJvY2VzcyBpdHMgY2hpbGQgbm9kZXNcbiAgICBjb25zdCBjaGlsZE5vZGVzID0gZWxlbWVudC5jaGlsZE5vZGVzO1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgY2hpbGROb2Rlcy5sZW5ndGg7IGkrKykge1xuICAgICAgY29weUNvbG9yU3R5bGVUb1RleHROb2RlcyhjaGlsZE5vZGVzW2ldIGFzIEVsZW1lbnQpO1xuICAgIH1cbiAgfVxufVxuXG5leHBvcnQgZnVuY3Rpb24gY29weVBhcmVudENvbG9yVG9DaGlsZChub2RlOiBFbGVtZW50KSB7XG4gIG5vZGUuY2hpbGROb2Rlcy5mb3JFYWNoKChjaGlsZCkgPT4ge1xuICAgIGNvcHlDb2xvclN0eWxlVG9UZXh0Tm9kZXMoY2hpbGQgYXMgRWxlbWVudCk7XG4gIH0pO1xuXG4gIHJldHVybiBub2RlO1xufVxuIiwgImV4cG9ydCBjb25zdCByZWN1cnNpdmVHZXROb2RlcyA9IChub2RlOiBFbGVtZW50KTogQXJyYXk8RWxlbWVudD4gPT4ge1xuICBsZXQgbm9kZXM6IEFycmF5PEVsZW1lbnQ+ID0gW107XG4gIGlmIChub2RlLm5vZGVUeXBlID09PSBOb2RlLlRFWFRfTk9ERSkge1xuICAgIC8vIEZvdW5kIGEgdGV4dCBub2RlLCByZWNvcmQgaXRzIGZpcnN0IHBhcmVudCBlbGVtZW50XG4gICAgbm9kZS5wYXJlbnRFbGVtZW50ICYmIG5vZGVzLnB1c2gobm9kZS5wYXJlbnRFbGVtZW50KTtcbiAgfSBlbHNlIHtcbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IG5vZGUuY2hpbGROb2Rlcy5sZW5ndGg7IGkrKykge1xuICAgICAgY29uc3QgY2hpbGQgPSBub2RlLmNoaWxkTm9kZXNbaV07XG4gICAgICAvLyBSZWN1cnNpdmVseSBzZWFyY2ggY2hpbGQgbm9kZXMgYW5kIGFkZCB0aGVpciByZXN1bHRzIHRvIHRoZSByZXN1bHQgYXJyYXlcbiAgICAgIGlmIChjaGlsZCkge1xuICAgICAgICBub2RlcyA9IG5vZGVzLmNvbmNhdChyZWN1cnNpdmVHZXROb2RlcyhjaGlsZCBhcyBFbGVtZW50KSk7XG4gICAgICB9XG4gICAgfVxuICB9XG4gIHJldHVybiBub2Rlcztcbn07XG4iLCAiaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCIuLi90eXBlc1wiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcIi4vZ2V0Tm9kZVN0eWxlXCI7XG5pbXBvcnQgeyByZWN1cnNpdmVHZXROb2RlcyB9IGZyb20gXCIuL3JlY3Vyc2l2ZUdldE5vZGVzXCI7XG5cbmV4cG9ydCBmdW5jdGlvbiBleHRyYWN0QWxsRWxlbWVudHNTdHlsZXMoXG4gIG5vZGU6IEVsZW1lbnRcbik6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+IHtcbiAgY29uc3Qgbm9kZXMgPSByZWN1cnNpdmVHZXROb2Rlcyhub2RlKTtcbiAgcmV0dXJuIG5vZGVzLnJlZHVjZSgoYWNjLCBlbGVtZW50KSA9PiB7XG4gICAgY29uc3Qgc3R5bGVzID0gZ2V0Tm9kZVN0eWxlKGVsZW1lbnQpO1xuXG4gICAgLy8gVGV4dC1BbGlnbiBhcmUgd3JvbmcgZm9yIElubGluZSBFbGVtZW50c1xuICAgIGlmIChzdHlsZXNbXCJkaXNwbGF5XCJdID09PSBcImlubGluZVwiKSB7XG4gICAgICBkZWxldGUgc3R5bGVzW1widGV4dC1hbGlnblwiXTtcbiAgICB9XG5cbiAgICByZXR1cm4geyAuLi5hY2MsIC4uLnN0eWxlcyB9O1xuICB9LCB7fSk7XG59XG4iLCAiaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCJ1dGlsc1wiO1xuaW1wb3J0IHsgZXh0cmFjdEFsbEVsZW1lbnRzU3R5bGVzIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZXh0cmFjdEFsbEVsZW1lbnRzU3R5bGVzXCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIG1lcmdlU3R5bGVzKGVsZW1lbnQ6IEVsZW1lbnQpOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPiB7XG4gIGNvbnN0IGVsZW1lbnRTdHlsZXMgPSBnZXROb2RlU3R5bGUoZWxlbWVudCk7XG5cbiAgLy8gVGV4dC1BbGlnbiBhcmUgd3JvbmcgZm9yIElubGluZSBFbGVtZW50c1xuICBpZiAoZWxlbWVudFN0eWxlc1tcImRpc3BsYXlcIl0gPT09IFwiaW5saW5lXCIpIHtcbiAgICBkZWxldGUgZWxlbWVudFN0eWxlc1tcInRleHQtYWxpZ25cIl07XG4gIH1cblxuICBjb25zdCBpbm5lclN0eWxlcyA9IGV4dHJhY3RBbGxFbGVtZW50c1N0eWxlcyhlbGVtZW50KTtcblxuICByZXR1cm4ge1xuICAgIC4uLmVsZW1lbnRTdHlsZXMsXG4gICAgLi4uaW5uZXJTdHlsZXMsXG4gICAgXCJsaW5lLWhlaWdodFwiOiBlbGVtZW50U3R5bGVzW1wibGluZS1oZWlnaHRcIl1cbiAgfTtcbn1cbiIsICJpbXBvcnQgeyBleGNlcHRFeHRyYWN0aW5nU3R5bGUsIHNob3VsZEV4dHJhY3RFbGVtZW50IH0gZnJvbSBcIi4uL2NvbW1vblwiO1xuaW1wb3J0IHsgbWVyZ2VTdHlsZXMgfSBmcm9tIFwiLi4vc3R5bGVzL21lcmdlU3R5bGVzXCI7XG5pbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcInV0aWxzXCI7XG5cbmludGVyZmFjZSBPdXRwdXQge1xuICB1aWQ6IHN0cmluZztcbiAgdGFnTmFtZTogc3RyaW5nO1xuICBzdHlsZXM6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+O1xufVxuXG5leHBvcnQgZnVuY3Rpb24gZXh0cmFjdFBhcmVudEVsZW1lbnRzV2l0aFN0eWxlcyhub2RlOiBFbGVtZW50KTogQXJyYXk8T3V0cHV0PiB7XG4gIGxldCByZXN1bHQ6IEFycmF5PE91dHB1dD4gPSBbXTtcblxuICBpZiAoc2hvdWxkRXh0cmFjdEVsZW1lbnQobm9kZSwgZXhjZXB0RXh0cmFjdGluZ1N0eWxlKSkge1xuICAgIGNvbnN0IHVpZCA9IGB1aWQtJHtNYXRoLnJhbmRvbSgpfS0ke01hdGgucmFuZG9tKCl9YDtcbiAgICBub2RlLnNldEF0dHJpYnV0ZShcImRhdGEtdWlkXCIsIHVpZCk7XG5cbiAgICByZXN1bHQucHVzaCh7XG4gICAgICB1aWQsXG4gICAgICB0YWdOYW1lOiBub2RlLnRhZ05hbWUsXG4gICAgICBzdHlsZXM6IG1lcmdlU3R5bGVzKG5vZGUpXG4gICAgfSk7XG4gIH1cblxuICBmb3IgKGxldCBpID0gMDsgaSA8IG5vZGUuY2hpbGROb2Rlcy5sZW5ndGg7IGkrKykge1xuICAgIGNvbnN0IGNoaWxkID0gbm9kZS5jaGlsZE5vZGVzW2ldO1xuICAgIHJlc3VsdCA9IHJlc3VsdC5jb25jYXQoZXh0cmFjdFBhcmVudEVsZW1lbnRzV2l0aFN0eWxlcyhjaGlsZCBhcyBFbGVtZW50KSk7XG4gIH1cblxuICByZXR1cm4gcmVzdWx0O1xufVxuIiwgImltcG9ydCB7IGV4dHJhY3RlZEF0dHJpYnV0ZXMgfSBmcm9tIFwiLi4vY29tbW9uXCI7XG5pbXBvcnQgeyBleHRyYWN0UGFyZW50RWxlbWVudHNXaXRoU3R5bGVzIH0gZnJvbSBcIi4uL2RvbS9leHRyYWN0UGFyZW50RWxlbWVudHNXaXRoU3R5bGVzXCI7XG5pbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcInV0aWxzXCI7XG5cbmludGVyZmFjZSBPdXRwdXQge1xuICB1aWQ6IHN0cmluZztcbiAgdGFnTmFtZTogc3RyaW5nO1xuICBzdHlsZXM6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+O1xufVxuXG5leHBvcnQgY29uc3QgZ2V0VHlwb2dyYXBoeVN0eWxlcyA9IChub2RlOiBFbGVtZW50KTogQXJyYXk8T3V0cHV0PiA9PiB7XG4gIGNvbnN0IGFsbFJpY2hUZXh0RWxlbWVudHMgPSBleHRyYWN0UGFyZW50RWxlbWVudHNXaXRoU3R5bGVzKG5vZGUpO1xuICByZXR1cm4gYWxsUmljaFRleHRFbGVtZW50cy5tYXAoKGVsZW1lbnQpID0+IHtcbiAgICBjb25zdCB7IHN0eWxlcyB9ID0gZWxlbWVudDtcblxuICAgIHJldHVybiB7XG4gICAgICAuLi5lbGVtZW50LFxuICAgICAgc3R5bGVzOiBleHRyYWN0ZWRBdHRyaWJ1dGVzLnJlZHVjZSgoYWNjLCBhdHRyaWJ1dGUpID0+IHtcbiAgICAgICAgYWNjW2F0dHJpYnV0ZV0gPSBzdHlsZXNbYXR0cmlidXRlXTtcbiAgICAgICAgcmV0dXJuIGFjYztcbiAgICAgIH0sIHt9IGFzIFJlY29yZDxzdHJpbmcsIExpdGVyYWw+KVxuICAgIH07XG4gIH0pO1xufTtcbiIsICJleHBvcnQgZnVuY3Rpb24gZ2V0TGV0dGVyU3BhY2luZyh2YWx1ZTogc3RyaW5nKTogc3RyaW5nIHtcbiAgaWYgKHZhbHVlID09PSBcIm5vcm1hbFwiKSB7XG4gICAgcmV0dXJuIFwiMFwiO1xuICB9XG5cbiAgLy8gUmVtb3ZlICdweCcgYW5kIGFueSBleHRyYSB3aGl0ZXNwYWNlXG4gIGNvbnN0IGxldHRlclNwYWNpbmdWYWx1ZSA9IHZhbHVlLnJlcGxhY2UoL3B4L2csIFwiXCIpLnRyaW0oKTtcbiAgY29uc3QgW2ludGVnZXJQYXJ0LCBkZWNpbWFsUGFydCA9IFwiMFwiXSA9IGxldHRlclNwYWNpbmdWYWx1ZS5zcGxpdChcIi5cIik7XG4gIGNvbnN0IHRvTnVtYmVySSA9ICtpbnRlZ2VyUGFydDtcblxuICBpZiAodG9OdW1iZXJJIDwgMCB8fCBPYmplY3QuaXModG9OdW1iZXJJLCAtMCkpIHtcbiAgICByZXR1cm4gXCJtX1wiICsgLXRvTnVtYmVySSArIFwiX1wiICsgZGVjaW1hbFBhcnRbMF07XG4gIH1cbiAgcmV0dXJuIHRvTnVtYmVySSArIFwiX1wiICsgZGVjaW1hbFBhcnRbMF07XG59XG4iLCAiZXhwb3J0IGZ1bmN0aW9uIGdldExpbmVIZWlnaHQodmFsdWU6IHN0cmluZywgZm9udFNpemU6IHN0cmluZyk6IHN0cmluZyB7XG4gIGlmICh2YWx1ZSA9PT0gXCJub3JtYWxcIikge1xuICAgIHJldHVybiBcIjFfMlwiO1xuICB9XG5cbiAgY29uc3QgbGluZUhlaWdodFZhbHVlID0gdmFsdWUucmVwbGFjZShcInB4XCIsIFwiXCIpO1xuICBjb25zdCBsaW5lSGVpZ2h0ID0gTnVtYmVyKGxpbmVIZWlnaHRWYWx1ZSkgLyBOdW1iZXIoZm9udFNpemUpO1xuICBjb25zdCBbaW50ZWdlclBhcnQsIGRlY2ltYWxQYXJ0ID0gXCJcIl0gPSBsaW5lSGVpZ2h0LnRvU3RyaW5nKCkuc3BsaXQoXCIuXCIpO1xuXG4gIHJldHVybiBkZWNpbWFsUGFydCA/IGludGVnZXJQYXJ0ICsgXCJfXCIgKyBkZWNpbWFsUGFydFswXSA6IGludGVnZXJQYXJ0O1xufVxuIiwgImltcG9ydCB7IFJlYWRlciB9IGZyb20gXCIuL3R5cGVzXCI7XG5cbmV4cG9ydCBjb25zdCByZWFkOiBSZWFkZXI8bnVtYmVyPiA9ICh2KSA9PiB7XG4gIHN3aXRjaCAodHlwZW9mIHYpIHtcbiAgICBjYXNlIFwic3RyaW5nXCI6IHtcbiAgICAgIGNvbnN0IHZfID0gdiAhPT0gXCJcIiA/IE51bWJlcih2KSA6IE5hTjtcbiAgICAgIHJldHVybiBpc05hTih2XykgPyB1bmRlZmluZWQgOiB2XztcbiAgICB9XG4gICAgY2FzZSBcIm51bWJlclwiOlxuICAgICAgcmV0dXJuIGlzTmFOKHYpID8gdW5kZWZpbmVkIDogdjtcbiAgICBkZWZhdWx0OlxuICAgICAgcmV0dXJuIHVuZGVmaW5lZDtcbiAgfVxufTtcblxuZXhwb3J0IGNvbnN0IHJlYWRJbnQ6IFJlYWRlcjxudW1iZXI+ID0gKHYpID0+IHtcbiAgaWYgKHR5cGVvZiB2ID09PSBcInN0cmluZ1wiKSB7XG4gICAgcmV0dXJuIHBhcnNlSW50KHYpO1xuICB9XG5cbiAgcmV0dXJuIHJlYWQodik7XG59O1xuIiwgImltcG9ydCB7IHRleHRBbGlnbiB9IGZyb20gXCIuLi8uLi8uLi91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IGdldExldHRlclNwYWNpbmcgfSBmcm9tIFwiLi4vLi4vLi4vdXRpbHMvc3R5bGVzL2dldExldHRlclNwYWNpbmdcIjtcbmltcG9ydCB7IGdldExpbmVIZWlnaHQgfSBmcm9tIFwiLi4vLi4vLi4vdXRpbHMvc3R5bGVzL2dldExpbmVIZWlnaHRcIjtcbmltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwidXRpbHNcIjtcbmltcG9ydCAqIGFzIE51bSBmcm9tIFwidXRpbHMvc3JjL3JlYWRlci9udW1iZXJcIjtcblxuZXhwb3J0IGNvbnN0IHN0eWxlc1RvQ2xhc3NlcyA9IChcbiAgc3R5bGVzOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPixcbiAgZmFtaWxpZXM6IFJlY29yZDxzdHJpbmcsIHN0cmluZz4sXG4gIGRlZmF1bHRGYW1pbHk6IHN0cmluZ1xuKTogQXJyYXk8c3RyaW5nPiA9PiB7XG4gIGNvbnN0IGNsYXNzZXM6IEFycmF5PHN0cmluZz4gPSBbXTtcblxuICBPYmplY3QuZW50cmllcyhzdHlsZXMpLmZvckVhY2goKFtrZXksIHZhbHVlXSkgPT4ge1xuICAgIHN3aXRjaCAoa2V5KSB7XG4gICAgICBjYXNlIFwiZm9udC1zaXplXCI6IHtcbiAgICAgICAgY29uc3Qgc2l6ZSA9IE1hdGgucm91bmQoTnVtLnJlYWRJbnQodmFsdWUpID8/IDEpO1xuICAgICAgICBjbGFzc2VzLnB1c2goYGJyei1mcy1sZy0ke3NpemV9YCk7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImZvbnQtZmFtaWx5XCI6IHtcbiAgICAgICAgY29uc3QgZm9udEZhbWlseSA9IGAke3ZhbHVlfWBcbiAgICAgICAgICAucmVwbGFjZSgvWydcIlxcLF0vZywgXCJcIikgLy8gZXNsaW50LWRpc2FibGUtbGluZVxuICAgICAgICAgIC5yZXBsYWNlKC9cXHMvZywgXCJfXCIpXG4gICAgICAgICAgLnRvTG9jYWxlTG93ZXJDYXNlKCk7XG5cbiAgICAgICAgaWYgKCFmYW1pbGllc1tmb250RmFtaWx5XSkge1xuICAgICAgICAgIGNsYXNzZXMucHVzaChgYnJ6LWZmLSR7ZGVmYXVsdEZhbWlseX1gLCBcImJyei1mdC11cGxvYWRcIik7XG4gICAgICAgICAgYnJlYWs7XG4gICAgICAgIH1cbiAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotZmYtJHtmYW1pbGllc1tmb250RmFtaWx5XX1gLCBcImJyei1mdC11cGxvYWRcIik7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImZvbnQtd2VpZ2h0XCI6IHtcbiAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotZnctbGctJHt2YWx1ZX1gKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwidGV4dC1hbGlnblwiOiB7XG4gICAgICAgIGNsYXNzZXMucHVzaChgYnJ6LXRleHQtbGctJHt0ZXh0QWxpZ25bdmFsdWVdIHx8IFwibGVmdFwifWApO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJsZXR0ZXItc3BhY2luZ1wiOiB7XG4gICAgICAgIGNvbnN0IGxldHRlclNwYWNpbmcgPSBnZXRMZXR0ZXJTcGFjaW5nKGAke3ZhbHVlfWApO1xuICAgICAgICBjbGFzc2VzLnB1c2goYGJyei1scy1sZy0ke2xldHRlclNwYWNpbmd9YCk7XG4gICAgICAgIGJyZWFrO1xuICAgICAgfVxuICAgICAgY2FzZSBcImxpbmUtaGVpZ2h0XCI6IHtcbiAgICAgICAgY29uc3QgZnMgPSBgJHtzdHlsZXNbXCJmb250LXNpemVcIl19YDtcbiAgICAgICAgY29uc3QgZm9udFNpemUgPSBmcy5yZXBsYWNlKFwicHhcIiwgXCJcIik7XG4gICAgICAgIGNvbnN0IGxpbmVIZWlnaHQgPSBnZXRMaW5lSGVpZ2h0KGAke3ZhbHVlfWAsIGZvbnRTaXplKTtcbiAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotbGgtbGctJHtsaW5lSGVpZ2h0fWApO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGRlZmF1bHQ6XG4gICAgICAgIGJyZWFrO1xuICAgIH1cbiAgfSk7XG5cbiAgcmV0dXJuIGNsYXNzZXM7XG59O1xuIiwgImltcG9ydCB7IGNyZWF0ZVdyYXBwZXJNb2RlbCB9IGZyb20gXCIuLi8uLi8uLi9Nb2RlbHMvV3JhcHBlclwiO1xuaW1wb3J0IHsgRWxlbWVudE1vZGVsIH0gZnJvbSBcIi4uLy4uLy4uL3R5cGVzL3R5cGVcIjtcbmltcG9ydCB7IHJlbW92ZUFsbFN0eWxlc0Zyb21IVE1MIH0gZnJvbSBcIi4uLy4uL3V0aWxzL2RvbS9yZW1vdmVBbGxTdHlsZXNGcm9tSFRNTFwiO1xuaW1wb3J0IHsgcmVtb3ZlRW1wdHlOb2RlcyB9IGZyb20gXCIuLi8uLi91dGlscy9kb20vcmVtb3ZlRW1wdHlOb2Rlc1wiO1xuaW1wb3J0IHsgdHJhbnNmb3JtRGl2c1RvUGFyYWdyYXBocyB9IGZyb20gXCIuLi8uLi91dGlscy9kb20vdHJhbnNmb3JtRGl2c1RvUGFyYWdyYXBoc1wiO1xuaW1wb3J0IHsgY29weVBhcmVudENvbG9yVG9DaGlsZCB9IGZyb20gXCIuLi8uLi91dGlscy9zdHlsZXMvY29weVBhcmVudENvbG9yVG9DaGlsZFwiO1xuaW1wb3J0IHsgZ2V0VHlwb2dyYXBoeVN0eWxlcyB9IGZyb20gXCIuLi8uLi91dGlscy9zdHlsZXMvZ2V0VHlwb2dyYXBoeVN0eWxlc1wiO1xuaW1wb3J0IHsgc3R5bGVzVG9DbGFzc2VzIH0gZnJvbSBcIi4vdXRpbHMvc3R5bGVzVG9DbGFzc2VzXCI7XG5pbXBvcnQgeyB1dWlkIH0gZnJvbSBcInV0aWxzL3NyYy91dWlkXCI7XG5cbmludGVyZmFjZSBEYXRhIHtcbiAgbm9kZTogRWxlbWVudDtcbiAgZmFtaWxpZXM6IFJlY29yZDxzdHJpbmcsIHN0cmluZz47XG4gIGRlZmF1bHRGYW1pbHk6IHN0cmluZztcbn1cblxuZXhwb3J0IGNvbnN0IGdldFRleHRNb2RlbCA9IChkYXRhOiBEYXRhKTogRWxlbWVudE1vZGVsID0+IHtcbiAgY29uc3QgeyBub2RlOiBfbm9kZSwgZmFtaWxpZXMsIGRlZmF1bHRGYW1pbHkgfSA9IGRhdGE7XG4gIGxldCBub2RlID0gX25vZGU7XG5cbiAgLy8gVHJhbnNmb3JtIGFsbCBpbnNpZGUgZGl2IHRvIFBcbiAgbm9kZSA9IHRyYW5zZm9ybURpdnNUb1BhcmFncmFwaHMobm9kZSk7XG5cbiAgLy8gUmVtb3ZlIGFsbCBlbXB0eSBQIHdpdGggWyA8YnI+LCBcXG4gXVxuICBub2RlID0gcmVtb3ZlRW1wdHlOb2Rlcyhub2RlKTtcblxuICAvLyBDb3B5IFBhcmVudCBDb2xvciB0byBDaGlsZCwgZnJvbSA8cD4gdG8gPHNwYW4+XG4gIG5vZGUgPSBjb3B5UGFyZW50Q29sb3JUb0NoaWxkKG5vZGUpO1xuXG4gIC8vIEdldCBhbGwgb3VycyBzdHlsZSBmb3IgQnVpbGRlciBbZm9udC1mYW1pbHksIGZvbnQtc2l6ZSwgbGluZS1oZWlnaHQsIC5ldGNdXG4gIGNvbnN0IHN0eWxlcyA9IGdldFR5cG9ncmFwaHlTdHlsZXMobm9kZSk7XG5cbiAgLy8gUmVtb3ZlIGFsbCBpbmxpbmUgc3R5bGVzIGxpa2UgYmFja2dyb3VuZC1jb2xvciwgcG9zaXRpb25zLi4gZXRjLlxuICBub2RlID0gcmVtb3ZlQWxsU3R5bGVzRnJvbUhUTUwobm9kZSk7XG5cbiAgLy8gVHJhbnNmb3JtIGFsbCBzdHlsZXMgdG8gY2xhc3NOYW1lIGZvbnQtc2l6ZTogMjAgdG8gLmJyei1mcy0yMFxuICBzdHlsZXMubWFwKChzdHlsZSkgPT4ge1xuICAgIGNvbnN0IGNsYXNzZXMgPSBzdHlsZXNUb0NsYXNzZXMoc3R5bGUuc3R5bGVzLCBmYW1pbGllcywgZGVmYXVsdEZhbWlseSk7XG4gICAgY29uc3Qgc3R5bGVOb2RlID0gbm9kZS5xdWVyeVNlbGVjdG9yKGBbZGF0YS11aWQ9JyR7c3R5bGUudWlkfSddYCk7XG5cbiAgICBpZiAoc3R5bGVOb2RlKSB7XG4gICAgICBzdHlsZU5vZGUuY2xhc3NMaXN0LmFkZCguLi5jbGFzc2VzKTtcbiAgICAgIHN0eWxlTm9kZS5yZW1vdmVBdHRyaWJ1dGUoXCJkYXRhLXVpZFwiKTtcbiAgICB9XG4gIH0pO1xuXG4gIGNvbnN0IHRleHQgPSBub2RlLmlubmVySFRNTDtcblxuICByZXR1cm4gY3JlYXRlV3JhcHBlck1vZGVsKHtcbiAgICBfc3R5bGVzOiBbXCJ3cmFwcGVyXCIsIFwid3JhcHBlci0tcmljaFRleHRcIl0sXG4gICAgaXRlbXM6IFtcbiAgICAgIHtcbiAgICAgICAgdHlwZTogXCJSaWNoVGV4dFwiLFxuICAgICAgICB2YWx1ZToge1xuICAgICAgICAgIF9pZDogdXVpZCgpLFxuICAgICAgICAgIF9zdHlsZXM6IFtcInJpY2hUZXh0XCJdLFxuICAgICAgICAgIHRleHQ6IHRleHRcbiAgICAgICAgfVxuICAgICAgfVxuICAgIF1cbiAgfSk7XG59O1xuIiwgImltcG9ydCB7IGJ1dHRvblNlbGVjdG9yLCBlbWJlZFNlbGVjdG9yLCBpY29uU2VsZWN0b3IgfSBmcm9tIFwiLi4vY29tbW9uXCI7XG5cbmV4cG9ydCBjbGFzcyBTdGFjayB7XG4gIGNvbGxlY3Rpb246IEFycmF5PEVsZW1lbnQ+ID0gW107XG5cbiAgYXBwZW5kKG5vZGU6IEVsZW1lbnQgfCBOb2RlLCBhdHRyPzogUmVjb3JkPHN0cmluZywgc3RyaW5nPikge1xuICAgIGNvbnN0IGRpdiA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG4gICAgZGl2LmFwcGVuZChub2RlKTtcblxuICAgIGlmIChhdHRyKSB7XG4gICAgICBPYmplY3QuZW50cmllcyhhdHRyKS5mb3JFYWNoKChbbmFtZSwgdmFsdWVdKSA9PiB7XG4gICAgICAgIGRpdi5zZXRBdHRyaWJ1dGUoYGRhdGEtJHtuYW1lfWAsIHZhbHVlKTtcbiAgICAgIH0pO1xuICAgIH1cblxuICAgIHRoaXMuY29sbGVjdGlvbi5wdXNoKGRpdik7XG4gIH1cblxuICBzZXQobm9kZTogRWxlbWVudCwgYXR0cj86IFJlY29yZDxzdHJpbmcsIHN0cmluZz4pIHtcbiAgICBjb25zdCBjb2xMZW5ndGggPSB0aGlzLmNvbGxlY3Rpb24ubGVuZ3RoO1xuXG4gICAgaWYgKGNvbExlbmd0aCA9PT0gMCkge1xuICAgICAgdGhpcy5hcHBlbmQobm9kZSwgYXR0cik7XG4gICAgfSBlbHNlIHtcbiAgICAgIGNvbnN0IGxhc3RDb2xsZWN0aW9uID0gdGhpcy5jb2xsZWN0aW9uW2NvbExlbmd0aCAtIDFdO1xuICAgICAgbGFzdENvbGxlY3Rpb24uYXBwZW5kKG5vZGUpO1xuICAgIH1cbiAgfVxuXG4gIGdldEFsbCgpIHtcbiAgICByZXR1cm4gdGhpcy5jb2xsZWN0aW9uO1xuICB9XG59XG5cbmludGVyZmFjZSBDb250YWluZXIge1xuICBjb250YWluZXI6IEVsZW1lbnQ7XG4gIGRlc3Ryb3k6ICgpID0+IHZvaWQ7XG59XG5cbmV4cG9ydCBjb25zdCBnZXRDb250YWluZXJTdGFja1dpdGhOb2RlcyA9IChub2RlOiBFbGVtZW50KTogQ29udGFpbmVyID0+IHtcbiAgY29uc3QgY29udGFpbmVyID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgY29uc3Qgc3RhY2sgPSBuZXcgU3RhY2soKTtcbiAgbGV0IGFwcGVuZE5ld1RleHQgPSBmYWxzZTtcblxuICBub2RlLmNoaWxkTm9kZXMuZm9yRWFjaCgobm9kZSkgPT4ge1xuICAgIGNvbnN0IF9ub2RlID0gbm9kZS5jbG9uZU5vZGUodHJ1ZSk7XG4gICAgY29uc3QgY29udGFpbmVyT2ZOb2RlID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcImRpdlwiKTtcbiAgICBjb250YWluZXJPZk5vZGUuYXBwZW5kKF9ub2RlKTtcblxuICAgIGlmIChfbm9kZSBpbnN0YW5jZW9mIEhUTUxFbGVtZW50KSB7XG4gICAgICBpZiAoY29udGFpbmVyT2ZOb2RlLnF1ZXJ5U2VsZWN0b3IoaWNvblNlbGVjdG9yKSkge1xuICAgICAgICBhcHBlbmROZXdUZXh0ID0gdHJ1ZTtcbiAgICAgICAgc3RhY2suYXBwZW5kKF9ub2RlLCB7IHR5cGU6IFwiaWNvblwiIH0pO1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG4gICAgICBpZiAoY29udGFpbmVyT2ZOb2RlLnF1ZXJ5U2VsZWN0b3IoYnV0dG9uU2VsZWN0b3IpKSB7XG4gICAgICAgIGFwcGVuZE5ld1RleHQgPSB0cnVlO1xuICAgICAgICBzdGFjay5hcHBlbmQoX25vZGUsIHsgdHlwZTogXCJidXR0b25cIiB9KTtcbiAgICAgICAgcmV0dXJuO1xuICAgICAgfVxuICAgICAgaWYgKGNvbnRhaW5lck9mTm9kZS5xdWVyeVNlbGVjdG9yKGVtYmVkU2VsZWN0b3IpKSB7XG4gICAgICAgIGFwcGVuZE5ld1RleHQgPSB0cnVlO1xuICAgICAgICBzdGFjay5hcHBlbmQoX25vZGUsIHsgdHlwZTogXCJlbWJlZFwiIH0pO1xuICAgICAgICByZXR1cm47XG4gICAgICB9XG5cbiAgICAgIGlmIChhcHBlbmROZXdUZXh0KSB7XG4gICAgICAgIGFwcGVuZE5ld1RleHQgPSBmYWxzZTtcbiAgICAgICAgc3RhY2suYXBwZW5kKF9ub2RlLCB7IHR5cGU6IFwidGV4dFwiIH0pO1xuICAgICAgfSBlbHNlIHtcbiAgICAgICAgc3RhY2suc2V0KF9ub2RlLCB7IHR5cGU6IFwidGV4dFwiIH0pO1xuICAgICAgfVxuICAgIH0gZWxzZSB7XG4gICAgICBzdGFjay5hcHBlbmQoX25vZGUsIHsgdHlwZTogXCJ0ZXh0XCIgfSk7XG4gICAgfVxuICB9KTtcblxuICBjb25zdCBhbGxFbGVtZW50cyA9IHN0YWNrLmdldEFsbCgpO1xuXG4gIGFsbEVsZW1lbnRzLmZvckVhY2goKG5vZGUpID0+IHtcbiAgICBjb250YWluZXIuYXBwZW5kKG5vZGUpO1xuICB9KTtcblxuICBub2RlLnBhcmVudEVsZW1lbnQ/LmFwcGVuZChjb250YWluZXIpO1xuXG4gIGNvbnN0IGRlc3Ryb3kgPSAoKSA9PiB7XG4gICAgY29udGFpbmVyLnJlbW92ZSgpO1xuICB9O1xuXG4gIHJldHVybiB7IGNvbnRhaW5lciwgZGVzdHJveSB9O1xufTtcbiIsICJpbXBvcnQgeyBFbGVtZW50TW9kZWwsIEVtYmVkTW9kZWwsIEVudHJ5LCBPdXRwdXQgfSBmcm9tIFwiLi4vdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgY3JlYXRlRGF0YSB9IGZyb20gXCIuLi91dGlscy9nZXREYXRhXCI7XG5pbXBvcnQgeyBnZXRCdXR0b25Nb2RlbCB9IGZyb20gXCIuL21vZGVscy9CdXR0b25cIjtcbmltcG9ydCB7IGdldEVtYmVkTW9kZWwgfSBmcm9tIFwiLi9tb2RlbHMvRW1iZWRcIjtcbmltcG9ydCB7IGdldEljb25Nb2RlbCB9IGZyb20gXCIuL21vZGVscy9JY29uXCI7XG5pbXBvcnQgeyBnZXRUZXh0TW9kZWwgfSBmcm9tIFwiLi9tb2RlbHMvVGV4dFwiO1xuaW1wb3J0IHsgZ2V0Q29udGFpbmVyU3RhY2tXaXRoTm9kZXMgfSBmcm9tIFwiLi91dGlscy9kb20vZ2V0Q29udGFpbmVyU3RhY2tXaXRoTm9kZXNcIjtcblxudHlwZSBUZXh0TW9kZWwgPSBFbGVtZW50TW9kZWwgfCBFbWJlZE1vZGVsO1xuXG5leHBvcnQgY29uc3QgZ2V0VGV4dCA9IChlbnRyeTogRW50cnkpOiBPdXRwdXQgPT4ge1xuICBsZXQgbm9kZSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoZW50cnkuc2VsZWN0b3IpO1xuXG4gIGlmICghbm9kZSkge1xuICAgIHJldHVybiB7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke2VudHJ5LnNlbGVjdG9yfSBub3QgZm91bmRgXG4gICAgfTtcbiAgfVxuXG4gIG5vZGUgPSBub2RlLmNoaWxkcmVuWzBdO1xuXG4gIGlmICghbm9kZSkge1xuICAgIHJldHVybiB7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke2VudHJ5LnNlbGVjdG9yfSBoYXMgbm8gd3JhcHBlcmBcbiAgICB9O1xuICB9XG5cbiAgY29uc3QgZGF0YTogQXJyYXk8VGV4dE1vZGVsPiA9IFtdO1xuXG4gIGNvbnN0IHsgY29udGFpbmVyLCBkZXN0cm95IH0gPSBnZXRDb250YWluZXJTdGFja1dpdGhOb2Rlcyhub2RlKTtcbiAgY29uc3QgY29udGFpbmVyQ2hpbGRyZW4gPSBBcnJheS5mcm9tKGNvbnRhaW5lci5jaGlsZHJlbik7XG5cbiAgY29udGFpbmVyQ2hpbGRyZW4uZm9yRWFjaCgobm9kZSkgPT4ge1xuICAgIGlmIChub2RlIGluc3RhbmNlb2YgSFRNTEVsZW1lbnQpIHtcbiAgICAgIHN3aXRjaCAobm9kZS5kYXRhc2V0LnR5cGUpIHtcbiAgICAgICAgY2FzZSBcInRleHRcIjoge1xuICAgICAgICAgIGNvbnN0IG1vZGVsID0gZ2V0VGV4dE1vZGVsKHsgLi4uZW50cnksIG5vZGUgfSk7XG4gICAgICAgICAgZGF0YS5wdXNoKG1vZGVsKTtcbiAgICAgICAgICBicmVhaztcbiAgICAgICAgfVxuICAgICAgICBjYXNlIFwiYnV0dG9uXCI6IHtcbiAgICAgICAgICBjb25zdCBtb2RlbHMgPSBnZXRCdXR0b25Nb2RlbChub2RlKTtcbiAgICAgICAgICBkYXRhLnB1c2goLi4ubW9kZWxzKTtcbiAgICAgICAgICBicmVhaztcbiAgICAgICAgfVxuICAgICAgICBjYXNlIFwiZW1iZWRcIjoge1xuICAgICAgICAgIGNvbnN0IG1vZGVscyA9IGdldEVtYmVkTW9kZWwobm9kZSk7XG4gICAgICAgICAgZGF0YS5wdXNoKC4uLm1vZGVscyk7XG4gICAgICAgICAgYnJlYWs7XG4gICAgICAgIH1cbiAgICAgICAgY2FzZSBcImljb25cIjoge1xuICAgICAgICAgIGNvbnN0IG1vZGVscyA9IGdldEljb25Nb2RlbChub2RlKTtcbiAgICAgICAgICBkYXRhLnB1c2goLi4ubW9kZWxzKTtcbiAgICAgICAgICBicmVhaztcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH1cbiAgfSk7XG5cbiAgZGVzdHJveSgpO1xuXG4gIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YSB9KTtcbn07XG4iXSwKICAibWFwcGluZ3MiOiAiOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FBQUE7QUFBQTtBQUFBO0FBQ0EsYUFBTyxlQUFlLFNBQVMsY0FBYyxFQUFFLE9BQU8sS0FBSyxDQUFDO0FBQzVELGNBQVEsU0FBUztBQU1qQixlQUFTLE9BQU8sSUFBSSxJQUFJLElBQUk7QUFDeEIsZUFBTyxTQUFVLEdBQUcsR0FBRztB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O0FBQUEsRUFDOUI7OztBQ2hGTyxNQUFNQyxXQUFVLENBQUMsVUFBeUI7QUFDL0MsUUFBSSxPQUFPLFNBQVMsY0FBYyxNQUFNLFFBQVE7QUFFaEQsUUFBSSxDQUFDLE1BQU07QUFDVCxhQUFPO0FBQUEsUUFDTCxPQUFPLHlCQUF5QixNQUFNLFFBQVE7QUFBQSxNQUNoRDtBQUFBLElBQ0Y7QUFFQSxXQUFPLEtBQUssU0FBUyxDQUFDO0FBRXRCLFFBQUksQ0FBQyxNQUFNO0FBQ1QsYUFBTztBQUFBLFFBQ0wsT0FBTyx5QkFBeUIsTUFBTSxRQUFRO0FBQUEsTUFDaEQ7QUFBQSxJQUNGO0FBRUEsVUFBTUMsUUFBeUIsQ0FBQztBQUVoQyxVQUFNLEVBQUUsV0FBVyxRQUFRLElBQUksMkJBQTJCLElBQUk7QUFDOUQsVUFBTSxvQkFBb0IsTUFBTSxLQUFLLFVBQVUsUUFBUTtBQUV2RCxzQkFBa0IsUUFBUSxDQUFDQyxVQUFTO0FBQ2xDLFVBQUlBLGlCQUFnQixhQUFhO0FBQy9CLGdCQUFRQSxNQUFLLFFBQVEsTUFBTTtBQUFBLFVBQ3pCLEtBQUssUUFBUTtBQUNYLGtCQUFNLFFBQVEsYUFBYSxFQUFFLEdBQUcsT0FBTyxNQUFBQSxNQUFLLENBQUM7QUFDN0MsWUFBQUQsTUFBSyxLQUFLLEtBQUs7QUFDZjtBQUFBLFVBQ0Y7QUFBQSxVQUNBLEtBQUssVUFBVTtBQUNiLGtCQUFNLFNBQVMsZUFBZUMsS0FBSTtBQUNsQyxZQUFBRCxNQUFLLEtBQUssR0FBRyxNQUFNO0FBQ25CO0FBQUEsVUFDRjtBQUFBLFVBQ0EsS0FBSyxTQUFTO0FBQ1osa0JBQU0sU0FBUyxjQUFjQyxLQUFJO0FBQ2pDLFlBQUFELE1BQUssS0FBSyxHQUFHLE1BQU07QUFDbkI7QUFBQSxVQUNGO0FBQUEsVUFDQSxLQUFLLFFBQVE7QUFDWCxrQkFBTSxTQUFTLGFBQWFDLEtBQUk7QUFDaEMsWUFBQUQsTUFBSyxLQUFLLEdBQUcsTUFBTTtBQUNuQjtBQUFBLFVBQ0Y7QUFBQSxRQUNGO0FBQUEsTUFDRjtBQUFBLElBQ0YsQ0FBQztBQUVELFlBQVE7QUFFUixXQUFPLFdBQVcsRUFBRSxNQUFBQSxNQUFLLENBQUM7QUFBQSxFQUM1Qjs7O0FyQ3pEQSxNQUFNLE9BQU8sUUFBUTtBQUNyQixNQUFNLFNBQVNFLFNBQVEsSUFBSTtBQUUzQixNQUFPLGVBQVE7IiwKICAibmFtZXMiOiBbIm1QaXBlIiwgIl9hIiwgIl9pIiwgIl9pIiwgImV4cG9ydHMiLCAib3V0cHV0IiwgImFscGhhYmV0IiwgImRhdGEiLCAiaW1wb3J0X2ZwX3V0aWxpdGllcyIsICJpbXBvcnRfZnBfdXRpbGl0aWVzIiwgIm5vZGUiLCAiZ2V0Q29sb3IiLCAiZ2V0QmdDb2xvciIsICJnZXRNb2RlbCIsICJnZXRNb2RlbCIsICJkYXRhIiwgInBhcmVudEVsZW1lbnQiLCAicmVhZCIsICJkYXRhIiwgIm5vZGUiLCAiZ2V0VGV4dCIsICJkYXRhIiwgIm5vZGUiLCAiZ2V0VGV4dCJdCn0K
