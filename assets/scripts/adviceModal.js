class AdviceModal {
  constructor() {
    this.modal = document.querySelector(".modal-js");
    this.showBtn = document.querySelector(".modal-show-js");
    this.closeBtn = document.querySelector(".modal-close-js");

    this.form = document.querySelector(".modal-form-js");
    this.pseudoInput = document.querySelector(".modal-pseudo-js");
    this.adviceInput = document.querySelector(".modal-text-js");
    this.submitBtn = document.querySelector(".modal-submit-js");
    this.csrfToken = document.querySelector(".modal-csrf-js");

    this.showBtn.addEventListener("click", () => this.showAdviceModal());
    this.closeBtn.addEventListener("click", () => this.closeModal());

    this.pseudoInput.addEventListener("input", () => this.checkInputs());
    this.adviceInput.addEventListener("input", () => this.checkInputs());
    this.isSendAdvice = false;

    this.form.addEventListener("submit", (event) => {
      event.preventDefault();

      if (!this.submitBtn.hasAttribute("disabled") && !this.isSendAdvice) {
        this.sendAdvice();
      }
    });
  }

  showAdviceModal() {
    document.body.classList.add("disable-scroll");
    this.modal.showModal();
  }

  closeModal() {
    document.body.classList.remove("disable-scroll");
    this.modal.close();
  }

  checkInputs() {
    const adviceLength = this.adviceInput.value.length;
    const pseudoLength = this.pseudoInput.value.length;

    let pseudoIsValid = false;
    let adviceIsValid = false;

    if (pseudoLength > 0) {
      if (pseudoLength > 60) {
        this.errorMessage(
          this.pseudoInput,
          "Le pseudo doit faire moins de 60 caractères"
        );
      } else if (pseudoLength < 3) {
        this.errorMessage(
          this.pseudoInput,
          "Le pseudo doit faire au minimum 3 caractères"
        );
      } else {
        pseudoIsValid = true;
        this.removeError(this.pseudoInput);
      }
    }

    if (adviceLength > 0) {
      if (adviceLength > 300) {
        this.errorMessage(
          this.adviceInput,
          "L'avis doit faire moins de 300 caractères"
        );
      } else if (adviceLength < 10) {
        this.errorMessage(
          this.adviceInput,
          "L'avis doit faire au minimum 10 caractères"
        );
      } else {
        adviceIsValid = true;
        this.removeError(this.adviceInput);
      }
    }
    if (adviceIsValid && pseudoIsValid) {
      this.submitBtn.disabled = false;
    } else {
      this.submitBtn.disabled = true;
    }
  }

  removeError(element) {
    const error = element.nextElementSibling;
    if (error.classList.contains("input-error-js")) {
      error.remove();
    }
  }

  errorMessage(element, message) {
    const nextElement = element.nextElementSibling;

    if (!nextElement.classList.contains("input-error-js")) {
      const error = document.createElement("small");

      error.className = "input-error-js text-red-500";
      error.textContent = message;
      element.insertAdjacentElement("afterEnd", error);
    }
  }

  alertMessage(message) {
    let alert = this.modal.querySelector(".modal-message-js");

    if (!alert) {
      alert = document.createElement("div");
      alert.className =
        "modal-message-js bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-10";

      const alertMessage = document.createElement("span");
      alertMessage.className = "block sm:inline";
      alertMessage.textContent = message;

      alert.appendChild(alertMessage);
      this.form.insertAdjacentElement("afterbegin", alert);
    } else {
      const newMessage = alert.closest("span");
      newMessage.textContent = message;
    }
  }

  successMessage(message) {
    let alert = this.modal.querySelector(".modal-message-js");

    if (!alert) {
      alert = document.createElement("div");
      alert.className =
        "modal-message-js bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-10";

      const alertMessage = document.createElement("span");
      alertMessage.className = "block sm:inline";
      alertMessage.textContent = message;

      alert.appendChild(alertMessage);
      this.form.insertAdjacentElement("afterbegin", alert);
    } else {
      const newMessage = alert.closest("span");
      newMessage.textContent = message;
    }
  }

  async sendAdvice() {
    this.isSendAdvice = true;

    // show loading icon
    this.submitBtn.innerHTML =
      '<svg class="mx-auto text-2xl " xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"><path stroke-dasharray="60" stroke-dashoffset="60" stroke-opacity="0.3" d="M12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3Z"><animate fill="freeze" attributeName="stroke-dashoffset" dur="1.3s" values="60;0"/></path><path stroke-dasharray="15" stroke-dashoffset="15" d="M12 3C16.9706 3 21 7.02944 21 12"><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.3s" values="15;0"/><animateTransform attributeName="transform" dur="1.5s" repeatCount="indefinite" type="rotate" values="0 12 12;360 12 12"/></path></g></svg>';
    try {
      const response = await fetch("/api/advice", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({
          pseudo: this.pseudoInput.value,
          advice: this.adviceInput.value,
          csrf: this.csrfToken.value,
        }),
      });

      const result = await response.json();

      if (result.success) {
        this.successMessage("merci d'avoir laissé votre avis sur Arcadia !");
        this.disableFormInputs();
      } else if (result.error) {
        throw new Error(result.error);
      } else {
        throw new Error(result.detail);
      }

      this.isSendAdvice = false;
    } catch (error) {
      this.alertMessage(error);
    }
  }

  disableFormInputs() {
    this.submitBtn.disabled = true;
    this.pseudoInput.disabled = true;
    this.adviceInput.disabled = true;
    this.submitBtn.textContent = "avis envoyé !";
  }
}

new AdviceModal();
