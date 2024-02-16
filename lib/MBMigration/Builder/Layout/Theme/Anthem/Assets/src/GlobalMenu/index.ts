import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

const globalMenuExtractor = () => {
  const menuItem = document.querySelector(
    "#main-navigation li:not(.selected) a"
  );

  if (!menuItem) {
    return;
  }

  const styles = getNodeStyle(menuItem);
  const color = parseColorString(`${styles["color"]}`);

  if (color) {
    window.menuModel = {
      hoverColorHex: color.hex,
      hoverColorOpacity: color.opacity ?? 1
    };
  }
};

globalMenuExtractor();
