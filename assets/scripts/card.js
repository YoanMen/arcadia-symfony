// Card
export class Card {
  constructor(imageName, alt, href, title, description, className = "") {
    this.imageName = imageName;
    this.alt = alt;
    this.href = href;
    this.title = title;
    this.description = description;
    this.className = className;
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
