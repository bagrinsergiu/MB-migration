import { Families } from "elements/src/types/type";
import { Literal, Primitive } from "utils";

export {};

declare global {
  const SELECTOR: string;
  const FAMILIES: Families;
  const DEFAULT_FAMILY: string;
  const STYLE_PROPERTIES: Array<string>;
  const TARGET: string | undefined;

  interface Window {
    isDev?: boolean;
    elementId?: string;
    iconModel?: Record<string, Literal>;
    buttonModel?: Record<string, Primitive>;
    menuModel?: Record<string, Literal>;
    brizy?: Record<string, unknown>;
  }
}
