interface Output {
  families: Record<string, string>;
  defaultFamily: string;
  selector?: string;
  itemSelector?: string;
  subItemSelector?: string;
  sectionSelector?: string;
  styleProperties?: string[];
  list?: Element | undefined;
  nav?: Element | undefined;
  urlMap: Record<string, string>;
  attributeNames?: string[];
  pseudoElement?: string;
}

export const getDataByEntry = (input: Output): Output => {
  const {
    styleProperties,
    list,
    nav,
    selector,
    itemSelector,
    subItemSelector,
    sectionSelector,
    attributeNames,
    pseudoElement
  } = input ?? {};

  return window.isDev
    ? {
        families: {},
        defaultFamily: "lato",
        ...(styleProperties ? { styleProperties: [""] } : {}),
        ...(selector ? { selector: `[data-id="${window.elementId}"]` } : {}),
        ...(list ? { list: undefined } : {}),
        ...(nav ? { nav: undefined } : {}),
        ...(itemSelector ? { itemSelector: "" } : {}),
        ...(subItemSelector ? { subItemSelector: "" } : {}),
        ...(sectionSelector ? { sectionSelector: "" } : {}),
        ...(attributeNames ? { attributeNames: [""] } : {}),
        pseudoElement,
        urlMap: {}
      }
    : input;
};
