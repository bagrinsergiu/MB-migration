import { Entry, Error } from "../types/type";

export const addNodeClass = ({
  selector,
  className
}: Entry): Error | boolean => {
  if (!className) {
    return {
      error: "className was not provided"
    };
  }

  const element = selector ? document.querySelector(selector) : undefined;

  if (!element) {
    return {
      error: `Element with selector ${selector} not found`
    };
  }

  element.classList.add(className);

  return element.classList.contains(className);
};
