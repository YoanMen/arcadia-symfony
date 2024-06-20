document.addEventListener("DOMContentLoaded", () => {
  const map = document.querySelector("#map");

  setTimeout(() => {
    // refresh url
    map.src = map.src;
  }, 500);
});
