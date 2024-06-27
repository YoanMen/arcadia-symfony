export class Card {
  constructor(className = "") {
    this.className = className;
  }

  setClassName(className) {
    this.className = className;
  }

  createCard(imageName, alt, href, title, description) {
    const articleElement = document.createElement("article");
    articleElement.className =
      "relative size-80 max-md:w-full md:rounded  first:rounded-t last:rounded-b border-8 bg-secondary cards overflow-clip group " +
      this.className;

    const anchorElement = document.createElement("a");
    anchorElement.href = href;

    const imageElement = document.createElement("img");
    imageElement.src = imageName;
    imageElement.alt = alt;
    imageElement.className =
      "object-cover rounded-sm w-full h-full group-hover:scale-105 transition-all ease-in-out duration-150";

    const textContainer = document.createElement("div");
    textContainer.className =
      "absolute rounded-sm bottom-0 flex flex-col justify-end w-full gradient-to-top p-2";

    const titleElement = document.createElement("h3");
    titleElement.textContent = title;
    titleElement.className =
      "button-title text-xl font-semibold uppercase text-primary";

    const descriptionElement = document.createElement("p");
    descriptionElement.textContent = description;
    descriptionElement.className = "text-primary line-clamp-2";

    textContainer.appendChild(titleElement);
    textContainer.appendChild(descriptionElement);
    anchorElement.appendChild(imageElement);
    anchorElement.appendChild(textContainer);
    articleElement.appendChild(anchorElement);

    return articleElement;
  }
}
