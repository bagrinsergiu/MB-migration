import { Entry, Output } from "../types/type";
import { createData } from "../utils/getData";

export const hasNode = (entry: Entry): Output => {
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
