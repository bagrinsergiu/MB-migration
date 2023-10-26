export function getLetterSpacing(value: string): string {
  if (value === "normal") {
    return "0";
  }

  // Remove 'px' and any extra whitespace
  const letterSpacingValue = value.replace(/px/g, "").trim();
  const [integerPart, decimalPart = "0"] = letterSpacingValue.split(".");
  const toNumberI = +integerPart;

  if (toNumberI < 0 || Object.is(toNumberI, -0)) {
    return "m_" + -toNumberI + "_" + decimalPart[0];
  }
  return toNumberI + "_" + decimalPart[0];
}
