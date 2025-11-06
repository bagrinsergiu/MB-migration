import { ElementModel } from "../../../../types/type";
import { getGlobalIconModel } from "../../../../utils/getGlobalIconModel";
import { roundToPrecision } from "../../../../utils/number";
import {
  getHref,
  iconNodeSelector,
  normalizeOpacity
} from "../../../utils/common";
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

const getBorderWidth = mPipe(
  Obj.readKey("border-top-width"),
  Str.read,
  parseInt
);

const getBorderColor = mPipe(
  Obj.readKey("border-top-color"),
  Str.read,
  parseColorString
);

const getBorderRadius = mPipe(
  Obj.readKey("border-top-left-radius"),
  Str.read,
  parseInt
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

export const getStyleModel = (node: Element, isCustom?: boolean) => {
  const style = getStyles(node);
  const iconNode = node.querySelector(iconNodeSelector);
  const iconNodeStyle = iconNode ? getNodeStyle(iconNode) : style;
  const parentStyle = getParentStyles(node);
  const opacity = +style.opacity;
  const color = getColor(style);
  const borderWidth = getBorderWidth(iconNodeStyle);
  const borderColor = getBorderColor(iconNodeStyle);
  const borderRadius = getBorderRadius(iconNodeStyle);
  const bgColor = getBgColor(parentStyle);

  const customSize = roundToPrecision(style.width, 2) || 26;

  return {
    ...(color && {
      colorHex: normalizeOpacity({
        hex: color.hex,
        opacity: color.opacity ?? opacity
      }).hex,
      colorOpacity: normalizeOpacity({
        hex: color.hex,
        opacity: isNaN(opacity) ? color.opacity ?? 1 : opacity
      }).opacity,
      colorPalette: "",

      hoverColorHex: normalizeOpacity({
        hex: color.hex,
        opacity: color.opacity ?? opacity
      }).hex,
      hoverColorOpacity: 0.8,
      hoverColorPalette: ""
    }),
    ...(bgColor && {
      bgColorHex: bgColor.hex,
      bgColorOpacity: bgColor.opacity,
      bgColorPalette: "",

      padding: 7
    }),
    ...(isCustom && {
      customSize
    }),
    ...(borderWidth &&
      borderColor && {
        borderWidth,
        borderColorHex: borderColor.hex,
        borderColorOpacity: borderColor.opacity ?? 1,
        borderColorPalette: "",
        borderRadius: borderRadius ?? 0
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
  const isSvg = node instanceof SVGElement;
  const modelStyle = getStyleModel(node, isSvg);
  const iconCode = iconNode?.textContent?.charCodeAt(0);
  const globalModel = getGlobalIconModel();

  const linkNode = node.querySelector("a") ?? node.closest("a");
  const isLink = !!linkNode;
  const href = getHref(linkNode);
  const mappedHref = href && urlMap[href] !== undefined ? urlMap[href] : href;

  const fileName = node.getAttribute("sodipodi:docname");

  return {
    type: "Icon",
    value: {
      _id: uuid(),
      _styles: ["icon"],
      customSize: 26,
      ...globalModel,
      ...modelStyle,
      padding: 7,
      name: iconCode ? codeToBuilderMap[iconCode] ?? defaultIcon : defaultIcon,
      type: iconCode ? "fa" : "glyph",
      ...(isSvg && {
        code: node.outerHTML,
        type: "custom",
        filename: fileName || "icon.svg"
      }),
      ...(isLink && {
        linkExternal: mappedHref,
        linkType: "external",
        linkExternalBlank: "on"
      })
    }
  };
}
