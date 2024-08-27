import { capByPrefix } from "utils/src/text/capByPrefix";
import { toCamelCase } from "utils/src/text/toCamelCase";

/**
 * Adds desktop,mobile and tablet keys to dictionary
 */
export const dicKeyForDevices = (key: string, value: string | number) => {
  return {
    [toCamelCase(key)]: value,
    [toCamelCase(capByPrefix("mobile", key))]: value,
    [toCamelCase(capByPrefix("tablet", key))]: value
  };
};
