export function removeEmptyNodes(node: Element): Element {
  const children = Array.from(node.children);

  children.forEach((child) => {
    const text = child.textContent;

    // Check if have only `\n` then remove it
    // when have <br> textContent is empty string ['']
    if (text && text.includes("\n") && !text.trim()) {
      child.remove();
    }
  });

  const content = node.innerHTML.replace(/\n/g, " "); // Replace all \n with space
  node.innerHTML = content.replace(/>\s{2,}/g, ">"); // Remove multiple spaces between tags

  return node;
}
