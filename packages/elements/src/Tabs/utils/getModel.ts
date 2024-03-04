import { getModel as getCommonModel } from "../../utils/getModel";
import { parseColorString } from "utils/src/color/parseColorString";
import { getNodeStyle } from "utils/src/dom/getNodeStyle";
import { toCamelCase } from "utils/src/text/toCamelCase";

interface Model {
  node: Element;
  families: Record<string, string>;
  defaultFamily: string;
}

const v = {
  borderColorHex: undefined,
  borderColorOpacity: 1,
  borderWidth: 1
};

export const getModel = (data: Model) => {
  const { node } = data;
  const styles = getNodeStyle(node);
  const dic: Record<string, string | number> = {};

  Object.keys(v).forEach((key) => {
    switch (key) {
      case "borderColorHex": {
        const toHex = parseColorString(`${styles["border-bottom-color"]}`);

        dic[toCamelCase(key)] = toHex?.hex ?? "#000000";
        break;
      }
      case "borderColorOpacity": {
        const toHex = parseColorString(`${styles["border-bottom-color"]}`);
        const opacity = isNaN(+styles.opacity) ? 1 : styles.opacity;

        dic[toCamelCase(key)] = +(toHex?.opacity ?? opacity);
        break;
      }
      case "borderWidth": {
        const borderWidth = `${styles["border-bottom-width"]}`.replace(
          /px/g,
          ""
        );

        dic[toCamelCase(key)] = +(borderWidth ?? 1);
        break;
      }
      default: {
        dic[toCamelCase(key)] = styles[key];
      }
    }
  });

  return { ...getCommonModel(data), ...dic };
};
