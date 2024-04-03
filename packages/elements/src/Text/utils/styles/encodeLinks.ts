import { encodeToString, getHref, getTarget } from "../common/index";

export function encodeLinks(node: Element, urlMap: Record<string, string>) {
  const links = Array.from(node.querySelectorAll("a"));

  links.map((link) => {
    const href = getHref(link);
    const mappedHref = href && urlMap[href] !== undefined ? urlMap[href] : href;
    const target = getTarget(link);
    const targetType = target === "_self" ? "off" : "on";

    link.dataset.href = encodeToString({
      type: "external",
      anchor: "",
      external: mappedHref,
      externalBlank: targetType,
      externalRel: "off",
      externalType: "external",
      population: "",
      populationEntityId: "",
      populationEntityType: "",
      popup: "",
      upload: "",
      linkToSlide: 1,
      internal: "",
      internalBlank: "off",
      pageTitle: "",
      pageSource: null
    });

    link.removeAttribute("href");
  });

  return node;
}
