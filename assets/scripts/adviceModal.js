class AdviceModal {
  constructor() {
    this.modal = document.querySelector(".modal-js");
    this.closeBtn = document.querySelector(".modal-close-js");

    this.form = document.querySelector(".modal-form-js");
    this.pseudoInput = document.querySelector(".modal-pseudo-js");
    this.adviceInput = document.querySelector(".modal-text-js");
    this.submitBtn = document.querySelector(".modal-submit-js");

    this.closeBtn.addEventListener("click", () => this.closeModal());

    this.pseudoInput.addEventListener("input", () => {
      if (this.pseudoInput.value.length > 0) {
        console.log(this.pseudoInput.value);

        if (this.pseudoInput.value.length > 5) {
          this.errorMessage(
            this.pseudoInput,
            "Le pseudo doit faire moins de 60 caractères"
          );
        }
      }
    });

    this.adviceInput.addEventListener("input", () => {
      if (this.adviceInput.value.length > 0) {
        console.log(this.adviceInput.value);
      }
    });
  }

  closeModal() {
    console.log(this.modal);
    this.modal.close();
  }

  checkInputs() {
    const adviceLength = this.adviceInput.value.length;
    const pseudoLength = this.pseudoInput.value.length;

    if (
      adviceLength > 0 &&
      adviceLength < 300 &&
      pseudoLength > 0 &&
      pseudoLength < 60
    ) {
      this.submitBtn.disabled = false;
    } else {
      this.submitBtn.disabled = true;
    }
  }
  errorMessage(element, message) {
    let error = this.pseudoInput.closest("input");
    console.log("error : ".error);
    if (error == null) {
      error = document.createElement("small");
      error.className = "pseudo-error-js text-red-500";
      error.textContent = message;
      element.insertAdjacentElement("afterEnd", error);
      return;
    }
  }
}

new AdviceModal();

` <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mt-10" role="alert">
        <span class="block sm:inline">Impossible d'envoyer votre avis</span>
    </div>`;

`        <svg class="mx-auto text-2xl " xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"><path stroke-dasharray="60" stroke-dashoffset="60" stroke-opacity="0.3" d="M12 3C16.9706 3 21 7.02944 21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3Z"><animate fill="freeze" attributeName="stroke-dashoffset" dur="1.3s" values="60;0"/></path><path stroke-dasharray="15" stroke-dashoffset="15" d="M12 3C16.9706 3 21 7.02944 21 12"><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.3s" values="15;0"/><animateTransform attributeName="transform" dur="1.5s" repeatCount="indefinite" type="rotate" values="0 12 12;360 12 12"/></path></g></svg>    </button>
`;
