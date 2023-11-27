"use strict";
var output = (() => {
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
    return output2;
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
  var styleExtractor = (entry) => {
    const { selector, styleProperties } = entry;
    const data2 = {};
    const element = document.querySelector(selector);
    if (!element) {
      return {
        error: `Element with selector ${selector} not found`
      };
    }
    const computedStyles = getNodeStyle(element);
    styleProperties.forEach((styleName) => {
      data2[styleName] = computedStyles[styleName];
    });
    return createData({ data: data2 });
  };

  // src/StyleExtractor/index.ts
  var getData = () => {
    try {
      return {
        selector: SELECTOR,
        families: FAMILIES,
        styleProperties: STYLE_PROPERTIES,
        defaultFamily: DEFAULT_FAMILY
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
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vc3JjL1N0eWxlRXh0cmFjdG9yL2luZGV4LnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy91dGlscy9nZXREYXRhLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL3V0aWxzL3NyYy9kb20vZ2V0Tm9kZVN0eWxlLnRzIiwgIi4uLy4uLy4uLy4uLy4uLy4uLy4uLy4uL3BhY2thZ2VzL2VsZW1lbnRzL3NyYy9TdHlsZUV4dHJhY3Rvci9pbmRleC50cyJdLAogICJzb3VyY2VzQ29udGVudCI6IFsiaW1wb3J0IHsgc3R5bGVFeHRyYWN0b3IsIERhdGEgfSBmcm9tIFwiZWxlbWVudHMvc3JjL1N0eWxlRXh0cmFjdG9yXCI7XG5pbXBvcnQgeyBFbnRyeSB9IGZyb20gXCJlbGVtZW50cy9zcmMvdHlwZXMvdHlwZVwiO1xuXG5jb25zdCBnZXREYXRhID0gKCk6IERhdGEgPT4ge1xuICB0cnkge1xuICAgIHJldHVybiB7XG4gICAgICBzZWxlY3RvcjogU0VMRUNUT1IsXG4gICAgICBmYW1pbGllczogRkFNSUxJRVMsXG4gICAgICBzdHlsZVByb3BlcnRpZXM6IFNUWUxFX1BST1BFUlRJRVMsXG4gICAgICBkZWZhdWx0RmFtaWx5OiBERUZBVUxUX0ZBTUlMWVxuICAgIH07XG4gIH0gY2F0Y2ggKGUpIHtcbiAgICBjb25zdCBmYW1pbHlNb2NrID0ge1xuICAgICAgbGF0bzogXCJ1aWRfZm9yX2xhdG9cIixcbiAgICAgIHJvYm90bzogXCJ1aWRfZm9yX3JvYm90b1wiXG4gICAgfTtcbiAgICBjb25zdCBtb2NrOiBFbnRyeSA9IHtcbiAgICAgIHNlbGVjdG9yOiBcIi5teS1kaXZcIixcbiAgICAgIGZhbWlsaWVzOiBmYW1pbHlNb2NrLFxuICAgICAgZGVmYXVsdEZhbWlseTogXCJsYXRvXCJcbiAgICB9O1xuXG4gICAgdGhyb3cgbmV3IEVycm9yKFxuICAgICAgSlNPTi5zdHJpbmdpZnkoe1xuICAgICAgICBlcnJvcjogYEludmFsaWQgSlNPTiAke2V9YCxcbiAgICAgICAgZGV0YWlsczogYE11c3QgYmU6ICR7SlNPTi5zdHJpbmdpZnkobW9jayl9YFxuICAgICAgfSlcbiAgICApO1xuICB9XG59O1xuXG5jb25zdCBkYXRhID0gZ2V0RGF0YSgpO1xuY29uc3Qgb3V0cHV0ID0gc3R5bGVFeHRyYWN0b3IoZGF0YSk7XG5cbmV4cG9ydCBkZWZhdWx0IG91dHB1dDtcbiIsICJpbXBvcnQgeyBFbnRyeSwgT3V0cHV0LCBPdXRwdXREYXRhIH0gZnJvbSBcIi4uL3R5cGVzL3R5cGVcIjtcblxuZXhwb3J0IGNvbnN0IGdldERhdGEgPSAoKTogRW50cnkgPT4ge1xuICB0cnkge1xuICAgIC8vIEZvciBkZXZlbG9wbWVudFxuICAgIC8vIHdpbmRvdy5pc0RldiA9IHRydWU7XG4gICAgcmV0dXJuIHdpbmRvdy5pc0RldlxuICAgICAgPyB7XG4gICAgICAgICAgc2VsZWN0b3I6IGBbZGF0YS1pZD0nJHs0MzQxNTc5fSddYCxcbiAgICAgICAgICBmYW1pbGllczoge1xuICAgICAgICAgICAgXCJwcm94aW1hX25vdmFfcHJveGltYV9ub3ZhX3JlZ3VsYXJfc2Fucy1zZXJpZlwiOiBcInVpZDExMTFcIixcbiAgICAgICAgICAgIFwiaGVsdmV0aWNhX25ldWVfaGVsdmV0aWNhbmV1ZV9oZWx2ZXRpY2FfYXJpYWxfc2Fucy1zZXJpZlwiOiBcInVpZDIyMjJcIlxuICAgICAgICAgIH0sXG4gICAgICAgICAgZGVmYXVsdEZhbWlseTogXCJsYXRvXCJcbiAgICAgICAgfVxuICAgICAgOiB7XG4gICAgICAgICAgc2VsZWN0b3I6IFNFTEVDVE9SLFxuICAgICAgICAgIGZhbWlsaWVzOiBGQU1JTElFUyxcbiAgICAgICAgICBkZWZhdWx0RmFtaWx5OiBERUZBVUxUX0ZBTUlMWVxuICAgICAgICB9O1xuICB9IGNhdGNoIChlKSB7XG4gICAgY29uc3QgZmFtaWx5TW9jayA9IHtcbiAgICAgIGxhdG86IFwidWlkX2Zvcl9sYXRvXCIsXG4gICAgICByb2JvdG86IFwidWlkX2Zvcl9yb2JvdG9cIlxuICAgIH07XG4gICAgY29uc3QgbW9jazogRW50cnkgPSB7XG4gICAgICBzZWxlY3RvcjogXCIubXktZGl2XCIsXG4gICAgICBmYW1pbGllczogZmFtaWx5TW9jayxcbiAgICAgIGRlZmF1bHRGYW1pbHk6IFwibGF0b1wiXG4gICAgfTtcblxuICAgIHRocm93IG5ldyBFcnJvcihcbiAgICAgIEpTT04uc3RyaW5naWZ5KHtcbiAgICAgICAgZXJyb3I6IGBJbnZhbGlkIEpTT04gJHtlfWAsXG4gICAgICAgIGRldGFpbHM6IGBNdXN0IGJlOiAke0pTT04uc3RyaW5naWZ5KG1vY2spfWBcbiAgICAgIH0pXG4gICAgKTtcbiAgfVxufTtcblxuZXhwb3J0IGNvbnN0IGNyZWF0ZURhdGEgPSAob3V0cHV0OiBPdXRwdXREYXRhKTogT3V0cHV0ID0+IHtcbiAgcmV0dXJuIG91dHB1dDtcbn07XG4iLCAiaW1wb3J0IHsgTGl0ZXJhbCB9IGZyb20gXCIuLi90eXBlc1wiO1xuXG5leHBvcnQgY29uc3QgZ2V0Tm9kZVN0eWxlID0gKFxuICBub2RlOiBIVE1MRWxlbWVudCB8IEVsZW1lbnRcbik6IFJlY29yZDxzdHJpbmcsIExpdGVyYWw+ID0+IHtcbiAgY29uc3QgY29tcHV0ZWRTdHlsZXMgPSB3aW5kb3cuZ2V0Q29tcHV0ZWRTdHlsZShub2RlKTtcbiAgY29uc3Qgc3R5bGVzOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPiA9IHt9O1xuXG4gIE9iamVjdC52YWx1ZXMoY29tcHV0ZWRTdHlsZXMpLmZvckVhY2goKGtleSkgPT4ge1xuICAgIHN0eWxlc1trZXldID0gY29tcHV0ZWRTdHlsZXMuZ2V0UHJvcGVydHlWYWx1ZShrZXkpO1xuICB9KTtcblxuICByZXR1cm4gc3R5bGVzO1xufTtcbiIsICJpbXBvcnQgeyBPdXRwdXQgfSBmcm9tIFwiLi4vdHlwZXMvdHlwZVwiO1xuaW1wb3J0IHsgY3JlYXRlRGF0YSB9IGZyb20gXCIuLi91dGlscy9nZXREYXRhXCI7XG5pbXBvcnQgeyBMaXRlcmFsIH0gZnJvbSBcInV0aWxzXCI7XG5pbXBvcnQgeyBnZXROb2RlU3R5bGUgfSBmcm9tIFwidXRpbHMvc3JjL2RvbS9nZXROb2RlU3R5bGVcIjtcblxuZXhwb3J0IGludGVyZmFjZSBEYXRhIHtcbiAgc2VsZWN0b3I6IHN0cmluZztcbiAgZmFtaWxpZXM6IFJlY29yZDxzdHJpbmcsIHN0cmluZz47XG4gIGRlZmF1bHRGYW1pbHk6IHN0cmluZztcbiAgc3R5bGVQcm9wZXJ0aWVzOiBBcnJheTxzdHJpbmc+O1xufVxuXG5leHBvcnQgY29uc3Qgc3R5bGVFeHRyYWN0b3IgPSAoZW50cnk6IERhdGEpOiBPdXRwdXQgPT4ge1xuICBjb25zdCB7IHNlbGVjdG9yLCBzdHlsZVByb3BlcnRpZXMgfSA9IGVudHJ5O1xuICBjb25zdCBkYXRhOiBSZWNvcmQ8c3RyaW5nLCBMaXRlcmFsPiA9IHt9O1xuICBjb25zdCBlbGVtZW50ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcihzZWxlY3Rvcik7XG5cbiAgaWYgKCFlbGVtZW50KSB7XG4gICAgcmV0dXJuIHtcbiAgICAgIGVycm9yOiBgRWxlbWVudCB3aXRoIHNlbGVjdG9yICR7c2VsZWN0b3J9IG5vdCBmb3VuZGBcbiAgICB9O1xuICB9XG5cbiAgY29uc3QgY29tcHV0ZWRTdHlsZXMgPSBnZXROb2RlU3R5bGUoZWxlbWVudCk7XG5cbiAgc3R5bGVQcm9wZXJ0aWVzLmZvckVhY2goKHN0eWxlTmFtZSkgPT4ge1xuICAgIGRhdGFbc3R5bGVOYW1lXSA9IGNvbXB1dGVkU3R5bGVzW3N0eWxlTmFtZV07XG4gIH0pO1xuXG4gIHJldHVybiBjcmVhdGVEYXRhKHsgZGF0YSB9KTtcbn07XG4iXSwKICAibWFwcGluZ3MiOiAiOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUFBQTtBQUFBO0FBQUE7QUFBQTs7O0FDd0NPLE1BQU0sYUFBYSxDQUFDQSxZQUErQjtBQUN4RCxXQUFPQTtBQUFBLEVBQ1Q7OztBQ3hDTyxNQUFNLGVBQWUsQ0FDMUIsU0FDNEI7QUFDNUIsVUFBTSxpQkFBaUIsT0FBTyxpQkFBaUIsSUFBSTtBQUNuRCxVQUFNLFNBQWtDLENBQUM7QUFFekMsV0FBTyxPQUFPLGNBQWMsRUFBRSxRQUFRLENBQUMsUUFBUTtBQUM3QyxhQUFPLEdBQUcsSUFBSSxlQUFlLGlCQUFpQixHQUFHO0FBQUEsSUFDbkQsQ0FBQztBQUVELFdBQU87QUFBQSxFQUNUOzs7QUNETyxNQUFNLGlCQUFpQixDQUFDLFVBQXdCO0FBQ3JELFVBQU0sRUFBRSxVQUFVLGdCQUFnQixJQUFJO0FBQ3RDLFVBQU1DLFFBQWdDLENBQUM7QUFDdkMsVUFBTSxVQUFVLFNBQVMsY0FBYyxRQUFRO0FBRS9DLFFBQUksQ0FBQyxTQUFTO0FBQ1osYUFBTztBQUFBLFFBQ0wsT0FBTyx5QkFBeUIsUUFBUTtBQUFBLE1BQzFDO0FBQUEsSUFDRjtBQUVBLFVBQU0saUJBQWlCLGFBQWEsT0FBTztBQUUzQyxvQkFBZ0IsUUFBUSxDQUFDLGNBQWM7QUFDckMsTUFBQUEsTUFBSyxTQUFTLElBQUksZUFBZSxTQUFTO0FBQUEsSUFDNUMsQ0FBQztBQUVELFdBQU8sV0FBVyxFQUFFLE1BQUFBLE1BQUssQ0FBQztBQUFBLEVBQzVCOzs7QUgzQkEsTUFBTSxVQUFVLE1BQVk7QUFDMUIsUUFBSTtBQUNGLGFBQU87QUFBQSxRQUNMLFVBQVU7QUFBQSxRQUNWLFVBQVU7QUFBQSxRQUNWLGlCQUFpQjtBQUFBLFFBQ2pCLGVBQWU7QUFBQSxNQUNqQjtBQUFBLElBQ0YsU0FBUyxHQUFHO0FBQ1YsWUFBTSxhQUFhO0FBQUEsUUFDakIsTUFBTTtBQUFBLFFBQ04sUUFBUTtBQUFBLE1BQ1Y7QUFDQSxZQUFNLE9BQWM7QUFBQSxRQUNsQixVQUFVO0FBQUEsUUFDVixVQUFVO0FBQUEsUUFDVixlQUFlO0FBQUEsTUFDakI7QUFFQSxZQUFNLElBQUk7QUFBQSxRQUNSLEtBQUssVUFBVTtBQUFBLFVBQ2IsT0FBTyxnQkFBZ0IsQ0FBQztBQUFBLFVBQ3hCLFNBQVMsWUFBWSxLQUFLLFVBQVUsSUFBSSxDQUFDO0FBQUEsUUFDM0MsQ0FBQztBQUFBLE1BQ0g7QUFBQSxJQUNGO0FBQUEsRUFDRjtBQUVBLE1BQU0sT0FBTyxRQUFRO0FBQ3JCLE1BQU0sU0FBUyxlQUFlLElBQUk7QUFFbEMsTUFBTyx5QkFBUTsiLAogICJuYW1lcyI6IFsib3V0cHV0IiwgImRhdGEiXQp9Cg==
