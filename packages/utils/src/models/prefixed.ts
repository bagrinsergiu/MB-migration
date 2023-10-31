import { capitalize } from "../text/capitalize";

export const prefixed = <T extends Record<string, unknown>>(
  v: T,
  prefix: string
): T => {
  return Object.entries(v).reduce((acc, [key, value]) => {
    let _key = prefix + capitalize(key);

    if (key.startsWith("active")) {
      _key = `active${capitalize(prefix)}${key.replace("active", "")}`;
    }

    return { ...acc, [_key]: value };
  }, {} as T);
};
