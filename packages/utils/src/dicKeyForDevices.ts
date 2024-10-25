import { capByPrefix } from "utils/src/text/capByPrefix";
import { toCamelCase } from "utils/src/text/toCamelCase";
import { Literal } from "utils/src/types";

/**
 * Adds desktop,mobile and tablet keys to dictionary
 */
export const dicKeyForDevices = (key: string, value: Literal | boolean) => {
  return {
    [toCamelCase(key)]: value,
    [toCamelCase(capByPrefix("mobile", key))]: value,
    [toCamelCase(capByPrefix("tablet", key))]: value
  };
};
