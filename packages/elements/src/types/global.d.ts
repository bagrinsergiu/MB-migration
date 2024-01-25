import { Literal } from "utils";

export {};

declare global {
  const SELECTOR: string;
  const FAMILIES: Record<string, string>;
  const DEFAULT_FAMILY: string;
  const TARGET: string | undefined;

  interface Window {
    iconModel?: Record<string, Literal>;
    buttonModel?: Record<string, Literal>;

    // only for development
    isDev?: boolean;
    elementId?: boolean;
  }
}
