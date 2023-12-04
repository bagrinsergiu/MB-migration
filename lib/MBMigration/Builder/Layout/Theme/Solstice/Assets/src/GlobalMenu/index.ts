import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

const globalMenuExtractor = () => {
  const menuItem = document.querySelector("#main-navigation a");

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
        hoverMenuBgColorOpacity: bgColor.hex
      };
    }
  }
};

globalMenuExtractor();
