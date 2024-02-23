import { Literal } from "utils";

export {};

declare global {
  const SELECTOR: string;
  const FAMILIES: Record<string, string>;
  const DEFAULT_FAMILY: string;
  const STYLE_PROPERTIES: Array<string>;
  const TARGET: string | undefined;

  interface Window {
    isDev?: boolean;
    elementId?: string;
    menuModel?: Record<string, Literal>;
    brizy?: Record<string, unknown>;
  }
}
