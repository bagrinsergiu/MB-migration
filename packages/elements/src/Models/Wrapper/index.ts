import { ElementModel } from "../../types/type";
import { uuid } from "utils/src/uuid";

interface Data {
  _styles: Array<string>;
  items: Array<ElementModel>;
  [k: string]: string | Array<string | ElementModel>;
}

export const createWrapperModel = (data: Data): ElementModel => {
  const { _styles, items, ...value } = data;
  return {
    type: "Wrapper",
    value: { _id: uuid(), _styles, items, ...value }
  };
};
