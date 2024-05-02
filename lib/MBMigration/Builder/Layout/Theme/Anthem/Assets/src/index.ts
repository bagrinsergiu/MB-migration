import { run as getAccordion } from "./Accordion";
import { dom } from "./Dom";
import { run as globalMenuExtractor } from "./GlobalMenu";
import { run as getImage } from "./Image";
import { run as getMenu } from "./Menu";
import {
  attributeRun as getAttributes,
  run as getStyles
} from "./StyleExtractor";
import { run as getTabs } from "./Tabs";
import { run as getText } from "./Text";

window.brizy = {
  globalMenuExtractor,
  getMenu,
  getStyles,
  getAttributes,
  getText,
  getImage,
  getAccordion,
  getTabs,
  dom
};
