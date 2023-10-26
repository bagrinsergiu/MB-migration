export function recursiveDeleteNodes(node: Element) {
  const parentElement = node.parentElement;
  node.remove();

  if (parentElement?.childNodes.length === 0) {
    recursiveDeleteNodes(parentElement);
  }
}
