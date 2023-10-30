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
      function mPipe2() {
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
      exports.mPipe = mPipe2;
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
  var iconSelector = `[data-socialicon],[style*="font-family: 'Mono Social Icons Font'"]`;
  var buttonSelector = ".sites-button";
  var embedSelector = ".embedded-paste";
  var getHref = (0, import_fp_utilities.mPipe)(readKey("href"), read);

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
        case "font-family":
          const fontFamily = `${value}`.replace(/['"\,]/g, "").replace(/\s/g, "_").toLocaleLowerCase();
          if (!families[fontFamily]) {
            classes.push(`brz-ff-${defaultFamily}`, "brz-ft-upload");
            break;
          }
          classes.push(`brz-ff-${families[fontFamily]}`, "brz-ft-upload");
          break;
        case "font-weight":
          classes.push(`brz-fw-lg-${value}`);
          break;
        case "text-align":
          classes.push(`brz-text-lg-${textAlign[value] || "left"}`);
          break;
        case "letter-spacing":
          const letterSpacing = getLetterSpacing(`${value}`);
          classes.push(`brz-ls-lg-${letterSpacing}`);
          break;
        case "line-height":
          const fs = `${styles["font-size"]}`;
          const fontSize = fs.replace("px", "");
          const lineHeight = getLineHeight(`${value}`, fontSize);
          classes.push(`brz-lh-lg-${lineHeight}`);
          break;
        default:
          break;
      }
    });
    return classes;
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

  // ../../../../../../../packages/elements/src/Models/Wrapper/index.ts
  var createWrapperModel = (data2) => {
    const { _styles, items, ...value } = data2;
    return {
      type: "Wrapper",
      value: { _id: uuid(), items, ...value }
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
    const elementsWithClasses = node.querySelectorAll(
      allowedTags.join(",") + "[class]"
    );
    elementsWithStyles.forEach(function(element) {
      element.removeAttribute("style");
    });
    cleanClassNames(node);
    node.innerHTML = removeStylesExceptFontWeightAndColor(node.innerHTML);
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

  // ../../../../../../../packages/utils/src/dom/getNodeStyle.ts
  var getNodeStyle = (node) => {
    const computedStyles = window.getComputedStyle(node);
    const styles = {};
    Object.values(computedStyles).forEach((key) => {
      styles[key] = computedStyles.getPropertyValue(key);
    });
    return styles;
  };

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
          parentElement2.style.color = parentOFParentStyle.color;
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
      let child = node.childNodes[i];
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

  // ../../../../../../../packages/elements/src/Text/models/Text/index.ts
  var getTextModel = (data2) => {
    let { node, families, defaultFamily } = data2;
    node = transformDivsToParagraphs(node);
    node = copyParentColorToChild(node);
    node = removeAllStylesFromHTML(node);
    const styles = getTypographyStyles(node);
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
  var getText = pipe(readKey("text"), read, onNullish("BUTTON"));
  var getModel = (node) => {
    const isLink = node.tagName === "A";
    const style = getNodeStyle(node);
    const color = parseColorString(style.color);
    const bgColor = parseColorString(style["background-color"]);
    const opacity = +style.opacity;
    return {
      type: "Button",
      value: {
        _id: uuid(),
        _styles: ["button"],
        bgColorHex: bgColor?.hex ?? "#ffffff",
        bgColorOpacity: isNaN(opacity) ? bgColor?.opacity ?? 1 : opacity,
        bgColorType: "solid",
        colorHex: color?.hex ?? "#ffffff",
        colorOpacity: color?.opacity ?? 1,
        text: getText(node),
        ...isLink && {
          linkExternal: getHref(node),
          linkType: "external",
          linkExternalBlank: "on"
        }
      }
    };
  };

  // ../../../../../../../packages/elements/src/Models/Cloneable/index.ts
  var createCloneableModel = (data2) => {
    const { _styles, items, ...value } = data2;
    return {
      type: "Cloneable",
      value: { _id: uuid(), items, ...value }
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
    facebook: "logo-facebook",
    instagram: "logo-instagram",
    youtube: "logo-youtube",
    twitter: "logo-twitter",
    vimeo: "logo-vimeo",
    mail: "email-85",
    apple: "apple",
    57380: "email-85",
    58624: "logo-instagram",
    58407: "logo-facebook",
    57895: "logo-facebook",
    57936: "note-03",
    58009: "logo-youtube"
  };
  function getModel2(node) {
    const parentNode = getParentElementOfTextNode(node);
    const isIconText = parentNode.nodeName === "#text";
    const iconNode = isIconText ? node : parentNode;
    const style = getNodeStyle(iconNode);
    const parentElement = node.parentElement;
    const isLink = parentElement?.tagName === "A" || node.tagName === "A";
    const parentBgColor = parentElement ? parseColorString(getNodeStyle(parentElement)["background-color"]) : void 0;
    const parentHref = getHref(parentElement) ?? getHref(node) ?? "";
    const opacity = +style.opacity;
    const color = parseColorString(style.color);
    const iconCode = iconNode.textContent.charCodeAt(0);
    return {
      type: "Icon",
      value: {
        _id: uuid(),
        _styles: ["icon"],
        colorHex: color?.hex ?? "#ffffff",
        colorOpacity: isNaN(opacity) ? color?.opacity ?? 1 : opacity,
        name: codeToBuilderMap[iconCode] ?? "favourite-31",
        ...isLink && {
          linkExternal: parentHref,
          linkType: "external",
          linkExternalBlank: "on",
          ...parentBgColor && {
            bgColorHex: parentBgColor.hex,
            bgColorOpacity: parentBgColor.opacity
          }
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
      const isIconText = parentNode.nodeName === "#text";
      const iconNode = isIconText ? node : parentNode;
      const style = getNodeStyle(iconNode);
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
  var extractElements = [iconSelector, buttonSelector, embedSelector];
  var getContainerStackWithNodes = (node) => {
    const artifacts = node.querySelectorAll(extractElements.join(","));
    const container = document.createElement("div");
    const stack = new Stack();
    if (artifacts.length > 0) {
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
    } else {
      container.innerHTML = node.innerHTML;
    }
    node.parentElement?.append(container);
    const destroy = () => {
      container.remove();
    };
    return { container, destroy };
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
