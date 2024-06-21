class Card {
  constructor(imageUrl, alt, href, title, description) {
    this.imageUrl = imageUrl;
    this.alt = alt;
    this.href = href;
    this.title = title;
    this.description = description;

    this.createCard();
  }

  createCard() {
    const article = document.createElement("article");
    anchor.href = this.href;

    const anchor = document.createElement("a");
    anchor.href = this.href;
    anchor.className =
      "relative size-80 max-md:w-full md:rounded  first:rounded-t last:rounded-b border-8 bg-secondary cards overflow-clip group";

    const image = document.createElement("img");
    image.src = this.imageUrl;
    image.alt = this.alt;
    image.className =
      "object-cover rounded-sm w-full h-full group-hover:scale-105 transition-transform ease-in-out duration-150";

    const textContainer = document.createElement("div");
    textContainer.className =
      "absolute rounded-sm bottom-0 flex flex-col justify-end w-full bg-gradient-to-t from-black p-2";

    const title = document.createElement("h3");
    title.textContent = this.title;
    title.className = "text-xl font-semibold uppercase text-primary";

    const description = document.createElement("p");
    description.textContent = this.description;
    description.className = "text-primary line-clamp-2";

    textContainer.appendChild(title, description);
    anchor.appendChild(textContainer);
    article.appendChild(anchor);
  }
}
