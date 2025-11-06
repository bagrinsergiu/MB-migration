import { Families } from "./type";
import { Literal, Primitive } from "utils/src/types";

export {};

declare global {
  const SELECTOR: string;
  const FAMILIES: Families;
  const DEFAULT_FAMILY: string;
  const TARGET: string | undefined;

  interface Window {
    iconModel?: Record<string, Literal>;
    buttonModel?: Record<string, Primitive>;

    // only for development
    isDev?: boolean;
    elementId?: boolean;
  }
}
