export {};

declare global {
  const SELECTOR: string;
  const FAMILIES: Record<string, string>;
  const DEFAULT_FAMILY: string;

  interface Window {
    isDev?: boolean;
  }
}
