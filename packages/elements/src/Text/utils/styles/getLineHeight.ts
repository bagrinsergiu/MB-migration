export function getLineHeight(value: string, fontSize: string): string {
  if (value === "normal") {
    return "1_2";
  }

  const numericLineHeight = parseFloat(value); // handles "px" and converts to number
  const numericFontSize = parseFloat(fontSize);

  if (
    isNaN(numericLineHeight) ||
    isNaN(numericFontSize) ||
    numericFontSize === 0
  ) {
    return "1";
  }

  const ratio = numericLineHeight / numericFontSize;
  const [integerPart, decimalPart = ""] = ratio.toFixed(2).split(".");

  return decimalPart ? `${integerPart}_${decimalPart[0]}` : integerPart;
}
