import { dom } from "./Dom";
import { getMenuItem, getSubMenuDropdown, getSubMenuItem } from "./Menu";
import {
  attributeRun as getAttributes,
  run as getStyles
} from "./StyleExtractor";
import { run as getText } from "./Text";

window.brizy = {
  getMenuItem,
  getSubMenuItem,
  getSubMenuDropdown,
  getAttributes,
  getStyles,
  getText,
  dom
};
