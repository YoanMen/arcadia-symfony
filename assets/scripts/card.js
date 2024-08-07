// Card
export class Card {
  constructor(imageName, alt, href, title, description, className = "", id = "") {
    this.imageName = imageName;
    this.alt = alt;
    this.href = href;
    this.title = title;
    this.description = description;
    this.className = className;
    this.id = id;
  }

  getId() {
    return this.id;
  }

  getImageName() {
    return this.imageName;
  }

  getAlt() {
    return this.alt;
  }

  getHref() {
    return this.href;
  }

  getTitle() {
    return this.title;
  }

  getDescription() {
    return this.description;
  }

  getClassName() {
    return this.className;
  }
}
