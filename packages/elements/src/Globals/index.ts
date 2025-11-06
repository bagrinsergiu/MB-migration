import { getStyleModel as getButtonModel } from "../Text/models/Button/utils/getModel";
import {
  getStyleModel as getIconModel,
  getParentStyles
} from "../Text/models/Icon/utils/getModel";
import { buttonSelector, iconSelector } from "../Text/utils/common";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { prefixed } from "utils/src/models/prefixed";

export const globalExtractor = (): void => {
  const icon = document.querySelector(iconSelector);
  const button = document.querySelector(buttonSelector);

  if (!icon && !button) {
    return;
  }

  if (icon) {
    const style = getNodeStyle(icon);
    const parentStyle = getParentStyles(icon);
    const model = getIconModel(style, parentStyle);
    window.iconModel = prefixed(model, "hover");
  }

  if (button) {
    const model = getButtonModel(button);
    window.buttonModel = prefixed(model, "hover");
  }
};
