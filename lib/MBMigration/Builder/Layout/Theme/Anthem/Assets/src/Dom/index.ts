import { addNodeClass } from "elements/src/Dom/addNodeClass";
import { detectSubpalette } from "elements/src/Dom/detectSubpalette";
import { getNodeText } from "elements/src/Dom/getNodeText";
import { getRootPropertyStyles } from "elements/src/Dom/getRootPropertyStyles";
import { hasNode } from "elements/src/Dom/hasNode";
import { removeNodeClass } from "elements/src/Dom/removeNodeClass";
import { getNodeAttribute } from "elements/src/Dom/getNodeAttribute";

export const dom = {
  hasNode,
  getNodeText,
  getRootPropertyStyles,
  detectSubpalette,
  addNodeClass,
  removeNodeClass,
  getNodeAttribute
};
