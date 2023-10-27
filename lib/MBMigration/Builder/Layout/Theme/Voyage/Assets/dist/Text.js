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

  // src/Text/index.ts
  var Text_exports = {};
  __export(Text_exports, {
    default: () => Text_default
  });

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
    return {
      type: "Wrapper",
      value: {
        _id: uuid(),
        _styles: data2._styles,
        items: data2.items
      }
    };
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
  var read = (v) => {
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
    return read(v);
  };

  // ../../../../../../../packages/elements/src/Text/models/Text/index.ts
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

  // ../../../../../../../packages/elements/src/Text/models/Button/index.ts
  function getButtonModel(style, node) {
    const isLink = node.tagName === "A";
    return {
      bgColorHex: rgbToHex(style["background-color"]) ?? "#ffffff",
      bgColorOpacity: +style.opacity,
      bgColorType: "solid",
      colorHex: rgbToHex(style.color) ?? "#ffffff",
      colorOpacity: 1,
      text: "text" in node ? node.text : void 0,
      ...isLink && {
        linkExternal: "href" in node ? node.href : "",
        linkType: "external",
        linkExternalBlank: "on"
      }
    };
  }

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

  // ../../../../../../../packages/utils/src/dom/getElementPositionAmongSiblings.ts
  function getElementPositionAmongSiblings(element, root) {
    const parent = findNearestBlockParent(element);
    if (!parent) {
      console.error("No block-level parent found.");
      return;
    }
    const siblings = Array.from(root.children);
    let totalSiblings = 0;
    let index = 0;
    if (root.contains(parent)) {
      totalSiblings = siblings.length;
      index = siblings.indexOf(parent);
    }
    if (index === -1) {
      console.error("Element is not a child of its parent.");
      return;
    }
    return index === 0 ? "top" : index === totalSiblings - 1 ? "bottom" : "middle";
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

  // ../../../../../../../packages/utils/src/dom/recursiveDeleteNodes.ts
  function recursiveDeleteNodes(node) {
    const parentElement = node.parentElement;
    node.remove();
    if (parentElement?.childNodes.length === 0) {
      recursiveDeleteNodes(parentElement);
    }
  }

  // ../../../../../../../packages/elements/src/Text/utils/buttons/index.ts
  function removeAllButtons(node) {
    const buttons = node.querySelectorAll(".sites-button");
    let buttonGroups = /* @__PURE__ */ new Map();
    buttons.forEach((button) => {
      const position = getElementPositionAmongSiblings(button, node);
      const parentElement = findNearestBlockParent(button);
      const style = getNodeStyle(button);
      const model = getButtonModel(style, button);
      const group = buttonGroups.get(parentElement) ?? { items: [] };
      buttonGroups.set(parentElement, {
        ...group,
        position,
        align: textAlign[style["text-align"]],
        items: [...group.items, model]
      });
    });
    buttons.forEach(recursiveDeleteNodes);
    return node;
  }

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
    var tempElement = document.createElement("div");
    tempElement.innerHTML = htmlString;
    var elementsWithStyles = tempElement.querySelectorAll("[style]");
    elementsWithStyles.forEach(function(element) {
      var styleAttribute = element.getAttribute("style") ?? "";
      var styleProperties = styleAttribute.split(";");
      var newStyle = "";
      for (var i = 0; i < styleProperties.length; i++) {
        var property = styleProperties[i].trim();
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

  // ../../../../../../../packages/elements/src/Text/utils/embeds/index.ts
  function removeAllEmbeds(node) {
    const nodes = node.querySelectorAll(".embedded-paste");
    const models = [];
    nodes.forEach((node2) => {
      models.push({
        type: "EmbedCode",
        value: {
          _id: uuid(),
          code: node2.outerHTML
        }
      });
      node2.remove();
    });
    return { node, models };
  }

  // ../../../../../../../packages/elements/src/Text/models/Icon/index.ts
  function getIconModel(style, node) {
    const parentElement = node.parentElement;
    const isLink = parentElement?.tagName === "A" || node.tagName === "A";
    const parentBgColor = parentElement ? rgbToHex(getNodeStyle(parentElement)["background-color"]) : void 0;
    const parentHref = parentElement && "href" in parentElement ? parentElement.href : "";
    return {
      colorHex: rgbToHex(style.color) ?? "#ffffff",
      colorOpacity: +style.opacity,
      ...isLink && {
        linkExternal: parentHref ?? ("href" in node ? node.href : ""),
        linkType: "external",
        linkExternalBlank: "on",
        ...parentBgColor && { bgColorHex: parentBgColor }
      }
    };
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

  // ../../../../../../../packages/elements/src/Text/utils/icons/index.ts
  function removeAllIcons(node) {
    const icons = node.querySelectorAll(
      `[data-socialicon],[style*="font-family: 'Mono Social Icons Font'"]`
    );
    let iconGroups = /* @__PURE__ */ new Map();
    icons.forEach((icon) => {
      const position = getElementPositionAmongSiblings(icon, node);
      const parentElement = findNearestBlockParent(icon);
      const parentNode = getParentElementOfTextNode(icon);
      const isIconText = parentNode.nodeName === "#text";
      const iconNode = isIconText ? icon : parentNode;
      const style = getNodeStyle(iconNode);
      const model = {
        ...getIconModel(style, icon),
        iconCode: iconNode.textContent.charCodeAt(0)
      };
      const group = iconGroups.get(parentElement) ?? { items: [] };
      iconGroups.set(parentElement, {
        ...group,
        position,
        align: textAlign[style["text-align"]],
        items: [...group.items, model]
      });
    });
    icons.forEach(recursiveDeleteNodes);
    return node;
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
  var toBuilderText = (data2) => {
    const { node, styles, families, defaultFamily } = data2;
    styles.map((style) => {
      const classes = stylesToClasses(style.styles, families, defaultFamily);
      const styleNode = node.querySelector(`[data-uid='${style.uid}']`);
      if (styleNode) {
        styleNode.classList.add(...classes);
        styleNode.removeAttribute("data-uid");
      }
    });
    return node.innerHTML;
  };
  var getText = (data2) => {
    let node = document.querySelector(data2.selector);
    if (!node) {
      return JSON.stringify({
        error: `Element with selector ${data2.selector} not found`
      });
    }
    node = node.children[0];
    if (!node) {
      return JSON.stringify({
        error: `Element with selector ${data2.selector} has no wrapper`
      });
    }
    const elements = [];
    node = removeAllIcons(node);
    node = removeAllButtons(node);
    const embeds = removeAllEmbeds(node);
    node = embeds.node;
    node = transformDivsToParagraphs(node);
    node = copyParentColorToChild(node);
    node = removeAllStylesFromHTML(node);
    const dataText = {
      node,
      families: data2.families,
      defaultFamily: data2.defaultFamily,
      styles: getTypographyStyles(node)
    };
    elements.push(
      createWrapperModel({
        _styles: ["wrapper", "wrapper--richText"],
        items: [
          {
            type: "RichText",
            value: {
              _id: uuid(),
              _styles: ["richText"],
              text: toBuilderText(dataText)
            }
          }
        ]
      })
    );
    return createData({ data: elements });
  };

  // src/Text/index.ts
  var data = getData();
  var output = getText(data);
  var Text_default = output;
  return __toCommonJS(Text_exports);
})();
