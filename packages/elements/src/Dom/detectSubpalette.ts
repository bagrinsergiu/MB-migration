import { Output } from "../types/type";

interface Data {
  selector?: string;
}

export const detectSubpalette = (entry: Data): Output => {
  const subpalettes = [
    "subpalette1",
    "subpalette2",
    "subpalette3",
    "subpalette4"
  ];
  const { selector } = entry;

  if (!selector) {
    return {
      error: "Selector not found"
    };
  }

  const element = document.querySelector(selector);

  if (element) {
    for (const subpalette of subpalettes) {
      if (element.classList.contains(subpalette)) {
        return {
          data: subpalette
        };
      }
    }
    return {
      data: false
    };
  }

  return {
    data: false
  };
};
