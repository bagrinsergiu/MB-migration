import { MValue, Primitive } from "utils";

export const getGlobalButtonModel = (): MValue<Record<string, Primitive>> => {
  return window.buttonModel;
};
