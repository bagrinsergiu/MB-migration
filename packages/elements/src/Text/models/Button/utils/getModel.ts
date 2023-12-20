import { ElementModel } from "../../../../types/type";
import { getGlobalButtonModel } from "../../../../utils/getGlobalButtonModel";
import { getHref, normalizeOpacity } from "../../../utils/common";
import { mPipe } from "fp-utilities";
import { Literal } from "utils";
import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { pipe } from "utils/src/fp/pipe";
import { onNullish } from "utils/src/onNullish";
import * as Obj from "utils/src/reader/object";
import * as Str from "utils/src/reader/string";
import { uuid } from "utils/src/uuid";

const getColor = mPipe(
  Obj.readKey("color"),
  Str.read,
  parseColorString,
  normalizeOpacity
);
const getBgColor = mPipe(
  Obj.readKey("background-color"),
  Str.read,
  parseColorString,
  normalizeOpacity
);
const getText = pipe(Obj.readKey("text"), Str.read, onNullish("BUTTON"));

export const getStyleModel = (node: Element): Record<string, Literal> => {
  const style = getNodeStyle(node);
  const color = getColor(style);
  const bgColor = getBgColor(style);
  const opacity = +style.opacity;

  return {
    ...(color && {
      colorHex: color.hex,
      colorOpacity: color.opacity,
      colorPalette: ""
    }),
    ...(bgColor && {
      bgColorHex: bgColor.hex,
      bgColorOpacity: isNaN(opacity) ? bgColor.opacity ?? 1 : opacity,
      bgColorPalette: "",
      bgColorType: "solid"
    })
  };
};

export const getModel = (node: Element): ElementModel => {
  const isLink = node.tagName === "A";
  const modelStyle = getStyleModel(node);
  const globalModel = getGlobalButtonModel();

  return {
    type: "Button",
    value: {
      _id: uuid(),
      _styles: ["button"],
      text: getText(node),
      ...globalModel,
      ...modelStyle,
      ...(isLink && {
        linkExternal: getHref(node),
        linkType: "external",
        linkExternalBlank: "on"
      })
    }
  };
};
