import { Output } from "../types/type";
import { createData } from "../utils/getData";

export const getRootPropertyStyles = (): Output => {
    const data = {};
    const styleSheets = document.styleSheets;

    for (let i = 0; i < styleSheets.length; i++) {
        const styleSheet = styleSheets[i];

        if (!styleSheet.href) {
            const cssRules = styleSheet.cssRules || styleSheet.rules;

            for (let j = 0; j < cssRules.length; j++) {
                const rule = cssRules[j];

                if (rule.selectorText === ":root") {
                    const declarations = rule.style;

                    for (let k = 0; k < declarations.length; k++) {
                        const property = declarations[k];
                        const value = declarations.getPropertyValue(property);
                        data[property] = value;
                    }
                }
            }
        }
    }

    return createData({ data });
};
