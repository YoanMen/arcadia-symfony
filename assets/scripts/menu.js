document.addEventListener("DOMContentLoaded", () => {
  const buttonMenu = document.querySelector(".menu-mobile-js");
  const menuIcon = document.querySelector(".menu-mobile-icon-js");
  const menuLogo = document.querySelector(".menu-logo-js");
  const connectionBtn = document.querySelector(".connection-btn-js");
  const menuLinks = document.querySelector(".menu-links-js");
  let isActive = false;

  listeningClickToBtnMenu();
  checkIfHomePage();

  function listeningClickToBtnMenu() {
    buttonMenu.addEventListener("click", () => {
      toggleMobileMenu();
    });
  }

  function toggleMobileMenu() {
    connectionBtn.classList.toggle("max-lg:hidden");
    document.body.classList.toggle("disable-scroll");
    menuLinks.classList.toggle("max-lg:hidden");
    menuIcon.classList.toggle("hidden");
  }

  function transparentBackground() {
    menuIcon.closest("header").classList.remove("border-b-color", "bg-primary");
    menuIcon.closest("header").classList.add("bg-gradient-to-b", "from-black");
    menuLinks.classList.add("text-primary");
    menuLogo.src = "images/logo/arcadia_white.svg";
    menuIcon.parentElement.classList.add("fill-white");
  }

  function disableTransparentBackground() {
    menuIcon.closest("header").classList.add("bg-primary", "border-b-color");
    menuIcon
      .closest("header")
      .classList.remove("bg-gradient-to-b", "from-black");
    menuLinks.classList.remove("text-primary");
    menuLogo.src = "images/logo/arcadia_green.svg";
    menuIcon.parentElement.classList.remove("fill-white");
  }

  function checkIfHomePage() {
    if (window.location.pathname === "/") {
      SetBackground();
      document.addEventListener("scroll", () => SetBackground());
    }
  }

  function SetBackground() {
    let scrollPosition = window.scrollY;

    if (scrollPosition > 0) {
      disableTransparentBackground();
    } else {
      transparentBackground();
    }
  }
});
