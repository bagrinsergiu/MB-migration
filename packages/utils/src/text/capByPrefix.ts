import { capitalize } from "./capitalize";

/**
 * Capitalize word depending on prefix
 * - If prefix is empty, do not capitalize
 * - If prefix is not empty capitalize word
 *
 * @param {string} p
 * @param {string} s
 * @returns {string}
 */
export const capByPrefix = (p: string, s: string): string =>
  p === "" ? s : p + "-" + capitalize(s);
