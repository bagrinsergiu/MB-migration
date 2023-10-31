import { ElementModel } from "../../../../types/type";
import { getHref } from "../../../utils/common";
import { mPipe } from "fp-utilities";
import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { getParentElementOfTextNode } from "utils/src/dom/getParentElementOfTextNode";
import * as Obj from "utils/src/reader/object";
import * as Str from "utils/src/reader/string";
import { uuid } from "utils/src/uuid";

const codeToBuilderMap: Record<string, string> = {
  facebook: "logo-facebook",
  instagram: "logo-instagram",
  youtube: "logo-youtube",
  twitter: "logo-twitter",
  vimeo: "logo-vimeo",
  mail: "email-85",
  apple: "apple",
  57380: "email-85",
  58624: "logo-instagram",
  58407: "logo-facebook",
  57895: "logo-facebook",
  57936: "note-03",
  58009: "logo-youtube"
};
const getColor = mPipe(Obj.readKey("color"), Str.read, parseColorString);
const getBgColor = mPipe(
  Obj.readKey("background-color"),
  Str.read,
  parseColorString
);

export function getModel(node: Element): ElementModel {
  const parentNode = getParentElementOfTextNode(node);
  const isIconText = parentNode?.nodeName === "#text";
  const iconNode = isIconText ? node : parentNode;
  const style = iconNode ? getNodeStyle(iconNode) : {};
  const parentElement = node.parentElement;
  const isLink = parentElement?.tagName === "A" || node.tagName === "A";
  const parentStyle = parentElement ? getNodeStyle(parentElement) : {};
  const parentBgColor = getBgColor(parentStyle);
  const parentHref = getHref(parentElement) ?? getHref(node) ?? "";
  const opacity = +style.opacity;
  const color = getColor(style);
  const iconCode = iconNode?.textContent?.charCodeAt(0);

  return {
    type: "Icon",
    value: {
      _id: uuid(),
      _styles: ["icon"],
      colorHex: color?.hex ?? "#ffffff",
      colorOpacity: isNaN(opacity) ? color?.opacity ?? 1 : opacity,
      name: iconCode
        ? codeToBuilderMap[iconCode] ?? "favourite-31"
        : "favourite-31",
      ...(isLink && {
        linkExternal: parentHref,
        linkType: "external",
        linkExternalBlank: "on",
        ...(parentBgColor && {
          bgColorHex: parentBgColor.hex,
          bgColorOpacity: parentBgColor.opacity
        })
      })
    }
  };
}
