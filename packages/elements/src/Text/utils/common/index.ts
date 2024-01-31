import { mPipe } from "fp-utilities";
import { Color } from "utils/src/color/parseColorString";
import * as Obj from "utils/src/reader/object";
import * as Str from "utils/src/reader/string";

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
  "font-style",
  "line-height",
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

export const iconSelector =
  "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]";
export const buttonSelector = ".sites-button:not(.nav-menu-button)";
export const embedSelector = ".embedded-paste";

export const getHref = mPipe(Obj.readKey("href"), Str.read);

export const getTarget = mPipe(Obj.readKey("target"), Str.read);

export const normalizeOpacity = (color: Color): Color => {
  const { hex, opacity } = color;

  return {
    hex,
    opacity: hex === "#ffffff" && opacity === "1" ? "0.99" : opacity
  };
};
