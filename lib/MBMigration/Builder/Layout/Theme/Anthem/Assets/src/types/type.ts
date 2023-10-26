export interface Entry {
  selector: string;
  defaultFamily: string;
}
export interface Output {
  warns: Record<string, string>;
  data: Record<string, string>;
}
