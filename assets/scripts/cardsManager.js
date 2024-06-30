// get data to create cards
// manage pagination

import { Pagination } from "./pagination.js";
import { Card } from "./card.js";

export default class CardsManager {
  constructor(fetchUrl, href, imagePath, className = "") {
    this.fetchUrl = fetchUrl;
    this.href = href;
    this.imagePath = imagePath;

    this.search = "";
    this.cardsContainer = document.querySelector(".cards-container-js");
    this.card = new Card(className);

    this.searchAnimal = false;
    this.searchInput = document.querySelector(".cards-search-js");
    this.searchSubmit = document.querySelector(".cards-search-submit-js");
    this.datalist = document.querySelector(".cards-search-datalist-js");
    this.searchForm = document.querySelector(".cards-search-form-js");

    this.initialize();
  }

  // initialise pagingation and
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
          this.searchAnimal = true;
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

      if (result.success) {
        this.showCards(result.data);
      } else if (result.error) {
        throw new Error(result.error);
      } else {
        throw new Error(result.detail);
      }
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
    this.searchAnimal ? this.getDataForAnimals(page) : this.getData(page);
  }

  previousPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage - 1);
    const page = this.pagination.currentPage;
    this.searchAnimal ? this.getDataForAnimals(page) : this.getData(page);
  }

  upperPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 1);

    const page = this.pagination.currentPage;
    this.searchAnimal ? this.getDataForAnimals(page) : this.getData(page);
  }

  doubleUpperPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 2);
    const page = this.pagination.currentPage;
    this.searchAnimal ? this.getDataForAnimals(page) : this.getData(page);
  }

  // Show Cards
  showCards(data) {
    this.cardsContainer.innerHTML = "";

    if (data.data.length != 0) {
      this.pagination.setTotalPages(data.totalPage);

      data.data.forEach((element) => {
        const newCard = this.card.createCard(
          `${this.imagePath}${element.image_name}`,
          element.alt,
          `${this.href}/${element.slug}`,
          element.name,
          element.description
        );

        // add card to container
        this.cardsContainer.appendChild(newCard);
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
      this.card.setClassName("button-listen");

      this.pagination.setTotalPages(data.totalPage);

      data.data.forEach((element) => {
        const newCard = this.card.createCard(
          `/images/animals/${element.image_name}`,
          element.alt,
          `/habitats/${element.habitat_slug}/${element.slug}`,
          element.name,
          element.description
        );

        // add card to container
        this.cardsContainer.appendChild(newCard);
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
    for (let index = 0; index < 5; index++) {
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
        const name = button.querySelector(".button-title").textContent;

        await fetch(`/api/clickToAnimal/${name}`, {
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
}
