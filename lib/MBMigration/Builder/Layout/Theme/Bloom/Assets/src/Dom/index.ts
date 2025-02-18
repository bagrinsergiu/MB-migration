import { addNodeClass } from "elements/src/Dom/addNodeClass";
import { detectSubpalette } from "elements/src/Dom/detectSubpalette";
import { extractAllFontFamilies } from "elements/src/Dom/extractAllFontFamilies";
import { getNodeAttribute } from "elements/src/Dom/getNodeAttribute";
import { getNodeText } from "elements/src/Dom/getNodeText";
import { getRootPropertyStyles } from "elements/src/Dom/getRootPropertyStyles";
import { hasNode } from "elements/src/Dom/hasNode";
import { removeNodeClass } from "elements/src/Dom/removeNodeClass";

export const dom = {
  hasNode,
  getNodeText,
  getRootPropertyStyles,
  detectSubpalette,
  addNodeClass,
  removeNodeClass,
  getNodeAttribute,
  extractAllFontFamilies
};
