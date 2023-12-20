import { MValue } from "../types";

export interface Color {
  hex: string;
  opacity: string;
}

const hexRegex = /^#(?:[A-Fa-f0-9]{3}){1,2}$/;
const rgbRegex = /^rgb\s*[(]\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*[)]$/;
const rgbaRegex =
  /^rgba\s*[(]\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*(0*(?:\.\d+)?|1(?:\.0*)?)\s*[)]$/;

const isHex = (v: string): boolean => hexRegex.test(v);

const fromRgb = (rgb: [number, number, number]): string => {
  return (
    "#" +
    ("0" + rgb[0].toString(16)).slice(-2) +
    ("0" + rgb[1].toString(16)).slice(-2) +
    ("0" + rgb[2].toString(16)).slice(-2)
  );
};

function parseRgb(color: string): MValue<[number, number, number]> {
  const matches = rgbRegex.exec(color);

  if (matches) {
    const [r, g, b] = matches.slice(1).map(Number);
    return [r, g, b];
  }

  return undefined;
}

function parseRgba(color: string): MValue<[number, number, number, number]> {
  const matches = rgbaRegex.exec(color);

  if (matches) {
    const [r, g, b, a] = matches.slice(1).map(Number);
    return [r, g, b, a];
  }

  return undefined;
}

export function parseColorString(colorString: string): MValue<Color> {
  if (isHex(colorString)) {
    return {
      hex: colorString,
      opacity: "1"
    };
  }

  const rgbResult = parseRgb(colorString);
  if (rgbResult) {
    return {
      hex: fromRgb(rgbResult),
      opacity: "1"
    };
  }

  const rgbaResult = parseRgba(colorString);
  if (rgbaResult) {
    const [r, g, b, a] = rgbaResult;
    return {
      hex: fromRgb([r, g, b]),
      opacity: String(a)
    };
  }

  return undefined;
}
