import { rgbToHex } from "utils/src/color/rgbaToHex";

export function getButtonModel(style: Record<string, string>, node: Element) {
  const isLink = node.tagName === "A";

  return {
    bgColorHex: rgbToHex(style["background-color"]) ?? "#ffffff",
    bgColorOpacity: +style.opacity,
    bgColorType: "solid",
    colorHex: rgbToHex(style.color) ?? "#ffffff",
    colorOpacity: 1,
    text: "text" in node ? node.text : undefined,
    ...(isLink && {
      linkExternal: "href" in node ? node.href : "",
      linkType: "external",
      linkExternalBlank: "on"
    })
  };
}
