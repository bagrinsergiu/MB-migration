import { createWrapperModel } from "../../../Models/Wrapper";
import { ElementModel } from "../../../types/type";
import { imageSelector } from "../../utils/common";
import { readInt } from "utils/src/reader/number";

export function getImageModel(node: HTMLElement): Array<ElementModel> {
  const images = node.querySelectorAll(imageSelector);

  return Array.from(images).map((image) => {
    const parent = image.parentElement;
    const isAnchor = parent?.tagName === "A";

    const width = readInt(image.width) ?? 0;
    const height = readInt(image.height) ?? 0;

    const imageSrc = image.getAttribute("src") ?? "";
    const alt = image.getAttribute("alt") ?? "";

    const linkExternal = isAnchor ? parent?.getAttribute("href") ?? "" : "";
    const linkExternalBlank =
      isAnchor && parent?.getAttribute("target") === "_blank" ? "on" : "off";
    const align = getComputedStyle(parent ?? image).textAlign;

    return createWrapperModel({
      _styles: ["wrapper", "wrapper--image"],
      items: [
        {
          type: "Image",
          value: {
            imageSrc,
            alt,
            width,
            height,
            widthSuffix: "px",
            heightSuffix: "px",
            sizeType: "custom",
            linkExternal,
            linkExternalBlank,
            linkType: "external"
          }
        }
      ],
      horizontalAlign: align
    });
  });
}
