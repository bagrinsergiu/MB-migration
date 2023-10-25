export interface Entry {
  selector: string;
  families: Record<string, string>;
  defaultFamily: string;
}

export type Output = string;
