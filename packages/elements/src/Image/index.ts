import { createData } from "../utils/getData";
import { getDataByEntry } from "../utils/getDataByEntry";
import { Entry, Output } from "elements/src/types/type";

interface ImageModel {
  src: string;
  width: number;
  height: number;
}

export const getImage = (_entry: Entry): Output => {
  const entry = window.isDev ? getDataByEntry(_entry) : _entry;

  const { selector } = entry;

  const node = selector ? document.querySelector(selector) : undefined;
  if (!node) {
    return {
      error: `Element with selector ${selector} not found`
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
