import { capitalize } from "./capitalize";

export const toCamelCase = (key: string): string => {
  const parts = key.split("-");
  for (let i = 1; i < parts.length; i++) {
    parts[i] = capitalize(parts[i]);
  }
  return parts.join("");
};
