import { run as globalMenuExtractor } from "./GlobalMenu";
import { run as getMenu } from "./Menu";
import { run as getStyles } from "./StyleExtractor";
import { run as getText } from "./Text";

window.brizy = {
  globalMenuExtractor,
  getMenu,
  getStyles,
  getText
};
