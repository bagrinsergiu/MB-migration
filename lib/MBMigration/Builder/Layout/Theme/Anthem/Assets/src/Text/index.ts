import { Entry } from "../types/type";
import { getText } from "elements/src/Text";
import { MValue } from "utils";

let data: MValue<Entry> = undefined;

try {
  data = {
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

getText(data);
