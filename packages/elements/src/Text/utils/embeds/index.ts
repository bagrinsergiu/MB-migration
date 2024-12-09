import { uuid } from "utils/src/uuid";

interface EmbedModel {
  type: "EmbedCode";
  value: {
    _id: string;
    code: string;
  };
}

export function removeAllEmbeds(node: Element): {
  node: Element;
  models: Array<EmbedModel>;
} {
  const nodes = node.querySelectorAll(".embedded-paste");
  const models: Array<EmbedModel> = [];

  nodes.forEach((node) => {
    models.push({
      type: "EmbedCode",
      value: {
        _id: uuid(),
        code: node.outerHTML
      }
    });
    node.remove();
  });

  return { node, models };
}
