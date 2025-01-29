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
    const child = listItem.children[0];

    if (!child) {
      return;
    }

    listItem.style.color = window.getComputedStyle(child).color;
  });
};

const removeParagraphsFromListAsFirstItem = (node: Element) => {
  // Get all LI elements from the list
  const list = Array.from(node.querySelectorAll("li"));

  list.forEach((node) => {
    // Get all P elements from the LI element
    const paragraphs = Array.from(node.children).filter(
      (element) => element.nodeName === "P"
    );

    paragraphs.forEach((paragraph) => {
      // Move each child of the paragraph to the parent node (before the paragraph)
      while (paragraph.firstChild) {
        node.insertBefore(paragraph.firstChild, paragraph);
      }
      // Remove the empty paragraph element
      node.removeChild(paragraph);
    });
  });
};

const listStyles = (node: Element): void => {
  const allowedTags = ["UL", "OL"];
  if (allowedTags.includes(node.nodeName)) {
    removeParagraphsFromListAsFirstItem(node);
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
