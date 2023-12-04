import { Literal } from "utils";

export {};

declare global {
  const SELECTOR: string;
  const FAMILIES: Record<string, string>;
  const DEFAULT_FAMILY: string;
  const STYLE_PROPERTIES: Array<string>;

  interface Window {
    isDev?: boolean;
    iconModel?: Record<string, Literal>;
    buttonModel?: Record<string, Literal>;
  }
}
