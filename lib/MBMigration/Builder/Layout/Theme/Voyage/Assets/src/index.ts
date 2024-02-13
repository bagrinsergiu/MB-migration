import { globalMenuExtractor } from "./GlobalMenu";
import { globalExtractor } from "./Globals";
import { run as getMenu } from "./Menu";
import { run as getStyles } from "./StyleExtractor";

window.brizy = {
  globalMenuExtractor,
  globalExtractor,
  getMenu,
  getStyles
};
