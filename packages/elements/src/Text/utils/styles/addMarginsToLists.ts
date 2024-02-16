const listMargins = (node: Element): void => {
  const allowedTags = ["UL", "OL"];
  if (allowedTags.includes(node.nodeName)) {
    const { marginTop, marginBottom } = window.getComputedStyle(node);

    if (!isNaN(parseFloat(marginTop))) {
      const parsedMarginTop = Math.round(parseFloat(marginTop));
      node.firstElementChild?.classList.add(`brz-mt-lg-${parsedMarginTop}`);
    }
    if (!isNaN(parseFloat(marginBottom))) {
      const parsedMarginBottom = Math.round(parseFloat(marginBottom));
      node.lastElementChild?.classList.add(`brz-mb-lg-${parsedMarginBottom}`);
    }
  } else if (node.nodeType === Node.ELEMENT_NODE) {
    const children = Array.from(node.children);

    for (node of children) {
      if (node.textContent?.trim()) {
        listMargins(node);
      }
    }
  }
  return;
};

export const addMarginsToLists = (node: Element) => {
  const children = Array.from(node.children);
  children.forEach((child) => {
    listMargins(child as Element);
  });
  return node;
};
