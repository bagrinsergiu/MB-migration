import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";

const run = () => {
  const menuItem = document.querySelector(
    "#main-navigation>ul>li:not(.selected) a"
  );

  if (!menuItem) {
    return;
  }

  const styles = getNodeStyle(menuItem);
  const color = parseColorString(`${styles["color"]}`);
  const opacity = +styles["opacity"];

  if (color) {
    window.menuModel = {
      hoverColorHex: color.hex,
      hoverColorOpacity: isNaN(opacity) ? color.opacity ?? 1 : opacity
    };
  }
};

export { run };
