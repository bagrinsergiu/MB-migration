import { getAccordion } from "elements/src/Accordion";
import { getData } from "elements/src/utils/getData";

// Only For Dev
// window.isDev = true;
const data = getData();
const output = getAccordion(data);

export default output;
