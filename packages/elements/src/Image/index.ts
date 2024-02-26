import { createData } from "../utils/getData";
import { Entry, Output } from "elements/src/types/type";

interface ImageModel {
  src: string;
  width: number;
  height: number;
}

export const getImage = (entry: Entry): Output => {
  const node = document.querySelector(entry.selector);
  if (!node) {
    return {
      error: `Element with selector ${entry.selector} not found`
    };
  }

  const images = node.querySelectorAll("img");

  const data: Array<ImageModel> = [];

  images.forEach((image) => {
    const src = image.src || image.srcset;
    const width = image.width;
    const height = image.height;
    data.push({ src, width, height });
  });

  return createData({ data });
};
