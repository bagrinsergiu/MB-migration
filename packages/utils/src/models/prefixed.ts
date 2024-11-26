import { capitalize } from "../text/capitalize";

export const prefixed = <T extends Record<string, unknown>>(
  v: T,
  prefix: string
): T => {
  return Object.entries(v).reduce((acc, [key, value]) => {
    let _key = prefix + capitalize(key);
    const prefixes = ["active", "mobile", "tablet"];
    const matchedPrefix = prefixes.find((prefix) => key.startsWith(prefix));

    if (matchedPrefix) {
      _key = `${matchedPrefix}${capitalize(prefix)}${key.replace(
        `${matchedPrefix}`,
        ""
      )}`;
    }

    return { ...acc, [_key]: value };
  }, {} as T);
};
