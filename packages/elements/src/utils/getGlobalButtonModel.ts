import { Literal, MValue } from "utils";

export const getGlobalButtonModel = (): MValue<Record<string, Literal>> => {
  return window.buttonModel;
};
