export function removeEmptyNodes(node: Element): Element {
  const children = Array.from(node.children);

  children.forEach((child) => {
    const haveText = child.textContent?.trim();

    if (!haveText) {
      child.remove();
    }
  });

  return node;
}
