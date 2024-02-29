import { run as getStyles } from "./StyleExtractor";
import { run as getText } from "./Text";
import { getMenuItem, getSubMenuItem } from "./Menu";

window.brizy = {
  getMenuItem,
  getSubMenuItem,
  getStyles,
  getText
};
