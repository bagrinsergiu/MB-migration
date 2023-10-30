import { getTextModel } from "./models/Text";
import { getButtonModel } from "@/Text/models/Button";
import { getEmbedModel } from "@/Text/models/Embed";
import { getIconModel } from "@/Text/models/Icon";
import { getContainerStackWithNodes } from "@/Text/utils/dom/getContainerStackWithNodes";
import { ElementModel, EmbedModel, Entry, Output } from "@/types/type";
import { createData } from "@/utils/getData";

type TextModel = ElementModel | EmbedModel;

export const getText = (entry: Entry): Output => {
  let node = document.querySelector(entry.selector);

  if (!node) {
    return {
      error: `Element with selector ${entry.selector} not found`
    };
  }

  node = node.children[0];

  if (!node) {
    return {
      error: `Element with selector ${entry.selector} has no wrapper`
    };
  }

  const data: Array<TextModel> = [];

  const { container, destroy } = getContainerStackWithNodes(node);
  const containerChildren = Array.from(container.children);

  containerChildren.forEach((node) => {
    if (node instanceof HTMLElement) {
      switch (node.dataset.type) {
        case "text": {
          const model = getTextModel({ ...entry, node });
          data.push(model);
          break;
        }
        case "button": {
          const models = getButtonModel(node);
          data.push(...models);
          break;
        }
        case "embed": {
          const models = getEmbedModel(node);
          data.push(...models);
          break;
        }
        case "icon": {
          const models = getIconModel(node);
          data.push(...models);
          break;
        }
      }
    }
  });

  destroy();

  return createData({ data });
};
