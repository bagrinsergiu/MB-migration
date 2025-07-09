import { EMPTY_SPACES_REGEX } from "../common";

export const trimTextContent = (node: Node) =>
  node.textContent?.replace(EMPTY_SPACES_REGEX, "").trim();
