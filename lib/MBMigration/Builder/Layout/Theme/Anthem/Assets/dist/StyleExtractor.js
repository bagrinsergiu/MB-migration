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
    default: () => StyleExtractor_default
  });

  // ../../../../../../../packages/elements/src/utils/getData.ts
  var createData = (output2) => {
    return output2;
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

  // ../../../../../../../packages/elements/src/StyleExtractor/index.ts
  var styleExtractor = (entry) => {
    const { selector, styleProperties } = entry;
    const data2 = {};
    const element = document.querySelector(selector);
    if (!element) {
      return {
        error: `Element with selector ${selector} not found`
      };
    }
    const computedStyles = getNodeStyle(element);
    styleProperties.forEach((styleName) => {
      data2[styleName] = computedStyles[styleName];
    });
    return createData({ data: data2 });
  };

  // src/StyleExtractor/index.ts
  var getData = () => {
    try {
      return {
        selector: SELECTOR,
        families: FAMILIES,
        styleProperties: STYLE_PROPERTIES,
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
  var data = getData();
  var output = styleExtractor(data);
  var StyleExtractor_default = output;
  return __toCommonJS(StyleExtractor_exports);
})();