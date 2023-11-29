import { extractedAttributes } from "../common";
import { extractParentElementsWithStyles } from "../dom/extractParentElementsWithStyles";
import { Literal } from "utils";

interface Output {
  uid: string;
  tagName: string;
  styles: Record<string, Literal>;
}

export const getTypographyStyles = (node: Element): Array<Output> => {
  const allRichTextElements = extractParentElementsWithStyles(node);
  return allRichTextElements.map((element) => {
    const { styles } = element;

    return {
      ...element,
      styles: extractedAttributes.reduce((acc, attribute) => {
        acc[attribute] = styles[attribute];
        return acc;
      }, {} as Record<string, Literal>)
    };
  });
};
