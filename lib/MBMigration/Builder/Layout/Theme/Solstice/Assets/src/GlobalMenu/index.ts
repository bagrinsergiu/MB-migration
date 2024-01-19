import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

const globalMenuExtractor = () => {
  const menuItem = document.querySelector(
    "#main-navigation li:not(.selected) a"
  );
  const subMenuItem = document.querySelector(
    "#main-navigation .sub-navigation li:not(.selected) a"
  );

  if (!menuItem) {
    return;
  }

  const span = menuItem.children[0];
  const styles = getNodeStyle(menuItem);
  const color = parseColorString(`${styles["color"]}`);

  if (color) {
    window.menuModel = {
      hoverColorHex: color.hex,
      hoverColorOpacity: color.opacity
    };
  }

  if (span) {
    const styles = getNodeStyle(span);
    const bgColor = parseColorString(`${styles["background-color"]}`);

    if (bgColor) {
      window.menuModel = {
        ...window.menuModel,
        hoverMenuBgColorHex: bgColor.hex,
        hoverMenuBgColorOpacity: bgColor.opacity
      };
    }
  }

  // For Submenu
  if (subMenuItem) {
    const span = subMenuItem.children[0];
    const styles = getNodeStyle(subMenuItem);
    const color = parseColorString(`${styles["color"]}`);

    if (color) {
      window.menuModel = {
        ...window.menuModel,
        hoverSubMenuColorHex: color.hex,
        hoverSubMenuColorOpacity: color.opacity,
        hoverSubMenuColorPalette: ""
      };
    }

    if (span) {
      const styles = getNodeStyle(span);
      const bgColor = parseColorString(`${styles["background-color"]}`);

      if (bgColor) {
        window.menuModel = {
          ...window.menuModel,
          hoverSubMenuBgColorHex: bgColor.hex,
          hoverSubMenuBgColorOpacity: bgColor.opacity,
          hoverSubMenuBgColorPalette: ""
        };
      }
    }
  }
};

globalMenuExtractor();
