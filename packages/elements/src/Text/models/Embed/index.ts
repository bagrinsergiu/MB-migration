import { embedSelector } from "@/Text/utils/common";
import { EmbedModel } from "@/types/type";

export function getEmbedModel(node: Element): Array<EmbedModel> {
  const embeds = node.querySelectorAll(embedSelector);
  const models: Array<EmbedModel> = [];

  embeds.forEach(() => {
    models.push({ type: "EmbedCode" });
  });

  return models;
}
