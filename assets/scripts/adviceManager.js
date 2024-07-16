const previousBtn = document.querySelectorAll(".advice-previous-js");
const nextBtn = document.querySelectorAll(".advice-next-js");
const container = document.querySelector(".advice-container-js");

let currentAdvice = 1;
let totalAdvice = 0;

// listener user actions
previousBtn.forEach((button) => {
  button.addEventListener("click", () => getPreviousAdvice());
});

nextBtn.forEach((button) => {
  button.addEventListener("click", () => getNextAdvice());
});

initialize();

// fetch data and update statut buttons , show message for no advice or advice depending advices count.
async function initialize() {
  totalAdvice = await getAdvicesCount();

  if (totalAdvice >= 1) {
    // enable next button if totalAdvice > 1
    if (totalAdvice > 1) {
      nextBtn.forEach((button) => (button.disabled = false));
    }

    getAdvice();
  } else {
    showError("Aucun avis, laissez le votre !")
  }
}

// function to next advice, set button status depending current advice numbers
function getNextAdvice() {
  if (currentAdvice < totalAdvice) {
    currentAdvice++;

    getAdvice();
  }

  if (currentAdvice == totalAdvice) {
    nextBtn.forEach((button) => (button.disabled = true));
  }

  if (currentAdvice != 1) {
    previousBtn.forEach((button) => (button.disabled = false));
  }
}

// function to previous advice, set button status depending current advice numbers
function getPreviousAdvice() {
  if (currentAdvice > 1) {
    currentAdvice--;

    getAdvice();
  }

  if (currentAdvice == 1) {
    previousBtn.forEach((button) => (button.disabled = true));
  }

  nextBtn.forEach((button) => (button.disabled = false));
}

// get total advices
async function getAdvicesCount() {
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

    showError(error.message)
  }
}

// fetch advice from API
async function getAdvice() {
  showLoadingAdvice();

  try {
    const response = await fetch(`/api/advice/${currentAdvice}`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });

    if(!response.ok) {
      throw new Error('Impossible de récupérer l\'avis, une erreur interne est survenu');
    }

    const result = await response.json();

    if (result.success) {
      showAdvice(result.data[0]);
    } else {
      throw new Error(result.error);
    }
  } catch (error) {
    showError(error.message)
  }
}

// Create advice with data fetched
function showAdvice(advice) {
  container.innerHTML = "";

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

  container.appendChild(avatarContainer);
  container.appendChild(textContainer);
}

function showError(error) {
  
  container.textContent = error;
  container.classList.add("flex", "items-center", "justify-center");
}

// create loading container when data is fetching
function showLoadingAdvice() {
  container.innerHTML = "";

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
  adviceLineOne.classList = "animate-pulse h-4 w-full bg-gray-100 rounded mb-2";

  let adviceLineTwo = document.createElement("div");
  adviceLineTwo.classList =
    "animate-pulse h-4 w-full bg-gray-100 rounded mb-4 mb-2";

  let adviceLineThree = document.createElement("div");
  adviceLineThree.classList =
    "animate-pulse h-4 w-full bg-gray-100 rounded mb-4 mb-2";

  textContainer.appendChild(adviceLineOne);
  textContainer.appendChild(adviceLineTwo);
  textContainer.appendChild(adviceLineThree);

  container.appendChild(avatarContainer);
  container.appendChild(textContainer);
}
