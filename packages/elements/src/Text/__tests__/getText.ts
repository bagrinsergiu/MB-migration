import { getText } from "../";
import { Entry, Output } from "../../types/type";
import { describe, expect, test } from "@jest/globals";

const entry: Entry = {
  selector: "test",
  defaultFamily: "lato",
  families: {}
};

describe("testing 'getText' function", () => {
  test.each<[Entry, Output]>([
    // Default
    [entry, { error: "Element with selector test not found" }]
  ])("getText nr %#", (entry, output) => {
    expect(getText(entry)).toEqual(output);
  });
});
