import { run as globalMenuExtractor } from "./GlobalMenu";
import { run as getStyles } from "./StyleExtractor";
import { run as getText } from "./Text";
import { getMenuItem, getSubMenuItem } from "./Menu";

window.brizy = {
  globalMenuExtractor,
  getMenuItem,
  getSubMenuItem,
  getStyles,
  getText
};
