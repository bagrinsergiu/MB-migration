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

  // src/StyleExtractor/index.ts
  var StyleExtractor_exports = {};
  __export(StyleExtractor_exports, {
    run: () => styleExtractor
  });

  // ../../../../../../../packages/elements/src/utils/getData.ts
  var createData = (output) => {
    return output;
  };

  // ../../../../../../../packages/elements/src/utils/getDataByEntry.ts
  var getDataByEntry = (input) => {
    const { styleProperties, list, nav } = input ?? {};
    return window.isDev ? {
      selector: `[data-id="${window.elementId}"]`,
      families: {},
      defaultFamily: "lato",
      ...styleProperties ? { styleProperties: [""] } : {},
      ...list ? { list: void 0 } : {},
      ...nav ? { nav: void 0 } : {}
    } : input;
  };

  // ../../../../../../../packages/elements/src/StyleExtractor/index.ts
  var styleExtractor = (_entry) => {
    const entry = window.isDev ? getDataByEntry(_entry) : _entry;
    const { selector, styleProperties } = entry;
    const data = {};
    const element = document.querySelector(selector);
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
  return __toCommonJS(StyleExtractor_exports);
})();
