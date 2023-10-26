"use strict";
(() => {
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
            warns[fontFamily] = {
              message: `Font family not found ${fontFamily}`
            };
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
  var textAlign2 = {
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
        align: textAlign2[style["text-align"]],
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
        align: textAlign2[style["text-align"]],
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
    elements.push({
      type: "Text",
      value: {
        _id: uuid(),
        text: toBuilderText(dataText)
      }
    });
    return JSON.stringify({
      data: elements
    });
  };

  // ../../../../../../../packages/elements/src/utils/getData.ts
  var getData = () => {
    try {
      return {
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

  // src/Text/index.ts
  var data = getData();
  getText(data);
})();
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2dldExldHRlclNwYWNpbmcudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2dldExpbmVIZWlnaHQudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL3JlYWRlci9udW1iZXIudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvbW9kZWxzL1RleHQvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2NvbG9yL3JnYmFUb0hleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC9tb2RlbHMvQnV0dG9uL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2NvbW1vbi9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvZG9tL2ZpbmROZWFyZXN0QmxvY2tQYXJlbnQudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2RvbS9nZXRFbGVtZW50UG9zaXRpb25BbW9uZ1NpYmxpbmdzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vcmVjdXJzaXZlRGVsZXRlTm9kZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvYnV0dG9ucy9pbmRleC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC91dGlscy9kb20vY2xlYW5DbGFzc05hbWVzLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L3V0aWxzL2RvbS9yZW1vdmVBbGxTdHlsZXNGcm9tSFRNTC50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC91dGlscy9kb20vdHJhbnNmb3JtRGl2c1RvUGFyYWdyYXBocy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9ub2RlX21vZHVsZXMvbmFub2lkL2luZGV4LmJyb3dzZXIuanMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL3V1aWQudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvZW1iZWRzL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9UZXh0L21vZGVscy9JY29uL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vZ2V0UGFyZW50RWxlbWVudE9mVGV4dE5vZGUudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvaWNvbnMvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2NvcHlQYXJlbnRDb2xvclRvQ2hpbGQudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvdXRpbHMvc3JjL2RvbS9yZWN1cnNpdmVHZXROb2Rlcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy91dGlscy9zcmMvZG9tL2V4dHJhY3RBbGxFbGVtZW50c1N0eWxlcy50cyIsICIuLi8uLi8uLi8uLi8uLi8uLi8uLi8uLi9wYWNrYWdlcy9lbGVtZW50cy9zcmMvVGV4dC91dGlscy9zdHlsZXMvbWVyZ2VTdHlsZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvZG9tL2V4dHJhY3RQYXJlbnRFbGVtZW50c1dpdGhTdHlsZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvdXRpbHMvc3R5bGVzL2dldFR5cG9ncmFwaHlTdHlsZXMudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL1RleHQvaW5kZXgudHMiLCAiLi4vLi4vLi4vLi4vLi4vLi4vLi4vLi4vcGFja2FnZXMvZWxlbWVudHMvc3JjL3V0aWxzL2dldERhdGEudHMiLCAiLi4vc3JjL1RleHQvaW5kZXgudHMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImV4cG9ydCBmdW5jdGlvbiBnZXRMZXR0ZXJTcGFjaW5nKHZhbHVlOiBzdHJpbmcpOiBzdHJpbmcge1xuICBpZiAodmFsdWUgPT09IFwibm9ybWFsXCIpIHtcbiAgICByZXR1cm4gXCIwXCI7XG4gIH1cblxuICAvLyBSZW1vdmUgJ3B4JyBhbmQgYW55IGV4dHJhIHdoaXRlc3BhY2VcbiAgY29uc3QgbGV0dGVyU3BhY2luZ1ZhbHVlID0gdmFsdWUucmVwbGFjZSgvcHgvZywgXCJcIikudHJpbSgpO1xuICBjb25zdCBbaW50ZWdlclBhcnQsIGRlY2ltYWxQYXJ0ID0gXCIwXCJdID0gbGV0dGVyU3BhY2luZ1ZhbHVlLnNwbGl0KFwiLlwiKTtcbiAgY29uc3QgdG9OdW1iZXJJID0gK2ludGVnZXJQYXJ0O1xuXG4gIGlmICh0b051bWJlckkgPCAwIHx8IE9iamVjdC5pcyh0b051bWJlckksIC0wKSkge1xuICAgIHJldHVybiBcIm1fXCIgKyAtdG9OdW1iZXJJICsgXCJfXCIgKyBkZWNpbWFsUGFydFswXTtcbiAgfVxuICByZXR1cm4gdG9OdW1iZXJJICsgXCJfXCIgKyBkZWNpbWFsUGFydFswXTtcbn1cbiIsICJleHBvcnQgZnVuY3Rpb24gZ2V0TGluZUhlaWdodCh2YWx1ZTogc3RyaW5nLCBmb250U2l6ZTogc3RyaW5nKTogc3RyaW5nIHtcbiAgaWYgKHZhbHVlID09PSBcIm5vcm1hbFwiKSB7XG4gICAgcmV0dXJuIFwiMV8yXCI7XG4gIH1cblxuICBjb25zdCBsaW5lSGVpZ2h0VmFsdWUgPSB2YWx1ZS5yZXBsYWNlKFwicHhcIiwgXCJcIik7XG4gIGNvbnN0IGxpbmVIZWlnaHQgPSBOdW1iZXIobGluZUhlaWdodFZhbHVlKSAvIE51bWJlcihmb250U2l6ZSk7XG4gIGNvbnN0IFtpbnRlZ2VyUGFydCwgZGVjaW1hbFBhcnQgPSBcIlwiXSA9IGxpbmVIZWlnaHQudG9TdHJpbmcoKS5zcGxpdChcIi5cIik7XG5cbiAgcmV0dXJuIGRlY2ltYWxQYXJ0ID8gaW50ZWdlclBhcnQgKyBcIl9cIiArIGRlY2ltYWxQYXJ0WzBdIDogaW50ZWdlclBhcnQ7XG59XG4iLCAiaW1wb3J0IHsgUmVhZGVyIH0gZnJvbSBcIi4vdHlwZXNcIjtcblxuZXhwb3J0IGNvbnN0IHJlYWQ6IFJlYWRlcjxudW1iZXI+ID0gKHYpID0+IHtcbiAgc3dpdGNoICh0eXBlb2Ygdikge1xuICAgIGNhc2UgXCJzdHJpbmdcIjoge1xuICAgICAgY29uc3Qgdl8gPSB2ICE9PSBcIlwiID8gTnVtYmVyKHYpIDogTmFOO1xuICAgICAgcmV0dXJuIGlzTmFOKHZfKSA/IHVuZGVmaW5lZCA6IHZfO1xuICAgIH1cbiAgICBjYXNlIFwibnVtYmVyXCI6XG4gICAgICByZXR1cm4gaXNOYU4odikgPyB1bmRlZmluZWQgOiB2O1xuICAgIGRlZmF1bHQ6XG4gICAgICByZXR1cm4gdW5kZWZpbmVkO1xuICB9XG59O1xuXG5leHBvcnQgY29uc3QgcmVhZEludDogUmVhZGVyPG51bWJlcj4gPSAodikgPT4ge1xuICBpZiAodHlwZW9mIHYgPT09IFwic3RyaW5nXCIpIHtcbiAgICByZXR1cm4gcGFyc2VJbnQodik7XG4gIH1cblxuICByZXR1cm4gcmVhZCh2KTtcbn07XG4iLCAiaW1wb3J0IHsgZ2V0TGV0dGVyU3BhY2luZyB9IGZyb20gXCJAL1RleHQvdXRpbHMvc3R5bGVzL2dldExldHRlclNwYWNpbmdcIjtcbmltcG9ydCB7IGdldExpbmVIZWlnaHQgfSBmcm9tIFwiQC9UZXh0L3V0aWxzL3N0eWxlcy9nZXRMaW5lSGVpZ2h0XCI7XG5pbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcInV0aWxzXCI7XG5pbXBvcnQgKiBhcyBOdW0gZnJvbSBcInV0aWxzL3NyYy9yZWFkZXIvbnVtYmVyXCI7XG5cbmV4cG9ydCBjb25zdCBzdHlsZXNUb0NsYXNzZXMgPSAoXG4gIHN0eWxlczogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4sXG4gIGZhbWlsaWVzOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+LFxuICBkZWZhdWx0RmFtaWx5OiBzdHJpbmdcbik6IEFycmF5PHN0cmluZz4gPT4ge1xuICBjb25zdCBjbGFzc2VzOiBBcnJheTxzdHJpbmc+ID0gW107XG5cbiAgT2JqZWN0LmVudHJpZXMoc3R5bGVzKS5mb3JFYWNoKChba2V5LCB2YWx1ZV0pID0+IHtcbiAgICBzd2l0Y2ggKGtleSkge1xuICAgICAgY2FzZSBcImZvbnQtc2l6ZVwiOiB7XG4gICAgICAgIGNvbnN0IHNpemUgPSBNYXRoLnJvdW5kKE51bS5yZWFkSW50KHZhbHVlKSA/PyAxKTtcbiAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotZnMtbGctJHtzaXplfWApO1xuICAgICAgICBicmVhaztcbiAgICAgIH1cbiAgICAgIGNhc2UgXCJmb250LWZhbWlseVwiOlxuICAgICAgICBjb25zdCBmb250RmFtaWx5ID0gYCR7dmFsdWV9YFxuICAgICAgICAgIC5yZXBsYWNlKC9bJ1wiXFwsXS9nLCBcIlwiKVxuICAgICAgICAgIC5yZXBsYWNlKC9cXHMvZywgXCJfXCIpXG4gICAgICAgICAgLnRvTG9jYWxlTG93ZXJDYXNlKCk7XG5cbiAgICAgICAgaWYgKCFmYW1pbGllc1tmb250RmFtaWx5XSkge1xuICAgICAgICAgIHdhcm5zW2ZvbnRGYW1pbHldID0ge1xuICAgICAgICAgICAgbWVzc2FnZTogYEZvbnQgZmFtaWx5IG5vdCBmb3VuZCAke2ZvbnRGYW1pbHl9YFxuICAgICAgICAgIH07XG4gICAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotZmYtJHtkZWZhdWx0RmFtaWx5fWAsIFwiYnJ6LWZ0LXVwbG9hZFwiKTtcbiAgICAgICAgICBicmVhaztcbiAgICAgICAgfVxuICAgICAgICBjbGFzc2VzLnB1c2goYGJyei1mZi0ke2ZhbWlsaWVzW2ZvbnRGYW1pbHldfWAsIFwiYnJ6LWZ0LXVwbG9hZFwiKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICBjYXNlIFwiZm9udC13ZWlnaHRcIjpcbiAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotZnctbGctJHt2YWx1ZX1gKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICBjYXNlIFwidGV4dC1hbGlnblwiOlxuICAgICAgICBjbGFzc2VzLnB1c2goYGJyei10ZXh0LWxnLSR7dGV4dEFsaWduW3ZhbHVlXSB8fCBcImxlZnRcIn1gKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICBjYXNlIFwibGV0dGVyLXNwYWNpbmdcIjpcbiAgICAgICAgY29uc3QgbGV0dGVyU3BhY2luZyA9IGdldExldHRlclNwYWNpbmcoYCR7dmFsdWV9YCk7XG4gICAgICAgIGNsYXNzZXMucHVzaChgYnJ6LWxzLWxnLSR7bGV0dGVyU3BhY2luZ31gKTtcbiAgICAgICAgYnJlYWs7XG4gICAgICBjYXNlIFwibGluZS1oZWlnaHRcIjpcbiAgICAgICAgY29uc3QgZnMgPSBgJHtzdHlsZXNbXCJmb250LXNpemVcIl19YDtcbiAgICAgICAgY29uc3QgZm9udFNpemUgPSBmcy5yZXBsYWNlKFwicHhcIiwgXCJcIik7XG4gICAgICAgIGNvbnN0IGxpbmVIZWlnaHQgPSBnZXRMaW5lSGVpZ2h0KGAke3ZhbHVlfWAsIGZvbnRTaXplKTtcbiAgICAgICAgY2xhc3Nlcy5wdXNoKGBicnotbGgtbGctJHtsaW5lSGVpZ2h0fWApO1xuICAgICAgICBicmVhaztcblxuICAgICAgZGVmYXVsdDpcbiAgICAgICAgYnJlYWs7XG4gICAgfVxuICB9KTtcblxuICByZXR1cm4gY2xhc3Nlcztcbn07XG4iLCAiaW1wb3J0IHsgTVZhbHVlIH0gZnJvbSBcIkAvdHlwZXNcIjtcblxuZnVuY3Rpb24gX3JnYlRvSGV4KHI6IG51bWJlciwgZzogbnVtYmVyLCBiOiBudW1iZXIpOiBzdHJpbmcge1xuICByID0gTWF0aC5taW4oMjU1LCBNYXRoLm1heCgwLCBNYXRoLnJvdW5kKHIpKSk7XG4gIGcgPSBNYXRoLm1pbigyNTUsIE1hdGgubWF4KDAsIE1hdGgucm91bmQoZykpKTtcbiAgYiA9IE1hdGgubWluKDI1NSwgTWF0aC5tYXgoMCwgTWF0aC5yb3VuZChiKSkpO1xuXG4gIGNvbnN0IGhleFIgPSByLnRvU3RyaW5nKDE2KS5wYWRTdGFydCgyLCBcIjBcIik7XG4gIGNvbnN0IGhleEcgPSBnLnRvU3RyaW5nKDE2KS5wYWRTdGFydCgyLCBcIjBcIik7XG4gIGNvbnN0IGhleEIgPSBiLnRvU3RyaW5nKDE2KS5wYWRTdGFydCgyLCBcIjBcIik7XG5cbiAgcmV0dXJuIGAjJHtoZXhSfSR7aGV4R30ke2hleEJ9YC50b1VwcGVyQ2FzZSgpO1xufVxuXG5leHBvcnQgY29uc3QgcmdiVG9IZXggPSAocmdiYTogc3RyaW5nKTogTVZhbHVlPHN0cmluZz4gPT4ge1xuICBjb25zdCByZ2JWYWx1ZXMgPSByZ2JhXG4gICAgLnNsaWNlKDQsIC0xKVxuICAgIC5zcGxpdChcIixcIilcbiAgICAubWFwKCh2YWx1ZSkgPT4gcGFyc2VJbnQodmFsdWUudHJpbSgpKSk7XG5cbiAgaWYgKHJnYlZhbHVlcy5sZW5ndGggIT09IDMpIHtcbiAgICByZXR1cm4gdW5kZWZpbmVkO1xuICB9XG5cbiAgcmV0dXJuIF9yZ2JUb0hleChyZ2JWYWx1ZXNbMF0sIHJnYlZhbHVlc1sxXSwgcmdiVmFsdWVzWzJdKTtcbn07XG4iLCAiaW1wb3J0IHsgcmdiVG9IZXggfSBmcm9tIFwidXRpbHMvc3JjL2NvbG9yL3JnYmFUb0hleFwiO1xuXG5leHBvcnQgZnVuY3Rpb24gZ2V0QnV0dG9uTW9kZWwoc3R5bGU6IFJlY29yZDxzdHJpbmcsIHN0cmluZz4sIG5vZGU6IEVsZW1lbnQpIHtcbiAgY29uc3QgaXNMaW5rID0gbm9kZS50YWdOYW1lID09PSBcIkFcIjtcblxuICByZXR1cm4ge1xuICAgIGJnQ29sb3JIZXg6IHJnYlRvSGV4KHN0eWxlW1wiYmFja2dyb3VuZC1jb2xvclwiXSkgPz8gXCIjZmZmZmZmXCIsXG4gICAgYmdDb2xvck9wYWNpdHk6ICtzdHlsZS5vcGFjaXR5LFxuICAgIGJnQ29sb3JUeXBlOiBcInNvbGlkXCIsXG4gICAgY29sb3JIZXg6IHJnYlRvSGV4KHN0eWxlLmNvbG9yKSA/PyBcIiNmZmZmZmZcIixcbiAgICBjb2xvck9wYWNpdHk6IDEsXG4gICAgdGV4dDogXCJ0ZXh0XCIgaW4gbm9kZSA/IG5vZGUudGV4dCA6IHVuZGVmaW5lZCxcbiAgICAuLi4oaXNMaW5rICYmIHtcbiAgICAgIGxpbmtFeHRlcm5hbDogXCJocmVmXCIgaW4gbm9kZSA/IG5vZGUuaHJlZiA6IFwiXCIsXG4gICAgICBsaW5rVHlwZTogXCJleHRlcm5hbFwiLFxuICAgICAgbGlua0V4dGVybmFsQmxhbms6IFwib25cIlxuICAgIH0pXG4gIH07XG59XG4iLCAiZXhwb3J0IGNvbnN0IGFsbG93ZWRUYWdzID0gW1xuICBcIlBcIixcbiAgXCJIMVwiLFxuICBcIkgyXCIsXG4gIFwiSDNcIixcbiAgXCJINFwiLFxuICBcIkg1XCIsXG4gIFwiSDZcIixcbiAgXCJVTFwiLFxuICBcIk9MXCIsXG4gIFwiTElcIlxuXTtcblxuZXhwb3J0IGNvbnN0IGV4Y2VwdEV4dHJhY3RpbmdTdHlsZSA9IFtcIlVMXCIsIFwiT0xcIl07XG5cbmV4cG9ydCBjb25zdCBleHRyYWN0ZWRBdHRyaWJ1dGVzID0gW1xuICBcImZvbnQtc2l6ZVwiLFxuICBcImZvbnQtZmFtaWx5XCIsXG4gIFwiZm9udC13ZWlnaHRcIixcbiAgXCJ0ZXh0LWFsaWduXCIsXG4gIFwibGV0dGVyLXNwYWNpbmdcIixcbiAgXCJ0ZXh0LXRyYW5zZm9ybVwiXG5dO1xuXG5leHBvcnQgY29uc3QgdGV4dEFsaWduOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+ID0ge1xuICBcIi13ZWJraXQtY2VudGVyXCI6IFwiY2VudGVyXCIsXG4gIFwiLW1vei1jZW50ZXJcIjogXCJjZW50ZXJcIixcbiAgc3RhcnQ6IFwibGVmdFwiLFxuICBlbmQ6IFwicmlnaHRcIixcbiAgbGVmdDogXCJsZWZ0XCIsXG4gIHJpZ2h0OiBcInJpZ2h0XCIsXG4gIGNlbnRlcjogXCJjZW50ZXJcIixcbiAganVzdGlmeTogXCJqdXN0aWZ5XCJcbn07XG5cbmV4cG9ydCBmdW5jdGlvbiBzaG91bGRFeHRyYWN0RWxlbWVudChcbiAgZWxlbWVudDogRWxlbWVudCxcbiAgZXhjZXB0aW9uczogQXJyYXk8c3RyaW5nPlxuKTogYm9vbGVhbiB7XG4gIGNvbnN0IGlzQWxsb3dlZCA9IGFsbG93ZWRUYWdzLmluY2x1ZGVzKGVsZW1lbnQudGFnTmFtZSk7XG5cbiAgaWYgKGlzQWxsb3dlZCAmJiBleGNlcHRpb25zKSB7XG4gICAgcmV0dXJuICFleGNlcHRpb25zLmluY2x1ZGVzKGVsZW1lbnQudGFnTmFtZSk7XG4gIH1cblxuICByZXR1cm4gaXNBbGxvd2VkO1xufVxuIiwgImltcG9ydCB7IE1WYWx1ZSB9IGZyb20gXCJAL3R5cGVzXCI7XG5cbmV4cG9ydCBmdW5jdGlvbiBmaW5kTmVhcmVzdEJsb2NrUGFyZW50KGVsZW1lbnQ6IEVsZW1lbnQpOiBNVmFsdWU8RWxlbWVudD4ge1xuICBpZiAoIWVsZW1lbnQucGFyZW50RWxlbWVudCkge1xuICAgIHJldHVybiB1bmRlZmluZWQ7XG4gIH1cblxuICBjb25zdCBkaXNwbGF5U3R5bGUgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShlbGVtZW50LnBhcmVudEVsZW1lbnQpLmRpc3BsYXk7XG4gIGNvbnN0IGlzQmxvY2tFbGVtZW50ID1cbiAgICBkaXNwbGF5U3R5bGUgPT09IFwiYmxvY2tcIiB8fFxuICAgIGRpc3BsYXlTdHlsZSA9PT0gXCJmbGV4XCIgfHxcbiAgICBkaXNwbGF5U3R5bGUgPT09IFwiZ3JpZFwiO1xuXG4gIGlmIChpc0Jsb2NrRWxlbWVudCkge1xuICAgIHJldHVybiBlbGVtZW50LnBhcmVudEVsZW1lbnQ7XG4gIH0gZWxzZSB7XG4gICAgcmV0dXJuIGZpbmROZWFyZXN0QmxvY2tQYXJlbnQoZWxlbWVudC5wYXJlbnRFbGVtZW50KTtcbiAgfVxufVxuIiwgImltcG9ydCB7IGZpbmROZWFyZXN0QmxvY2tQYXJlbnQgfSBmcm9tIFwiLi9maW5kTmVhcmVzdEJsb2NrUGFyZW50XCI7XG5pbXBvcnQgeyBNVmFsdWUgfSBmcm9tIFwiQC90eXBlc1wiO1xuXG5leHBvcnQgZnVuY3Rpb24gZ2V0RWxlbWVudFBvc2l0aW9uQW1vbmdTaWJsaW5ncyhcbiAgZWxlbWVudDogRWxlbWVudCxcbiAgcm9vdDogRWxlbWVudFxuKTogTVZhbHVlPHN0cmluZz4ge1xuICBjb25zdCBwYXJlbnQgPSBmaW5kTmVhcmVzdEJsb2NrUGFyZW50KGVsZW1lbnQpO1xuXG4gIGlmICghcGFyZW50KSB7XG4gICAgY29uc29sZS5lcnJvcihcIk5vIGJsb2NrLWxldmVsIHBhcmVudCBmb3VuZC5cIik7XG4gICAgcmV0dXJuO1xuICB9XG5cbiAgY29uc3Qgc2libGluZ3MgPSBBcnJheS5mcm9tKHJvb3QuY2hpbGRyZW4pO1xuXG4gIGxldCB0b3RhbFNpYmxpbmdzID0gMDtcbiAgbGV0IGluZGV4ID0gMDtcblxuICBpZiAocm9vdC5jb250YWlucyhwYXJlbnQpKSB7XG4gICAgdG90YWxTaWJsaW5ncyA9IHNpYmxpbmdzLmxlbmd0aDtcbiAgICBpbmRleCA9IHNpYmxpbmdzLmluZGV4T2YocGFyZW50KTtcbiAgfVxuXG4gIGlmIChpbmRleCA9PT0gLTEpIHtcbiAgICBjb25zb2xlLmVycm9yKFwiRWxlbWVudCBpcyBub3QgYSBjaGlsZCBvZiBpdHMgcGFyZW50LlwiKTtcbiAgICByZXR1cm47XG4gIH1cblxuICByZXR1cm4gaW5kZXggPT09IDBcbiAgICA/IFwidG9wXCJcbiAgICA6IGluZGV4ID09PSB0b3RhbFNpYmxpbmdzIC0gMVxuICAgID8gXCJib3R0b21cIlxuICAgIDogXCJtaWRkbGVcIjtcbn1cbiIsICJpbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcIkAvdHlwZXNcIjtcblxuZXhwb3J0IGNvbnN0IGdldE5vZGVTdHlsZSA9IChcbiAgbm9kZTogSFRNTEVsZW1lbnQgfCBFbGVtZW50XG4pOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPiA9PiB7XG4gIGNvbnN0IGNvbXB1dGVkU3R5bGVzID0gd2luZG93LmdldENvbXB1dGVkU3R5bGUobm9kZSk7XG4gIGNvbnN0IHN0eWxlczogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4gPSB7fTtcblxuICBPYmplY3QudmFsdWVzKGNvbXB1dGVkU3R5bGVzKS5mb3JFYWNoKChrZXkpID0+IHtcbiAgICBzdHlsZXNba2V5XSA9IGNvbXB1dGVkU3R5bGVzLmdldFByb3BlcnR5VmFsdWUoa2V5KTtcbiAgfSk7XG5cbiAgcmV0dXJuIHN0eWxlcztcbn07XG4iLCAiZXhwb3J0IGZ1bmN0aW9uIHJlY3Vyc2l2ZURlbGV0ZU5vZGVzKG5vZGU6IEVsZW1lbnQpIHtcbiAgY29uc3QgcGFyZW50RWxlbWVudCA9IG5vZGUucGFyZW50RWxlbWVudDtcbiAgbm9kZS5yZW1vdmUoKTtcblxuICBpZiAocGFyZW50RWxlbWVudD8uY2hpbGROb2Rlcy5sZW5ndGggPT09IDApIHtcbiAgICByZWN1cnNpdmVEZWxldGVOb2RlcyhwYXJlbnRFbGVtZW50KTtcbiAgfVxufVxuIiwgImltcG9ydCB7IGdldEJ1dHRvbk1vZGVsIH0gZnJvbSBcIkAvVGV4dC9tb2RlbHMvQnV0dG9uXCI7XG5pbXBvcnQgeyB0ZXh0QWxpZ24gfSBmcm9tIFwiQC9UZXh0L3V0aWxzL2NvbW1vblwiO1xuaW1wb3J0IHsgZmluZE5lYXJlc3RCbG9ja1BhcmVudCB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2ZpbmROZWFyZXN0QmxvY2tQYXJlbnRcIjtcbmltcG9ydCB7IGdldEVsZW1lbnRQb3NpdGlvbkFtb25nU2libGluZ3MgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXRFbGVtZW50UG9zaXRpb25BbW9uZ1NpYmxpbmdzXCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcbmltcG9ydCB7IHJlY3Vyc2l2ZURlbGV0ZU5vZGVzIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vcmVjdXJzaXZlRGVsZXRlTm9kZXNcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIHJlbW92ZUFsbEJ1dHRvbnMobm9kZTogRWxlbWVudCk6IEVsZW1lbnQge1xuICBjb25zdCBidXR0b25zID0gbm9kZS5xdWVyeVNlbGVjdG9yQWxsKFwiLnNpdGVzLWJ1dHRvblwiKTtcbiAgbGV0IGJ1dHRvbkdyb3VwcyA9IG5ldyBNYXAoKTtcblxuICBidXR0b25zLmZvckVhY2goKGJ1dHRvbikgPT4ge1xuICAgIGNvbnN0IHBvc2l0aW9uID0gZ2V0RWxlbWVudFBvc2l0aW9uQW1vbmdTaWJsaW5ncyhidXR0b24sIG5vZGUpO1xuICAgIGNvbnN0IHBhcmVudEVsZW1lbnQgPSBmaW5kTmVhcmVzdEJsb2NrUGFyZW50KGJ1dHRvbik7XG4gICAgY29uc3Qgc3R5bGUgPSBnZXROb2RlU3R5bGUoYnV0dG9uKTtcbiAgICBjb25zdCBtb2RlbCA9IGdldEJ1dHRvbk1vZGVsKHN0eWxlLCBidXR0b24pO1xuXG4gICAgY29uc3QgZ3JvdXAgPSBidXR0b25Hcm91cHMuZ2V0KHBhcmVudEVsZW1lbnQpID8/IHsgaXRlbXM6IFtdIH07XG4gICAgYnV0dG9uR3JvdXBzLnNldChwYXJlbnRFbGVtZW50LCB7XG4gICAgICAuLi5ncm91cCxcbiAgICAgIHBvc2l0aW9uLFxuICAgICAgYWxpZ246IHRleHRBbGlnbltzdHlsZVtcInRleHQtYWxpZ25cIl1dLFxuICAgICAgaXRlbXM6IFsuLi5ncm91cC5pdGVtcywgbW9kZWxdXG4gICAgfSk7XG4gIH0pO1xuXG4gIC8vIGJ1dHRvbkdyb3Vwcy5mb3JFYWNoKChidXR0b24pID0+IHtcbiAgLy8gICBidXR0b25zUG9zaXRpb25zLnB1c2goYnV0dG9uKTtcbiAgLy8gfSk7XG5cbiAgYnV0dG9ucy5mb3JFYWNoKHJlY3Vyc2l2ZURlbGV0ZU5vZGVzKTtcblxuICByZXR1cm4gbm9kZTtcbn1cbiIsICJleHBvcnQgY29uc3QgY2xlYW5DbGFzc05hbWVzID0gKG5vZGU6IEVsZW1lbnQpOiB2b2lkID0+IHtcbiAgY29uc3QgY2xhc3NMaXN0RXhjZXB0cyA9IFtcImJyei1cIl07XG4gIGNvbnN0IGVsZW1lbnRzV2l0aENsYXNzZXMgPSBub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoXCJbY2xhc3NdXCIpO1xuICBlbGVtZW50c1dpdGhDbGFzc2VzLmZvckVhY2goZnVuY3Rpb24gKGVsZW1lbnQpIHtcbiAgICBlbGVtZW50LmNsYXNzTGlzdC5mb3JFYWNoKChjbHMpID0+IHtcbiAgICAgIGlmICghY2xhc3NMaXN0RXhjZXB0cy5zb21lKChleGNlcHQpID0+IGNscy5zdGFydHNXaXRoKGV4Y2VwdCkpKSB7XG4gICAgICAgIGlmIChjbHMgPT09IFwiZmluYWxkcmFmdF9wbGFjZWhvbGRlclwiKSB7XG4gICAgICAgICAgZWxlbWVudC5pbm5lckhUTUwgPSBcIlwiO1xuICAgICAgICB9XG4gICAgICAgIGVsZW1lbnQuY2xhc3NMaXN0LnJlbW92ZShjbHMpO1xuICAgICAgfVxuICAgIH0pO1xuXG4gICAgaWYgKGVsZW1lbnQuY2xhc3NMaXN0Lmxlbmd0aCA9PT0gMCkge1xuICAgICAgZWxlbWVudC5yZW1vdmVBdHRyaWJ1dGUoXCJjbGFzc1wiKTtcbiAgICB9XG4gIH0pO1xufTtcbiIsICJpbXBvcnQgeyBhbGxvd2VkVGFncyB9IGZyb20gXCIuLi9jb21tb25cIjtcbmltcG9ydCB7IGNsZWFuQ2xhc3NOYW1lcyB9IGZyb20gXCIuL2NsZWFuQ2xhc3NOYW1lc1wiO1xuXG5leHBvcnQgZnVuY3Rpb24gcmVtb3ZlU3R5bGVzRXhjZXB0Rm9udFdlaWdodEFuZENvbG9yKFxuICBodG1sU3RyaW5nOiBzdHJpbmdcbik6IHN0cmluZyB7XG4gIC8vIENyZWF0ZSBhIHRlbXBvcmFyeSBlbGVtZW50XG4gIHZhciB0ZW1wRWxlbWVudCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJkaXZcIik7XG5cbiAgLy8gU2V0IHRoZSBIVE1MIGNvbnRlbnQgb2YgdGhlIHRlbXBvcmFyeSBlbGVtZW50XG4gIHRlbXBFbGVtZW50LmlubmVySFRNTCA9IGh0bWxTdHJpbmc7XG5cbiAgLy8gRmluZCBlbGVtZW50cyB3aXRoIGlubGluZSBzdHlsZXNcbiAgdmFyIGVsZW1lbnRzV2l0aFN0eWxlcyA9IHRlbXBFbGVtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoXCJbc3R5bGVdXCIpO1xuXG4gIC8vIEl0ZXJhdGUgdGhyb3VnaCBlbGVtZW50cyB3aXRoIHN0eWxlc1xuICBlbGVtZW50c1dpdGhTdHlsZXMuZm9yRWFjaChmdW5jdGlvbiAoZWxlbWVudCkge1xuICAgIC8vIEdldCB0aGUgaW5saW5lIHN0eWxlIGF0dHJpYnV0ZVxuICAgIHZhciBzdHlsZUF0dHJpYnV0ZSA9IGVsZW1lbnQuZ2V0QXR0cmlidXRlKFwic3R5bGVcIikgPz8gXCJcIjtcblxuICAgIC8vIFNwbGl0IHRoZSBpbmxpbmUgc3R5bGUgaW50byBpbmRpdmlkdWFsIHByb3BlcnRpZXNcbiAgICB2YXIgc3R5bGVQcm9wZXJ0aWVzID0gc3R5bGVBdHRyaWJ1dGUuc3BsaXQoXCI7XCIpO1xuXG4gICAgLy8gSW5pdGlhbGl6ZSBhIG5ldyBzdHlsZSBzdHJpbmcgdG8gcmV0YWluIG9ubHkgZm9udC13ZWlnaHQgYW5kIGNvbG9yXG4gICAgdmFyIG5ld1N0eWxlID0gXCJcIjtcblxuICAgIC8vIEl0ZXJhdGUgdGhyb3VnaCB0aGUgc3R5bGUgcHJvcGVydGllc1xuICAgIGZvciAodmFyIGkgPSAwOyBpIDwgc3R5bGVQcm9wZXJ0aWVzLmxlbmd0aDsgaSsrKSB7XG4gICAgICB2YXIgcHJvcGVydHkgPSBzdHlsZVByb3BlcnRpZXNbaV0udHJpbSgpO1xuXG4gICAgICAvLyBDaGVjayBpZiB0aGUgcHJvcGVydHkgaXMgZm9udC13ZWlnaHQgb3IgY29sb3JcbiAgICAgIGlmIChwcm9wZXJ0eS5zdGFydHNXaXRoKFwiZm9udC13ZWlnaHRcIikgfHwgcHJvcGVydHkuc3RhcnRzV2l0aChcImNvbG9yXCIpKSB7XG4gICAgICAgIG5ld1N0eWxlICs9IHByb3BlcnR5ICsgXCI7IFwiO1xuICAgICAgfVxuICAgIH1cblxuICAgIC8vIFNldCB0aGUgZWxlbWVudCdzIHN0eWxlIGF0dHJpYnV0ZSB0byByZXRhaW4gb25seSBmb250LXdlaWdodCBhbmQgY29sb3JcbiAgICBlbGVtZW50LnNldEF0dHJpYnV0ZShcInN0eWxlXCIsIG5ld1N0eWxlKTtcbiAgfSk7XG5cbiAgY2xlYW5DbGFzc05hbWVzKHRlbXBFbGVtZW50KTtcbiAgLy8gUmV0dXJuIHRoZSBjbGVhbmVkIEhUTUxcbiAgcmV0dXJuIHRlbXBFbGVtZW50LmlubmVySFRNTDtcbn1cblxuZXhwb3J0IGZ1bmN0aW9uIHJlbW92ZUFsbFN0eWxlc0Zyb21IVE1MKG5vZGU6IEVsZW1lbnQpIHtcbiAgLy8gRGVmaW5lIHRoZSBsaXN0IG9mIGFsbG93ZWQgdGFnc1xuXG4gIC8vIEZpbmQgZWxlbWVudHMgd2l0aCBpbmxpbmUgc3R5bGVzIG9ubHkgZm9yIGFsbG93ZWQgdGFnc1xuICBjb25zdCBlbGVtZW50c1dpdGhTdHlsZXMgPSBub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoXG4gICAgYWxsb3dlZFRhZ3Muam9pbihcIixcIikgKyBcIltzdHlsZV1cIlxuICApO1xuICBjb25zdCBlbGVtZW50c1dpdGhDbGFzc2VzID0gbm9kZS5xdWVyeVNlbGVjdG9yQWxsKFxuICAgIGFsbG93ZWRUYWdzLmpvaW4oXCIsXCIpICsgXCJbY2xhc3NdXCJcbiAgKTtcblxuICAvLyBSZW1vdmUgdGhlIFwic3R5bGVcIiBhdHRyaWJ1dGUgZnJvbSBlYWNoIGVsZW1lbnRcbiAgZWxlbWVudHNXaXRoU3R5bGVzLmZvckVhY2goZnVuY3Rpb24gKGVsZW1lbnQpIHtcbiAgICBlbGVtZW50LnJlbW92ZUF0dHJpYnV0ZShcInN0eWxlXCIpO1xuICB9KTtcblxuICAvLyBSZW1vdmUgdGhlIFwic3R5bGVcIiBhdHRyaWJ1dGUgZnJvbSBlYWNoIGVsZW1lbnRcbiAgY2xlYW5DbGFzc05hbWVzKG5vZGUpO1xuXG4gIG5vZGUuaW5uZXJIVE1MID0gcmVtb3ZlU3R5bGVzRXhjZXB0Rm9udFdlaWdodEFuZENvbG9yKG5vZGUuaW5uZXJIVE1MKTtcblxuICAvLyBSZXR1cm4gdGhlIGNsZWFuZWQgSFRNTFxuICByZXR1cm4gbm9kZTtcbn1cbiIsICJleHBvcnQgZnVuY3Rpb24gdHJhbnNmb3JtRGl2c1RvUGFyYWdyYXBocyhjb250YWluZXJFbGVtZW50OiBFbGVtZW50KTogRWxlbWVudCB7XG4gIC8vIEdldCBhbGwgdGhlIGRpdiBlbGVtZW50cyB3aXRoaW4gdGhlIGNvbnRhaW5lclxuICBjb25zdCBkaXZFbGVtZW50cyA9IGNvbnRhaW5lckVsZW1lbnQucXVlcnlTZWxlY3RvckFsbChcImRpdlwiKTtcblxuICAvLyBJdGVyYXRlIHRocm91Z2ggZWFjaCBkaXYgZWxlbWVudFxuICBkaXZFbGVtZW50cy5mb3JFYWNoKGZ1bmN0aW9uIChkaXZFbGVtZW50KSB7XG4gICAgLy8gQ3JlYXRlIGEgbmV3IHBhcmFncmFwaCBlbGVtZW50XG4gICAgY29uc3QgcGFyYWdyYXBoRWxlbWVudCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoXCJwXCIpO1xuXG4gICAgLy8gQ29weSBhbGwgYXR0cmlidXRlcyBmcm9tIHRoZSBkaXYgdG8gdGhlIHBhcmFncmFwaFxuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgZGl2RWxlbWVudC5hdHRyaWJ1dGVzLmxlbmd0aDsgaSsrKSB7XG4gICAgICBjb25zdCBhdHRyID0gZGl2RWxlbWVudC5hdHRyaWJ1dGVzW2ldO1xuICAgICAgcGFyYWdyYXBoRWxlbWVudC5zZXRBdHRyaWJ1dGUoYXR0ci5uYW1lLCBhdHRyLnZhbHVlKTtcbiAgICB9XG5cbiAgICAvLyBUcmFuc2ZlciB0aGUgY29udGVudCBmcm9tIHRoZSBkaXYgdG8gdGhlIHBhcmFncmFwaFxuICAgIHBhcmFncmFwaEVsZW1lbnQuaW5uZXJIVE1MID0gZGl2RWxlbWVudC5pbm5lckhUTUw7XG5cbiAgICAvLyBSZXBsYWNlIHRoZSBkaXYgd2l0aCB0aGUgbmV3IHBhcmFncmFwaCBlbGVtZW50XG4gICAgZGl2RWxlbWVudC5wYXJlbnROb2RlPy5yZXBsYWNlQ2hpbGQocGFyYWdyYXBoRWxlbWVudCwgZGl2RWxlbWVudCk7XG4gIH0pO1xuXG4gIHJldHVybiBjb250YWluZXJFbGVtZW50O1xufVxuIiwgImV4cG9ydCB7IHVybEFscGhhYmV0IH0gZnJvbSAnLi91cmwtYWxwaGFiZXQvaW5kZXguanMnXG5leHBvcnQgbGV0IHJhbmRvbSA9IGJ5dGVzID0+IGNyeXB0by5nZXRSYW5kb21WYWx1ZXMobmV3IFVpbnQ4QXJyYXkoYnl0ZXMpKVxuZXhwb3J0IGxldCBjdXN0b21SYW5kb20gPSAoYWxwaGFiZXQsIGRlZmF1bHRTaXplLCBnZXRSYW5kb20pID0+IHtcbiAgbGV0IG1hc2sgPSAoMiA8PCAoTWF0aC5sb2coYWxwaGFiZXQubGVuZ3RoIC0gMSkgLyBNYXRoLkxOMikpIC0gMVxuICBsZXQgc3RlcCA9IC1+KCgxLjYgKiBtYXNrICogZGVmYXVsdFNpemUpIC8gYWxwaGFiZXQubGVuZ3RoKVxuICByZXR1cm4gKHNpemUgPSBkZWZhdWx0U2l6ZSkgPT4ge1xuICAgIGxldCBpZCA9ICcnXG4gICAgd2hpbGUgKHRydWUpIHtcbiAgICAgIGxldCBieXRlcyA9IGdldFJhbmRvbShzdGVwKVxuICAgICAgbGV0IGogPSBzdGVwXG4gICAgICB3aGlsZSAoai0tKSB7XG4gICAgICAgIGlkICs9IGFscGhhYmV0W2J5dGVzW2pdICYgbWFza10gfHwgJydcbiAgICAgICAgaWYgKGlkLmxlbmd0aCA9PT0gc2l6ZSkgcmV0dXJuIGlkXG4gICAgICB9XG4gICAgfVxuICB9XG59XG5leHBvcnQgbGV0IGN1c3RvbUFscGhhYmV0ID0gKGFscGhhYmV0LCBzaXplID0gMjEpID0+XG4gIGN1c3RvbVJhbmRvbShhbHBoYWJldCwgc2l6ZSwgcmFuZG9tKVxuZXhwb3J0IGxldCBuYW5vaWQgPSAoc2l6ZSA9IDIxKSA9PlxuICBjcnlwdG8uZ2V0UmFuZG9tVmFsdWVzKG5ldyBVaW50OEFycmF5KHNpemUpKS5yZWR1Y2UoKGlkLCBieXRlKSA9PiB7XG4gICAgYnl0ZSAmPSA2M1xuICAgIGlmIChieXRlIDwgMzYpIHtcbiAgICAgIGlkICs9IGJ5dGUudG9TdHJpbmcoMzYpXG4gICAgfSBlbHNlIGlmIChieXRlIDwgNjIpIHtcbiAgICAgIGlkICs9IChieXRlIC0gMjYpLnRvU3RyaW5nKDM2KS50b1VwcGVyQ2FzZSgpXG4gICAgfSBlbHNlIGlmIChieXRlID4gNjIpIHtcbiAgICAgIGlkICs9ICctJ1xuICAgIH0gZWxzZSB7XG4gICAgICBpZCArPSAnXydcbiAgICB9XG4gICAgcmV0dXJuIGlkXG4gIH0sICcnKVxuIiwgImltcG9ydCB7IGN1c3RvbUFscGhhYmV0IH0gZnJvbSBcIm5hbm9pZFwiO1xuXG5jb25zdCBhbHBoYWJldCA9IFwiYWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXpcIjtcbmNvbnN0IGZ1bGxTeW1ib2xMaXN0ID1cbiAgXCIwMTIzNDU2Nzg5YWJjZGVmZ2hpamtsbW5vcHFyc3R1dnd4eXpBQkNERUZHSElKS0xNTk9QUVJTVFVWV1hZWl9cIjtcblxuZXhwb3J0IGNvbnN0IHV1aWQgPSAobGVuZ3RoID0gMTIpOiBzdHJpbmcgPT5cbiAgY3VzdG9tQWxwaGFiZXQoYWxwaGFiZXQsIDEpKCkgK1xuICBjdXN0b21BbHBoYWJldChmdWxsU3ltYm9sTGlzdCwgbGVuZ3RoKShsZW5ndGggLSAxKTtcbiIsICJpbXBvcnQgeyB1dWlkIH0gZnJvbSBcInV0aWxzL3NyYy91dWlkXCI7XG5cbmludGVyZmFjZSBFbWJlZE1vZGVsIHtcbiAgdHlwZTogXCJFbWJlZENvZGVcIjtcbiAgdmFsdWU6IHtcbiAgICBfaWQ6IHN0cmluZztcbiAgICBjb2RlOiBzdHJpbmc7XG4gIH07XG59XG5cbmV4cG9ydCBmdW5jdGlvbiByZW1vdmVBbGxFbWJlZHMobm9kZTogRWxlbWVudCk6IHtcbiAgbm9kZTogRWxlbWVudDtcbiAgbW9kZWxzOiBBcnJheTxFbWJlZE1vZGVsPjtcbn0ge1xuICBjb25zdCBub2RlcyA9IG5vZGUucXVlcnlTZWxlY3RvckFsbChcIi5lbWJlZGRlZC1wYXN0ZVwiKTtcbiAgY29uc3QgbW9kZWxzOiBBcnJheTxFbWJlZE1vZGVsPiA9IFtdO1xuXG4gIG5vZGVzLmZvckVhY2goKG5vZGUpID0+IHtcbiAgICBtb2RlbHMucHVzaCh7XG4gICAgICB0eXBlOiBcIkVtYmVkQ29kZVwiLFxuICAgICAgdmFsdWU6IHtcbiAgICAgICAgX2lkOiB1dWlkKCksXG4gICAgICAgIGNvZGU6IG5vZGUub3V0ZXJIVE1MXG4gICAgICB9XG4gICAgfSk7XG4gICAgbm9kZS5yZW1vdmUoKTtcbiAgfSk7XG5cbiAgcmV0dXJuIHsgbm9kZSwgbW9kZWxzIH07XG59XG4iLCAiaW1wb3J0IHsgcmdiVG9IZXggfSBmcm9tIFwidXRpbHMvc3JjL2NvbG9yL3JnYmFUb0hleFwiO1xuaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlXCI7XG5cbmV4cG9ydCBmdW5jdGlvbiBnZXRJY29uTW9kZWwoc3R5bGU6IFJlY29yZDxzdHJpbmcsIHN0cmluZz4sIG5vZGU6IEVsZW1lbnQpIHtcbiAgY29uc3QgcGFyZW50RWxlbWVudCA9IG5vZGUucGFyZW50RWxlbWVudDtcbiAgY29uc3QgaXNMaW5rID0gcGFyZW50RWxlbWVudD8udGFnTmFtZSA9PT0gXCJBXCIgfHwgbm9kZS50YWdOYW1lID09PSBcIkFcIjtcbiAgY29uc3QgcGFyZW50QmdDb2xvciA9IHBhcmVudEVsZW1lbnRcbiAgICA/IHJnYlRvSGV4KGdldE5vZGVTdHlsZShwYXJlbnRFbGVtZW50KVtcImJhY2tncm91bmQtY29sb3JcIl0pXG4gICAgOiB1bmRlZmluZWQ7XG4gIGNvbnN0IHBhcmVudEhyZWYgPVxuICAgIHBhcmVudEVsZW1lbnQgJiYgXCJocmVmXCIgaW4gcGFyZW50RWxlbWVudCA/IHBhcmVudEVsZW1lbnQuaHJlZiA6IFwiXCI7XG5cbiAgcmV0dXJuIHtcbiAgICBjb2xvckhleDogcmdiVG9IZXgoc3R5bGUuY29sb3IpID8/IFwiI2ZmZmZmZlwiLFxuICAgIGNvbG9yT3BhY2l0eTogK3N0eWxlLm9wYWNpdHksXG4gICAgLi4uKGlzTGluayAmJiB7XG4gICAgICBsaW5rRXh0ZXJuYWw6IHBhcmVudEhyZWYgPz8gKFwiaHJlZlwiIGluIG5vZGUgPyBub2RlLmhyZWYgOiBcIlwiKSxcbiAgICAgIGxpbmtUeXBlOiBcImV4dGVybmFsXCIsXG4gICAgICBsaW5rRXh0ZXJuYWxCbGFuazogXCJvblwiLFxuICAgICAgLi4uKHBhcmVudEJnQ29sb3IgJiYgeyBiZ0NvbG9ySGV4OiBwYXJlbnRCZ0NvbG9yIH0pXG4gICAgfSlcbiAgfTtcbn1cbiIsICJpbXBvcnQgeyBNVmFsdWUgfSBmcm9tIFwiQC90eXBlc1wiO1xuXG5leHBvcnQgZnVuY3Rpb24gZ2V0UGFyZW50RWxlbWVudE9mVGV4dE5vZGUobm9kZTogRWxlbWVudCk6IE1WYWx1ZTxFbGVtZW50PiB7XG4gIGlmIChub2RlLm5vZGVUeXBlID09PSBOb2RlLlRFWFRfTk9ERSkge1xuICAgIHJldHVybiAobm9kZS5wYXJlbnROb2RlIGFzIEVsZW1lbnQpID8/IHVuZGVmaW5lZDtcbiAgfVxuXG4gIHJldHVybiBBcnJheS5mcm9tKG5vZGUuY2hpbGROb2RlcykuZmluZCgobm9kZSkgPT5cbiAgICBnZXRQYXJlbnRFbGVtZW50T2ZUZXh0Tm9kZShub2RlIGFzIEVsZW1lbnQpXG4gICkgYXMgRWxlbWVudDtcbn1cbiIsICJpbXBvcnQgeyBnZXRJY29uTW9kZWwgfSBmcm9tIFwiQC9UZXh0L21vZGVscy9JY29uXCI7XG5pbXBvcnQgeyB0ZXh0QWxpZ24gfSBmcm9tIFwiQC9UZXh0L3V0aWxzL2NvbW1vblwiO1xuaW1wb3J0IHsgZmluZE5lYXJlc3RCbG9ja1BhcmVudCB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2ZpbmROZWFyZXN0QmxvY2tQYXJlbnRcIjtcbmltcG9ydCB7IGdldEVsZW1lbnRQb3NpdGlvbkFtb25nU2libGluZ3MgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXRFbGVtZW50UG9zaXRpb25BbW9uZ1NpYmxpbmdzXCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcbmltcG9ydCB7IGdldFBhcmVudEVsZW1lbnRPZlRleHROb2RlIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vZ2V0UGFyZW50RWxlbWVudE9mVGV4dE5vZGVcIjtcbmltcG9ydCB7IHJlY3Vyc2l2ZURlbGV0ZU5vZGVzIH0gZnJvbSBcInV0aWxzL3NyYy9kb20vcmVjdXJzaXZlRGVsZXRlTm9kZXNcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIHJlbW92ZUFsbEljb25zKG5vZGU6IEVsZW1lbnQpOiBFbGVtZW50IHtcbiAgY29uc3QgaWNvbnMgPSBub2RlLnF1ZXJ5U2VsZWN0b3JBbGwoXG4gICAgXCJbZGF0YS1zb2NpYWxpY29uXSxbc3R5bGUqPVxcXCJmb250LWZhbWlseTogJ01vbm8gU29jaWFsIEljb25zIEZvbnQnXFxcIl1cIlxuICApO1xuICBsZXQgaWNvbkdyb3VwcyA9IG5ldyBNYXAoKTtcblxuICBpY29ucy5mb3JFYWNoKChpY29uKSA9PiB7XG4gICAgY29uc3QgcG9zaXRpb24gPSBnZXRFbGVtZW50UG9zaXRpb25BbW9uZ1NpYmxpbmdzKGljb24sIG5vZGUpO1xuICAgIGNvbnN0IHBhcmVudEVsZW1lbnQgPSBmaW5kTmVhcmVzdEJsb2NrUGFyZW50KGljb24pO1xuICAgIGNvbnN0IHBhcmVudE5vZGUgPSBnZXRQYXJlbnRFbGVtZW50T2ZUZXh0Tm9kZShpY29uKTtcbiAgICBjb25zdCBpc0ljb25UZXh0ID0gcGFyZW50Tm9kZS5ub2RlTmFtZSA9PT0gXCIjdGV4dFwiO1xuICAgIGNvbnN0IGljb25Ob2RlID0gaXNJY29uVGV4dCA/IGljb24gOiBwYXJlbnROb2RlO1xuICAgIGNvbnN0IHN0eWxlID0gZ2V0Tm9kZVN0eWxlKGljb25Ob2RlKTtcblxuICAgIGNvbnN0IG1vZGVsID0ge1xuICAgICAgLi4uZ2V0SWNvbk1vZGVsKHN0eWxlLCBpY29uKSxcbiAgICAgIGljb25Db2RlOiBpY29uTm9kZS50ZXh0Q29udGVudC5jaGFyQ29kZUF0KDApXG4gICAgfTtcblxuICAgIGNvbnN0IGdyb3VwID0gaWNvbkdyb3Vwcy5nZXQocGFyZW50RWxlbWVudCkgPz8geyBpdGVtczogW10gfTtcbiAgICBpY29uR3JvdXBzLnNldChwYXJlbnRFbGVtZW50LCB7XG4gICAgICAuLi5ncm91cCxcbiAgICAgIHBvc2l0aW9uLFxuICAgICAgYWxpZ246IHRleHRBbGlnbltzdHlsZVtcInRleHQtYWxpZ25cIl1dLFxuICAgICAgaXRlbXM6IFsuLi5ncm91cC5pdGVtcywgbW9kZWxdXG4gICAgfSk7XG4gIH0pO1xuXG4gIC8vIGljb25Hcm91cHMuZm9yRWFjaCgoYnV0dG9uKSA9PiB7XG4gIC8vICAgaWNvbnNQb3NpdGlvbnMucHVzaChidXR0b24pO1xuICAvLyB9KTtcblxuICBpY29ucy5mb3JFYWNoKHJlY3Vyc2l2ZURlbGV0ZU5vZGVzKTtcblxuICByZXR1cm4gbm9kZTtcbn1cbiIsICJpbXBvcnQgeyBleHRyYWN0ZWRBdHRyaWJ1dGVzIH0gZnJvbSBcIkAvVGV4dC91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IGdldE5vZGVTdHlsZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldE5vZGVTdHlsZVwiO1xuXG5jb25zdCBhdHRyaWJ1dGVzID0gZXh0cmFjdGVkQXR0cmlidXRlcztcblxuZXhwb3J0IGZ1bmN0aW9uIGNvcHlDb2xvclN0eWxlVG9UZXh0Tm9kZXMoZWxlbWVudDogRWxlbWVudCk6IHZvaWQge1xuICBpZiAoZWxlbWVudC5ub2RlVHlwZSA9PT0gTm9kZS5URVhUX05PREUpIHtcbiAgICBjb25zdCBwYXJlbnRFbGVtZW50ID0gZWxlbWVudC5wYXJlbnRFbGVtZW50O1xuXG4gICAgaWYgKCFwYXJlbnRFbGVtZW50KSB7XG4gICAgICByZXR1cm47XG4gICAgfVxuXG4gICAgaWYgKHBhcmVudEVsZW1lbnQudGFnTmFtZSA9PT0gXCJTUEFOXCIpIHtcbiAgICAgIGNvbnN0IHBhcmVudE9mUGFyZW50ID0gZWxlbWVudC5wYXJlbnRFbGVtZW50LnBhcmVudEVsZW1lbnQ7XG4gICAgICBjb25zdCBwYXJlbnRFbGVtZW50ID0gZWxlbWVudC5wYXJlbnRFbGVtZW50O1xuICAgICAgY29uc3QgcGFyZW50U3R5bGUgPSBwYXJlbnRFbGVtZW50LnN0eWxlO1xuXG4gICAgICBpZiAoXG4gICAgICAgIGF0dHJpYnV0ZXMuaW5jbHVkZXMoXCJ0ZXh0LXRyYW5zZm9ybVwiKSAmJlxuICAgICAgICAhcGFyZW50U3R5bGU/LnRleHRUcmFuc2Zvcm1cbiAgICAgICkge1xuICAgICAgICBjb25zdCBzdHlsZSA9IGdldE5vZGVTdHlsZShwYXJlbnRFbGVtZW50KTtcbiAgICAgICAgaWYgKHN0eWxlW1widGV4dC10cmFuc2Zvcm1cIl0gPT09IFwidXBwZXJjYXNlXCIpIHtcbiAgICAgICAgICBwYXJlbnRFbGVtZW50LmNsYXNzTGlzdC5hZGQoXCJicnotY2FwaXRhbGl6ZS1vblwiKTtcbiAgICAgICAgfVxuICAgICAgfVxuXG4gICAgICBpZiAoIXBhcmVudE9mUGFyZW50KSB7XG4gICAgICAgIHJldHVybjtcbiAgICAgIH1cblxuICAgICAgaWYgKCFwYXJlbnRTdHlsZT8uY29sb3IpIHtcbiAgICAgICAgY29uc3QgcGFyZW50T0ZQYXJlbnRTdHlsZSA9IGdldE5vZGVTdHlsZShwYXJlbnRPZlBhcmVudCk7XG4gICAgICAgIHBhcmVudEVsZW1lbnQuc3R5bGUuY29sb3IgPSBwYXJlbnRPRlBhcmVudFN0eWxlLmNvbG9yO1xuICAgICAgfVxuICAgICAgaWYgKCFwYXJlbnRTdHlsZT8uZm9udFdlaWdodCAmJiBwYXJlbnRPZlBhcmVudC5zdHlsZT8uZm9udFdlaWdodCkge1xuICAgICAgICBwYXJlbnRFbGVtZW50LnN0eWxlLmZvbnRXZWlnaHQgPSBwYXJlbnRPZlBhcmVudC5zdHlsZS5mb250V2VpZ2h0O1xuICAgICAgfVxuXG4gICAgICBpZiAocGFyZW50T2ZQYXJlbnQudGFnTmFtZSA9PT0gXCJTUEFOXCIpIHtcbiAgICAgICAgY29uc3QgcGFyZW50Rm9udFdlaWdodCA9IHBhcmVudEVsZW1lbnQuc3R5bGUuZm9udFdlaWdodDtcbiAgICAgICAgcGFyZW50RWxlbWVudC5zdHlsZS5mb250V2VpZ2h0ID1cbiAgICAgICAgICBwYXJlbnRGb250V2VpZ2h0IHx8IGdldENvbXB1dGVkU3R5bGUocGFyZW50RWxlbWVudCkuZm9udFdlaWdodDtcbiAgICAgIH1cblxuICAgICAgcmV0dXJuO1xuICAgIH1cblxuICAgIGNvbnN0IHNwYW5FbGVtZW50ID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcInNwYW5cIik7XG4gICAgY29uc3QgY29tcHV0ZWRTdHlsZXMgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShwYXJlbnRFbGVtZW50KTtcblxuICAgIGlmIChcbiAgICAgIGF0dHJpYnV0ZXMuaW5jbHVkZXMoXCJ0ZXh0LXRyYW5zZm9ybVwiKSAmJlxuICAgICAgY29tcHV0ZWRTdHlsZXMudGV4dFRyYW5zZm9ybSA9PT0gXCJ1cHBlcmNhc2VcIlxuICAgICkge1xuICAgICAgc3BhbkVsZW1lbnQuY2xhc3NMaXN0LmFkZChcImJyei1jYXBpdGFsaXplLW9uXCIpO1xuICAgIH1cblxuICAgIGlmIChjb21wdXRlZFN0eWxlcy5jb2xvcikge1xuICAgICAgc3BhbkVsZW1lbnQuc3R5bGUuY29sb3IgPSBjb21wdXRlZFN0eWxlcy5jb2xvcjtcbiAgICB9XG5cbiAgICBpZiAoY29tcHV0ZWRTdHlsZXMuZm9udFdlaWdodCkge1xuICAgICAgc3BhbkVsZW1lbnQuc3R5bGUuZm9udFdlaWdodCA9IGNvbXB1dGVkU3R5bGVzLmZvbnRXZWlnaHQ7XG4gICAgfVxuXG4gICAgc3BhbkVsZW1lbnQudGV4dENvbnRlbnQgPSBlbGVtZW50LnRleHRDb250ZW50O1xuXG4gICAgaWYgKHBhcmVudEVsZW1lbnQudGFnTmFtZSA9PT0gXCJVXCIpIHtcbiAgICAgIGVsZW1lbnQucGFyZW50RWxlbWVudC5zdHlsZS5jb2xvciA9IGNvbXB1dGVkU3R5bGVzLmNvbG9yO1xuICAgIH1cblxuICAgIGlmIChlbGVtZW50KSB7XG4gICAgICBlbGVtZW50LnBhcmVudEVsZW1lbnQucmVwbGFjZUNoaWxkKHNwYW5FbGVtZW50LCBlbGVtZW50KTtcbiAgICB9XG4gIH0gZWxzZSBpZiAoZWxlbWVudC5ub2RlVHlwZSA9PT0gTm9kZS5FTEVNRU5UX05PREUpIHtcbiAgICAvLyBJZiB0aGUgY3VycmVudCBub2RlIGlzIGFuIGVsZW1lbnQgbm9kZSwgcmVjdXJzaXZlbHkgcHJvY2VzcyBpdHMgY2hpbGQgbm9kZXNcbiAgICBjb25zdCBjaGlsZE5vZGVzID0gZWxlbWVudC5jaGlsZE5vZGVzO1xuICAgIGZvciAobGV0IGkgPSAwOyBpIDwgY2hpbGROb2Rlcy5sZW5ndGg7IGkrKykge1xuICAgICAgY29weUNvbG9yU3R5bGVUb1RleHROb2RlcyhjaGlsZE5vZGVzW2ldIGFzIEVsZW1lbnQpO1xuICAgIH1cbiAgfVxufVxuXG5leHBvcnQgZnVuY3Rpb24gY29weVBhcmVudENvbG9yVG9DaGlsZChub2RlOiBFbGVtZW50KSB7XG4gIG5vZGUuY2hpbGROb2Rlcy5mb3JFYWNoKChjaGlsZCkgPT4ge1xuICAgIGNvcHlDb2xvclN0eWxlVG9UZXh0Tm9kZXMoY2hpbGQgYXMgRWxlbWVudCk7XG4gIH0pO1xuXG4gIHJldHVybiBub2RlO1xufVxuIiwgImV4cG9ydCBjb25zdCByZWN1cnNpdmVHZXROb2RlcyA9IChub2RlOiBFbGVtZW50KTogQXJyYXk8RWxlbWVudD4gPT4ge1xuICBsZXQgbm9kZXM6IEFycmF5PEVsZW1lbnQ+ID0gW107XG4gIGlmIChub2RlLm5vZGVUeXBlID09PSBOb2RlLlRFWFRfTk9ERSkge1xuICAgIC8vIEZvdW5kIGEgdGV4dCBub2RlLCByZWNvcmQgaXRzIGZpcnN0IHBhcmVudCBlbGVtZW50XG4gICAgbm9kZS5wYXJlbnRFbGVtZW50ICYmIG5vZGVzLnB1c2gobm9kZS5wYXJlbnRFbGVtZW50KTtcbiAgfSBlbHNlIHtcbiAgICBmb3IgKGxldCBpID0gMDsgaSA8IG5vZGUuY2hpbGROb2Rlcy5sZW5ndGg7IGkrKykge1xuICAgICAgY29uc3QgY2hpbGQgPSBub2RlLmNoaWxkTm9kZXNbaV07XG4gICAgICAvLyBSZWN1cnNpdmVseSBzZWFyY2ggY2hpbGQgbm9kZXMgYW5kIGFkZCB0aGVpciByZXN1bHRzIHRvIHRoZSByZXN1bHQgYXJyYXlcbiAgICAgIGlmIChjaGlsZCkge1xuICAgICAgICBub2RlcyA9IG5vZGVzLmNvbmNhdChyZWN1cnNpdmVHZXROb2RlcyhjaGlsZCBhcyBFbGVtZW50KSk7XG4gICAgICB9XG4gICAgfVxuICB9XG4gIHJldHVybiBub2Rlcztcbn07XG4iLCAiaW1wb3J0IHsgZ2V0Tm9kZVN0eWxlIH0gZnJvbSBcIi4vZ2V0Tm9kZVN0eWxlXCI7XG5pbXBvcnQgeyByZWN1cnNpdmVHZXROb2RlcyB9IGZyb20gXCIuL3JlY3Vyc2l2ZUdldE5vZGVzXCI7XG5pbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcIkAvdHlwZXNcIjtcblxuZXhwb3J0IGZ1bmN0aW9uIGV4dHJhY3RBbGxFbGVtZW50c1N0eWxlcyhcbiAgbm9kZTogRWxlbWVudFxuKTogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4ge1xuICBjb25zdCBub2RlcyA9IHJlY3Vyc2l2ZUdldE5vZGVzKG5vZGUpO1xuICByZXR1cm4gbm9kZXMucmVkdWNlKChhY2MsIGVsZW1lbnQpID0+IHtcbiAgICBjb25zdCBzdHlsZXMgPSBnZXROb2RlU3R5bGUoZWxlbWVudCk7XG5cbiAgICAvLyBUZXh0LUFsaWduIGFyZSB3cm9uZyBmb3IgSW5saW5lIEVsZW1lbnRzXG4gICAgaWYgKHN0eWxlc1tcImRpc3BsYXlcIl0gPT09IFwiaW5saW5lXCIpIHtcbiAgICAgIGRlbGV0ZSBzdHlsZXNbXCJ0ZXh0LWFsaWduXCJdO1xuICAgIH1cblxuICAgIHJldHVybiB7IC4uLmFjYywgLi4uc3R5bGVzIH07XG4gIH0sIHt9KTtcbn1cbiIsICJpbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcInV0aWxzXCI7XG5pbXBvcnQgeyBleHRyYWN0QWxsRWxlbWVudHNTdHlsZXMgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9leHRyYWN0QWxsRWxlbWVudHNTdHlsZXNcIjtcbmltcG9ydCB7IGdldE5vZGVTdHlsZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldE5vZGVTdHlsZVwiO1xuXG5leHBvcnQgZnVuY3Rpb24gbWVyZ2VTdHlsZXMoZWxlbWVudDogRWxlbWVudCk6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+IHtcbiAgY29uc3QgZWxlbWVudFN0eWxlcyA9IGdldE5vZGVTdHlsZShlbGVtZW50KTtcblxuICAvLyBUZXh0LUFsaWduIGFyZSB3cm9uZyBmb3IgSW5saW5lIEVsZW1lbnRzXG4gIGlmIChlbGVtZW50U3R5bGVzW1wiZGlzcGxheVwiXSA9PT0gXCJpbmxpbmVcIikge1xuICAgIGRlbGV0ZSBlbGVtZW50U3R5bGVzW1widGV4dC1hbGlnblwiXTtcbiAgfVxuXG4gIGNvbnN0IGlubmVyU3R5bGVzID0gZXh0cmFjdEFsbEVsZW1lbnRzU3R5bGVzKGVsZW1lbnQpO1xuXG4gIHJldHVybiB7XG4gICAgLi4uZWxlbWVudFN0eWxlcyxcbiAgICAuLi5pbm5lclN0eWxlcyxcbiAgICBcImxpbmUtaGVpZ2h0XCI6IGVsZW1lbnRTdHlsZXNbXCJsaW5lLWhlaWdodFwiXVxuICB9O1xufVxuIiwgImltcG9ydCB7XG4gIGV4Y2VwdEV4dHJhY3RpbmdTdHlsZSxcbiAgc2hvdWxkRXh0cmFjdEVsZW1lbnRcbn0gZnJvbSBcIkAvVGV4dC91dGlscy9jb21tb25cIjtcbmltcG9ydCB7IG1lcmdlU3R5bGVzIH0gZnJvbSBcIkAvVGV4dC91dGlscy9zdHlsZXMvbWVyZ2VTdHlsZXNcIjtcbmltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwidXRpbHNcIjtcblxuaW50ZXJmYWNlIE91dHB1dCB7XG4gIHVpZDogc3RyaW5nO1xuICB0YWdOYW1lOiBzdHJpbmc7XG4gIHN0eWxlczogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD47XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBleHRyYWN0UGFyZW50RWxlbWVudHNXaXRoU3R5bGVzKG5vZGU6IEVsZW1lbnQpOiBBcnJheTxPdXRwdXQ+IHtcbiAgbGV0IHJlc3VsdDogQXJyYXk8T3V0cHV0PiA9IFtdO1xuXG4gIGlmIChzaG91bGRFeHRyYWN0RWxlbWVudChub2RlLCBleGNlcHRFeHRyYWN0aW5nU3R5bGUpKSB7XG4gICAgY29uc3QgdWlkID0gYHVpZC0ke01hdGgucmFuZG9tKCl9LSR7TWF0aC5yYW5kb20oKX1gO1xuICAgIG5vZGUuc2V0QXR0cmlidXRlKFwiZGF0YS11aWRcIiwgdWlkKTtcblxuICAgIHJlc3VsdC5wdXNoKHtcbiAgICAgIHVpZCxcbiAgICAgIHRhZ05hbWU6IG5vZGUudGFnTmFtZSxcbiAgICAgIHN0eWxlczogbWVyZ2VTdHlsZXMobm9kZSlcbiAgICB9KTtcbiAgfVxuXG4gIGZvciAobGV0IGkgPSAwOyBpIDwgbm9kZS5jaGlsZE5vZGVzLmxlbmd0aDsgaSsrKSB7XG4gICAgbGV0IGNoaWxkID0gbm9kZS5jaGlsZE5vZGVzW2ldO1xuICAgIHJlc3VsdCA9IHJlc3VsdC5jb25jYXQoZXh0cmFjdFBhcmVudEVsZW1lbnRzV2l0aFN0eWxlcyhjaGlsZCBhcyBFbGVtZW50KSk7XG4gIH1cblxuICByZXR1cm4gcmVzdWx0O1xufVxuIiwgImltcG9ydCB7IGV4dHJhY3RQYXJlbnRFbGVtZW50c1dpdGhTdHlsZXMgfSBmcm9tIFwiLi4vZG9tL2V4dHJhY3RQYXJlbnRFbGVtZW50c1dpdGhTdHlsZXNcIjtcbmltcG9ydCB7IGV4dHJhY3RlZEF0dHJpYnV0ZXMgfSBmcm9tIFwiQC9UZXh0L3V0aWxzL2NvbW1vblwiO1xuaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCJ1dGlsc1wiO1xuXG5pbnRlcmZhY2UgT3V0cHV0IHtcbiAgdWlkOiBzdHJpbmc7XG4gIHRhZ05hbWU6IHN0cmluZztcbiAgc3R5bGVzOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPjtcbn1cblxuZXhwb3J0IGNvbnN0IGdldFR5cG9ncmFwaHlTdHlsZXMgPSAobm9kZTogRWxlbWVudCk6IEFycmF5PE91dHB1dD4gPT4ge1xuICBjb25zdCBhbGxSaWNoVGV4dEVsZW1lbnRzID0gZXh0cmFjdFBhcmVudEVsZW1lbnRzV2l0aFN0eWxlcyhub2RlKTtcbiAgcmV0dXJuIGFsbFJpY2hUZXh0RWxlbWVudHMubWFwKChlbGVtZW50KSA9PiB7XG4gICAgY29uc3QgeyBzdHlsZXMgfSA9IGVsZW1lbnQ7XG5cbiAgICByZXR1cm4ge1xuICAgICAgLi4uZWxlbWVudCxcbiAgICAgIHN0eWxlczogZXh0cmFjdGVkQXR0cmlidXRlcy5yZWR1Y2UoKGFjYywgYXR0cmlidXRlKSA9PiB7XG4gICAgICAgIGFjY1thdHRyaWJ1dGVdID0gc3R5bGVzW2F0dHJpYnV0ZV07XG4gICAgICAgIHJldHVybiBhY2M7XG4gICAgICB9LCB7fSBhcyBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPilcbiAgICB9O1xuICB9KTtcbn07XG4iLCAiaW1wb3J0IHsgc3R5bGVzVG9DbGFzc2VzIH0gZnJvbSBcIkAvVGV4dC9tb2RlbHMvVGV4dFwiO1xuaW1wb3J0IHsgcmVtb3ZlQWxsQnV0dG9ucyB9IGZyb20gXCJAL1RleHQvdXRpbHMvYnV0dG9uc1wiO1xuaW1wb3J0IHsgcmVtb3ZlQWxsU3R5bGVzRnJvbUhUTUwgfSBmcm9tIFwiQC9UZXh0L3V0aWxzL2RvbS9yZW1vdmVBbGxTdHlsZXNGcm9tSFRNTFwiO1xuaW1wb3J0IHsgdHJhbnNmb3JtRGl2c1RvUGFyYWdyYXBocyB9IGZyb20gXCJAL1RleHQvdXRpbHMvZG9tL3RyYW5zZm9ybURpdnNUb1BhcmFncmFwaHNcIjtcbmltcG9ydCB7IHJlbW92ZUFsbEVtYmVkcyB9IGZyb20gXCJAL1RleHQvdXRpbHMvZW1iZWRzXCI7XG5pbXBvcnQgeyByZW1vdmVBbGxJY29ucyB9IGZyb20gXCJAL1RleHQvdXRpbHMvaWNvbnNcIjtcbmltcG9ydCB7IGNvcHlQYXJlbnRDb2xvclRvQ2hpbGQgfSBmcm9tIFwiQC9UZXh0L3V0aWxzL3N0eWxlcy9jb3B5UGFyZW50Q29sb3JUb0NoaWxkXCI7XG5pbXBvcnQgeyBnZXRUeXBvZ3JhcGh5U3R5bGVzIH0gZnJvbSBcIkAvVGV4dC91dGlscy9zdHlsZXMvZ2V0VHlwb2dyYXBoeVN0eWxlc1wiO1xuaW1wb3J0IHsgRWxlbWVudE1vZGVsLCBFbnRyeSwgT3V0cHV0IH0gZnJvbSBcIkAvdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCJ1dGlsc1wiO1xuaW1wb3J0IHsgdXVpZCB9IGZyb20gXCJ1dGlscy9zcmMvdXVpZFwiO1xuXG5pbnRlcmZhY2UgRGF0YSB7XG4gIG5vZGU6IEVsZW1lbnQ7XG4gIGZhbWlsaWVzOiBSZWNvcmQ8c3RyaW5nLCBzdHJpbmc+O1xuICBkZWZhdWx0RmFtaWx5OiBzdHJpbmc7XG4gIHN0eWxlczogQXJyYXk8e1xuICAgIHVpZDogc3RyaW5nO1xuICAgIHRhZ05hbWU6IHN0cmluZztcbiAgICBzdHlsZXM6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+O1xuICB9Pjtcbn1cblxuY29uc3QgdG9CdWlsZGVyVGV4dCA9IChkYXRhOiBEYXRhKTogc3RyaW5nID0+IHtcbiAgY29uc3QgeyBub2RlLCBzdHlsZXMsIGZhbWlsaWVzLCBkZWZhdWx0RmFtaWx5IH0gPSBkYXRhO1xuXG4gIHN0eWxlcy5tYXAoKHN0eWxlKSA9PiB7XG4gICAgY29uc3QgY2xhc3NlcyA9IHN0eWxlc1RvQ2xhc3NlcyhzdHlsZS5zdHlsZXMsIGZhbWlsaWVzLCBkZWZhdWx0RmFtaWx5KTtcbiAgICBjb25zdCBzdHlsZU5vZGUgPSBub2RlLnF1ZXJ5U2VsZWN0b3IoYFtkYXRhLXVpZD0nJHtzdHlsZS51aWR9J11gKTtcblxuICAgIGlmIChzdHlsZU5vZGUpIHtcbiAgICAgIHN0eWxlTm9kZS5jbGFzc0xpc3QuYWRkKC4uLmNsYXNzZXMpO1xuICAgICAgc3R5bGVOb2RlLnJlbW92ZUF0dHJpYnV0ZShcImRhdGEtdWlkXCIpO1xuICAgIH1cbiAgfSk7XG5cbiAgcmV0dXJuIG5vZGUuaW5uZXJIVE1MO1xufTtcblxuZXhwb3J0IGNvbnN0IGdldFRleHQgPSAoZGF0YTogRW50cnkpOiBPdXRwdXQgPT4ge1xuICBsZXQgbm9kZSA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoZGF0YS5zZWxlY3Rvcik7XG5cbiAgaWYgKCFub2RlKSB7XG4gICAgcmV0dXJuIEpTT04uc3RyaW5naWZ5KHtcbiAgICAgIGVycm9yOiBgRWxlbWVudCB3aXRoIHNlbGVjdG9yICR7ZGF0YS5zZWxlY3Rvcn0gbm90IGZvdW5kYFxuICAgIH0pO1xuICB9XG5cbiAgbm9kZSA9IG5vZGUuY2hpbGRyZW5bMF07XG5cbiAgaWYgKCFub2RlKSB7XG4gICAgcmV0dXJuIEpTT04uc3RyaW5naWZ5KHtcbiAgICAgIGVycm9yOiBgRWxlbWVudCB3aXRoIHNlbGVjdG9yICR7ZGF0YS5zZWxlY3Rvcn0gaGFzIG5vIHdyYXBwZXJgXG4gICAgfSk7XG4gIH1cblxuICBjb25zdCBlbGVtZW50czogQXJyYXk8RWxlbWVudE1vZGVsPiA9IFtdO1xuXG4gIG5vZGUgPSByZW1vdmVBbGxJY29ucyhub2RlKTtcblxuICBub2RlID0gcmVtb3ZlQWxsQnV0dG9ucyhub2RlKTtcblxuICBjb25zdCBlbWJlZHMgPSByZW1vdmVBbGxFbWJlZHMobm9kZSk7XG5cbiAgbm9kZSA9IGVtYmVkcy5ub2RlO1xuXG4gIG5vZGUgPSB0cmFuc2Zvcm1EaXZzVG9QYXJhZ3JhcGhzKG5vZGUpO1xuXG4gIG5vZGUgPSBjb3B5UGFyZW50Q29sb3JUb0NoaWxkKG5vZGUpO1xuXG4gIG5vZGUgPSByZW1vdmVBbGxTdHlsZXNGcm9tSFRNTChub2RlKTtcblxuICBjb25zdCBkYXRhVGV4dCA9IHtcbiAgICBub2RlOiBub2RlLFxuICAgIGZhbWlsaWVzOiBkYXRhLmZhbWlsaWVzLFxuICAgIGRlZmF1bHRGYW1pbHk6IGRhdGEuZGVmYXVsdEZhbWlseSxcbiAgICBzdHlsZXM6IGdldFR5cG9ncmFwaHlTdHlsZXMobm9kZSlcbiAgfTtcblxuICBlbGVtZW50cy5wdXNoKHtcbiAgICB0eXBlOiBcIlRleHRcIixcbiAgICB2YWx1ZToge1xuICAgICAgX2lkOiB1dWlkKCksXG4gICAgICB0ZXh0OiB0b0J1aWxkZXJUZXh0KGRhdGFUZXh0KVxuICAgIH1cbiAgfSk7XG5cbiAgcmV0dXJuIEpTT04uc3RyaW5naWZ5KHtcbiAgICBkYXRhOiBlbGVtZW50c1xuICB9KTtcbn07XG4iLCAiaW1wb3J0IHsgRW50cnksIE91dHB1dCwgT3V0cHV0RGF0YSB9IGZyb20gXCJAL3R5cGVzL3R5cGVcIjtcblxuZXhwb3J0IGNvbnN0IGdldERhdGEgPSAoKTogRW50cnkgPT4ge1xuICB0cnkge1xuICAgIHJldHVybiB7XG4gICAgICBzZWxlY3RvcjogXCJ7e3NlbGVjdG9yfX1cIixcbiAgICAgIGZhbWlsaWVzOiBKU09OLnBhcnNlKFwie3tmYW1pbGllc319XCIpLFxuICAgICAgZGVmYXVsdEZhbWlseTogXCJ7e2RlZmF1bHRGYW1pbHl9fVwiXG4gICAgfTtcbiAgfSBjYXRjaCAoZSkge1xuICAgIGNvbnN0IGZhbWlseU1vY2sgPSB7XG4gICAgICBsYXRvOiBcInVpZF9mb3JfbGF0b1wiLFxuICAgICAgcm9ib3RvOiBcInVpZF9mb3Jfcm9ib3RvXCJcbiAgICB9O1xuICAgIGNvbnN0IG1vY2s6IEVudHJ5ID0ge1xuICAgICAgc2VsZWN0b3I6IFwiLm15LWRpdlwiLFxuICAgICAgZmFtaWxpZXM6IGZhbWlseU1vY2ssXG4gICAgICBkZWZhdWx0RmFtaWx5OiBcImxhdG9cIlxuICAgIH07XG5cbiAgICB0aHJvdyBuZXcgRXJyb3IoXG4gICAgICBKU09OLnN0cmluZ2lmeSh7XG4gICAgICAgIGVycm9yOiBgSW52YWxpZCBKU09OICR7ZX1gLFxuICAgICAgICBkZXRhaWxzOiBgTXVzdCBiZTogJHtKU09OLnN0cmluZ2lmeShtb2NrKX1gXG4gICAgICB9KVxuICAgICk7XG4gIH1cbn07XG5cbmV4cG9ydCBjb25zdCBjcmVhdGVEYXRhID0gKG91dHB1dDogT3V0cHV0RGF0YSk6IE91dHB1dCA9PiB7XG4gIHJldHVybiBKU09OLnN0cmluZ2lmeShvdXRwdXQpO1xufTtcbiIsICJpbXBvcnQgeyBnZXRUZXh0IH0gZnJvbSBcImVsZW1lbnRzL3NyYy9UZXh0XCI7XG5pbXBvcnQgeyBnZXREYXRhfSBmcm9tIFwiZWxlbWVudHMvc3JjL3V0aWxzL2dldERhdGFcIjtcblxuY29uc3QgZGF0YSA9IGdldERhdGEoKTtcblxuZ2V0VGV4dChkYXRhKTtcbiJdLAogICJtYXBwaW5ncyI6ICI7OztBQUFPLFdBQVMsaUJBQWlCLE9BQXVCO0FBQ3RELFFBQUksVUFBVSxVQUFVO0FBQ3RCLGFBQU87QUFBQSxJQUNUO0FBR0EsVUFBTSxxQkFBcUIsTUFBTSxRQUFRLE9BQU8sRUFBRSxFQUFFLEtBQUs7QUFDekQsVUFBTSxDQUFDLGFBQWEsY0FBYyxHQUFHLElBQUksbUJBQW1CLE1BQU0sR0FBRztBQUNyRSxVQUFNLFlBQVksQ0FBQztBQUVuQixRQUFJLFlBQVksS0FBSyxPQUFPLEdBQUcsV0FBVyxFQUFFLEdBQUc7QUFDN0MsYUFBTyxPQUFPLENBQUMsWUFBWSxNQUFNLFlBQVksQ0FBQztBQUFBLElBQ2hEO0FBQ0EsV0FBTyxZQUFZLE1BQU0sWUFBWSxDQUFDO0FBQUEsRUFDeEM7OztBQ2RPLFdBQVMsY0FBYyxPQUFlLFVBQTBCO0FBQ3JFLFFBQUksVUFBVSxVQUFV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LEtBQUssU0FBUyxDQUFDO0FBRXRCLFFBQUksQ0FBQyxNQUFNO0FBQ1QsYUFBTyxLQUFLLFVBQVU7QUFBQSxRQUNwQixPQUFPLHlCQUF5QkEsTUFBSyxRQUFRO0FBQUEsTUFDL0MsQ0FBQztBQUFBLElBQ0g7QUFFQSxVQUFNLFdBQWdDLENBQUM7QUFFdkMsV0FBTyxlQUFlLElBQUk7QUFFMUIsV0FBTyxpQkFBaUIsSUFBSTtBQUU1QixVQUFNLFNBQVMsZ0JBQWdCLElBQUk7QUFFbkMsV0FBTyxPQUFPO0FBRWQsV0FBTywwQkFBMEIsSUFBSTtBQUVyQyxXQUFPLHVCQUF1QixJQUFJO0FBRWxDLFdBQU8sd0JBQXdCLElBQUk7QUFFbkMsVUFBTSxXQUFXO0FBQUEsTUFDZjtBQUFBLE1BQ0EsVUFBVUEsTUFBSztBQUFBLE1BQ2YsZUFBZUEsTUFBSztBQUFBLE1BQ3BCLFFBQVEsb0JBQW9CLElBQUk7QUFBQSxJQUNsQztBQUVBLGFBQVMsS0FBSztBQUFBLE1BQ1osTUFBTTtBQUFBLE1BQ04sT0FBTztBQUFBLFFBQ0wsS0FBSyxLQUFLO0FBQUEsUUFDVixNQUFNLGNBQWMsUUFBUTtBQUFBLE1BQzlCO0FBQUEsSUFDRixDQUFDO0FBRUQsV0FBTyxLQUFLLFVBQVU7QUFBQSxNQUNwQixNQUFNO0FBQUEsSUFDUixDQUFDO0FBQUEsRUFDSDs7O0FDeEZPLE1BQU0sVUFBVSxNQUFhO0FBQ2xDLFFBQUk7QUFDRixhQUFPO0FBQUEsUUFDTCxVQUFVO0FBQUEsUUFDVixVQUFVLEtBQUssTUFBTSxjQUFjO0FBQUEsUUFDbkMsZUFBZTtBQUFBLE1BQ2pCO0FBQUEsSUFDRixTQUFTLEdBQUc7QUFDVixZQUFNLGFBQWE7QUFBQSxRQUNqQixNQUFNO0FBQUEsUUFDTixRQUFRO0FBQUEsTUFDVjtBQUNBLFlBQU0sT0FBYztBQUFBLFFBQ2xCLFVBQVU7QUFBQSxRQUNWLFVBQVU7QUFBQSxRQUNWLGVBQWU7QUFBQSxNQUNqQjtBQUVBLFlBQU0sSUFBSTtBQUFBLFFBQ1IsS0FBSyxVQUFVO0FBQUEsVUFDYixPQUFPLGdCQUFnQixDQUFDO0FBQUEsVUFDeEIsU0FBUyxZQUFZLEtBQUssVUFBVSxJQUFJLENBQUM7QUFBQSxRQUMzQyxDQUFDO0FBQUEsTUFDSDtBQUFBLElBQ0Y7QUFBQSxFQUNGOzs7QUN4QkEsTUFBTSxPQUFPLFFBQVE7QUFFckIsVUFBUSxJQUFJOyIsCiAgIm5hbWVzIjogWyJ0ZXh0QWxpZ24iLCAidGV4dEFsaWduIiwgImFscGhhYmV0IiwgIm5vZGUiLCAibm9kZSIsICJ0ZXh0QWxpZ24iLCAicGFyZW50RWxlbWVudCIsICJkYXRhIl0KfQo=
