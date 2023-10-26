"use strict";
var scripts;
(scripts ||= {}).Text = (() => {
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
        selector: "{{selector}}",
        families: JSON.parse("{{families}}"),
        defaultFamily: "{{defaultFamily}}"
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
    return JSON.stringify(output2);
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
            type: "Text",
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
  window.isDev = true;
  var data = getData();
  var output = getText(data);
  var Text_default = output;
  return __toCommonJS(Text_exports);
})();
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vc3JjL1RleHQvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vbm9kZV9tb2R1bGVzL25hbm9pZC9pbmRleC5icm93c2VyLmpzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy91dWlkLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9Nb2RlbHMvV3JhcHBlci9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC91dGlscy9jb21tb24vaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2dldExldHRlclNwYWNpbmcudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2dldExpbmVIZWlnaHQudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL3JlYWRlci9udW1iZXIudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvbW9kZWxzL1RleHQvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2NvbG9yL3JnYmFUb0hleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvQnV0dG9uL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vZmluZE5lYXJlc3RCbG9ja1BhcmVudC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvZG9tL2dldEVsZW1lbnRQb3NpdGlvbkFtb25nU2libGluZ3MudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGUudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2RvbS9yZWN1cnNpdmVEZWxldGVOb2Rlcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC91dGlscy9idXR0b25zL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2RvbS9jbGVhbkNsYXNzTmFtZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvZG9tL3JlbW92ZUFsbFN0eWxlc0Zyb21IVE1MLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2RvbS90cmFuc2Zvcm1EaXZzVG9QYXJhZ3JhcGhzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2VtYmVkcy9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvSWNvbi9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvZG9tL2dldFBhcmVudEVsZW1lbnRPZlRleHROb2RlLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2ljb25zL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL3N0eWxlcy9jb3B5UGFyZW50Q29sb3JUb0NoaWxkLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vcmVjdXJzaXZlR2V0Tm9kZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2RvbS9leHRyYWN0QWxsRWxlbWVudHNTdHlsZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL21lcmdlU3R5bGVzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2RvbS9leHRyYWN0UGFyZW50RWxlbWVudHNXaXRoU3R5bGVzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL3N0eWxlcy9nZXRUeXBvZ3JhcGh5U3R5bGVzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy91dGlscy9nZXREYXRhLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L2luZGV4LnRzIl0sCiAgInNvdXJjZXNDb250ZW50IjogWyJpbXBvcnQgeyBnZXRUZXh0IH0gZnJvbSBcImVsZW1lbnRzL3NyYy9UZXh0XCI7XG5pbXBvcnQgeyBnZXREYXRhfSBmcm9tIFwiZWxlbWVudHMvc3JjL3V0aWxzL2dldERhdGFcIjtcblxuLy8gT25seSBGb3IgRGV2XG53aW5kb3cuaXNEZXYgPSB0cnVlXG5cbmNvbnN0IGRhdGEgPSBnZXREYXRhKCk7XG5jb25zdCBvdXRwdXQgPSBnZXRUZXh0KGRhdGEpO1xuXG5leHBvcnQgZGVmYXVsdCBvdXRwdXQ7XG4iLCAiZXhwb3J0IHsgdXJsQWxwaGFiZXQgfSBmcm9tICcuL3VybC1hbHBoYWJldC9pbmRleC5qcydcbmV4cG9ydCBsZXQgcmFuZG9tID0gYnl0ZXMgPT4gY3J5cHRvLmdldFJhbmRvbVZhbHVlcyhuZXcgVWludDhBcnJheShieXRlcykpXG5leHBvcnQgbGV0IGN1c3RvbVJhbmRvbSA9IChhbHBoYWJldCwgZGVmYXVsdFNpemUsIGdldFJhbmRvbSkgPT4ge1xuICBsZXQgbWFzayA9ICgyIDw8IChNYXRoLmxvZyhhbHBoYWJldC5sZW5ndGggLSAxKSAvIE1hdGguTE4yKSkgLSAxXG4gIGxldCBzdGVwID0gLX4oKDEuNiAqIG1hc2sgKiBkZWZhdWx0U2l6ZSkgLyBhbHBoYWJldC5sZW5ndGgpXG4gIHJldHVybiAoc2l6ZSA9IGRlZmF1bHRTaXplKSA9PiB7XG4gICAgbGV0IGlkID0gJydcbiAgICB3aGlsZSAodHJ1ZSkge1xuICAgICAgbGV0IGJ5dGVzID0gZ2V0UmFuZG9tKHN0ZXApXG4gICAgICBsZXQgaiA9IHN0ZXBcbiAgICAgIHdoaWxlIChqLS0pIHtcbiAgICAgICAgaWQgKz0gYWxwaGFiZXRbYnl0ZXNbal0gJiBtYXNrXSB8fCAnJ1xuICAgICAgICBpZiAoaWQubGVuZ3RoID09PSBzaXplKSByZXR1cm4gaWRcbiAgICAgIH1cbiAgICB9XG4gIH1cbn1cbmV4cG9ydCBsZXQgY3VzdG9tQWxwaGFiZXQgPSAoYWxwaGFiZXQsIHNpemUgPSAyMSkgPT5cbiAgY3VzdG9tUmFuZG9tKGFscGhhYmV0LCBzaXplLCByYW5kb20pXG5leHBvcnQgbGV0IG5hbm9pZCA9IChzaXplID0gMjEpID0+XG4gIGNyeXB0by5nZXRSYW5kb21WYWx1ZXMobmV3IFVpbnQ4QXJyYXkoc2l6ZSkpLnJlZHVjZSgoaWQsIGJ5dGUpID0+IHtcbiAgICBieXRlICY9IDYzXG4gICAgaWYgKGJ5dGUgPCAzNikge1xuICAgICAgaWQgKz0gYnl0ZS50b1N0cmluZygzNilcbiAgICB9IGVsc2UgaWYgKGJ5dGUgPCA2Mikge1xuICAgICAgaWQgKz0gKGJ5dGUgLSAyNikudG9TdHJpbmcoMzYpLnRvVXBwZXJDYXNlKClcbiAgICB9IGVsc2UgaWYgKGJ5dGUgPiA2Mikge1xuICAgICAgaWQgKz0gJy0nXG4gICAgfSBlbHNlIHtcbiAgICAgIGlkICs9ICdfJ1xuICAgIH1cbiAgICByZXR1cm4gaWRcbiAgfSwgJycpXG4iLCAiaW1wb3J0IHsgY3VzdG9tQWxwaGFiZXQgfSBmcm9tIFwibmFub2lkXCI7XG5cbmNvbnN0IGFscGhhYmV0ID0gXCJhYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5elwiO1xuY29uc3QgZnVsbFN5bWJvbExpc3QgPVxuICBcIjAxMjM0NTY3ODlhYmNkZWZnaGlqa2xtbm9wcXJzdHV2d3h5ekFCQ0RFRkdISUpLTE1OT1BRUlNUVVZXWFlaX1wiO1xuXG5leHBvcnQgY29uc3QgdXVpZCA9IChsZW5ndGggPSAxMik6IHN0cmluZyA9PlxuICBjdXN0b21BbHBoYWJldChhbHBoYWJldCwgMSkoKSArXG4gIGN1c3RvbUFscGhhYmV0KGZ1bGxTeW1ib2xMaXN0LCBsZW5ndGgpKGxlbmd0aCAtIDEpO1xuIiwgImltcG9ydCB7IEVsZW1lbnRNb2RlbCB9IGZyb20gXCJAL3R5cGVzL3R5cGVcIjtcbmltcG9ydCB7IHV1aWQgfSBmcm9tIFwidXRpbHMvc3JjL3V1aWRcIjtcblxuaW50ZXJmYWNlIERhdGEge1xuICBfc3R5bGVzOiBBcnJheTxzdHJpbmc+O1xuICBpdGVtczogQXJyYXk8RWxlbWVudE1vZGVsPjtcbn1cblxuZXhwb3J0IGNvbnN0IGNyZWF0ZVdyYXBwZXJNb2RlbCA9IChkYXRhOiBEYXRhKTogRWxlbWVudE1vZGVsID0+IHtcbiAgcmV0dXJuIHtcbiAgICB0eXBlOiBcIldyYXBwZXJcIixcbiAgICB2YWx1ZToge1xuICAgICAgX2lkOiB1dWlkKCksXG4gICAgICBfc3R5bGVzOiBkYXRhLl9zdHlsZXMsXG4gICAgICBpdGVtczogZGF0YS5pdGVtc1xuICAgIH1cbiAgfTtcbn07XG4iLCAiZXhwb3J0IGNvbnN0IGFsbG93ZWRUYWdzID0gW1xuICBcIlBcIixcbiAgXCJIMVwiLFxuICBcIkgyXCIsXG4gIFwiSDNcIixcbiAgXCJINFwiLFxuICBcIkg1XCIsXG4gIFwiSDZcIixcbiAgXCJVTFwiLFxuICBcIk9MXCIsXG4gIFwiTElcIlxuXTtcblxuZXhwb3J0IGNvbnN0IGV4Y2VwdEV4dHJhY3RpbmdTdHlsZSA9IFtcIlVMXCIsIFwiT0xcIl07XG5cbmV4cG9ydCBjb25zdCBleHRyYWN0ZWRBdHRyaWJ1dGVzID0gW1xuICBcImZvbnQtc2l6ZVwiLFxuICBcImZvbnQtZmFtaWx5XCIsXG4gIFwiZm9udC13ZWlnaHRcIixcbiAgXCJ0ZXh0LWFsaWduXCIsXG4gIFwibGV0dGVyLXNwYWNpbmdcIixcbiAgXCJ0ZXh0LXRyYW5zZm9ybVwiXG5dO1xuXG5leHBvcnQgY29uc3QgdGV4dEFsaWduOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+ID0ge1xuICBcIi13ZWJraXQtY2VudGVyXCI6IFwiY2VudGVyXCIsXG4gIFwiLW1vei1jZW50ZXJcIjogXCJjZW50ZXJcIixcbiAgc3RhcnQ6IFwibGVmdFwiLFxuICBlbmQ6IFwicmlnaHRcIixcbiAgbGVmdDogXCJsZWZ0XCIsXG4gIHJpZ2h0OiBcInJpZ2h0XCIsXG4gIGNlbnRlcjogXCJjZW50ZXJcIixcbiAganVzdGlmeTogXCJqdXN0aWZ5XCJcbn07XG5cbmV4cG9ydCBmdW5jdGlvbiBzaG91bGRFeHRyYWN0RWxlbWVudChcbiAgZWxlbWVudDogRWxlbWVudCxcbiAgZXhjZXB0aW9uczogQXJyYXk8c3RyaW5nPlxuKTogYm9vbGVhbiB7XG4gIGNvbnN0IGlzQWxsb3dlZCA9IGFsbG93ZWRUYWdzLmluY2x1ZGVzKGVsZW1lbnQudGFnTmFtZSk7XG5cbiAgaWYgKGlzQWxsb3dlZCAmJiBleGNlcHRpb25zKSB7XG4gICAgcmV0dXJuICFleGNlcHRpb25zLmluY2x1ZGVzKGVsZW1lbnQudGFnTmFtZSk7XG4gIH1cblxuICByZXR1cm4gaXNBbGxvd2VkO1xufVxuIiwgImV4cG9ydCBmdW5jdGlvbiBnZXRMZXR0ZXJTcGFjaW5nKHZhbHVlOiBzdHJpbmcpOiBzdHJpbmcge1xuICBpZiAodmFsdWUgPT09IFwibm9ybWFsXCIpIHtcbiAgICByZXR1cm4gXCIwXCI7XG4gIH1cblxuICAvLyBSZW1vdmUgJ3B4JyBhbmQgYW55IGV4dHJhIHdoaXRlc3BhY2VcbiAgY29uc3QgbGV0dGVyU3BhY2luZ1ZhbHVlID0gdmFsdWUucmVwbGFjZSgvcHgvZywgXCJcIikudHJpbSgpO1xuICBjb25zdCBbaW50ZWdlclBhcnQsIGRlY2ltYWxQYXJ0ID0gXCIwXCJdID0gbGV0dGVyU3BhY2luZ1ZhbHVlLnNwbGl0KFwiLlwiKTtcbiAgY29uc3QgdG9OdW1iZXJJID0gK2ludGVnZXJQYXJ0O1xuXG4gIGlmICh0b051bWJlckkgPCAwIHx8IE9iamVjdC5pcyh0b051bWJlckksIC0wKSkge1xuICAgIHJldHVybiBcIm1fXCIgKyAtdG9OdW1iZXJJICsgXCJfXCIgKyBkZWNpbWFsUGFydFswXTtcbiAgfVxuICByZXR1cm4gdG9OdW1iZXJJICsgXCJfXCIgKyBkZWNpbWFsUGFydFswXTtcbn1cbiIsICJleHBvcnQgZnVuY3Rpb24gZ2V0TGluZUhlaWdodCh2YWx1ZTogc3RyaW5nLCBmb250U2l6ZTogc3RyaW5nKTogc3RyaW5nIHtcbiAgaWYgKHZhbHVlID09PSBcIm5vcm1hbFwiKSB7XG4gICAgcmV0dXJuIFwiMV8yXCI7XG4gIH1cblxuICBjb25zdCBsaW5lSGVpZ2h0VmFsdWUgPSB2YWx1ZS5yZXBsYWNlKFwicHhcIiwgXCJcIik7XG4gIGNvbnN0IGxpbmVIZWlnaHQgPSBOdW1iZXIobGluZUhlaWdodFZhbHVlKSAvIE51bWJlcihmb250U2l6ZSk7XG4gIGNvbnN0IFtpbnRlZ2VyUGFydCwgZGVjaW1hbFBhcnQgPSBcIlwiXSA9IGxpbmVIZWlnaHQudG9TdHJpbmcoKS5zcGxpdChcIi5cIik7XG5cbiAgcmV0dXJuIGRlY2ltYWxQYXJ0ID8gaW50ZWdlclBhcnQgKyBcIl9cIiArIGRlY2ltYWxQYXJ0WzBdIDogaW50ZWdlclBhcnQ7XG59XG4iLCAiaW1wb3J0IHsgUmVhZGVyIH0gZnJvbSBcIi4vdHlwZXNcIjtcblxuZXhwb3J0IGNvbnN0IHJlYWQ6IFJlYWRlcjxudW1iZXI+ID0gKHYpID0+IHtcbiAgc3dpdGNoICh0eXBlb2Ygdikge1xuICAgIGNhc2UgXCJzdHJpbmdcIjoge1xuICAgICAgY29uc3Qgdl8gPSB2ICE9PSBcIlwiID8gTnVtYmVyKHYpIDogTmFOO1xuICAgICAgcmV0dXJuIGlzTmFOKHZfKSA/IHVuZGVmaW5lZCA6IHZfO1xuICAgIH1cbiAgICBjYXNlIFwibnVtYmVyXCI6XG4gICAgICByZXR1cm4gaXNOYU4odikgPyB1bmRlZmluZWQgOiB2O1xuICAgIGRlZmF1bHQ6XG4gICAgICByZXR1cm4gdW5kZWZpbmVkO1xuICB9XG59O1xuXG5leHBvcnQgY29uc3QgcmVhZEludDogUmVhZGVyPG51bWJlcj4gPSAodikgPT4ge1xuICBpZiAodHlwZW9mIHYgPT09IFwic3RyaW5nXCIpIHtcbiAgICByZXR1cm4gcGFyc2VJbnQodik7XG4gIH1cblxuICByZXR1cm4gcmVhZCh2KTtcbn07XG4iLCAiaW1wb3J0IHsgdGV4dEFsaWduIH0gZnJvbSBcIkAvVGV4dC91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IGdldExldHRlclNwYWNpbmcgfSBmcm9tIFwiQC9UZXh0L3V0aWxzL3N0eWxlcy9nZXRMZXR0ZXJTcGFjaW5nXCI7XG5pbXBvcnQgeyBnZXRMaW5lSGVpZ2h0IH0gZnJvbSBcIkAvVGV4dC91dGlscy9zdHlsZXMvZ2V0TGluZUhlaWdodFwiO1xuaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCJ1dGlsc1wiO1xuaW1wb3J0ICogYXMgTnVtIGZyb20gXCJ1dGlscy9zcmMvcmVhZGVyL251bWJlclwiO1xuXG5leHBvcnQgY29uc3Qgc3R5bGVzVG9DbGFzc2VzID0gKFxuICBzdHlsZXM6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+LFxuICBmYW1pbGllczogUmVjb3JkPHN0cmluZywgc3RyaW5nPixcbiAgZGVmYXVsdEZhbWlseTogc3RyaW5nXG4pOiBBcnJheTxzdHJpbmc+ID0+IHtcbiAgY29uc3QgY2xhc3NlczogQXJyYXk8c3RyaW5nPiA9IFtdO1xuXG4gIE9iamVjdC5lbnRyaWVzKHN0eWxlcykuZm9yRWFjaCgoW2tleSwgdmFsdWVdKSA9PiB7XG4gICAgc3dpdGNoIChrZXkpIHtcbiAgICAgIGNhc2UgXCJmb250LXNpemVcIjoge1xuICAgICAgICBjb25zdCBzaXplID0gTWF0aC5yb3VuZChOdW0ucmVhZEludCh2YWx1ZSkgPz8gMSk7XG4gICAgICAgIGNsYXNzZXMucHVzaChgYnJ6LWZzLWxnLSR7c2l6ZX1gKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICB9XG4gICAgICBjYXNlIFwiZm9udC1mYW1pbHlcIjpcbiAgICAgICAgY29uc3QgZm9udEZhbWlseSA9IGAke3ZhbHVlfWBcbiAgICAgICAgICAucmVwbGFjZSgvWydcIlxcLF0vZywgXCJcIilcbiAgICAgICAgICAucmVwbGFjZSgvXFxzL2csIFwiX1wiKVxuICAgICAgICAgIC50b0xvY2FsZUxvd2VyQ2FzZSgpO1xuXG4gICAgICAgIGlmICghZmFtaWxpZXNbZm9udEZhbWlseV0pIHtcbiAgICAgICAgICBjbGFzc2VzLnB1c2goYGJyei1mZi0ke2RlZmF1bHRGYW1pbHl9YCwgXCJicnotZnQtdXBsb2FkXCIpO1xuICAgICAgICAgIGJyZWFrO1xuICAgICAgICB9XG4gICAgICAgIGNsYXNzZXMucHVzaChgYnJ6LWZmLSR7ZmFtaWxpZXNbZm9udEZhbWlseV19YCwgXCJicnotZnQtdXBsb2FkXCIpO1xuICAgICAgICBicmVhaztcbiAgICAgIGNhc2UgXCJmb250LXdlaWdodFwiOlxuICAgICAgICBjbGFzc2VzLnB1c2goYGJyei1mdy1sZy0ke3ZhbHVlfWApO1xuICAgICAgICBicmVhaztcbiAgICAgIGNhc2UgXCJ0ZXh0LWFsaWduXCI6XG4gICAgICAgIGNsYXNzZXMucHVzaChgYnJ6LXRleHQtbGctJHt0ZXh0QWxpZ25bdmFsdWVdIHx8IFwibGVmdFwifWApO1xuICAgICAgICBicmVhaztcbiAgICAgIGNhc2UgXCJsZXR0ZXItc3BhY2luZ1wiOlxuICAgICAgICBjb25zdCBsZXR0ZXJTcGFjaW5nID0gZ2V0TGV0dGVyU3BhY2luZyhgJHt2YWx1ZX1gKTtcbiAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotbHMtbGctJHtsZXR0ZXJTcGFjaW5nfWApO1xuICAgICAgICBicmVhaztcbiAgICAgIGNhc2UgXCJsaW5lLWhlaWdodFwiOlxuICAgICAgICBjb25zdCBmcyA9IGAke3N0eWxlc1tcImZvbnQtc2l6ZVwiXX1gO1xuICAgICAgICBjb25zdCBmb250U2l6ZSA9IGZzLnJlcGxhY2UoXCJweFwiLCBcIlwiKTtcbiAgICAgICAgY29uc3QgbGluZUhlaWdodCA9IGdldExpbmVIZWlnaHQoYCR7dmFsdWV9YCwgZm9udFNpemUpO1xuICAgICAgICBjbGFzc2VzLnB1c2goYGJyei1saC1sZy0ke2xpbmVIZWlnaHR9YCk7XG4gICAgICAgIGJyZWFrO1xuXG4gICAgICBkZWZhdWx0OlxuICAgICAgICBicmVhaztcbiAgICB9XG4gIH0pO1xuXG4gIHJldHVybiBjbGFzc2VzO1xufTtcbiIsICJpbXBvcnQgeyBNVmFsdWUgfSBmcm9tIFwiQC90eXBlc1wiO1xuXG5mdW5jdGlvbiBfcmdiVG9IZXgocjogbnVtYmVyLCBnOiBudW1iZXIsIGI6IG51bWJlcik6IHN0cmluZyB7XG4gIHIgPSBNYXRoLm1pbigyNTUsIE1hdGgubWF4KDAsIE1hdGgucm91bmQocikpKTtcbiAgZyA9IE1hdGgubWluKDI1NSwgTWF0aC5tYXgoMCwgTWF0aC5yb3VuZChnKSkpO1xuICBiID0gTWF0aC5taW4oMjU1LCBNYXRoLm1heCgwLCBNYXRoLnJvdW5kKGIpKSk7XG5cbiAgY29uc3QgaGV4UiA9IHIudG9TdHJpbmcoMTYpLnBhZFN0YXJ0KDIsIFwiMFwiKTtcbiAgY29uc3QgaGV4RyA9IGcudG9TdHJpbmcoMTYpLnBhZFN0YXJ0KDIsIFwiMFwiKTtcbiAgY29uc3QgaGV4QiA9IGIudG9TdHJpbmcoMTYpLnBhZFN0YXJ0KDIsIFwiMFwiKTtcblxuICByZXR1cm4gYCMke2hleFJ9JHtoZXhHfSR7aGV4Qn1gLnRvVXBwZXJDYXNlKCk7XG59XG5cbmV4cG9ydCBjb25zdCByZ2JUb0hleCA9IChyZ2JhOiBzdHJpbmcpOiBNVmFsdWU8c3RyaW5nPiA9PiB7XG4gIGNvbnN0IHJnYlZhbHVlcyA9IHJnYmFcbiAgICAuc2xpY2UoNCwgLTEpXG4gICAgLnNwbGl0KFwiLFwiKVxuICAgIC5tYXAoKHZhbHVlKSA9PiBwYXJzZUludCh2YWx1ZS50cmltKCkpKTtcblxuICBpZiAocmdiVmFsdWVzLmxlbmd0aCAhPT0gMykge1xuICAgIHJldHVybiB1bmRlZmluZWQ7XG4gIH1cblxuICByZXR1cm4gX3JnYlRvSGV4KHJnYlZhbHVlc1swXSwgcmdiVmFsdWVzWzFdLCByZ2JWYWx1ZXNbMl0pO1xufTtcbiIsICJpbXBvcnQgeyByZ2JUb0hleCB9IGZyb20gXCJ1dGlscy9zcmMvY29sb3IvcmdiYVRvSGV4XCI7XG5cbmV4cG9ydCBmdW5jdGlvbiBnZXRCdXR0b25Nb2RlbChzdHlsZTogUmVjb3JkPHN0cmluZywgc3RyaW5nPiwgbm9kZTogRWxlbWVudCkge1xuICBjb25zdCBpc0xpbmsgPSBub2RlLnRhZ05hbWUgPT09IFwiQVwiO1xuXG4gIHJldHVybiB7XG4gICAgYmdDb2xvckhleDogcmdiVG9IZXgoc3R5bGVbXCJiYWNrZ3JvdW5kLWNvbG9yXCJdKSA/PyBcIiNmZmZmZmZcIixcbiAgICBiZ0NvbG9yT3BhY2l0eTogK3N0eWxlLm9wYWNpdHksXG4gICAgYmdDb2xvclR5cGU6IFwic29saWRcIixcbiAgICBjb2xvckhleDogcmdiVG9IZXgoc3R5bGUuY29sb3IpID8/IFwiI2ZmZmZmZlwiLFxuICAgIGNvbG9yT3BhY2l0eTogMSxcbiAgICB0ZXh0OiBcInRleHRcIiBpbiBub2RlID8gbm9kZS50ZXh0IDogdW5kZWZpbmVkLFxuICAgIC4uLihpc0xpbmsgJiYge1xuICAgICAgbGlua0V4dGVybmFsOiBcImhyZWZcIiBpbiBub2RlID8gbm9kZS5ocmVmIDogXCJcIixcbiAgICAgIGxpbmtUeXBlOiBcImV4dGVybmFsXCIsXG4gICAgICBsaW5rRXh0ZXJuYWxCbGFuazogXCJvblwiXG4gICAgfSlcbiAgfTtcbn1cbiIsICJpbXBvcnQgeyBNVmFsdWUgfSBmcm9tIFwiQC90eXBlc1wiO1xuXG5leHBvcnQgZnVuY3Rpb24gZmluZE5lYXJlc3RCbG9ja1BhcmVudChlbGVtZW50OiBFbGVtZW50KTogTVZhbHVlPEVsZW1lbnQ+IHtcbiAgaWYgKCFlbGVtZW50LnBhcmVudEVsZW1lbnQpIHtcbiAgICByZXR1cm4gdW5kZWZpbmVkO1xuICB9XG5cbiAgY29uc3QgZGlzcGxheVN0eWxlID0gd2luZG93LmdldENvbXB1dGVkU3R5bGUoZWxlbWVudC5wYXJlbnRFbGVtZW50KS5kaXNwbGF5O1xuICBjb25zdCBpc0Jsb2NrRWxlbWVudCA9XG4gICAgZGlzcGxheVN0eWxlID09PSBcImJsb2NrXCIgfHxcbiAgICBkaXNwbGF5U3R5bGUgPT09IFwiZmxleFwiIHx8XG4gICAgZGlzcGxheVN0eWxlID09PSBcImdyaWRcIjtcblxuICBpZiAoaXNCbG9ja0VsZW1lbnQpIHtcbiAgICByZXR1cm4gZWxlbWVudC5wYXJlbnRFbGVtZW50O1xuICB9IGVsc2Uge1xuICAgIHJldHVybiBmaW5kTmVhcmVzdEJsb2NrUGFyZW50KGVsZW1lbnQucGFyZW50RWxlbWVudCk7XG4gIH1cbn1cbiIsICJpbXBvcnQgeyBmaW5kTmVhcmVzdEJsb2NrUGFyZW50IH0gZnJvbSBcIi4vZmluZE5lYXJlc3RCbG9ja1BhcmVudFwiO1xuaW1wb3J0IHsgTVZhbHVlIH0gZnJvbSBcIkAvdHlwZXNcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIGdldEVsZW1lbnRQb3NpdGlvbkFtb25nU2libGluZ3MoXG4gIGVsZW1lbnQ6IEVsZW1lbnQsXG4gIHJvb3Q6IEVsZW1lbnRcbik6IE1WYWx1ZTxzdHJpbmc+IHtcbiAgY29uc3QgcGFyZW50ID0gZmluZE5lYXJlc3RCbG9ja1BhcmVudChlbGVtZW50KTtcblxuICBpZiAoIXBhcmVudCkge1xuICAgIGNvbnNvbGUuZXJyb3IoXCJObyBibG9jay1sZXZlbCBwYXJlbnQgZm91bmQuXCIpO1xuICAgIHJldHVybjtcbiAgfVxuXG4gIGNvbnN0IHNpYmxpbmdzID0gQXJyYXkuZnJvbShyb290LmNoaWxkcmVuKTtcblxuICBsZXQgdG90YWxTaWJsaW5ncyA9IDA7XG4gIGxldCBpbmRleCA9IDA7XG5cbiAgaWYgKHJvb3QuY29udGFpbnMocGFyZW50KSkge1xuICAgIHRvdGFsU2libGluZ3MgPSBzaWJsaW5ncy5sZW5ndGg7XG4gICAgaW5kZXggPSBzaWJsaW5ncy5pbmRleE9mKHBhcmVudCk7XG4gIH1cblxuICBpZiAoaW5kZXggPT09IC0xKSB7XG4gICAgY29uc29sZS5lcnJvcihcIkVsZW1lbnQgaXMgbm90IGEgY2hpbGQgb2YgaXRzIHBhcmVudC5cIik7XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgcmV0dXJuIGluZGV4ID09PSAwXG4gICAgPyBcInRvcFwiXG4gICAgOiBpbmRleCA9PT0gdG90YWxTaWJsaW5ncyAtIDFcbiAgICA/IFwiYm90dG9tXCJcbiAgICA6IFwibWlkZGxlXCI7XG59XG4iLCAiaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCJAL3R5cGVzXCI7XG5cbmV4cG9ydCBjb25zdCBnZXROb2RlU3R5bGUgPSAoXG4gIG5vZGU6IEhUTUxFbGVtZW50IHwgRWxlbWVudFxuKTogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4gPT4ge1xuICBjb25zdCBjb21wdXRlZFN0eWxlcyA9IHdpbmRvdy5nZXRDb21wdXRlZFN0eWxlKG5vZGUpO1xuICBjb25zdCBzdHlsZXM6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+ID0ge307XG5cbiAgT2JqZWN0LnZhbHVlcyhjb21wdXRlZFN0eWxlcykuZm9yRWFjaCgoa2V5KSA9PiB7XG4gICAgc3R5bGVzW2tleV0gPSBjb21wdXRlZFN0eWxlcy5nZXRQcm9wZXJ0eVZhbHVlKGtleSk7XG4gIH0pO1xuXG4gIHJldHVybiBzdHlsZXM7XG59O1xuIiwgImV4cG9ydCBmdW5jdGlvbiByZWN1cnNpdmVEZWxldGVOb2Rlcyhub2RlOiBFbGVtZW50KSB7XG4gIGNvbnN0IHBhcmVudEVsZW1lbnQgPSBub2RlLnBhcmVudEVsZW1lbnQ7XG4gIG5vZGUucmVtb3ZlKCk7XG5cbiAgaWYgKHBhcmVudEVsZW1lbnQ/LmNoaWxkTm9kZXMubGVuZ3RoID09PSAwKSB7XG4gICAgcmVjdXJzaXZlRGVsZXRlTm9kZXMocGFyZW50RWxlbWVudCk7XG4gIH1cbn1cbiIsICJpbXBvcnQgeyBnZXRCdXR0b25Nb2RlbCB9IGZyb20gXCJAL1RleHQvbW9kZWxzL0J1dHRvblwiO1xuaW1wb3J0IHsgdGV4dEFsaWduIH0gZnJvbSBcIkAvVGV4dC91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IGZpbmROZWFyZXN0QmxvY2tQYXJlbnQgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9maW5kTmVhcmVzdEJsb2NrUGFyZW50XCI7XG5pbXBvcnQgeyBnZXRFbGVtZW50UG9zaXRpb25BbW9uZ1NpYmxpbmdzIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0RWxlbWVudFBvc2l0aW9uQW1vbmdTaWJsaW5nc1wiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5pbXBvcnQgeyByZWN1cnNpdmVEZWxldGVOb2RlcyB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL3JlY3Vyc2l2ZURlbGV0ZU5vZGVzXCI7XG5cbmV4cG9ydCBmdW5jdGlvbiByZW1vdmVBbGxCdXR0b25zKG5vZGU6IEVsZW1lbnQpOiBFbGVtZW50IHtcbiAgY29uc3QgYnV0dG9ucyA9IG5vZGUucXVlcnlTZWxlY3RvckFsbChcIi5zaXRlcy1idXR0b25cIik7XG4gIGxldCBidXR0b25Hcm91cHMgPSBuZXcgTWFwKCk7XG5cbiAgYnV0dG9ucy5mb3JFYWNoKChidXR0b24pID0+IHtcbiAgICBjb25zdCBwb3NpdGlvbiA9IGdldEVsZW1lbnRQb3NpdGlvbkFtb25nU2libGluZ3MoYnV0dG9uLCBub2RlKTtcbiAgICBjb25zdCBwYXJlbnRFbGVtZW50ID0gZmluZE5lYXJlc3RCbG9ja1BhcmVudChidXR0b24pO1xuICAgIGNvbnN0IHN0eWxlID0gZ2V0Tm9kZVN0eWxlKGJ1dHRvbik7XG4gICAgY29uc3QgbW9kZWwgPSBnZXRCdXR0b25Nb2RlbChzdHlsZSwgYnV0dG9uKTtcblxuICAgIGNvbnN0IGdyb3VwID0gYnV0dG9uR3JvdXBzLmdldChwYXJlbnRFbGVtZW50KSA/PyB7IGl0ZW1zOiBbXSB9O1xuICAgIGJ1dHRvbkdyb3Vwcy5zZXQocGFyZW50RWxlbWVudCwge1xuICAgICAgLi4uZ3JvdXAsXG4gICAgICBwb3NpdGlvbixcbiAgICAgIGFsaWduOiB0ZXh0QWxpZ25bc3R5bGVbXCJ0ZXh0LWFsaWduXCJdXSxcbiAgICAgIGl0ZW1zOiBbLi4uZ3JvdXAuaXRlbXMsIG1vZGVsXVxuICAgIH0pO1xuICB9KTtcblxuICAvLyBidXR0b25Hcm91cHMuZm9yRWFjaCgoYnV0dG9uKSA9PiB7XG4gIC8vICAgYnV0dG9uc1Bvc2l0aW9ucy5wdXNoKGJ1dHRvbik7XG4gIC8vIH0pO1xuXG4gIGJ1dHRvbnMuZm9yRWFjaChyZWN1cnNpdmVEZWxldGVOb2Rlcyk7XG5cbiAgcmV0dXJuIG5vZGU7XG59XG4iLCAiZXhwb3J0IGNvbnN0IGNsZWFuQ2xhc3NOYW1lcyA9IChub2RlOiBFbGVtZW50KTogdm9pZCA9PiB7XG4gIGNvbnN0IGNsYXNzTGlzdEV4Y2VwdHMgPSBbXCJicnotXCJdO1xuICBjb25zdCBlbGVtZW50c1dpdGhDbGFzc2VzID0gbm9kZS5xdWVyeVNlbGVjdG9yQWxsKFwiW2NsYXNzXVwiKTtcbiAgZWxlbWVudHNXaXRoQ2xhc3Nlcy5mb3JFYWNoKGZ1bmN0aW9uIChlbGVtZW50KSB7XG4gICAgZWxlbWVudC5jbGFzc0xpc3QuZm9yRWFjaCgoY2xzKSA9PiB7XG4gICAgICBpZiAoIWNsYXNzTGlzdEV4Y2VwdHMuc29tZSgoZXhjZXB0KSA9PiBjbHMuc3RhcnRzV2l0aChleGNlcHQpKSkge1xuICAgICAgICBpZiAoY2xzID09PSBcImZpbmFsZHJhZnRfcGxhY2Vob2xkZXJcIikge1xuICAgICAgICAgIGVsZW1lbnQuaW5uZXJIVE1MID0gXCJcIjtcbiAgICAgICAgfVxuICAgICAgICBlbGVtZW50LmNsYXNzTGlzdC5yZW1vdmUoY2xzKTtcbiAgICAgIH1cbiAgICB9KTtcblxuICAgIGlmIChlbGVtZW50LmNsYXNzTGlzdC5sZW5ndGggPT09IDApIHtcbiAgICAgIGVsZW1lbnQucmVtb3ZlQXR0cmlidXRlKFwiY2xhc3NcIik7XG4gICAgfVxuICB9KTtcbn07XG4iLCAiaW1wb3J0IHsgYWxsb3dlZFRhZ3MgfSBmcm9tIFwiLi4vY29tbW9uXCI7XG5pbXBvcnQgeyBjbGVhbkNsYXNzTmFtZXMgfSBmcm9tIFwiLi9jbGVhbkNsYXNzTmFtZXNcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIHJlbW92ZVN0eWxlc0V4Y2VwdEZvbnRXZWlnaHRBbmRDb2xvcihcbiAgaHRtbFN0cmluZzogc3RyaW5nXG4pOiBzdHJpbmcge1xuICAvLyBDcmVhdGUgYSB0ZW1wb3JhcnkgZWxlbWVudFxuICB2YXIgdGVtcEVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwiZGl2XCIpO1xuXG4gIC8vIFNldCB0aGUgSFRNTCBjb250ZW50IG9mIHRoZSB0ZW1wb3JhcnkgZWxlbWVudFxuICB0ZW1wRWxlbWVudC5pbm5lckhUTUwgPSBodG1sU3RyaW5nO1xuXG4gIC8vIEZpbmQgZWxlbWVudHMgd2l0aCBpbmxpbmUgc3R5bGVzXG4gIHZhciBlbGVtZW50c1dpdGhTdHlsZXMgPSB0ZW1wRWxlbWVudC5xdWVyeVNlbGVjdG9yQWxsKFwiW3N0eWxlXVwiKTtcblxuICAvLyBJdGVyYXRlIHRocm91Z2ggZWxlbWVudHMgd2l0aCBzdHlsZXNcbiAgZWxlbWVudHNXaXRoU3R5bGVzLmZvckVhY2goZnVuY3Rpb24gKGVsZW1lbnQpIHtcbiAgICAvLyBHZXQgdGhlIGlubGluZSBzdHlsZSBhdHRyaWJ1dGVcbiAgICB2YXIgc3R5bGVBdHRyaWJ1dGUgPSBlbGVtZW50LmdldEF0dHJpYnV0ZShcInN0eWxlXCIpID8/IFwiXCI7XG5cbiAgICAvLyBTcGxpdCB0aGUgaW5saW5lIHN0eWxlIGludG8gaW5kaXZpZHVhbCBwcm9wZXJ0aWVzXG4gICAgdmFyIHN0eWxlUHJvcGVydGllcyA9IHN0eWxlQXR0cmlidXRlLnNwbGl0KFwiO1wiKTtcblxuICAgIC8vIEluaXRpYWxpemUgYSBuZXcgc3R5bGUgc3RyaW5nIHRvIHJldGFpbiBvbmx5IGZvbnQtd2VpZ2h0IGFuZCBjb2xvclxuICAgIHZhciBuZXdTdHlsZSA9IFwiXCI7XG5cbiAgICAvLyBJdGVyYXRlIHRocm91Z2ggdGhlIHN0eWxlIHByb3BlcnRpZXNcbiAgICBmb3IgKHZhciBpID0gMDsgaSA8IHN0eWxlUHJvcGVydGllcy5sZW5ndGg7IGkrKykge1xuICAgICAgdmFyIHByb3BlcnR5ID0gc3R5bGVQcm9wZXJ0aWVzW2ldLnRyaW0oKTtcblxuICAgICAgLy8gQ2hlY2sgaWYgdGhlIHByb3BlcnR5IGlzIGZvbnQtd2VpZ2h0IG9yIGNvbG9yXG4gICAgICBpZiAocHJvcGVydHkuc3RhcnRzV2l0aChcImZvbnQtd2VpZ2h0XCIpIHx8IHByb3BlcnR5LnN0YXJ0c1dpdGgoXCJjb2xvclwiKSkge1xuICAgICAgICBuZXdTdHlsZSArPSBwcm9wZXJ0eSArIFwiOyBcIjtcbiAgICAgIH1cbiAgICB9XG5cbiAgICAvLyBTZXQgdGhlIGVsZW1lbnQncyBzdHlsZSBhdHRyaWJ1dGUgdG8gcmV0YWluIG9ubHkgZm9udC13ZWlnaHQgYW5kIGNvbG9yXG4gICAgZWxlbWVudC5zZXRBdHRyaWJ1dGUoXCJzdHlsZVwiLCBuZXdTdHlsZSk7XG4gIH0pO1xuXG4gIGNsZWFuQ2xhc3NOYW1lcyh0ZW1wRWxlbWVudCk7XG4gIC8vIFJldHVybiB0aGUgY2xlYW5lZCBIVE1MXG4gIHJldHVybiB0ZW1wRWxlbWVudC5pbm5lckhUTUw7XG59XG5cbmV4cG9ydCBmdW5jdGlvbiByZW1vdmVBbGxTdHlsZXNGcm9tSFRNTChub2RlOiBFbGVtZW50KSB7XG4gIC8vIERlZmluZSB0aGUgbGlzdCBvZiBhbGxvd2VkIHRhZ3NcblxuICAvLyBGaW5kIGVsZW1lbnRzIHdpdGggaW5saW5lIHN0eWxlcyBvbmx5IGZvciBhbGxvd2VkIHRhZ3NcbiAgY29uc3QgZWxlbWVudHNXaXRoU3R5bGVzID0gbm9kZS5xdWVyeVNlbGVjdG9yQWxsKFxuICAgIGFsbG93ZWRUYWdzLmpvaW4oXCIsXCIpICsgXCJbc3R5bGVdXCJcbiAgKTtcbiAgY29uc3QgZWxlbWVudHNXaXRoQ2xhc3NlcyA9IG5vZGUucXVlcnlTZWxlY3RvckFsbChcbiAgICBhbGxvd2VkVGFncy5qb2luKFwiLFwiKSArIFwiW2NsYXNzXVwiXG4gICk7XG5cbiAgLy8gUmVtb3ZlIHRoZSBcInN0eWxlXCIgYXR0cmlidXRlIGZyb20gZWFjaCBlbGVtZW50XG4gIGVsZW1lbnRzV2l0aFN0eWxlcy5mb3JFYWNoKGZ1bmN0aW9uIChlbGVtZW50KSB7XG4gICAgZWxlbWVudC5yZW1vdmVBdHRyaWJ1dGUoXCJzdHlsZVwiKTtcbiAgfSk7XG5cbiAgLy8gUmVtb3ZlIHRoZSBcInN0eWxlXCIgYXR0cmlidXRlIGZyb20gZWFjaCBlbGVtZW50XG4gIGNsZWFuQ2xhc3NOYW1lcyhub2RlKTtcblxuICBub2RlLmlubmVySFRNTCA9IHJlbW92ZVN0eWxlc0V4Y2VwdEZvbnRXZWlnaHRBbmRDb2xvcihub2RlLmlubmVySFRNTCk7XG5cbiAgLy8gUmV0dXJuIHRoZSBjbGVhbmVkIEhUTUxcbiAgcmV0dXJuIG5vZGU7XG59XG4iLCAiZXhwb3J0IGZ1bmN0aW9uIHRyYW5zZm9ybURpdnNUb1BhcmFncmFwaHMoY29udGFpbmVyRWxlbWVudDogRWxlbWVudCk6IEVsZW1lbnQge1xuICAvLyBHZXQgYWxsIHRoZSBkaXYgZWxlbWVudHMgd2l0aGluIHRoZSBjb250YWluZXJcbiAgY29uc3QgZGl2RWxlbWVudHMgPSBjb250YWluZXJFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCJkaXZcIik7XG5cbiAgLy8gSXRlcmF0ZSB0aHJvdWdoIGVhY2ggZGl2IGVsZW1lbnRcbiAgZGl2RWxlbWVudHMuZm9yRWFjaChmdW5jdGlvbiAoZGl2RWxlbWVudCkge1xuICAgIC8vIENyZWF0ZSBhIG5ldyBwYXJhZ3JhcGggZWxlbWVudFxuICAgIGNvbnN0IHBhcmFncmFwaEVsZW1lbnQgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KFwicFwiKTtcblxuICAgIC8vIENvcHkgYWxsIGF0dHJpYnV0ZXMgZnJvbSB0aGUgZGl2IHRvIHRoZSBwYXJhZ3JhcGhcbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IGRpdkVsZW1lbnQuYXR0cmlidXRlcy5sZW5ndGg7IGkrKykge1xuICAgICAgY29uc3QgYXR0ciA9IGRpdkVsZW1lbnQuYXR0cmlidXRlc1tpXTtcbiAgICAgIHBhcmFncmFwaEVsZW1lbnQuc2V0QXR0cmlidXRlKGF0dHIubmFtZSwgYXR0ci52YWx1ZSk7XG4gICAgfVxuXG4gICAgLy8gVHJhbnNmZXIgdGhlIGNvbnRlbnQgZnJvbSB0aGUgZGl2IHRvIHRoZSBwYXJhZ3JhcGhcbiAgICBwYXJhZ3JhcGhFbGVtZW50LmlubmVySFRNTCA9IGRpdkVsZW1lbnQuaW5uZXJIVE1MO1xuXG4gICAgLy8gUmVwbGFjZSB0aGUgZGl2IHdpdGggdGhlIG5ldyBwYXJhZ3JhcGggZWxlbWVudFxuICAgIGRpdkVsZW1lbnQucGFyZW50Tm9kZT8ucmVwbGFjZUNoaWxkKHBhcmFncmFwaEVsZW1lbnQsIGRpdkVsZW1lbnQpO1xuICB9KTtcblxuICByZXR1cm4gY29udGFpbmVyRWxlbWVudDtcbn1cbiIsICJpbXBvcnQgeyB1dWlkIH0gZnJvbSBcInV0aWxzL3NyYy91dWlkXCI7XG5cbmludGVyZmFjZSBFbWJlZE1vZGVsIHtcbiAgdHlwZTogXCJFbWJlZENvZGVcIjtcbiAgdmFsdWU6IHtcbiAgICBfaWQ6IHN0cmluZztcbiAgICBjb2RlOiBzdHJpbmc7XG4gIH07XG59XG5cbmV4cG9ydCBmdW5jdGlvbiByZW1vdmVBbGxFbWJlZHMobm9kZTogRWxlbWVudCk6IHtcbiAgbm9kZTogRWxlbWVudDtcbiAgbW9kZWxzOiBBcnJheTxFbWJlZE1vZGVsPjtcbn0ge1xuICBjb25zdCBub2RlcyA9IG5vZGUucXVlcnlTZWxlY3RvckFsbChcIi5lbWJlZGRlZC1wYXN0ZVwiKTtcbiAgY29uc3QgbW9kZWxzOiBBcnJheTxFbWJlZE1vZGVsPiA9IFtdO1xuXG4gIG5vZGVzLmZvckVhY2goKG5vZGUpID0+IHtcbiAgICBtb2RlbHMucHVzaCh7XG4gICAgICB0eXBlOiBcIkVtYmVkQ29kZVwiLFxuICAgICAgdmFsdWU6IHtcbiAgICAgICAgX2lkOiB1dWlkKCksXG4gICAgICAgIGNvZGU6IG5vZGUub3V0ZXJIVE1MXG4gICAgICB9XG4gICAgfSk7XG4gICAgbm9kZS5yZW1vdmUoKTtcbiAgfSk7XG5cbiAgcmV0dXJuIHsgbm9kZSwgbW9kZWxzIH07XG59XG4iLCAiaW1wb3J0IHsgcmdiVG9IZXggfSBmcm9tIFwidXRpbHMvc3JjL2NvbG9yL3JnYmFUb0hleFwiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5cbmV4cG9ydCBmdW5jdGlvbiBnZXRJY29uTW9kZWwoc3R5bGU6IFJlY29yZDxzdHJpbmcsIHN0cmluZz4sIG5vZGU6IEVsZW1lbnQpIHtcbiAgY29uc3QgcGFyZW50RWxlbWVudCA9IG5vZGUucGFyZW50RWxlbWVudDtcbiAgY29uc3QgaXNMaW5rID0gcGFyZW50RWxlbWVudD8udGFnTmFtZSA9PT0gXCJBXCIgfHwgbm9kZS50YWdOYW1lID09PSBcIkFcIjtcbiAgY29uc3QgcGFyZW50QmdDb2xvciA9IHBhcmVudEVsZW1lbnRcbiAgICA/IHJnYlRvSGV4KGdldE5vZGVTdHlsZShwYXJlbnRFbGVtZW50KVtcImJhY2tncm91bmQtY29sb3JcIl0pXG4gICAgOiB1bmRlZmluZWQ7XG4gIGNvbnN0IHBhcmVudEhyZWYgPVxuICAgIHBhcmVudEVsZW1lbnQgJiYgXCJocmVmXCIgaW4gcGFyZW50RWxlbWVudCA/IHBhcmVudEVsZW1lbnQuaHJlZiA6IFwiXCI7XG5cbiAgcmV0dXJuIHtcbiAgICBjb2xvckhleDogcmdiVG9IZXgoc3R5bGUuY29sb3IpID8/IFwiI2ZmZmZmZlwiLFxuICAgIGNvbG9yT3BhY2l0eTogK3N0eWxlLm9wYWNpdHksXG4gICAgLi4uKGlzTGluayAmJiB7XG4gICAgICBsaW5rRXh0ZXJuYWw6IHBhcmVudEhyZWYgPz8gKFwiaHJlZlwiIGluIG5vZGUgPyBub2RlLmhyZWYgOiBcIlwiKSxcbiAgICAgIGxpbmtUeXBlOiBcImV4dGVybmFsXCIsXG4gICAgICBsaW5rRXh0ZXJuYWxCbGFuazogXCJvblwiLFxuICAgICAgLi4uKHBhcmVudEJnQ29sb3IgJiYgeyBiZ0NvbG9ySGV4OiBwYXJlbnRCZ0NvbG9yIH0pXG4gICAgfSlcbiAgfTtcbn1cbiIsICJpbXBvcnQgeyBNVmFsdWUgfSBmcm9tIFwiQC90eXBlc1wiO1xuXG5leHBvcnQgZnVuY3Rpb24gZ2V0UGFyZW50RWxlbWVudE9mVGV4dE5vZGUobm9kZTogRWxlbWVudCk6IE1WYWx1ZTxFbGVtZW50PiB7XG4gIGlmIChub2RlLm5vZGVUeXBlID09PSBOb2RlLlRFWFRfTk9ERSkge1xuICAgIHJldHVybiAobm9kZS5wYXJlbnROb2RlIGFzIEVsZW1lbnQpID8/IHVuZGVmaW5lZDtcbiAgfVxuXG4gIHJldHVybiBBcnJheS5mcm9tKG5vZGUuY2hpbGROb2RlcykuZmluZCgobm9kZSkgPT5cbiAgICBnZXRQYXJlbnRFbGVtZW50T2ZUZXh0Tm9kZShub2RlIGFzIEVsZW1lbnQpXG4gICkgYXMgRWxlbWVudDtcbn1cbiIsICJpbXBvcnQgeyBnZXRJY29uTW9kZWwgfSBmcm9tIFwiQC9UZXh0L21vZGVscy9JY29uXCI7XG5pbXBvcnQgeyB0ZXh0QWxpZ24gfSBmcm9tIFwiQC9UZXh0L3V0aWxzL2NvbW1vblwiO1xuaW1wb3J0IHsgZmluZE5lYXJlc3RCbG9ja1BhcmVudCB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2ZpbmROZWFyZXN0QmxvY2tQYXJlbnRcIjtcbmltcG9ydCB7IGdldEVsZW1lbnRQb3NpdGlvbkFtb25nU2libGluZ3MgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXRFbGVtZW50UG9zaXRpb25BbW9uZ1NpYmxpbmdzXCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcbmltcG9ydCB7IGdldFBhcmVudEVsZW1lbnRPZlRleHROb2RlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0UGFyZW50RWxlbWVudE9mVGV4dE5vZGVcIjtcbmltcG9ydCB7IHJlY3Vyc2l2ZURlbGV0ZU5vZGVzIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vcmVjdXJzaXZlRGVsZXRlTm9kZXNcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIHJlbW92ZUFsbEljb25zKG5vZGU6IEVsZW1lbnQpOiBFbGVtZW50IHtcbiAgY29uc3QgaWNvbnMgPSBub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoXG4gICAgXCJbZGF0YS1zb2NpYWxpY29uXSxbc3R5bGUqPVxcXCJmb250LWZhbWlseTogJ01vbm8gU29jaWFsIEljb25zIEZvbnQnXFxcIl1cIlxuICApO1xuICBsZXQgaWNvbkdyb3VwcyA9IG5ldyBNYXAoKTtcblxuICBpY29ucy5mb3JFYWNoKChpY29uKSA9PiB7XG4gICAgY29uc3QgcG9zaXRpb24gPSBnZXRFbGVtZW50UG9zaXRpb25BbW9uZ1NpYmxpbmdzKGljb24sIG5vZGUpO1xuICAgIGNvbnN0IHBhcmVudEVsZW1lbnQgPSBmaW5kTmVhcmVzdEJsb2NrUGFyZW50KGljb24pO1xuICAgIGNvbnN0IHBhcmVudE5vZGUgPSBnZXRQYXJlbnRFbGVtZW50T2ZUZXh0Tm9kZShpY29uKTtcbiAgICBjb25zdCBpc0ljb25UZXh0ID0gcGFyZW50Tm9kZS5ub2RlTmFtZSA9PT0gXCIjdGV4dFwiO1xuICAgIGNvbnN0IGljb25Ob2RlID0gaXNJY29uVGV4dCA/IGljb24gOiBwYXJlbnROb2RlO1xuICAgIGNvbnN0IHN0eWxlID0gZ2V0Tm9kZVN0eWxlKGljb25Ob2RlKTtcblxuICAgIGNvbnN0IG1vZGVsID0ge1xuICAgICAgLi4uZ2V0SWNvbk1vZGVsKHN0eWxlLCBpY29uKSxcbiAgICAgIGljb25Db2RlOiBpY29uTm9kZS50ZXh0Q29udGVudC5jaGFyQ29kZUF0KDApXG4gICAgfTtcblxuICAgIGNvbnN0IGdyb3VwID0gaWNvbkdyb3Vwcy5nZXQocGFyZW50RWxlbWVudCkgPz8geyBpdGVtczogW10gfTtcbiAgICBpY29uR3JvdXBzLnNldChwYXJlbnRFbGVtZW50LCB7XG4gICAgICAuLi5ncm91cCxcbiAgICAgIHBvc2l0aW9uLFxuICAgICAgYWxpZ246IHRleHRBbGlnbltzdHlsZVtcInRleHQtYWxpZ25cIl1dLFxuICAgICAgaXRlbXM6IFsuLi5ncm91cC5pdGVtcywgbW9kZWxdXG4gICAgfSk7XG4gIH0pO1xuXG4gIC8vIGljb25Hcm91cHMuZm9yRWFjaCgoYnV0dG9uKSA9PiB7XG4gIC8vICAgaWNvbnNQb3NpdGlvbnMucHVzaChidXR0b24pO1xuICAvLyB9KTtcblxuICBpY29ucy5mb3JFYWNoKHJlY3Vyc2l2ZURlbGV0ZU5vZGVzKTtcblxuICByZXR1cm4gbm9kZTtcbn1cbiIsICJpbXBvcnQgeyBleHRyYWN0ZWRBdHRyaWJ1dGVzIH0gZnJvbSBcIkAvVGV4dC91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IGdldE5vZGVTdHlsZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldE5vZGVTdHlsZVwiO1xuXG5jb25zdCBhdHRyaWJ1dGVzID0gZXh0cmFjdGVkQXR0cmlidXRlcztcblxuZXhwb3J0IGZ1bmN0aW9uIGNvcHlDb2xvclN0eWxlVG9UZXh0Tm9kZXMoZWxlbWVudDogRWxlbWVudCk6IHZvaWQge1xuICBpZiAoZWxlbWVudC5ub2RlVHlwZSA9PT0gTm9kZS5URVhUX05PREUpIHtcbiAgICBjb25zdCBwYXJlbnRFbGVtZW50ID0gZWxlbWVudC5wYXJlbnRFbGVtZW50O1xuXG4gICAgaWYgKCFwYXJlbnRFbGVtZW50KSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuXG4gICAgaWYgKHBhcmVudEVsZW1lbnQudGFnTmFtZSA9PT0gXCJTUEFOXCIpIHtcbiAgICAgIGNvbnN0IHBhcmVudE9mUGFyZW50ID0gZWxlbWVudC5wYXJlbnRFbGVtZW50LnBhcmVudEVsZW1lbnQ7XG4gICAgICBjb25zdCBwYXJlbnRFbGVtZW50ID0gZWxlbWVudC5wYXJlbnRFbGVtZW50O1xuICAgICAgY29uc3QgcGFyZW50U3R5bGUgPSBwYXJlbnRFbGVtZW50LnN0eWxlO1xuXG4gICAgICBpZiAoXG4gICAgICAgIGF0dHJpYnV0ZXMuaW5jbHVkZXMoXCJ0ZXh0LXRyYW5zZm9ybVwiKSAmJlxuICAgICAgICAhcGFyZW50U3R5bGU/LnRleHRUcmFuc2Zvcm1cbiAgICAgICkge1xuICAgICAgICBjb25zdCBzdHlsZSA9IGdldE5vZGVTdHlsZShwYXJlbnRFbGVtZW50KTtcbiAgICAgICAgaWYgKHN0eWxlW1widGV4dC10cmFuc2Zvcm1cIl0gPT09IFwidXBwZXJjYXNlXCIpIHtcbiAgICAgICAgICBwYXJlbnRFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJicnotY2FwaXRhbGl6ZS1vblwiKTtcbiAgICAgICAgfVxuICAgICAgfVxuXG4gICAgICBpZiAoIXBhcmVudE9mUGFyZW50KSB7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cblxuICAgICAgaWYgKCFwYXJlbnRTdHlsZT8uY29sb3IpIHtcbiAgICAgICAgY29uc3QgcGFyZW50T0ZQYXJlbnRTdHlsZSA9IGdldE5vZGVTdHlsZShwYXJlbnRPZlBhcmVudCk7XG4gICAgICAgIHBhcmVudEVsZW1lbnQuc3R5bGUuY29sb3IgPSBwYXJlbnRPRlBhcmVudFN0eWxlLmNvbG9yO1xuICAgICAgfVxuICAgICAgaWYgKCFwYXJlbnRTdHlsZT8uZm9udFdlaWdodCAmJiBwYXJlbnRPZlBhcmVudC5zdHlsZT8uZm9udFdlaWdodCkge1xuICAgICAgICBwYXJlbnRFbGVtZW50LnN0eWxlLmZvbnRXZWlnaHQgPSBwYXJlbnRPZlBhcmVudC5zdHlsZS5mb250V2VpZ2h0O1xuICAgICAgfVxuXG4gICAgICBpZiAocGFyZW50T2ZQYXJlbnQudGFnTmFtZSA9PT0gXCJTUEFOXCIpIHtcbiAgICAgICAgY29uc3QgcGFyZW50Rm9udFdlaWdodCA9IHBhcmVudEVsZW1lbnQuc3R5bGUuZm9udFdlaWdodDtcbiAgICAgICAgcGFyZW50RWxlbWVudC5zdHlsZS5mb250V2VpZ2h0ID1cbiAgICAgICAgICBwYXJlbnRGb250V2VpZ2h0IHx8IGdldENvbXB1dGVkU3R5bGUocGFyZW50RWxlbWVudCkuZm9udFdlaWdodDtcbiAgICAgIH1cblxuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGNvbnN0IHNwYW5FbGVtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNwYW5cIik7XG4gICAgY29uc3QgY29tcHV0ZWRTdHlsZXMgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShwYXJlbnRFbGVtZW50KTtcblxuICAgIGlmIChcbiAgICAgIGF0dHJpYnV0ZXMuaW5jbHVkZXMoXCJ0ZXh0LXRyYW5zZm9ybVwiKSAmJlxuICAgICAgY29tcHV0ZWRTdHlsZXMudGV4dFRyYW5zZm9ybSA9PT0gXCJ1cHBlcmNhc2VcIlxuICAgICkge1xuICAgICAgc3BhbkVsZW1lbnQuY2xhc3NMaXN0LmFkZChcImJyei1jYXBpdGFsaXplLW9uXCIpO1xuICAgIH1cblxuICAgIGlmIChjb21wdXRlZFN0eWxlcy5jb2xvcikge1xuICAgICAgc3BhbkVsZW1lbnQuc3R5bGUuY29sb3IgPSBjb21wdXRlZFN0eWxlcy5jb2xvcjtcbiAgICB9XG5cbiAgICBpZiAoY29tcHV0ZWRTdHlsZXMuZm9udFdlaWdodCkge1xuICAgICAgc3BhbkVsZW1lbnQuc3R5bGUuZm9udFdlaWdodCA9IGNvbXB1dGVkU3R5bGVzLmZvbnRXZWlnaHQ7XG4gICAgfVxuXG4gICAgc3BhbkVsZW1lbnQudGV4dENvbnRlbnQgPSBlbGVtZW50LnRleHRDb250ZW50O1xuXG4gICAgaWYgKHBhcmVudEVsZW1lbnQudGFnTmFtZSA9PT0gXCJVXCIpIHtcbiAgICAgIGVsZW1lbnQucGFyZW50RWxlbWVudC5zdHlsZS5jb2xvciA9IGNvbXB1dGVkU3R5bGVzLmNvbG9yO1xuICAgIH1cblxuICAgIGlmIChlbGVtZW50KSB7XG4gICAgICBlbGVtZW50LnBhcmVudEVsZW1lbnQucmVwbGFjZUNoaWxkKHNwYW5FbGVtZW50LCBlbGVtZW50KTtcbiAgICB9XG4gIH0gZWxzZSBpZiAoZWxlbWVudC5ub2RlVHlwZSA9PT0gTm9kZS5FTEVNRU5UX05PREUpIHtcbiAgICAvLyBJZiB0aGUgY3VycmVudCBub2RlIGlzIGFuIGVsZW1lbnQgbm9kZSwgcmVjdXJzaXZlbHkgcHJvY2VzcyBpdHMgY2hpbGQgbm9kZXNcbiAgICBjb25zdCBjaGlsZE5vZGVzID0gZWxlbWVudC5jaGlsZE5vZGVzO1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgY2hpbGROb2Rlcy5sZW5ndGg7IGkrKykge1xuICAgICAgY29weUNvbG9yU3R5bGVUb1RleHROb2RlcyhjaGlsZE5vZGVzW2ldIGFzIEVsZW1lbnQpO1xuICAgIH1cbiAgfVxufVxuXG5leHBvcnQgZnVuY3Rpb24gY29weVBhcmVudENvbG9yVG9DaGlsZChub2RlOiBFbGVtZW50KSB7XG4gIG5vZGUuY2hpbGROb2Rlcy5mb3JFYWNoKChjaGlsZCkgPT4ge1xuICAgIGNvcHlDb2xvclN0eWxlVG9UZXh0Tm9kZXMoY2hpbGQgYXMgRWxlbWVudCk7XG4gIH0pO1xuXG4gIHJldHVybiBub2RlO1xufVxuIiwgImV4cG9ydCBjb25zdCByZWN1cnNpdmVHZXROb2RlcyA9IChub2RlOiBFbGVtZW50KTogQXJyYXk8RWxlbWVudD4gPT4ge1xuICBsZXQgbm9kZXM6IEFycmF5PEVsZW1lbnQ+ID0gW107XG4gIGlmIChub2RlLm5vZGVUeXBlID09PSBOb2RlLlRFWFRfTk9ERSkge1xuICAgIC8vIEZvdW5kIGEgdGV4dCBub2RlLCByZWNvcmQgaXRzIGZpcnN0IHBhcmVudCBlbGVtZW50XG4gICAgbm9kZS5wYXJlbnRFbGVtZW50ICYmIG5vZGVzLnB1c2gobm9kZS5wYXJlbnRFbGVtZW50KTtcbiAgfSBlbHNlIHtcbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IG5vZGUuY2hpbGROb2Rlcy5sZW5ndGg7IGkrKykge1xuICAgICAgY29uc3QgY2hpbGQgPSBub2RlLmNoaWxkTm9kZXNbaV07XG4gICAgICAvLyBSZWN1cnNpdmVseSBzZWFyY2ggY2hpbGQgbm9kZXMgYW5kIGFkZCB0aGVpciByZXN1bHRzIHRvIHRoZSByZXN1bHQgYXJyYXlcbiAgICAgIGlmIChjaGlsZCkge1xuICAgICAgICBub2RlcyA9IG5vZGVzLmNvbmNhdChyZWN1cnNpdmVHZXROb2RlcyhjaGlsZCBhcyBFbGVtZW50KSk7XG4gICAgICB9XG4gICAgfVxuICB9XG4gIHJldHVybiBub2Rlcztcbn07XG4iLCAiaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcIi4vZ2V0Tm9kZVN0eWxlXCI7XG5pbXBvcnQgeyByZWN1cnNpdmVHZXROb2RlcyB9IGZyb20gXCIuL3JlY3Vyc2l2ZUdldE5vZGVzXCI7XG5pbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcIkAvdHlwZXNcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIGV4dHJhY3RBbGxFbGVtZW50c1N0eWxlcyhcbiAgbm9kZTogRWxlbWVudFxuKTogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4ge1xuICBjb25zdCBub2RlcyA9IHJlY3Vyc2l2ZUdldE5vZGVzKG5vZGUpO1xuICByZXR1cm4gbm9kZXMucmVkdWNlKChhY2MsIGVsZW1lbnQpID0+IHtcbiAgICBjb25zdCBzdHlsZXMgPSBnZXROb2RlU3R5bGUoZWxlbWVudCk7XG5cbiAgICAvLyBUZXh0LUFsaWduIGFyZSB3cm9uZyBmb3IgSW5saW5lIEVsZW1lbnRzXG4gICAgaWYgKHN0eWxlc1tcImRpc3BsYXlcIl0gPT09IFwiaW5saW5lXCIpIHtcbiAgICAgIGRlbGV0ZSBzdHlsZXNbXCJ0ZXh0LWFsaWduXCJdO1xuICAgIH1cblxuICAgIHJldHVybiB7IC4uLmFjYywgLi4uc3R5bGVzIH07XG4gIH0sIHt9KTtcbn1cbiIsICJpbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcInV0aWxzXCI7XG5pbXBvcnQgeyBleHRyYWN0QWxsRWxlbWVudHNTdHlsZXMgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9leHRyYWN0QWxsRWxlbWVudHNTdHlsZXNcIjtcbmltcG9ydCB7IGdldE5vZGVTdHlsZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldE5vZGVTdHlsZVwiO1xuXG5leHBvcnQgZnVuY3Rpb24gbWVyZ2VTdHlsZXMoZWxlbWVudDogRWxlbWVudCk6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+IHtcbiAgY29uc3QgZWxlbWVudFN0eWxlcyA9IGdldE5vZGVTdHlsZShlbGVtZW50KTtcblxuICAvLyBUZXh0LUFsaWduIGFyZSB3cm9uZyBmb3IgSW5saW5lIEVsZW1lbnRzXG4gIGlmIChlbGVtZW50U3R5bGVzW1wiZGlzcGxheVwiXSA9PT0gXCJpbmxpbmVcIikge1xuICAgIGRlbGV0ZSBlbGVtZW50U3R5bGVzW1widGV4dC1hbGlnblwiXTtcbiAgfVxuXG4gIGNvbnN0IGlubmVyU3R5bGVzID0gZXh0cmFjdEFsbEVsZW1lbnRzU3R5bGVzKGVsZW1lbnQpO1xuXG4gIHJldHVybiB7XG4gICAgLi4uZWxlbWVudFN0eWxlcyxcbiAgICAuLi5pbm5lclN0eWxlcyxcbiAgICBcImxpbmUtaGVpZ2h0XCI6IGVsZW1lbnRTdHlsZXNbXCJsaW5lLWhlaWdodFwiXVxuICB9O1xufVxuIiwgImltcG9ydCB7XG4gIGV4Y2VwdEV4dHJhY3RpbmdTdHlsZSxcbiAgc2hvdWxkRXh0cmFjdEVsZW1lbnRcbn0gZnJvbSBcIkAvVGV4dC91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IG1lcmdlU3R5bGVzIH0gZnJvbSBcIkAvVGV4dC91dGlscy9zdHlsZXMvbWVyZ2VTdHlsZXNcIjtcbmltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwidXRpbHNcIjtcblxuaW50ZXJmYWNlIE91dHB1dCB7XG4gIHVpZDogc3RyaW5nO1xuICB0YWdOYW1lOiBzdHJpbmc7XG4gIHN0eWxlczogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD47XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBleHRyYWN0UGFyZW50RWxlbWVudHNXaXRoU3R5bGVzKG5vZGU6IEVsZW1lbnQpOiBBcnJheTxPdXRwdXQ+IHtcbiAgbGV0IHJlc3VsdDogQXJyYXk8T3V0cHV0PiA9IFtdO1xuXG4gIGlmIChzaG91bGRFeHRyYWN0RWxlbWVudChub2RlLCBleGNlcHRFeHRyYWN0aW5nU3R5bGUpKSB7XG4gICAgY29uc3QgdWlkID0gYHVpZC0ke01hdGgucmFuZG9tKCl9LSR7TWF0aC5yYW5kb20oKX1gO1xuICAgIG5vZGUuc2V0QXR0cmlidXRlKFwiZGF0YS11aWRcIiwgdWlkKTtcblxuICAgIHJlc3VsdC5wdXNoKHtcbiAgICAgIHVpZCxcbiAgICAgIHRhZ05hbWU6IG5vZGUudGFnTmFtZSxcbiAgICAgIHN0eWxlczogbWVyZ2VTdHlsZXMobm9kZSlcbiAgICB9KTtcbiAgfVxuXG4gIGZvciAobGV0IGkgPSAwOyBpIDwgbm9kZS5jaGlsZE5vZGVzLmxlbmd0aDsgaSsrKSB7XG4gICAgbGV0IGNoaWxkID0gbm9kZS5jaGlsZE5vZGVzW2ldO1xuICAgIHJlc3VsdCA9IHJlc3VsdC5jb25jYXQoZXh0cmFjdFBhcmVudEVsZW1lbnRzV2l0aFN0eWxlcyhjaGlsZCBhcyBFbGVtZW50KSk7XG4gIH1cblxuICByZXR1cm4gcmVzdWx0O1xufVxuIiwgImltcG9ydCB7IGV4dHJhY3RQYXJlbnRFbGVtZW50c1dpdGhTdHlsZXMgfSBmcm9tIFwiLi4vZG9tL2V4dHJhY3RQYXJlbnRFbGVtZW50c1dpdGhTdHlsZXNcIjtcbmltcG9ydCB7IGV4dHJhY3RlZEF0dHJpYnV0ZXMgfSBmcm9tIFwiQC9UZXh0L3V0aWxzL2NvbW1vblwiO1xuaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCJ1dGlsc1wiO1xuXG5pbnRlcmZhY2UgT3V0cHV0IHtcbiAgdWlkOiBzdHJpbmc7XG4gIHRhZ05hbWU6IHN0cmluZztcbiAgc3R5bGVzOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPjtcbn1cblxuZXhwb3J0IGNvbnN0IGdldFR5cG9ncmFwaHlTdHlsZXMgPSAobm9kZTogRWxlbWVudCk6IEFycmF5PE91dHB1dD4gPT4ge1xuICBjb25zdCBhbGxSaWNoVGV4dEVsZW1lbnRzID0gZXh0cmFjdFBhcmVudEVsZW1lbnRzV2l0aFN0eWxlcyhub2RlKTtcbiAgcmV0dXJuIGFsbFJpY2hUZXh0RWxlbWVudHMubWFwKChlbGVtZW50KSA9PiB7XG4gICAgY29uc3QgeyBzdHlsZXMgfSA9IGVsZW1lbnQ7XG5cbiAgICByZXR1cm4ge1xuICAgICAgLi4uZWxlbWVudCxcbiAgICAgIHN0eWxlczogZXh0cmFjdGVkQXR0cmlidXRlcy5yZWR1Y2UoKGFjYywgYXR0cmlidXRlKSA9PiB7XG4gICAgICAgIGFjY1thdHRyaWJ1dGVdID0gc3R5bGVzW2F0dHJpYnV0ZV07XG4gICAgICAgIHJldHVybiBhY2M7XG4gICAgICB9LCB7fSBhcyBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPilcbiAgICB9O1xuICB9KTtcbn07XG4iLCAiaW1wb3J0IHsgRW50cnksIE91dHB1dCwgT3V0cHV0RGF0YSB9IGZyb20gXCJAL3R5cGVzL3R5cGVcIjtcblxuZXhwb3J0IGNvbnN0IGdldERhdGEgPSAoKTogRW50cnkgPT4ge1xuICB0cnkge1xuICAgIHJldHVybiB3aW5kb3cuaXNEZXZcbiAgICAgID8ge1xuICAgICAgICAgIHNlbGVjdG9yOiBgW2RhdGEtaWQ9JyR7MTY2MzAxMzF9J11gLFxuICAgICAgICAgIGZhbWlsaWVzOiB7XG4gICAgICAgICAgICBcInByb3hpbWFfbm92YV9wcm94aW1hX25vdmFfcmVndWxhcl9zYW5zLXNlcmlmXCI6IFwidWlkMTExMVwiLFxuICAgICAgICAgICAgXCJoZWx2ZXRpY2FfbmV1ZV9oZWx2ZXRpY2FuZXVlX2hlbHZldGljYV9hcmlhbF9zYW5zLXNlcmlmXCI6IFwidWlkMjIyMlwiXG4gICAgICAgICAgfSxcbiAgICAgICAgICBkZWZhdWx0RmFtaWx5OiBcImxhdG9cIlxuICAgICAgICB9XG4gICAgICA6IHtcbiAgICAgICAgICBzZWxlY3RvcjogXCJ7e3NlbGVjdG9yfX1cIixcbiAgICAgICAgICBmYW1pbGllczogSlNPTi5wYXJzZShcInt7ZmFtaWxpZXN9fVwiKSxcbiAgICAgICAgICBkZWZhdWx0RmFtaWx5OiBcInt7ZGVmYXVsdEZhbWlseX19XCJcbiAgICAgICAgfTtcbiAgfSBjYXRjaCAoZSkge1xuICAgIGNvbnN0IGZhbWlseU1vY2sgPSB7XG4gICAgICBsYXRvOiBcInVpZF9mb3JfbGF0b1wiLFxuICAgICAgcm9ib3RvOiBcInVpZF9mb3Jfcm9ib3RvXCJcbiAgICB9O1xuICAgIGNvbnN0IG1vY2s6IEVudHJ5ID0ge1xuICAgICAgc2VsZWN0b3I6IFwiLm15LWRpdlwiLFxuICAgICAgZmFtaWxpZXM6IGZhbWlseU1vY2ssXG4gICAgICBkZWZhdWx0RmFtaWx5OiBcImxhdG9cIlxuICAgIH07XG5cbiAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICBKU09OLnN0cmluZ2lmeSh7XG4gICAgICAgIGVycm9yOiBgSW52YWxpZCBKU09OICR7ZX1gLFxuICAgICAgICBkZXRhaWxzOiBgTXVzdCBiZTogJHtKU09OLnN0cmluZ2lmeShtb2NrKX1gXG4gICAgICB9KVxuICAgICk7XG4gIH1cbn07XG5cbmV4cG9ydCBjb25zdCBjcmVhdGVEYXRhID0gKG91dHB1dDogT3V0cHV0RGF0YSk6IE91dHB1dCA9PiB7XG4gIHJldHVybiBKU09OLnN0cmluZ2lmeShvdXRwdXQpO1xufTtcbiIsICJpbXBvcnQgeyBjcmVhdGVXcmFwcGVyTW9kZWwgfSBmcm9tIFwiQC9Nb2RlbHMvV3JhcHBlclwiO1xuaW1wb3J0IHsgc3R5bGVzVG9DbGFzc2VzIH0gZnJvbSBcIkAvVGV4dC9tb2RlbHMvVGV4dFwiO1xuaW1wb3J0IHsgcmVtb3ZlQWxsQnV0dG9ucyB9IGZyb20gXCJAL1RleHQvdXRpbHMvYnV0dG9uc1wiO1xuaW1wb3J0IHsgcmVtb3ZlQWxsU3R5bGVzRnJvbUhUTUwgfSBmcm9tIFwiQC9UZXh0L3V0aWxzL2RvbS9yZW1vdmVBbGxTdHlsZXNGcm9tSFRNTFwiO1xuaW1wb3J0IHsgdHJhbnNmb3JtRGl2c1RvUGFyYWdyYXBocyB9IGZyb20gXCJAL1RleHQvdXRpbHMvZG9tL3RyYW5zZm9ybURpdnNUb1BhcmFncmFwaHNcIjtcbmltcG9ydCB7IHJlbW92ZUFsbEVtYmVkcyB9IGZyb20gXCJAL1RleHQvdXRpbHMvZW1iZWRzXCI7XG5pbXBvcnQgeyByZW1vdmVBbGxJY29ucyB9IGZyb20gXCJAL1RleHQvdXRpbHMvaWNvbnNcIjtcbmltcG9ydCB7IGNvcHlQYXJlbnRDb2xvclRvQ2hpbGQgfSBmcm9tIFwiQC9UZXh0L3V0aWxzL3N0eWxlcy9jb3B5UGFyZW50Q29sb3JUb0NoaWxkXCI7XG5pbXBvcnQgeyBnZXRUeXBvZ3JhcGh5U3R5bGVzIH0gZnJvbSBcIkAvVGV4dC91dGlscy9zdHlsZXMvZ2V0VHlwb2dyYXBoeVN0eWxlc1wiO1xuaW1wb3J0IHsgRWxlbWVudE1vZGVsLCBFbnRyeSwgT3V0cHV0IH0gZnJvbSBcIkAvdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgY3JlYXRlRGF0YSB9IGZyb20gXCJAL3V0aWxzL2dldERhdGFcIjtcbmltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwidXRpbHNcIjtcbmltcG9ydCB7IHV1aWQgfSBmcm9tIFwidXRpbHMvc3JjL3V1aWRcIjtcblxuaW50ZXJmYWNlIERhdGEge1xuICBub2RlOiBFbGVtZW50O1xuICBmYW1pbGllczogUmVjb3JkPHN0cmluZywgc3RyaW5nPjtcbiAgZGVmYXVsdEZhbWlseTogc3RyaW5nO1xuICBzdHlsZXM6IEFycmF5PHtcbiAgICB1aWQ6IHN0cmluZztcbiAgICB0YWdOYW1lOiBzdHJpbmc7XG4gICAgc3R5bGVzOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPjtcbiAgfT47XG59XG5cbmNvbnN0IHRvQnVpbGRlclRleHQgPSAoZGF0YTogRGF0YSk6IHN0cmluZyA9PiB7XG4gIGNvbnN0IHsgbm9kZSwgc3R5bGVzLCBmYW1pbGllcywgZGVmYXVsdEZhbWlseSB9ID0gZGF0YTtcblxuICBzdHlsZXMubWFwKChzdHlsZSkgPT4ge1xuICAgIGNvbnN0IGNsYXNzZXMgPSBzdHlsZXNUb0NsYXNzZXMoc3R5bGUuc3R5bGVzLCBmYW1pbGllcywgZGVmYXVsdEZhbWlseSk7XG4gICAgY29uc3Qgc3R5bGVOb2RlID0gbm9kZS5xdWVyeVNlbGVjdG9yKGBbZGF0YS11aWQ9JyR7c3R5bGUudWlkfSddYCk7XG5cbiAgICBpZiAoc3R5bGVOb2RlKSB7XG4gICAgICBzdHlsZU5vZGUuY2xhc3NMaXN0LmFkZCguLi5jbGFzc2VzKTtcbiAgICAgIHN0eWxlTm9kZS5yZW1vdmVBdHRyaWJ1dGUoXCJkYXRhLXVpZFwiKTtcbiAgICB9XG4gIH0pO1xuXG4gIHJldHVybiBub2RlLmlubmVySFRNTDtcbn07XG5cbmV4cG9ydCBjb25zdCBnZXRUZXh0ID0gKGRhdGE6IEVudHJ5KTogT3V0cHV0ID0+IHtcbiAgbGV0IG5vZGUgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKGRhdGEuc2VsZWN0b3IpO1xuXG4gIGlmICghbm9kZSkge1xuICAgIHJldHVybiBKU09OLnN0cmluZ2lmeSh7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke2RhdGEuc2VsZWN0b3J9IG5vdCBmb3VuZGBcbiAgICB9KTtcbiAgfVxuXG4gIG5vZGUgPSBub2RlLmNoaWxkcmVuWzBdO1xuXG4gIGlmICghbm9kZSkge1xuICAgIHJldHVybiBKU09OLnN0cmluZ2lmeSh7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke2RhdGEuc2VsZWN0b3J9IGhhcyBubyB3cmFwcGVyYFxuICAgIH0pO1xuICB9XG5cbiAgY29uc3QgZWxlbWVudHM6IEFycmF5PEVsZW1lbnRNb2RlbD4gPSBbXTtcblxuICBub2RlID0gcmVtb3ZlQWxsSWNvbnMobm9kZSk7XG5cbiAgbm9kZSA9IHJlbW92ZUFsbEJ1dHRvbnMobm9kZSk7XG5cbiAgY29uc3QgZW1iZWRzID0gcmVtb3ZlQWxsRW1iZWRzKG5vZGUpO1xuXG4gIG5vZGUgPSBlbWJlZHMubm9kZTtcblxuICBub2RlID0gdHJhbnNmb3JtRGl2c1RvUGFyYWdyYXBocyhub2RlKTtcblxuICBub2RlID0gY29weVBhcmVudENvbG9yVG9DaGlsZChub2RlKTtcblxuICBub2RlID0gcmVtb3ZlQWxsU3R5bGVzRnJvbUhUTUwobm9kZSk7XG5cbiAgY29uc3QgZGF0YVRleHQgPSB7XG4gICAgbm9kZTogbm9kZSxcbiAgICBmYW1pbGllczogZGF0YS5mYW1pbGllcyxcbiAgICBkZWZhdWx0RmFtaWx5OiBkYXRhLmRlZmF1bHRGYW1pbHksXG4gICAgc3R5bGVzOiBnZXRUeXBvZ3JhcGh5U3R5bGVzKG5vZGUpXG4gIH07XG5cbiAgZWxlbWVudHMucHVzaChcbiAgICBjcmVhdGVXcmFwcGVyTW9kZWwoe1xuICAgICAgX3N0eWxlczogW1wid3JhcHBlclwiLCBcIndyYXBwZXItLXJpY2hUZXh0XCJdLFxuICAgICAgaXRlbXM6IFtcbiAgICAgICAge1xuICAgICAgICAgIHR5cGU6IFwiVGV4dFwiLFxuICAgICAgICAgIHZhbHVlOiB7XG4gICAgICAgICAgICBfaWQ6IHV1aWQoKSxcbiAgICAgICAgICAgIF9zdHlsZXM6IFtcInJpY2hUZXh0XCJdLFxuICAgICAgICAgICAgdGV4dDogdG9CdWlsZGVyVGV4dChkYXRhVGV4dClcbiAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICAgIF1cbiAgICB9KVxuICApO1xuXG4gIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YTogZWxlbWVudHMgfSk7XG59O1xuIl0sCiAgIm1hcHBpbmdzIjogIjs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQUFBO0FBQUE7QUFBQTtBQUFBOzs7QUNDTyxNQUFJLFNBQVMsV0FBUyxPQUFPLGdCQUFnQixJQUFJLFdBQVcsS0FBSyxDQUFDO0FBQ2xFLE1BQUksZUFBZSxDQUFDQSxXQUFVLGFBQWEsY0FBYztBQUM5RCxRQUFJLFFBQVEsS0FBTSxL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
