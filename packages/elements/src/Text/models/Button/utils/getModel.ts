import { getHref } from "@/Text/utils/common";
import { ElementModel } from "@/types/type";
import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { pipe } from "utils/src/fp/pipe";
import { onNullish } from "utils/src/onNullish";
import * as Obj from "utils/src/reader/object";
import * as Str from "utils/src/reader/string";
import { uuid } from "utils/src/uuid";

const getText = pipe(Obj.readKey("text"), Str.read, onNullish("BUTTON"));

export const getModel = (node: Element): ElementModel => {
  const isLink = node.tagName === "A";
  const style = getNodeStyle(node);
  const color = parseColorString(style.color);
  const bgColor = parseColorString(style["background-color"]);
  const opacity = +style.opacity;

  return {
    type: "Button",
    value: {
      _id: uuid(),
      _styles: ["button"],
      bgColorHex: bgColor?.hex ?? "#ffffff",
      bgColorOpacity: isNaN(opacity) ? bgColor?.opacity ?? 1 : opacity,
      bgColorType: "solid",
      colorHex: color?.hex ?? "#ffffff",
      colorOpacity: color?.opacity ?? 1,
      text: getText(node),
      ...(isLink && {
        linkExternal: getHref(node),
        linkType: "external",
        linkExternalBlank: "on"
      })
    }
  };
};
