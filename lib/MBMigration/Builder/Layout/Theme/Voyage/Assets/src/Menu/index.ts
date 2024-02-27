import {getGlobalMenuModel} from "../utils/getGlobalMenuModel";
import {getModel} from "./model/getModel";
import {Entry, MenuEntry, Output} from "elements/src/types/type";
import {createData} from "elements/src/utils/getData";
import {parseColorString} from "utils/src/color/parseColorString";
import {prefixed} from "utils/src/models/prefixed";

interface NavData {
    section: Element;
    menuItem: Element;
    subMenuItem?: Element;
    families: Record<string, string>;
    defaultFamily: string;
}

const warns: Record<string, Record<string, string>> = {};

const getMenuV = (data: NavData) => {
    const {menuItem, subMenuItem, selector} = data;
    let v = {};

    v = getModel({
        node: menuItem,
        families: data.families,
        defaultFamily: data.defaultFamily
    });
    const globalStyle = getGlobalMenuModel();

    return {...globalStyle, ...v, itemPadding: 20};
};

const getSubMenuV = (data: Required<NavData>) => {
    const { section, subMenuItem} = data;

    const typography = getModel({
        node: subMenuItem,
        families: data.families,
        defaultFamily: data.defaultFamily
    });
    const submenuTypography = prefixed(typography, "subMenu");
    const baseStyle = window.getComputedStyle(section);
    const bgColor = parseColorString(baseStyle.backgroundColor) ?? {
        hex: "#ffffff",
        opacity: 1
    };

    return {
        ...submenuTypography,
        subMenuBgColorOpacity: bgColor.opacity,
        subMenuBgColorHex: bgColor.hex
    };
};

const run = (entry: MenuEntry): Output => {
    const {itemSelector, subItemSelector, sectionSelector, families, defaultFamily} = entry;
    const section = document.querySelector(sectionSelector);
    const menuItem = document.querySelector(itemSelector);
    const subMenuItem = document.querySelector(subItemSelector) ?? undefined;

    if (!menuItem) {
        return {
            error: `Element with selector ${itemSelector} not found`
        };
    }

    if (!section) {
        return {
            error: `Element with selector ${sectionSelector} not found`
        };
    }

    let data = getMenuV({section, menuItem, subMenuItem, families, defaultFamily});

    if (subMenuItem) {
        const _v = getSubMenuV({section, menuItem, subMenuItem, families, defaultFamily});
        data = {...data, ..._v};
    }

    return createData({data, warns});
};

// For development
// window.isDev = true;

export {run};
