import { ElementModel, EmbedModel, Entry, Output } from "../types/type";
import { createData } from "../utils/getData";
import { getDataByEntry } from "../utils/getDataByEntry";
import { getButtonModel } from "./models/Button";
import { getEmbedModel } from "./models/Embed";
import { getIconModel } from "./models/Icon";
import { getTextModel } from "./models/Text";
import { getContainerStackWithNodes } from "./utils/dom/getContainerStackWithNodes";

type TextModel = ElementModel | EmbedModel;

export const getText = (_entry: Entry): Output => {
  const entry = window.isDev ? getDataByEntry(_entry) : _entry;

  const { selector } = entry;

  let node = selector ? document.querySelector(selector) : undefined;

  if (!node) {
    return {
      error: `Element with selector ${selector} not found`
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
