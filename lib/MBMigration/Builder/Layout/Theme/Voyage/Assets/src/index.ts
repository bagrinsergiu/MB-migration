import { getMenuItem, getSubMenuItem } from "./Menu";
import {
  attributeRun as getAttributes,
  run as getStyles
} from "./StyleExtractor";
import { run as getText } from "./Text";

window.brizy = {
  getMenuItem,
  getSubMenuItem,
  getAttributes,
  getStyles,
  getText
};
