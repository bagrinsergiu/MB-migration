import { Literal } from "utils";

export interface Data {
  node: Element;
  urlMap: Record<string, string>;
  families?: Record<string, string>;
  defaultFamily?: string;
}

export type FontStyleProps = Omit<Data, "urlMap"> & {
  style: Record<string, Literal>;
};

export interface FontStyles {
  fontFamily?: string;
  fontWeight?: number;
  fontSize?: number;
}

export type FontSizeAndWeight = Omit<FontStyles, "fontFamily">;
