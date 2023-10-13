// const id = 3638618;
// const data = JSON.stringify({
//   selector: `[data-id='${id}']`,
//   families: {
//     "proxima_nova_proxima_nova_regular_sans-serif": "uid1111",
//     "helvetica_neue_helveticaneue_helvetica_arial_sans-serif": "uid2222",
//   },
//   defaultFontFamily: "helvetica_neue_helveticaneue_helvetica_arial_sans-serif",
// });

let warns = {};

const typographyV = {
  "font-family": undefined,
  "font-family-type": "uploaded",
  "font-weight": undefined,
  "font-size": undefined,
  "line-height": undefined,
  "letter-spacing": undefined,
  colorHex: "#484848",
  colorOpacity: 1,
  activeColorHex: undefined,
  activeColorOpacity: undefined,
};

const defaultSubMenuV = {
  subMenuBgColorHex: "#ffffff",
  subMenuBgColorOpacity: 1,
  subMenuColorHex: "#484848",
  subMenuColorOpacity: 1,
  subMenuFontFamily: "lato",
  subMenuFontFamilyType: "google",
  subMenuFontSize: 15,
  subMenuFontWeight: 400,
  subMenuLetterSpacing: 0,
  subMenuLineHeight: 1.2,
};

const rrgbToHex = (rrgbString) => {
  function rgbToHex(r, g, b) {
    r = Math.min(255, Math.max(0, Math.round(r)));
    g = Math.min(255, Math.max(0, Math.round(g)));
    b = Math.min(255, Math.max(0, Math.round(b)));

    const hexR = r.toString(16).padStart(2, "0");
    const hexG = g.toString(16).padStart(2, "0");
    const hexB = b.toString(16).padStart(2, "0");

    return `#${hexR}${hexG}${hexB}`.toUpperCase();
  }
  const rgbValues = rrgbString
    .slice(4, -1)
    .split(",")
    .map((value) => parseInt(value.trim()));

  if (rgbValues.length !== 3) {
    return undefined;
  }

  return rgbToHex(rgbValues[0], rgbValues[1], rgbValues[2]);
};

const capitalize = (str) => {
  return str.charAt(0).toUpperCase() + str.slice(1);
};

const toCamelCase = (key) => {
  const parts = key.split("-");
  for (let i = 1; i < parts.length; i++) {
    parts[i] = capitalize(parts[i]);
  }
  return parts.join("");
};

const prefixed = (v, prefix) => {
  return Object.entries(v).reduce((acc, [key, value]) => {
    let _key = prefix + capitalize(key);

    if (key.startsWith("active")) {
      _key = `active${capitalize(prefix)}${key.replace("active", "")}`;
    }

    return { ...acc, [_key]: value };
  }, {});
};

const getTypography = (data) => {
  const { v, styles, families, defaultFontFamily } = data;
  const dic = {};

  Object.keys(v).forEach((key) => {
    switch (key) {
      case "font-family": {
        const value = styles[key];
        const fontFamily = value
          .replace(/['"\,]/g, "")
          .replace(/\s/g, "_")
          .toLocaleLowerCase();

        if (!families[fontFamily]) {
          warns[fontFamily] = {
            message: `Font family not found ${fontFamily}`,
          };
          dic[toCamelCase(key)] = defaultFontFamily;
        } else {
          dic[toCamelCase(key)] = families[fontFamily];
        }
        break;
      }
      case "font-family-type": {
        dic[toCamelCase(key)] = "upload";
        break;
      }
      case "line-height": {
        const value = parseInt(styles[key]);
        if (!isNaN(value)) {
          dic[toCamelCase(key)] = value;
        } else {
          dic[toCamelCase(key)] = 1;
        }
        break;
      }
      case "font-size": {
        dic[toCamelCase(key)] = parseInt(styles[key]);
        break;
      }
      case "letter-spacing": {
        const value = styles[key];
        if (value === "normal") {
          dic[toCamelCase(key)] = 0;
        } else {
          // Remove 'px' and any extra whitespace
          const letterSpacingValue = value.replace(/px/g, "").trim();
          dic[toCamelCase(key)] = +letterSpacingValue;
        }
        break;
      }
      case "colorHex": {
        const toHex = rrgbToHex(styles["color"]);

        if (!toHex) {
          warns["COLOR"] = {
            message: "The color is not valid",
          };
        }

        dic[toCamelCase(key)] = toHex ?? "#000000";
        break;
      }
      case "colorOpacity": {
        break;
      }
      default: {
        dic[toCamelCase(key)] = styles[key];
      }
    }
  });

  return dic;
};

const getT = (data) => {
  const { v, node, families, defaultFontFamily } = data;

  let menu = {};

  const styles = window.getComputedStyle(node);
  const linkTypography = getTypography({
    v,
    styles,
    families,
    defaultFontFamily,
  });

  return { ...menu, ...linkTypography };
};

const getMenuV = (data) => {
  const { nav, selector } = data;
  const ul = nav.children[0];
  let v = {};

  if (!ul) {
    warns["menu"] = {
      message: `Navigation don't have ul in ${selector}`,
    };
    return v;
  }

  const li = ul.querySelector("li");
  if (!li) {
    warns["menu li"] = {
      message: `Navigation don't have ul > li in ${selector}`,
    };
    return v;
  }

  const link = ul.querySelector("li > a");
  if (!link) {
    warns["menu li a"] = {
      message: `Navigation don't have ul > li > a in ${selector}`,
    };
    return v;
  }
  const styles = window.getComputedStyle(li);
  const itemPadding = parseInt(styles.paddingLeft);

  v = getT({
    v: typographyV,
    selector: data.selector,
    node: link,
    families: data.families,
    defaultFontFamily: data.defaultFontFamily,
  });

  return { ...v, itemPadding: isNaN(itemPadding) ? 10 : itemPadding };
};

const getSubMenuV = (data) => {
  const { subNav, selector } = data;

  if (!subNav) {
    return defaultSubMenuV;
  }

  const ul = subNav.children[0];
  let v = {};

  if (!ul) {
    warns["submenu"] = {
      message: `Navigation don't have ul in ${selector}`,
    };
    return v;
  }

  const li = ul.querySelector("li");
  if (!li) {
    warns["submenu li"] = {
      message: `Navigation don't have ul > li in ${selector}`,
    };
    return v;
  }

  const link = ul.querySelector("li > a");
  if (!link) {
    warns["submenu li a"] = {
      message: `Navigation don't have ul > li > a in ${selector}`,
    };
    return v;
  }

  const typography = getT({
    v: typographyV,
    selector: data.selector,
    node: link,
    families: data.families,
    defaultFontFamily: data.defaultFontFamily,
  });
  const submenuTypography = prefixed(typography, "subMenu");
  const baseStyle = window.getComputedStyle(subNav);
  const bgColor =
    rrgbToHex(baseStyle.backgroundColor) ??
    defaultSubMenuV["subMenuBgColorHex"];

  return {
    ...defaultSubMenuV,
    ...submenuTypography,
    subMenuBgColorOpacity: 1,
    subMenuBgColorHex: bgColor,
  };
};

const getNavStyles = (data) => {
  const menuV = getMenuV(data);
  const subMenuV = getSubMenuV(data);
  return { ...subMenuV, ...menuV };
};

const run = (data) => {
  const node = document.querySelector(data.selector);

  if (!node) {
    return JSON.stringify({
      error: `Element with selector ${data.selector} not found`,
      warns: warns,
    });
  }

  const header = node;

  if (!header) {
    return JSON.stringify({
      error: `Element with selector ${data.selector} has no header`,
      warns,
    });
  }

  const nav = header.querySelector("#main-navigation");

  if (!nav) {
    return JSON.stringify({
      error: `Element with selector ${data.selector} has no nav`,
      warns,
    });
  }

  const subNav = header.querySelector("#selected-sub-navigation");

  const dataText = {
    nav: nav,
    subNav: subNav,
    selector: data.selector,
    families: data.families,
    defaultFontFamily: data.defaultFontFamily,
  };

  return JSON.stringify({
    menu: getNavStyles(dataText),
    warns: warns,
  });
};
