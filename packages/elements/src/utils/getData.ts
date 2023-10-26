import { Entry, Output, OutputData } from "@/types/type";

export const getData = (): Entry => {
  try {
    return {
      selector: "{{selector}}",
      families: JSON.parse("{{families}}"),
      defaultFamily: "{{defaultFamily}}"
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
  return JSON.stringify(output);
};
