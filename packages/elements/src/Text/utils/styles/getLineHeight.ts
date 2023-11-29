export function getLineHeight(value: string, fontSize: string): string {
  if (value === "normal") {
    return "1_2";
  }

  const lineHeightValue = value.replace("px", "");
  const lineHeight = Number(lineHeightValue) / Number(fontSize);
  const [integerPart, decimalPart = ""] = lineHeight.toString().split(".");

  return decimalPart ? integerPart + "_" + decimalPart[0] : integerPart;
}
