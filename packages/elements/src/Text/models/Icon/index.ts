import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

export function getIconModel(style: Record<string, string>, node: Element) {
  const parentElement = node.parentElement;
  const isLink = parentElement?.tagName === "A" || node.tagName === "A";
  const parentBgColor = parentElement
    ? parseColorString(getNodeStyle(parentElement)["background-color"])
    : undefined;
  const parentHref =
    parentElement && "href" in parentElement ? parentElement.href : "";
  const opacity = +style.opacity;
  const color = parseColorString(style.color);

  return {
    colorHex: color?.hex ?? "#ffffff",
    colorOpacity: isNaN(opacity) ? color?.opacity ?? 1 : opacity,
    ...(isLink && {
      linkExternal: parentHref ?? ("href" in node ? node.href : ""),
      linkType: "external",
      linkExternalBlank: "on",
      ...(parentBgColor && {
        bgColorHex: parentBgColor.hex,
        bgColorOpacity: parentBgColor.opacity
      })
    })
  };
}
