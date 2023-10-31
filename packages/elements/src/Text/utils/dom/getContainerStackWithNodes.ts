import {
  buttonSelector,
  embedSelector,
  iconSelector
} from "@/Text/utils/common";

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

export const getContainerStackWithNodes = (node: Element): Container => {
  const container = document.createElement("div");
  const stack = new Stack();
  let appendNewText = false;

  node.childNodes.forEach((node) => {
    const _node = node.cloneNode(true);
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

  allElements.forEach((node) => {
    container.append(node);
  });

  node.parentElement?.append(container);

  const destroy = () => {
    container.remove();
  };

  return { container, destroy };
};
