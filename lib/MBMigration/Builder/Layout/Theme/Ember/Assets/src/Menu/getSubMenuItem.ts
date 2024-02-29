import {MenuItemEntry, Output} from "elements/src/types/type";
import {createData} from "elements/src/utils/getData";
import {getModel} from "./utils/getModel";
import {prefixed} from "utils/src/models/prefixed";

interface MenuItemData {
    item: Element;
    itemBg: Element;
    families: Record<string, string>;
    defaultFamily: string;
}

const getV = (entry: MenuItemData) => {
    const {item, itemBg, families, defaultFamily} = entry;

    const model = {
        "color-hex": undefined,
        "color-opacity": undefined
    };

    const v = getModel({
        node: item,
        modelDefaults: model,
        families: families,
        defaultFamily: defaultFamily
    });

    const bgModel = {
        "bg-color-hex": undefined,
        "bg-color-opacity": undefined
    };

    const bgV = getModel({
        node: itemBg,
        modelDefaults: bgModel,
        families: families,
        defaultFamily: defaultFamily
    });

    return {...prefixed(v, "subMenu"), ...prefixed(bgV, "subMenu")};
};

const getHoverV = (entry: MenuItemData) => {
    const {item, itemBg, families, defaultFamily} = entry;

    const model = {
        "hover-color-hex": undefined,
        "hover-color-opacity": undefined,
    };

    const v = getModel({
        node: item,
        modelDefaults: model,
        families: families,
        defaultFamily: defaultFamily
    });

    const bgModel = {
        "hover-menu-bg-color-hex": undefined,
        "hover-menu-bg-color-opacity": 1,
    };

    const bgV = getModel({
        node: itemBg,
        modelDefaults: bgModel,
        families: families,
        defaultFamily: defaultFamily
    });
    return {...prefixed(v, "subMenu"), ...prefixed(bgV, "subMenu")};
};

const getSubMenuItem = (entry: MenuItemEntry): Output => {
    const {itemSelector, itemBgSelector, hover, families, defaultFamily} = entry;
    const item = document.querySelector(itemSelector);
    const itemBg = document.querySelector(itemBgSelector);

    if (!item) {
        return {
            error: `Element with selector "${itemSelector}" not found`
        };
    }
    if (!itemBg) {
        return {
            error: `Element with selector "${itemBgSelector}" not found`
        };
    }
    let data = {};
    if (!hover)
        data = getV({item, itemBg, families, defaultFamily});
    else
        data = getHoverV({item, itemBg, families, defaultFamily});

    return createData({data});
};

// For development
// window.isDev = true;

export {getSubMenuItem};