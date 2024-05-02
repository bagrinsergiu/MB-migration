import { Output } from "../types/type";
import { createData } from "../utils/getData";

interface Data {
  selector?: string;
}

export const hasNode = (entry: Data): Output => {
  const { selector } = entry;

  if (!selector) {
    return {
      error: "Selector not found"
    };
  }

  const data = {
    hasNode: !!document.querySelector(selector)
  };

  return createData({ data });
};
