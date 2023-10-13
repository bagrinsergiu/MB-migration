// const id = 111;
// const data = {
//   selector: `[data-id='${id}']`,
//   styleProperties: JSON.stringify(["background-color"]),
// };
const data = {
  selector: "{{selector}}",
  styleProperties: JSON.stringify("{{styleProperties}}"),
};

const run = (data) => {
  const { selector, styleProperties: _properties } = data;
  const styles = {};

  try {
    const styleProperties = JSON.parse(_properties);
    const element = document.querySelector(selector);

    if (!element) {
      return JSON.stringify({
        error: "Element with selector ${selector} not found,",
      });
    }

    if (element) {
      const computedStyles = window.getComputedStyle(element);

      for (const styleName of computedStyles) {
        if (styleProperties.includes(styleName)) {
          styles[styleName] = computedStyles[styleName];
        }
      }
    }

    return JSON.stringify({
      style: styles,
    });
  } catch (e) {
    return JSON.stringify({
      error: `Invalid JSON ${e},`,
    });
  }
};

run(data);
