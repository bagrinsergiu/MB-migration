import { Entry, Output } from "../types/type";

(() => {
  let warns: Record<string, Record<string, string>> = {};

  const run = (data: Entry): Output => {
    const node = document.querySelector(data.selector);

    if (!node) {
      return JSON.stringify({
        error: `Element with selector ${data.selector} not found`,
        warns: warns
      });
    }

    return JSON.stringify({});
  };

  try {
    const id = 4242415;
    const data: Entry = {
      selector: `[data-id='${id}']`,
      families: {
        "proxima_nova_proxima_nova_regular_sans-serif": "uid1111",
        "helvetica_neue_helveticaneue_helvetica_arial_sans-serif": "uid2222"
      },
      defaultFamily: "helvetica_neue_helveticaneue_helvetica_arial_sans-serif"
    };
    console.log(JSON.parse(run(data)));
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
})();
