import { Data, styleExtractor } from "elements/src/StyleExtractor";
import { Entry } from "elements/src/types/type";

const getData = (): Data => {
  try {
    return {
      selector: SELECTOR,
      families: FAMILIES,
      styleProperties: STYLE_PROPERTIES,
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

const data = getData();
const output = styleExtractor(data);

export default output;