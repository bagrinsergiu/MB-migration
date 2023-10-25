const data = "{{data}}";

let warns: Record<string, Record<string, string>> = {};
let embeds = {};
let iconsPositions = [];
let buttonsPositions = [];

const textAlign: Record<string, string> = {
  "-webkit-center": "center",
  "-moz-center": "center",
  start: "left",
  end: "right",
  left: "left",
  right: "right",
  center: "center",
  justify: "justify"
};

function removeStylesExceptFontWeightAndColor(htmlString) {
  // Create a temporary element
  var tempElement = document.createElement("div");

  // Set the HTML content of the temporary element
  tempElement.innerHTML = htmlString;

  // Find elements with inline styles
  var elementsWithStyles = tempElement.querySelectorAll("[style]");

  // Iterate through elements with styles
  elementsWithStyles.forEach(function (element) {
    // Get the inline style attribute
    var styleAttribute = element.getAttribute("style");

    // Split the inline style into individual properties
    var styleProperties = styleAttribute.split(";");

    // Initialize a new style string to retain only font-weight and color
    var newStyle = "";

    // Iterate through the style properties
    for (var i = 0; i < styleProperties.length; i++) {
      var property = styleProperties[i].trim();

      // Check if the property is font-weight or color
      if (property.startsWith("font-weight") || property.startsWith("color")) {
        newStyle += property + "; ";
      }
    }

    // Set the element's style attribute to retain only font-weight and color
    element.setAttribute("style", newStyle);
  });

  cleanClassNames(tempElement);
  // Return the cleaned HTML
  return tempElement.innerHTML;
}

function removeAllStylesFromHTML(node) {
  // Define the list of allowed tags

  // Find elements with inline styles only for allowed tags
  const elementsWithStyles = node.querySelectorAll(
    allowedTags.join(",") + "[style]"
  );
  const elementsWithClasses = node.querySelectorAll(
    allowedTags.join(",") + "[class]"
  );

  // Remove the "style" attribute from each element
  elementsWithStyles.forEach(function (element) {
    element.removeAttribute("style");
  });

  // Remove the "style" attribute from each element
  cleanClassNames(node);

  node.innerHTML = removeStylesExceptFontWeightAndColor(node.innerHTML);

  // Return the cleaned HTML
  return node;
}

function recursiveDeleteNodes(node) {
  const parentElement = node.parentElement;
  node.remove();

  if (parentElement?.childNodes.length === 0) {
    recursiveDeleteNodes(parentElement);
  }
}

function removeAllIcons(node) {
  const icons = node.querySelectorAll(
    "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"]"
  );
  let iconGroups = new Map();

  icons.forEach((icon) => {
    const position = getElementPositionAmongSiblings(icon, node);
    const parentElement = findNearestBlockParent(icon);
    const parentNode = getParentElementOfTextNode(icon);
    const isIconText = parentNode.nodeName === "#text";
    const iconNode = isIconText ? icon : parentNode;
    const style = getStyle(iconNode);

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

  iconGroups.forEach((button) => {
    iconsPositions.push(button);
  });

  icons.forEach(recursiveDeleteNodes);

  return node;
}

function removeAllButtons(node) {
  const buttons = node.querySelectorAll(".sites-button");
  let buttonGroups = new Map();

  buttons.forEach((button) => {
    const position = getElementPositionAmongSiblings(button, node);
    const parentElement = findNearestBlockParent(button);
    const style = getStyle(button);
    const model = getButtonModel(style, button);

    const group = buttonGroups.get(parentElement) ?? { items: [] };
    buttonGroups.set(parentElement, {
      ...group,
      position,
      align: textAlign[style["text-align"]],
      items: [...group.items, model]
    });
  });

  buttonGroups.forEach((button) => {
    buttonsPositions.push(button);
  });

  buttons.forEach(recursiveDeleteNodes);

  return node;
}

function removeAllEmbeds(node) {
  const nodes = node.querySelectorAll(".embedded-paste");

  nodes.forEach((node) => {
    embeds["persist"] = true;
    node.remove();
  });

  return node;
}

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

function transformDivsToParagraphs(containerElement) {
  // Get all the div elements within the container
  const divElements = containerElement.querySelectorAll("div");

  // Iterate through each div element
  divElements.forEach(function (divElement) {
    // Create a new paragraph element
    const paragraphElement = document.createElement("p");

    // Copy all attributes from the div to the paragraph
    for (let i = 0; i < divElement.attributes.length; i++) {
      const attr = divElement.attributes[i];
      paragraphElement.setAttribute(attr.name, attr.value);
    }

    // Transfer the content from the div to the paragraph
    paragraphElement.innerHTML = divElement.innerHTML;

    // Replace the div with the new paragraph element
    divElement.parentNode.replaceChild(paragraphElement, divElement);
  });

  return containerElement;
}

function copyColorStyleToTextNodes(element, attributes) {
  if (element.nodeType === Node.TEXT_NODE) {
    if (element.parentElement.tagName === "SPAN") {
      const parentOfParent = element.parentElement.parentElement;
      const parentElement = element.parentElement;
      const parentStyle = parentElement.style;

      if (
        attributes.includes("text-transform") &&
        !parentStyle?.textTransform
      ) {
        const style = getStyle(parentElement);
        if (style["text-transform"] === "uppercase") {
          parentElement.classList.add("brz-capitalize-on");
        }
      }

      if (!parentStyle?.color) {
        const parentOFParentStyle = getStyle(parentOfParent);
        parentElement.style.color = parentOFParentStyle.color;
      }
      if (!parentStyle?.fontWeight && parentOfParent.style?.fontWeight) {
        parentElement.style.fontWeight = parentOfParent.style.fontWeight;
      }

      if (parentOfParent.tagName === "SPAN") {
        const parentFontWeight = parentElement.style.fontWeight;
        parentElement.style.fontWeight =
          parentFontWeight || getComputedStyle(parentElement).fontWeight;
      }

      return;
    }

    const spanElement = document.createElement("span");
    const computedStyles = window.getComputedStyle(element.parentElement);

    if (
      attributes.includes("text-transform") &&
      computedStyles.textTransform === "uppercase"
    ) {
      spanElement.classList.add("brz-capitalize-on");
    }

    if (computedStyles.color) {
      spanElement.style.color = computedStyles.color;
    }

    if (computedStyles.fontWeight) {
      spanElement.style.fontWeight = computedStyles.fontWeight;
    }

    spanElement.textContent = element.textContent;

    if (element.parentElement.tagName === "U") {
      element.parentElement.style.color = computedStyles.color;
    }

    if (element) element.parentElement.replaceChild(spanElement, element);
  } else if (element.nodeType === Node.ELEMENT_NODE) {
    // If the current node is an element node, recursively process its child nodes
    var childNodes = element.childNodes;
    for (var i = 0; i < childNodes.length; i++) {
      copyColorStyleToTextNodes(childNodes[i], attributes);
    }
  }
}

function copyParentColorToChild(node, attributes) {
  node.childNodes.forEach((child) => {
    copyColorStyleToTextNodes(child, attributes);
  });

  return node;
}

//#endregion

//#region Extract Styles

const getTypographyStyles = (data) => {
  const { node, attributes } = data;

  const allRichTextElements = extractParentElementsWithStyles(node);

  return allRichTextElements.map((element) => {
    const { styles } = element;

    return {
      ...element,
      styles: attributes.reduce((acc, attribute) => {
        acc[attribute] = styles[attribute];
        return acc;
      }, {})
    };
  });
};

//#endregion

//#region Builder Text

const toBuilderText = (data) => {
  const { node, styles, families, defaultFontFamily } = data;
  const cleanText = removeAllStylesFromHTML(node);

  styles.map((style) => {
    const classes = stylesToClasses(style.styles, families, defaultFontFamily);
    const styleNode = node.querySelector(`[data-uid='${style.uid}']`);

    if (styleNode) {
      styleNode.classList.add(...classes);
      styleNode.removeAttribute("data-uid");
    }
  });

  return cleanText.innerHTML;
};

//#endregion

const run = (data) => {
  let node = document.querySelector(data.selector);

  if (!node) {
    return JSON.stringify({
      error: `Element with selector ${data.selector} not found`,
      warns: warns
    });
  }

  node = node.children[0];

  if (!node) {
    return JSON.stringify({
      error: `Element with selector ${data.selector} has no wrapper`,
      warns
    });
  }

  node = removeAllIcons(node);

  node = removeAllButtons(node);

  node = removeAllEmbeds(node);

  node = transformDivsToParagraphs(node);

  node = copyParentColorToChild(node, data.attributes);

  const dataText = {
    node: node,
    families: data.families,
    defaultFontFamily: data.defaultFontFamily,
    styles: getTypographyStyles({ node: node, attributes: data.attributes })
  };

  return JSON.stringify({
    text: toBuilderText(dataText),
    warns: warns,
    embeds: embeds,
    icons: iconsPositions,
    buttons: buttonsPositions
  });
};

// run(JSON.parse(data));
