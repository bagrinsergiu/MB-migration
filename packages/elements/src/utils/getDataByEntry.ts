interface Output {
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
  styleProperties?: string[];
  list?: Element | undefined;
  nav?: Element | undefined;
}

export const getDataByEntry = (input: Output): Output => {
  const { styleProperties, list, nav } = input ?? {};

  return window.isDev
    ? {
        selector: `[data-id="${window.elementId}"]`,
        families: {},
        defaultFamily: "lato",
        ...(styleProperties ? { styleProperties: [""] } : {}),
        ...(list ? { list: undefined } : {}),
        ...(nav ? { nav: undefined } : {})
      }
    : input;
};
