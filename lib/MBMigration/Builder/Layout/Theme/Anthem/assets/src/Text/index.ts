import { Entry, Output } from "../types/type";

function run(data: Entry): Output {
  return {
    warns: {},
    data: {}
  };
}

run({ selector: "{{selector}}", defaultFamily: "{{ family }}" });
