import {createData} from "../utils/getData";

export const getNodeAttribute = ({selector, attributeName}) => {
  try {
    const element = document.querySelector(selector ?? "");

    if (!element) {
      return {
        data: false
      };
    }

    const value = element.getAttribute(attributeName);

    if (value) {
      return createData({
        data: value
      })
    }

    return {
      data: false
    }

  } catch (error) {
    return {
      data: false
    }
  }
};
