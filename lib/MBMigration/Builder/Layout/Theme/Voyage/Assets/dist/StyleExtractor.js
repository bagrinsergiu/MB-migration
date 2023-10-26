"use strict";
var scripts;
(scripts ||= {}).StyleExtractor = (() => {
  var __defProp = Object.defineProperty;
  var __getOwnPropDesc = Object.getOwnPropertyDescriptor;
  var __getOwnPropNames = Object.getOwnPropertyNames;
  var __hasOwnProp = Object.prototype.hasOwnProperty;
  var __export = (target, all) => {
    for (var name in all)
      __defProp(target, name, { get: all[name], enumerable: true });
  };
  var __copyProps = (to, from, except, desc) => {
    if (from && typeof from === "object" || typeof from === "function") {
      for (let key of __getOwnPropNames(from))
        if (!__hasOwnProp.call(to, key) && key !== except)
          __defProp(to, key, { get: () => from[key], enumerable: !(desc = __getOwnPropDesc(from, key)) || desc.enumerable });
    }
    return to;
  };
  var __toCommonJS = (mod) => __copyProps(__defProp({}, "__esModule", { value: true }), mod);

  // src/StyleExtractor/index.ts
  var StyleExtractor_exports = {};
  __export(StyleExtractor_exports, {
    default: () => StyleExtractor_default
  });

  // ../../../../../../../packages/elements/src/utils/getData.ts
  var createData = (output2) => {
    return JSON.stringify(output2);
  };

  // ../../../../../../../packages/utils/src/dom/getNodeStyle.ts
  var getNodeStyle = (node) => {
    const computedStyles = window.getComputedStyle(node);
    const styles = {};
    Object.values(computedStyles).forEach((key) => {
      styles[key] = computedStyles.getPropertyValue(key);
    });
    return styles;
  };

  // ../../../../../../../packages/elements/src/StyleExtractor/index.ts
  var styleExtractor = (data2) => {
    const { selector, styleProperties } = data2;
    const styles = {};
    const element = document.querySelector(selector);
    if (!element) {
      return JSON.stringify({
        error: `Element with selector ${selector} not found`
      });
    }
    const computedStyles = getNodeStyle(element);
    styleProperties.forEach((styleName) => {
      styles[styleName] = computedStyles[styleName];
    });
    return createData({ data: styles });
  };

  // src/StyleExtractor/index.ts
  var getData = () => {
    try {
      return {
        selector: "{{selector}}",
        families: JSON.parse("{{families}}"),
        styleProperties: JSON.parse("{{styleProperties}}"),
        defaultFamily: "{{defaultFamily}}"
      };
    } catch (e) {
      const familyMock = {
        lato: "uid_for_lato",
        roboto: "uid_for_roboto"
      };
      const mock = {
        selector: ".my-div",
        families: familyMock,
        defaultFamily: "lato"
      };
      throw new Error(
        JSON.stringify({
          error: `Invalid JSON ${e}`,
          details: `Must be: ${JSON.stringify(mock)}`
        })
      );
    }
  };
  var data = getData();
  var output = styleExtractor(data);
  var StyleExtractor_default = output;
  return __toCommonJS(StyleExtractor_exports);
})();
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vc3JjL1N0eWxlRXh0cmFjdG9yL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy91dGlscy9nZXREYXRhLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9TdHlsZUV4dHJhY3Rvci9pbmRleC50cyJdLAogICJzb3VyY2VzQ29udGVudCI6IFsiaW1wb3J0IHsgc3R5bGVFeHRyYWN0b3IsIERhdGEgfSBmcm9tIFwiZWxlbWVudHMvc3JjL1N0eWxlRXh0cmFjdG9yXCI7XG5pbXBvcnQgeyBFbnRyeSB9IGZyb20gXCJlbGVtZW50cy9zcmMvdHlwZXMvdHlwZVwiO1xuXG5jb25zdCBnZXREYXRhID0gKCk6IERhdGEgPT4ge1xuICB0cnkge1xuICAgIHJldHVybiB7XG4gICAgICBzZWxlY3RvcjogXCJ7e3NlbGVjdG9yfX1cIixcbiAgICAgIGZhbWlsaWVzOiBKU09OLnBhcnNlKFwie3tmYW1pbGllc319XCIpLFxuICAgICAgc3R5bGVQcm9wZXJ0aWVzOiBKU09OLnBhcnNlKFwie3tzdHlsZVByb3BlcnRpZXN9fVwiKSxcbiAgICAgIGRlZmF1bHRGYW1pbHk6IFwie3tkZWZhdWx0RmFtaWx5fX1cIlxuICAgIH07XG4gIH0gY2F0Y2ggKGUpIHtcbiAgICBjb25zdCBmYW1pbHlNb2NrID0ge1xuICAgICAgbGF0bzogXCJ1aWRfZm9yX2xhdG9cIixcbiAgICAgIHJvYm90bzogXCJ1aWRfZm9yX3JvYm90b1wiXG4gICAgfTtcbiAgICBjb25zdCBtb2NrOiBFbnRyeSA9IHtcbiAgICAgIHNlbGVjdG9yOiBcIi5teS1kaXZcIixcbiAgICAgIGZhbWlsaWVzOiBmYW1pbHlNb2NrLFxuICAgICAgZGVmYXVsdEZhbWlseTogXCJsYXRvXCJcbiAgICB9O1xuXG4gICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgSlNPTi5zdHJpbmdpZnkoe1xuICAgICAgICBlcnJvcjogYEludmFsaWQgSlNPTiAke2V9YCxcbiAgICAgICAgZGV0YWlsczogYE11c3QgYmU6ICR7SlNPTi5zdHJpbmdpZnkobW9jayl9YFxuICAgICAgfSlcbiAgICApO1xuICB9XG59O1xuXG5jb25zdCBkYXRhID0gZ2V0RGF0YSgpO1xuY29uc3Qgb3V0cHV0ID0gc3R5bGVFeHRyYWN0b3IoZGF0YSk7XG5cbmV4cG9ydCBkZWZhdWx0IG91dHB1dDtcbiIsICJpbXBvcnQgeyBFbnRyeSwgT3V0cHV0LCBPdXRwdXREYXRhIH0gZnJvbSBcIkAvdHlwZXMvdHlwZVwiO1xuXG5leHBvcnQgY29uc3QgZ2V0RGF0YSA9ICgpOiBFbnRyeSA9PiB7XG4gIHRyeSB7XG4gICAgcmV0dXJuIHdpbmRvdy5pc0RldlxuICAgICAgPyB7XG4gICAgICAgICAgc2VsZWN0b3I6IGBbZGF0YS1pZD0nJHsxNjYzMDEzMX0nXWAsXG4gICAgICAgICAgZmFtaWxpZXM6IHtcbiAgICAgICAgICAgIFwicHJveGltYV9ub3ZhX3Byb3hpbWFfbm92YV9yZWd1bGFyX3NhbnMtc2VyaWZcIjogXCJ1aWQxMTExXCIsXG4gICAgICAgICAgICBcImhlbHZldGljYV9uZXVlX2hlbHZldGljYW5ldWVfaGVsdmV0aWNhX2FyaWFsX3NhbnMtc2VyaWZcIjogXCJ1aWQyMjIyXCJcbiAgICAgICAgICB9LFxuICAgICAgICAgIGRlZmF1bHRGYW1pbHk6IFwibGF0b1wiXG4gICAgICAgIH1cbiAgICAgIDoge1xuICAgICAgICAgIHNlbGVjdG9yOiBcInt7c2VsZWN0b3J9fVwiLFxuICAgICAgICAgIGZhbWlsaWVzOiBKU09OLnBhcnNlKFwie3tmYW1pbGllc319XCIpLFxuICAgICAgICAgIGRlZmF1bHRGYW1pbHk6IFwie3tkZWZhdWx0RmFtaWx5fX1cIlxuICAgICAgICB9O1xuICB9IGNhdGNoIChlKSB7XG4gICAgY29uc3QgZmFtaWx5TW9jayA9IHtcbiAgICAgIGxhdG86IFwidWlkX2Zvcl9sYXRvXCIsXG4gICAgICByb2JvdG86IFwidWlkX2Zvcl9yb2JvdG9cIlxuICAgIH07XG4gICAgY29uc3QgbW9jazogRW50cnkgPSB7XG4gICAgICBzZWxlY3RvcjogXCIubXktZGl2XCIsXG4gICAgICBmYW1pbGllczogZmFtaWx5TW9jayxcbiAgICAgIGRlZmF1bHRGYW1pbHk6IFwibGF0b1wiXG4gICAgfTtcblxuICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgIEpTT04uc3RyaW5naWZ5KHtcbiAgICAgICAgZXJyb3I6IGBJbnZhbGlkIEpTT04gJHtlfWAsXG4gICAgICAgIGRldGFpbHM6IGBNdXN0IGJlOiAke0pTT04uc3RyaW5naWZ5KG1vY2spfWBcbiAgICAgIH0pXG4gICAgKTtcbiAgfVxufTtcblxuZXhwb3J0IGNvbnN0IGNyZWF0ZURhdGEgPSAob3V0cHV0OiBPdXRwdXREYXRhKTogT3V0cHV0ID0+IHtcbiAgcmV0dXJuIEpTT04uc3RyaW5naWZ5KG91dHB1dCk7XG59O1xuIiwgImltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwiQC90eXBlc1wiO1xuXG5leHBvcnQgY29uc3QgZ2V0Tm9kZVN0eWxlID0gKFxuICBub2RlOiBIVE1MRWxlbWVudCB8IEVsZW1lbnRcbik6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+ID0+IHtcbiAgY29uc3QgY29tcHV0ZWRTdHlsZXMgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShub2RlKTtcbiAgY29uc3Qgc3R5bGVzOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPiA9IHt9O1xuXG4gIE9iamVjdC52YWx1ZXMoY29tcHV0ZWRTdHlsZXMpLmZvckVhY2goKGtleSkgPT4ge1xuICAgIHN0eWxlc1trZXldID0gY29tcHV0ZWRTdHlsZXMuZ2V0UHJvcGVydHlWYWx1ZShrZXkpO1xuICB9KTtcblxuICByZXR1cm4gc3R5bGVzO1xufTtcbiIsICJpbXBvcnQgeyBFbnRyeSwgT3V0cHV0IH0gZnJvbSBcIkAvdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgY3JlYXRlRGF0YSB9IGZyb20gXCJAL3V0aWxzL2dldERhdGFcIjtcbmltcG9ydCB7IExpdGVyYWwgfSBmcm9tIFwidXRpbHNcIjtcbmltcG9ydCB7IGdldE5vZGVTdHlsZSB9IGZyb20gXCJ1dGlscy9zcmMvZG9tL2dldE5vZGVTdHlsZVwiO1xuXG5leHBvcnQgaW50ZXJmYWNlIERhdGEgZXh0ZW5kcyBFbnRyeSB7XG4gIHN0eWxlUHJvcGVydGllczogQXJyYXk8c3RyaW5nPjtcbn1cblxuZXhwb3J0IGNvbnN0IHN0eWxlRXh0cmFjdG9yID0gKGRhdGE6IERhdGEpOiBPdXRwdXQgPT4ge1xuICBjb25zdCB7IHNlbGVjdG9yLCBzdHlsZVByb3BlcnRpZXMgfSA9IGRhdGE7XG4gIGNvbnN0IHN0eWxlczogUmVjb3JkPHN0cmluZywgTGl0ZXJhbD4gPSB7fTtcbiAgY29uc3QgZWxlbWVudCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3Ioc2VsZWN0b3IpO1xuXG4gIGlmICghZWxlbWVudCkge1xuICAgIHJldHVybiBKU09OLnN0cmluZ2lmeSh7XG4gICAgICBlcnJvcjogYEVsZW1lbnQgd2l0aCBzZWxlY3RvciAke3NlbGVjdG9yfSBub3QgZm91bmRgXG4gICAgfSk7XG4gIH1cblxuICBjb25zdCBjb21wdXRlZFN0eWxlcyA9IGdldE5vZGVTdHlsZShlbGVtZW50KTtcblxuICBzdHlsZVByb3BlcnRpZXMuZm9yRWFjaCgoc3R5bGVOYW1lKSA9PiB7XG4gICAgc3R5bGVzW3N0eWxlTmFtZV0gPSBjb21wdXRlZFN0eWxlc1tzdHlsZU5hbWVdO1xuICB9KTtcblxuICByZXR1cm4gY3JlYXRlRGF0YSh7IGRhdGE6IHN0eWxlcyB9KTtcbn07XG4iXSwKICAibWFwcGluZ3MiOiAiOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7O0FBQUE7QUFBQTtBQUFBO0FBQUE7OztBQ3NDTyxNQUFNLGFBQWEsQ0FBQ0EsWUFBK0I7QUFDeEQsV0FBTyxLQUFLLFVBQVVBLE9BQU07QUFBQSxFQUM5Qjs7O0FDdENPLE1BQU0sZUFBZSxDQUMxQixTQUM0QjtBQUM1QixVQUFNLGlCQUFpQixPQUFPLGlCQUFpQixJQUFJO0FBQ25ELFVBQU0sU0FBa0MsQ0FBQztBQUV6QyxXQUFPLE9BQU8sY0FBYyxFQUFFLFFBQVEsQ0FBQyxRQUFRO0FBQzdDLGFBQU8sR0FBRyxJQUFJLGVBQWUsaUJBQWlCLEdBQUc7QUFBQSxJQUNuRCxDQUFDO0FBRUQsV0FBTztBQUFBLEVBQ1Q7OztBQ0pPLE1BQU0saUJBQWlCLENBQUNDLFVBQXVCO0FBQ3BELFVBQU0sRUFBRSxVQUFVLGdCQUFnQixJQUFJQTtBQUN0QyxVQUFNLFNBQWtDLENBQUM7QUFDekMsVUFBTSxVQUFVLFNBQVMsY0FBYyxRQUFRO0FBRS9DLFFBQUksQ0FBQyxTQUFTO0FBQ1osYUFBTyxLQUFLLFVBQVU7QUFBQSxRQUNwQixPQUFPLHlCQUF5QixRQUFRO0FBQUEsTUFDMUMsQ0FBQztBQUFBLElBQ0g7QUFFQSxVQUFNLGlCQUFpQixhQUFhLE9BQU87QUFFM0Msb0JBQWdCLFFBQVEsQ0FBQyxjQUFjO0FBQ3JDLGFBQU8sU0FBUyxJQUFJLGVBQWUsU0FBUztBQUFBLElBQzlDLENBQUM7QUFFRCxXQUFPLFdBQVcsRUFBRSxNQUFNLE9BQU8sQ0FBQztBQUFBLEVBQ3BDOzs7QUh4QkEsTUFBTSxVQUFVLE1BQVk7QUFDMUIsUUFBSTtBQUNGLGFBQU87QUFBQSxRQUNMLFVBQVU7QUFBQSxRQUNWLFVBQVUsS0FBSyxNQUFNLGNBQWM7QUFBQSxRQUNuQyxpQkFBaUIsS0FBSyxNQUFNLHFCQUFxQjtBQUFBLFFBQ2pELGVBQWU7QUFBQSxNQUNqQjtBQUFBLElBQ0YsU0FBUyxHQUFHO0FBQ1YsWUFBTSxhQUFhO0FBQUEsUUFDakIsTUFBTTtBQUFBLFFBQ04sUUFBUTtBQUFBLE1BQ1Y7QUFDQSxZQUFNLE9BQWM7QUFBQSxRQUNsQixVQUFVO0FBQUEsUUFDVixVQUFVO0FBQUEsUUFDVixlQUFlO0FBQUEsTUFDakI7QUFFQSxZQUFNLElBQUk7QUFBQSxRQUNSLEtBQUssVUFBVTtBQUFBLFVBQ2IsT0FBTyxnQkFBZ0IsQ0FBQztBQUFBLFVBQ3hCLFNBQVMsWUFBWSxLQUFLLFVBQVUsSUFBSSxDQUFDO0FBQUEsUUFDM0MsQ0FBQztBQUFBLE1BQ0g7QUFBQSxJQUNGO0FBQUEsRUFDRjtBQUVBLE1BQU0sT0FBTyxRQUFRO0FBQ3JCLE1BQU0sU0FBUyxlQUFlLElBQUk7QUFFbEMsTUFBTyx5QkFBUTsiLAogICJuYW1lcyI6IFsib3V0cHV0IiwgImRhdGEiXQp9Cg==
