import { createWrapperModel } from "../../../Models/Wrapper";
import { ElementModel } from "../../../types/type";
import { imageSelector } from "../../utils/common";

export function getImageModel(node: HTMLElement): Array<ElementModel> {
  const images = node.querySelectorAll(imageSelector);

  return Array.from(images).map((image) => {
    const parent = image.parentElement;
    const isAnchor = parent?.tagName === "A";

    const imageWidth = +(image.getAttribute("width") ?? "0");
    const imageHeight = +(image.getAttribute("height") ?? "0");

    const imageSrc = image.getAttribute("src") ?? "";
    const alt = image.getAttribute("alt") ?? "";

    const linkExternal = isAnchor ? parent?.getAttribute("href") ?? "" : "";
    const linkExternalBlank =
      isAnchor && parent?.getAttribute("target") === "_blank" ? "on" : "off";

    return createWrapperModel({
      _styles: ["wrapper", "wrapper--image"],
      items: [
        {
          type: "Image",
          value: {
            imageSrc,
            alt,
            imageWidth,
            imageHeight,
            linkExternal,
            linkExternalBlank,
            linkType: "external"
          }
        }
      ]
    });
  });
}
