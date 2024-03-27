export function removeEmptyNodes(node: Element): Element {
  node.innerHTML = node.innerHTML.replace(/\n\s*/g, " ");

  return node;
}
