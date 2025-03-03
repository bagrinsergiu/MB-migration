const disallowedTags = ["SUP"];

export const recursiveGetNodes = (node: Element): Array<Element> => {
  let nodes: Array<Element> = [];
  if (node.nodeType === Node.TEXT_NODE) {
    // Found a text node, record its first parent element
    node.parentElement && nodes.push(node.parentElement);
  } else {
    for (let i = 0; i < node.childNodes.length; i++) {
      const child = node.childNodes[i];
      // Recursively search child nodes and add their results to the result array
      if (
        child.textContent?.trim() &&
        !disallowedTags.includes(child.nodeName) // The font size of subscript will override the parent font size
      ) {
        nodes = nodes.concat(recursiveGetNodes(child as Element));
      }
    }
  }
  return nodes;
};
