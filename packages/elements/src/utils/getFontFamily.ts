import { Families, Family } from "../types/type";
import { Literal, MValue } from "utils";

export const getFontFamily = (
  styles: Record<string, Literal>,
  families: Families
): MValue<Family> => {
  const value = `${styles["font-family"]}`;

  const fontFamily = value
    .replace(/['"\,]/g, "") // eslint-disable-line
    .replace(/\s/g, "_")
    .toLocaleLowerCase();

  const [firstFontFamily] = fontFamily.split("_");

  return families[fontFamily] ?? families[firstFontFamily];
};
