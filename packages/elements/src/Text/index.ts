import { getTextModel } from "./models/Text";
import { getButtonModel } from "@/Text/models/Button";
import { getEmbedModel } from "@/Text/models/Embed";
import { getIconModel } from "@/Text/models/Icon";
import { getContainerStackWithNodes } from "@/Text/utils/dom/getContainerStackWithNodes";
import { ElementModel, EmbedModel, Entry, Output } from "@/types/type";
import { createData } from "@/utils/getData";

type TextModel = ElementModel | EmbedModel;

export const getText = (data: Entry): Output => {
  let node = document.querySelector(data.selector);

  if (!node) {
    return {
      error: `Element with selector ${data.selector} not found`
    };
  }

  node = node.children[0];

  if (!node) {
    return {
      error: `Element with selector ${data.selector} has no wrapper`
    };
  }

  const elements: Array<TextModel> = [];

  const { container, destroy } = getContainerStackWithNodes(node);
  const containerChildren = Array.from(container.children);

  containerChildren.forEach((node) => {
    if (node instanceof HTMLElement) {
      switch (node.dataset.type) {
        case "text": {
          const model = getTextModel({ ...data, node });
          elements.push(model);
          break;
        }
        case "button": {
          const models = getButtonModel(node);
          elements.push(...models);
          break;
        }
        case "embed": {
          const models = getEmbedModel(node);
          elements.push(...models);
          break;
        }
        case "icon": {
          const models = getIconModel(node);
          elements.push(...models);
          break;
        }
      }
    }
  });

  destroy();

  return createData({ data: elements });
};
