document.addEventListener("DOMContentLoaded", () => {
  class Menu {
    constructor() {
      this.buttonMenu = document.querySelector(".menu-mobile-js");
      this.menuIcon = document.querySelector(".menu-mobile-icon-js");
      this.menuLogo = document.querySelector(".menu-logo-js");
      this.connectionBtn = document.querySelector(".connection-btn-js");
      this.menuLinks = document.querySelector(".menu-links-js");
      this.isActive = false;
      this.listeningClickToBtnMenu();
      this.checkIfHomePage();
    }

    listeningClickToBtnMenu() {
      this.buttonMenu.addEventListener("click", () => {
        this.toggleMobileMenu();
      });
    }

    toggleMobileMenu() {
      this.connectionBtn.classList.toggle("max-lg:hidden");
      this.menuLinks.classList.toggle("max-lg:hidden");
      this.menuIcon.classList.toggle("hidden");
    }

    transparentBackground() {
      this.menuIcon
        .closest("header")
        .classList.remove("border-b-[1px]", "border-color", "bg-primary");
      this.menuIcon
        .closest("header")
        .classList.add("bg-gradient-to-b", "from-black");
      this.menuLinks.classList.add("text-primary");
      this.menuLogo.src = "images/logo/arcadia_white.svg";
      this.menuIcon.parentElement.classList.add("fill-white");
    }

    disableTransparentBackground() {
      this.menuIcon
        .closest("header")
        .classList.add("bg-primary", "border-b-[1px]", "border-color");
      this.menuIcon
        .closest("header")
        .classList.remove("bg-gradient-to-b", "from-black");
      this.menuLinks.classList.remove("text-primary");
      this.menuLogo.src = "images/logo/arcadia_green.svg";
      this.menuIcon.parentElement.classList.remove("fill-white");
    }

    checkIfHomePage() {
      if (window.location.pathname === "/") {
        this.SetBackground();
        document.addEventListener("scroll", () => this.SetBackground());
      }
    }

    SetBackground() {
      let scrollPosition = window.scrollY;

      if (scrollPosition > 0) {
        this.disableTransparentBackground();
      } else {
        this.transparentBackground();
      }
    }
  }

  new Menu();
});
