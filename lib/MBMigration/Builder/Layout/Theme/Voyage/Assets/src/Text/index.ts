import { getText } from "elements/src/Text";
import { getData } from "elements/src/utils/getData";

// Only For Dev
// window.isDev = true

const data = getData();
const output = getText(data);

export default output;
