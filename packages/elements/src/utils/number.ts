export const roundToPrecision = (
  value: string | number,
  precision: number
): number => {
  if (typeof value === "string") {
    value = parseFloat(value);
  }
  if (isNaN(value)) {
    return 0;
  }
  const factor = Math.pow(10, precision);
  return Math.round(value * factor) / factor;
};

export const splitNumberParts = (value: number | string) => {
  const num = typeof value === "string" ? parseFloat(value) : value;
  const parts = num.toString().split(".");
  const integerPart = parts[0];
  const decimalPart = parts[1] || "";
  return {
    integerPart,
    decimalPart
  };
};
