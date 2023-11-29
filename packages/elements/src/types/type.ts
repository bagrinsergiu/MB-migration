import { Literal } from "utils";

export interface Entry {
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
}

export interface OutputData {
  data: unknown;
  warns?: Record<string, Record<string, string>>;
}

interface Data {
  data: unknown;
  warns?: Record<string, Record<string, string>>;
}

interface Error {
  error: string;
}

export type Output = Data | Error;

export interface ElementModel {
  type: string;
  value: Record<string, Literal | Array<ElementModel | string>>;
}

export interface EmbedModel {
  type: "EmbedCode";
}
