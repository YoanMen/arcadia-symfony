/**
 * get data to create cards
 * manage pagination
 */

import { Pagination } from "./pagination.js";
import { Card } from "./card.js";

export default class CardsManager {
  constructor(fetchUrl, href, imagePath, className = "") {
    this.fetchUrl = fetchUrl;
    this.href = href;
    this.imagePath = imagePath;
    this.className = className;
    this.cardsContainer = document.querySelector(".cards-container-js");

    // search filter
    this.search = "";
    this.searchAnimals = false;
    this.searchInput = document.querySelector(".cards-search-js");
    this.searchSubmit = document.querySelector(".cards-search-submit-js");
    this.datalist = document.querySelector(".cards-search-datalist-js");
    this.searchForm = document.querySelector(".cards-search-form-js");

    this.initialize();
  }

  // initialize pagination and data
  initialize() {
    this.pagination = new Pagination(
      () => {
        this.nextPage();
      },
      () => {
        this.previousPage();
      },
      () => {
        this.upperPage();
      },
      () => {
        this.doubleUpperPage();
      }
    );

    // listen search input and create prediction depending input value
    if (this.searchInput) {
      this.searchInput.addEventListener("input", () => {
        this.search = this.searchInput.value;

        if (this.search.length != 0) {
          this.setPredictive();
        }
      });

      // search animals depending search input
      this.searchForm.addEventListener("submit", (event) => {
        event.preventDefault();
        if (this.search.length > 0) {
          this.searchAnimals = true;
          this.getDataForAnimals();
        }
      });
    }

    this.getData();
  }

  // search data by url and page
  async getData(page = 1) {
    try {
      this.showLoadingCards();

      const response = await fetch(`${this.fetchUrl}?page=${page}`, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      });

      const result = await response.json();

      if (!result.success) {
        if (result.error) {
          throw new Error(result.error);
        }

        throw new Error(result.detail);
      }

      this.showCards(result.data);

    } catch (error) {
      console.error(error);
    }
  }

  // replace getData with this animals versions when user use search filter
  async getDataForAnimals(page = 1) {
    try {
      this.showLoadingCards();

      const response = await fetch(
        `/api/animal/search?page=${page}&search=${this.search}`,
        {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        }
      );

      const result = await response.json();

      if (result.success) {
        this.showAnimalsCards(result.data);
      } else if (result.error) {
        throw new Error(result.error);
      } else {
        throw new Error(result.detail);
      }
    } catch (error) {
      console.error(error);
    }
  }

  // CALLBACK PAGINATION
  // fetch data depending if user search animal or navigate to habitat page.
  nextPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 1);
    const page = this.pagination.currentPage;
    this.searchAnimals ? this.getDataForAnimals(page) : this.getData(page);
  }

  previousPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage - 1);
    const page = this.pagination.currentPage;
    this.searchAnimals ? this.getDataForAnimals(page) : this.getData(page);
  }

  upperPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 1);

    const page = this.pagination.currentPage;
    this.searchAnimals ? this.getDataForAnimals(page) : this.getData(page);
  }

  doubleUpperPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 2);
    const page = this.pagination.currentPage;
    this.searchAnimals ? this.getDataForAnimals(page) : this.getData(page);
  }

  // Show Cards
  showCards(data) {
    this.cardsContainer.innerHTML = "";

    if (data.data.length != 0) {
      this.pagination.setTotalPages(data.totalPage);

      data.data.forEach((element) => {

        const card = new Card(
          `${this.imagePath}${element.image_name}`,
          element.alt,
          `${this.href}/${element.slug}`,
          element.name,
          element.description,
          this.className,
          element.id
        );

        // add card to container
        this.cardsContainer.appendChild(this.createCard(card));
      });

      this.listenButtonsForAnimalClick();
    } else {
      this.cardsContainer.innerHTML = "";
      this.pagination.remove();
    }
  }

  // replace show Card with this if user use search filter
  showAnimalsCards(data) {
    this.cardsContainer.innerHTML = "";

    if (data.data.length != 0) {
      // add class to animals buttons
      this.pagination.setTotalPages(data.totalPage);

      data.data.forEach((element) => {
        const card = new Card(
          `/images/animals/${element.image_name}`,
          element.alt,
          `/habitats/${element.habitat_slug}/${element.slug}`,
          element.name,
          element.description,
          "button-listen",
          element.id
        );

        // add card to container
        this.cardsContainer.appendChild(this.createCard(card));
      });

      this.listenButtonsForAnimalClick();
    } else {
      this.cardsContainer.innerHTML = "";
      this.pagination.remove();
    }
  }

  // set prediction depending user input to help find animals
  async setPredictive() {
    const response = await fetch("/api/animal/predictive", {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        search: this.searchInput.value,
      }),
    });

    const result = await response.json();

    if (result.success) {
      // some action
      this.datalist.innerHTML = "";

      for (const element of result.data.data) {
        const searchValue = this.searchInput.value.toLowerCase();
        const habitat = element.habitat.toLowerCase();
        const name = element.name.toLowerCase();
        const communName = element.commun_name.toLowerCase();

        const family = element.family.toLowerCase();
        const region = element.region.toLowerCase();

        if (habitat.includes(searchValue)) {
          this.createOptionPrediction(element.habitat);
          this.removeDuplicationForPrediction(element.habitat);
        }

        if (name.includes(searchValue)) {
          this.createOptionPrediction(element.name);
          this.removeDuplicationForPrediction(element.name);
        }

        if (communName.includes(searchValue)) {
          this.createOptionPrediction(element.commun_name);
          this.removeDuplicationForPrediction(element.commun_name);
        }
        if (family.includes(searchValue)) {
          this.createOptionPrediction(element.family);
          this.removeDuplicationForPrediction(element.family);
        }

        if (region.includes(searchValue)) {
          this.createOptionPrediction(element.region);
          this.removeDuplicationForPrediction(element.region);
        }
      }
    } else if (result.error) {
      throw new Error(result.error);
    } else {
      throw new Error(result.detail);
    }
  }

  async createOptionPrediction(element) {
    const option = document.createElement("option");
    option.text = element;
    option.id = element;
    this.datalist.appendChild(option);
  }

  removeDuplicationForPrediction(element) {
    const duplicate = this.datalist.querySelectorAll(`#${element}`);

    if (duplicate) {
      duplicate.forEach((element, key) => {
        if (key != 0) {
          element.remove();
        }
      });
    }
  }

  // when data is loaded use loading cards placeholder
  showLoadingCards() {
    this.cardsContainer.innerHTML = "";
    for (let index = 0; index < 6; index++) {
      const loadingElement = document.createElement("article");
      loadingElement.className =
        "size-80 max-md:w-full md:rounded  bg-slate-100 animate-pulse";

      this.cardsContainer.appendChild(loadingElement);
    }
  }

  // Used to get animal when user click
  listenButtonsForAnimalClick() {
    const buttons = document.querySelectorAll(".button-listen");

    buttons.forEach((button) => {
      let pressedBtn = false;
      button.addEventListener("click", async (event) => {
        if (pressedBtn) {
          return;
        }

        pressedBtn = true;

        event.preventDefault();
        const href = button.querySelector("a").href;
        const id = button.dataset.id;

        await fetch(`/api/clickToAnimal/${id}`, {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
          },
        }).finally(() => {
          window.location.href = href;
        });
      });
    });
  }

  createCard(card) {
    const articleElement = document.createElement("article");
    articleElement.className =
      "relative size-80 max-md:w-full md:rounded  first:rounded-t last:rounded-b border-8 bg-secondary cards overflow-clip group " +
      card.getClassName();

    const anchorElement = document.createElement("a");
    anchorElement.href = card.getHref();
    anchorElement.className = "hidden";
    const imageElement = document.createElement("img");
    imageElement.src = card.getImageName();
    imageElement.alt = card.getAlt();
    imageElement.className =
      "object-cover rounded-sm w-full h-full group-hover:scale-105 transition-all ease-in-out duration-150";

    imageElement.addEventListener("load", () => {
      anchorElement.classList.remove("hidden")
      anchorElement.classList.add("fade-in")
    })

    const textContainer = document.createElement("div");
    textContainer.className =
      "absolute rounded-sm bottom-0 flex flex-col justify-end w-full gradient-to-top p-2";

    const titleElement = document.createElement("h3");
    titleElement.textContent = card.getTitle();
    titleElement.className =
      "text-xl font-semibold uppercase text-primary";

    const descriptionElement = document.createElement("p");
    descriptionElement.textContent = card.getDescription();
    descriptionElement.className = "text-primary line-clamp-2";

    textContainer.appendChild(titleElement);
    textContainer.appendChild(descriptionElement);
    anchorElement.appendChild(imageElement);
    anchorElement.appendChild(textContainer);
    articleElement.appendChild(anchorElement);
    articleElement.dataset.id = card.getId();

    return articleElement;
  }
}
