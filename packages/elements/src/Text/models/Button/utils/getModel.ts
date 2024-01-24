import { ElementModel } from "../../../../types/type";
import { getGlobalButtonModel } from "../../../../utils/getGlobalButtonModel";
import { getHref, iconSelector, normalizeOpacity } from "../../../utils/common";
import { getModel as getIconModel } from "../../Icon/utils/getModel";
import { mPipe } from "fp-utilities";
import { Literal } from "utils";
import { Color, parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { pipe } from "utils/src/fp/pipe";
import { onNullish } from "utils/src/onNullish";
import * as Num from "utils/src/reader/number";
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
const getBorderWidth = mPipe(Obj.readKey("border-width"), Num.read);
const getTransform = mPipe(Obj.readKey("text-transform"), Str.read);
const getText = pipe(Obj.readKey("text"), Str.read, onNullish("BUTTON"));

const getBgColorOpacity = (color: Color, opacity: number): number => {
  return +(isNaN(opacity) ? color.opacity ?? 1 : opacity);
};

export const getStyleModel = (node: Element): Record<string, Literal> => {
  const style = getNodeStyle(node);
  const color = getColor(style);
  const bgColor = getBgColor(style);
  const opacity = +style.opacity;
  const borderWidth = getBorderWidth(style);

  return {
    ...(color && {
      colorHex: color.hex,
      colorOpacity: +color.opacity,
      colorPalette: ""
    }),
    ...(bgColor && {
      bgColorHex: bgColor.hex,
      bgColorOpacity: getBgColorOpacity(bgColor, opacity),
      bgColorPalette: "",
      ...(getBgColorOpacity(bgColor, opacity) === 0
        ? { bgColorType: "none" }
        : { bgColorType: "solid" })
    }),
    ...(borderWidth === undefined && { borderStyle: "none" })
  };
};

export const getModel = (node: Element): ElementModel => {
  const isLink = node.tagName === "A";
  const modelStyle = getStyleModel(node);
  const globalModel = getGlobalButtonModel();
  const textTransform = getTransform(getNodeStyle(node));
  let iconModel: Record<string, Literal> = {};
  const icon = node.querySelector(iconSelector);

  if (icon) {
    const model = getIconModel(icon);
    const name = Str.read(model.value.name);

    // Remove the html for Icon
    // have conflicts with text of button
    icon.remove();

    if (name) {
      iconModel = {
        iconName: name
      };
    }
  }

  let text = getText(node);

  switch (textTransform) {
    case "uppercase": {
      text = text.toUpperCase();
      break;
    }
    case "lowercase": {
      text = text.toUpperCase();
      break;
    }
  }

  return {
    type: "Button",
    value: {
      _id: uuid(),
      _styles: ["button"],
      text,
      ...globalModel,
      ...modelStyle,
      ...iconModel,
      ...(isLink && {
        linkExternal: getHref(node),
        linkType: "external",
        linkExternalBlank: "on"
      })
    }
  };
};
