import { getNodeStyle } from "utils/src/dom/getNodeStyle";

export const cleanClassNames = (node: Element): void => {
  const classListExcepts = ["brz-"];
  const elementsWithClasses = node.querySelectorAll("[class]");
  elementsWithClasses.forEach(function (element) {
    element.classList.forEach((cls) => {
      if (!classListExcepts.some((except) => cls.startsWith(except))) {
        const elementStyle = getNodeStyle(element);
        const isHidden = elementStyle["display"] === "none";

        if (cls === "finaldraft_placeholder" && isHidden) {
          element.innerHTML = "";
        }
        element.classList.remove(cls);
      }
    });

    if (element.classList.length === 0) {
      element.removeAttribute("class");
    }
  });
};
