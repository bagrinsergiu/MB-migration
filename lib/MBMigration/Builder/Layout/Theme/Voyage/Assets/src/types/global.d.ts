declare const SELECTOR: string;
declare const FAMILIES: Record<string, string>;
declare const DEFAULT_FAMILY: string;
declare const STYLE_PROPERTIES: Array<string>;

declare global {
  namespace NodeJS {
    interface Global {
      isDev?: boolean;
    }
  }
  interface Window {
    isDev?: boolean;
  }
}
