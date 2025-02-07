import { Entry, Output, OutputData } from "../types/type";

export const getData = (): Entry => {
  try {
    // For development
    // window.isDev = true;
    return window.isDev
      ? {
          selector: `[data-id='${window.elementId}']`,
          families: {
            "proxima_nova_proxima_nova_regular_sans-serif": {
              name: "uid1111",
              type: "upload"
            },
            "helvetica_neue_helveticaneue_helvetica_arial_sans-serif": {
              name: "uid2222",
              type: "upload"
            }
          },
          defaultFamily: "lato",
          urlMap: {}
        }
      : {
          selector: SELECTOR,
          families: FAMILIES,
          defaultFamily: DEFAULT_FAMILY,
          urlMap: {}
        };
  } catch (e) {
    const familyMock = {
      lato: { name: "uid_for_lato", type: "upload" },
      roboto: { name: "uid_for_roboto", type: "upload" }
    };
    const mock: Entry = {
      selector: ".my-div",
      families: familyMock,
      defaultFamily: "lato",
      urlMap: {}
    };

    throw new Error(
      JSON.stringify({
        error: `Invalid JSON ${e}`,
        details: `Must be: ${JSON.stringify(mock)}`
      })
    );
  }
};

export const createData = (output: OutputData): Output => {
  return output;
};
