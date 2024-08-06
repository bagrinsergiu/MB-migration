import { ElementModel } from "../../../../types/type";
import { getGlobalButtonModel } from "../../../../utils/getGlobalButtonModel";
import {
  getHref,
  getTarget,
  iconSelector,
  normalizeOpacity
} from "../../../utils/common";
import { getModel as getIconModel } from "../../Icon/utils/getModel";
import { Data } from "../types";
import { getFontStyles } from "./getFontStyles";
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

const getColor = mPipe(Obj.readKey("color"), Str.read, parseColorString);
const getBgColor = mPipe(
  Obj.readKey("background-color"),
  Str.read,
  parseColorString
);

const getBorderWidth = mPipe(Obj.readKey("border-width"), Num.read);
const getBorderRadius = mPipe(
  Obj.readKey("border-top-left-radius"),
  Str.read,
  parseFloat
);
const getTransform = mPipe(Obj.readKey("text-transform"), Str.read);
const getText = pipe(Obj.readKey("text"), Str.read, onNullish("BUTTON"));

const getBgColorOpacity = (color: Color, opacity: number): number => {
  if (color.opacity && +color.opacity === 0) {
    return 0;
  }

  return +(isNaN(opacity) ? color.opacity ?? 1 : opacity);
};

export const getStyleModel = (
  node: Element,
  defaultFamily?: Data["defaultFamily"],
  families?: Data["families"]
): Record<string, Literal> => {
  const style = getNodeStyle(node);
  const color = getColor(style);
  const bgColor = getBgColor(style);
  const opacity = +style.opacity;
  const borderWidth = getBorderWidth(style);
  const borderRadius = getBorderRadius(style);
  const { fontFamily, fontSize, fontWeight } = getFontStyles({
    node,
    style,
    defaultFamily,
    families
  });

  return {
    ...(color && {
      colorHex: normalizeOpacity({
        hex: color.hex,
        opacity: color.opacity ?? String(opacity)
      }).hex,
      colorOpacity: normalizeOpacity({
        hex: color.hex,
        opacity: color.opacity ?? String(opacity)
      }).opacity,
      colorPalette: ""
    }),
    ...(bgColor && {
      bgColorHex: bgColor.hex,
      bgColorOpacity: getBgColorOpacity(bgColor, opacity),
      bgColorPalette: "",
      ...(getBgColorOpacity(bgColor, opacity) === 0
        ? { bgColorType: "none", hoverBgColorType: "solid" }
        : { bgColorType: "solid", hoverBgColorType: "solid" }),
      hoverBgColorHex: bgColor.hex,
      hoverBgColorOpacity: 0.8,
      hoverBgColorPalette: ""
    }),
    ...(borderRadius && { borderRadiusType: "custom", borderRadius }),
    ...(borderWidth === undefined && { borderStyle: "none" }),
    ...(fontFamily && { fontFamily, fontFamilyType: "upload" }),
    ...(fontSize && { fontSize }),
    ...(fontWeight && { fontWeight })
  };
};

export const getModel = ({
  node,
  defaultFamily,
  families,
  urlMap
}: Data): ElementModel => {
  let iconModel: Record<string, Literal> = {};
  const isLink = node.tagName === "A";

  const modelStyle = getStyleModel(node, defaultFamily, families);
  const globalModel = getGlobalButtonModel();
  const textTransform = getTransform(getNodeStyle(node));
  const icon = node.querySelector(iconSelector);

  if (icon) {
    const model = getIconModel(icon, urlMap);
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

  const link = getTarget(node);
  const targetType = link === "_self" ? "off" : "on";

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

  const href = getHref(node);
  const mappedHref = href && urlMap[href] !== undefined ? urlMap[href] : href;

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
        linkExternal: mappedHref,
        linkType: "external",
        linkExternalBlank: targetType
      })
    }
  };
};
