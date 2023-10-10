const version = "v15.0.0";
const data = "{{data}}";
const allowedTags = ["P", "H1", "H2", "H3", "H4", "H5", "H6", "UL", "OL", "LI"];
const exceptExtractingStyle = ["UL", "OL"];

//#region Utils
const rrgbToHex = (rrgbString) => {
    function rgbToHex(r, g, b) {
        r = Math.min(255, Math.max(0, Math.round(r)));
        g = Math.min(255, Math.max(0, Math.round(g)));
        b = Math.min(255, Math.max(0, Math.round(b)));

        const hexR = r.toString(16).padStart(2, "0");
        const hexG = g.toString(16).padStart(2, "0");
        const hexB = b.toString(16).padStart(2, "0");

        return `#${hexR}${hexG}${hexB}`.toUpperCase();
    }
    const rgbValues = rrgbString
        .slice(4, -1)
        .split(",")
        .map((value) => parseInt(value.trim()));

    if (rgbValues.length !== 3) {
        return undefined;
    }

    return rgbToHex(rgbValues[0], rgbValues[1], rgbValues[2]);
};

const getStyle = (node) => {
    const computedStyles = window.getComputedStyle(node);
    const styles = {};

    for (const styleName of computedStyles) {
        styles[styleName] = computedStyles[styleName];
    }

    return styles;
};

const recursiveGetNodes = (node) => {
    let nodes = [];
    if (node.nodeType === Node.TEXT_NODE) {
        // Found a text node, record its first parent element
        nodes.push(node.parentElement);
    } else {
        for (let i = 0; i < node.childNodes.length; i++) {
            const child = node.childNodes[i];
            // Recursively search child nodes and add their results to the result array
            nodes = nodes.concat(recursiveGetNodes(child));
        }
    }
    return nodes;
};

function extractAllElementsStyles(node) {
    const nodes = recursiveGetNodes(node);
    return nodes.reduce((acc, element) => {
        const styles = getStyle(element);

        // Text-Align are wrong for Inline Elements
        if (styles["display"] === "inline") {
            delete styles["text-align"];
        }

        return { ...acc, ...styles };
    }, {});
}

function mergeStyles(element) {
    const elementStyles = getStyle(element);

    // Text-Align are wrong for Inline Elements
    if (elementStyles["display"] === "inline") {
        delete elementStyles["text-align"];
    }
    const innerStyles = extractAllElementsStyles(element);
    return { ...elementStyles, ...innerStyles };
}

function shouldExtractElement(element, exceptions) {
    const isAllowed = allowedTags.includes(element.tagName);

    if (isAllowed && exceptions) {
        return !exceptions.includes(element.tagName);
    }

    return isAllowed;
}

function getLetterSpacing(value) {
    if (value === "normal") {
        return "0";
    }

    // Remove 'px' and any extra whitespace
    const letterSpacingValue = value.replace(/px/g, "").trim();
    const [integerPart, decimalPart = "0"] = letterSpacingValue.split(".");
    const toNumberI = +integerPart;

    if (toNumberI < 0 || toNumberI === -0) {
        return "m_" + -toNumberI + "_" + decimalPart[0];
    }
    return toNumberI + "_" + decimalPart[0];
}

const cleanClassNames = (node) => {
    const classListExcepts = ["brz-"];
    const elementsWithClasses = node.querySelectorAll("[class]");

    elementsWithClasses.forEach(function (element) {
        element.classList.forEach((cls) => {
            if (!classListExcepts.some((except) => cls.startsWith(except))) {
                element.classList.remove(cls);
            }
        });

        if (element.classList.length === 0) {
            element.removeAttribute("class");
        }
    });
};

function findNearestBlockParent(element) {
    if (!element.parentElement) {
        return null;
    }

    const displayStyle = window.getComputedStyle(element.parentElement).display;
    const isBlockElement =
        displayStyle === "block" ||
        displayStyle === "flex" ||
        displayStyle === "grid";

    if (isBlockElement) {
        return element.parentElement;
    } else {
        return findNearestBlockParent(element.parentElement);
    }
}

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

    return index === 0
        ? "top"
        : index === totalSiblings - 1
            ? "bottom"
            : "middle";
}

let warns = {};
let embeds = {};
let iconsPositions = [];
let buttonsPositions = [];

const textAlign = {
    "-webkit-center": "center",
    "-moz-center": "center",
    start: "left",
    end: "right",
    left: "left",
    right: "right",
    center: "center",
    justify: "justify",
};

function getButtonModel(style, node) {
    const isLink = node.tagName === "A";

    return {
        bgColorHex: rrgbToHex(style["background-color"]) ?? "#ffffff",
        bgColorOpacity: +style.opacity,
        bgColorType: "solid",
        colorHex: rrgbToHex(style.color) ?? "#ffffff",
        colorOpacity: 1,
        text: node.text,
        ...(isLink && {
            linkExternal: node.href ?? "",
            linkType: "external",
            linkExternalBlank: "on",
        }),
    };
}

function getIconModelStyle(style, node) {
    const parentElement = node.parentElement;
    const isLink = parentElement.tagName === "A";

    return {
        colorHex: rrgbToHex(style.color) ?? "#ffffff",
        colorOpacity: +style.opacity,
        ...(isLink && {
            linkExternal: parentElement.href ?? "",
            linkType: "external",
            linkExternalBlank: "on",
        }),
    };
}

const stylesToClasses = (styles, families, defaultFontFamily) => {
    const classes = [];

    Object.entries(styles).forEach(([key, value]) => {
        switch (key) {
            case "font-size": {
                const size = Math.round(parseInt(value));
                classes.push(`brz-fs-lg-${size}`);
                break;
            }
            case "font-family":
                const fontFamily = value
                    .replace(/['"\,]/g, "")
                    .replace(/\s/g, "_")
                    .toLocaleLowerCase();

                if (!families[fontFamily]) {
                    warns[fontFamily] = {
                        message: `Font family not found ${fontFamily}`,
                    };
                    classes.push(`brz-ff-${defaultFontFamily}`, "brz-ft-upload");
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
                const letterSpacing = getLetterSpacing(value);
                classes.push(`brz-ls-lg-${letterSpacing}`);
                break;
            default:
                break;
        }
    });

    return classes;
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
    const icons = node.querySelectorAll("[data-socialicon]");
    let iconGroups = new Map();

    icons.forEach((icon) => {
        const position = getElementPositionAmongSiblings(icon, node);
        const style = getStyle(icon);
        const parentElement = findNearestBlockParent(icon);
        const model = getIconModelStyle(style, icon);

        const group = iconGroups.get(parentElement) ?? { items: [] };
        iconGroups.set(parentElement, {
            ...group,
            position,
            align: textAlign[style["text-align"]],
            items: [...group.items, model],
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
            items: [...group.items, model],
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
            styles: mergeStyles(node),
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
            element.parentElement.innerHTML = element.parentElement.innerHTML.trim();
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
        spanElement.textContent = element.textContent.trim();
        element.parentElement.replaceChild(spanElement, element);
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
            }, {}),
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
            warns: warns,
        });
    }

    node = node.children[0];

    if (!node) {
        return JSON.stringify({
            error: `Element with selector ${data.selector} has no wrapper`,
            warns,
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
        styles: getTypographyStyles({ node: node, attributes: data.attributes }),
    };

    return JSON.stringify({
        text: toBuilderText(dataText),
        warns: warns,
        embeds: embeds,
        icons: iconsPositions,
        buttons: buttonsPositions,
    });
};

run(JSON.parse(data));