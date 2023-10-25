import { MValue } from "@/types";

function _rgbToHex(r: number, g: number, b: number): string {
  r = Math.min(255, Math.max(0, Math.round(r)));
  g = Math.min(255, Math.max(0, Math.round(g)));
  b = Math.min(255, Math.max(0, Math.round(b)));

  const hexR = r.toString(16).padStart(2, "0");
  const hexG = g.toString(16).padStart(2, "0");
  const hexB = b.toString(16).padStart(2, "0");

  return `#${hexR}${hexG}${hexB}`.toUpperCase();
}

export const rgbToHex = (rgba: string): MValue<string> => {
  const rgbValues = rgba
    .slice(4, -1)
    .split(",")
    .map((value) => parseInt(value.trim()));

  if (rgbValues.length !== 3) {
    return undefined;
  }

  return _rgbToHex(rgbValues[0], rgbValues[1], rgbValues[2]);
};
