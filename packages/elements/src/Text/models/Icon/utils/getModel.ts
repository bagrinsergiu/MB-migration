import { ElementModel } from "../../../../types/type";
import { getGlobalIconModel } from "../../../../utils/getGlobalIconModel";
import { getHref, normalizeOpacity } from "../../../utils/common";
import { codeToBuilderMap, defaultIcon } from "./iconMapping";
import { mPipe } from "fp-utilities";
import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { getParentElementOfTextNode } from "utils/src/dom/getParentElementOfTextNode";
import * as Obj from "utils/src/reader/object";
import * as Str from "utils/src/reader/string";
import { uuid } from "utils/src/uuid";

const getColor = mPipe(Obj.readKey("color"), Str.read, parseColorString);
const getBgColor = mPipe(
  Obj.readKey("background-color"),
  Str.read,
  parseColorString
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
  const bgColor = getBgColor(parentStyle);

  return {
    ...(color && {
      colorHex: normalizeOpacity({
        hex: color.hex,
        opacity: color.opacity ?? String(opacity)
      }).hex,
      colorOpacity: normalizeOpacity({
        hex: color.hex,
        opacity: isNaN(opacity) ? color.opacity ?? "1" : String(opacity)
      }).opacity,
      colorPalette: "",

      hoverColorHex: normalizeOpacity({
        hex: color.hex,
        opacity: color.opacity ?? String(opacity)
      }).hex,
      hoverColorOpacity: 0.8,
      hoverColorPalette: ""
    }),
    ...(bgColor && {
      bgColorHex: bgColor.hex,
      bgColorOpacity: bgColor.opacity,
      bgColorPalette: "",

      hoverBgColorHex: bgColor.hex,
      hoverBgColorOpacity: 0.8,
      hoverBgColorPalette: "",

      padding: 7
    })
  };
};

export function getModel(
  node: Element,
  urlMap: Record<string, string>
): ElementModel {
  const parentNode = getParentElementOfTextNode(node);
  const isIconText = parentNode?.nodeName === "#text";
  const iconNode = isIconText ? node : parentNode;
  const modelStyle = getStyleModel(node);
  const iconCode = iconNode?.textContent?.charCodeAt(0);
  const globalModel = getGlobalIconModel();

  const parentElement = node.parentElement;
  const isLink = parentElement?.tagName === "A" || node.tagName === "A";
  const href = getHref(parentElement) ?? getHref(node) ?? "";
  const mappedHref = href && urlMap[href] !== undefined ? urlMap[href] : href;

  return {
    type: "Icon",
    value: {
      _id: uuid(),
      _styles: ["icon"],
      ...globalModel,
      ...modelStyle,
      customSize: 26,
      padding: 7,
      name: iconCode ? codeToBuilderMap[iconCode] ?? defaultIcon : defaultIcon,
      type: iconCode ? "fa" : "glyph",
      ...(isLink && {
        linkExternal: mappedHref,
        linkType: "external",
        linkExternalBlank: "on"
      })
    }
  };
}
