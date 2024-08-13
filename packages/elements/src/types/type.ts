import { Literal } from "utils";

export interface Entry {
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
  urlMap: Record<string, string>;
}

export interface MenuItemSelector {
  selector: string;
  pseudoEl: string;
}

export interface MenuItemElement {
  item: Element;
  pseudoEl: string;
}

export interface MenuItemEntry {
  hover: boolean;
  itemSelector: MenuItemSelector;
  itemBgSelector: MenuItemSelector;
  itemPaddingSelector: MenuItemSelector;
  itemMobileBtnSelector?: MenuItemSelector;
  itemMobileNavSelector?: MenuItemSelector;
  families: Record<string, string>;
  defaultFamily: string;
}

export interface MenuEntry {
  sectionSelector: string;
  itemSelector: string;
  subItemSelector: string;
  families: Record<string, string>;
  defaultFamily: string;
}

export interface OutputData {
  data: unknown;
  warns?: Record<string, Record<string, string>>;
}

export interface Data {
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
