import { Entry, Output, OutputData } from "../types/type";

export const getData = (): Entry => {
  try {
    // For development
    // window.isDev = true;
    return window.isDev
      ? {
          selector: `[data-id='${4341579}']`,
          families: {
            "proxima_nova_proxima_nova_regular_sans-serif": "uid1111",
            "helvetica_neue_helveticaneue_helvetica_arial_sans-serif": "uid2222"
          },
          defaultFamily: "lato"
        }
      : {
          selector: SELECTOR,
          families: FAMILIES,
          defaultFamily: DEFAULT_FAMILY
        };
  } catch (e) {
    const familyMock = {
      lato: "uid_for_lato",
      roboto: "uid_for_roboto"
    };
    const mock: Entry = {
      selector: ".my-div",
      families: familyMock,
      defaultFamily: "lato"
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
