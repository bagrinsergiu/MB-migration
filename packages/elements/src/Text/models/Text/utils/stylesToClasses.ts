import { Families } from "../../../../types/type";
import {
  defaultDesktopLineHeight,
  defaultMobileLineHeight,
  defaultTabletLineHeight,
  textAlign
} from "../../../utils/common";
import { getLetterSpacing } from "../../../utils/styles/getLetterSpacing";
import { Literal } from "utils";
import * as Num from "utils/src/reader/number";

export const stylesToClasses = (
  styles: Record<string, Literal>,
  families: Families,
  defaultFamily: string
): Array<string> => {
  const classes: Array<string> = [];

  Object.entries(styles).forEach(([key, value]) => {
    switch (key) {
      case "font-size": {
        const size = Math.round(Num.readInt(value) ?? 1);
        classes.push(`brz-fs-lg-${size}`);
        break;
      }
      case "font-family": {
        const fontFamily = `${value}`
          .replace(/['"\,]/g, "") // eslint-disable-line
          .replace(/\s/g, "_")
          .toLocaleLowerCase();

        if (!families[fontFamily]) {
          classes.push(`brz-ff-${defaultFamily}`, "brz-ft-upload");
          break;
        }
        const { name, type } = families[fontFamily];
        classes.push(`brz-ff-${name}`, `brz-ft-${type}`);
        break;
      }
      case "font-weight": {
        classes.push(`brz-fw-lg-${value}`);
        break;
      }
      case "text-align": {
        classes.push(`brz-text-lg-${textAlign[value] || "left"}`);
        break;
      }
      case "letter-spacing": {
        const letterSpacing = getLetterSpacing(`${value}`);
        classes.push(`brz-ls-lg-${letterSpacing}`);
        break;
      }
      case "line-height": {
        classes.push(`brz-lh-lg-${defaultDesktopLineHeight}`);
        classes.push(`brz-lh-sm-${defaultTabletLineHeight}`);
        classes.push(`brz-lh-xs-${defaultMobileLineHeight}`);
        break;
      }
      default:
        break;
    }
  });

  return classes;
};
