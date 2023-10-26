import { createWrapperModel } from "@/Models/Wrapper";
import { stylesToClasses } from "@/Text/models/Text";
import { removeAllButtons } from "@/Text/utils/buttons";
import { removeAllStylesFromHTML } from "@/Text/utils/dom/removeAllStylesFromHTML";
import { transformDivsToParagraphs } from "@/Text/utils/dom/transformDivsToParagraphs";
import { removeAllEmbeds } from "@/Text/utils/embeds";
import { removeAllIcons } from "@/Text/utils/icons";
import { copyParentColorToChild } from "@/Text/utils/styles/copyParentColorToChild";
import { getTypographyStyles } from "@/Text/utils/styles/getTypographyStyles";
import { ElementModel, Entry, Output } from "@/types/type";
import { createData } from "@/utils/getData";
import { Literal } from "utils";
import { uuid } from "utils/src/uuid";

interface Data {
  node: Element;
  families: Record<string, string>;
  defaultFamily: string;
  styles: Array<{
    uid: string;
    tagName: string;
    styles: Record<string, Literal>;
  }>;
}

const toBuilderText = (data: Data): string => {
  const { node, styles, families, defaultFamily } = data;

  styles.map((style) => {
    const classes = stylesToClasses(style.styles, families, defaultFamily);
    const styleNode = node.querySelector(`[data-uid='${style.uid}']`);

    if (styleNode) {
      styleNode.classList.add(...classes);
      styleNode.removeAttribute("data-uid");
    }
  });

  return node.innerHTML;
};

export const getText = (data: Entry): Output => {
  let node = document.querySelector(data.selector);

  if (!node) {
    return JSON.stringify({
      error: `Element with selector ${data.selector} not found`
    });
  }

  node = node.children[0];

  if (!node) {
    return JSON.stringify({
      error: `Element with selector ${data.selector} has no wrapper`
    });
  }

  const elements: Array<ElementModel> = [];

  node = removeAllIcons(node);

  node = removeAllButtons(node);

  const embeds = removeAllEmbeds(node);

  node = embeds.node;

  node = transformDivsToParagraphs(node);

  node = copyParentColorToChild(node);

  node = removeAllStylesFromHTML(node);

  const dataText = {
    node: node,
    families: data.families,
    defaultFamily: data.defaultFamily,
    styles: getTypographyStyles(node)
  };

  elements.push(
    createWrapperModel({
      _styles: ["wrapper", "wrapper--richText"],
      items: [
        {
          type: "RichText",
          value: {
            _id: uuid(),
            _styles: ["richText"],
            text: toBuilderText(dataText)
          }
        }
      ]
    })
  );

  return createData({ data: elements });
};
