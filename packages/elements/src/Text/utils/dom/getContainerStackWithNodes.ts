import {
  allowedTags,
  buttonSelector,
  embedSelector,
  iconSelector,
  imageSelector
} from "../common";
import { appendNodeStyles } from "./appendNodeStyles";
import { trimTextContent } from "./trimTextContent";

export class Stack {
  collection: Array<Element> = [];

  append(node: Element | Node, attr?: Record<string, string>) {
    const div = document.createElement("div");
    div.append(node);

    if (attr) {
      Object.entries(attr).forEach(([name, value]) => {
        div.setAttribute(`data-${name}`, value);
      });
    }

    this.collection.push(div);
  }

  set(node: Element, attr?: Record<string, string>) {
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
}

interface Container {
  container: Element;
  destroy: () => void;
}

const extractInnerText = (
  node: Node,
  stack: Stack,
  selector: string,
  styles: Record<string, string> = {}
): void => {
  const _node = node.cloneNode(true);

  if (_node instanceof HTMLElement) {
    const innerElements = _node.querySelectorAll(selector);

    if (innerElements.length > 0) {
      innerElements.forEach((el) => {
        el.tagName !== "BR" && el.remove();
      });
    }
    // Extract the other html without Artifacts like Button, Icons
    const text = trimTextContent(_node);

    if (text) {
      let appendedItem = _node;
      if (_node.tagName !== "P") {
        const container = document.createElement("p");

        Object.entries(styles).forEach(([key, value]) => {
          (container.style as unknown as Record<string, string>)[key] = value;
        });

        container.append(_node.cloneNode(true));
        appendedItem = container;
      }

      stack.append(appendedItem, { type: "text" });
    }
  }
};

function removeNestedDivs(node: HTMLElement) {
  const embeddedPasteExists = node.querySelectorAll(embedSelector).length > 0;

  if (!embeddedPasteExists) {
    Array.from(node.childNodes).forEach((child) => {
      if (
        child instanceof HTMLElement &&
        (child.nodeName === "DIV" || child.nodeName === "CENTER")
      ) {
        removeNestedDivs(child);
        // in case if there is no div or p inside of node should stop flattening
        const tagsToFlatten = ["DIV", "P"];
        const hasDivOrPChildren = Array.from(child.children).find((node) =>
          tagsToFlatten.includes(node.nodeName)
        );
        if (!hasDivOrPChildren) return;

        // insert granchild to child parent node and remove child
        Array.from(child.childNodes).forEach((grandchild) => {
          if (grandchild instanceof HTMLElement) {
            appendNodeStyles(grandchild);

            node.insertBefore(grandchild, child);
          } else if (trimTextContent(grandchild)) {
            const containerOfNode = document.createElement("div");
            appendNodeStyles(child, containerOfNode);
            containerOfNode.append(grandchild);

            node.insertBefore(containerOfNode, child);
          }
        });

        node.removeChild(child);
      }
    });
  }
}

function appendNodeStylesToDivsWithoutStyles(node: HTMLElement) {
  node.querySelectorAll("div").forEach((div) => {
    if (div.style.cssText === "") {
      appendNodeStyles(div);
    }
  });
}

const copyClassList = (
  sourceElement: HTMLElement,
  targetElement: HTMLElement
) => {
  sourceElement.classList.forEach((className) => {
    targetElement.classList.add(className);
  });
};

const flattenNode = (node: Element) => {
  let _node = node.cloneNode(true) as HTMLElement;
  const parentElement = node.parentElement;

  if (_node.tagName === "A" && parentElement) {
    const parentWrapper = document.createElement("div");
    const wrapper = document.createElement("p");
    parentWrapper.appendChild(wrapper);
    const btn = _node.querySelector("button");

    if (btn) {
      copyClassList(btn, _node);
      _node.innerHTML = btn.innerHTML;
      btn.remove();
    }

    wrapper.appendChild(_node);
    _node = parentWrapper;
    parentElement.appendChild(_node);
  } else {
    parentElement?.append(_node);
  }

  removeNestedDivs(_node);
  appendNodeStylesToDivsWithoutStyles(_node);

  _node.remove();

  return _node;
};

const removeWrongElements = (node: HTMLElement) => {
  const wrongSelectors = ["style", ".sr-only"];

  wrongSelectors.forEach((selector) => {
    const elements = node.querySelectorAll(selector);
    elements.forEach((element) => {
      element.remove();
    });
  });
};

const replaceWrongTags = (node: HTMLElement) => {
  const wrongTags = ["font", "blockquote", "table", "tbody", "tr", "td"];
  const replaceElements = node.querySelectorAll<HTMLElement>(
    wrongTags.join(", ")
  );

  replaceElements.forEach((element) => {
    const isFont = element.tagName === "FONT";

    const newElement = isFont
      ? document.createElement("span")
      : document.createElement("div");

    appendNodeStyles(element, newElement);
    newElement.innerHTML = element.innerHTML;

    if (isFont) {
      newElement.style.color = element.getAttribute("color") ?? "";
    }

    element.parentNode?.replaceChild(newElement, element);

    replaceWrongTags(newElement);
  });
};

function containsOnlyIconsAndButtons(node: Node): boolean {
  const textLikeTags = ["SPAN", "P", "A", "H1", "H2", "H3", "H4", "H5", "H6"];

  return Array.from(node.childNodes).every((child) => {
    if (!(child instanceof Element)) return false;

    const textLikeChildren = Array.from(child.childNodes).filter(
      (n) => n instanceof Element && textLikeTags.includes(n.tagName)
    );

    const iconsAndButtonsCount = child.querySelectorAll(
      [iconSelector, buttonSelector].join(",")
    ).length;

    return iconsAndButtonsCount === textLikeChildren.length;
  });
}

const getImageSizes = (node: Element) => {
  const images = node.querySelectorAll(imageSelector);

  return images.forEach((image) => {
    const { width, height } = image.getBoundingClientRect();
    image.setAttribute("width", width.toString());
    image.setAttribute("height", height.toString());
  });
};

export const getContainerStackWithNodes = (element: Element): Container => {
  const container = document.createElement("div");
  const stack = new Stack();
  let appendNewText = false;

  const parentNode = element.parentElement;

  if (element.tagName === "BUTTON" && parentNode) {
    if (parentNode.tagName === "A") {
      element = parentNode;
    } else {
      const wrapper = document.createElement("div");
      parentNode.replaceChild(wrapper, element);
      wrapper.appendChild(element);
      element = wrapper;
    }
  } else {
    element = element.children[0];
  }

  if (element instanceof HTMLElement) {
    removeWrongElements(element);
    replaceWrongTags(element);
  }

  getImageSizes(element); // We should get sizes before flattening, because after flattening we can't get sizes of images

  const flatNode = flattenNode(element);

  flatNode.childNodes.forEach((node) => {
    const _node = node.cloneNode(true);
    const containerOfNode = document.createElement("div");
    containerOfNode.append(_node);

    // For list elements [UL, OL], exclude icons & buttons unless the list contains only them
    const isList =
      _node instanceof HTMLOListElement || _node instanceof HTMLUListElement;

    const excludeIcons = isList && !containsOnlyIconsAndButtons(_node);

    if (_node instanceof HTMLElement) {
      const icons = containerOfNode.querySelectorAll(iconSelector);
      const buttons = containerOfNode.querySelectorAll(buttonSelector);
      const images = containerOfNode.querySelectorAll(imageSelector);

      if (excludeIcons) {
        icons.forEach((node) => {
          node.remove();
        });
        buttons.forEach((node) => {
          node.remove();
        });
      } else {
        if (containerOfNode.querySelector(embedSelector)) {
          appendNewText = true;
          stack.append(_node, { type: "embed" });
          return;
        }
        // Check the button first because
        // inside button can be icons
        if (buttons.length > 0) {
          if (_node.tagName === "BUTTON") {
            appendNewText = true;
            return stack.append(_node, { type: "button" });
          }
          // check for non empty nodes which are not inside buttons
          const container = document.createElement("div");
          container.innerHTML = _node.innerHTML;

          const innerButtons = container.querySelectorAll(buttonSelector);
          innerButtons.forEach((btn) => btn.remove());

          const onlyButtons = (trimTextContent(container) ?? "").length === 0;

          if (onlyButtons) {
            appendNewText = true;
            let appendedButton = false;
            element.parentElement?.append(_node);

            _node.childNodes.forEach((node) => {
              if (node instanceof HTMLElement) {
                const container = document.createElement("div");
                container.append(node.cloneNode(true));
                appendNodeStyles(node);

                // if latest appended is icon, icons must be wrapped in same node
                if (appendedButton) {
                  stack.set(node);
                } else {
                  stack.append(node, { type: "button" });
                  appendedButton = true;
                }
              }
            });
            _node.remove();
            return;
          }
          _node.remove();
        }

        if (icons.length > 0) {
          appendNewText = true;
          let appendedIcon = false;
          let parentWasProcessed = false;

          Array.from(_node.childNodes).forEach((node) => {
            if (node instanceof HTMLElement) {
              const container = document.createElement("div");
              container.append(node.cloneNode(true));

              if (container.querySelector(iconSelector)) {
                element.parentElement?.append(_node);
                appendNodeStyles(node);

                Array.from(node.childNodes).forEach((child) => {
                  if (child.nodeType === Node.TEXT_NODE) {
                    const text = trimTextContent(child);

                    if (text) {
                      const textNode = document.createElement("p");

                      if (child.parentElement?.tagName === "A") {
                        const parent = child.parentElement;
                        const a = document.createElement("a");

                        for (let i = 0; i < parent.attributes.length; i++) {
                          const attr = parent.attributes[i];
                          a.setAttribute(attr.name, attr.value);
                        }

                        a.textContent = text;
                        textNode.append(a);
                      } else {
                        textNode.textContent = text;
                      }

                      appendNodeStyles(node, textNode);
                      stack.append(textNode, { type: "text" });
                      appendedIcon = false;
                    }
                  } else if (child instanceof Element) {
                    const parent = child.parentElement;

                    if (!parent) return;

                    // Clone parent to preserve its styles and structure
                    const wrapper = parent.cloneNode(false); // Clone only the element, not its children
                    wrapper.appendChild(child); // Move child into the cloned parent

                    if (child.childNodes.length > 1) {
                      child.childNodes.forEach((element) => {
                        const isElement = element instanceof Element;

                        // Check if element itself or its children contain an icon
                        const hasIcon =
                          isElement &&
                          (element.matches(iconSelector) ||
                            element.querySelector(iconSelector) ||
                            (wrapper as Element).matches(iconSelector));

                        if (hasIcon) {
                          if (appendedIcon) {
                            stack.set(wrapper as Element);
                          } else {
                            stack.append(wrapper, { type: "icon" });
                            appendedIcon = true;
                          }
                          return;
                        }

                        const childTextContent = trimTextContent(element);

                        const shouldAppendText =
                          !!childTextContent ||
                          (isElement &&
                            element.classList.contains("clovercustom"));

                        if (shouldAppendText) {
                          const clonedWrapper = wrapper.cloneNode(true);

                          if (clonedWrapper instanceof Element) {
                            clonedWrapper
                              .querySelectorAll(iconSelector)
                              .forEach((node) => node.remove());
                          }

                          let newWrapper = clonedWrapper;

                          if (!allowedTags.includes(clonedWrapper.nodeName)) {
                            newWrapper = document.createElement("p");
                            newWrapper.appendChild(clonedWrapper);
                          }

                          stack.append(newWrapper, { type: "text" });
                          appendedIcon = false;
                        }
                      });
                    } else {
                      const childTextContent = trimTextContent(child);

                      const isLinkWithoutIcon =
                        child.nodeName === "A" &&
                        !child.querySelector(iconSelector);

                      const shouldAppendText =
                        !!childTextContent &&
                        (child.classList.contains("clovercustom") ||
                          isLinkWithoutIcon);

                      if (shouldAppendText) {
                        let newWrapper = wrapper;

                        if (!allowedTags.includes(wrapper.nodeName)) {
                          newWrapper = document.createElement("p");
                          newWrapper.appendChild(wrapper);
                        }

                        stack.append(newWrapper, { type: "text" });
                        appendedIcon = false;
                        return;
                      }

                      // Final stack append if icon was processed and not yet handled
                      if (appendedIcon) {
                        stack.set(wrapper as Element);
                      } else {
                        stack.append(wrapper, { type: "icon" });
                        appendedIcon = true;
                      }
                    }
                  }
                });
              } else {
                const text = trimTextContent(node);
                const parentNode = node.parentElement;
                let clonedParent = null;

                let styles = getComputedStyle(node);

                // If parent isn't in the DOM, temporarily insert a clone to get styles
                if (parentNode && !document.contains(parentNode)) {
                  clonedParent = parentNode.cloneNode(true);

                  // Find the cloned version of our target node
                  const originalIndex = Array.from(parentNode.children).indexOf(
                    node
                  );

                  if (clonedParent instanceof Element) {
                    const clonedNode = clonedParent.children[originalIndex];

                    document.body.appendChild(clonedParent);

                    styles = getComputedStyle(clonedNode);
                  }
                }

                if (text) {
                  // Remove the inline font-size because it’s often set in `em` units, which can produce incorrect values due to inheritance from the parent
                  const fontSize = styles.fontSize;
                  node.style.removeProperty("font-size");

                  const inlineStyles: Record<string, string> = {
                    ...(styles.color ? { color: styles.color } : {}),
                    ...(fontSize ? { fontSize: fontSize } : {}),
                    ...(styles.fontWeight
                      ? { fontWeight: styles.fontWeight }
                      : {}),
                    ...(styles.fontFamily
                      ? { fontFamily: styles.fontFamily }
                      : {})
                  };

                  extractInnerText(node, stack, iconSelector, inlineStyles);

                  appendedIcon = false;
                }

                if (clonedParent) {
                  document.body.removeChild(clonedParent);
                }
              }
            } else if (node instanceof SVGElement) {
              appendNewText = true;

              stack.append(node.cloneNode(true), {
                type: "icon"
              });
            } else {
              const text = trimTextContent(node);

              if (text && !parentWasProcessed) {
                extractInnerText(_node, stack, "*:not(br)");
                parentWasProcessed = true;
              }
            }
          });
          _node.remove();
          return;
        }
      }

      if (images.length > 0) {
        appendNewText = true;
        images.forEach((image) => {
          const element =
            image.parentElement && image.parentElement.tagName === "A"
              ? image.parentElement
              : image;
          stack.append(element, { type: "image" });
        });
      }

      if (appendNewText) {
        appendNewText = false;
        stack.append(_node, { type: "text" });
      } else {
        stack.set(_node, { type: "text" });
      }
    } else {
      if (trimTextContent(_node)) {
        stack.append(_node, { type: "text" });
      }
    }
  });

  const allElements = stack.getAll();

  allElements.forEach((node) => {
    container.append(node);
  });

  element.parentElement?.append(container);

  const destroy = () => {
    container.remove();
  };

  return { container, destroy };
};
