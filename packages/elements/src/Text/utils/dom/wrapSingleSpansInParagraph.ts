import { appendNodeStyles } from "./appendNodeStyles";

const getSiblingTextAlign = (sibling: Element | null) =>
  sibling instanceof HTMLElement &&
  sibling.tagName === "P" &&
  sibling.style.textAlign
    ? sibling.style.textAlign
    : null;

function wrapSpansInParagraph(parent: Element, spanGroup: HTMLElement[]): void {
  if (!spanGroup.length) return;

  const paragraph = document.createElement("p");
  parent.insertBefore(paragraph, spanGroup[0]); // Insert the new <p> element before the first span

  // Move spans into the paragraph
  spanGroup.forEach((span) => {
    paragraph.appendChild(span);
    appendNodeStyles(span, paragraph);
  });

  // Set the paragraph's text-align based on the previous or next sibling, because the spans have wrong text-align
  const previousSiblingAlign = getSiblingTextAlign(
    paragraph.previousElementSibling
  );
  const nextSiblingAlign = getSiblingTextAlign(paragraph.nextElementSibling);

  if (previousSiblingAlign) {
    paragraph.style.textAlign = previousSiblingAlign;
  } else if (nextSiblingAlign) {
    paragraph.style.textAlign = nextSiblingAlign;
  }
}

export function wrapSingleSpansInParagraph(container: Element): Element {
  const spanGroup: HTMLElement[] = [];

  Array.from(container.children).forEach((node) => {
    if (node.tagName === "SPAN") {
      // Add to the span group
      spanGroup.push(node as HTMLElement);
    } else {
      // Wrap collected spans and reset
      wrapSpansInParagraph(container, spanGroup);
      spanGroup.length = 0; // Reset the array
    }
  });

  if (spanGroup.length) {
    // Wrap any remaining spans at the end
    wrapSpansInParagraph(container, spanGroup);
  }

  return container;
}
