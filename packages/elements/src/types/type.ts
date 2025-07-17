import { Literal } from "utils";

export interface Family {
  name: string;
  type: string;
}

export type Families = Record<string, Family>;

export interface Entry {
  selector: string;
  families: Families;
  defaultFamily: string;
  urlMap: Record<string, string>;
  className?: string;
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
  itemActiveSelector?: MenuItemSelector;
  itemMobileNavSelector?: MenuItemSelector;
  families: Families;
  defaultFamily: string;
  isBgHoverItemMenu?: boolean;
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

export interface Error {
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
