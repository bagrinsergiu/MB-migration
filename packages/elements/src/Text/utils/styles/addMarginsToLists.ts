const listMargins = (node: Element) => {
  const { marginTop, marginBottom } = window.getComputedStyle(node);

  if (!isNaN(parseFloat(marginTop))) {
    const parsedMarginTop = Math.round(parseFloat(marginTop));
    node.firstElementChild?.classList.add(`brz-mt-lg-${parsedMarginTop}`);
  }
  if (!isNaN(parseFloat(marginBottom))) {
    const parsedMarginBottom = Math.round(parseFloat(marginBottom));
    node.lastElementChild?.classList.add(`brz-mb-lg-${parsedMarginBottom}`);
  }
};

const listItemsColor = (node: Element) => {
  const listItems = Array.from(node.children) as HTMLElement[];

  listItems.forEach((listItem) => {
    const child = listItem.firstChild as HTMLElement;

    if (child.style?.color) {
      listItem.style.color = child.style.color;
    }
  });
};

const listStyles = (node: Element): void => {
  const allowedTags = ["UL", "OL"];
  if (allowedTags.includes(node.nodeName)) {
    listMargins(node);
    listItemsColor(node);
  } else if (node.nodeType === Node.ELEMENT_NODE) {
    const children = Array.from(node.children);

    for (node of children) {
      if (node.textContent?.trim()) {
        listStyles(node);
      }
    }
  }
  return;
};

export const addStylesToList = (node: Element) => {
  const children = Array.from(node.children);
  children.forEach((child) => {
    listStyles(child as Element);
  });
  return node;
};
