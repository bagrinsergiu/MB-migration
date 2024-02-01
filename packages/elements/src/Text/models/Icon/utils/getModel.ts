import { ElementModel } from "../../../../types/type";
import { getGlobalIconModel } from "../../../../utils/getGlobalIconModel";
import { getHref, normalizeOpacity } from "../../../utils/common";
import { mPipe } from "fp-utilities";
import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { getParentElementOfTextNode } from "utils/src/dom/getParentElementOfTextNode";
import * as Obj from "utils/src/reader/object";
import * as Str from "utils/src/reader/string";
import { uuid } from "utils/src/uuid";

const codeToBuilderMap: Record<string, string> = {
  apple: "apple",
  57351: "apple",

  57686: "pin-3",

  mail: "email-85",
  57892: "email-85",
  57380: "email-85",

  57936: "note-03",

  facebook: "logo-facebook",
  57895: "logo-facebook",
  58407: "logo-facebook",
  61570: "logo-facebook",

  youtube: "logo-youtube",
  58009: "logo-youtube",
  58521: "logo-youtube",
  62513: "logo-youtube",

  vimeo: "logo-vimeo",
  57993: "logo-vimeo",

  twitter: "logo-twitter",
  57990: "logo-twitter",
  58503: "logo-twitter",

  instagram: "logo-instagram",
  58624: "logo-instagram",
  58112: "logo-instagram",
  61805: "logo-instagram",

  58211: "circle-right-37",

  63244: "user-run"
};
const getColor = mPipe(
  Obj.readKey("color"),
  Str.read,
  parseColorString,
  normalizeOpacity
);
const getBgColor = mPipe(
  Obj.readKey("background-color"),
  Str.read,
  parseColorString,
  normalizeOpacity
);

export const getStyles = (node: Element) => {
  const parentNode = getParentElementOfTextNode(node);
  const isIconText = parentNode?.nodeName === "#text";
  const iconNode = isIconText ? node : parentNode;
  return iconNode ? getNodeStyle(iconNode) : {};
};

export const getParentStyles = (node: Element) => {
  const parentElement = node.parentElement;
  return parentElement ? getNodeStyle(parentElement) : {};
};

export const getStyleModel = (node: Element) => {
  const style = getStyles(node);
  const parentStyle = getParentStyles(node);
  const opacity = +style.opacity;
  const color = getColor(style);
  const parentBgColor = getBgColor(parentStyle);

  return {
    ...(color && {
      colorHex: color.hex,
      colorOpacity: isNaN(opacity) ? color.opacity : opacity,
      colorPalette: ""
    }),
    ...(parentBgColor && {
      bgColorHex: parentBgColor.hex,
      bgColorOpacity: parentBgColor.opacity,
      bgColorPalette: "",
      padding: 7
    })
  };
};

export function getModel(node: Element): ElementModel {
  const parentNode = getParentElementOfTextNode(node);
  const isIconText = parentNode?.nodeName === "#text";
  const iconNode = isIconText ? node : parentNode;
  const parentElement = node.parentElement;
  const isLink = parentElement?.tagName === "A" || node.tagName === "A";
  const parentHref = getHref(parentElement) ?? getHref(node) ?? "";
  const modelStyle = getStyleModel(node);
  const iconCode = iconNode?.textContent?.charCodeAt(0);
  const globalModel = getGlobalIconModel();

  return {
    type: "Icon",
    value: {
      _id: uuid(),
      _styles: ["icon"],
      ...globalModel,
      ...modelStyle,
      customSize: 26,
      padding: 7,
      name: iconCode
        ? codeToBuilderMap[iconCode] ?? "favourite-31"
        : "favourite-31",
      ...(isLink && {
        linkExternal: parentHref,
        linkType: "external",
        linkExternalBlank: "on"
      })
    }
  };
}
