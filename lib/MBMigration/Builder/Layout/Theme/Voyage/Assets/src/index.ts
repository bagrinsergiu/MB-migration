import { run as getAccordion } from "./Accordion";
import { dom } from "./Dom";
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
  getAccordion,
  getStyles,
  getText,
  dom
};
