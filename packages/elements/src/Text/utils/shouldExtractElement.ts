import { allowedTags } from "./common";

export function shouldExtractElement(
  element: Element,
  exceptions: Array<string>
): boolean {
  const isAllowed = allowedTags.includes(element.tagName);

  if (isAllowed && exceptions) {
    return !exceptions.includes(element.tagName);
  }

  return isAllowed;
}
