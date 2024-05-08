import { Output } from "../types/type";
import { createData } from "../utils/getData";

export const getRootPropertyStyles = (): Output => {
  const data: { [key: string]: string } = {}; // Define the type for 'data'
  const styleSheets = document.styleSheets;

  for (let i = 0; i < styleSheets.length; i++) {
    const styleSheet = styleSheets[i];

    if (!styleSheet.href) {
      const cssRules = (styleSheet as CSSStyleSheet).cssRules || (styleSheet as CSSStyleSheet).rules;

      for (let j = 0; j < cssRules.length; j++) {
        const rule = cssRules[j] as CSSStyleRule; // Narrow down to CSSStyleRule

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
