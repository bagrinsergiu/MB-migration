import {parseColorString} from "utils/src/color/parseColorString";
import {getNodeStyle} from "utils/src/dom/getNodeStyle";
import {Entry} from "elements/src/types/type";

const run = (entry: Entry) => {

    const {selector} = entry;
    const menuItem = document.querySelector(selector);

    if (!menuItem) {
        return {
            error: `Element with selector: '${selector}' was not found`
        };
    }

    const styles = getNodeStyle(menuItem);
    const color = parseColorString(`${styles["color"]}`);
    const opacity = +styles["opacity"];

    if (color) {
        const activeColorOpacity = isNaN(opacity) ? color.opacity ?? 1 : opacity;
        return window.menuModel = {
            hoverColorHex: color.hex,
            hoverColorOpacity: activeColorOpacity,
            activeColorHex: color.hex,
            activeColorOpacity: activeColorOpacity,
            hoverSubMenuColorHex:color.hex,
            hoverSubMenuColorOpacity:activeColorOpacity
        };
    }
};

export {run};
