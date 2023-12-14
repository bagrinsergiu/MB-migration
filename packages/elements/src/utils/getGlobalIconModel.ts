import { Literal, MValue } from "utils";

export const getGlobalIconModel = (): MValue<Record<string, Literal>> => {
  return window.iconModel;
};
