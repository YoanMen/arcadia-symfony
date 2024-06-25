export class Pagination {
  constructor(nextPage, previousPage, upperPage, doubleUpperPage) {
    this.currentPage = 1;
    this.totalPage = 1;

    this.container = document.querySelector(".pagination-container-js");
    this.nextBtn = document.querySelector(".pagination-next-js");
    this.previousBtn = document.querySelector(".pagination-previous-js");
    this.upperBtn = document.querySelector(".pagination-upper-js");
    this.doubleUpperBtn = document.querySelector(".pagination-double-upper-js");

    this.nextBtn.addEventListener("click", () => nextPage());
    this.previousBtn.addEventListener("click", () => previousPage());
    this.upperBtn.addEventListener("click", () => upperPage());
    this.doubleUpperBtn.addEventListener("click", () => doubleUpperPage());
  }

  setCurrentPage(page) {
    this.currentPage = page;

    this.setButtonsInteraction();
  }

  setTotalPages(page) {
    this.totalPage = page;
    this.setButtonsInteraction();
  }

  remove() {
    this.container.remove();
  }

  setButtonsInteraction() {
    // manage previous button
    if (this.currentPage - 1 >= 1) {
      this.previousBtn.classList.remove("hidden");

      this.previousBtn.disabled = false;
    } else {
      this.previousBtn.disabled = false;
      this.previousBtn.classList.add("hidden");
    }

    // manage nextBtn
    if (this.currentPage + 1 > this.totalPage) {
      this.nextBtn.disabled = true;
      this.nextBtn.classList.add("hidden");
    } else {
      this.nextBtn.disabled = false;
      this.nextBtn.classList.remove("hidden");
    }

    // manage upper
    if (this.currentPage + 1 > this.totalPage) {
      this.upperBtn.classList.add("hidden");
      this.upperBtn.disabled = true;
    } else {
      this.upperBtn.textContent = this.currentPage + 1;
      this.upperBtn.disabled = false;
      this.upperBtn.classList.remove("hidden");
    }

    // manage double upper
    if (this.currentPage + 2 > this.totalPage) {
      this.doubleUpperBtn.classList.add("hidden");
      this.doubleUpperBtn.disabled = true;
    } else {
      this.doubleUpperBtn.textContent = this.currentPage + 2;
      this.doubleUpperBtn.disabled = false;
      this.doubleUpperBtn.classList.remove("hidden");
    }
  }
}
