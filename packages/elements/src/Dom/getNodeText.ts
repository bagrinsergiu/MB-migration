import { Output } from "../types/type";
import { createData } from "../utils/getData";

interface Data {
  selector?: string;
}

export const getNodeText = (entry: Data): Output => {
  const { selector } = entry;

  if (!selector) {
    return {
      error: "Selector not found"
    };
  }

  const element = document.querySelector(selector);

  if (element) {
    const data = {
      contain: element.textContent
    };

    return createData({ data });
  }

  return {
    error: "Selector not found"
  };
};
