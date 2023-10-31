import { MValue } from "@/types";

export function getParentElementOfTextNode(node: Element): MValue<Element> {
  if (node.nodeType === Node.TEXT_NODE) {
    return (node.parentNode as Element) ?? undefined;
  }

  return Array.from(node.childNodes).find((node) =>
    getParentElementOfTextNode(node as Element)
  ) as Element;
}
