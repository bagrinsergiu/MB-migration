export const allowedTags = [
  "P",
  "H1",
  "H2",
  "H3",
  "H4",
  "H5",
  "H6",
  "UL",
  "OL",
  "LI"
];

export const exceptExtractingStyle = ["UL", "OL"];

export const extractedAttributes = [
  "font-size",
  "font-family",
  "font-weight",
  "text-align",
  "letter-spacing",
  "text-transform"
];

export const textAlign: Record<string, string> = {
  "-webkit-center": "center",
  "-moz-center": "center",
  start: "left",
  end: "right",
  left: "left",
  right: "right",
  center: "center",
  justify: "justify"
};

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
