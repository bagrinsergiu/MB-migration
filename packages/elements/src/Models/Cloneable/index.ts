import { ElementModel } from "@/types/type";
import { uuid } from "utils/src/uuid";

interface Data {
  _styles: Array<string>;
  items: Array<ElementModel>;
}

export const createCloneableModel = (data: Data): ElementModel => {
  return {
    type: "Cloneable",
    value: {
      _id: uuid(),
      _styles: data._styles,
      items: data.items
    }
  };
};
