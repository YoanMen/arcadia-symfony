class AdviceManager {
  constructor() {
    this.previousBtn = document.querySelectorAll(".advice-previous-js");
    this.nextBtn = document.querySelectorAll(".advice-next-js");
    this.container = document.querySelector(".advice-container-js");
    this.currentAdvice = 1;
    this.totalAdvice = 0;

    // listener user actions
    this.previousBtn.forEach((button) => {
      button.addEventListener("click", () => this.getPreviousAdvice());
    });
    this.nextBtn.forEach((button) => {
      button.addEventListener("click", () => this.getNextAdvice());
    });

    this.initialize();
  }

  async initialize() {
    this.totalAdvice = await this.getAdvicesCount();

    if (this.totalAdvice >= 1) {
      // enable next button if totalAdvice > 1
      if (this.totalAdvice > 1) {
        this.nextBtn.forEach((button) => (button.disabled = false));
      }

      this.getAdvice();
    } else {
      this.container.textContent = "Aucun avis, laissez le votre !";
      this.container.classList.add("flex", "items-center", "justify-center");
    }
  }

  getNextAdvice() {
    if (this.currentAdvice < this.totalAdvice) {
      this.currentAdvice++;

      this.getAdvice();
    }

    if (this.currentAdvice == this.totalAdvice) {
      this.nextBtn.forEach((button) => (button.disabled = true));
    }

    if (this.currentAdvice != 1) {
      this.previousBtn.forEach((button) => (button.disabled = false));
    }
  }

  getPreviousAdvice() {
    if (this.currentAdvice > 1) {
      this.currentAdvice--;

      this.getAdvice();
    }

    if (this.currentAdvice == 1) {
      this.previousBtn.forEach((button) => (button.disabled = true));
    }

    this.nextBtn.forEach((button) => (button.disabled = false));
  }

  async getAdvicesCount() {
    try {
      const response = await fetch("/api/advice/count", {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      });

      const result = await response.json();

      if (result.success) {
        return result.count;
      } else {
        throw new Error(result.error);
      }
    } catch (error) {
      console.error(error.message);
    }
  }

  async getAdvice() {
    this.showLoadingAdvice();

    try {
      const response = await fetch(`/api/advice/${this.currentAdvice}`, {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      });

      const result = await response.json();

      if (result.success) {
        this.showAdvice(result.data[0]);
      } else {
        throw new Error(result.error);
      }
    } catch (error) {
      console.error(error.message);
    }
  }

  showAdvice(advice) {
    this.container.innerHTML = "";

    let avatarContainer = document.createElement("div");
    avatarContainer.className = "flex gap-2 items-center mb-4";

    let avatar = document.createElement("img");
    avatar.className = "size-16";
    avatar.src = `https://api.dicebear.com/9.x/initials/svg?seed='${advice.pseudo}'`;
    avatar.alt = "avatar image";

    let pseudo = document.createElement("p");
    pseudo.className = "uppercase font-medium text-base font-montserrat";
    pseudo.textContent = advice.pseudo;

    avatarContainer.appendChild(avatar);
    avatarContainer.appendChild(pseudo);

    let textContainer = document.createElement("p");
    textContainer.textContent = advice.advice;

    this.container.appendChild(avatarContainer);
    this.container.appendChild(textContainer);
  }

  // create loading container
  showLoadingAdvice() {
    this.container.innerHTML = "";

    let avatarContainer = document.createElement("div");
    avatarContainer.className = "flex gap-2 items-center mb-4";

    let avatar = document.createElement("div");
    avatar.className = "animate-pulse size-16 bg-gray-100";

    let pseudo = document.createElement("div");
    pseudo.className = "animate-pulse h-4 w-40 bg-gray-100 rounded";

    avatarContainer.appendChild(avatar);
    avatarContainer.appendChild(pseudo);

    let textContainer = document.createElement("div");

    let adviceLineOne = document.createElement("div");
    adviceLineOne.classList =
      "animate-pulse h-4 w-full bg-gray-100 rounded mb-2";

    let adviceLineTwo = document.createElement("div");
    adviceLineTwo.classList =
      "animate-pulse h-4 w-full bg-gray-100 rounded mb-4 mb-2";

    let adviceLineThree = document.createElement("div");
    adviceLineThree.classList =
      "animate-pulse h-4 w-full bg-gray-100 rounded mb-4 mb-2";

    textContainer.appendChild(adviceLineOne);
    textContainer.appendChild(adviceLineTwo);
    textContainer.appendChild(adviceLineThree);

    this.container.appendChild(avatarContainer);
    this.container.appendChild(textContainer);
  }
}

new AdviceManager();
