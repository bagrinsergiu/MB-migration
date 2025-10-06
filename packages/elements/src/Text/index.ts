import { ElementModel, EmbedModel, Entry, Output } from "../types/type";
import { createData } from "../utils/getData";
import { getDataByEntry } from "../utils/getDataByEntry";
import { getButtonModel } from "./models/Button";
import { getIconModel } from "./models/Icon";
import { getImageModel } from "./models/Image";
import { getTextModel } from "./models/Text";
import { getContainerStackWithNodes } from "./utils/dom/getContainerStackWithNodes";

type TextModel = ElementModel | EmbedModel;

export const getText = (_entry: Entry): Output => {
  const entry = window.isDev ? getDataByEntry(_entry) : _entry;

  const { selector, defaultFamily, families, urlMap } = entry;

  const node = selector ? document.querySelector(selector) : undefined;

  if (!node) {
    return {
      error: `Element with selector ${selector} not found`
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
          const models = getButtonModel({
            node,
            urlMap,
            defaultFamily,
            families
          });
          data.push(...models);
          break;
        }
        case "embed": {
          data.push({ type: "EmbedCode" });
          break;
        }
        case "icon": {
          const models = getIconModel(node, urlMap);
          data.push(...models);
          break;
        }
        case "image": {
          const models = getImageModel(node);
          data.push(...models);
        }
      }
    }
  });

  destroy();

  return createData({ data });
};
