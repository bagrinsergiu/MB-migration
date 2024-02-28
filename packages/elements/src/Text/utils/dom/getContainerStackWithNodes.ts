import {
  buttonSelector,
  embedSelector,
  extractedAttributes,
  iconSelector
} from "../common";

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

const extractInnerText = (node: Node, stack: Stack, selector: string): void => {
  const _node = node.cloneNode(true);

  if (_node instanceof HTMLElement) {
    const innerElements = _node.querySelectorAll(selector);

    if (innerElements.length > 0) {
      innerElements.forEach((el) => {
        el.remove();
      });
    }
    // Extract the other html without Artifacts like Button, Icons
    const text = _node.textContent;

    if (text && text.trim()) {
      stack.append(_node, { type: "text" });
    }
  }
};

function appendNodeStyles(node: HTMLElement, targetNode: HTMLElement) {
  const styles = window.getComputedStyle(node);
  extractedAttributes.forEach((style) => {
    targetNode.style.setProperty(style, styles.getPropertyValue(style));
  });
}

function removeNestedDivs(node: HTMLElement, cssText: string) {
  Array.from(node.childNodes).forEach((child) => {
    if (child instanceof HTMLElement && child.nodeName === "DIV") {
      removeNestedDivs(child, cssText);

      // in case if there is no div or p inside of node should stop flattening
      const tagsToFlatten = ["DIV", "P"];
      const hasDivOrPChildren = Array.from(child.children).find((node) =>
        tagsToFlatten.includes(node.nodeName)
      );
      if (!hasDivOrPChildren) return;

      // insert granchild to child parent node and remove child
      Array.from(child.childNodes).forEach((grandchild) => {
        if (grandchild instanceof HTMLElement) {
          appendNodeStyles(grandchild, grandchild);

          node.insertBefore(grandchild, child);
        } else if (grandchild.textContent?.trim()) {
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

const flattenNode = (node: Element) => {
  const _node = node.cloneNode(true) as HTMLElement;
  node.parentElement?.append(_node);

  removeNestedDivs(_node, _node.style.cssText);

  _node.remove();

  return _node;
};

export const getContainerStackWithNodes = (node: Element): Container => {
  const container = document.createElement("div");
  const stack = new Stack();
  let appendNewText = false;

  const flatNode = flattenNode(node);

  flatNode.childNodes.forEach((node) => {
    const _node = node.cloneNode(true);
    const containerOfNode = document.createElement("div");
    containerOfNode.append(_node);

    // Exclude extracting icons & button for [ UL, OL ]
    // Removed all icons & button inside [ UL, OL ]
    const excludeIcons =
      _node instanceof HTMLOListElement || _node instanceof HTMLUListElement;

    if (_node instanceof HTMLElement) {
      const icons = containerOfNode.querySelectorAll(iconSelector);
      const buttons = containerOfNode.querySelectorAll(buttonSelector);

      if (excludeIcons) {
        icons.forEach((node) => {
          node.remove();
        });
        buttons.forEach((node) => {
          node.remove();
        });
      } else {
        // Check the button first because
        // inside button can be icons
        if (buttons.length > 0) {
          appendNewText = true;
          let appendedButton = false;

          _node.childNodes.forEach((node) => {
            if (node instanceof HTMLElement) {
              const container = document.createElement("div");
              container.append(node.cloneNode(true));

              if (container.querySelector(buttonSelector)) {
                if (!appendedButton) {
                  stack.append(_node, { type: "button" });
                  appendedButton = true;
                }
              } else {
                const text = node.textContent;

                if (text?.trim()) {
                  extractInnerText(_node, stack, buttonSelector);
                }
              }
            } else {
              const text = node.textContent;

              if (text?.trim()) {
                extractInnerText(_node, stack, buttonSelector);
              }
            }
          });
          return;
        }
        if (icons.length > 0) {
          appendNewText = true;
          let appendedIcon = false;

          Array.from(_node.childNodes).forEach((node) => {
            if (node instanceof HTMLElement) {
              const container = document.createElement("div");
              container.append(node.cloneNode(true));

              if (container.querySelector(iconSelector)) {
                // if latest appended is icon, icons must be wrapped in same node
                if (appendedIcon) {
                  stack.set(node);
                } else {
                  stack.append(node, { type: "icon" });
                  appendedIcon = true;
                }
              } else {
                const text = node.textContent;

                if (text?.trim()) {
                  extractInnerText(_node, stack, iconSelector);
                  appendedIcon = false;
                }
              }
            } else {
              const text = node.textContent;

              if (text?.trim()) {
                extractInnerText(_node, stack, iconSelector);
              }
            }
          });
          return;
        }
      }

      if (containerOfNode.querySelector(embedSelector)) {
        appendNewText = true;
        extractInnerText(_node, stack, embedSelector);
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

  allElements.forEach((node) => {
    container.append(node);
  });

  node.parentElement?.append(container);

  const destroy = () => {
    container.remove();
  };

  return { container, destroy };
};
