import { Data, FontSizeAndWeight, FontStyleProps, FontStyles } from "../types";
import { mPipe } from "fp-utilities";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import * as Num from "utils/src/reader/number";
import * as Obj from "utils/src/reader/object";
import * as Str from "utils/src/reader/string";

const filterNodes = (node: Element): Array<Element> => {
  if (node.children.length === 0) {
    return [node];
  }
  const nodes: Array<Element> = [];

  node.childNodes.forEach((child) => {
    if (child.nodeType === Node.TEXT_NODE) {
      return;
    }

    const _child = child as Element;
    const notIcon = !_child.hasAttribute("data-socialicon");

    if (notIcon) {
      nodes.push(_child);
    }
  });

  return nodes;
};

const getFontFamily = (
  defaultFamily: NonNullable<Data["defaultFamily"]>,
  families: NonNullable<Data["families"]>,
  font?: string
) => {
  if (!font) return defaultFamily;

  const fontFamily = font
    .replace(/['"\,]/g, "") // eslint-disable-line
    .replace(/\s/g, "_")
    .toLocaleLowerCase();

  if (!families[fontFamily]) {
    return defaultFamily;
  } else {
    return families[fontFamily];
  }
};

const getFontSizeAndWeight = (node: Element): FontSizeAndWeight => {
  const targetNode = filterNodes(node)[0];

  const style = getNodeStyle(targetNode);
  const fontSize = mPipe(Obj.readKey("font-size"), Str.read, parseFloat)(style);
  const fontWeight = mPipe(Obj.readKey("font-weight"), Num.read)(style);

  return { fontSize, fontWeight };
};

export const getFontStyles = ({
  node,
  style,
  defaultFamily,
  families
}: FontStyleProps): FontStyles => {
  const defaultFontFamily = mPipe(Obj.readKey("font-family"), Str.read)(style);
  const { fontSize, fontWeight } = getFontSizeAndWeight(node);
  let fontFamily;

  if (families && defaultFamily) {
    fontFamily = getFontFamily(defaultFamily, families, defaultFontFamily);
  }

  return { fontSize, fontWeight, fontFamily };
};
