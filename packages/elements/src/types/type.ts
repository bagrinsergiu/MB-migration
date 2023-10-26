import { Data, styleExtractor } from "elements/src/StyleExtractor";
import { Literal } from "utils";

export interface Entry {
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
}

export type Output = string;

export interface OutputData {
  data: unknown;
  warns?: Record<string, Record<string, string>>;
}

export interface ElementModel {
  type: string;
  value: Record<string, Literal>;
}
