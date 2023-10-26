import {
  exceptExtractingStyle,
  shouldExtractElement
} from "@/Text/utils/common";
import { mergeStyles } from "@/Text/utils/styles/mergeStyles";
import { Literal } from "utils";

interface Output {
  uid: string;
  tagName: string;
  styles: Record<string, Literal>;
}

export function extractParentElementsWithStyles(node: Element): Array<Output> {
  let result: Array<Output> = [];

  if (shouldExtractElement(node, exceptExtractingStyle)) {
    const uid = `uid-${Math.random()}-${Math.random()}`;
    node.setAttribute("data-uid", uid);

    result.push({
      uid,
      tagName: node.tagName,
      styles: mergeStyles(node)
    });
  }

  for (let i = 0; i < node.childNodes.length; i++) {
    let child = node.childNodes[i];
    result = result.concat(extractParentElementsWithStyles(child as Element));
  }

  return result;
}
