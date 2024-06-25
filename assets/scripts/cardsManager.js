// récupéré les data
// crée la pagination
// faire des callback afin de géré la pagination
//

import { Pagination } from "./pagination.js";
import { Card } from "./card.js";

export default class CardsManager {
  constructor(fetchUrl, href, imagePath) {
    this.fetchUrl = fetchUrl;
    this.href = href;
    this.imagePath = imagePath;

    this.search = "";
    this.cardsContainer = document.querySelector(".cards-container-js");
    this.card = new Card();

    this.searchAnimal = false;
    this.searchInput = document.querySelector(".cards-search-js");
    this.searchSubmit = document.querySelector(".cards-search-submit-js");
    this.datalist = document.querySelector(".cards-search-datalist-js");
    this.searchForm = document.querySelector(".cards-search-form-js");

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

    if (this.searchInput) {
      this.searchInput.addEventListener("input", () => {
        // event
        this.search = this.searchInput.value;
        // search

        if (this.search.length != 0) {
          this.setPredictive();
        }
      });

      this.searchForm.addEventListener("submit", (event) => {
        event.preventDefault();
        this.searchAnimal = true;
        this.getDataForAnimal();
      });
    }

    this.getData();
  }

  async getData(page = 1) {
    try {
      const response = await fetch(`${this.fetchUrl}?page=${page}`, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      });

      const result = await response.json();

      if (result.success) {
        // some action
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

  async getDataForAnimal(page = 1) {
    try {
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

  nextPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 1);
    const page = this.pagination.currentPage;
    this.searchAnimal ? this.getDataForAnimal(page) : this.getData(page);
  }

  previousPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage - 1);
    const page = this.pagination.currentPage;
    this.searchAnimal ? this.getDataForAnimal(page) : this.getData(page);
  }

  upperPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 1);

    const page = this.pagination.currentPage;
    this.searchAnimal ? this.getDataForAnimal(page) : this.getData(page);
  }

  doubleUpperPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 2);
    const page = this.pagination.currentPage;
    this.searchAnimal ? this.getDataForAnimal(page) : this.getData(page);
  }

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
    } else {
      this.cardsContainer.remove();
      this.pagination.remove();
    }
  }

  showAnimalsCards(data) {
    this.cardsContainer.innerHTML = "";

    if (data.data.length != 0) {
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
    } else {
      this.cardsContainer.innerHTML = "";
      this.pagination.remove();
    }
  }

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
}
