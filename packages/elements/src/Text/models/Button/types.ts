import { Families } from "../../../types/type";

export interface Data {
  node: Element;
  urlMap: Record<string, string>;
  families?: Families;
  defaultFamily?: string;
}
