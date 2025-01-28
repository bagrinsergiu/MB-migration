const trimSpacesAtStartOfTag = (html: string) =>
  html.replace(
    /(<[^>]+>)\s{2,}([^<]*)(<\/[^>]+>)/g, // Match tags with two or more spaces at the start of the text content
    (_, openTag, textContent, closeTag) =>
      `${openTag}${textContent.trimStart()}${closeTag}` // Trim leading spaces if no nested tags
  );

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

  const content = node.innerHTML.replace(/\n/g, " ");
  node.innerHTML = trimSpacesAtStartOfTag(content);

  return node;
}
