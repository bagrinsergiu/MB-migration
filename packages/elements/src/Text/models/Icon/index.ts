import { rgbToHex } from "utils/src/color/rgbaToHex";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

export function getIconModel(style: Record<string, string>, node: HTMLElement) {
  const parentElement = node.parentElement;
  const isLink = parentElement?.tagName === "A" || node.tagName === "A";
  const parentBgColor = parentElement
    ? rgbToHex(getNodeStyle(parentElement)["background-color"])
    : undefined;
  const parentHref =
    parentElement && "href" in parentElement ? parentElement.href : "";

  return {
    colorHex: rgbToHex(style.color) ?? "#ffffff",
    colorOpacity: +style.opacity,
    ...(isLink && {
      linkExternal: parentHref ?? ("href" in node ? node.href : ""),
      linkType: "external",
      linkExternalBlank: "on",
      ...(parentBgColor && { bgColorHex: parentBgColor })
    })
  };
}
