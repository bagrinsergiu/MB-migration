import { Reader } from "./types";

export const read: Reader<number> = (v) => {
  switch (typeof v) {
    case "string": {
      const v_ = v !== "" ? Number(v) : NaN;
      return isNaN(v_) ? undefined : v_;
    }
    case "number":
      return isNaN(v) ? undefined : v;
    default:
      return undefined;
  }
};

export const readInt: Reader<number> = (v) => {
  if (typeof v === "string") {
    return parseInt(v);
  }

  return read(v);
};

export const readFloat: Reader<number> = (v) => {
  if (typeof v === "string") {
    return parseFloat(v);
  }

  return read(v);
};
