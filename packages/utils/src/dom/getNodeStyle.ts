import { Literal } from "@/types";

export const getNodeStyle = (
  node: HTMLElement | Element
): Record<string, Literal> => {
  const computedStyles = window.getComputedStyle(node);
  const styles: Record<string, Literal> = {};

  Object.values(computedStyles).forEach((key) => {
    styles[key] = computedStyles.getPropertyValue(key);
  });

  return styles;
};
