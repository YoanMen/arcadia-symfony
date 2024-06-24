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

    this.search;
    this.searchInput = document.querySelector(".cards-search-js");
    this.cardsContainer = document.querySelector(".cards-container-js");
    this.card = new Card();
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
      });
    }
    this.getData();
  }

  async getData(page = 1) {
    let url = "";
    if (this.search) {
      url = `${this.fetchUrl}?page=${page}&?search=${this.search}`;
    } else {
      url = `${this.fetchUrl}?page=${page}`;
    }
    const response = await fetch(url, {
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

    this.isSendAdvice = false;
  }
  catch(error) {
    console.error(error);
  }

  nextPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 1);
  }

  previousPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage - 1);
  }

  upperPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 1);
  }

  doubleUpperPage() {
    this.pagination.setCurrentPage(this.pagination.currentPage + 2);
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
}
