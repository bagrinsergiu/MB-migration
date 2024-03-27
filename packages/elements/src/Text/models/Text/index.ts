import { createWrapperModel } from "../../../Models/Wrapper";
import { ElementModel } from "../../../types/type";
import { addMarginsToLists } from "../..//utils/styles/addMarginsToLists";
import { removeAllStylesFromHTML } from "../../utils/dom/removeAllStylesFromHTML";
import { removeEmptyNodes } from "../../utils/dom/removeEmptyNodes";
import { transformDivsToParagraphs } from "../../utils/dom/transformDivsToParagraphs";
import { copyParentColorToChild } from "../../utils/styles/copyParentColorToChild";
import { getTypographyStyles } from "../../utils/styles/getTypographyStyles";
import { stylesToClasses } from "./utils/stylesToClasses";
import { uuid } from "utils/src/uuid";

interface Data {
  node: Element;
  families: Record<string, string>;
  urlMap: Record<string, string>;
  defaultFamily: string;
}

export const getTextModel = (data: Data): ElementModel => {
  const { node: _node, families, defaultFamily, urlMap } = data;
  let node = _node;

  // Transform all inside div to P
  node = transformDivsToParagraphs(node);

  // Copy Parent Color to Child, from <p> to <span>
  node = copyParentColorToChild(node);

  // Get all ours style for Builder [font-family, font-size, line-height, .etc]
  const styles = getTypographyStyles(node);

  // Get all lists and add margins to it;
  node = addMarginsToLists(node);

  // Remove all inline styles like background-color, positions.. etc.
  node = removeAllStylesFromHTML(node);

  // Transform all styles to className font-size: 20 to .brz-fs-20
  styles.map((style) => {
    const classes = stylesToClasses(style.styles, families, defaultFamily);
    const styleNode = node.querySelector(`[data-uid='${style.uid}']`);

    if (styleNode) {
      styleNode.classList.add(...classes);
      styleNode.removeAttribute("data-uid");
    }
  });

  // Remove all empty P with [ \n ]
  node = removeEmptyNodes(node);

  let text = node.innerHTML;
  let match;

  const regex = /href="(.*?)"/g;

  while ((match = regex.exec(text))) {
    const hrefValue = match[1];

    if (urlMap[hrefValue]) {
      const mappedValue = urlMap[hrefValue];

      if (mappedValue === hrefValue) {
        text = text.replace(match[0], `href="${mappedValue}"`);
      }
    }
  }

  return createWrapperModel({
    _styles: ["wrapper", "wrapper--richText"],
    items: [
      {
        type: "RichText",
        value: {
          _id: uuid(),
          _styles: ["richText"],
          text: text
        }
      }
    ]
  });
};
