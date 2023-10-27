import { parseColorString } from "utils/src/color/parseColorString";

export function getButtonModel(style: Record<string, string>, node: Element) {
  const isLink = node.tagName === "A";
  const color = parseColorString(style.color);
  const bgColor = parseColorString(style["background-color"]);
  const opacity = +style.opacity;

  return {
    bgColorHex: bgColor?.hex ?? "#ffffff",
    bgColorOpacity: isNaN(opacity) ? bgColor?.opacity ?? 1 : opacity,
    bgColorType: "solid",
    colorHex: color?.hex ?? "#ffffff",
    colorOpacity: color?.opacity ?? 1,
    text: "text" in node ? node.text : undefined,
    ...(isLink && {
      linkExternal: "href" in node ? node.href : "",
      linkType: "external",
      linkExternalBlank: "on"
    })
  };
}
