import { getStyleModel as getButtonModel } from "../Text/models/Button/utils/getModel";
// import { getStyleModel as getIconModel } from "../Text/models/Icon/utils/getModel";
import {
  buttonSelector // iconSelector
} from "../Text/utils/common";
import { prefixed } from "utils/src/models/prefixed";

export const globalExtractor = (): void => {
  // const icon = document.querySelector(iconSelector);
  const button = document.querySelector(buttonSelector);

  if (!button) {
    return;
  }

  // if (icon) {
  //   const model = getIconModel(icon);
  //   window.iconModel = prefixed(model, "hover");
  // }

  if (button) {
    const model = getButtonModel(button);
    window.buttonModel = prefixed(model, "hover");
  }
};
