export const cleanClassNames = (node: Element): void => {
  const classListExcepts = ["brz-"];
  const elementsWithClasses = node.querySelectorAll("[class]");
  elementsWithClasses.forEach(function (element) {
    element.classList.forEach((cls) => {
      if (!classListExcepts.some((except) => cls.startsWith(except))) {
        // TODO : Need to check if this code is needed
        // if (cls === "finaldraft_placeholder") {
        //   element.innerHTML = "";
        // }
        element.classList.remove(cls);
      }
    });

    if (element.classList.length === 0) {
      element.removeAttribute("class");
    }
  });
};
