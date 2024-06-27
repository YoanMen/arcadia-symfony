/**
 * Class to create a carousel with option
 *
 * autoplay autoplay carousel
 * no-interaction disable show image modal
 * hide-buttons disable control of carousel
 */

export class Carousel {
  constructor(carousel, delay = 5000) {
    this.carousel = carousel;
    this.container = this.carousel.querySelector(".carousel-items");
    this.items;
    this.currentItem = 0;
    this.isScrolling = false;
    this.animationAutoplay;
    this.delay = delay;
    this.autoplay;

    // initialize
    this.setCarousel();
  }

  setCarousel() {
    // get slides
    this.items = this.getItems();
    this.autoplay = this.carousel.hasAttribute("autoplay");
    const hideButton = this.carousel.hasAttribute("hide-buttons");
    const noInteraction = this.carousel.hasAttribute("no-interaction");

    // hide buttons if hide button params is set or if items is lower than 2
    if (hideButton || this.items.length <= 1) {
      this.carousel.querySelector(".carousel-control").classList.add("hidden");
      return;
    }

    // autoplay carousel if autoplay params is set and items is > 1
    if (this.autoplay && this.items.length > 1) {
      this.autoplayScroll();

      this.container.addEventListener("scroll", () => {
        if (screen.width < 648) {
          clearTimeout(this.animationAutoplay);
          this.autoplay = false;
        } else {
          this.autoplay = true;
        }
      });
    }

    if (this.items.length > 1) {
      const cloneImage = this.items[0].cloneNode(true);
      cloneImage.classList.add("last:max-sm:hidden");

      this.container.appendChild(cloneImage);
    }

    // disable interaction if no interaction is set
    if (noInteraction) this.disableInteractionImage();

    // listen buttons
    this.previous();
    this.next();
  }

  // get all images of carousel
  getItems() {
    const items = this.carousel.querySelector(".carousel-items").children;
    return items;
  }

  // previous item
  previous() {
    const button = this.carousel.querySelector(".carousel-control").children[0];

    button.classList.remove("hidden");
    button.addEventListener("click", (e) => {
      e.stopPropagation();
      if (this.autoplay) clearTimeout(this.animationAutoplay);

      this.changeSlide(this.currentItem - 1);
    });
  }

  // next item
  next() {
    const button = this.carousel.querySelector(".carousel-control").children[1];

    button.classList.remove("hidden");
    button.addEventListener("click", (e) => {
      e.stopPropagation();

      if (this.autoplay) clearTimeout(this.animationAutoplay);

      this.changeSlide(this.currentItem + 1);
    });
  }

  // change slide
  changeSlide(index) {
    if (this.isScrolling) return;
    this.isScrolling = true;
    this.currentItem = index;

    if (index > this.items.length - 1) {
      // scroll instant to the first image
      this.container.scrollTo({
        behavior: "instant",
        left: this.items[0].offsetLeft,
      });
      this.currentItem = 1;
    } else if (index < 0) {
      // scroll instant to the last image
      this.container.scrollTo({
        behavior: "instant",
        left: this.items[this.items.length - 1].offsetLeft,
      });

      this.currentItem = this.items.length - 2;
    }

    // scroll to new image
    this.scrollToItem();

    setTimeout(() => {
      this.isScrolling = false;
    }, 500);

    if (this.autoplay) this.autoplayScroll();
  }

  scrollToItem() {
    this.container.scrollTo({
      behavior: "smooth",
      left: this.items[this.currentItem].offsetLeft,
    });
  }

  autoplayScroll() {
    this.animationAutoplay = setTimeout(() => {
      this.changeSlide(this.currentItem + 1);
    }, this.delay);
  }

  disableInteractionImage() {
    for (const item of this.items) {
      item.classList.remove("image");
    }
  }
}

const carousels = document.querySelectorAll(".carousel");
carousels.forEach((carousel) => {
  new Carousel(carousel);
});
