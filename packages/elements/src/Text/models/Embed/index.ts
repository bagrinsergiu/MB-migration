import { EmbedModel } from "../../../types/type";
import { embedSelector } from "../../utils/common";

export function getEmbedModel(node: Element): Array<EmbedModel> {
  const embeds = node.querySelectorAll(embedSelector);
  const models: Array<EmbedModel> = [];

  embeds.forEach(() => {
    models.push({ type: "EmbedCode" });
  });

  return models;
}
