import { run as getAccordion } from "./Accordion";
import { run as globalMenuExtractor } from "./GlobalMenu";
import { run as getImage } from "./Image";
import { run as getMenu } from "./Menu";
import { run as getStyles } from "./StyleExtractor";
import { run as getTabs } from "./Tabs";
import { run as getText } from "./Text";

window.brizy = {
  globalMenuExtractor,
  getMenu,
  getStyles,
  getText,
  getImage,
  getAccordion,
  getTabs
};
